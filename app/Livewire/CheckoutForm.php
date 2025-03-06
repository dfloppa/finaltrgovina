<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutForm extends Component
{
    public $cart;
    public $cartItems = [];
    
    // Billing information
    public $billing_name;
    public $billing_email;
    public $billing_phone;
    public $billing_address;
    public $billing_city;
    public $billing_state;
    public $billing_zipcode;
    public $billing_country;
    
    // Shipping information
    public $same_as_billing = true;
    public $shipping_name;
    public $shipping_email;
    public $shipping_phone;
    public $shipping_address;
    public $shipping_city;
    public $shipping_state;
    public $shipping_zipcode;
    public $shipping_country;
    
    // Order details
    public $notes;
    public $payment_method = 'stripe';
    
    protected $rules = [
        'billing_name' => 'required|string|max:255',
        'billing_email' => 'required|email|max:255',
        'billing_phone' => 'required|string|max:20',
        'billing_address' => 'required|string|max:255',
        'billing_city' => 'required|string|max:100',
        'billing_state' => 'required|string|max:100',
        'billing_zipcode' => 'required|string|max:20',
        'billing_country' => 'required|string|max:100',
        'payment_method' => 'required|in:stripe',
    ];
    
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->cart = $this->getCart();
        $this->cartItems = $this->cart->items()->with('product')->get();
        
        // Pre-fill with user information if available
        $user = Auth::user();
        $this->billing_name = $user->name;
        $this->billing_email = $user->email;
    }
    
    public function updatedSameAsBilling($value)
    {
        if ($value) {
            $this->resetShippingFields();
        }
    }
    
    private function resetShippingFields()
    {
        $this->shipping_name = null;
        $this->shipping_email = null;
        $this->shipping_phone = null;
        $this->shipping_address = null;
        $this->shipping_city = null;
        $this->shipping_state = null;
        $this->shipping_zipcode = null;
        $this->shipping_country = null;
    }
    
    public function placeOrder()
    {
        if ($this->same_as_billing) {
            $this->shipping_name = $this->billing_name;
            $this->shipping_email = $this->billing_email;
            $this->shipping_phone = $this->billing_phone;
            $this->shipping_address = $this->billing_address;
            $this->shipping_city = $this->billing_city;
            $this->shipping_state = $this->billing_state;
            $this->shipping_zipcode = $this->billing_zipcode;
            $this->shipping_country = $this->billing_country;
        } else {
            $this->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:100',
                'shipping_state' => 'required|string|max:100',
                'shipping_zipcode' => 'required|string|max:20',
                'shipping_country' => 'required|string|max:100',
            ]);
        }
        
        $this->validate();
        
        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'status' => 'pending',
            'total_price' => $this->cart->total_price,
            'payment_method' => $this->payment_method,
            'payment_status' => 'pending',
            'billing_name' => $this->billing_name,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'billing_address' => $this->billing_address,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_zipcode' => $this->billing_zipcode,
            'billing_country' => $this->billing_country,
            'shipping_name' => $this->shipping_name,
            'shipping_email' => $this->shipping_email,
            'shipping_phone' => $this->shipping_phone,
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_state' => $this->shipping_state,
            'shipping_zipcode' => $this->shipping_zipcode,
            'shipping_country' => $this->shipping_country,
            'notes' => $this->notes,
        ]);
        
        // Add order items
        foreach ($this->cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'options' => $item->options,
            ]);
        }
        
        // Mark the cart as completed
        $this->cart->completed = true;
        $this->cart->save();
        
        // Redirect to order confirmation page
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Your order has been placed successfully!');
    }
    
    private function getCart()
    {
        if (Auth::check()) {
            // For logged in users, get their cart
            $cart = \App\Models\Cart::where('user_id', Auth::id())
                ->where('completed', false)
                ->latest()
                ->first();
                
            if (!$cart) {
                $cart = \App\Models\Cart::create([
                    'user_id' => Auth::id(),
                    'total_price' => 0,
                    'total_items' => 0,
                    'completed' => false
                ]);
            }
            
            return $cart;
        }
        
        // Redirect to login if not authenticated
        return redirect()->route('login');
    }
    
    public function render()
    {
        return view('livewire.checkout-form');
    }
} 