<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return true;
    }

    public function view(User $actor, User $target): bool
    {
        return $actor->id === $target->id
            || $actor->canAccessBranch((string) $target->branch_id);
    }

    public function create(User $actor): bool
    {
        return $actor->isManager();
    }

    public function update(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return true;
        }

        return $this->manageTarget($actor, $target);
    }

    public function delete(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return false;
        }

        return $this->manageTarget($actor, $target);
    }

    public function resetPassword(User $actor, User $target): bool
    {
        return $this->update($actor, $target);
    }

    public function setActive(User $actor, User $target): bool
    {
        return $this->update($actor, $target);
    }

    public function setRoles(User $actor, User $target): bool
    {
        return $this->manageTarget($actor, $target);
    }

    private function manageTarget(User $actor, User $target): bool
    {
        if ($target->all_branches && ! $actor->all_branches) {
            return false;
        }

        return $actor->canManageBranch((string) $target->branch_id);
    }
}
