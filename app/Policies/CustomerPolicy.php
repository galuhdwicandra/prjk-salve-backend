<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->canAccessBranch((string) $customer->branch_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->canAccessBranch((string) $customer->branch_id);
    }

    public function viewLoyalty(User $user, Customer $customer): bool
    {
        return $user->canAccessBranch((string) $customer->branch_id);
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->canManageBranch((string) $customer->branch_id);
    }
}
