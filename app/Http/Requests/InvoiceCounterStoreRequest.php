<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\DocumentNumberService;
use Illuminate\Validation\Rule;

class InvoiceCounterStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // di-controller tetap pakai Policy pada Branch
    }

    public function rules(): array
    {
        return [
            'branch_id'    => ['required', 'uuid', 'exists:branches,id'],
            'doc_key'      => ['required', 'string', Rule::in(array_keys(DocumentNumberService::DOCUMENTS))],
            'format'       => ['required', 'string', 'max:40', 'regex:/\[NUMBER(:\d+)?\]/'],
            'reset_policy' => ['required', 'in:monthly,yearly,never'],
            'seq'          => ['required', 'integer', 'min:0', 'max:999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'branch_id is required',
            'doc_key.in'         => 'doc_key is not a known document',
            'format.regex'       => 'format must contain [NUMBER]',
            'reset_policy.in'    => 'reset_policy must be monthly, yearly, or never',
        ];
    }
}
