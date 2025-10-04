<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * ロケーションに紐づく在庫異動
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
