<?php
namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderVoidRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'min:5', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.min' => 'Alasan void minimal 5 karakter.',
        ];
    }
}
