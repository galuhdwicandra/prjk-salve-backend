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
        return $actor->hasAnyRole(['Admin Cabang', 'Kasir']);
    }
}
