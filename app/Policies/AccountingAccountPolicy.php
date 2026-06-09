<?php
namespace App\Policies;

use App\Models\AccountingAccount;
use App\Models\User;

class AccountingAccountPolicy
{
    public function before(User $user, string $ability): bool | null
    {
        return $user->hasAnyRole(['Superadmin', 'Akuntansi']) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, AccountingAccount $account): bool
    {
        if ($account->branch_id === null) {
            return $user->hasRole('Admin Cabang');
        }

        return $user->hasRole('Admin Cabang')
        && (string) $account->branch_id === (string) $user->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function update(User $user, AccountingAccount $account): bool
    {
        if ($account->branch_id === null) {
            return false;
        }

        return $user->hasRole('Admin Cabang')
        && (string) $account->branch_id === (string) $user->branch_id;
    }

    public function delete(User $user, AccountingAccount $account): bool
    {
        if ($account->branch_id === null) {
            return false;
        }

        return $user->hasRole('Admin Cabang')
        && (string) $account->branch_id === (string) $user->branch_id;
    }
}
