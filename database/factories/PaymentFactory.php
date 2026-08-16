<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agreement_id' => 1,
            'version_id' => null,
            'type' => PaymentType::Full,
            'milestone_id' => null,
            'stripe_payment_intent_id' => 'pi_'.strtolower(str()->random(12)),
            'stripe_invoice_id' => null,
            'amount_pence' => $this->faker->randomElement([38099, 75000, 120000]),
            'currency' => 'gbp',
            'status' => PaymentStatus::Pending,
            'refunded_amount_pence' => 0,
        ];
    }
}
