<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'deliveries';

    protected $fillable = [
        'order_id',
        'type',
        'zone_id',
        'fee',
        'assigned_to',
        'auto_assigned',
        'status',
        'handover_photo',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'auto_assigned' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function events()
    {
        return $this->hasMany(DeliveryEvent::class, 'delivery_id', 'id');
    }
}
