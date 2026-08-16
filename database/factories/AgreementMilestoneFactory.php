<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AgreementMilestoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agreement_id' => 1,
            'version_id' => null,
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'amount_pence' => $this->faker->randomElement([25000, 50000, 100000, 250000]),
            'order_index' => 1,
            'status' => 'pending',
            'payment_id' => null,
        ];
    }
}
