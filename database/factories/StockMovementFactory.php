<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
            'item_id'   => Item::factory(),
            'type'      => $this->faker->randomElement(['receive','waste','adjust']),
            'qty'       => $this->faker->randomFloat(3, 1, 100),
            'unit_cost' => $this->faker->optional()->randomFloat(4, 0.1, 1000),
            'moved_at'  => $this->faker->date(),
            'note'      => $this->faker->optional()->sentence(),
        ];
    }
}
