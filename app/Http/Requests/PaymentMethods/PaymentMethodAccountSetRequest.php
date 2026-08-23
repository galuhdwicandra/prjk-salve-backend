<?php
namespace App\Http\Requests\PaymentMethods;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodAccountSetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $branchId = $this->input('branch_id');

        return [
            'branch_id'  => ['required', 'uuid', 'exists:branches,id'],
            'account_id' => [
                'present',
                'nullable',
                'uuid',
                Rule::exists('accounting_accounts', 'id')
                    ->where('is_cash_account', true)
                    ->where('is_active', true)
                    ->where(fn($query) => $query->where(
                        fn($scope) => $scope->whereNull('branch_id')->orWhere('branch_id', $branchId)
                    )),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required'  => 'Cabang wajib dipilih.',
            'account_id.present' => 'Akun kas/bank wajib dikirim.',
            'account_id.exists'   => 'Akun harus akun kas/bank yang aktif dan tersedia untuk cabang tersebut.',
        ];
    }
}
