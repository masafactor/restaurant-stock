<?php

use App\Models\{Item, MenuItem, Supplier, PurchaseOrder, StockMovement, DailySale, RecipeIngredient};
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\getJson;


it('returns sales summary', function () {
    loginAs('Manager');

    $a = MenuItem::factory()->create(['price' => 500]);
    $b = MenuItem::factory()->create(['price' => 800]);

    DailySale::factory()->create(['sold_at'=>'2025-09-01', 'menu_item_id'=>$a->id, 'qty_sold'=>2.0]);
    DailySale::factory()->create(['sold_at'=>'2025-09-01', 'menu_item_id'=>$b->id, 'qty_sold'=>1.0]);
    DailySale::factory()->create(['sold_at'=>'2025-09-02', 'menu_item_id'=>$a->id, 'qty_sold'=>3.0]);

    // 9/1 売上 = 2*500 + 1*800 = 1800
    // 9/2 売上 = 3*500 = 1500
    // 合計 3300, 日数 2, 平均 1650
    getJson('/reports/sales-summary?from=2025-09-01&to=2025-09-30')
        ->assertOk()
        ->assertJsonFragment(['revenue' => 3300.0])
        ->assertJsonFragment(['days' => 2])
        ->assertJsonFragment(['avg_per_day' => 1650.0]);
});

it('returns purchase vs sales', function () {
    loginAs('Manager');

    // 売上（9/01 に 1800）
    $mi = MenuItem::factory()->create(['price' => 600]);
    DailySale::factory()->create(['sold_at'=>'2025-09-01', 'menu_item_id'=>$mi->id, 'qty_sold'=>3.0]); // 3*600=1800

    // 仕入（9/01 に 800） … POと明細を作る
    $supplier = Supplier::factory()->create();
    $po = PurchaseOrder::factory()->for($supplier)->create(['ordered_at'=>'2025-09-01']);
    // 明細（qty * unit_cost の合計が 800 になるように）
    DB::table('purchase_order_lines')->insert([
        'purchase_order_id' => $po->id,
        'item_id' => Item::factory()->create()->id,
        'qty' => 8,
        'unit_cost' => 100,
        'line_total' => 8 * 100,
        'created_at' => now(), 'updated_at' => now(),
    ]);

    getJson('/reports/purchase-vs-sales?from=2025-09-01&to=2025-09-30')
        ->assertOk()
        ->assertJsonStructure(['data'=>[['date','sales','purchases']]])
        ->assertJsonFragment(['date'=>'2025-09-01','sales'=>1800.0,'purchases'=>800.0]);
});

it('returns inventory valuation', function () {
    loginAs('Manager');

    $item = Item::factory()->create(['standard_cost'=>10]);
    StockMovement::factory()->create(['item_id'=>$item->id,'type'=>'receive','qty'=>50]);
    StockMovement::factory()->create(['item_id'=>$item->id,'type'=>'consume','qty'=>20]);

    getJson('/reports/inventory-valuation')
        ->assertOk()
        ->assertJsonStructure(['total_valuation','items']);
});
