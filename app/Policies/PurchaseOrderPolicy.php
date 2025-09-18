<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchaseOrderPolicy
{
    public function viewAny(User $u){ return true; }
    public function view(User $u, PurchaseOrder $po){ return true; }

    public function create(User $u){ return in_array($u->role, ['Manager','Admin']); }
    public function update(User $u, PurchaseOrder $po){ return in_array($u->role, ['Manager','Admin']); }
    public function delete(User $u, PurchaseOrder $po){ return $u->role === 'Admin'; }

    // 状態遷移
    public function submit(User $u, PurchaseOrder $po){ return in_array($u->role, ['Manager','Admin']); }
    public function receive(User $u, PurchaseOrder $po){ return in_array($u->role, ['Manager','Admin']); }
    public function close(User $u, PurchaseOrder $po){ return in_array($u->role, ['Manager','Admin']); }
}
