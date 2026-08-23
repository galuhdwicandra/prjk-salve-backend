<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Voucher $voucher): bool
    {
        return (bool) $voucher->active || $user->canAccessBranch((string) $voucher->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, Voucher $voucher): bool
    {
        return $user->canManageBranch($voucher->branch_id);
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        return $user->canManageBranch($voucher->branch_id);
    }
}
