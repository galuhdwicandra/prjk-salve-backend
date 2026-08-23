<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WashNote;

class WashNotePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, WashNote $note): bool
    {
        return $this->isOwner($user, $note)
            || $user->canAccessBranch((string) $note->branch_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, WashNote $note): bool
    {
        return $this->isOwner($user, $note)
            || $user->canManageBranch((string) $note->branch_id);
    }

    public function delete(User $user, WashNote $note): bool
    {
        return $user->canManageBranch((string) $note->branch_id);
    }

    private function isOwner(User $user, WashNote $note): bool
    {
        return (int) $note->user_id === (int) $user->id;
    }
}
