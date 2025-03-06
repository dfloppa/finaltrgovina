<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Cart;

class ProductAddToCart extends Component
{
    public $product;
    public $quantity = 1;
    public $message = '';
    public $messageType = '';
    
    public function mount(Product $product)
    {
        $this->product = $product;
    }
    
    public function addToCart()
    {
        // Validate quantity
        if ($this->quantity < 1) {
            $this->message = 'Quantity must be at least 1.';
            $this->messageType = 'error';
            return;
        }
        
        // Check if product is in stock
        if (!$this->product->inStock() || $this->product->stock < $this->quantity) {
            $this->message = 'Sorry, this product is out of stock or the requested quantity is not available.';
            $this->messageType = 'error';
            return;
        }
        
        // Get the user's cart
        if (Auth::check()) {
            // For logged in users, get their cart
            $cart = Cart::where('user_id', Auth::id())
                ->where('completed', false)
                ->latest()
                ->first();
                
            // If no active cart exists, create a new one
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'total_price' => 0,
                    'total_items' => 0,
                    'completed' => false
                ]);
            }
        } else {
            // For guest users, use the CartController's getCart method
            $cart = app(\App\Http\Controllers\CartController::class)->getCart();
        }
        
        // Add product to cart
        $cart->addProduct($this->product, $this->quantity);
        
        $this->message = 'Product added to cart successfully!';
        $this->messageType = 'success';
        
        // Emit event to update cart count in the header
        $this->dispatch('cartUpdated', ['cartCount' => $cart->total_items]);
    }
    
    public function render()
    {
        return view('livewire.product-add-to-cart');
    }
} 