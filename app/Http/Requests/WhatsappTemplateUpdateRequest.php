<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WhatsappTemplateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'content' => ['sometimes', 'required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'key' => ['sometimes', 'required', 'string', Rule::in(['receipt_pending', 'receipt_paid'])],
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
        ];
    }
}