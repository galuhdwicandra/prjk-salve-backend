<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WashNote extends Model
{
    protected $table = 'wash_notes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'branch_id',
        'note_date',
        'orders_count',
        'total_qty',
    ];

    protected $casts = [
        'note_date'  => 'date:Y-m-d',
        'orders_count' => 'integer',
        'total_qty' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(WashNoteItem::class);
    }
}
