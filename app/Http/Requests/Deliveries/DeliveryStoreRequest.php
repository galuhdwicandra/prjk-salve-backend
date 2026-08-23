<?php
namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Delivery::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        // normalisasi 'type'
        if ($t = $this->input('type')) {
            $this->merge(['type' => strtolower($t)]);
        }
    }

    public function rules(): array
    {
        $branchIds = $this->user()?->branchScopeIds();

        return [
            'order_id' => [
                'required', 'uuid',
                Rule::exists('orders', 'id')
                    ->when($branchIds !== null, fn($q) => $q->where(
                        fn($w) => $w->whereIn('branch_id', $branchIds)
                            ->orWhereIn('destination_branch_id', $branchIds)
                    )),
            ],
            'type'     => ['required', Rule::in(['pickup', 'delivery', 'return', 'transfer_out', 'transfer_in'])],
            'zone_id'  => ['nullable', 'uuid'],
            'fee'      => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
