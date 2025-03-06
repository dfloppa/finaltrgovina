@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-primary ml-1 md:ml-2">
                            Cart
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-500 ml-1 md:ml-2">Checkout</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Checkout Form -->
            <div class="lg:w-2/3">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Checkout Information</h2>

                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Billing Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="billing_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="billing_name" name="billing_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->name ?? old('billing_name') }}" required>
                                </div>
                                <div>
                                    <label for="billing_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" id="billing_email" name="billing_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->email ?? old('billing_email') }}" required>
                                </div>
                                <div>
                                    <label for="billing_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" id="billing_phone" name="billing_phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->phone ?? old('billing_phone') }}">
                                </div>
                                <div>
                                    <label for="billing_address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" id="billing_address" name="billing_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->address ?? old('billing_address') }}" required>
                                </div>
                                <div>
                                    <label for="billing_city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" id="billing_city" name="billing_city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->city ?? old('billing_city') }}" required>
                                </div>
                                <div>
                                    <label for="billing_zipcode" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                    <input type="text" id="billing_zipcode" name="billing_zipcode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->zipcode ?? old('billing_zipcode') }}" required>
                                </div>
                                <div>
                                    <label for="billing_state" class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="billing_state" name="billing_state" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->state ?? old('billing_state') }}">
                                </div>
                                <div>
                                    <label for="billing_country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <input type="text" id="billing_country" name="billing_country" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['billing']->country ?? old('billing_country') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Shipping Information</h3>
                                <label class="flex items-center">
                                    <input type="checkbox" id="same_as_billing" class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-600">Same as billing</span>
                                </label>
                            </div>
                            <div id="shipping_fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="shipping_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="shipping_name" name="shipping_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['shipping']->name ?? old('shipping_name') }}" required>
                                </div>
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" id="shipping_address" name="shipping_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['shipping']->address ?? old('shipping_address') }}" required>
                                </div>
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" id="shipping_city" name="shipping_city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['shipping']->city ?? old('shipping_city') }}" required>
                                </div>
                                <div>
                                    <label for="shipping_zipcode" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                    <input type="text" id="shipping_zipcode" name="shipping_zipcode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['shipping']->zipcode ?? old('shipping_zipcode') }}" required>
                                </div>
                                <div>
                                    <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="shipping_state" name="shipping_state" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['shipping']->state ?? old('shipping_state') }}">
                                </div>
                                <div>
                                    <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <input type="text" id="shipping_country" name="shipping_country" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary" value="{{ $savedCheckoutData['shipping']->country ?? old('shipping_country') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Methods -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Method</h3>
                            <div class="space-y-3">
                                @foreach($shippingMethods as $method)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="shipping_method_{{ $method->id }}" name="shipping_method_id" type="radio" value="{{ $method->id }}" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" 
                                        {{ ($savedCheckoutData['preferred_shipping_method_id'] == $method->id) ? 'checked' : ($loop->first ? 'checked' : '') }} required>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="shipping_method_{{ $method->id }}" class="font-medium text-gray-700 flex justify-between w-full">
                                            <span>{{ $method->name }}</span>
                                            <span>${{ number_format($method->price, 2) }}</span>
                                        </label>
                                        <p class="text-gray-500">{{ $method->description }} ({{ $method->delivery_time }})</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h3>
                            <div class="space-y-3">
                                @foreach($paymentMethods as $method)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment_method_{{ $method->id }}" name="payment_method_id" type="radio" value="{{ $method->id }}" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" 
                                        {{ ($savedCheckoutData['preferred_payment_method_id'] == $method->id) ? 'checked' : ($loop->first ? 'checked' : '') }} required>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment_method_{{ $method->id }}" class="font-medium text-gray-700">
                                            <i class="fas {{ $method->icon }} mr-2"></i>
                                            {{ $method->name }}
                                        </label>
                                        <p class="text-gray-500">{{ $method->description }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('notes') }}</textarea>
                        </div>
                        
                        <!-- Save Information Checkbox -->
                        @auth
                        <div class="flex items-center">
                            <input id="save_info" name="save_info" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" value="1" checked>
                            <label for="save_info" class="ml-2 block text-sm text-gray-900">
                                Save this information for future orders
                            </label>
                        </div>
                        @endauth
                        
                        <!-- Submit Button (Mobile) -->
                        <div class="lg:hidden">
                            <button type="submit" class="w-full bg-primary text-white py-3 px-4 rounded-md hover:bg-primary-dark transition duration-200 flex items-center justify-center">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white shadow-sm rounded-lg p-6 sticky top-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="cart-items divide-y divide-gray-200">
                            @foreach($cart->items as $item)
                            <div class="flex justify-between py-4">
                                <div>
                                    <h4 class="text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <span class="text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">${{ number_format($cart->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-gray-900 shipping-cost">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Tax</span>
                                <span class="text-gray-900 tax-amount">${{ number_format($cart->total_price * 0.1, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-medium">
                                <span class="text-gray-900">Total</span>
                                <span class="text-primary order-total">${{ number_format($cart->total_price + ($cart->total_price * 0.1), 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 hidden lg:block">
                            <button type="submit" form="checkout-form" class="w-full bg-primary text-white py-3 px-4 rounded-md hover:bg-primary-dark transition duration-200 flex items-center justify-center">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Same as billing" checkbox
    const sameAsBillingCheckbox = document.getElementById('same_as_billing');
    const shippingFields = document.getElementById('shipping_fields');

    sameAsBillingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Copy billing info to shipping fields
            document.getElementById('shipping_name').value = document.getElementById('billing_name').value;
            document.getElementById('shipping_address').value = document.getElementById('billing_address').value;
            document.getElementById('shipping_city').value = document.getElementById('billing_city').value;
            document.getElementById('shipping_zipcode').value = document.getElementById('billing_zipcode').value;
            document.getElementById('shipping_state').value = document.getElementById('billing_state').value;
            document.getElementById('shipping_country').value = document.getElementById('billing_country').value;
            
            // Disable shipping fields
            Array.from(shippingFields.getElementsByTagName('input')).forEach(input => {
                input.disabled = true;
            });
        } else {
            // Enable shipping fields
            Array.from(shippingFields.getElementsByTagName('input')).forEach(input => {
                input.disabled = false;
            });
        }
    });

    // Handle shipping method selection
    const shippingMethodInputs = document.querySelectorAll('input[name="shipping_method_id"]');
    const shippingCostElement = document.querySelector('.shipping-cost');
    const taxAmountElement = document.querySelector('.tax-amount');
    const orderTotalElement = document.querySelector('.order-total');
    const subtotalElement = document.querySelector('.text-gray-900:not(.shipping-cost):not(.tax-amount):not(.order-total)');
    
    // Get subtotal value
    const subtotal = parseFloat(subtotalElement.textContent.replace('$', ''));
    
    // Function to update order total
    function updateOrderTotal() {
        const selectedShippingMethod = document.querySelector('input[name="shipping_method_id"]:checked');
        if (selectedShippingMethod) {
            const shippingCost = parseFloat(selectedShippingMethod.closest('.flex').querySelector('.text-gray-700 span:last-child').textContent.replace('$', ''));
            const tax = subtotal * 0.1; // 10% tax
            const total = subtotal + shippingCost + tax;
            
            shippingCostElement.textContent = '$' + shippingCost.toFixed(2);
            taxAmountElement.textContent = '$' + tax.toFixed(2);
            orderTotalElement.textContent = '$' + total.toFixed(2);
        }
    }
    
    // Add event listeners to shipping method inputs
    shippingMethodInputs.forEach(input => {
        input.addEventListener('change', updateOrderTotal);
    });
    
    // Initialize order total
    updateOrderTotal();
});
</script>
@endpush
@endsection 