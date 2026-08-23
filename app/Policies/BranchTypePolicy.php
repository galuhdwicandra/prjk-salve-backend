<?php

namespace App\Policies;

use App\Models\BranchType;
use App\Models\User;

class BranchTypePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BranchType $branchType): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, BranchType $branchType): bool
    {
        return $user->isManager();
    }

    public function delete(User $user, BranchType $branchType): bool
    {
        return $user->isManager();
    }
}
