<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Tipe numerik-keuangan yang disimpan sebagai string (sesuai cast decimal Laravel).
 *
 * @property numeric-string $subtotal
 * @property numeric-string $discount
 * @property string|null $discount_type
 * @property numeric-string $discount_value
 * @property numeric-string $grand_total
 * @property numeric-string $paid_amount
 * @property numeric-string $due_amount
 */
class Order extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table     = 'orders';

    protected $fillable = [
        'branch_id',
        'customer_id',
        'customer_name',
        'number',
        'invoice_no',
        'status',
        'payment_status',
        'processing_destination',
        'destination_branch_id',
        'destination_contact_id',
        'subtotal',
        'discount',
        'discount_type',
        'discount_value',
        'dp_amount',
        'grand_total',
        'paid_amount',
        'paid_at',
        'due_amount',
        'notes',
        'created_by',
        'received_at',
        'ready_at',
        'tracker_token',
        'tracker_token_expires_at',
    ];

    // Tetap pakai decimal:2 (Laravel mengembalikan string)
    protected $casts = [
        'subtotal'                 => 'decimal:2',
        'discount'                 => 'decimal:2',
        'discount_value'           => 'decimal:2',
        'grand_total'              => 'decimal:2',
        'dp_amount'                => 'decimal:2',
        'paid_amount'              => 'decimal:2',
        'due_amount'               => 'decimal:2',
        'paid_at'                  => 'datetime',
        'created_by'               => 'integer',
        'received_at'              => 'date:Y-m-d',
        'ready_at'                 => 'date:Y-m-d',
        'tracker_token_expires_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id', 'id');
    }
    public function destinationContact()
    {
        return $this->belongsTo(Contact::class, 'destination_contact_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function deliveryNotes()
    {
        return $this->belongsToMany(DeliveryNote::class, 'delivery_note_orders', 'order_id', 'delivery_note_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function productionTask()
    {
        return $this->hasOne(\App\Models\ProductionTask::class, 'order_id', 'id');
    }
    public function photos()
    {
        return $this->hasMany(OrderPhoto::class, 'order_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'order_id', 'id');
    }
    public function vouchers()
    {
        return $this->belongsToMany(\App\Models\Voucher::class, 'order_vouchers', 'order_id', 'voucher_id')
            ->withPivot(['id', 'applied_amount', 'applied_by', 'applied_at'])
            ->withTimestamps();
    }
    public function receivable()
    {
        return $this->hasOne(\App\Models\Receivable::class, 'order_id', 'id');
    }

    /**
     * Mutator generik untuk kolom uang — menerima float|int|string.
     * Agar IDE happy, panggil via $this->setMoney('subtotal', $v) dsb.
     * @param float|int|string|null $value
     */
    public function setMoney(string $attr, float | int | string | null $value): void
    {
        $v                       = is_numeric($value) ? (float) $value : 0.0;
        $this->attributes[$attr] = number_format($v, 2, '.', '');
    }
}
