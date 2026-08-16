<?php

namespace Database\Factories;

use App\Enums\AgreementPaymentType;
use App\Enums\AgreementStatus;
use App\Support\AgreementNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgreementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agreement_number' => AgreementNumber::generate(),
            'title' => $this->faker->sentence(3),
            'client_name' => $this->faker->name(),
            'client_email' => $this->faker->safeEmail(),
            'client_mobile' => $this->faker->phoneNumber(),
            'validity_date' => $this->faker->dateTimeBetween('+1 week', '+6 months')->format('Y-m-d'),
            'payment_type' => AgreementPaymentType::None,
            'status' => AgreementStatus::Pending,
            'is_archived' => false,
            'created_by' => null,
        ];
    }
}
