<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',      // ID uporabnika (če je prijavljen)
        'session_id',   // ID seje (če je gost)
        'total_price',  // Skupna cena košarice
        'total_items',  // Skupno število izdelkov
        'completed'     // Ali je košarica zaključena
    ];

    protected $casts = [
        'completed' => 'boolean',
        'total_price' => 'decimal:2',
        'total_items' => 'integer',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate and update the cart totals
     * To se kliče po vsakem dodajanju/spreminjanju izdelkov
     */
    public function calculateTotal(): void
    {
        // Izračun skupnega števila izdelkov
        $this->total_items = $this->items()->sum('quantity');
        
        // Izračun skupne cene
        $this->total_price = $this->items()->get()->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Shrani spremembe v bazo
        $this->save();
    }

    /**
     * Add a product to the cart
     * Ta metoda se kliče ko dodate izdelek v košarico
     */
    public function addProduct(Product $product, int $quantity = 1, array $options = []): CartItem
    {
        // Preveri če izdelek že obstaja v košarici
        $cartItem = $this->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Če izdelek že obstaja, povečaj količino
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Če izdelek ne obstaja, ustvari nov zapis v cart_items tabeli
            $cartItem = $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->getCurrentPrice(),
                'options' => !empty($options) ? json_encode($options) : null,
            ]);
        }

        // Posodobi skupne vrednosti košarice
        $this->calculateTotal();

        return $cartItem;
    }

    /**
     * Remove a product from the cart
     */
    public function removeProduct(int $productId): void
    {
        $this->items()->where('product_id', $productId)->delete();
        $this->calculateTotal();
    }

    /**
     * Update product quantity in the cart
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeProduct($productId);
            return;
        }

        $cartItem = $this->items()->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            $this->calculateTotal();
        }
    }

    /**
     * Clear all items from the cart
     */
    public function clear(): void
    {
        $this->items()->delete();
        $this->total_items = 0;
        $this->total_price = 0;
        $this->save();
    }
}
