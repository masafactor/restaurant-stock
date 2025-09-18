<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
{
    public function viewAny(User $u){ return true; }
    public function view(User $u, Supplier $s){ return true; }

    public function create(User $u){ return in_array($u->role, ['Manager','Admin']); }
    public function update(User $u, Supplier $s){ return in_array($u->role, ['Manager','Admin']); }
    public function delete(User $u, Supplier $s){ return $u->role === 'Admin'; }

}
