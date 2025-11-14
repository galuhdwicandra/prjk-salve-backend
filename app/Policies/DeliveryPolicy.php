<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;

class DeliveryPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir']);
    }

    public function assignCourier(User $user, Delivery $delivery): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $delivery->order?->branch_id === (string) $user->branch_id;
        }
        return false;
    }

    public function updateStatus(User $user, Delivery $delivery): bool
    {
        if ($user->hasRole('Kurir')) {
            return (int) $delivery->assigned_to === (int) $user->id;
        }
        if ($user->hasRole('Admin Cabang')) {
            return (string) $delivery->order?->branch_id === (string) $user->branch_id;
        }
        return false;
    }

    public function view(User $user, Delivery $delivery): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'])) {
            return (string) $delivery->order?->branch_id === (string) $user->branch_id
                || (int) $delivery->assigned_to === (int) $user->id;
        }
        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir']);
    }
}
