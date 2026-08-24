<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderBulkVoidRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_ids' => ['required', 'array', 'min:1', 'max:100'],
            'order_ids.*' => ['required', 'uuid', 'distinct', 'exists:orders,id'],
            'reason' => ['required', 'string', 'min:5', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_ids.required' => 'Pilih minimal satu receipt.',
            'order_ids.min' => 'Pilih minimal satu receipt.',
            'order_ids.max' => 'Maksimal 100 receipt dalam satu proses.',
            'order_ids.*.exists' => 'Salah satu receipt tidak ditemukan.',
            'order_ids.*.distinct' => 'Receipt tidak boleh dipilih lebih dari sekali.',
            'reason.required' => 'Alasan void wajib diisi.',
            'reason.min' => 'Alasan void minimal 5 karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('reason')) {
            $this->merge([
                'reason' => trim((string) $this->input('reason')),
            ]);
        }
    }
}
