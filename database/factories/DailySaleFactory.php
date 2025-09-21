<?php

namespace Database\Factories;

use App\Models\DailySale;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailySale>
 */
class DailySaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DailySale::class;
    public function definition(): array
    {
        return [
            'sold_at'      => $this->faker->date(),
            'menu_item_id' => MenuItem::factory(),
            'qty_sold'     => $this->faker->randomFloat(3, 1, 100),
            'source'       => $this->faker->randomElement(['csv', 'manual']),
        ];
    }
}
