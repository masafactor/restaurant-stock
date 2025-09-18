<?php

use App\Models\{Supplier, Item, PurchaseOrder};

it('Manager can create/submit/receive but not delete', function () {
    loginAs('Manager');

    $supplier = Supplier::factory()->create();
    $item = Item::factory()->create(['standard_cost'=>10]);

    // create
    $payload = [
        'supplier_id' => $supplier->id,
        'lines' => [
            ['item_id'=>$item->id, 'qty'=>5, 'unit_cost'=>10],
        ],
        'note' => 'test',
    ];
    $res = $this->postJson('/purchase-orders', $payload)->assertCreated();
    $id = $res->json('id');

    // submit
    $this->postJson("/purchase-orders/{$id}/submit")->assertOk()
         ->assertJsonFragment(['status'=>PurchaseOrder::STATUS_SUBMITTED]);

    // receive
    $this->postJson("/purchase-orders/{$id}/receive")->assertOk()
         ->assertJsonFragment(['status'=>PurchaseOrder::STATUS_RECEIVED]);

    // cannot delete
    $this->deleteJson("/purchase-orders/{$id}")->assertForbidden();
});

it('Admin can delete', function () {
    loginAs('Admin');
    $supplier = Supplier::factory()->create();
    $item = Item::factory()->create();
    $po = PurchaseOrder::factory()
        ->for($supplier)
        ->create();

    $this->deleteJson("/purchase-orders/{$po->id}")->assertNoContent();
});

