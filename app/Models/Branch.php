<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'branches';

    protected $fillable = [
        'code',
        'name',
        'address',
        'invoice_prefix',
        'reset_policy',
    ];

    public function invoiceCounters()
    {
        return $this->hasMany(InvoiceCounter::class, 'branch_id', 'id');
    }

    public function scopeLite($q)
    {
        return $q->select(['id', 'name', 'address']);
    }
}
