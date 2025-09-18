<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderLine>
 */
class PurchaseOrderLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty       = $this->faker->randomFloat(3, 1, 50);
        $unit_cost = $this->faker->randomFloat(4, 10, 500);
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'item_id'           => Item::factory(),
            'qty'               => $qty,
            'unit_cost'         => $unit_cost,
            'line_total'        => round($qty * $unit_cost, 2),
        ];
    }
}
