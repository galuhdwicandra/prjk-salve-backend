<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isManager();
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->canAccessBranch((string) $branch->id);
    }

    public function create(User $user): bool
    {
        return $user->isManager() && $user->all_branches;
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->canManageBranch((string) $branch->id);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->isManager() && $user->all_branches;
    }
}
