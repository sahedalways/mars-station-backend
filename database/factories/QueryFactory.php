<?php

namespace Database\Factories;

use App\Enums\QueryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class QueryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'query' => $this->faker->paragraph(3),
            'status' => QueryStatus::New,
            'is_read' => false,
        ];
    }
}
