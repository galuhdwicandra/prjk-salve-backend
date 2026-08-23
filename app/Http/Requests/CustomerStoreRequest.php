<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Customer::class) ?? false;
    }

    public function rules(): array
    {
        $branchId = $this->input('branch_id') ?: $this->user()?->branch_id;

        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'name'      => ['required', 'string', 'max:150'],
            'whatsapp'  => [
                'required',
                'string',
                'max:32',
                Rule::unique('customers', 'whatsapp')
                    ->where(fn($q) => $q->where('branch_id', $branchId)->where('is_active', true)),
            ],
            'address'   => ['nullable', 'string', 'max:255'],
            'notes'     => ['nullable', 'string'],
            'tags'      => ['nullable', 'array', 'max:10'],
            'tags.*'    => ['string', Rule::exists('customer_labels', 'name')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $wa = preg_replace('/\D+/', '', (string) $this->input('whatsapp'));

        $tags = $this->input('tags', null);

        if (is_array($tags)) {
            $tags = collect($tags)
                ->map(fn($tag) => trim((string) $tag))
                ->filter(fn($tag) => $tag !== '')
                ->unique()
                ->values()
                ->all();
        }

        $this->merge([
            'whatsapp' => $wa,
            'tags'     => $tags,
        ]);
    }
}
