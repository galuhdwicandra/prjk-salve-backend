<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Selaras dengan DashboardSummaryRequest (gate/ability yang sama)
        return $this->user()?->can('dashboard.summary') === true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
            'branch_id' => ['nullable', 'uuid'],
            // filter spesifik opsional
            'method' => ['nullable', 'string', 'max:32'], // untuk sales (payments)
            'status' => ['nullable', 'string', 'max:32'], // untuk orders/receivables
            'format' => ['nullable', 'in:csv,xlsx'],
            'delimiter' => ['nullable', 'in:comma,semicolon,tab'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $u = $this->user();
        if ($u && !$u->hasRole('Superadmin') && !$this->input('branch_id')) {
            $this->merge(['branch_id' => $u->branch_id]);
        }
    }

    public function fromDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('from'))->startOfDay();
    }

    public function toDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('to'))->endOfDay();
    }

    public function branchId(): ?string
    {
        return $this->input('branch_id');
    }
}
