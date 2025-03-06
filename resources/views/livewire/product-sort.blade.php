<div class="flex items-center">
    <span class="mr-2 text-sm text-gray-600">Sort By</span>
    <div class="relative inline-block">
        <select 
            id="sort" 
            wire:model.live="sortBy" 
            class="appearance-none border border-gray-300 rounded-md text-sm py-2 px-4 pr-8 focus:outline-none focus:ring-2 focus:ring-primary bg-white"
        >
            <option value="newest">Newest</option>
            <option value="price_low_high">Price: Low to High</option>
            <option value="price_high_low">Price: High to Low</option>
            <option value="name_a_z">Name: A to Z</option>
            <option value="name_z_a">Name: Z to A</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
            </svg>
        </div>
    </div>
</div> 