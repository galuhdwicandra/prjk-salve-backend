<?php

namespace App\Services\Accounting;

use App\Models\AccountingJournalEntry;
use Illuminate\Support\Carbon;

class AccountingJournalNumberService
{
    public function next(Carbon|string $journalDate): string
    {
        $date = $journalDate instanceof Carbon
            ? $journalDate
            : Carbon::parse($journalDate);

        $prefix = 'JRN-' . $date->format('Ymd');

        $last = AccountingJournalEntry::query()
            ->where('journal_no', 'like', $prefix . '-%')
            ->lockForUpdate()
            ->orderByDesc('journal_no')
            ->value('journal_no');

        $nextNumber = 1;

        if ($last) {
            $lastSeq = (int) substr((string) $last, -4);
            $nextNumber = $lastSeq + 1;
        }

        return $prefix . '-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
