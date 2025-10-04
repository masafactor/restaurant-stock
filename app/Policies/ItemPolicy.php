<?php 
namespace App\Policies;
use App\Models\Item;
use App\Models\User; 
use Illuminate\Auth\Access\Response; 
class ItemPolicy { 
    /** * Determine whether the user can view any models. */
     public function viewAny(\App\Models\User $user): bool { return in_array($user->role, ['Admin','Manager','Staff']); } 
     public function view(\App\Models\User $user, \App\Models\Item $item): bool { return $this->viewAny($user); } 
     public function create(\App\Models\User $user): bool { return in_array($user->role, ['Admin','Manager']); }
     public function update(\App\Models\User $user, \App\Models\Item $item): bool { return in_array($user->role, ['Admin','Manager']); }
     public function delete(\App\Models\User $user, \App\Models\Item $item): bool { return $user->role === 'Admin'; }
}
