<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function before(?User $user, string $ability)
    {
        if ($user && $user->hasRole('Superadmin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        // Sesuai SOP: modul Expenses hanya untuk Admin Cabang (dan Superadmin via before)
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, Expense $expense): bool
    {
        if ($user->hasRole('Admin Cabang') && $user->branch_id !== null) {
            return $expense->branch_id === $user->branch_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang') && $user->branch_id !== null;
    }

    public function update(User $user, Expense $expense): bool
    {
        return $this->view($user, $expense);
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $this->view($user, $expense);
    }
}
