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
            'amount' => ['required', 'integer', 'min:1', 'max:1000'],
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
            'amount.min' => 'Jumlah stamp minimal 1.',
            'amount.max' => 'Jumlah stamp terlalu besar.',
            'branch_id.uuid' => 'Format branch_id tidak valid.',
        ];
    }
}