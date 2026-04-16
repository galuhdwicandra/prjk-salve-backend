<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'effective_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:200'],
        ];
    }
}