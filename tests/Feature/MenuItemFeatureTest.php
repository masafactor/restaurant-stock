<?php

use App\Models\{Item, MenuItem};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Manager can create & update menu with ingredients (cost is calculated), but cannot delete', function () {
    loginAs('Manager');

    $sugar = Item::factory()->create(['standard_cost' => 0.5]);
    $milk  = Item::factory()->create(['standard_cost' => 1.2]);

    $payload = [
        'name' => 'Milk Tea',
        'price' => 480,
        'ingredients' => [
            ['item_id'=>$sugar->id, 'qty'=>10, 'wastage_rate'=>0],   // 10 * 0.5 = 5.0
            ['item_id'=>$milk->id,  'qty'=>200, 'wastage_rate'=>10], // 200/0.9 * 1.2 ≒ 266.667 * 1.2 = 320.0004
        ],
    ];

    $res = $this->postJson('/menu-items', $payload)->assertCreated();
    $menuId = $res->json('id');

    // 再取得して cost が入っていることを確認（概算でOK）
    $menu = MenuItem::findOrFail($menuId);
    expect((float)$menu->cost)
    ->toBeGreaterThan(270.0)
    ->toBeLessThan(273.0);

    // 更新（材料入替）
    $this->putJson("/menu-items/{$menuId}", [
        'name' => 'Royal Milk Tea',
        'price' => 520,
        'ingredients' => [
            ['item_id'=>$milk->id,  'qty'=>220],
        ],
    ])->assertOk()
      ->assertJsonFragment(['name'=>'Royal Milk Tea']);
    
    // 削除は不可
    $this->deleteJson("/menu-items/{$menuId}")->assertForbidden();
});

it('Admin can delete menu item', function () {
    loginAs('Admin');

    $menu = MenuItem::factory()->create();

    $this->deleteJson("/menu-items/{$menu->id}")
        ->assertNoContent();
});
