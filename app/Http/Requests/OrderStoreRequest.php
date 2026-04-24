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
        $branchId = $this->user()?->branch_id;

        return [
            'branch_id'          => ['nullable', 'uuid', 'exists:branches,id'],
            'customer_id'        => [
                'required',
                'uuid',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'notes'              => ['nullable', 'string'],

            'items'              => ['required', 'array', 'min:1'],
            'items.*.service_id' => ['required', 'uuid', 'exists:services,id'],
            'items.*.qty'        => ['required', 'numeric', 'gt:0'],

            'received_at'        => ['required', 'date'],
            'ready_at'           => ['required', 'date', 'after_or_equal:received_at'],
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
