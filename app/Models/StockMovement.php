<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    /** @use HasFactory<\Database\Factories\StockMovementFactory> */
    use HasFactory;

    protected $fillable = [
        'item_id', 'type', 'qty', 'unit_cost', 'moved_at', 'note',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'unit_cost' => 'decimal:4',
        'moved_at' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
