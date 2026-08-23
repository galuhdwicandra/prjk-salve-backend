<?php

namespace App\Policies;

use App\Models\Receivable;
use App\Models\User;

class ReceivablePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Receivable $receivable): bool
    {
        return $user->canAccessBranch((string) $receivable->order?->branch_id);
    }

    public function settle(User $user, Receivable $receivable): bool
    {
        return $this->view($user, $receivable);
    }
}
