<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return $this->user()->can(
            $this->route('supplier') ? 'update' : 'create',
            $this->route('supplier') ?? Supplier::class
        );
    }

    public function rules(): array
    {
        return [
            'name'      => ['required','string','max:255'],
            'email'     => ['nullable','email'],
            'phone'     => ['nullable','string','max:50'],
            'is_active' => ['boolean'],
            'note'      => ['nullable','string'],
        ];
    }
    
}
