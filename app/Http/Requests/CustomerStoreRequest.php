<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Customer::class) ?? false;
    }

    public function rules(): array
    {
        $branchId = $this->input('branch_id') ?: $this->user()?->branch_id;

        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:150'],
            'whatsapp' => [
                'required',
                'string',
                'max:32',
                Rule::unique('customers', 'whatsapp')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalisasi ringan: trim dan hilangkan spasi pada whatsapp
        $wa = preg_replace('/\s+/', '', (string) $this->input('whatsapp'));
        $this->merge(['whatsapp' => $wa]);
    }
}
