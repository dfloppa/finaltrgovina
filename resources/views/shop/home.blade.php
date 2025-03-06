@extends('layouts.shop')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gray-900">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="{{ asset('images/hero-bg.jpg') }}" alt="Hero background">
        <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Welcome to Our Shop</h1>
        <p class="mt-6 text-xl text-gray-300 max-w-3xl">Discover our amazing collection of products at great prices.</p>
        <div class="mt-10">
            <a href="{{ route('shop') }}" class="inline-block bg-primary border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-primary-dark">
                Shop Now
            </a>
        </div>
    </div>
</div>

<!-- Featured Categories -->
<div class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Browse our wide selection of categories to find exactly what you're looking for.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="group">
                    <div class="relative rounded-lg overflow-hidden">
                        @if($category->image)
                            <img 
                                src="{{ asset('storage/categories/' . $category->image) }}" 
                                alt="{{ $category->name }}" 
                                class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-300"
                            >
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $category->name }}</h3>
                            <p class="text-white/80">{{ $category->products_count }} Products</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Discover our handpicked selection of featured products.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="product-card group">
                    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                        <!-- Product Image -->
                        <div class="aspect-w-1 aspect-h-1 overflow-hidden rounded-t-lg">
                            @if($product->image)
                                <img 
                                    src="{{ asset('storage/products/' . $product->image) }}" 
                                    alt="{{ $product->name }}" 
                                    class="product-image object-cover w-full h-full transform group-hover:scale-105 transition duration-300"
                                >
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Sale Badge -->
                            @if($product->isOnSale())
                                <div class="absolute top-2 right-2">
                                    <span class="badge-sale px-2 py-1 text-xs font-bold rounded">
                                        -{{ $product->getDiscountPercentage() }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                    
                    <!-- Product Info -->
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-lg font-medium text-gray-900 mb-2 group-hover:text-primary transition duration-200">{{ $product->name }}</h3>
                        </a>
                        
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                @if($product->isOnSale())
                                    <span class="text-lg font-bold text-primary">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-500">
                                @if($product->inStock())
                                    <span class="text-green-600">In Stock</span>
                                @else
                                    <span class="text-red-600">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a 
                                href="{{ route('products.show', $product->slug) }}" 
                                class="flex-1 bg-gray-200 text-gray-800 py-2 px-3 rounded-md hover:bg-gray-300 transition duration-200 flex items-center justify-center"
                            >
                                View
                            </a>
                            <button 
                                type="button" 
                                class="add-to-cart-btn flex-1 bg-primary text-white py-2 px-3 rounded-md hover:bg-primary-dark transition-colors {{ !$product->inStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$product->inStock() ? 'disabled' : '' }}
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->isOnSale() ? $product->sale_price : $product->price }}">
                                {{ $product->inStock() ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- New Arrivals -->
<div class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">New Arrivals</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Check out our latest products.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($newArrivals as $product)
                <div class="product-card group">
                    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                        <!-- Product Image -->
                        <div class="aspect-w-1 aspect-h-1 overflow-hidden rounded-t-lg">
                            @if($product->image)
                                <img 
                                    src="{{ asset('storage/products/' . $product->image) }}" 
                                    alt="{{ $product->name }}" 
                                    class="product-image object-cover w-full h-full transform group-hover:scale-105 transition duration-300"
                                >
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- New Badge -->
                            <div class="absolute top-2 left-2">
                                <span class="bg-green-500 text-white px-2 py-1 text-xs font-bold rounded">
                                    NEW
                                </span>
                            </div>
                            
                            <!-- Sale Badge -->
                            @if($product->isOnSale())
                                <div class="absolute top-2 right-2">
                                    <span class="badge-sale px-2 py-1 text-xs font-bold rounded">
                                        -{{ $product->getDiscountPercentage() }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                    
                    <!-- Product Info -->
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-lg font-medium text-gray-900 mb-2 group-hover:text-primary transition duration-200">{{ $product->name }}</h3>
                        </a>
                        
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                @if($product->isOnSale())
                                    <span class="text-lg font-bold text-primary">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-500">
                                @if($product->inStock())
                                    <span class="text-green-600">In Stock</span>
                                @else
                                    <span class="text-red-600">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a 
                                href="{{ route('products.show', $product->slug) }}" 
                                class="flex-1 bg-gray-200 text-gray-800 py-2 px-3 rounded-md hover:bg-gray-300 transition duration-200 flex items-center justify-center"
                            >
                                View
                            </a>
                            <button 
                                type="button" 
                                class="add-to-cart-btn flex-1 bg-primary text-white py-2 px-3 rounded-md hover:bg-primary-dark transition-colors {{ !$product->inStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$product->inStock() ? 'disabled' : '' }}
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->isOnSale() ? $product->sale_price : $product->price }}">
                                {{ $product->inStock() ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Shop With Us</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">We provide the best shopping experience with these amazing features.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 text-primary rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Free Shipping</h3>
                <p class="text-gray-600">Enjoy free shipping on all orders over $50. No minimum purchase required.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 text-primary rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Payments</h3>
                <p class="text-gray-600">We use the latest security measures to ensure your payments are safe and secure.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 text-primary rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Returns</h3>
                <p class="text-gray-600">Not satisfied with your purchase? Return it within 30 days for a full refund.</p>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="py-16 bg-primary">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-white/80 mb-8">Stay updated with our latest products, promotions, and exclusive offers.</p>
            
            <form class="flex flex-col sm:flex-row gap-4">
                <input 
                    type="email" 
                    placeholder="Enter your email address" 
                    class="flex-1 px-4 py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-white"
                >
                <button 
                    type="submit" 
                    class="bg-white text-primary px-6 py-3 rounded-md font-medium hover:bg-gray-100 transition duration-200"
                >
                    Subscribe
                </button>
            </form>
            
            <p class="text-white/60 mt-4 text-sm">By subscribing, you agree to our Privacy Policy and consent to receive updates from our company.</p>
        </div>
    </div>
</div>
@endsection 