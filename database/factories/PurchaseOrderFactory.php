<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'status'      => $this->faker->randomElement(['draft', 'submitted', 'received', 'completed']),
            'ordered_at'  => $this->faker->date(),
            'expected_at' => $this->faker->date(),
            'received_at' => null,
            'total'       => $this->faker->randomFloat(2, 100, 5000),
            'note'        => $this->faker->optional()->sentence,
        ];
    }
}
