<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionCategory extends Model
{
    use HasUuids;

    protected $table = 'transaction_categories';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'cash_in',
        'cash_out',
        'cashflow',
        'in_account_id',
        'out_account_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'cash_in'    => 'boolean',
        'cash_out'   => 'boolean',
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function inAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'in_account_id');
    }

    public function outAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'out_account_id');
    }
}
