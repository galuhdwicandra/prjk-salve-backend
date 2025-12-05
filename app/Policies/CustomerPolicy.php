<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
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
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir']);
    }

    public function view(User $user, Customer $c): bool
    {
        if ($user->hasRole('Admin Cabang') || $user->hasRole('Kasir')) {
            return (string) $user->branch_id === (string) $c->branch_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir']);
    }

    public function update(User $user, Customer $c): bool
    {
        if ($user->hasRole('Admin Cabang') || $user->hasRole('Kasir')) {
            return (string) $user->branch_id === (string) $c->branch_id;
        }
        return false;
    }

    public function viewLoyalty(User $user, Customer $customer): bool
    {
        if ($user->hasRole('Superadmin')) return true;
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string)$user->branch_id === (string)$customer->branch_id;
        }
        return false;
    }

    public function delete(User $user, Customer $c): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $c->branch_id;
        }
        // Kasir tidak bisa delete
        return false;
    }
}
