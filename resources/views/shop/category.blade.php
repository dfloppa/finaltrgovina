@extends('layouts.shop')

@section('title', $category->name)

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
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-500 ml-1 md:ml-2">{{ $category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Category Header -->
        <div class="mb-8">
            <div class="relative rounded-lg overflow-hidden h-64 mb-6">
                @if($category->image)
                    <img 
                        src="{{ asset('storage/categories/' . $category->image) }}" 
                        alt="{{ $category->name }}" 
                        class="w-full h-full object-cover"
                    >
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $category->name }}</h1>
                    <p class="text-white/80">{{ $products->total() }} Products</p>
                </div>
            </div>
            
            @if($category->description)
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700">{{ $category->description }}</p>
                </div>
            @endif
        </div>
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters -->
            <div class="lg:w-1/4">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Filters</h2>
                    <!-- Add your filter options here -->
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">{{ $category->name }} Products</h2>
                    
                    <div class="flex items-center">
                        <label for="sort" class="mr-2 text-sm text-gray-600">Sort by:</label>
                        <select id="sort" class="border border-gray-300 rounded-md text-sm py-1 px-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="newest">Newest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </div>
                
                @if($products->isEmpty())
                    <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search criteria.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
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
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 