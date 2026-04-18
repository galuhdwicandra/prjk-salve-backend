<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyLog extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'order_id',
        'customer_id',
        'branch_id',
        'action',
        'note',
        'before',
        'after',
    ];
}