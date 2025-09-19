<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockMovementPolicy
{
    
        public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, StockMovement $movement): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Manager', 'Admin']);
    }

    public function update(User $user, StockMovement $movement): bool
    {
        return in_array($user->role, ['Manager', 'Admin']);
    }

    public function delete(User $user, StockMovement $movement): bool
    {
        return $user->role === 'Admin';
    }

}
