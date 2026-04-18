<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class LoyaltyPolicy
{
    public function before(User $actor, string $ability): bool|null
    {
        return $actor->hasRole('Superadmin') ? true : null;
    }

    public function view(User $actor, Customer $customer): bool
    {
        if (! $actor->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return false;
        }

        if (! $actor->branch_id) {
            return false;
        }

        return (string) $actor->branch_id === (string) $customer->branch_id;
    }

    public function manageManual(User $actor, Customer $customer): bool
    {
        if (! $actor->hasRole('Admin Cabang')) {
            return false;
        }

        if (! $actor->branch_id) {
            return false;
        }

        return (string) $actor->branch_id === (string) $customer->branch_id;
    }
}