<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AgreementVersionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agreement_id' => 1,
            'version' => 1,
            'title' => $this->faker->sentence(3),
            'client_name' => $this->faker->name(),
            'client_email' => $this->faker->safeEmail(),
            'client_mobile' => $this->faker->phoneNumber(),
            'validity_date' => $this->faker->dateTimeBetween('+1 week', '+6 months')->format('Y-m-d'),
            'content' => $this->faker->paragraphs(4, true),
            'payment_config' => null,
            'status' => 'pending',
            'admin_id' => null,
        ];
    }
}
