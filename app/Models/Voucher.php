<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'vouchers';

    protected $fillable = [
        'branch_id',
        'code',
        'type',
        'value',
        'start_at',
        'end_at',
        'min_total',
        'usage_limit',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_total' => 'decimal:2',
        'active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_vouchers', 'voucher_id', 'order_id')
            ->withPivot(['id', 'applied_amount', 'applied_by', 'applied_at'])
            ->withTimestamps();
    }
}
