<?php

namespace App\Http\Requests;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
            $this->route('purchase_order') ? 'update' : 'create',
            $this->route('purchase_order') ?? PurchaseOrder::class
        );
    }

    public function rules(): array
    {
        return [
            'supplier_id'          => ['required','exists:suppliers,id'],
            'ordered_at'           => ['nullable','date'],
            'expected_at'          => ['nullable','date'],
            'note'                 => ['nullable','string'],
            'lines'                => ['array'],              // 明細（store/update 共通）
            'lines.*.item_id'      => ['required','exists:items,id'],
            'lines.*.qty'          => ['required','numeric','min:0.001'],
            'lines.*.unit_cost'    => ['required','numeric','min:0'],
        ];
    }
}
