<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransactionLine extends Model
{
    use HasUuids;

    protected $table = 'cash_transaction_lines';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cash_transaction_id',
        'transaction_category_id',
        'description',
        'amount',
        'line_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'line_order' => 'integer',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CashTransaction::class, 'cash_transaction_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }
}
