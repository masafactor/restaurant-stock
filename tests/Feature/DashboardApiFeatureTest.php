<?php

use App\Models\User;
use function Pest\Laravel\getJson;

it('Admin and Manager can access dashboard summary', function () {
    foreach (['Admin','Manager'] as $role) {
        $u = User::factory()->create(['role'=>$role]);
        $this->actingAs($u);
        getJson('/dashboard/summary')->assertOk();
    }
});

it('Staff cannot access dashboard summary', function () {
    $u = User::factory()->create(['role'=>'Staff']);
    $this->actingAs($u);
    getJson('/dashboard/summary')->assertForbidden();
});
