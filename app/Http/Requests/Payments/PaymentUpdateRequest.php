<?php
namespace App\Http\Requests\Payments;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
            $order   = $this->route('order');
            $payment = $this->route('payment');

            if (! $order || ! $payment) {
                return;
            }

            $paidByOthers = (float) $order->paid_amount - (float) $payment->amount;
            $headroom     = max((float) $order->grand_total - $paidByOthers, 0);

            if ((float) $this->input('amount', 0) > $headroom) {
                $v->errors()->add('amount', 'Nominal melebihi sisa tagihan.');
            }
        });
    }
}
