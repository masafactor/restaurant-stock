<?php
// tests/Feature/DashboardFeatureTest.php
use App\Models\{Item, Supplier, PurchaseOrder, MenuItem, DailySale, StockMovement};
use function Pest\Laravel\getJson;

it('returns dashboard summary for Manager', function () {
    loginAs('Manager');

    // ダミーデータ
    Supplier::factory()->count(2)->create();
    $item = Item::factory()->create(['standard_cost'=>10]);

    // 受入50/消費20 → 在庫30, 評価額=300
    StockMovement::factory()->create(['item_id'=>$item->id,'type'=>'receive','qty'=>50]);
    StockMovement::factory()->create(['item_id'=>$item->id,'type'=>'consume','qty'=>20]);

    // 売上（直近7日内に2日分）
    $mi = MenuItem::factory()->create(['price'=>500]);
    DailySale::factory()->create(['sold_at'=>now()->subDays(1)->toDateString(), 'menu_item_id'=>$mi->id, 'qty_sold'=>2]); // 1000
    DailySale::factory()->create(['sold_at'=>now()->toDateString(),            'menu_item_id'=>$mi->id, 'qty_sold'=>1]); // 500

    // 未完了の発注
    PurchaseOrder::factory()->create(['status'=>'submitted']);

    getJson('/dashboard/summary')
        ->assertOk()
        ->assertJsonStructure([
            'cards' => ['items','suppliers','open_pos','inventory_value'],
            'sales_last_7_days' => [['date','revenue']],
            'low_stock_items',
        ]);
});
