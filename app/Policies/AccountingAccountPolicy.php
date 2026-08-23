<?php
namespace App\Policies;

use App\Models\AccountingAccount;
use App\Models\User;

class AccountingAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AccountingAccount $account): bool
    {
        return $account->branch_id === null
        || $user->canAccessBranch((string) $account->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, AccountingAccount $account): bool
    {
        if (! $user->canModule('set-coa') && ! $account->is_cash_account) {
            return false;
        }
        return $user->canManageBranch($account->branch_id);
    }

    public function delete(User $user, AccountingAccount $account): bool
    {
        if (! $user->canModule('set-coa') && ! $account->is_cash_account) {
            return false;
        }
        return $user->canManageBranch($account->branch_id);
    }
}
