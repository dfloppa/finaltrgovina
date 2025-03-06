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
                            Shopping Cart
                        </a>
                    </div>
                </li>
                <li>
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
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Checkout</h2>

                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            
                            <!-- Billing Information -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Billing Information</h3>
                                    <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                        <div>
                                            <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                                            <input type="text" name="first_name" id="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>

                                        <div>
                                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                                            <input type="text" name="last_name" id="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
                                            <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="address" class="block text-sm font-medium text-gray-700">Street address</label>
                                            <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>

                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                            <input type="text" name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>

                                        <div>
                                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal code</label>
                                            <input type="text" name="postal_code" id="postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Information -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                                    <div class="mt-4">
                                        <div class="relative">
                                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                                <div class="w-full border-t border-gray-200"></div>
                                            </div>
                                            <div class="relative flex justify-center">
                                                <span class="bg-white px-4 text-sm font-medium text-gray-500">Payment will be handled securely</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>

                        <div class="mt-6">
                            <div class="flow-root">
                                <ul role="list" class="-my-6 divide-y divide-gray-200">
                                    @foreach($cart->items as $item)
                                        <li class="flex py-6">
                                            <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/products/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="h-full w-full object-cover object-center">
                                                @else
                                                    <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="ml-4 flex flex-1 flex-col">
                                                <div>
                                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                                        <h3>{{ $item->product->name }}</h3>
                                                        <p class="ml-4">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                                    </div>
                                                    <p class="mt-1 text-sm text-gray-500">Qty {{ $item->quantity }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                <dt class="text-base font-medium text-gray-900">Order total</dt>
                                <dd class="text-base font-medium text-gray-900">${{ number_format($cart->total_price, 2) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('cart.index') }}" class="text-sm font-medium text-primary hover:text-primary-dark">
                        &larr; Return to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 