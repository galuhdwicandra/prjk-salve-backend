<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCounterUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['Superadmin', 'Admin Cabang']) ?? false;
    }

    public function rules(): array
    {
        return [
            'format'       => ['required', 'string', 'max:40', 'regex:/\[NUMBER(:\d+)?\]/'],
            'reset_policy' => ['required', 'in:monthly,yearly,never'],
            'seq'          => ['required', 'integer', 'min:0', 'max:999999'],
        ];
    }
}
