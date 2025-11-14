<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;

class ServicePolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang','Kasir']);
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang','Kasir']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }
}
