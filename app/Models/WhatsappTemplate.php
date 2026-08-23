<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasUuids;

    public const KEYS = [
        'struk',
        'ready',
        'reminder',
        'greeting',
        'receipt_pending',
        'receipt_paid',
        'order_status',
    ];

    protected $table     = 'whatsapp_templates';
    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'id',
        'branch_id',
        'key',
        'name',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
