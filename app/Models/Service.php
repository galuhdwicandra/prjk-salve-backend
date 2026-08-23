<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table     = 'services';

    protected $fillable = [
        'category_id',
        'parent_id',
        'name',
        'unit',
        'price_default',
        'is_active',
    ];

    protected $casts = [
        'price_default' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany(ServicePrice::class, 'service_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
