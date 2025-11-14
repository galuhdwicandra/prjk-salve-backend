<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receivable;

class ReceivablePolicy
{
    public function before(User $user, $ability)
    {
        // Superadmin full access
        if ($user->hasRole('Superadmin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        // Kasir & Admin Cabang boleh melihat
        return $user->hasRole(['Admin Cabang', 'Kasir', 'Superadmin']);
    }

    public function view(User $user, Receivable $receivable): bool
    {
        // Batasi per cabang via relasi Order
        return $user->branch_id && $receivable->order && $receivable->order->branch_id === $user->branch_id;
    }

    public function settle(User $user, Receivable $receivable): bool
    {
        // Pelunasan oleh Kasir/Admin Cabang di cabang yang sama
        return $this->view($user, $receivable) && $user->hasRole(['Admin Cabang', 'Kasir']);
    }
}
