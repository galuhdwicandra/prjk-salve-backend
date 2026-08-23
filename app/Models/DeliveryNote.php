<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table     = 'delivery_notes';

    protected $fillable = [
        'number',
        'kind',
        'note_date',
        'branch_id',
        'to_type',
        'to_branch_id',
        'to_contact_id',
        'from_contact_id',
        'status',
        'proofs',
        'created_by',
        'picked_by',
        'picked_at',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'note_date'    => 'date:Y-m-d',
        'proofs'       => 'array',
        'picked_at'    => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'delivery_note_orders', 'delivery_note_id', 'order_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id', 'id');
    }

    public function toContact()
    {
        return $this->belongsTo(Contact::class, 'to_contact_id', 'id');
    }

    public function fromContact()
    {
        return $this->belongsTo(Contact::class, 'from_contact_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
