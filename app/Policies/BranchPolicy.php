<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch;

class BranchPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Superadmin', 'Admin Cabang']);
    }

    public function view(User $user, Branch $branch): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $branch->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    public function update(User $user, Branch $branch): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $branch->id;
        }
        return false;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRole('superadmin');
    }
}
