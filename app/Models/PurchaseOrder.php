<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    const STATUS_DRAFT     = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_RECEIVED  = 'received';
    const STATUS_CLOSED    = 'closed';

    protected $fillable = [
        'supplier_id','status','ordered_at','expected_at','received_at','total','note'
    ];

    protected $casts = [
        'ordered_at'  => 'date',
        'expected_at' => 'date',
        'received_at' => 'date',
    ];

    public function supplier(){ return $this->belongsTo(Supplier::class); }
    public function lines(){ return $this->hasMany(PurchaseOrderLine::class); }

    /** 合計を再計算 */
    public function recalcTotal(): void
    {
        $this->total = $this->lines()->sum('line_total');
        $this->save();
    }
}
