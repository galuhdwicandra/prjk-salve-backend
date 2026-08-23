<?php

namespace App\Policies;

use App\Models\AccountingAccountMapping;
use App\Models\User;

class AccountingAccountMappingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AccountingAccountMapping $mapping): bool
    {
        return $mapping->branch_id === null
            || $user->canAccessBranch((string) $mapping->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, AccountingAccountMapping $mapping): bool
    {
        return $user->canManageBranch($mapping->branch_id);
    }

    public function delete(User $user, AccountingAccountMapping $mapping): bool
    {
        return $user->canManageBranch($mapping->branch_id);
    }
}
