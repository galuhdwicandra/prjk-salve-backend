<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicePriceSetRequest extends FormRequest
{
    public function authorize(): bool
    {
        // otorisasi dilakukan pada controller via policy Service + scope cabang
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'uuid', 'exists:services,id'],
            'branch_id' => ['required', 'uuid', 'exists:branches,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'service_id wajib diisi',
            'branch_id.required' => 'branch_id wajib diisi',
            'price.required' => 'price wajib diisi',
        ];
    }
}
