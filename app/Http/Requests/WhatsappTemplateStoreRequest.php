<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WhatsappTemplateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'key' => ['required', 'string', Rule::in(['receipt_pending', 'receipt_paid'])],
            'name' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}