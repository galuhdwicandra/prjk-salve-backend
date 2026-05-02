<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTaskLog extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'production_task_id',
        'order_id',
        'branch_id',
        'user_id',
        'from_status',
        'to_status',
        'qty',
        'process_date',
        'started_date',
        'finished_date',
        'note',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'process_date' => 'date:Y-m-d',
        'started_date' => 'date:Y-m-d',
        'finished_date' => 'date:Y-m-d',
    ];

    public function task()
    {
        return $this->belongsTo(ProductionTask::class, 'production_task_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
