<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => strtoupper(fake()->bothify('ITM-###??')),
            'name' => fake()->words(2, true),
            'unit' => 'pcs',
            'standard_cost' => fake()->randomFloat(4, 0, 100),
            'is_active' => true,
            'category_id' => Category::factory(),
        ];
    }
}
