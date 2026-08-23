<?php
namespace App\Http\Requests\Payments;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorize di controller (settlePayment/update)
    }

    public function rules(): array
    {
        return [
            'method'  => [
                'required',
                'string',
                Rule::in([
                    'PENDING',
                    'DP',
                    ...PaymentMethod::query()
                        ->where('is_active', true)
                        ->pluck('code')
                        ->all(),
                ]),
            ],
            'amount'  => ['required', 'numeric', 'gt:0'],
            'paid_at' => ['nullable', 'date'],
            'note'    => ['nullable', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'method.in' => 'Metode pembayaran tidak valid atau sedang nonaktif.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $amount = (float) $this->input('amount', 0);
            $order  = $this->route('order');
            $due    = max((float) $order->grand_total - (float) $order->paid_amount, 0);
            if ($amount > $due) {
                $v->errors()->add('amount', 'Nominal melebihi sisa tagihan.');
            }
        });
    }
}
