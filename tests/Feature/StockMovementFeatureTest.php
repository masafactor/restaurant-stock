<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{postJson, getJson};

// 必要ならこのテストファイルだけで RefreshDatabase 付与
uses(RefreshDatabase::class);

it('Admin can create a stock movement (receive)', function () {
    loginAs('Admin'); // いつものヘルパ

    $item = Item::factory()->create(['standard_cost' => 10]);

    $payload = [
        'item_id'   => $item->id,
        'type'      => 'receive',   // 受入 / waste / adjust など
        'qty'       => 5,
        'unit_cost' => 10,          // 必要に応じて
        'note'      => 'initial stock',
    ];

    postJson('/stock-movements', $payload)
        ->assertCreated()
        ->assertJsonFragment([
            'item_id' => $item->id,
            'type'    => 'receive',
            'qty'     => '5.000',
        ]);
});
