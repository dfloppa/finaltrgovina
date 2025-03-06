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
                        <span class="text-gray-500 ml-1 md:ml-2">Shopping Cart</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Shopping Cart</h2>

                        @if($cart && $cart->items->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($cart->items as $item)
                                    <div class="flex py-6 sm:py-8">
                                        <div class="flex-shrink-0 w-24 h-24 sm:w-32 sm:h-32 border border-gray-200 rounded-md overflow-hidden">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/products/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-center object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4 sm:ml-6 flex-1 flex flex-col">
                                            <div>
                                                <div class="flex justify-between">
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-primary">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="ml-4 text-lg font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                                </div>
                                                @if($item->product->isOnSale())
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        <span class="line-through">${{ number_format($item->product->price, 2) }}</span>
                                                        <span class="text-primary ml-2">${{ number_format($item->product->sale_price, 2) }} each</span>
                                                    </p>
                                                @else
                                                    <p class="mt-1 text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                                                @endif
                                            </div>

                                            <div class="flex-1 flex items-end justify-between">
                                                <div class="flex items-center">
                                                    <label for="quantity-{{ $item->id }}" class="sr-only">Quantity</label>
                                                    <select id="quantity-{{ $item->id }}" 
                                                            name="quantity-{{ $item->id }}"
                                                            class="rounded-md border-gray-300 py-1.5 text-base leading-5 focus:border-primary focus:ring-primary"
                                                            wire:model.live="quantity"
                                                            wire:change="updateQuantity({{ $item->id }}, $event.target.value)">
                                                        @for($i = 1; $i <= min($item->product->stock, 10); $i++)
                                                            <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="ml-4">
                                                    <button type="button" 
                                                            wire:click="removeItem({{ $item->id }})"
                                                            class="text-sm font-medium text-primary hover:text-primary-dark">
                                                        <span>Remove</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" 
                                        wire:click="clear"
                                        class="text-sm font-medium text-primary hover:text-primary-dark">
                                    Clear Cart
                                </button>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">Your cart is empty</h3>
                                <p class="mt-1 text-sm text-gray-500">Start adding some items to your cart!</p>
                                <div class="mt-6">
                                    <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            @if($cart && $cart->items->count() > 0)
                <div class="lg:w-1/3">
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>

                            <div class="mt-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">${{ number_format($cart->total_price, 2) }}</dd>
                                </div>

                                <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                    <dt class="text-base font-medium text-gray-900">Order total</dt>
                                    <dd class="text-base font-medium text-gray-900">${{ number_format($cart->total_price, 2) }}</dd>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('cart.checkout') }}" 
                                   class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Proceed to Checkout
                                </a>
                            </div>

                            <div class="mt-6 text-center">
                                <a href="{{ route('shop') }}" class="text-sm font-medium text-primary hover:text-primary-dark">
                                    or Continue Shopping <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div> 