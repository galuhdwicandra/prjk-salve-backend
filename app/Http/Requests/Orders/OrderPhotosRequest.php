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
            'photos.before.*' => ['image', 'max:4096'], // ~4 MB per file
            'photos.after.*'  => ['image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.before.*.image' => 'File "before" harus berupa gambar.',
            'photos.after.*.image'  => 'File "after" harus berupa gambar.',
        ];
    }
}
