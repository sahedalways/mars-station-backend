<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'icon' => $this->faker->randomElement(['globe', 'smartphone', 'palette', 'briefcase', 'megaphone', 'search', 'video', 'code']),
            'title' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(['web', 'mobile', 'uiux', 'branding', 'marketing', 'seo', 'video', 'ecommerce']),
            'description' => $this->faker->paragraph(3),
            'order_index' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
