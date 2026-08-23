<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerLabel extends Model
{
    use HasUuids;

    protected $table = 'customer_labels';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
