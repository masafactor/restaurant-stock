<?php

use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a purchase order line', function () {
    // 前提データ
    $supplier = Supplier::factory()->create();
    $order    = PurchaseOrder::factory()->create(['supplier_id' => $supplier->id]);
    $item     = Item::factory()->create();

    // 行の作成
    $line = PurchaseOrderLine::factory()->create([
        'purchase_order_id' => $order->id,
        'item_id'           => $item->id,
    ]);

    expect($line->purchase_order_id)->toBe($order->id)
        ->and($line->item_id)->toBe($item->id)
        ->and($line->qty)->toBeFloat(); // 型の確認など必要に応じて
});
