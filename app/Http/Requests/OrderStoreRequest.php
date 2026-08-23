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

    protected function normalizeLocal(?string $dt): ?string
    {
        if (! $dt) {
            return null;
        }

        $s = str_replace('T', ' ', trim($dt));
        $s = preg_replace('/Z$/', '', $s);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
            return $s;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $s)) {
            return $s . ':00';
        }

        try {
            return \Carbon\CarbonImmutable::parse($s)->toDateString();
        } catch (\Throwable) {
            return $s;
        }
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('customer_id')) {
            $data['customer_id'] = trim((string) $this->input('customer_id'));
        }

        if ($this->has('notes')) {
            $data['notes'] = ($this->input('notes') === null) ? null : trim((string) $this->input('notes'));
        }

        if ($this->has('discount_value')) {
            $data['discount_value'] = is_numeric($this->input('discount_value'))
                ? (float) $this->input('discount_value')
                : $this->input('discount_value');
        }

        if ($this->has('items') && is_array($this->input('items'))) {
            $items = $this->input('items');
            foreach ($items as $k => $row) {
                if (isset($row['discount_value'])) {
                    $items[$k]['discount_value'] = is_numeric($row['discount_value'])
                        ? (float) $row['discount_value']
                        : $row['discount_value'];
                }
                if (isset($row['price'])) {
                    $items[$k]['price'] = is_numeric($row['price'])
                        ? (float) $row['price']
                        : $row['price'];
                }
            }
            $data['items'] = $items;
        }

        if ($this->has('received_at')) {
            $data['received_at'] = $this->normalizeLocal($this->input('received_at'));
        }

        if ($this->has('ready_at')) {
            $data['ready_at'] = $this->normalizeLocal($this->input('ready_at'));
        }

        if ($data !== []) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        $user     = $this->user();
        $branchId = $user?->all_branches
            ? (string) $this->input('branch_id')
            : $user?->branch_id;

        return [
            'branch_id'              => ['nullable', 'uuid', 'exists:branches,id'],
            'client_ref'             => ['nullable', 'uuid'],
            'customer_id'            => [
                'required',
                'uuid',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'notes'                  => ['nullable', 'string'],
            'discount_type'          => ['nullable', Rule::in(['NOMINAL', 'PERCENT'])],
            'processing_destination' => ['nullable', Rule::in(['workshop', 'vendor'])],
            'destination_branch_id'  => [
                'nullable', 'uuid',
                Rule::requiredIf(fn() => $this->input('processing_destination') === 'workshop'),
                Rule::exists('branches', 'id')->where(fn($q) => $q->where('type', 'workshop')),
                function ($attribute, $value, $fail) {
                    if ($value !== null && $this->input('processing_destination') !== 'workshop') {
                        $fail('Cabang tujuan hanya berlaku untuk tujuan workshop.');
                    }
                },
            ],
            'discount_value'         => [
                'nullable', 'numeric', 'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->input('discount_type') === 'PERCENT' && (float) $value > 100) {
                        $fail('Diskon persen tidak boleh lebih dari 100.');
                    }
                },
            ],

            'items'                  => ['required', 'array', 'min:1'],
            'items.*.service_id'     => ['required', 'uuid', 'exists:services,id'],
            'items.*.qty'            => ['required', 'numeric', 'gt:0'],
            'items.*.price'          => ['nullable', 'numeric', 'min:0'],
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

            'received_at'            => ['required', 'date'],
            'ready_at'               => ['required', 'date', 'after_or_equal:received_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'    => 'Pelanggan wajib dipilih.',
            'customer_id.uuid'        => 'Pelanggan tidak valid.',
            'customer_id.exists'      => 'Pelanggan tidak ditemukan di cabang Anda.',

            'received_at.required'    => 'Tanggal masuk wajib diisi.',
            'received_at.date'        => 'Tanggal masuk tidak valid.',

            'ready_at.required'       => 'Tanggal selesai wajib diisi.',
            'ready_at.date'           => 'Tanggal selesai tidak valid.',
            'ready_at.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal masuk.',
        ];
    }
}
