<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Order;

class WashNoteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\WashNote::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Normalizer waktu: ubah "HH:MM:SS" → "HH:MM"
        $normTime = function ($t) {
            if ($t === null || $t === '') return null;
            $t = trim((string) $t);
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) {
                return substr($t, 0, 5);
            }
            return $t; // biarkan "HH:MM" apa adanya
        };

        // note_date tetap dirapikan
        if ($this->has('note_date')) {
            $this->merge(['note_date' => trim((string) $this->input('note_date'))]);
        }

        // items: pangkas detik untuk started_at/finished_at, kosong → null
        if ($this->has('items') && is_array($this->input('items'))) {
            $items = array_map(function ($it) use ($normTime) {
                $it = (array) $it;

                // Kosong "" → null untuk field tertentu
                foreach (['started_at', 'finished_at', 'note', 'process_status'] as $k) {
                    if (array_key_exists($k, $it) && $it[$k] === '') {
                        $it[$k] = null;
                    }
                }

                // Normalisasi format waktu agar sesuai rule date_format:H:i
                if (array_key_exists('started_at', $it)) {
                    $it['started_at'] = $normTime($it['started_at']);
                }
                if (array_key_exists('finished_at', $it)) {
                    $it['finished_at'] = $normTime($it['finished_at']);
                }

                // Rapikan order_id bila ada
                if (array_key_exists('order_id', $it) && $it['order_id'] !== null) {
                    $it['order_id'] = trim((string) $it['order_id']);
                }

                return $it;
            }, $this->input('items', []));

            $this->merge(['items' => $items]);
        }
    }


    public function rules(): array
    {
        $me = $this->user();

        return [
            'note_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],

            'items.*.order_id' => [
                'required',
                'uuid',
                // Default: semua order harus dari cabang user (selaras constraint proyek).
                // KECUALI Petugas Cuci: boleh lintas cabang (mengikuti kebutuhan operasional pusat).
                Rule::exists('orders', 'id')->where(function ($q) use ($me) {
                    // Superadmin & Petugas Cuci: tanpa filter cabang
                    if ($me->hasRole('Superadmin') || $me->hasRole('Petugas Cuci')) {
                        return $q;
                    }
                    // Selain itu: batasi ke cabang user bila ada
                    if ($me->branch_id) {
                        return $q->where('branch_id', $me->branch_id);
                    }
                    return $q;
                }),
            ],
            'items.*.qty' => ['nullable', 'numeric', 'min:0'],
            'items.*.process_status' => ['nullable', 'string', 'in:QUEUE,WASH,DRY,FINISHING,COMPLETED,PICKED_UP'], // refer SOP
            'items.*.started_at' => ['nullable', 'date_format:H:i'],
            'items.*.finished_at' => ['nullable', 'date_format:H:i'],
            'items.*.note' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $items = (array) $this->input('items', []);
            foreach ($items as $idx => $it) {
                $s = $it['started_at'] ?? null;
                $f = $it['finished_at'] ?? null;
                if ($s && $f && is_string($s) && is_string($f) && $f < $s) {
                    $v->errors()->add("items.$idx.finished_at", "Jam selesai harus ≥ jam mulai.");
                }
            }
        });
    }
}
