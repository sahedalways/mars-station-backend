<?php

namespace App\Http\Controllers;

use App\Enums\AgreementStatus;
use App\Enums\MilestoneStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Mail\PaymentFailedMail;
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
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event),
            'charge.refunded' => $this->handleChargeRefunded($event),
            default => null,
        };

        return response()->json(['received' => true]);
    }

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

    private function handleChargeRefunded($event): void
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
        $status = $charge->refunds->data[0]->status ?? 'succeeded';

        foreach ($charge->refunds->data as $refund) {
            $refundedPence += (int) ($refund->amount ?? 0);

            PaymentRefund::create([
                'payment_id' => $payment->id,
                'stripe_refund_id' => $refund->id,
                'amount_pence' => (int) ($refund->amount ?? 0),
                'currency' => $refund->currency ?? 'gbp',
                'status' => $refund->status ?? 'pending',
                'reason' => $refund->reason ?? null,
                'processed_at' => $refund->status === 'succeeded' ? now() : null,
            ]);
        }

        $payment->update([
            'refunded_amount_pence' => $refundedPence,
            'status' => $refundedPence >= $payment->amount_pence ? PaymentStatus::Refunded : PaymentStatus::PartiallyRefunded,
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
