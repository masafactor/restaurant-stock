<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    // 一覧
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manager', 'Staff'], true);
    }

    // 詳細
    public function view(User $user, Location $location): bool
    {
        return $this->viewAny($user);
    }

    // 作成
    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manager'], true);
    }

    // 更新
    public function update(User $user, Location $location): bool
    {
        return in_array($user->role, ['Admin', 'Manager'], true);
    }

    // 削除
    public function delete(User $user, Location $location): bool
    {
        return $user->role === 'Admin';
    }
}
