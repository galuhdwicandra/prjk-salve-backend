<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class LoyaltyPolicy
{
    public function view(User $actor, Customer $customer): bool
    {
        return $actor->canAccessBranch((string) $customer->branch_id);
    }

    public function manageManual(User $actor, Customer $customer): bool
    {
        return $actor->canManageBranch((string) $customer->branch_id);
    }
}
