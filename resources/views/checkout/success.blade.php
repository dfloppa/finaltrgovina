@extends('layouts.shop')

@section('title', 'Order Successful')

@section('content')
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <div class="mb-8">
                <svg class="mx-auto h-24 w-24 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
            <p class="text-lg text-gray-600 mb-4">Your order has been successfully placed and is being processed.</p>
            <p class="text-lg font-medium text-gray-800">Order Number: <span class="text-primary">{{ $order->order_number }}</span></p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <!-- Order Details -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-8">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Order Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Billing Information</h3>
                            <address class="not-italic text-gray-600">
                                {{ $order->billing_name }}<br>
                                {{ $order->billing_address }}<br>
                                {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zipcode }}<br>
                                {{ $order->billing_country }}<br>
                                <br>
                                <strong>Email:</strong> {{ $order->billing_email }}<br>
                                @if($order->billing_phone)
                                <strong>Phone:</strong> {{ $order->billing_phone }}
                                @endif
                            </address>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Shipping Information</h3>
                            <address class="not-italic text-gray-600">
                                {{ $order->shipping_name }}<br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}<br>
                                {{ $order->shipping_country }}
                            </address>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Order Summary</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($item->price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($order->total_price - ($order->total_price * 0.1), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tax (10%)</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($order->total_price * 0.1, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-primary">${{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Payment Method</h3>
                            <p class="text-gray-600">{{ $order->payment_method }}</p>
                            <p class="text-gray-600 mt-1">Status: <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($order->payment_status) }}</span></p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Order Status</h3>
                            <p class="text-gray-600">Status: <span class="font-medium text-primary">{{ ucfirst($order->status) }}</span></p>
                            <p class="text-gray-600 mt-1">Date: {{ $order->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">What's Next?</h2>
                <ul class="text-left space-y-4">
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span>You will receive an order confirmation email shortly.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span>We will notify you when your order has been shipped.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span>You can track your order status in your account dashboard.</span>
                    </li>
                </ul>
            </div>

            <div class="text-center space-x-4">
                <a href="{{ route('home') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition duration-200">
                    Continue Shopping
                </a>
                @auth
                <a href="{{ route('orders.index') }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-300 transition duration-200">
                    View Orders
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear the cart after successful order
    window.cart.clear();
    window.cart.updateDisplay();
});
</script>
@endpush
@endsection 