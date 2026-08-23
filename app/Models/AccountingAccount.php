<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingAccount extends Model
{
    protected $table = 'accounting_accounts';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'branch_id',
        'parent_id',
        'code',
        'name',
        'description',
        'type',
        'normal_balance',
        'is_cash_account',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_cash_account' => 'boolean',
        'is_active'       => 'boolean',
        'sort_order'      => 'integer',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AccountingAccount::class, 'parent_id');
    }

    public function debitMappings(): HasMany
    {
        return $this->hasMany(AccountingAccountMapping::class, 'debit_account_id');
    }

    public function creditMappings(): HasMany
    {
        return $this->hasMany(AccountingAccountMapping::class, 'credit_account_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(AccountingJournalLine::class, 'account_id');
    }

    public function paymentMethodAccounts(): HasMany
    {
        return $this->hasMany(PaymentMethodAccount::class, 'account_id');
    }
}
