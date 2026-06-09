<?php

namespace App\Policies;

use App\Models\AccountingAccountMapping;
use App\Models\User;

class AccountingAccountMappingPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasAnyRole(['Superadmin', 'Akuntansi']) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, AccountingAccountMapping $mapping): bool
    {
        if ($mapping->branch_id === null) {
            return $user->hasRole('Admin Cabang');
        }

        return $user->hasRole('Admin Cabang')
            && (string) $mapping->branch_id === (string) $user->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function update(User $user, AccountingAccountMapping $mapping): bool
    {
        if ($mapping->branch_id === null) {
            return false;
        }

        return $user->hasRole('Admin Cabang')
            && (string) $mapping->branch_id === (string) $user->branch_id;
    }

    public function delete(User $user, AccountingAccountMapping $mapping): bool
    {
        if ($mapping->branch_id === null) {
            return false;
        }

        return $user->hasRole('Admin Cabang')
            && (string) $mapping->branch_id === (string) $user->branch_id;
    }
}
