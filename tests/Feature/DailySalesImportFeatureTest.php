<?php

use App\Models\{Item, MenuItem, RecipeIngredient, StockMovement};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

it('Manager can import daily sales and create consume stock movements', function () {
    loginAs('Manager');

    // レシピ: ミルク 0.2, 砂糖 0.01
    $milk  = Item::factory()->create(['standard_cost'=>1.2]);
    $sugar = Item::factory()->create(['standard_cost'=>0.5]);

    $menu = MenuItem::factory()->create(['name'=>'Milk Tea']);
    RecipeIngredient::factory()->create(['menu_item_id'=>$menu->id,'item_id'=>$milk->id,'qty'=>0.2,'wastage_rate'=>10]);
    RecipeIngredient::factory()->create(['menu_item_id'=>$menu->id,'item_id'=>$sugar->id,'qty'=>0.01]);

    // CSV: menu_item_id, qty_sold
    $csv = "{$menu->id},10\n"; // 10杯売れた
    $file = UploadedFile::fake()->createWithContent('sales.csv', $csv);

    $soldAt = now()->toDateString();
    $this->postJson('/daily-sales/import', ['file'=>$file,'sold_at'=>$soldAt])
         ->assertCreated()
         ->assertJson(['imported'=>1]);

    // consume が作られているか（件数だけざっくり）
    expect(StockMovement::where('type','consume')->count())->toBe(2);

    // ミルクの消費量 = (0.2 / 0.9) * 10 = 2.222...
    $milkConsume = StockMovement::where('item_id',$milk->id)->where('type','consume')->first();
    expect((float)$milkConsume->qty)->toBeGreaterThan(2.22)->toBeLessThan(2.23);
});
