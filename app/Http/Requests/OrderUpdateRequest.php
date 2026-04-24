<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function rules(): array
    {
        return [
            'customer_id'        => ['sometimes', 'nullable', 'uuid', 'exists:customers,id'],
            'discount'           => ['sometimes', 'numeric', 'min:0'],
            'notes'              => ['sometimes', 'nullable', 'string', 'max:500'],

            'items'              => ['sometimes', 'array', 'min:1'],
            'items.*.id'         => ['sometimes', 'uuid', 'exists:order_items,id'],
            'items.*.service_id' => ['required_with:items', 'uuid', 'exists:services,id', 'distinct'],
            'items.*.qty'        => ['required_with:items', 'integer', 'min:1'],
            'items.*.note'       => ['sometimes', 'nullable', 'string', 'max:300'],

            'received_at'        => ['sometimes', 'required', 'date'],
            'ready_at'           => ['sometimes', 'required', 'date', 'after_or_equal:received_at'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (array_key_exists('customer_id', $data) && $data['customer_id'] !== null) {
            $data['customer_id'] = trim((string) $data['customer_id']);
        }

        if (array_key_exists('notes', $data)) {
            $data['notes'] = $data['notes'] === null ? null : trim((string) $data['notes']);
        }

        if (isset($data['discount']) && $data['discount'] !== null) {
            $data['discount'] = is_numeric($data['discount']) ? (float) $data['discount'] : $data['discount'];
        }

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $k => $row) {
                if (isset($row['qty'])) {
                    $data['items'][$k]['qty'] = is_numeric($row['qty']) ? (int) $row['qty'] : $row['qty'];
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
