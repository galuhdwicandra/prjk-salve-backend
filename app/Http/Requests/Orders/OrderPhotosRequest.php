<?php
namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderPhotosRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi akan dicek di Controller (authorize('update', $order))
        return true;
    }

    public function rules(): array
    {
        return [
            'photos.before'   => ['required', 'array', 'min:1'],
            'photos.before.*' => ['required', 'image', 'max:4096'],
            'photos.after'    => ['nullable', 'array'],
            'photos.after.*'  => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.before.required'   => 'Foto before wajib diupload.',
            'photos.before.array'      => 'Format foto before tidak valid.',
            'photos.before.min'        => 'Minimal 1 foto before wajib diupload.',
            'photos.before.*.required' => 'File foto before wajib diisi.',
            'photos.before.*.image'    => 'File "before" harus berupa gambar.',
            'photos.before.*.max'      => 'Ukuran foto before maksimal 4MB.',
            'photos.after.*.image'     => 'File "after" harus berupa gambar.',
            'photos.after.*.max'       => 'Ukuran foto after maksimal 4MB.',
        ];
    }
}
