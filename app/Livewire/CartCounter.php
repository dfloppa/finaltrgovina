<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartCounter extends Component
{
    public $cartCount = 0;
    
    protected $listeners = ['cartUpdated' => 'updateCartCount'];
    
    public function mount()
    {
        $this->updateCartCount();
    }
    
    public function updateCartCount($data = null)
    {
        if ($data && isset($data['cartCount'])) {
            $this->cartCount = $data['cartCount'];
            return;
        }
        
        if (Auth::check()) {
            // For logged in users, get their cart
            $cart = \App\Models\Cart::where('user_id', Auth::id())
                ->where('completed', false)
                ->latest()
                ->first();
                
            if ($cart) {
                $this->cartCount = $cart->total_items;
            } else {
                $this->cartCount = 0;
            }
        } else {
            // For guest users, use the CartController's getCart method
            $cart = app(\App\Http\Controllers\CartController::class)->getCart();
            $this->cartCount = $cart->total_items;
        }
    }
    
    public function render()
    {
        return view('livewire.cart-counter');
    }
} 