<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPhoto extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_photos';

    protected $fillable = ['order_id', 'kind', 'path'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
