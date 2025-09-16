<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function viewAny(User $user)  { return true; }
    public function view(User $user, Category $category) { return true; }

    public function create(User $user)  { return in_array($user->role, ['Manager', 'Admin']); }
    public function update(User $user, Category $category) { return in_array($user->role, ['Manager', 'Admin']); }
    public function delete(User $user, Category $category) { return $user->role === 'Admin'; }
}
