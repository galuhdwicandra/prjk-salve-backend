<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table     = 'vouchers';

    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'type',
        'value',
        'start_at',
        'end_at',
        'min_total',
        'usage_limit',
        'active',
        'is_archived',
        'stack_voucher',
        'stack_discount',
        'percent_after_discount',
    ];

    protected $casts = [
        'value'                  => 'decimal:2',
        'min_total'              => 'decimal:2',
        'active'                 => 'boolean',
        'is_archived'            => 'boolean',
        'stack_voucher'          => 'boolean',
        'stack_discount'         => 'boolean',
        'percent_after_discount' => 'boolean',
        'start_at'               => 'datetime',
        'end_at'                 => 'datetime',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_vouchers', 'voucher_id', 'order_id')
            ->withPivot(['id', 'applied_amount', 'applied_by', 'applied_at'])
            ->withTimestamps();
    }
}
