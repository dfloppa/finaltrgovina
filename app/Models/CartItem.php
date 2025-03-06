<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'options'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'options' => 'json',
    ];

    /**
     * Get the cart that owns the item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product associated with the cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for this item
     */
    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }
}
