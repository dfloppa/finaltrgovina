<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cart = $this->getCart();

        if ($request->wantsJson()) {
            return response()->json([
                'items' => $cart->items()->with('product')->get(),
                'total_items' => $cart->total_items,
                'total_price' => $cart->total_price
            ]);
        }

        return view('cart.index', compact('cart'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cartItem = CartItem::findOrFail($id);
            
            // Verify the cart item belongs to the current user's cart
            if ($cartItem->cart_id !== $this->getCart()->id) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Unauthorized action.'
                    ], 403);
                }
                
                return redirect()->route('cart.index')
                    ->with('error', 'Unauthorized action.');
            }

            // Check if the requested quantity is available
            if (!$cartItem->product->inStock() || $cartItem->product->stock < $request->quantity) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'The requested quantity is not available in stock.'
                    ], 422);
                }
                
                return redirect()->route('cart.index')
                    ->with('error', 'The requested quantity is not available in stock.');
            }

            // If quantity is 0, remove the item
            if ($request->quantity <= 0) {
                $cart = $cartItem->cart;
                $cartItem->delete();
                $cart->calculateTotal();
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Item removed from cart successfully.',
                        'cartCount' => $cart->total_items
                    ]);
                }
                
                return redirect()->route('cart.index')
                    ->with('success', 'Item removed from cart successfully.');
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Recalculate cart total
            $cartItem->cart->calculateTotal();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Cart updated successfully.',
                    'cartCount' => $cartItem->cart->total_items
                ]);
            }
            
            return redirect()->route('cart.index')
                ->with('success', 'Cart updated successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'An error occurred while updating the cart: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('cart.index')
                ->with('error', 'An error occurred while updating the cart.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);
            
            // Verify the cart item belongs to the current user's cart
            if ($cartItem->cart_id !== $this->getCart()->id) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'message' => 'Unauthorized action.'
                    ], 403);
                }
                
                return redirect()->route('cart.index')
                    ->with('error', 'Unauthorized action.');
            }

            $cart = $cartItem->cart;
            $cartItem->delete();
            
            // Recalculate cart total
            $cart->calculateTotal();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Item removed from cart successfully.',
                    'cartCount' => $cart->total_items
                ]);
            }
            
            return redirect()->route('cart.index')
                ->with('success', 'Item removed from cart successfully.');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'An error occurred while removing the item from cart: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('cart.index')
                ->with('error', 'An error occurred while removing the item from cart.');
        }
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->clear();

        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully.');
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);
            
            $product = Product::findOrFail($request->product_id);
            
            // Check if product is in stock
            if (!$product->inStock() || $product->stock < $request->quantity) {
                return response()->json([
                    'message' => 'Sorry, this product is out of stock or the requested quantity is not available.'
                ], 422);
            }
            
            $cart = $this->getCart();
            $cart->addProduct($product, $request->quantity);
            
            return response()->json([
                'message' => 'Product added to cart successfully!',
                'cartCount' => $cart->total_items
            ]);
            
        } catch (\Exception $e) {
            Log::error('Cart addition error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while adding the product to cart.'
            ], 500);
        }
    }

    /**
     * Get the current cart or create a new one.
     */
    public function getCart()
    {
        if (Auth::check()) {
            // For logged in users - get their most recent active cart
            $cart = Cart::where('user_id', Auth::id())
                ->where('completed', false)
                ->latest()
                ->first();
                
            // If no active cart exists, create a new one
            if (!$cart) {
                // Check if there's a previous cart ID in the session
                $previousCartId = Session::get('previous_cart_id');
                if ($previousCartId) {
                    $previousCart = Cart::find($previousCartId);
                    if ($previousCart) {
                        // Update the cart with the current user ID
                        $previousCart->user_id = Auth::id();
                        $previousCart->save();
                        Session::forget('previous_cart_id');
                        return $previousCart;
                    }
                }
                
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'total_price' => 0,
                    'total_items' => 0,
                    'completed' => false
                ]);
            }

            // If there was a guest cart in session, merge it with the user's cart
            if (Session::has('guest_cart_id')) {
                $guestCart = Cart::where('session_id', Session::getId())
                    ->where('id', Session::get('guest_cart_id'))
                    ->where('completed', false)
                    ->first();

                if ($guestCart) {
                    // Move all items from guest cart to user cart
                    foreach ($guestCart->items as $item) {
                        $existingItem = $cart->items()->where('product_id', $item->product_id)->first();
                        
                        if ($existingItem) {
                            // If product already exists in user cart, update quantity
                            $existingItem->quantity += $item->quantity;
                            $existingItem->save();
                        } else {
                            // Otherwise add the product to user cart
                            $cart->addProduct($item->product, $item->quantity);
                        }
                    }
                    // Delete the guest cart
                    $guestCart->delete();
                }
                Session::forget('guest_cart_id');
            }

            // Recalculate cart total to ensure it's accurate
            $cart->calculateTotal();
            
            return $cart;
        }

        // For guests
        $sessionId = Session::getId();
        
        // Check if there's a previous user ID in the session
        $previousUserId = Session::get('previous_user_id');
        if ($previousUserId) {
            // Check if there's a previous cart ID in the session
            $previousCartId = Session::get('previous_cart_id');
            if ($previousCartId) {
                $previousCart = Cart::find($previousCartId);
                if ($previousCart) {
                    // Create a new guest cart
                    $guestCart = Cart::create([
                        'session_id' => $sessionId,
                        'total_price' => 0,
                        'total_items' => 0,
                        'completed' => false
                    ]);
                    
                    // Copy items from the previous user's cart to the guest cart
                    foreach ($previousCart->items as $item) {
                        $guestCart->addProduct($item->product, $item->quantity);
                    }
                    
                    // Store the guest cart ID in the session
                    Session::put('guest_cart_id', $guestCart->id);
                    
                    // Remove the previous user ID and cart ID from the session
                    Session::forget('previous_user_id');
                    Session::forget('previous_cart_id');
                    
                    return $guestCart;
                }
            }
            
            // Get the previous user's cart
            $previousCart = Cart::where('user_id', $previousUserId)
                ->where('completed', false)
                ->latest()
                ->first();
                
            if ($previousCart) {
                // Create a new guest cart
                $guestCart = Cart::create([
                    'session_id' => $sessionId,
                    'total_price' => 0,
                    'total_items' => 0,
                    'completed' => false
                ]);
                
                // Copy items from the previous user's cart to the guest cart
                foreach ($previousCart->items as $item) {
                    $guestCart->addProduct($item->product, $item->quantity);
                }
                
                // Store the guest cart ID in the session
                Session::put('guest_cart_id', $guestCart->id);
                
                // Remove the previous user ID from the session
                Session::forget('previous_user_id');
                
                return $guestCart;
            }
        }
        
        // Try to get existing guest cart from session
        if (Session::has('guest_cart_id')) {
            $cart = Cart::where('session_id', $sessionId)
                ->where('id', Session::get('guest_cart_id'))
                ->where('completed', false)
                ->first();
            
            if ($cart) {
                return $cart;
            }
        }
        
        // Create new guest cart if none exists
        $cart = Cart::create([
            'session_id' => $sessionId,
            'total_price' => 0,
            'total_items' => 0,
            'completed' => false
        ]);
        
        // Store cart ID in session
        Session::put('guest_cart_id', $cart->id);
        
        return $cart;
    }

    /**
     * Show the checkout page.
     */
    public function checkout()
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to proceed with checkout.');
        }

        return view('cart.checkout', compact('cart'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);
            
            $product = Product::findOrFail($validated['product_id']);
            
            // Check if product is in stock
            if (!$product->inStock() || $product->stock < $validated['quantity']) {
                return response()->json([
                    'message' => 'Sorry, this product is out of stock or the requested quantity is not available.'
                ], 422);
            }
            
            // Get or create cart using the existing method
            $cart = $this->getCart();
            
            // Add product to cart using the existing method
            $cart->addProduct($product, $validated['quantity']);
            
            return response()->json([
                'message' => 'Product added to cart successfully!',
                'cartCount' => $cart->total_items
            ]);
            
        } catch (\Exception $e) {
            Log::error('Cart addition error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while adding the product to cart: ' . $e->getMessage()
            ], 500);
        }
    }
}
