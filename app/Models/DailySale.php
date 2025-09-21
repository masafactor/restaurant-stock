<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    /** @use HasFactory<\Database\Factories\DailySaleFactory> */


    use HasFactory;

    protected $fillable = ['sold_at','menu_item_id','qty_sold','source'];

    protected $casts = [
        'sold_at'  => 'date',
        'qty_sold' => 'decimal:3',
    ];

    public function menuItem(){ return $this->belongsTo(MenuItem::class); }
}
