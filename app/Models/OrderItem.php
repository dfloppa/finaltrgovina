<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'quantity',
        'price',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'options' => 'array',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price of the item.
     */
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Set the options attribute.
     */
    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get the options attribute.
     */
    public function getOptionsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
}
