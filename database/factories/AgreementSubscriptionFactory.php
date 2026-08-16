<?php

namespace Database\Factories;

use App\Enums\SubscriptionFrequency;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgreementSubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agreement_id' => 1,
            'version_id' => null,
            'title' => $this->faker->words(3, true),
            'amount_pence' => $this->faker->randomElement([4999, 9999, 29900, 120000]),
            'frequency' => SubscriptionFrequency::Monthly,
            'stripe_customer_id' => 'cus_'.strtolower(str()->random(12)),
            'stripe_subscription_id' => 'sub_'.strtolower(str()->random(12)),
            'stripe_price_id' => 'price_'.strtolower(str()->random(12)),
            'status' => SubscriptionStatus::Active,
        ];
    }
}
