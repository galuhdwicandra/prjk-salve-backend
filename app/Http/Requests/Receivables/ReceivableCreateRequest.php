<?php

namespace App\Http\Requests\Receivables;

use Illuminate\Foundation\Http\FormRequest;

class ReceivableCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'due_date' => ['nullable', 'date'],
        ];
    }
}
