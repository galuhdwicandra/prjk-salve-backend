<?php

namespace App\Http\Requests\Receivables;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Receivable;

class ReceivableSettleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'in:CASH,QRIS,TRANSFER'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $id = $this->route('id');
            $rcv = $id ? Receivable::query()->with('order')->find($id) : null;
            if (!$rcv) {
                return;
            }
            $amt = (float) $this->input('amount', 0);
            $remaining = (float) $rcv->remaining_amount;
            if ($amt > $remaining + 1e-6) {
                $v->errors()->add('amount', 'Jumlah melebihi sisa piutang.');
            }
        });
    }
}
