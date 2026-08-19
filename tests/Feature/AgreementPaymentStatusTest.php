<?php

namespace Tests\Feature;

use App\Enums\AgreementPaymentType;
use App\Enums\MilestoneStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Agreement\AgreementPortal;
use App\Models\Admin;
use App\Models\Agreement;
use App\Models\AgreementMilestone;
use App\Models\AgreementSubscription;
use App\Services\AgreementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AgreementPaymentStatusTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    private function createFullAgreement(array $overrides = []): Agreement
    {
        return app(AgreementService::class)->create(array_merge([
            'title' => 'Website Development',
            'client_name' => 'Alice Client',
            'client_email' => 'alice@example.com',
            'content' => 'Build a website.',
            'validity_date' => now()->addMonths(2)->format('Y-m-d'),
            'payment_type' => 'full',
            'full_title' => 'Website Build',
            'full_amount_pence' => 50000,
        ], $overrides), $this->admin);
    }

    private function createMilestoneAgreement(array $overrides = []): Agreement
    {
        return app(AgreementService::class)->create(array_merge([
            'title' => 'App Development',
            'client_name' => 'Bob Client',
            'client_email' => 'bob@example.com',
            'content' => 'Build an app.',
            'validity_date' => now()->addMonths(3)->format('Y-m-d'),
            'payment_type' => 'milestone',
            'milestones' => [
                ['title' => 'Design', 'description' => 'UI/UX design', 'amount_pence' => 20000, 'order_index' => 1],
                ['title' => 'Development', 'description' => 'Core build', 'amount_pence' => 50000, 'order_index' => 2],
                ['title' => 'Launch', 'description' => 'Deployment', 'amount_pence' => 30000, 'order_index' => 3],
            ],
        ], $overrides), $this->admin);
    }

    private function createSubscriptionAgreement(array $overrides = []): Agreement
    {
        return app(AgreementService::class)->create(array_merge([
            'title' => 'Monthly Retainer',
            'client_name' => 'Carol Client',
            'client_email' => 'carol@example.com',
            'content' => 'Ongoing support.',
            'validity_date' => now()->addMonths(12)->format('Y-m-d'),
            'payment_type' => 'subscription',
            'subscription_title' => 'Monthly Support',
            'subscription_amount_pence' => 9900,
            'subscription_frequency' => 'monthly',
        ], $overrides), $this->admin);
    }

    // ---------------------------------------------------------------
    //  CANCELLED — Type-specific error messages
    // ---------------------------------------------------------------

    public function test_full_payment_cancelled_shows_specific_error(): void
    {
        $agreement = $this->createFullAgreement();
        $link = $agreement->links->first();

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'cancelled']));

        $response->assertSee('Full payment was cancelled. No payment was completed. You can try again when ready.');
        $response->assertDontSee('Subscription payment was cancelled');
        $response->assertDontSee('Milestone payment was cancelled');
    }

    public function test_milestone_payment_cancelled_shows_specific_error(): void
    {
        $agreement = $this->createMilestoneAgreement();
        $link = $agreement->links->first();

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'cancelled']));

        $response->assertSee('Milestone payment was cancelled. This milestone is still unpaid. You can try again when ready.');
        $response->assertDontSee('Full payment was cancelled');
        $response->assertDontSee('Subscription payment was cancelled');
    }

    public function test_subscription_payment_cancelled_shows_specific_error(): void
    {
        $agreement = $this->createSubscriptionAgreement();
        $link = $agreement->links->first();

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'cancelled']));

        $response->assertSee('Subscription payment was cancelled. Your subscription has not been activated. You can try again when ready.');
        $response->assertDontSee('Full payment was cancelled');
        $response->assertDontSee('Milestone payment was cancelled');
    }

    // ---------------------------------------------------------------
    //  SUCCESS — Pending confirmation (webhook not yet fired)
    // ---------------------------------------------------------------

    public function test_full_payment_success_pending_shows_type_specific_pending_message(): void
    {
        $agreement = $this->createFullAgreement();
        $link = $agreement->links->first();

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'success']));

        $response->assertSee('Payment submitted. We are confirming your full payment...');
        $response->assertSee('This page will update automatically once the payment is confirmed.');
        $response->assertDontSee('activating your subscription');
        $response->assertDontSee('confirming your milestone payment');
    }

    public function test_subscription_success_pending_shows_type_specific_pending_message(): void
    {
        $agreement = $this->createSubscriptionAgreement();
        $link = $agreement->links->first();

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'success']));

        $response->assertSee('Payment submitted. We are activating your subscription and confirming the payment...');
        $response->assertDontSee('confirming your full payment');
        $response->assertDontSee('confirming your milestone payment');
    }

    public function test_milestone_success_pending_shows_type_specific_pending_message(): void
    {
        $agreement = $this->createMilestoneAgreement();
        $link = $agreement->links->first();

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'success']));

        $response->assertSee('Payment submitted. We are confirming your milestone payment...');
        $response->assertDontSee('activating your subscription');
        $response->assertDontSee('confirming your full payment');
    }

    // ---------------------------------------------------------------
    //  SUCCESS — Webhook confirmed (full payment)
    // ---------------------------------------------------------------

    public function test_full_payment_webhook_confirmed_shows_specific_completion_message(): void
    {
        $agreement = $this->createFullAgreement();
        $link = $agreement->links->first();
        $version = $agreement->currentVersion;

        // Simulate webhook: create a succeeded payment
        $agreement->payments()->create([
            'version_id' => $version->id,
            'type' => 'full',
            'stripe_payment_intent_id' => 'pi_test_full_' . str()->random(10),
            'amount_pence' => 50000,
            'currency' => 'gbp',
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        $agreement->update(['status' => 'in_progress']);

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'success']));

        $response->assertSee('Payment received in full. Your agreement is now complete.');
        $response->assertSee('Thank you');
    }

    // ---------------------------------------------------------------
    //  SUCCESS — Webhook confirmed (subscription)
    // ---------------------------------------------------------------

    public function test_subscription_webhook_confirmed_shows_next_billing_date(): void
    {
        $agreement = $this->createSubscriptionAgreement();
        $link = $agreement->links->first();

        $nextBilling = now()->addMonth()->startOfDay();

        AgreementSubscription::factory()->create([
            'agreement_id' => $agreement->id,
            'version_id' => $agreement->currentVersion->id,
            'status' => SubscriptionStatus::Active,
            'current_period_end' => $nextBilling,
        ]);

        $agreement->update(['status' => 'subscribed']);

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'success']));

        $response->assertSee('Subscription activated successfully.');
        $response->assertSee('Your next billing date is');
        $response->assertSee($nextBilling->format('F j, Y'));
    }

    public function test_subscription_webhook_confirmed_without_period_end_shows_fallback(): void
    {
        $agreement = $this->createSubscriptionAgreement();
        $link = $agreement->links->first();

        AgreementSubscription::factory()->create([
            'agreement_id' => $agreement->id,
            'version_id' => $agreement->currentVersion->id,
            'status' => SubscriptionStatus::Active,
            'current_period_end' => null,
        ]);

        $agreement->update(['status' => 'subscribed']);

        $response = $this->get(route('agreement.view', ['token' => $link->token, 'status' => 'success']));

        $response->assertSee('Subscription activated successfully. Your subscription is now active.');
        $response->assertDontSee('next billing date');
    }

    // ---------------------------------------------------------------
    //  SUCCESS — Webhook confirmed (milestone — final)
    // ---------------------------------------------------------------

    public function test_milestone_final_webhook_confirmed_shows_agreement_complete(): void
    {
        $agreement = $this->createMilestoneAgreement();
        $link = $agreement->links->first();

        // Verify milestones were created
        $this->assertCount(3, $agreement->milestones);

        // Mark all milestones as paid
        $agreement->milestones()->update(['status' => MilestoneStatus::Paid, 'paid_at' => now()]);

        // Simulate: webhook confirmed, user lands on ?status=success, isPaid() returns true
        Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'milestone')
            ->set('pendingMilestoneId', 0)
            ->set('pendingMilestoneIndex', 3)
            ->set('totalMilestoneCount', 3)
            ->call('checkPaymentStatus')
            ->assertSet('paymentPending', false)
            ->assertSet('step', 'complete')
            ->assertSet('completionMessage', 'Final milestone paid successfully. Your agreement is now complete.')
            ->assertSee('Thank you');
    }

    // ---------------------------------------------------------------
    //  checkPaymentStatus — Milestone intermediate
    // ---------------------------------------------------------------

    public function test_milestone_check_payment_status_intermediate_shows_progress(): void
    {
        $agreement = $this->createMilestoneAgreement();
        $link = $agreement->links->first();
        $version = $agreement->currentVersion;

        // Get the first milestone
        $milestones = $agreement->milestones()->orderBy('order_index')->get();
        $firstMilestone = $milestones->first();

        // Simulate the pending state: set paymentType and pendingMilestoneId
        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'milestone')
            ->set('pendingMilestoneId', $firstMilestone->id)
            ->set('pendingMilestoneIndex', 1)
            ->set('totalMilestoneCount', 3);

        // Simulate webhook marking first milestone as paid
        $firstMilestone->update(['status' => 'paid', 'paid_at' => now()]);

        $component->call('checkPaymentStatus')
            ->assertSet('paymentPending', false)
            ->assertSet('message', 'Milestone 1 of 3 paid successfully. 2 milestones remaining.')
            ->assertSet('showPaymentModal', true);
    }

    public function test_milestone_check_payment_status_final_marks_complete(): void
    {
        $agreement = $this->createMilestoneAgreement();
        $link = $agreement->links->first();

        $milestones = $agreement->milestones()->orderBy('order_index')->get();
        $lastMilestone = $milestones->last();

        // Mark first two as paid
        $milestones->first()->update(['status' => 'paid', 'paid_at' => now()]);
        $milestones->slice(1, 1)->first()->update(['status' => 'paid', 'paid_at' => now()]);

        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'milestone')
            ->set('pendingMilestoneId', $lastMilestone->id)
            ->set('pendingMilestoneIndex', 3)
            ->set('totalMilestoneCount', 3);

        // Simulate webhook marking last milestone as paid
        $lastMilestone->update(['status' => 'paid', 'paid_at' => now()]);

        $component->call('checkPaymentStatus')
            ->assertSet('paymentPending', false)
            ->assertSet('step', 'complete')
            ->assertSet('completionMessage', 'Final milestone paid successfully. Your agreement is now complete.');
    }

    // ---------------------------------------------------------------
    //  checkPaymentStatus — Subscription confirmed
    // ---------------------------------------------------------------

    public function test_subscription_check_payment_status_confirmed_shows_billing_date(): void
    {
        $agreement = $this->createSubscriptionAgreement();
        $link = $agreement->links->first();

        $nextBilling = now()->addMonth()->startOfDay();

        AgreementSubscription::factory()->create([
            'agreement_id' => $agreement->id,
            'version_id' => $agreement->currentVersion->id,
            'status' => SubscriptionStatus::Active,
            'current_period_end' => $nextBilling,
        ]);

        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'subscription');

        $component->call('checkPaymentStatus')
            ->assertSet('paymentPending', false)
            ->assertSet('step', 'complete')
            ->assertSet('completionMessage', "Subscription activated successfully. Your next billing date is {$nextBilling->format('F j, Y')}.");
    }

    // ---------------------------------------------------------------
    //  checkPaymentStatus — Full payment confirmed
    // ---------------------------------------------------------------

    public function test_full_check_payment_status_confirmed_shows_specific_message(): void
    {
        $agreement = $this->createFullAgreement();
        $link = $agreement->links->first();
        $version = $agreement->currentVersion;

        $agreement->payments()->create([
            'version_id' => $version->id,
            'type' => 'full',
            'stripe_payment_intent_id' => 'pi_test_full_check_' . str()->random(10),
            'amount_pence' => 50000,
            'currency' => 'gbp',
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'full');

        $component->call('checkPaymentStatus')
            ->assertSet('paymentPending', false)
            ->assertSet('step', 'complete')
            ->assertSet('completionMessage', 'Payment received in full. Your agreement is now complete.');
    }

    // ---------------------------------------------------------------
    //  Edge case: unknown payment type falls back to generic
    // ---------------------------------------------------------------

    public function test_unknown_payment_type_uses_fallback_messages(): void
    {
        $agreement = $this->createFullAgreement();
        $link = $agreement->links->first();

        // Simulate unknown type by overriding paymentType after mount
        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'unknown');

        // When checkPaymentStatus runs with unknown type, it should not crash
        // and should still detect isPaid() if applicable
        $component->call('checkPaymentStatus');
    }

    // ---------------------------------------------------------------
    //  Milestone singular remaining
    // ---------------------------------------------------------------

    public function test_milestone_single_remaining_shows_singular(): void
    {
        $agreement = $this->createMilestoneAgreement();
        $link = $agreement->links->first();

        $milestones = $agreement->milestones()->orderBy('order_index')->get();

        // Mark first milestone as paid
        $milestones->first()->update(['status' => 'paid', 'paid_at' => now()]);

        // Now pay the second milestone — only 1 remaining
        $secondMilestone = $milestones->slice(1, 1)->first();

        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('paymentPending', true)
            ->set('paymentType', 'milestone')
            ->set('pendingMilestoneId', $secondMilestone->id)
            ->set('pendingMilestoneIndex', 2)
            ->set('totalMilestoneCount', 3);

        $secondMilestone->update(['status' => 'paid', 'paid_at' => now()]);

        $component->call('checkPaymentStatus')
            ->assertSet('message', 'Milestone 2 of 3 paid successfully. 1 milestone remaining.');
    }
}
