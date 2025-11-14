<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir']);
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir']);
    }

    public function update(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function delete(User $user, Order $order): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function transitionStatus(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function settlePayment(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }
}
