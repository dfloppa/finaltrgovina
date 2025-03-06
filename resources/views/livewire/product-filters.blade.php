<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <form wire:submit.prevent class="space-y-6">
        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input 
                type="text" 
                id="search" 
                wire:model.live="search" 
                placeholder="Search products..." 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
            >
        </div>
        
        <!-- Categories -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select 
                id="category" 
                wire:model.live="categoryFilter" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
            >
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Price Range -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
            <div class="flex items-center space-x-2">
                <input 
                    type="number" 
                    wire:model.live="priceMin" 
                    placeholder="Min" 
                    class="w-1/2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                <span class="text-gray-500">-</span>
                <input 
                    type="number" 
                    wire:model.live="priceMax" 
                    placeholder="Max" 
                    class="w-1/2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                >
            </div>
        </div>
        
        <!-- Reset Filters -->
        <div>
            <button 
                type="button" 
                wire:click="resetFilters" 
                class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 transition duration-200"
            >
                Reset Filters
            </button>
        </div>
        
        <!-- Debug Info (hidden in production) -->
        @if(config('app.debug'))
        <div class="mt-4 p-3 bg-gray-100 rounded-md text-xs">
            <p>Debug Info:</p>
            <p>Category Filter: {{ $categoryFilter }}</p>
            <p>Search: {{ $search }}</p>
            <p>Price Min: {{ $priceMin }}</p>
            <p>Price Max: {{ $priceMax }}</p>
        </div>
        @endif
    </form>
</div>
