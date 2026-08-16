<?php

namespace Database\Factories;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'dp_path' => null,
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'rating' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->paragraph(2),
            'status' => ReviewStatus::Pending,
        ];
    }
}
