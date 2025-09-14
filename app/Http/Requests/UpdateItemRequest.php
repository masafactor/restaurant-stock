<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $item = $this->route('item');
        return $this->user()->can('update', $item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('item')->id;
        return [
        'sku' => ['required','string','max:100',"unique:items,sku,{$id}"],
        'name' => ['required','string','max:255'],
        'unit' => ['required','string','max:20'],
        'standard_cost' => ['required','numeric','min:0'],
        'is_active' => ['boolean'],

        ];
    }
}
