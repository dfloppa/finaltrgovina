@extends('layouts.shop')

@section('title', 'Shopping Cart')

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
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">Shopping Cart</h2>
                            <button class="clear-cart-btn text-red-600 hover:text-red-800">
                                Clear Cart
                            </button>
                        </div>

                        <!-- Empty Cart Message -->
                        <div class="empty-cart-message text-center py-8 hidden">
                            <p class="text-gray-500 text-lg">Your cart is empty</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                                Continue Shopping
                            </a>
                        </div>

                        <!-- Cart Content -->
                        <div class="cart-content">
                            <div class="cart-items divide-y divide-gray-200">
                                <!-- Cart items will be inserted here by JavaScript -->
                            </div>

                            <!-- Cart Summary -->
                            <div class="mt-8 border-t border-gray-200 pt-8">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-primary cart-total">$0.00</span>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('checkout.index') }}" class="w-full bg-primary text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-primary-dark">
                                        Proceed to Checkout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="lg:w-1/3">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">You might also like</h3>
                    <!-- Add your related products here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 