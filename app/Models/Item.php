<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory; 
    
    protected $fillable = ['sku','name','unit','standard_cost','is_active','category_id'];
    protected $casts = [
        'standard_cost' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function category()
{
    return $this->belongsTo(Category::class);
}
}
