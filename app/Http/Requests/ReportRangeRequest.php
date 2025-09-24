<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRangeRequest extends FormRequest
{
     public function authorize(): bool
    {
        return $this->user()?->can('viewReports') ?? false; // 後述のGate/Policyで許可
    }

    public function rules(): array
    {
        return [
            'from' => ['required','date','before_or_equal:to'],
            'to'   => ['required','date'],
            'limit' => ['nullable','integer','min:1','max:100'],
        ];
    }

    public function validatedRange(): array
    {
        $data = $this->validated();
        return [$data['from'], $data['to'], $data['limit'] ?? 10];
    }
}
