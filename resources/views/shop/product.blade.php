@extends('layouts.shop')

@section('title', $product->name)

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
                        <a href="{{ route('shop') }}" class="text-gray-700 hover:text-primary ml-1 md:ml-2">
                            Shop
                        </a>
                    </div>
                </li>
                @if($product->category)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('shop', ['category' => $product->category->id]) }}" class="text-gray-700 hover:text-primary ml-1 md:ml-2">
                            {{ $product->category->name }}
                        </a>
                    </div>
                </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-500 ml-1 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="bg-gray-100 rounded-lg overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover">
                @else
                    <div class="flex items-center justify-center h-96 bg-gray-200">
                        <span class="text-gray-500">No image available</span>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                
                @if($product->sale_price && $product->sale_price < $product->price)
                    <div class="flex items-center mb-4">
                        <span class="text-2xl font-bold text-primary">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="ml-2 text-lg text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Save {{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                    </div>
                @else
                    <div class="text-2xl font-bold text-primary mb-4">${{ number_format($product->price, 2) }}</div>
                @endif

                <!-- Stock Status -->
                @if($product->inStock())
                    <div class="flex items-center mb-4">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">In Stock</span>
                        @if($product->stock > 0)
                            <span class="ml-2 text-sm text-gray-500">{{ $product->stock }} available</span>
                        @endif
                    </div>
                @else
                    <div class="flex items-center mb-4">
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Out of Stock</span>
                    </div>
                @endif

                <!-- Description -->
                <div class="prose max-w-none mb-6">
                    {!! $product->description !!}
                </div>

                <!-- Add to Cart Form -->
                @if($product->inStock())
                    <div class="mt-6">
                        <div class="flex items-center space-x-4">
                            <div class="inline-flex items-center border border-gray-300 rounded-md">
                                <button type="button" id="decrease-quantity" 
                                    class="w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-50 text-gray-600 focus:outline-none">
                                    <span class="text-lg font-medium">−</span>
                                </button>
                                <input type="text" id="product-quantity" min="1" max="{{ $product->stock }}" value="1" 
                                    class="w-10 h-10 text-center border-x border-gray-300 bg-white focus:outline-none focus:ring-0"
                                    readonly>
                                <button type="button" id="increase-quantity" 
                                    class="w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-50 text-gray-600 focus:outline-none">
                                    <span class="text-lg font-medium">+</span>
                                </button>
                            </div>
                            <button type="button"
                                class="add-to-cart-btn flex-1 bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition duration-200 flex items-center justify-center"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        </div>
                        <div id="cart-message" class="mt-2 text-sm hidden"></div>
                    </div>
                @else
                    <div class="mt-6">
                        <button disabled class="w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-md cursor-not-allowed">
                            Out of Stock
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if(count($relatedProducts) > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition duration-200">
                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                        <div class="h-48 overflow-hidden">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No image</span>
                                </div>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-lg font-medium text-gray-900 hover:text-primary">
                            {{ $relatedProduct->name }}
                        </a>
                        <div class="mt-2">
                            @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                <span class="text-primary font-bold">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                <span class="ml-2 text-sm text-gray-500 line-through">${{ number_format($relatedProduct->price, 2) }}</span>
                            @else
                                <span class="text-primary font-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('product-quantity');
        const decreaseBtn = document.getElementById('decrease-quantity');
        const increaseBtn = document.getElementById('increase-quantity');
        const addToCartBtn = document.querySelector('.add-to-cart-btn');
        
        if (quantityInput && addToCartBtn) {
            // Set initial quantity
            addToCartBtn.dataset.quantity = quantityInput.value;
            
            // Handle decrease button click
            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    const minValue = parseInt(quantityInput.getAttribute('min'));
                    
                    if (currentValue > minValue) {
                        quantityInput.value = currentValue - 1;
                        // Update button data attribute
                        addToCartBtn.dataset.quantity = quantityInput.value;
                    }
                });
            }
            
            // Handle increase button click
            if (increaseBtn) {
                increaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    const maxValue = parseInt(quantityInput.getAttribute('max'));
                    
                    if (!maxValue || currentValue < maxValue) {
                        quantityInput.value = currentValue + 1;
                        // Update button data attribute
                        addToCartBtn.dataset.quantity = quantityInput.value;
                    }
                });
            }
            
            // Allow manual input but validate it
            quantityInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                
                if (value === '' || parseInt(value) < 1) {
                    value = '1';
                }
                
                const maxValue = parseInt(this.getAttribute('max'));
                if (maxValue && parseInt(value) > maxValue) {
                    value = maxValue.toString();
                }
                
                this.value = value;
                addToCartBtn.dataset.quantity = value;
            });
        }
    });
</script>
@endpush 