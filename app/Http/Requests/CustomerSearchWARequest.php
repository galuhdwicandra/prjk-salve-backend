<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSearchWARequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // authorize di controller via policy viewAny
    }

    public function rules(): array
    {
        return [
            'wa' => ['required', 'string', 'max:32'],
        ];
    }
}
