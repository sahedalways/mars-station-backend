<?php

namespace App\Console\Commands;

use App\Models\AgreementSubscription;
use App\Services\StripeService;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class SyncSubscriptionsCommand extends Command
{
    protected $signature = 'subscriptions:sync';

    protected $description = 'Reconcile subscription statuses with Stripe';

    public function handle(StripeService $stripe, SubscriptionService $subscriptions): int
    {
        if (! $stripe->isConfigured()) {
            $this->warn('Stripe is not configured. Skipping.');

            return Command::SUCCESS;
        }

        $records = AgreementSubscription::query()
            ->whereNotNull('stripe_subscription_id')
            ->whereIn('status', ['active', 'trialing', 'past_due', 'incomplete', 'unpaid', 'paused'])
            ->get();

        $count = 0;

        foreach ($records as $record) {
            try {
                $stripeSubscription = $stripe->retrieveSubscription($record->stripe_subscription_id);
                $subscriptions->syncFromStripe($record, $stripeSubscription);
                $count++;
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->info("Synced {$count} subscriptions.");

        return Command::SUCCESS;
    }
}
