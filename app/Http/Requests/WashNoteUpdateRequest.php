<?php

namespace App\Http\Requests;

class WashNoteUpdateRequest extends WashNoteStoreRequest
{
    public function authorize(): bool
    {
        $note = $this->route('wash_note'); // route-model binding
        return $this->user()?->can('update', $note) ?? false;
    }

    /**
     * PATCH: items tidak wajib dikirim. Aturan lain tetap mewarisi dari StoreRequest,
     * termasuk pengecualian Superadmin/Petugas Cuci dan validasi jam mulai/selesai.
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Jadikan items opsional saat update (tanpa min:1)
        $rules['items'] = ['sometimes', 'array'];
        // Nested fields hanya divalidasi jika items dikirim
        $rules['items.*.order_id'][0] = 'required_with:items';
        
        $rules['note_date'] = ['sometimes', 'date'];

        return $rules;
    }
}
