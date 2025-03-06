<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Check if there was a previous user ID in the session
        $previousUserId = $request->session()->get('previous_user_id');
        $previousCartId = $request->session()->get('previous_cart_id');
        
        // Only regenerate the session if there was no previous user ID
        // This ensures we don't lose the cart data
        if (!$previousUserId) {
            $request->session()->regenerate();
        } else {
            // Remove the previous user ID from the session
            $request->session()->forget('previous_user_id');
        }

        // If there was a previous cart ID, restore it
        if ($previousCartId) {
            $previousCart = \App\Models\Cart::find($previousCartId);
            
            if ($previousCart) {
                // Update the cart with the current user ID
                $previousCart->user_id = Auth::id();
                $previousCart->save();
                
                // Remove the previous cart ID from the session
                $request->session()->forget('previous_cart_id');
            }
        }

        // After login, check if there's a guest cart to merge with the user's cart
        if (Session::has('guest_cart_id')) {
            $guestCart = Cart::where('session_id', Session::getId())
                ->where('id', Session::get('guest_cart_id'))
                ->where('completed', false)
                ->first();

            if ($guestCart) {
                // Get or create the user's cart
                $userCart = Cart::where('user_id', Auth::id())
                    ->where('completed', false)
                    ->latest()
                    ->first();
                
                if (!$userCart) {
                    $userCart = Cart::create([
                        'user_id' => Auth::id(),
                        'total_price' => 0,
                        'total_items' => 0,
                        'completed' => false
                    ]);
                }

                // Move all items from guest cart to user cart
                foreach ($guestCart->items as $item) {
                    $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();
                    
                    if ($existingItem) {
                        // If product already exists in user cart, update quantity
                        $existingItem->quantity += $item->quantity;
                        $existingItem->save();
                    } else {
                        // Otherwise add the product to user cart
                        $userCart->addProduct($item->product, $item->quantity);
                    }
                }
                
                // Delete the guest cart
                $guestCart->delete();
                
                // Recalculate cart total
                $userCart->calculateTotal();
                
                // Remove guest cart ID from session
                Session::forget('guest_cart_id');
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Get the user's cart before logout
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Store the user ID in the session to retrieve the cart later
            $request->session()->put('previous_user_id', $userId);
            
            // Get the user's active cart
            $userCart = \App\Models\Cart::where('user_id', $userId)
                ->where('completed', false)
                ->latest()
                ->first();
                
            // If the user has an active cart, store its ID in the session
            if ($userCart) {
                $request->session()->put('previous_cart_id', $userCart->id);
            }
        }

        Auth::guard('web')->logout();

        // Don't invalidate the session to preserve the cart
        // $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
