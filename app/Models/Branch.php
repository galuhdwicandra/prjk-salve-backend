<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table     = 'branches';

    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'phone',
        'hours',
        'invoice_prefix',
        'reset_policy',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function invoiceCounters()
    {
        return $this->hasMany(InvoiceCounter::class, 'branch_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'branch_id', 'id');
    }

    public function scopeLite($q)
    {
        return $q->select(['id', 'name', 'address']);
    }
}
