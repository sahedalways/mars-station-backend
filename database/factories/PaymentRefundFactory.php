<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentRefundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'stripe_refund_id' => 're_' . strtolower(str()->random(12)),
            'amount_pence' => $this->faker->randomElement([1000, 2000, 3000, 5000]),
            'currency' => 'gbp',
            'status' => 'pending',
            'reason' => null,
        ];
    }
}
