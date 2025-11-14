<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property numeric-string $qty
 * @property numeric-string $price
 * @property numeric-string $total
 */
class OrderItem extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_items';

    protected $fillable = ['order_id', 'service_id', 'qty', 'price', 'total', 'note'];

    protected $casts = [
        'qty' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function setMoney(string $attr, float|int|string|null $value): void
    {
        $v = is_numeric($value) ? (float) $value : 0.0;
        $this->attributes[$attr] = number_format($v, 2, '.', '');
    }
}
