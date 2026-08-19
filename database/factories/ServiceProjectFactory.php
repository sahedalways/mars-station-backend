<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceProject;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceProjectFactory extends Factory
{
    protected $model = ServiceProject::class;

    public function definition(): array
    {
        $filename = $this->faker->uuid . '.jpg';

        return [
            'service_id' => Service::factory(),
            'title' => $this->faker->words(2, true),
            'picture_path' => 'services/projects/' . $filename,
            'order_index' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function withNullPath(): static
    {
        return $this->state(fn () => ['picture_path' => null]);
    }

    public function withMissingFile(): static
    {
        return $this->state(fn () => ['picture_path' => 'services/projects/nonexistent_' . $this->faker->uuid . '.jpg']);
    }
}
