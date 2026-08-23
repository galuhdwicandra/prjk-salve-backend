<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LoyaltySetting extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'loyalty_settings';

    protected $fillable = ['branch_id', 'target', 'stamp_per', 'rewards'];

    protected $casts = [
        'target' => 'integer',
        'rewards' => 'array',
    ];
}
