<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashTransaction extends Model
{
    use HasUuids;

    protected $table = 'cash_transactions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'branch_id',
        'kind',
        'no',
        'trx_date',
        'cash_account_id',
        'to_account_id',
        'contact_id',
        'amount',
        'fee_amount',
        'fee_bearer',
        'description',
        'attachment_path',
        'created_by',
    ];

    protected $casts = [
        'trx_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'cash_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'to_account_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(CashTransactionLine::class)->orderBy('line_order');
    }
}
