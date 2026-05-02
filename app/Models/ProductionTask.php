<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTask extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'branch_id',
        'assigned_to',
        'current_status',
        'qty',
        'started_date',
        'finished_date',
        'note',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'started_date' => 'date:Y-m-d',
        'finished_date' => 'date:Y-m-d',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function logs()
    {
        return $this->hasMany(ProductionTaskLog::class, 'production_task_id', 'id');
    }
}
