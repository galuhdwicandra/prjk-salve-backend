<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingJournalLine extends Model
{
    protected $table = 'accounting_journal_lines';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'journal_entry_id',
        'account_id',
        'description',
        'debit',
        'credit',
        'line_order',
    ];

    protected $casts = [
        'debit'      => 'decimal:2',
        'credit'     => 'decimal:2',
        'line_order' => 'integer',
    ];

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(AccountingJournalEntry::class, 'journal_entry_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'account_id');
    }
}
