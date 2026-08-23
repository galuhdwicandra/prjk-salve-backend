<?php
namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;

class DeliveryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Delivery $delivery): bool
    {
        return $this->isAssignee($user, $delivery)
        || $this->canAccessOrderBranches($user, $delivery);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function assignCourier(User $user, Delivery $delivery): bool
    {
        return $this->canManageOrderBranches($user, $delivery);
    }

    public function updateStatus(User $user, Delivery $delivery): bool
    {
        return $this->isAssignee($user, $delivery)
        || $this->canAccessOrderBranches($user, $delivery);
    }

    private function isAssignee(User $user, Delivery $delivery): bool
    {
        return $delivery->assigned_to !== null && (int) $delivery->assigned_to === (int) $user->id;
    }

    private function canAccessOrderBranches(User $user, Delivery $delivery): bool
    {
        return $user->canAccessBranch((string) $delivery->order?->branch_id)
        || $user->canAccessBranch((string) $delivery->order?->destination_branch_id);
    }

    private function canManageOrderBranches(User $user, Delivery $delivery): bool
    {
        return $user->canManageBranch((string) $delivery->order?->branch_id)
        || $user->canManageBranch((string) $delivery->order?->destination_branch_id);
    }
}
