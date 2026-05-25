<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingAccountMapping extends Model
{
    protected $table = 'accounting_account_mappings';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'branch_id',
        'event_key',
        'payment_method',
        'expense_category',
        'debit_account_id',
        'credit_account_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'credit_account_id');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(AccountingJournalEntry::class, 'mapping_id');
    }
}
