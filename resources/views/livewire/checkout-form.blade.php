<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
</div>

<div>
    <form wire:submit.prevent="placeOrder">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column - Billing Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Billing Information</h2>
                
                <div class="mb-4">
                    <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="billing_name" wire:model="billing_name" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('billing_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="billing_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="billing_email" wire:model="billing_email" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('billing_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" id="billing_phone" wire:model="billing_phone" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('billing_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" id="billing_address" wire:model="billing_address" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('billing_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" id="billing_city" wire:model="billing_city" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('billing_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                        <input type="text" id="billing_state" wire:model="billing_state" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('billing_state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="billing_zipcode" class="block text-sm font-medium text-gray-700 mb-1">Postal/ZIP Code</label>
                        <input type="text" id="billing_zipcode" wire:model="billing_zipcode" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('billing_zipcode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" id="billing_country" wire:model="billing_country" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('billing_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="same_as_billing" class="form-checkbox h-5 w-5 text-primary">
                        <span class="ml-2 text-sm text-gray-700">Shipping address same as billing</span>
                    </label>
                </div>
            </div>
            
            <!-- Right Column - Shipping Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                
                @if (!$same_as_billing)
                    <div class="mb-4">
                        <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="shipping_name" wire:model="shipping_name" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('shipping_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="shipping_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="shipping_email" wire:model="shipping_email" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('shipping_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" id="shipping_phone" wire:model="shipping_phone" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('shipping_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" id="shipping_address" wire:model="shipping_address" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('shipping_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" id="shipping_city" wire:model="shipping_city" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('shipping_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                            <input type="text" id="shipping_state" wire:model="shipping_state" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('shipping_state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="shipping_zipcode" class="block text-sm font-medium text-gray-700 mb-1">Postal/ZIP Code</label>
                            <input type="text" id="shipping_zipcode" wire:model="shipping_zipcode" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('shipping_zipcode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" id="shipping_country" wire:model="shipping_country" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('shipping_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic">Same as billing address</p>
                @endif
                
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Order Notes</h2>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <textarea id="notes" wire:model="notes" rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Special instructions for delivery or any other notes"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
            
            @if(count($cartItems) > 0)
                <div class="border-b pb-4 mb-4">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between items-center py-2">
                            <div class="flex items-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                        <span class="text-gray-500">No image</span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-medium">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($cart->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                        <span>Total</span>
                        <span>${{ number_format($cart->total_price, 2) }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Your cart is empty</p>
                    <a href="{{ route('shop.index') }}" class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark transition duration-200">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Payment Method -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="radio" wire:model="payment_method" value="stripe" class="form-radio h-5 w-5 text-primary">
                    <span class="ml-2 text-sm text-gray-700">Credit Card (Stripe)</span>
                </label>
                <p class="text-sm text-gray-500 ml-7 mt-1">Pay securely using your credit card</p>
            </div>
            
            @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <!-- Submit Button -->
        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-md hover:bg-primary-dark transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
                Place Order
            </button>
        </div>
    </form>
</div>
