<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['name','category_id','price','cost','is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'cost'  => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function ingredients() { return $this->hasMany(RecipeIngredient::class); }

    /**
     * 原価を再計算して cost に保存
     */
    public function recalcCost(): void
    {
        $sum = $this->ingredients()->with('item:id,standard_cost')->get()
            ->reduce(function ($carry, RecipeIngredient $ri) {
                $waste = $ri->wastage_rate ? (1 - ($ri->wastage_rate / 100)) : 1;
                $unitCost = $ri->item->standard_cost ?? 0;
                return $carry + ($ri->qty / max($waste, 0.000001)) * $unitCost;
            }, 0.0);

        $this->cost = $sum;
        $this->save();
    }
}
