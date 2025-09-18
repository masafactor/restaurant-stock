<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
     use HasFactory;
     
    protected $fillable = ['purchase_order_id','item_id','qty','unit_cost','line_total'];

    public function purchaseOrder(){ return $this->belongsTo(PurchaseOrder::class); }
    public function item(){ return $this->belongsTo(Item::class); }

}
