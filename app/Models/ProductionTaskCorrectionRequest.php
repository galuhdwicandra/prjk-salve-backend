<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTaskCorrectionRequest extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'production_task_correction_requests';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'production_task_id',
        'order_id',
        'branch_id',
        'requested_by',
        'reviewed_by',
        'type',
        'from_status',
        'to_status',
        'reason',
        'status',
        'review_note',
        'requested_date',
        'reviewed_date',
    ];

    protected $casts = [
        'requested_date' => 'date:Y-m-d',
        'reviewed_date' => 'date:Y-m-d',
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

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }
}
