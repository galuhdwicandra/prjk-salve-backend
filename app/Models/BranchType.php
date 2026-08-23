<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BranchType extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'branch_types';

    protected $fillable = [
        'code',
        'name',
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class, 'type', 'code');
    }
}
