<?php

namespace App\Services;

use App\Models\Agreement;
use App\Models\AgreementSubscription;
use Carbon\Carbon;
use Stripe\Subscription;

class SubscriptionService
{
    public function createStripeCustomer(Agreement $agreement, StripeService $stripe): string
    {
        $customer = $stripe->createCustomer(
            $agreement->client_email,
            $agreement->client_name,
            ['agreement_number' => $agreement->agreement_number]
        );

        return $customer->id;
    }

    public function startSubscription(
        Agreement $agreement,
        int $amountPence,
        string $interval,
        string $stripeCustomerId,
        StripeService $stripe
    ): AgreementSubscription {
        $price = $stripe->createPrice(
            $amountPence,
            $stripe->currency(),
            $interval
        );

        $stripeSubscription = $stripe->createSubscription([
            'customer' => $stripeCustomerId,
            'items' => [['price' => $price->id]],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
            'metadata' => ['agreement_number' => $agreement->agreement_number],
        ]);

        $record = AgreementSubscription::create([
            'agreement_id' => $agreement->id,
            'version_id' => $agreement->currentVersion?->id,
            'title' => $agreement->title,
            'amount_pence' => $amountPence,
            'frequency' => $interval === 'year' ? 'yearly' : 'monthly',
            'stripe_customer_id' => $stripeCustomerId,
            'stripe_subscription_id' => $stripeSubscription->id,
            'stripe_price_id' => $price->id,
            'status' => $stripeSubscription->status,
            'cancel_at_period_end' => $stripeSubscription->cancel_at_period_end ?? false,
            'current_period_start' => isset($stripeSubscription->current_period_start)
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_start)
                : null,
            'current_period_end' => isset($stripeSubscription->current_period_end)
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : null,
            'started_at' => now(),
        ]);

        return $record;
    }

    public function syncFromStripe(AgreementSubscription $record, Subscription $stripeSubscription): void
    {
        $record->update([
            'status' => $stripeSubscription->status,
            'cancel_at_period_end' => (bool) ($stripeSubscription->cancel_at_period_end ?? false),
            'current_period_start' => isset($stripeSubscription->current_period_start)
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_start)
                : $record->current_period_start,
            'current_period_end' => isset($stripeSubscription->current_period_end)
                ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : $record->current_period_end,
            'ended_at' => in_array($stripeSubscription->status, ['canceled', 'ended'], true)
                ? now()
                : $record->ended_at,
        ]);
    }

    public function cancel(AgreementSubscription $record, bool $atPeriodEnd, StripeService $stripe): AgreementSubscription
    {
        $stripeSubscription = $stripe->cancelSubscription($record->stripe_subscription_id, $atPeriodEnd);

        $record->update([
            'status' => $atPeriodEnd ? $record->status : 'canceled',
            'cancel_at_period_end' => (bool) ($stripeSubscription->cancel_at_period_end ?? $atPeriodEnd),
            'canceled_at' => now(),
        ]);

        if (! $atPeriodEnd) {
            $record->update(['ended_at' => now()]);
        }

        return $record->fresh();
    }
}
