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

    /**
     * Normalisasi waktu lokal "naif" ke 'Y-m-d H:i:s' tanpa konversi zona waktu.
     */
    protected function normalizeLocal(?string $dt): ?string
    {
        if (!$dt) return null;
        $s = str_replace('T', ' ', trim($dt));
        $s = preg_replace('/Z$/', '', $s); // buang Z bila ada
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $s)) {
            $s .= ':00';
        }
        try {
            return \Carbon\CarbonImmutable::createFromFormat('Y-m-d H:i:s', $s)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            // Jika format tak cocok, biarkan apa adanya agar validator 'date' menangkapnya.
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
        // Normalisasi datetime lokal (tanpa konversi TZ) untuk kolom TIMESTAMP WITHOUT TIME ZONE
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
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'customer_id' => [
                'required',
                'uuid',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.service_id' => ['required', 'uuid', 'exists:services,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'received_at' => ['nullable', 'date'],
            'ready_at'    => ['nullable', 'date', 'after_or_equal:received_at'],
            // price dari klien diabaikan; server akan hitung pakai PricingService
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.uuid' => 'Pelanggan tidak valid.',
            'customer_id.exists' => 'Pelanggan tidak ditemukan di cabang Anda.',
        ];
    }
}
