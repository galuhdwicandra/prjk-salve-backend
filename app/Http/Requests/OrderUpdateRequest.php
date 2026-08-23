<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function normalizeLocal(?string $dt): ?string
    {
        if (! $dt) {
            return null;
        }

        $s = trim((string) $dt);

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $s)) {
            $date = substr($s, 0, 10);

            return \Carbon\CarbonImmutable::createFromFormat(
                'Y-m-d',
                $date,
                'Asia/Jakarta'
            )->startOfDay()->format('Y-m-d H:i:s');
        }

        try {
            return \Carbon\CarbonImmutable::parse($s, 'Asia/Jakarta')
                ->timezone('Asia/Jakarta')
                ->startOfDay()
                ->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return $s;
        }
    }

    public function rules(): array
    {
        $order   = $this->route('order');
        $orderId = $order instanceof \App\Models\Order  ? $order->getKey() : $order;

        return [
            'invoice_no'             => [
                'sometimes',
                'required',
                'string',
                'max:40',
                Rule::unique('orders', 'invoice_no')->ignore($orderId),
            ],
            'customer_id'            => ['sometimes', 'nullable', 'uuid', 'exists:customers,id'],
            'discount_type'          => ['sometimes', 'nullable', Rule::in(['NOMINAL', 'PERCENT'])],
            'processing_destination' => ['sometimes', 'nullable', Rule::in(['workshop', 'vendor'])],
            'destination_branch_id'  => [
                'sometimes', 'nullable', 'uuid',
                Rule::requiredIf(fn() => $this->input('processing_destination') === 'workshop'),
                Rule::exists('branches', 'id')->where(fn($q) => $q->where('type', 'workshop')),
                function ($attribute, $value, $fail) {
                    if ($value !== null && $this->input('processing_destination') !== 'workshop') {
                        $fail('Cabang tujuan hanya berlaku untuk tujuan workshop.');
                    }
                },
            ],
            'discount_value'         => [
                'sometimes', 'nullable', 'numeric', 'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->input('discount_type') === 'PERCENT' && (float) $value > 100) {
                        $fail('Diskon persen tidak boleh lebih dari 100.');
                    }
                },
            ],
            'notes'                  => ['sometimes', 'nullable', 'string', 'max:500'],

            'items'                  => ['sometimes', 'array', 'min:1'],
            'items.*.id'             => ['sometimes', 'uuid', 'exists:order_items,id'],
            'items.*.service_id'     => ['required_with:items', 'uuid', 'exists:services,id', 'distinct'],
            'items.*.qty'            => ['required_with:items', 'integer', 'min:1'],
            'items.*.price'          => ['nullable', 'numeric', 'min:0'],
            'items.*.note'           => ['sometimes', 'nullable', 'string', 'max:300'],
            'items.*.discount_type'  => ['nullable', Rule::in(['NOMINAL', 'PERCENT'])],
            'items.*.discount_value' => Rule::forEach(function ($value, $attribute) {
                $index = (int) explode('.', $attribute)[1];
                $type  = $this->input("items.$index.discount_type");

                return [
                    'nullable', 'numeric', 'min:0',
                    function ($attr, $val, $fail) use ($type) {
                        if ($type === 'PERCENT' && (float) $val > 100) {
                            $fail('Diskon persen item tidak boleh lebih dari 100.');
                        }
                    },
                ];
            }) ,

            'received_at'            => ['sometimes', 'required', 'date'],
            'ready_at'               => ['sometimes', 'required', 'date', 'after_or_equal:received_at'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (array_key_exists('customer_id', $data) && $data['customer_id'] !== null) {
            $data['customer_id'] = trim((string) $data['customer_id']);
        }

        if (array_key_exists('invoice_no', $data)) {
            $data['invoice_no'] = trim((string) $data['invoice_no']);
        }

        if (array_key_exists('notes', $data)) {
            $data['notes'] = $data['notes'] === null ? null : trim((string) $data['notes']);
        }

        if (isset($data['discount_value']) && $data['discount_value'] !== null) {
            $data['discount_value'] = is_numeric($data['discount_value']) ? (float) $data['discount_value'] : $data['discount_value'];
        }

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $k => $row) {
                if (isset($row['qty'])) {
                    $data['items'][$k]['qty'] = is_numeric($row['qty']) ? (int) $row['qty'] : $row['qty'];
                }
                if (isset($row['discount_value'])) {
                    $data['items'][$k]['discount_value'] = is_numeric($row['discount_value']) ? (float) $row['discount_value'] : $row['discount_value'];
                }
                if (isset($row['price'])) {
                    $data['items'][$k]['price'] = is_numeric($row['price']) ? (float) $row['price'] : $row['price'];
                }
            }
        }

        if (array_key_exists('received_at', $data)) {
            $data['received_at'] = $this->normalizeLocal($data['received_at']);
        }

        if (array_key_exists('ready_at', $data)) {
            $data['ready_at'] = $this->normalizeLocal($data['ready_at']);
        }

        $this->replace($data);
    }

    public function messages(): array
    {
        return [
            'received_at.required'    => 'Tanggal masuk wajib diisi.',
            'received_at.date'        => 'Tanggal masuk tidak valid.',

            'ready_at.required'       => 'Tanggal selesai wajib diisi.',
            'ready_at.date'           => 'Tanggal selesai tidak valid.',
            'ready_at.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal masuk.',
        ];
    }
}
