<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\StockMovement::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'item_id'   => ['required','exists:items,id'],
        'type'      => ['required','in:receive,waste,adjust'],
        'qty'       => ['required','numeric','gt:0'],
        'unit_cost' => ['nullable','numeric','gte:0'],
        'moved_at'  => ['nullable','date'],
        'note'      => ['nullable','string'],
    ];
    }
}
