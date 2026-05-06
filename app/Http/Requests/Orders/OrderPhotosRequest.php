<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderPhotosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos.before'   => ['required', 'array', 'min:1'],
            'photos.before.*' => ['required', 'image', 'max:4096'],

            'photos.after'    => ['nullable', 'array'],
            'photos.after.*'  => ['nullable', 'image', 'max:4096'],

            'replace_existing' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.before.required' => 'Foto before wajib diupload.',
            'photos.before.array' => 'Format foto before tidak valid.',
            'photos.before.min' => 'Minimal upload 1 foto before.',
            'photos.before.*.required' => 'File foto before wajib diisi.',
            'photos.before.*.image' => 'File before harus berupa gambar.',
            'photos.before.*.max' => 'Ukuran foto before maksimal 4MB.',

            'photos.after.array' => 'Format foto after tidak valid.',
            'photos.after.*.image' => 'File after harus berupa gambar.',
            'photos.after.*.max' => 'Ukuran foto after maksimal 4MB.',

            'replace_existing.boolean' => 'Format pilihan ganti foto lama tidak valid.',
        ];
    }
}
