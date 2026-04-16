<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
{
    private const ALLOWED_TAGS = [
        'VIP',
        'Langganan',
        'Corporate',
        'Member',
        'Prioritas',
        'Outlet',
        'Komplain',
        'Blacklist',
    ];

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
                Rule::unique('customers', 'whatsapp')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'address'   => ['nullable', 'string', 'max:255'],
            'notes'     => ['nullable', 'string'],
            'tags'      => ['nullable', 'array', 'max:10'],
            'tags.*'    => ['string', Rule::in(self::ALLOWED_TAGS)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $wa = preg_replace('/\s+/', '', (string) $this->input('whatsapp'));

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