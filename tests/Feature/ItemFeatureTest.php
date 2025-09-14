<?php

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function loginAs(string $role) {
    $user = User::factory()->create(['role' => $role]);
    test()->actingAs($user);
    return $user;
}

it('Staff can list & view items, but cannot create/update/delete', function () {
    loginAs('Staff');
    $item = Item::factory()->create();

    $this->getJson('/items')->assertOk();
    $this->getJson("/items/{$item->id}")->assertOk();

    $payload = ['sku'=>'A001','name'=>'Apple','unit'=>'pcs','standard_cost'=>10];
    $this->postJson('/items', $payload)->assertForbidden();
    $this->putJson("/items/{$item->id}", $payload)->assertForbidden();
    $this->deleteJson("/items/{$item->id}")->assertForbidden();
});

it('Manager can create & update, but cannot delete', function () {
    loginAs('Manager');

    $payload = ['sku'=>'B001','name'=>'Banana','unit'=>'pcs','standard_cost'=>12];
    $res = $this->postJson('/items', $payload)->assertCreated();
    $id = $res->json('id');

    $this->putJson("/items/{$id}", ['sku'=>'B001','name'=>'Banana XL','unit'=>'pcs','standard_cost'=>15])
        ->assertOk();

    $this->deleteJson("/items/{$id}")->assertForbidden();
});

it('Admin can do everything', function () {
    loginAs('Admin');
    $payload = ['sku'=>'C001','name'=>'Cherry','unit'=>'g','standard_cost'=>0.25];
    $res = $this->postJson('/items', $payload)->assertCreated();
    $id = $res->json('id');

    $this->putJson("/items/{$id}", ['sku'=>'C001','name'=>'Cherry Jam','unit'=>'g','standard_cost'=>0.3,'is_active'=>true])
        ->assertOk();

    $this->deleteJson("/items/{$id}")->assertNoContent();
});
