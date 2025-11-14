<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCounterUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['Superadmin', 'Admin Cabang']) ?? false;
    }

    public function rules(): array
    {
        return [
            'prefix' => ['required', 'string', 'regex:/^[A-Z]{2,8}$/'],
            'reset_policy' => ['required', 'in:monthly,never'],
            'seq' => ['required', 'integer', 'min:0'],
        ];
    }
}
