<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
 
    public function authorize(): bool
    {
        // Policy の create / update を自動判定
        return $this->user()->can(
            $this->route('category') ? 'update' : 'create',
            $this->route('category') ?? \App\Models\Category::class
        );
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
