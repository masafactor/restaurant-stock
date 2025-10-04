<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Location;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // create と update を自動判定（ポリシー前提）
        return $this->user()->can(
            $this->route('location') ? 'update' : 'create',
            $this->route('location') ?? Location::class
        );
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '名前',
            'is_active' => '有効',
        ];
    }
}
