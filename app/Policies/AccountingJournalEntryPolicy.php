<?php

namespace App\Policies;

use App\Models\AccountingJournalEntry;
use App\Models\User;

class AccountingJournalEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AccountingJournalEntry $journal): bool
    {
        return $user->canAccessBranch((string) $journal->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, AccountingJournalEntry $journal): bool
    {
        return (string) $journal->status === 'DRAFT'
            && $user->canManageBranch((string) $journal->branch_id);
    }

    public function post(User $user, AccountingJournalEntry $journal): bool
    {
        return $this->update($user, $journal);
    }

    public function void(User $user, AccountingJournalEntry $journal): bool
    {
        return (string) $journal->status !== 'VOID'
            && $user->canManageBranch((string) $journal->branch_id);
    }
}
