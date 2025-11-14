<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorize di controller (settlePayment/update)
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'in:PENDING,DP,CASH,QRIS,TRANSFER'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $method = (string) $this->input('method');
            $amount = (float) $this->input('amount', 0);
            $grand = (float) $this->route('order')->grand_total; // order binding
            if ($method === 'DP' && $amount > $grand) {
                $v->errors()->add('amount', 'DP tidak boleh melebihi grand_total.');
            }
        });
    }
}
