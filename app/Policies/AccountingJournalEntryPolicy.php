<?php

namespace App\Policies;

use App\Models\AccountingJournalEntry;
use App\Models\User;

class AccountingJournalEntryPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasAnyRole(['Superadmin', 'Akuntansi']) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, AccountingJournalEntry $journal): bool
    {
        return $user->hasRole('Admin Cabang')
            && (string) $journal->branch_id === (string) $user->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function update(User $user, AccountingJournalEntry $journal): bool
    {
        if ((string) $journal->status !== 'DRAFT') {
            return false;
        }

        return $user->hasRole('Admin Cabang')
            && (string) $journal->branch_id === (string) $user->branch_id;
    }

    public function post(User $user, AccountingJournalEntry $journal): bool
    {
        if ((string) $journal->status !== 'DRAFT') {
            return false;
        }

        return $user->hasRole('Admin Cabang')
            && (string) $journal->branch_id === (string) $user->branch_id;
    }

    public function void(User $user, AccountingJournalEntry $journal): bool
    {
        if ((string) $journal->status === 'VOID') {
            return false;
        }

        return $user->hasRole('Admin Cabang')
            && (string) $journal->branch_id === (string) $user->branch_id;
    }
}
