<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MenuItemPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, MenuItem $m): bool { return true; }
    public function create(User $user): bool { return in_array($user->role, ['Manager','Admin']); }
    public function update(User $user, MenuItem $m): bool { return in_array($user->role, ['Manager','Admin']); }
    public function delete(User $user, MenuItem $m): bool { return $user->role === 'Admin'; }

    }
