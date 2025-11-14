<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceCategory;

class CategoryPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function view(User $user, ServiceCategory $category): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function update(User $user, ServiceCategory $category): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function delete(User $user, ServiceCategory $category): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }
}
