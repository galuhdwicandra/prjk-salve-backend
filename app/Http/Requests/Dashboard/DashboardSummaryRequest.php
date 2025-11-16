<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class DashboardSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('dashboard.summary') === true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
            'branch_id' => ['nullable', 'uuid'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Non-Superadmin wajib terikat ke cabangnya sendiri bila branch_id tidak diisi.
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
