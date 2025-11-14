<?php

namespace App\Http\Requests\Expenses;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\Expense $expense */
        $expense = $this->route('expense');

        return $this->user()?->can('update', $expense) ?? false;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }
}
