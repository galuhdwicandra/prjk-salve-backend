<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashSession extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'cash_sessions';

    protected $fillable = [
        'branch_id',
        'business_date',
        'status',
        'opened_by',
        'opened_at',
        'opening_cash',
        'closed_by',
        'closed_at',
        'closing_cash_system',
        'closing_cash_counted',
        'difference_amount',
        'notes',
    ];

    protected $casts = [
        'business_date' => 'date',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'decimal:2',
        'closing_cash_system' => 'decimal:2',
        'closing_cash_counted' => 'decimal:2',
        'difference_amount' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function opener()
    {
        return $this->belongsTo(User::class, 'opened_by', 'id');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by', 'id');
    }

    public function mutations()
    {
        return $this->hasMany(CashMutation::class, 'cash_session_id', 'id');
    }
}