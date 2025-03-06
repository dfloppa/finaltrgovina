@extends('layouts.shop')

@section('title', 'Order Cancelled')

@section('content')
<div class="bg-white py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="mb-8">
            <svg class="mx-auto h-24 w-24 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Order Cancelled</h1>
        <p class="text-lg text-gray-600 mb-8">Your order has been cancelled. No charges have been made.</p>
        
        <div class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">What Would You Like to Do?</h2>
            <ul class="text-left space-y-4">
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span>Review your cart and try again</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span>Continue shopping for more items</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span>Contact our support team if you need assistance</span>
                </li>
            </ul>
        </div>

        <div class="space-x-4">
            <a href="{{ route('cart.index') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition duration-200">
                Return to Cart
            </a>
            <a href="{{ route('home') }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-300 transition duration-200">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection 