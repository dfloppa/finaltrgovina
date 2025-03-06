@extends('layouts.shop')

@section('title', 'Order Details')

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
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-primary ml-1 md:ml-2">
                            My Orders
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-500 ml-1 md:ml-2">Order #{{ $order->order_number }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Order Header -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-8">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-900">Order Items</h2>
            </div>
            <div class="border-t border-gray-200 divide-y divide-gray-200">
                @foreach($order->items as $item)
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/products/' . $item->product->image) }}" 
                                         alt="{{ $item->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-6 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item->name }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900">${{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Shipping Information -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900">Shipping Information</h2>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_name }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}<br>
                                {{ $order->shipping_country }}
                            </dd>
                        </div>
                        @if($order->shipping_phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_phone }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_email }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900">Payment Information</h2>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->payment_method) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->payment_status) }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($order->total_price, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Shipping</dt>
                            <dd class="mt-1 text-sm text-gray-900">Free</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-base font-medium text-gray-900">Total</dt>
                            <dd class="mt-1 text-2xl font-bold text-primary">${{ number_format($order->total_price, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 