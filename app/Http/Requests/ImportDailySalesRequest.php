<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportDailySalesRequest extends FormRequest
{

    public function authorize(): bool
    {
        // 取込は Manager/Admin のみ
        return $this->user()?->role !== 'Staff';
    }
    public function rules(): array
    {
        return [
            'file' => ['required','file','mimes:csv,txt'],
            'sold_at' => ['required','date'], // CSVに日付が無い前提。列にある場合は不要
            'source' => ['nullable','string','max:255'],
        ];
    }
}
