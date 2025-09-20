<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $fillable = ['menu_item_id','item_id','qty','wastage_rate'];

    protected $casts = [
        'qty' => 'decimal:3',
        'wastage_rate' => 'decimal:2',
    ];

    public function menuItem() { return $this->belongsTo(MenuItem::class); }
    public function item()     { return $this->belongsTo(Item::class); }
}
