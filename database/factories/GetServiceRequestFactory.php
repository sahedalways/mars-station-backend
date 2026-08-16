<?php

namespace Database\Factories;

use App\Enums\GetServiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class GetServiceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company' => $this->faker->company(),
            'preferred_contact' => $this->faker->randomElement(['email', 'phone']),
            'selected_services' => $this->faker->randomElements(['web', 'mobile', 'uiux', 'branding'], 2),
            'additional_notes' => $this->faker->sentence(),
            'status' => GetServiceStatus::New,
            'is_read' => false,
        ];
    }
}
