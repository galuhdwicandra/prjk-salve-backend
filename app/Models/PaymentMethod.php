<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasUuids;

    protected $table = 'payment_methods';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(PaymentMethodAccount::class, 'payment_method_id');
    }

    public function setCodeAttribute(?string $v): void
    {
        $this->attributes['code'] = $v !== null ? strtoupper(trim($v)) : null;
    }
}
