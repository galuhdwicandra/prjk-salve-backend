<?php
namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
        && $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir', 'Akuntansi']);
    }

    public function rules(): array
    {
        return [
            'from'      => ['required', 'date_format:Y-m-d'],
            'to'        => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
            'branch_id' => ['nullable', 'uuid'],
                                                             // filter spesifik opsional
            'method'    => ['nullable', 'string', 'max:32'], // untuk sales (payments)
            'status'    => ['nullable', 'string', 'max:32'], // untuk orders/receivables
            'format'    => ['nullable', 'in:csv'],
            'delimiter' => ['nullable', 'in:comma,semicolon,tab'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function fromDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('from'))->startOfDay();
    }

    public function toDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('to'))->endOfDay();
    }

    public function branchIds(): ?array
    {
        $user = $this->user();

        if ($user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            $requested = $this->input('branch_id');
            return $requested ? [$requested] : null;
        }

        return $user->branchScopeIds($this->input('branch_id'));
    }
}
