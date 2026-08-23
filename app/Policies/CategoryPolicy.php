<?php

namespace App\Policies;

use App\Models\ServiceCategory;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ServiceCategory $category): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, ServiceCategory $category): bool
    {
        return $user->isManager();
    }

    public function delete(User $user, ServiceCategory $category): bool
    {
        return $user->isManager();
    }
}
