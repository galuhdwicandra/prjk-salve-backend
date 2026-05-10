<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderLoyaltyCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user?->hasRole('Superadmin') || $user?->hasRole('Admin Cabang');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reward' => strtoupper(trim((string) $this->input('reward', ''))),
            'note'   => trim((string) $this->input('note', '')),
        ]);
    }

    public function rules(): array
    {
        return [
            'reward' => ['required', 'string', 'in:DISC25,FREE100'],
            'note'   => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'reward.required' => 'Jenis reward loyalty wajib dipilih.',
            'reward.in'       => 'Jenis reward loyalty tidak valid.',

            'note.required' => 'Alasan koreksi wajib diisi.',
            'note.min'      => 'Alasan koreksi minimal 10 karakter.',
            'note.max'      => 'Alasan koreksi maksimal 500 karakter.',
        ];
    }
}
