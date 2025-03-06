<div>
    <div class="container mx-auto px-4 py-8">
        <!-- Filters and Search -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <!-- Search -->
                <div class="w-full md:w-1/3">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Products</label>
                    <input 
                        type="text" 
                        id="search" 
                        wire:model.live.debounce.300ms="search" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Search products..."
                    >
                </div>
                
                <!-- Category Filter -->
                <div class="w-full md:w-1/4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select 
                        id="category" 
                        wire:model.live="categoryFilter" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <img 
                            src="{{ $product->image_url }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-48 object-cover"
                        >
                    </a>
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-lg font-semibold text-gray-800 hover:text-primary transition-colors">{{ $product->name }}</h3>
                        </a>
                        <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-primary">${{ number_format($product->price, 2) }}</span>
                            <button 
                                type="button"
                                class="add-to-cart-btn bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark transition-colors"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}"
                                {{ !$product->inStock() ? 'disabled' : '' }}
                            >
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No products found matching your criteria.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // The cart functionality is now handled by the Cart class in shop.blade.php
            // No need for additional JavaScript here as the Cart class listens for clicks
            // on elements with the add-to-cart-btn class
        });
    </script>
</div>
