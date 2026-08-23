<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->canAccessBranch((string) $expense->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->canManageBranch((string) $expense->branch_id);
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->canManageBranch((string) $expense->branch_id);
    }
}
