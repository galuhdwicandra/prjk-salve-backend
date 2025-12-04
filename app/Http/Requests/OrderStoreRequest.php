<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Order::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('customer_id')) {
            $this->merge([
                'customer_id' => trim((string) $this->input('customer_id')),
            ]);
        }
    }

    public function rules(): array
    {
        $branchId = $this->user()?->branch_id;

        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'customer_id' => [
                'required',
                'uuid',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.service_id' => ['required', 'uuid', 'exists:services,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'received_at' => ['nullable', 'date'],
            'ready_at'    => ['nullable', 'date', 'after_or_equal:received_at'],
            // price dari klien diabaikan; server akan hitung pakai PricingService
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.uuid' => 'Pelanggan tidak valid.',
            'customer_id.exists' => 'Pelanggan tidak ditemukan di cabang Anda.',
        ];
    }
}
