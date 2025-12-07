<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WashNoteItem extends Model
{
    protected $table = 'wash_note_items';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'wash_note_id',
        'order_id',
        'qty',
        'process_status',
        'started_at',
        'finished_at',
        'note',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    public function washNote(): BelongsTo
    {
        return $this->belongsTo(WashNote::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
