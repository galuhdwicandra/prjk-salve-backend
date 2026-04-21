<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhatsappTemplate;

class WhatsappTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir']);
    }

    public function view(User $user, WhatsappTemplate $template): bool
    {
        if ($user->hasRole('Superadmin')) {
            return true;
        }

        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string) $template->branch_id === (string) $user->branch_id
                || $template->branch_id === null;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Superadmin') || $user->hasRole('Admin Cabang');
    }

    public function update(User $user, WhatsappTemplate $template): bool
    {
        if ($user->hasRole('Superadmin')) {
            return true;
        }

        if ($user->hasRole('Admin Cabang')) {
            return (string) $template->branch_id === (string) $user->branch_id;
        }

        return false;
    }

    public function delete(User $user, WhatsappTemplate $template): bool
    {
        return $this->update($user, $template);
    }
}
