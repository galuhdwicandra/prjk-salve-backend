<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyManualAdjustRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:add,subtract,set'],
            'amount' => [
                'required',
                'integer',
                'max:1000',
                function ($attribute, $value, $fail) {
                    $type = (string) $this->input('type');
                    $amount = (int) $value;

                    if ($type === 'set' && $amount < 0) {
                        $fail('Jumlah stamp minimal 0 untuk tipe set.');
                        return;
                    }

                    if ($type !== 'set' && $amount < 1) {
                        $fail('Jumlah stamp minimal 1 untuk tambah atau kurangi stamp.');
                    }
                },
            ],
            'note' => ['nullable', 'string', 'max:255'],
            'branch_id' => ['nullable', 'uuid'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipe adjustment wajib diisi.',
            'type.in' => 'Tipe adjustment tidak valid.',
            'amount.required' => 'Jumlah stamp wajib diisi.',
            'amount.integer' => 'Jumlah stamp harus berupa angka bulat.',
            'amount.max' => 'Jumlah stamp terlalu besar.',
            'branch_id.uuid' => 'Format branch_id tidak valid.',
        ];
    }
}
