<?php
namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    private const TERMINAL_STATUSES = ['DELIVERING', 'PICKED_UP', 'CANCELED'];

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->canAccessBranch((string) $order->branch_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->canManageBranch((string) $order->branch_id)
        && ! $this->isTerminal($order);
    }

    public function uploadPhotos(User $user, Order $order): bool
    {
        return $user->canAccessBranch((string) $order->branch_id)
        && ! $this->isTerminal($order);
    }

    public function applyVoucher(User $user, Order $order): bool
    {
        return $user->canAccessBranch((string) $order->branch_id)
        && ! $this->isTerminal($order);
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->canManageBranch((string) $order->branch_id)
        && ! $this->isTerminal($order)
        && ! $order->payments()->exists();
    }

    public function transitionStatus(User $user, Order $order): bool
    {
        return $user->canAccessBranch((string) $order->branch_id);
    }

    public function settlePayment(User $user, Order $order): bool
    {
        return $user->canAccessBranch((string) $order->branch_id);
    }

    public function void(User $user, Order $order): bool
    {
        return $user->canManageBranch((string) $order->branch_id)
        && $order->status !== 'CANCELED';
    }

    private function isTerminal(Order $order): bool
    {
        return in_array($order->status, self::TERMINAL_STATUSES, true);
    }
}
