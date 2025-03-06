<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_price',
        'payment_method',
        'payment_status',
        'payment_id',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zipcode',
        'billing_country',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'shipping_country',
        'notes',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-';
        $timestamp = now()->format('YmdHis');
        $random = rand(1000, 9999);
        
        return $prefix . $timestamp . '-' . $random;
    }

    /**
     * Check if the order is pending.
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the order is processing.
     */
    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    /**
     * Check if the order is completed.
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the order is shipped.
     */
    public function isShipped()
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    /**
     * Check if the order is delivered.
     */
    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Check if the order is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if the order is refunded.
     */
    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    /**
     * Check if the payment is pending.
     */
    public function isPaymentPending()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PENDING;
    }

    /**
     * Check if the payment is paid.
     */
    public function isPaymentPaid()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    /**
     * Check if the payment failed.
     */
    public function isPaymentFailed()
    {
        return $this->payment_status === self::PAYMENT_STATUS_FAILED;
    }

    /**
     * Check if the payment is refunded.
     */
    public function isPaymentRefunded()
    {
        return $this->payment_status === self::PAYMENT_STATUS_REFUNDED;
    }

    /**
     * Mark the order as paid.
     */
    public function markAsPaid($paymentId = null)
    {
        $this->payment_status = self::PAYMENT_STATUS_PAID;
        
        if ($paymentId) {
            $this->payment_id = $paymentId;
        }
        
        $this->status = self::STATUS_PROCESSING;
        $this->save();
        
        return $this;
    }

    /**
     * Mark the order as shipped.
     */
    public function markAsShipped($trackingNumber = null)
    {
        $this->status = self::STATUS_SHIPPED;
        
        if ($trackingNumber) {
            $this->tracking_number = $trackingNumber;
        }
        
        $this->shipped_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Mark the order as delivered.
     */
    public function markAsDelivered()
    {
        $this->status = self::STATUS_DELIVERED;
        $this->delivered_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Mark the order as cancelled.
     */
    public function markAsCancelled()
    {
        $this->status = self::STATUS_CANCELLED;
        $this->save();
        
        return $this;
    }

    /**
     * Mark the order as refunded.
     */
    public function markAsRefunded()
    {
        $this->status = self::STATUS_REFUNDED;
        $this->payment_status = self::PAYMENT_STATUS_REFUNDED;
        $this->save();
        
        return $this;
    }
}
