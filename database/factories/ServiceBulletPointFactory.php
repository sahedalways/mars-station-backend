<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceBulletPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceBulletPointFactory extends Factory
{
    protected $model = ServiceBulletPoint::class;

    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'text' => $this->faker->sentence(4),
            'order_index' => $this->faker->numberBetween(0, 100),
        ];
    }
}
