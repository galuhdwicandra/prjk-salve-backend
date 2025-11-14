<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Selaraskan kapitalisasi role dengan data/FE
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, Voucher $voucher): bool
    {
        if ($user->hasRole('Admin Cabang'))
            return $voucher->branch_id === $user->branch_id;
        if ($user->hasRole('Kasir'))
            return $voucher->active === true;
        return $user->branch_id && $user->branch_id === $voucher->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function update(User $user, Voucher $voucher): bool
    {
        if ($voucher->branch_id === null)
            return false; // global hanya superadmin (ditangani di before)
        return $user->hasRole('Admin Cabang') && $voucher->branch_id === $user->branch_id;
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        if ($voucher->branch_id === null)
            return false;
        return $user->hasRole('Admin Cabang') && $voucher->branch_id === $user->branch_id;
    }
}

