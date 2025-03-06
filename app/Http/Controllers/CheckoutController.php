<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Get the current cart
        $cart = $this->getCart();
        
        // If cart is empty, redirect to cart page
        if ($cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add some products before checkout.');
        }
        
        // Get active payment methods
        $paymentMethods = PaymentMethod::active()->orderBy('sort_order')->get();
        
        // Get active shipping methods
        $shippingMethods = ShippingMethod::active()->orderBy('sort_order')->get();
        
        // Get saved checkout data for the user
        $savedCheckoutData = $this->getSavedCheckoutData();
        
        return view('checkout.index', compact('cart', 'paymentMethods', 'shippingMethods', 'savedCheckoutData'));
    }
    
    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        // Validate the request
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_zipcode' => 'required|string|max:20',
            'billing_country' => 'required|string|max:255',
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_zipcode' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'notes' => 'nullable|string|max:1000',
            'save_info' => 'nullable|boolean',
        ]);
        
        // Get the current cart
        $cart = $this->getCart();
        
        // If cart is empty, redirect to cart page
        if ($cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add some products before checkout.');
        }
        
        // Get the selected payment method
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
        
        // Get the selected shipping method
        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
        
        try {
            // Start a database transaction
            DB::beginTransaction();
            
            // Calculate total price including shipping
            $subtotal = $cart->total_price;
            $shippingCost = $shippingMethod->price;
            $tax = $subtotal * 0.1; // 10% tax
            $totalPrice = $subtotal + $shippingCost + $tax;
            
            // Create the order
            $order = new Order();
            $order->user_id = Auth::id(); // Will be null for guests
            $order->order_number = Order::generateOrderNumber();
            $order->status = Order::STATUS_PENDING;
            $order->total_price = $totalPrice;
            $order->payment_method = $paymentMethod->name;
            $order->payment_status = Order::PAYMENT_STATUS_PENDING;
            
            // Set billing information
            $order->billing_name = $request->billing_name;
            $order->billing_email = $request->billing_email;
            $order->billing_phone = $request->billing_phone;
            $order->billing_address = $request->billing_address;
            $order->billing_city = $request->billing_city;
            $order->billing_state = $request->billing_state;
            $order->billing_zipcode = $request->billing_zipcode;
            $order->billing_country = $request->billing_country;
            
            // Set shipping information
            $order->shipping_name = $request->shipping_name;
            $order->shipping_email = $request->billing_email; // Use billing email for shipping
            $order->shipping_phone = $request->billing_phone; // Use billing phone for shipping
            $order->shipping_address = $request->shipping_address;
            $order->shipping_city = $request->shipping_city;
            $order->shipping_state = $request->shipping_state;
            $order->shipping_zipcode = $request->shipping_zipcode;
            $order->shipping_country = $request->shipping_country;
            
            // Set notes
            $order->notes = $request->notes;
            
            // Save the order
            $order->save();
            
            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->name = $cartItem->product->name;
                $orderItem->quantity = $cartItem->quantity;
                $orderItem->price = $cartItem->price;
                $orderItem->options = $cartItem->options ?? [];
                $orderItem->save();
            }
            
            // Save checkout data for future use if requested
            if ($request->has('save_info') && $request->save_info && Auth::check()) {
                $this->saveCheckoutData($request);
            }
            
            // Mark the cart as completed
            $cart->completed = true;
            $cart->save();
            
            // Clear the cart session for guests
            if (!Auth::check()) {
                session()->forget('cart_id');
            }
            
            // Process payment based on payment method
            // This would typically involve redirecting to a payment gateway
            // For now, we'll just mark the order as paid for demonstration purposes
            if ($paymentMethod->code === 'cod') {
                // For Cash on Delivery, we keep the payment status as pending
                // The order status is set to processing
                $order->status = Order::STATUS_PROCESSING;
                $order->save();
            } else {
                // For other payment methods, we would redirect to the payment gateway
                // For demonstration, we'll just mark the order as paid
                $order->markAsPaid('demo_payment_' . time());
            }
            
            // Commit the transaction
            DB::commit();
            
            // Store the order ID in the session for the success page
            session(['last_order_id' => $order->id]);
            
            // Redirect to success page
            return redirect()->route('checkout.success');
            
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            
            // Log the error
            Log::error('Order creation failed: ' . $e->getMessage());
            
            // Redirect back with error
            return redirect()->back()
                ->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }
    
    /**
     * Display the success page.
     */
    public function success()
    {
        // Get the last order from session
        $orderId = session('last_order_id');
        
        if (!$orderId) {
            return redirect()->route('home');
        }
        
        $order = Order::find($orderId);
        
        if (!$order) {
            return redirect()->route('home');
        }
        
        return view('checkout.success', compact('order'));
    }
    
    /**
     * Handle the checkout cancel
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }

    /**
     * Handle Stripe webhook.
     */
    public function stripeWebhook(Request $request)
    {
        // Handle Stripe webhook
        return response()->json(['status' => 'success']);
    }
    
    /**
     * Get the current cart or create a new one.
     */
    private function getCart()
    {
        if (Auth::check()) {
            // For logged in users - get their most recent active cart
            $cart = Cart::where('user_id', Auth::id())
                ->where('completed', false)
                ->latest()
                ->first();
                
            if (!$cart) {
                // Create a new cart if none exists
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'completed' => false,
                ]);
            }
            
            return $cart;
        } else {
            // For guests - get cart from session
            $cartId = session('cart_id');
            
            if ($cartId) {
                $cart = Cart::find($cartId);
                
                if ($cart && !$cart->completed) {
                    return $cart;
                }
            }
            
            // Create a new cart if none exists or if the existing one is completed
            $cart = Cart::create([
                'completed' => false,
            ]);
            
            session(['cart_id' => $cart->id]);
            
            return $cart;
        }
    }
    
    /**
     * Save checkout data for future use.
     */
    private function saveCheckoutData(Request $request)
    {
        if (!Auth::check()) {
            return;
        }
        
        $user = Auth::user();
        
        // Save billing address
        $billingAddress = UserAddress::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'billing',
                'is_default' => true
            ],
            [
                'name' => $request->billing_name,
                'email' => $request->billing_email,
                'phone' => $request->billing_phone,
                'address' => $request->billing_address,
                'city' => $request->billing_city,
                'state' => $request->billing_state,
                'zipcode' => $request->billing_zipcode,
                'country' => $request->billing_country,
            ]
        );
        
        // Save shipping address
        $shippingAddress = UserAddress::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'shipping',
                'is_default' => true
            ],
            [
                'name' => $request->shipping_name,
                'email' => $request->billing_email,
                'phone' => $request->billing_phone,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zipcode' => $request->shipping_zipcode,
                'country' => $request->shipping_country,
            ]
        );
        
        // Save preferred payment and shipping methods
        $user->preferred_payment_method_id = $request->payment_method_id;
        $user->preferred_shipping_method_id = $request->shipping_method_id;
        $user->save();
    }
    
    /**
     * Get saved checkout data for the user.
     */
    private function getSavedCheckoutData()
    {
        if (!Auth::check()) {
            return [
                'billing' => null,
                'shipping' => null,
                'preferred_payment_method_id' => null,
                'preferred_shipping_method_id' => null,
            ];
        }
        
        $user = Auth::user();
        
        $data = [
            'billing' => UserAddress::where('user_id', $user->id)
                ->where('type', 'billing')
                ->where('is_default', true)
                ->first(),
                
            'shipping' => UserAddress::where('user_id', $user->id)
                ->where('type', 'shipping')
                ->where('is_default', true)
                ->first(),
                
            'preferred_payment_method_id' => $user->preferred_payment_method_id,
            'preferred_shipping_method_id' => $user->preferred_shipping_method_id,
        ];
        
        return $data;
    }
}
