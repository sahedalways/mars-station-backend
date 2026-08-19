<?php

namespace App\Http\Controllers;

use App\Enums\AgreementStatus;
use App\Enums\MilestoneStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Mail\PaymentActionRequiredMail;
use App\Mail\PaymentFailedMail;
use App\Mail\PaymentRefundFailedMail;
use App\Mail\PaymentRefundedMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\SubscribeCancelledMail;
use App\Mail\SubscribeStartedMail;
use App\Models\Agreement;
use App\Models\AgreementMilestone;
use App\Models\AgreementSubscription;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\PaymentRefund;
use App\Services\ActivityLogService;
use App\Services\EmailService;
use App\Services\StripeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, StripeService $stripe, ActivityLogService $logs): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (! $signature || ! $stripe->isConfigured()) {
            return response()->json(['error' => 'Invalid signature or configuration'], 400);
        }

        try {
            $event = $stripe->constructEvent(
                $payload,
                $signature,
                (string) config('mars.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $existing = PaymentEvent::where('stripe_event_id', $event->id)->exists();

        if ($existing) {
            return response()->json(['received' => true, 'duplicate' => true]);
        }

        PaymentEvent::create([
            'stripe_event_id' => $event->id,
            'type' => $event->type,
            'payload' => $event->toArray(),
            'processed_at' => now(),
        ]);

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event, $logs),
            'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($event),
            'payment_intent.payment_failed' => $this->handlePaymentIntentFailed($event),
            'invoice.paid' => $this->handleInvoicePaid($event, $logs),
            'invoice.payment_failed' => $this->handleInvoicePaymentFailed($event),
            'invoice.payment_action_required' => $this->handleInvoicePaymentActionRequired($event, $logs),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event),
            'charge.refunded' => $this->handleChargeRefunded($event, $logs),
            'refund.created' => $this->handleRefundCreated($event),
            'refund.updated' => $this->handleRefundUpdated($event),
            'refund.failed' => $this->handleRefundFailed($event, $logs),
            default => null,
        };

        return response()->json(['received' => true]);
    }

    // ---------------------------------------------------------------
    // checkout.session.completed
    // ---------------------------------------------------------------

    private function handleCheckoutCompleted($event, ActivityLogService $logs): void
    {
        $session = $event->data->object;

        if ($session->mode === 'subscription') {
            $this->handleSubscriptionCheckout($session, $logs);

            return;
        }

        $agreementId = (int) ($session->metadata->agreement_id ?? $session->client_reference_id ?? 0);
        $paymentType = $session->metadata->payment_type ?? 'full';
        $milestoneId = (int) ($session->metadata->milestone_id ?? 0);

        $agreement = Agreement::find($agreementId);

        if (! $agreement) {
            return;
        }

        $paymentIntent = $session->payment_intent ?? null;
        $paymentIntentId = is_string($paymentIntent) ? $paymentIntent : ($paymentIntent->id ?? null);

        $amountPence = $session->amount_total ?? 0;

        $payment = Payment::firstOrCreate(
            ['stripe_payment_intent_id' => $paymentIntentId],
            [
                'agreement_id' => $agreement->id,
                'version_id' => (int) ($session->metadata->version_id ?? $agreement->currentVersion?->id),
                'type' => $paymentType,
                'milestone_id' => $milestoneId ?: null,
                'amount_pence' => $amountPence,
                'currency' => $session->currency ?? 'gbp',
                'status' => $session->payment_status === 'paid' ? PaymentStatus::Succeeded : PaymentStatus::Pending,
                'metadata' => $session->toArray(),
                'paid_at' => $session->payment_status === 'paid' ? now() : null,
            ]
        );

        if ($payment->wasRecentlyCreated && $payment->isSuccessful()) {
            $this->applySuccess($payment, $agreement, $paymentType, $milestoneId, $logs);
        }
    }

    private function handleSubscriptionCheckout($session, ActivityLogService $logs): void
    {
        $agreementId = (int) ($session->metadata->agreement_id ?? $session->client_reference_id ?? 0);
        $agreement = Agreement::find($agreementId);

        if (! $agreement) {
            return;
        }

        $subscription = $session->subscription;
        $subscriptionId = is_string($subscription) ? $subscription : ($subscription->id ?? null);

        if (! $subscriptionId) {
            return;
        }

        $existing = AgreementSubscription::where('stripe_subscription_id', $subscriptionId)->first();

        $config = $agreement->currentVersion?->payment_config ?? [];

        $record = $existing ?? AgreementSubscription::create([
            'agreement_id' => $agreement->id,
            'version_id' => (int) ($session->metadata->version_id ?? $agreement->currentVersion?->id),
            'title' => $config['title'] ?? $agreement->title,
            'amount_pence' => (int) ($config['amount_pence'] ?? $session->amount_total ?? 0),
            'frequency' => ($config['frequency'] ?? 'monthly') === 'yearly' ? 'yearly' : 'monthly',
            'stripe_customer_id' => $session->customer,
            'stripe_subscription_id' => $subscriptionId,
            'stripe_price_id' => null,
            'status' => 'trialing',
            'started_at' => now(),
        ]);

        if ($session->payment_status === 'paid') {
            Payment::firstOrCreate(
                ['stripe_invoice_id' => $session->invoice],
                [
                    'agreement_id' => $agreement->id,
                    'version_id' => (int) ($session->metadata->version_id ?? $agreement->currentVersion?->id),
                    'type' => PaymentType::Subscription,
                    'stripe_subscription_id' => $record->stripe_subscription_id,
                    'amount_pence' => $session->amount_total ?? 0,
                    'currency' => $session->currency ?? 'gbp',
                    'status' => PaymentStatus::Succeeded,
                    'paid_at' => now(),
                    'metadata' => $session->toArray(),
                ]
            );
        }

        $this->markAgreementActive($agreement, $logs);

        $link = $agreement->activeLink;

        if ($link) {
            app(EmailService::class)->send(
                new SubscribeStartedMail($record, $link),
                $agreement->client_email,
                'subscription.started',
                $agreement
            );
        }
    }

    // ---------------------------------------------------------------
    // payment_intent.succeeded
    // ---------------------------------------------------------------

    private function handlePaymentIntentSucceeded($event): void
    {
        $intent = $event->data->object;

        $payment = Payment::where('stripe_payment_intent_id', $intent->id)->first();

        if (! $payment) {
            return;
        }

        if ($payment->isSuccessful()) {
            return;
        }

        $payment->update([
            'status' => PaymentStatus::Succeeded,
            'paid_at' => now(),
        ]);

        $this->applySuccess($payment, $payment->agreement, $payment->type->value, $payment->milestone_id);
    }

    // ---------------------------------------------------------------
    // payment_intent.payment_failed
    // ---------------------------------------------------------------

    private function handlePaymentIntentFailed($event): void
    {
        $intent = $event->data->object;

        $payment = Payment::where('stripe_payment_intent_id', $intent->id)
            ->where('status', '!=', PaymentStatus::Succeeded->value)
            ->first();

        $payment?->update([
            'status' => PaymentStatus::Failed,
            'failed_at' => now(),
        ]);

        if ($payment) {
            $link = $payment->agreement->activeLink;

            if ($link) {
                app(EmailService::class)->send(
                    new PaymentFailedMail($payment->agreement, $payment, $link),
                    $payment->agreement->client_email,
                    'payment.failed',
                    $payment->agreement
                );
            }
        }
    }

    // ---------------------------------------------------------------
    // invoice.paid
    // ---------------------------------------------------------------

    private function handleInvoicePaid($event, ActivityLogService $logs): void
    {
        $invoice = $event->data->object;
        $subscriptionId = $invoice->subscription;

        $subscription = AgreementSubscription::where('stripe_subscription_id', $subscriptionId)->first();

        if (! $subscription) {
            return;
        }

        $amountPence = $invoice->amount_paid ?? $invoice->total ?? 0;

        $payment = Payment::firstOrCreate(
            ['stripe_invoice_id' => $invoice->id],
            [
                'agreement_id' => $subscription->agreement_id,
                'version_id' => $subscription->version_id,
                'type' => PaymentType::Subscription,
                'stripe_subscription_id' => $subscriptionId,
                'amount_pence' => $amountPence,
                'currency' => $invoice->currency ?? 'gbp',
                'status' => PaymentStatus::Succeeded,
                'paid_at' => now(),
                'metadata' => $invoice->toArray(),
            ]
        );

        if ($payment->wasRecentlyCreated) {
            $this->applySuccess($payment, $subscription->agreement, 'subscription', null, $logs);
        }
    }

    // ---------------------------------------------------------------
    // invoice.payment_failed
    // ---------------------------------------------------------------

    private function handleInvoicePaymentFailed($event): void
    {
        $invoice = $event->data->object;

        $subscription = AgreementSubscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $subscription->update(['status' => 'past_due']);
        }

        Payment::where('stripe_invoice_id', $invoice->id)
            ->where('status', '!=', PaymentStatus::Succeeded->value)
            ->update([
                'status' => PaymentStatus::Failed,
                'failed_at' => now(),
            ]);
    }

    // ---------------------------------------------------------------
    // invoice.payment_action_required
    // ---------------------------------------------------------------

    private function handleInvoicePaymentActionRequired($event, ActivityLogService $logs): void
    {
        $invoice = $event->data->object;

        $subscriptionId = $invoice->subscription ?? null;
        $paymentIntentId = is_string($invoice->payment_intent)
            ? $invoice->payment_intent
            : ($invoice->payment_intent?->id ?? null);

        $payment = null;

        if ($paymentIntentId) {
            $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)
                ->where('status', '!=', PaymentStatus::Succeeded->value)
                ->first();
        }

        if (! $payment && $invoice->id) {
            $payment = Payment::where('stripe_invoice_id', $invoice->id)
                ->where('status', '!=', PaymentStatus::Succeeded->value)
                ->first();
        }

        if (! $payment) {
            return;
        }

        $clientSecret = is_string($invoice->payment_intent)
            ? null
            : ($invoice->payment_intent?->client_secret ?? null);

        $payment->update([
            'status' => PaymentStatus::RequiresAction,
            'action_required_secret' => $clientSecret,
            'action_required_url' => $invoice->hosted_invoice_url ?? null,
            'metadata' => array_merge($payment->metadata ?? [], [
                'invoice_action_required' => true,
                'invoice_id' => $invoice->id,
            ]),
        ]);

        $logs->record('payment.action_required', $payment, [
            'agreement_number' => $payment->agreement->agreement_number,
            'amount_pence' => $payment->amount_pence,
            'invoice_id' => $invoice->id,
        ]);

        $link = $payment->agreement->activeLink;

        if ($link) {
            app(EmailService::class)->send(
                new PaymentActionRequiredMail($payment->agreement, $payment, $link),
                $payment->agreement->client_email,
                'payment.action_required',
                $payment->agreement
            );
        }
    }

    // ---------------------------------------------------------------
    // customer.subscription.updated
    // ---------------------------------------------------------------

    private function handleSubscriptionUpdated($event): void
    {
        $stripeSubscription = $event->data->object;

        $record = AgreementSubscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (! $record) {
            return;
        }

        $record->update([
            'status' => $stripeSubscription->status,
            'cancel_at_period_end' => (bool) ($stripeSubscription->cancel_at_period_end ?? false),
            'current_period_start' => isset($stripeSubscription->current_period_start)
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_start)
                : $record->current_period_start,
            'current_period_end' => isset($stripeSubscription->current_period_end)
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : $record->current_period_end,
        ]);
    }

    // ---------------------------------------------------------------
    // customer.subscription.deleted
    // ---------------------------------------------------------------

    private function handleSubscriptionDeleted($event): void
    {
        $stripeSubscription = $event->data->object;

        $record = AgreementSubscription::where('stripe_subscription_id', $stripeSubscription->id)->first();
        AgreementSubscription::where('stripe_subscription_id', $stripeSubscription->id)
            ->update([
                'status' => 'canceled',
                'ended_at' => now(),
            ]);
        if ($record) {
            $link = $record->agreement->activeLink;

            if ($link) {
                app(EmailService::class)->send(
                    new SubscribeCancelledMail($record, $record->agreement, $link),
                    $record->agreement->client_email,
                    'subscription.cancelled',
                    $record->agreement
                );
            }
        }
    }

    // ---------------------------------------------------------------
    // charge.refunded
    // ---------------------------------------------------------------

    private function handleChargeRefunded($event, ActivityLogService $logs): void
    {
        $charge = $event->data->object;
        $paymentIntentId = $charge->payment_intent;

        if (! $paymentIntentId) {
            return;
        }

        $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if (! $payment) {
            return;
        }

        $refundedPence = 0;

        foreach ($charge->refunds->data as $refund) {
            $amount = (int) ($refund->amount ?? 0);
            $refundedPence += $amount;

            PaymentRefund::updateOrCreate(
                ['stripe_refund_id' => $refund->id],
                [
                    'payment_id' => $payment->id,
                    'amount_pence' => $amount,
                    'currency' => $refund->currency ?? 'gbp',
                    'status' => $refund->status ?? 'pending',
                    'reason' => $refund->reason ?? null,
                    'processed_at' => $refund->status === 'succeeded' ? now() : null,
                ]
            );
        }

        $payment->update([
            'refunded_amount_pence' => $refundedPence,
            'status' => $refundedPence >= $payment->amount_pence ? PaymentStatus::Refunded : PaymentStatus::PartiallyRefunded,
        ]);

        $logs->record('payment.refunded', $payment, [
            'agreement_number' => $payment->agreement->agreement_number,
            'amount_pence' => $refundedPence,
        ]);

        $latestRefund = $payment->refunds()->latest('id')->first();
        $link = $payment->agreement->activeLink;

        if ($latestRefund && $link) {
            app(EmailService::class)->send(
                new PaymentRefundedMail($payment->agreement, $latestRefund, $link),
                $payment->agreement->client_email,
                'payment.refunded',
                $payment->agreement
            );
        }
    }

    // ---------------------------------------------------------------
    // refund.created
    // ---------------------------------------------------------------

    private function handleRefundCreated($event): void
    {
        $refundObj = $event->data->object;

        $paymentIntentId = is_string($refundObj->payment_intent)
            ? $refundObj->payment_intent
            : null;

        if (! $paymentIntentId) {
            return;
        }

        $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if (! $payment) {
            return;
        }

        $amount = (int) ($refundObj->amount ?? 0);

        $refund = PaymentRefund::updateOrCreate(
            ['stripe_refund_id' => $refundObj->id],
            [
                'payment_id' => $payment->id,
                'amount_pence' => $amount,
                'currency' => $refundObj->currency ?? 'gbp',
                'status' => $refundObj->status ?? 'pending',
                'reason' => $refundObj->reason ?? null,
                'processed_at' => $refundObj->status === 'succeeded' ? now() : null,
            ]
        );

        if ($refundObj->status === 'succeeded') {
            $this->syncPaymentRefundStatus($payment);
        }
    }

    // ---------------------------------------------------------------
    // refund.updated
    // ---------------------------------------------------------------

    private function handleRefundUpdated($event): void
    {
        $refundObj = $event->data->object;

        $refund = PaymentRefund::where('stripe_refund_id', $refundObj->id)->first();

        if (! $refund) {
            return;
        }

        $refund->update([
            'status' => $refundObj->status ?? $refund->status,
            'amount_pence' => isset($refundObj->amount) ? (int) $refundObj->amount : $refund->amount_pence,
            'currency' => $refundObj->currency ?? $refund->currency,
            'reason' => $refundObj->reason ?? $refund->reason,
            'failure_code' => $refundObj->failure_code ?? $refund->failure_code,
            'failure_message' => $refundObj->failure_message ?? $refund->failure_message,
            'processed_at' => $refundObj->status === 'succeeded' ? now() : ($refund->processed_at),
        ]);

        $this->syncPaymentRefundStatus($refund->payment);
    }

    // ---------------------------------------------------------------
    // refund.failed
    // ---------------------------------------------------------------

    private function handleRefundFailed($event, ActivityLogService $logs): void
    {
        $refundObj = $event->data->object;

        $refund = PaymentRefund::where('stripe_refund_id', $refundObj->id)->first();

        if (! $refund) {
            return;
        }

        $refund->update([
            'status' => 'failed',
            'failure_code' => $refundObj->failure_code ?? null,
            'failure_message' => $refundObj->failure_message ?? null,
            'processed_at' => null,
        ]);

        $payment = $refund->payment;
        $this->syncPaymentRefundStatus($payment);

        $logs->record('payment.refund_failed', $refund, [
            'agreement_number' => $payment->agreement->agreement_number,
            'refund_id' => $refund->id,
            'failure_code' => $refundObj->failure_code ?? null,
            'failure_message' => $refundObj->failure_message ?? null,
        ]);

        $link = $payment->agreement->activeLink;

        if ($link) {
            app(EmailService::class)->send(
                new PaymentRefundFailedMail($payment->agreement, $refund, $link),
                $payment->agreement->client_email,
                'payment.refund_failed',
                $payment->agreement
            );
        }
    }

    // ---------------------------------------------------------------
    // Shared helpers
    // ---------------------------------------------------------------

    private function syncPaymentRefundStatus(Payment $payment): void
    {
        $successfulRefunded = $payment->refunds()
            ->where('status', 'succeeded')
            ->sum('amount_pence');

        if ($successfulRefunded <= 0 && $payment->status !== PaymentStatus::Succeeded) {
            $payment->update([
                'refunded_amount_pence' => 0,
                'status' => PaymentStatus::Succeeded,
            ]);

            return;
        }

        if ($successfulRefunded > 0) {
            $status = $successfulRefunded >= $payment->amount_pence
                ? PaymentStatus::Refunded
                : PaymentStatus::PartiallyRefunded;

            $payment->update([
                'refunded_amount_pence' => $successfulRefunded,
                'status' => $status,
            ]);
        }
    }

    private function applySuccess(Payment $payment, ?Agreement $agreement, string $paymentType, ?int $milestoneId, ?ActivityLogService $logs = null): void
    {
        if (! $agreement) {
            return;
        }

        if ($paymentType === 'milestone' && $milestoneId) {
            AgreementMilestone::where('id', $milestoneId)
                ->where('agreement_id', $agreement->id)
                ->update([
                    'status' => MilestoneStatus::Paid,
                    'paid_at' => now(),
                    'payment_id' => $payment->id,
                ]);

            if ($agreement->milestones()->where('status', 'pending')->doesntExist()) {
                $agreement->update(['status' => AgreementStatus::Completed]);
            } else {
                $agreement->update(['status' => AgreementStatus::InProgress]);
            }
        } else {
            $agreement->update(['status' => AgreementStatus::InProgress]);
        }

        $logs?->record('payment.succeeded', $payment, [
            'agreement_number' => $agreement->agreement_number,
            'amount_pence' => $payment->amount_pence,
            'type' => $paymentType,
        ]);

        $link = $agreement->activeLink;

        if ($link) {
            app(EmailService::class)->send(
                new PaymentSuccessMail($agreement, $payment, $link),
                $agreement->client_email,
                'payment.succeeded',
                $agreement
            );
        }
    }

    private function markAgreementActive(Agreement $agreement, ActivityLogService $logs): void
    {
        $agreement->update(['status' => AgreementStatus::InProgress]);

        $logs->record('agreement.subscription_started', $agreement, [
            'agreement_number' => $agreement->agreement_number,
        ]);
    }
}
