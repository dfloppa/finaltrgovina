@extends('layouts.shop')

@section('title', 'Order Confirmed')

@section('content')
<div class="bg-white py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Your order has been confirmed and is being processed. You will receive an email confirmation shortly.</p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-8 max-w-3xl mx-auto mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Order #{{ $order->order_number }}</h2>
                <span class="text-gray-500">{{ $order->created_at->format('F j, Y') }}</span>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium mb-4">Order Summary</h3>
                
                <div class="space-y-4 mb-6">
                    @foreach($order->items as $item)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-md overflow-hidden">
                                @if($item->product && $item->product->image)
                                    <img 
                                        src="{{ asset('storage/products/' . $item->product->image) }}" 
                                        alt="{{ $item->name }}" 
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-medium text-gray-900">{{ $item->name }}</h4>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-base font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between">
                        <p class="text-gray-600">Subtotal</p>
                        <p class="text-gray-900 font-medium">${{ number_format($order->total_price, 2) }}</p>
                    </div>
                    
                    <div class="flex justify-between">
                        <p class="text-gray-600">Shipping</p>
                        <p class="text-gray-900 font-medium">Free</p>
                    </div>
                    
                    <div class="flex justify-between border-t border-gray-200 pt-4">
                        <p class="text-lg font-semibold text-gray-900">Total</p>
                        <p class="text-lg font-semibold text-primary">${{ number_format($order->total_price, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-medium mb-4">Shipping Information</h3>
                    <address class="not-italic text-gray-600">
                        {{ $order->shipping_name }}<br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}<br>
                        {{ $order->shipping_country }}<br>
                        {{ $order->shipping_phone }}
                    </address>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium mb-4">Payment Information</h3>
                    <p class="text-gray-600">
                        <span class="font-medium">Method:</span> {{ ucfirst($order->payment_method) }}<br>
                        <span class="font-medium">Status:</span> {{ ucfirst($order->payment_status) }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('orders.show', $order->id) }}" class="bg-primary text-white py-3 px-6 rounded-md hover:bg-primary-dark transition duration-200">
                View Order Details
            </a>
            
            <a href="{{ route('shop') }}" class="bg-white text-gray-800 py-3 px-6 rounded-md border border-gray-300 hover:bg-gray-100 transition duration-200">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection 