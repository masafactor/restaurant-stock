<?php

use App\Models\Category;

use function Pest\Laravel\{getJson, postJson, putJson, deleteJson};

it('Admin can manage categories', function () {
    loginAs('Admin'); // テスト用ヘルパ（Items で使ったのと同じ）

    // Create
    $payload = ['name' => '食材', 'is_active' => true];
    $res = postJson('/categories', $payload)->assertCreated();
    $id = $res->json('id');

    // Read
    getJson("/categories/{$id}")
        ->assertOk()
        ->assertJsonFragment(['name' => '食材']);

    // Update
    putJson("/categories/{$id}", ['name' => '飲料', 'is_active' => false])
        ->assertOk()
        ->assertJsonFragment(['name' => '飲料', 'is_active' => false]);

    // Delete
    deleteJson("/categories/{$id}")->assertNoContent();
});

it('Manager can create & update but not delete', function () {
    loginAs('Manager');
    $cat = Category::factory()->create();

    // Create
    $res = postJson('/categories', ['name' => '備品'])->assertCreated();
    $id = $res->json('id');

    // Update
    putJson("/categories/{$id}", ['name' => '備品改'])->assertOk();

    // Delete should be forbidden
    deleteJson("/categories/{$id}")->assertForbidden();
});

it('Staff can only view', function () {
    loginAs('Staff');
    $cat = Category::factory()->create();

    // View all
    getJson('/categories')->assertOk();
    getJson("/categories/{$cat->id}")->assertOk();

    // Try to create/update/delete → forbidden
    postJson('/categories', ['name' => 'NG'])->assertForbidden();
    putJson("/categories/{$cat->id}", ['name' => 'NG'])->assertForbidden();
    deleteJson("/categories/{$cat->id}")->assertForbidden();
});
