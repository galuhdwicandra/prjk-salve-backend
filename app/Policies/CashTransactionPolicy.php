<?php

namespace App\Policies;

use App\Models\CashTransaction;
use App\Models\User;

class CashTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CashTransaction $transaction): bool
    {
        return $user->canAccessBranch((string) $transaction->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, CashTransaction $transaction): bool
    {
        return $user->canManageBranch((string) $transaction->branch_id);
    }

    public function delete(User $user, CashTransaction $transaction): bool
    {
        return $user->canManageBranch((string) $transaction->branch_id);
    }
}
