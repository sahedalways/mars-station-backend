<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AgreementLinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agreement_id' => 1,
            'version_id' => null,
            'token' => Str::random(64),
            'is_active' => true,
            'otp_enabled' => false,
            'created_by' => null,
        ];
    }
}
