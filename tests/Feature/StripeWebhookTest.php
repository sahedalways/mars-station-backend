<?php

namespace Tests\Feature;

use App\Enums\AgreementPaymentType;
use App\Enums\AgreementStatus;
use App\Enums\MilestoneStatus;
use App\Enums\PaymentStatus;
use App\Models\Admin;
use App\Models\Agreement;
use App\Models\AgreementLink;
use App\Models\AgreementMilestone;
use App\Models\AgreementSubscription;
use App\Models\AgreementVersion;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\PaymentRefund;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Stripe\Event as StripeEvent;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    private Agreement $agreement;
    private AgreementVersion $version;
    private AgreementLink $link;

    protected function setUp(): void
    {
        parent::setUp();

        config(['mars.stripe.webhook_secret' => 'whsec_test_secret']);

        $admin = Admin::factory()->create();

        $this->agreement = Agreement::factory()->create([
            'status' => AgreementStatus::Pending,
            'payment_type' => AgreementPaymentType::Full,
            'client_email' => 'client@test.com',
            'created_by' => $admin->id,
        ]);

        $this->version = AgreementVersion::factory()->create([
            'agreement_id' => $this->agreement->id,
            'payment_config' => ['amount_pence' => 10000, 'title' => 'Test Service'],
        ]);

        $this->link = AgreementLink::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'is_active' => true,
            'token' => Str::random(32),
        ]);
    }

    private function postWebhook(StripeEvent $event): \Illuminate\Testing\TestResponse
    {
        $this->mock(StripeService::class, function ($mock) use ($event) {
            $mock->shouldReceive('isConfigured')->once()->andReturn(true);
            $mock->shouldReceive('constructEvent')->once()->andReturn($event);
        });

        return $this->postJson('/stripe/webhook', [], [
            'Stripe-Signature' => 'test_sig',
        ]);
    }

    private function fakeEvent(string $type, array $data): StripeEvent
    {
        return StripeEvent::constructFrom([
            'id' => 'evt_test_'.Str::random(10),
            'type' => $type,
            'data' => ['object' => $data],
            'created' => now()->timestamp,
            'livemode' => false,
        ]);
    }

    // ---------------------------------------------------------------
    // checkout.session.completed
    // ---------------------------------------------------------------

    public function test_checkout_completed_full_payment(): void
    {
        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'payment',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_test_123',
            'amount_total' => 10000,
            'currency' => 'gbp',
            'metadata' => [
                'agreement_id' => $this->agreement->id,
                'version_id' => $this->version->id,
                'payment_type' => 'full',
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payments', [
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_test_123',
            'type' => 'full',
            'amount_pence' => 10000,
            'currency' => 'gbp',
            'status' => PaymentStatus::Succeeded->value,
        ]);

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::InProgress, $this->agreement->status);
    }

    public function test_checkout_completed_full_payment_pending(): void
    {
        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'payment',
            'payment_status' => 'unpaid',
            'payment_intent' => 'pi_test_pending',
            'amount_total' => 5000,
            'currency' => 'gbp',
            'metadata' => [
                'agreement_id' => $this->agreement->id,
                'payment_type' => 'full',
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payments', [
            'stripe_payment_intent_id' => 'pi_test_pending',
            'status' => PaymentStatus::Pending->value,
            'amount_pence' => 5000,
        ]);
    }

    public function test_checkout_completed_milestone_payment(): void
    {
        $milestone = AgreementMilestone::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'amount_pence' => 3000,
            'status' => MilestoneStatus::Pending,
            'order_index' => 1,
        ]);

        AgreementMilestone::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'amount_pence' => 7000,
            'status' => MilestoneStatus::Pending,
            'order_index' => 2,
        ]);

        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'payment',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_milestone_1',
            'amount_total' => 3000,
            'currency' => 'gbp',
            'metadata' => [
                'agreement_id' => $this->agreement->id,
                'version_id' => $this->version->id,
                'payment_type' => 'milestone',
                'milestone_id' => $milestone->id,
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payments', [
            'stripe_payment_intent_id' => 'pi_milestone_1',
            'type' => 'milestone',
            'amount_pence' => 3000,
            'status' => PaymentStatus::Succeeded->value,
        ]);

        $milestone->refresh();
        $this->assertEquals(MilestoneStatus::Paid, $milestone->status);
        $this->assertNotNull($milestone->paid_at);
        $this->assertNotNull($milestone->payment_id);

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::InProgress, $this->agreement->status);
    }

    public function test_checkout_completed_all_milestones_completes_agreement(): void
    {
        $m1 = AgreementMilestone::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'amount_pence' => 5000,
            'status' => MilestoneStatus::Pending,
            'order_index' => 1,
        ]);

        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'payment',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_ms_last',
            'amount_total' => 5000,
            'currency' => 'gbp',
            'metadata' => [
                'agreement_id' => $this->agreement->id,
                'version_id' => $this->version->id,
                'payment_type' => 'milestone',
                'milestone_id' => $m1->id,
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::Completed, $this->agreement->status);
    }

    public function test_checkout_completed_subscription(): void
    {
        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'subscription',
            'payment_status' => 'paid',
            'subscription' => 'sub_test_123',
            'customer' => 'cus_test_123',
            'invoice' => 'in_sub_001',
            'amount_total' => 2999,
            'currency' => 'gbp',
            'metadata' => [
                'agreement_id' => $this->agreement->id,
                'version_id' => $this->version->id,
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('agreement_subscriptions', [
            'agreement_id' => $this->agreement->id,
            'stripe_subscription_id' => 'sub_test_123',
            'stripe_customer_id' => 'cus_test_123',
            'status' => 'trialing',
        ]);

        $this->assertDatabaseHas('payments', [
            'agreement_id' => $this->agreement->id,
            'stripe_invoice_id' => 'in_sub_001',
            'type' => 'subscription',
            'amount_pence' => 2999,
            'status' => PaymentStatus::Succeeded->value,
        ]);

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::InProgress, $this->agreement->status);
    }

    public function test_checkout_completed_unknown_agreement_is_noop(): void
    {
        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'payment',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_ghost',
            'amount_total' => 1000,
            'currency' => 'gbp',
            'metadata' => ['agreement_id' => 999999],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseEmpty('payments');
    }

    public function test_checkout_completed_is_idempotent(): void
    {
        $eventId = 'evt_dup_checkout_'.Str::random(8);

        $makeEvent = fn () => StripeEvent::constructFrom([
            'id' => $eventId,
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'mode' => 'payment',
                'payment_status' => 'paid',
                'payment_intent' => 'pi_idem',
                'amount_total' => 1000,
                'currency' => 'gbp',
                'metadata' => ['agreement_id' => $this->agreement->id, 'payment_type' => 'full'],
            ]],
            'created' => now()->timestamp,
            'livemode' => false,
        ]);

        $this->postWebhook($makeEvent())->assertOk();

        $paymentCount = Payment::where('stripe_payment_intent_id', 'pi_idem')->count();
        $this->assertEquals(1, $paymentCount);

        $this->mock(StripeService::class, function ($mock) use ($makeEvent) {
            $mock->shouldReceive('isConfigured')->once()->andReturn(true);
            $mock->shouldReceive('constructEvent')->once()->andReturn($makeEvent());
        });

        $this->postJson('/stripe/webhook', [], ['Stripe-Signature' => 'test_sig'])
            ->assertOk()
            ->assertJson(['duplicate' => true]);

        $this->assertEquals(1, Payment::where('stripe_payment_intent_id', 'pi_idem')->count());
    }

    // ---------------------------------------------------------------
    // payment_intent.succeeded
    // ---------------------------------------------------------------

    public function test_payment_intent_succeeded(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_succeed_1',
            'status' => PaymentStatus::Pending,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        $event = $this->fakeEvent('payment_intent.succeeded', [
            'id' => 'pi_succeed_1',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Succeeded, $payment->status);
        $this->assertNotNull($payment->paid_at);

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::InProgress, $this->agreement->status);
    }

    public function test_payment_intent_succeeded_already_succeeded(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_already_ok',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'paid_at' => now()->subHour(),
        ]);

        $event = $this->fakeEvent('payment_intent.succeeded', [
            'id' => 'pi_already_ok',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(now()->subHour()->timestamp, $payment->paid_at->timestamp);
    }

    public function test_payment_intent_succeeded_no_matching_payment(): void
    {
        $event = $this->fakeEvent('payment_intent.succeeded', [
            'id' => 'pi_nonexistent',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    public function test_payment_intent_succeeded_milestone(): void
    {
        $milestone = AgreementMilestone::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'amount_pence' => 4000,
            'status' => MilestoneStatus::Pending,
            'order_index' => 1,
        ]);

        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_ms_succeed',
            'status' => PaymentStatus::Pending,
            'type' => 'milestone',
            'milestone_id' => $milestone->id,
            'amount_pence' => 4000,
        ]);

        $event = $this->fakeEvent('payment_intent.succeeded', [
            'id' => 'pi_ms_succeed',
        ]);

        $this->postWebhook($event)->assertOk();

        $milestone->refresh();
        $this->assertEquals(MilestoneStatus::Paid, $milestone->status);
        $this->assertNotNull($milestone->paid_at);
        $this->assertEquals($payment->id, $milestone->payment_id);
    }

    // ---------------------------------------------------------------
    // payment_intent.payment_failed
    // ---------------------------------------------------------------

    public function test_payment_intent_failed(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_fail_1',
            'status' => PaymentStatus::Pending,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        $event = $this->fakeEvent('payment_intent.payment_failed', [
            'id' => 'pi_fail_1',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Failed, $payment->status);
        $this->assertNotNull($payment->failed_at);
    }

    public function test_payment_intent_failed_already_succeeded(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_fail_but_ok',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        $event = $this->fakeEvent('payment_intent.payment_failed', [
            'id' => 'pi_fail_but_ok',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Succeeded, $payment->status);
    }

    public function test_payment_intent_failed_no_matching_payment(): void
    {
        $event = $this->fakeEvent('payment_intent.payment_failed', [
            'id' => 'pi_ghost_fail',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    // ---------------------------------------------------------------
    // invoice.paid
    // ---------------------------------------------------------------

    public function test_invoice_paid(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_inv_1',
            'status' => 'active',
        ]);

        $event = $this->fakeEvent('invoice.paid', [
            'id' => 'in_inv_001',
            'subscription' => 'sub_inv_1',
            'amount_paid' => 2999,
            'total' => 2999,
            'currency' => 'gbp',
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payments', [
            'agreement_id' => $this->agreement->id,
            'stripe_invoice_id' => 'in_inv_001',
            'type' => 'subscription',
            'stripe_subscription_id' => 'sub_inv_1',
            'amount_pence' => 2999,
            'status' => PaymentStatus::Succeeded->value,
        ]);
    }

    public function test_invoice_paid_unknown_subscription(): void
    {
        $event = $this->fakeEvent('invoice.paid', [
            'id' => 'in_orphan',
            'subscription' => 'sub_unknown',
            'amount_paid' => 1000,
            'currency' => 'gbp',
        ]);

        $this->postWebhook($event)->assertOk();
        $this->assertDatabaseEmpty('payments');
    }

    public function test_invoice_paid_is_idempotent(): void
    {
        AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_idem_inv',
            'status' => 'active',
        ]);

        $event = $this->fakeEvent('invoice.paid', [
            'id' => 'in_idem_001',
            'subscription' => 'sub_idem_inv',
            'amount_paid' => 2999,
            'currency' => 'gbp',
        ]);

        $this->postWebhook($event)->assertOk();
        $this->assertEquals(1, Payment::where('stripe_invoice_id', 'in_idem_001')->count());

        $event2 = $this->fakeEvent('invoice.paid', [
            'id' => 'in_idem_002',
            'subscription' => 'sub_idem_inv',
            'amount_paid' => 2999,
            'currency' => 'gbp',
        ]);

        $this->postWebhook($event2)->assertOk();
        $this->assertEquals(2, Payment::where('agreement_id', $this->agreement->id)->where('type', 'subscription')->count());
    }

    // ---------------------------------------------------------------
    // invoice.payment_failed
    // ---------------------------------------------------------------

    public function test_invoice_payment_failed(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_invfail_1',
            'status' => 'active',
        ]);

        $event = $this->fakeEvent('invoice.payment_failed', [
            'id' => 'in_fail_001',
            'subscription' => 'sub_invfail_1',
        ]);

        $this->postWebhook($event)->assertOk();

        $subscription->refresh();
        $this->assertEquals('past_due', $subscription->status->value);
    }

    public function test_invoice_payment_failed_no_subscription(): void
    {
        $event = $this->fakeEvent('invoice.payment_failed', [
            'id' => 'in_fail_orphan',
            'subscription' => 'sub_ghost',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    public function test_invoice_payment_failed_marks_pending_payment(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_invfail_2',
            'status' => 'active',
        ]);

        Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_invoice_id' => 'in_fail_002',
            'stripe_subscription_id' => 'sub_invfail_2',
            'type' => 'subscription',
            'status' => PaymentStatus::Pending,
            'amount_pence' => 2999,
        ]);

        $event = $this->fakeEvent('invoice.payment_failed', [
            'id' => 'in_fail_002',
            'subscription' => 'sub_invfail_2',
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payments', [
            'stripe_invoice_id' => 'in_fail_002',
            'status' => PaymentStatus::Failed->value,
        ]);
    }

    // ---------------------------------------------------------------
    // customer.subscription.updated
    // ---------------------------------------------------------------

    public function test_subscription_updated(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_upd_1',
            'status' => 'trialing',
        ]);

        $event = $this->fakeEvent('customer.subscription.updated', [
            'id' => 'sub_upd_1',
            'status' => 'active',
            'cancel_at_period_end' => false,
            'current_period_start' => now()->timestamp,
            'current_period_end' => now()->addMonth()->timestamp,
        ]);

        $this->postWebhook($event)->assertOk();

        $subscription->refresh();
        $this->assertEquals('active', $subscription->status->value);
        $this->assertFalse($subscription->cancel_at_period_end);
        $this->assertNotNull($subscription->current_period_start);
        $this->assertNotNull($subscription->current_period_end);
    }

    public function test_subscription_updated_cancel_at_period_end(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_upd_2',
            'status' => 'active',
            'cancel_at_period_end' => false,
        ]);

        $event = $this->fakeEvent('customer.subscription.updated', [
            'id' => 'sub_upd_2',
            'status' => 'active',
            'cancel_at_period_end' => true,
        ]);

        $this->postWebhook($event)->assertOk();

        $subscription->refresh();
        $this->assertTrue($subscription->cancel_at_period_end);
    }

    public function test_subscription_updated_unknown(): void
    {
        $event = $this->fakeEvent('customer.subscription.updated', [
            'id' => 'sub_unknown_upd',
            'status' => 'active',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    // ---------------------------------------------------------------
    // customer.subscription.deleted
    // ---------------------------------------------------------------

    public function test_subscription_deleted(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_del_1',
            'status' => 'active',
        ]);

        $event = $this->fakeEvent('customer.subscription.deleted', [
            'id' => 'sub_del_1',
        ]);

        $this->postWebhook($event)->assertOk();

        $subscription->refresh();
        $this->assertEquals('canceled', $subscription->status->value);
        $this->assertNotNull($subscription->ended_at);
    }

    public function test_subscription_deleted_unknown(): void
    {
        $event = $this->fakeEvent('customer.subscription.deleted', [
            'id' => 'sub_ghost_del',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    // ---------------------------------------------------------------
    // charge.refunded
    // ---------------------------------------------------------------

    public function test_charge_refunded_partial(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_1',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $event = $this->fakeEvent('charge.refunded', [
            'id' => 'ch_refund_1',
            'payment_intent' => 'pi_refund_1',
            'refunds' => [
                'data' => [
                    [
                        'id' => 're_partial_1',
                        'amount' => 3000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'requested_by_customer',
                    ],
                ],
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payment_refunds', [
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_partial_1',
            'amount_pence' => 3000,
            'status' => 'succeeded',
            'reason' => 'requested_by_customer',
        ]);

        $payment->refresh();
        $this->assertEquals(PaymentStatus::PartiallyRefunded, $payment->status);
        $this->assertEquals(3000, $payment->refunded_amount_pence);
    }

    public function test_charge_refunded_full(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_full_refund',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $event = $this->fakeEvent('charge.refunded', [
            'id' => 'ch_full_refund',
            'payment_intent' => 'pi_full_refund',
            'refunds' => [
                'data' => [
                    [
                        'id' => 're_full_1',
                        'amount' => 10000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'duplicate',
                    ],
                ],
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Refunded, $payment->status);
        $this->assertEquals(10000, $payment->refunded_amount_pence);
    }

    public function test_charge_refunded_multiple_refunds(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_multi_refund',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $event = $this->fakeEvent('charge.refunded', [
            'id' => 'ch_multi_refund',
            'payment_intent' => 'pi_multi_refund',
            'refunds' => [
                'data' => [
                    [
                        'id' => 're_multi_1',
                        'amount' => 2000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'requested_by_customer',
                    ],
                    [
                        'id' => 're_multi_2',
                        'amount' => 3000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'requested_by_customer',
                    ],
                ],
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertEquals(2, $payment->refunds()->count());

        $payment->refresh();
        $this->assertEquals(PaymentStatus::PartiallyRefunded, $payment->status);
        $this->assertEquals(5000, $payment->refunded_amount_pence);
    }

    public function test_charge_refunded_no_payment_intent(): void
    {
        $event = $this->fakeEvent('charge.refunded', [
            'id' => 'ch_no_pi',
            'payment_intent' => null,
            'refunds' => ['data' => []],
        ]);

        $this->postWebhook($event)->assertOk();
    }

    public function test_charge_refunded_unknown_payment(): void
    {
        $event = $this->fakeEvent('charge.refunded', [
            'id' => 'ch_ghost_refund',
            'payment_intent' => 'pi_ghost_refund',
            'refunds' => [
                'data' => [
                    [
                        'id' => 're_ghost',
                        'amount' => 1000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                    ],
                ],
            ],
        ]);

        $this->postWebhook($event)->assertOk();
    }

    // ---------------------------------------------------------------
    // Edge cases
    // ---------------------------------------------------------------

    public function test_missing_signature_returns_400(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->never();
        });

        $this->postJson('/stripe/webhook', [])
            ->assertStatus(400)
            ->assertJson(['error' => 'Invalid signature or configuration']);
    }

    public function test_not_configured_returns_400(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->once()->andReturn(false);
        });

        $this->postJson('/stripe/webhook', [], ['Stripe-Signature' => 'sig_xxx'])
            ->assertStatus(400);
    }

    public function test_invalid_signature_returns_400(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->once()->andReturn(true);
            $mock->shouldReceive('constructEvent')->once()->andThrow(
                new \Exception('Invalid signature')
            );
        });

        $this->postJson('/stripe/webhook', [], ['Stripe-Signature' => 'bad_sig'])
            ->assertStatus(400);
    }

    public function test_unknown_event_type_returns_received(): void
    {
        $event = $this->fakeEvent('unknown.event.type', [
            'id' => 'obj_unknown',
        ]);

        $this->postWebhook($event)->assertOk()->assertJson(['received' => true]);

        $this->assertDatabaseHas('payment_events', [
            'type' => 'unknown.event.type',
        ]);
    }

    public function test_each_event_creates_payment_event_record(): void
    {
        $event = $this->fakeEvent('checkout.session.completed', [
            'mode' => 'payment',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_evt_record',
            'amount_total' => 1000,
            'currency' => 'gbp',
            'metadata' => ['agreement_id' => $this->agreement->id, 'payment_type' => 'full'],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payment_events', [
            'stripe_event_id' => $event->id,
            'type' => 'checkout.session.completed',
        ]);

        $pe = PaymentEvent::where('stripe_event_id', $event->id)->first();
        $this->assertNotNull($pe->processed_at);
        $this->assertNotEmpty($pe->payload);
    }

    // ---------------------------------------------------------------
    // invoice.payment_action_required
    // ---------------------------------------------------------------

    public function test_invoice_payment_action_required(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_1',
            'status' => 'active',
        ]);

        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_1',
            'stripe_payment_intent_id' => 'pi_action_1',
            'type' => 'subscription',
            'status' => PaymentStatus::Pending,
            'amount_pence' => 2999,
        ]);

        $event = $this->fakeEvent('invoice.payment_action_required', [
            'id' => 'in_action_001',
            'subscription' => 'sub_action_1',
            'payment_intent' => 'pi_action_1',
            'hosted_invoice_url' => 'https://invoice.stripe.com/abc',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::RequiresAction, $payment->status);
        $this->assertNotNull($payment->action_required_url);
        $this->assertEquals('https://invoice.stripe.com/abc', $payment->action_required_url);
        $this->assertNotNull($payment->metadata);
    }

    public function test_invoice_payment_action_required_falls_back_to_invoice_id(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_2',
            'status' => 'active',
        ]);

        Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_2',
            'stripe_invoice_id' => 'in_action_002',
            'type' => 'subscription',
            'status' => PaymentStatus::Pending,
            'amount_pence' => 5000,
        ]);

        $event = $this->fakeEvent('invoice.payment_action_required', [
            'id' => 'in_action_002',
            'subscription' => 'sub_action_2',
            'payment_intent' => null,
            'hosted_invoice_url' => 'https://invoice.stripe.com/xyz',
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payments', [
            'stripe_invoice_id' => 'in_action_002',
            'status' => PaymentStatus::RequiresAction->value,
        ]);
    }

    public function test_invoice_payment_action_required_does_not_mark_milestone_paid(): void
    {
        $milestone = AgreementMilestone::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'amount_pence' => 5000,
            'status' => MilestoneStatus::Pending,
            'order_index' => 1,
        ]);

        $this->agreement->update(['payment_type' => AgreementPaymentType::Milestone]);

        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_payment_intent_id' => 'pi_action_ms',
            'type' => 'milestone',
            'milestone_id' => $milestone->id,
            'status' => PaymentStatus::Pending,
            'amount_pence' => 5000,
        ]);

        $event = $this->fakeEvent('invoice.payment_action_required', [
            'id' => 'in_action_ms',
            'subscription' => null,
            'payment_intent' => 'pi_action_ms',
            'hosted_invoice_url' => null,
        ]);

        $this->postWebhook($event)->assertOk();

        $milestone->refresh();
        $this->assertEquals(MilestoneStatus::Pending, $milestone->status);
        $this->assertNull($milestone->paid_at);

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::Pending, $this->agreement->status);
    }

    public function test_invoice_payment_action_required_does_not_call_apply_success(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_nosuccess',
            'status' => 'active',
        ]);

        Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_nosuccess',
            'stripe_payment_intent_id' => 'pi_action_nosuccess',
            'type' => 'subscription',
            'status' => PaymentStatus::Pending,
            'amount_pence' => 2999,
        ]);

        $event = $this->fakeEvent('invoice.payment_action_required', [
            'id' => 'in_action_nosuccess',
            'subscription' => 'sub_action_nosuccess',
            'payment_intent' => 'pi_action_nosuccess',
            'hosted_invoice_url' => null,
        ]);

        $this->postWebhook($event)->assertOk();

        $this->agreement->refresh();
        $this->assertEquals(AgreementStatus::Pending, $this->agreement->status);
    }

    public function test_invoice_payment_action_required_no_matching_payment(): void
    {
        $event = $this->fakeEvent('invoice.payment_action_required', [
            'id' => 'in_action_orphan',
            'subscription' => 'sub_ghost_action',
            'payment_intent' => 'pi_ghost_action',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    public function test_invoice_payment_action_required_is_idempotent(): void
    {
        $subscription = AgreementSubscription::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_idem',
            'status' => 'active',
        ]);

        Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'version_id' => $this->version->id,
            'stripe_subscription_id' => 'sub_action_idem',
            'stripe_payment_intent_id' => 'pi_action_idem',
            'type' => 'subscription',
            'status' => PaymentStatus::Pending,
            'amount_pence' => 2999,
        ]);

        $event = $this->fakeEvent('invoice.payment_action_required', [
            'id' => 'in_action_idem_001',
            'subscription' => 'sub_action_idem',
            'payment_intent' => 'pi_action_idem',
            'hosted_invoice_url' => 'https://invoice.stripe.com/abc',
        ]);

        $this->postWebhook($event)->assertOk();
        $this->assertDatabaseCount('payments', 1);

        $this->postWebhook($event)->assertOk();
        $this->assertDatabaseCount('payments', 1);
    }

    // ---------------------------------------------------------------
    // refund.created
    // ---------------------------------------------------------------

    public function test_refund_created(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_created_1',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $event = $this->fakeEvent('refund.created', [
            'id' => 're_created_1',
            'payment_intent' => 'pi_refund_created_1',
            'amount' => 3000,
            'currency' => 'gbp',
            'status' => 'succeeded',
            'reason' => 'requested_by_customer',
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payment_refunds', [
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_created_1',
            'amount_pence' => 3000,
            'status' => 'succeeded',
            'reason' => 'requested_by_customer',
        ]);

        $payment->refresh();
        $this->assertEquals(PaymentStatus::PartiallyRefunded, $payment->status);
        $this->assertEquals(3000, $payment->refunded_amount_pence);
    }

    public function test_refund_created_pending_does_not_mark_payment_refunded(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_pending_1',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $event = $this->fakeEvent('refund.created', [
            'id' => 're_pending_1',
            'payment_intent' => 'pi_refund_pending_1',
            'amount' => 5000,
            'currency' => 'gbp',
            'status' => 'pending',
            'reason' => 'requested_by_customer',
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertDatabaseHas('payment_refunds', [
            'stripe_refund_id' => 're_pending_1',
            'status' => 'pending',
        ]);

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Succeeded, $payment->status);
        $this->assertEquals(0, $payment->refunded_amount_pence);
    }

    public function test_refund_created_is_idempotent(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_idem_1',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        $event = $this->fakeEvent('refund.created', [
            'id' => 're_idem_1',
            'payment_intent' => 'pi_refund_idem_1',
            'amount' => 2000,
            'currency' => 'gbp',
            'status' => 'succeeded',
        ]);

        $this->postWebhook($event)->assertOk();
        $this->assertEquals(1, PaymentRefund::where('stripe_refund_id', 're_idem_1')->count());

        $this->postWebhook($event)->assertOk();
        $this->assertEquals(1, PaymentRefund::where('stripe_refund_id', 're_idem_1')->count());
    }

    public function test_refund_created_no_payment_intent(): void
    {
        $event = $this->fakeEvent('refund.created', [
            'id' => 're_no_pi',
            'payment_intent' => null,
            'amount' => 1000,
            'currency' => 'gbp',
            'status' => 'succeeded',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    public function test_refund_created_unknown_payment(): void
    {
        $event = $this->fakeEvent('refund.created', [
            'id' => 're_ghost_created',
            'payment_intent' => 'pi_ghost_created',
            'amount' => 1000,
            'currency' => 'gbp',
            'status' => 'succeeded',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    // ---------------------------------------------------------------
    // refund.updated
    // ---------------------------------------------------------------

    public function test_refund_updated_succeeded(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_upd_1',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        PaymentRefund::factory()->create([
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_upd_1',
            'amount_pence' => 4000,
            'status' => 'pending',
            'currency' => 'gbp',
        ]);

        $event = $this->fakeEvent('refund.updated', [
            'id' => 're_upd_1',
            'payment_intent' => 'pi_refund_upd_1',
            'amount' => 4000,
            'currency' => 'gbp',
            'status' => 'succeeded',
            'failure_code' => null,
            'failure_message' => null,
        ]);

        $this->postWebhook($event)->assertOk();

        $refund = PaymentRefund::where('stripe_refund_id', 're_upd_1')->first();
        $this->assertEquals('succeeded', $refund->status);
        $this->assertNotNull($refund->processed_at);

        $payment->refresh();
        $this->assertEquals(PaymentStatus::PartiallyRefunded, $payment->status);
        $this->assertEquals(4000, $payment->refunded_amount_pence);
    }

    public function test_refund_updated_full_refund(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_upd_full',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 5000,
        ]);

        PaymentRefund::factory()->create([
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_upd_full',
            'amount_pence' => 5000,
            'status' => 'pending',
            'currency' => 'gbp',
        ]);

        $event = $this->fakeEvent('refund.updated', [
            'id' => 're_upd_full',
            'payment_intent' => 'pi_refund_upd_full',
            'amount' => 5000,
            'currency' => 'gbp',
            'status' => 'succeeded',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Refunded, $payment->status);
        $this->assertEquals(5000, $payment->refunded_amount_pence);
    }

    public function test_refund_updated_to_failed(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_upd_fail',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 8000,
        ]);

        PaymentRefund::factory()->create([
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_upd_fail',
            'amount_pence' => 3000,
            'status' => 'pending',
            'currency' => 'gbp',
        ]);

        $event = $this->fakeEvent('refund.updated', [
            'id' => 're_upd_fail',
            'payment_intent' => 'pi_refund_upd_fail',
            'amount' => 3000,
            'currency' => 'gbp',
            'status' => 'failed',
            'failure_code' => 'lost_or_stolen_card',
            'failure_message' => 'This card has been reported as lost or stolen.',
        ]);

        $this->postWebhook($event)->assertOk();

        $refund = PaymentRefund::where('stripe_refund_id', 're_upd_fail')->first();
        $this->assertEquals('failed', $refund->status);
        $this->assertEquals('lost_or_stolen_card', $refund->failure_code);
        $this->assertEquals('This card has been reported as lost or stolen.', $refund->failure_message);

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Succeeded, $payment->status);
        $this->assertEquals(0, $payment->refunded_amount_pence);
    }

    public function test_refund_updated_no_existing_refund(): void
    {
        $event = $this->fakeEvent('refund.updated', [
            'id' => 're_upd_ghost',
            'payment_intent' => 'pi_ghost_upd',
            'amount' => 1000,
            'currency' => 'gbp',
            'status' => 'succeeded',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    // ---------------------------------------------------------------
    // refund.failed
    // ---------------------------------------------------------------

    public function test_refund_failed(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_fail_1',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        PaymentRefund::factory()->create([
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_fail_1',
            'amount_pence' => 3000,
            'status' => 'pending',
            'currency' => 'gbp',
        ]);

        $event = $this->fakeEvent('refund.failed', [
            'id' => 're_fail_1',
            'payment_intent' => 'pi_refund_fail_1',
            'amount' => 3000,
            'currency' => 'gbp',
            'status' => 'failed',
            'failure_code' => 'bank_declined',
            'failure_message' => 'The bank declined the refund.',
        ]);

        $this->postWebhook($event)->assertOk();

        $refund = PaymentRefund::where('stripe_refund_id', 're_fail_1')->first();
        $this->assertEquals('failed', $refund->status);
        $this->assertEquals('bank_declined', $refund->failure_code);
        $this->assertEquals('The bank declined the refund.', $refund->failure_message);

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Succeeded, $payment->status);
        $this->assertEquals(0, $payment->refunded_amount_pence);
    }

    public function test_refund_failed_restores_payment_status(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_fail_restore',
            'status' => PaymentStatus::PartiallyRefunded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 3000,
        ]);

        PaymentRefund::factory()->create([
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_fail_restore',
            'amount_pence' => 3000,
            'status' => 'succeeded',
            'currency' => 'gbp',
        ]);

        $event = $this->fakeEvent('refund.failed', [
            'id' => 're_fail_restore',
            'payment_intent' => 'pi_refund_fail_restore',
            'amount' => 3000,
            'currency' => 'gbp',
            'status' => 'failed',
            'failure_code' => 'expired_card',
            'failure_message' => 'Refund could not be processed to this expired card.',
        ]);

        $this->postWebhook($event)->assertOk();

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Succeeded, $payment->status);
        $this->assertEquals(0, $payment->refunded_amount_pence);
    }

    public function test_refund_failed_no_existing_refund(): void
    {
        $event = $this->fakeEvent('refund.failed', [
            'id' => 're_fail_ghost',
            'payment_intent' => 'pi_ghost_fail',
            'amount' => 1000,
            'currency' => 'gbp',
            'status' => 'failed',
        ]);

        $this->postWebhook($event)->assertOk();
    }

    public function test_refund_failed_is_idempotent(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_refund_fail_idem',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
        ]);

        PaymentRefund::factory()->create([
            'payment_id' => $payment->id,
            'stripe_refund_id' => 're_fail_idem',
            'amount_pence' => 5000,
            'status' => 'pending',
            'currency' => 'gbp',
        ]);

        $event = $this->fakeEvent('refund.failed', [
            'id' => 're_fail_idem',
            'payment_intent' => 'pi_refund_fail_idem',
            'amount' => 5000,
            'currency' => 'gbp',
            'status' => 'failed',
            'failure_code' => 'bank_declined',
        ]);

        $this->postWebhook($event)->assertOk();

        $refund = PaymentRefund::where('stripe_refund_id', 're_fail_idem')->first();
        $this->assertEquals('failed', $refund->status);

        $this->postWebhook($event)->assertOk();
        $refund->refresh();
        $this->assertEquals('failed', $refund->status);
    }

    // ---------------------------------------------------------------
    // Cross-event: charge.refunded + refund.created idempotency
    // ---------------------------------------------------------------

    public function test_charge_refunded_and_refund_created_no_duplicate_records(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_cross_refund',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $chargeEvent = $this->fakeEvent('charge.refunded', [
            'id' => 'evt_charge_cross',
            'payment_intent' => 'pi_cross_refund',
            'refunds' => [
                'data' => [
                    [
                        'id' => 're_cross_1',
                        'amount' => 4000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'requested_by_customer',
                    ],
                ],
            ],
        ]);

        $this->postWebhook($chargeEvent)->assertOk();
        $this->assertEquals(1, PaymentRefund::where('stripe_refund_id', 're_cross_1')->count());

        $refundEvent = $this->fakeEvent('refund.created', [
            'id' => 're_cross_1',
            'payment_intent' => 'pi_cross_refund',
            'amount' => 4000,
            'currency' => 'gbp',
            'status' => 'succeeded',
            'reason' => 'requested_by_customer',
        ]);

        $this->postWebhook($refundEvent)->assertOk();
        $this->assertEquals(1, PaymentRefund::where('stripe_refund_id', 're_cross_1')->count());

        $payment->refresh();
        $this->assertEquals(PaymentStatus::PartiallyRefunded, $payment->status);
        $this->assertEquals(4000, $payment->refunded_amount_pence);
    }

    public function test_existing_charge_refunded_behavior_still_works(): void
    {
        $payment = Payment::factory()->create([
            'agreement_id' => $this->agreement->id,
            'stripe_payment_intent_id' => 'pi_charge_still_works',
            'status' => PaymentStatus::Succeeded,
            'type' => 'full',
            'amount_pence' => 10000,
            'refunded_amount_pence' => 0,
        ]);

        $event = $this->fakeEvent('charge.refunded', [
            'id' => 'ch_still_works',
            'payment_intent' => 'pi_charge_still_works',
            'refunds' => [
                'data' => [
                    [
                        'id' => 're_still_works_1',
                        'amount' => 3000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'duplicate',
                    ],
                    [
                        'id' => 're_still_works_2',
                        'amount' => 7000,
                        'currency' => 'gbp',
                        'status' => 'succeeded',
                        'reason' => 'requested_by_customer',
                    ],
                ],
            ],
        ]);

        $this->postWebhook($event)->assertOk();

        $this->assertEquals(2, $payment->refunds()->count());

        $payment->refresh();
        $this->assertEquals(PaymentStatus::Refunded, $payment->status);
        $this->assertEquals(10000, $payment->refunded_amount_pence);
    }
}
