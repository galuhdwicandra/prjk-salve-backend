<?php
namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
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
        /** @var Customer $customer */
        $customer = $this->route('customer');
        return $this->user()?->can('update', $customer) ?? false;
    }

    public function rules(): array
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');
        $branchId = $customer->branch_id;

        return [
            'name'     => ['sometimes', 'required', 'string', 'max:150'],
            'whatsapp' => [
                'sometimes',
                'required',
                'string',
                'max:32',
                Rule::unique('customers', 'whatsapp')
                    ->where(fn($q) => $q->where('branch_id', $branchId))
                    ->ignore($customer->id, 'id'),
            ],
            'address'  => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes'    => ['sometimes', 'nullable', 'string'],
            'tags'     => ['sometimes', 'nullable', 'array', 'max:10'],
            'tags.*'   => ['string', Rule::in(self::ALLOWED_TAGS)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $payload = [];

        if ($this->has('whatsapp')) {
            $payload['whatsapp'] = preg_replace('/\s+/', '', (string) $this->input('whatsapp'));
        }

        if ($this->has('tags') && is_array($this->input('tags'))) {
            $payload['tags'] = collect($this->input('tags', []))
                ->map(fn($tag) => trim((string) $tag))
                ->filter(fn($tag) => $tag !== '')
                ->unique()
                ->values()
                ->all();
        }

        if (!empty($payload)) {
            $this->merge($payload);
        }
    }
}