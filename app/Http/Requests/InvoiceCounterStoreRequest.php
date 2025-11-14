<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCounterStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // di-controller tetap pakai Policy pada Branch
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'uuid', 'exists:branches,id'],
            'prefix' => ['required', 'string', 'max:8'],
            'reset_policy' => ['required', 'in:monthly,never'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'branch_id is required',
            'prefix.required' => 'prefix is required',
            'prefix.max' => 'prefix max length is 8 characters',
            'reset_policy.in' => 'reset_policy must be monthly or never',
        ];
    }
}
