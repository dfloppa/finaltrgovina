<div>
    @if($product->inStock())
        <div class="mt-6">
            <div class="flex items-center space-x-4">
                <div class="w-24">
                    <label for="quantity" class="sr-only">Quantity</label>
                    <input type="number" id="quantity" wire:model.live="quantity" min="1" max="{{ $product->stock }}" value="1" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <button wire:click="addToCart" wire:loading.attr="disabled" 
                    class="flex-1 bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span wire:loading.remove>Add to Cart</span>
                    <span wire:loading>Adding...</span>
                </button>
            </div>
            @if($message)
                <div class="mt-2 text-sm {{ $messageType === 'success' ? 'text-green-500' : 'text-red-500' }}">
                    {{ $message }}
                </div>
            @endif
        </div>
    @else
        <div class="mt-6">
            <button disabled class="w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-md cursor-not-allowed">
                Out of Stock
            </button>
        </div>
    @endif
</div> 