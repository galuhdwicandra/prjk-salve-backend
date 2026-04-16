<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashMutation extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'cash_mutations';

    protected $fillable = [
        'cash_session_id',
        'branch_id',
        'type',
        'direction',
        'amount',
        'source_type',
        'source_id',
        'reference_no',
        'note',
        'created_by',
        'effective_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}