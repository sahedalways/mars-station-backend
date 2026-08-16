<?php

namespace Database\Factories;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'description' => $this->faker->paragraph(3),
            'status' => ComplaintStatus::New,
            'is_read' => false,
        ];
    }
}
