<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table     = 'customers';

    protected $fillable = [
        'branch_id',
        'name',
        'whatsapp',
        'address',
        'notes',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'tags'      => 'array',
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
