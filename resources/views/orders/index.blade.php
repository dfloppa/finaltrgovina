@extends('layouts.shop')

@section('title', 'My Orders')

@section('content')
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

        @if($orders->isEmpty())
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('shop') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition duration-200">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
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

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Total Amount</p>
                                    <p class="text-lg font-medium text-gray-900">${{ number_format($order->total_price, 2) }}</p>
                                </div>
                                <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center text-primary hover:text-primary-dark">
                                    View Details
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 