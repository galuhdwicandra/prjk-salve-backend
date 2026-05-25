<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingJournalEntry extends Model
{
    protected $table = 'accounting_journal_entries';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'branch_id',
        'mapping_id',
        'journal_no',
        'journal_date',
        'source_type',
        'source_id',
        'source_no',
        'status',
        'description',
        'total_debit',
        'total_credit',
        'created_by',
        'posted_by',
        'posted_at',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'journal_date' => 'date',
        'total_debit'  => 'decimal:2',
        'total_credit' => 'decimal:2',
        'posted_at'    => 'datetime',
        'voided_at'    => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function mapping(): BelongsTo
    {
        return $this->belongsTo(AccountingAccountMapping::class, 'mapping_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(AccountingJournalLine::class, 'journal_entry_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function voider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}
