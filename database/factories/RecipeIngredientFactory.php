<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeIngredient>
 */
class RecipeIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_item_id' => MenuItem::factory(),
            'item_id'      => Item::factory(),
            'qty'          => fake()->randomFloat(3, 0.1, 5),
            'wastage_rate' => fake()->optional()->randomFloat(2, 0, 20),
        ];
    }
}
