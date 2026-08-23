<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhatsappTemplate;

class WhatsappTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, WhatsappTemplate $template): bool
    {
        return $template->branch_id === null
            || $user->canAccessBranch((string) $template->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, WhatsappTemplate $template): bool
    {
        return $user->canManageBranch($template->branch_id);
    }

    public function delete(User $user, WhatsappTemplate $template): bool
    {
        return $this->update($user, $template);
    }
}
