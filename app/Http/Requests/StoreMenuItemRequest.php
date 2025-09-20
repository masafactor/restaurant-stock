<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\MenuItem::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'category_id' => ['nullable','exists:categories,id'],
            'price'       => ['nullable','numeric','gte:0'],
            'is_active'   => ['boolean'],

            // レシピ行（任意・配列渡し）
            'ingredients'                => ['nullable','array','max:200'],
            'ingredients.*.item_id'      => ['required','exists:items,id'],
            'ingredients.*.qty'          => ['required','numeric','gt:0'],
            'ingredients.*.wastage_rate' => ['nullable','numeric','between:0,100'],
        ];
    }

}
