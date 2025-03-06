<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://js.stripe.com/v3/"></script>
        <script src="{{ url('js/filters.js') }}"></script>
        <script src="{{ url('js/filters-simple.js') }}"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.shop-navigation')

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.shop-footer')
        </div>

        <!-- Cart Sidebar -->
        <div id="cart-sidebar" class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 flex flex-col">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Your Cart</h3>
                <button id="close-cart" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Empty Cart Message -->
            <div class="empty-cart-message text-center py-8 hidden">
                <p class="text-gray-500 text-lg">Your cart is empty</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                    Continue Shopping
                </a>
            </div>

            <!-- Cart Content -->
            <div class="cart-content flex-1 overflow-y-auto">
                <div class="cart-items divide-y divide-gray-200 p-4">
                    <!-- Cart items will be inserted here by JavaScript -->
                </div>

                <!-- Cart Summary -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-medium text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-primary cart-total">$0.00</span>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('cart.index') }}" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark transition duration-200 flex items-center justify-center">
                            View Cart
                        </a>
                        <a href="{{ route('checkout.index') }}" class="w-full bg-gray-900 text-white py-2 px-4 rounded-md hover:bg-gray-800 transition duration-200 flex items-center justify-center">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cart Overlay -->
        <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        <!-- Notification Toast -->
        <div id="notification-toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full transition-transform duration-300 z-50">
            <span id="notification-message"></span>
        </div>
        
        @stack('scripts')
        <script>
            // Cart functionality
            class Cart {
                constructor() {
                    this.items = [];
                    this.initializeEventListeners();
                    this.fetchCartFromServer();
                }

                initializeEventListeners() {
                    // Add to cart buttons
                    document.addEventListener('click', async (e) => {
                        // Find the add-to-cart button (either the target or a parent)
                        const button = e.target.matches('.add-to-cart-btn, .add-to-cart-button') 
                            ? e.target 
                            : e.target.closest('.add-to-cart-btn, .add-to-cart-button');
                        
                        if (!button) return; // Not an add-to-cart button
                        
                        // Check if button is disabled (out of stock)
                        if (button.hasAttribute('disabled') || button.classList.contains('cursor-not-allowed')) {
                            console.log('Button is disabled, not adding to cart');
                            return;
                        }
                        
                        const productId = button.dataset.productId;
                        const quantity = parseInt(button.dataset.quantity || 1);
                        
                        console.log('Add to cart clicked:', { productId, quantity });
                        await this.addItem(productId, quantity);
                    });

                    // Remove from cart buttons
                    document.addEventListener('click', async (e) => {
                        // Find the remove button (either the target or a parent)
                        const removeButton = e.target.closest('.remove-from-cart-btn');
                        if (removeButton) {
                            const itemId = removeButton.dataset.productId;
                            console.log('Remove button clicked for item ID:', itemId);
                            
                            if (confirm('Are you sure you want to remove this item from your cart?')) {
                                await this.removeItem(itemId);
                            }
                        }
                    });

                    // Quantity update inputs
                    document.addEventListener('change', async (e) => {
                        if (e.target.matches('.cart-quantity-input')) {
                            const productId = e.target.dataset.productId;
                            const quantity = parseInt(e.target.value);
                            await this.updateQuantity(productId, quantity);
                        }
                    });

                    // Clear cart button
                    document.addEventListener('click', async (e) => {
                        if (e.target.matches('.clear-cart-btn')) {
                            await this.clearCart();
                        }
                    });
                }

                async fetchCartFromServer() {
                    try {
                        console.log('Fetching cart from server');
                        
                        // Use the correct base URL for the Laravel development server
                        const baseUrl = window.location.origin;
                        const cartUrl = `${baseUrl}/finaltrgovina/public/cart?json=true`;
                        
                        console.log('Cart URL:', cartUrl);
                        
                        const response = await fetch(cartUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin' // Include cookies to maintain session
                        });
                        
                        console.log('Cart fetch response status:', response.status);
                        
                        if (response.ok) {
                            try {
                                const data = await response.json();
                                console.log('Cart data received:', data);
                                this.items = data.items || [];
                                this.updateCartDisplay();
                            } catch (jsonError) {
                                console.error('Error parsing JSON from cart fetch:', jsonError);
                                this.items = [];
                                this.updateCartDisplay();
                            }
                        } else {
                            console.error('Failed to fetch cart from server, status:', response.status);
                            try {
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    const errorData = await response.json();
                                    console.error('Error response:', errorData);
                                } else {
                                    const text = await response.text();
                                    console.error('Non-JSON error response:', text.substring(0, 500) + '...');
                                }
                            } catch (e) {
                                console.error('Could not parse error response');
                            }
                            
                            // Reset items and update display
                            this.items = [];
                            this.updateCartDisplay();
                        }
                    } catch (error) {
                        console.error('Error fetching cart:', error);
                        this.items = [];
                        this.updateCartDisplay();
                    }
                }

                async addItem(productId, quantity) {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        
                        console.log('Add to cart clicked:', { productId, quantity });
                        
                        // Use the correct base URL for the Laravel development server
                        const baseUrl = window.location.origin;
                        
                        // Get the cart.add route from a data attribute or use the default path
                        const cartAddRoute = '/finaltrgovina/public/cart/add';
                        
                        // Ensure the route starts with a slash
                        const addUrl = `${baseUrl}${cartAddRoute.startsWith('/') ? cartAddRoute : '/' + cartAddRoute}`;
                        
                        console.log('Add URL:', addUrl);
                        console.log('Request data:', { product_id: productId, quantity: quantity });
                        
                        const response = await fetch(addUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin', // Include cookies to maintain session
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: quantity
                            })
                        });
                        
                        console.log('Response status:', response.status);
                        
                        if (response.ok) {
                            try {
                                const data = await response.json();
                                console.log('Response data:', data);
                                await this.fetchCartFromServer();
                                this.showNotification(data.message || 'Product added to cart successfully!');
                                window.openCart();
                            } catch (jsonError) {
                                console.error('Error parsing JSON response:', jsonError);
                                this.showNotification('Product added but encountered an error updating the cart display', 'error');
                                await this.fetchCartFromServer();
                                window.openCart();
                            }
                        } else {
                            try {
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    const data = await response.json();
                                    console.error('Error response:', data);
                                    this.showNotification(data.message || 'Failed to add product to cart', 'error');
                                } else {
                                    const text = await response.text();
                                    console.error('Non-JSON error response:', text.substring(0, 500) + '...');
                                    this.showNotification('Failed to add product to cart', 'error');
                                }
                            } catch (parseError) {
                                console.error('Error parsing error response:', parseError);
                                this.showNotification('Failed to add product to cart', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Error adding item to cart:', error);
                        this.showNotification('Error adding item to cart', 'error');
                    }
                }

                async removeItem(itemId) {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        
                        // Use the correct base URL for the Laravel development server
                        const baseUrl = window.location.origin;
                        const removeUrl = `${baseUrl}/finaltrgovina/public/cart/items/${itemId}`;
                        
                        console.log('Remove URL:', removeUrl);
                        
                        const response = await fetch(removeUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin' // Include cookies to maintain session
                        });
                        
                        console.log('Remove response status:', response.status);
                        
                        if (response.ok) {
                            const data = await response.json();
                            console.log('Remove response data:', data);
                            await this.fetchCartFromServer();
                            this.showNotification(data.message || 'Product removed from cart');
                        } else {
                            try {
                                const data = await response.json();
                                console.error('Error response:', data);
                                this.showNotification(data.message || 'Failed to remove product from cart', 'error');
                            } catch (parseError) {
                                console.error('Error parsing error response:', parseError);
                                this.showNotification('Failed to remove product from cart', 'error');
                            }
                            // Refresh the cart to restore the original state
                            await this.fetchCartFromServer();
                        }
                    } catch (error) {
                        console.error('Error removing item from cart:', error);
                        this.showNotification('Error removing item from cart', 'error');
                        // Refresh the cart to restore the original state
                        await this.fetchCartFromServer();
                    }
                }

                async updateQuantity(productId, quantity) {
                    try {
                        console.log('Updating quantity for product:', productId, 'to', quantity);
                        
                        // Validate quantity
                        if (quantity < 1) {
                            if (confirm('Are you sure you want to remove this item from your cart?')) {
                                await this.removeItem(productId);
                                return;
                            } else {
                                // Reset the input to 1 if user cancels
                                const input = document.querySelector(`.cart-quantity-input[data-product-id="${productId}"]`);
                                if (input) {
                                    input.value = 1;
                                }
                                return;
                            }
                        }
                        
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        
                        // Use the correct base URL for the Laravel development server
                        const baseUrl = window.location.origin;
                        const updateUrl = `${baseUrl}/finaltrgovina/public/cart/items/${productId}`;
                        
                        console.log('Update URL:', updateUrl);
                        console.log('Updating quantity to:', quantity);
                        
                        const response = await fetch(updateUrl, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin', // Include cookies to maintain session
                            body: JSON.stringify({
                                quantity: quantity
                            })
                        });
                        
                        console.log('Update response status:', response.status);
                        
                        if (response.ok) {
                            const data = await response.json();
                            console.log('Update response data:', data);
                            await this.fetchCartFromServer();
                            this.showNotification(data.message || 'Cart updated');
                        } else {
                            try {
                                const data = await response.json();
                                console.error('Error response:', data);
                                this.showNotification(data.message || 'Failed to update cart', 'error');
                            } catch (parseError) {
                                console.error('Error parsing error response:', parseError);
                                this.showNotification('Failed to update cart', 'error');
                            }
                            // Refresh the cart to restore the original state
                            await this.fetchCartFromServer();
                        }
                    } catch (error) {
                        console.error('Error updating cart:', error);
                        this.showNotification('Error updating cart', 'error');
                        // Refresh the cart to restore the original state
                        await this.fetchCartFromServer();
                    }
                }

                async clearCart() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').content;
                        
                        // Use the correct base URL for the Laravel development server
                        const baseUrl = window.location.origin;
                        const clearUrl = `${baseUrl}/finaltrgovina/public/cart/clear`;
                        
                        console.log('Clear URL:', clearUrl);
                        
                        const response = await fetch(clearUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin' // Include cookies to maintain session
                        });
                        
                        if (response.ok) {
                            await this.fetchCartFromServer();
                            this.showNotification('Cart cleared');
                        } else {
                            const data = await response.json();
                            this.showNotification(data.message || 'Failed to clear cart', 'error');
                        }
                    } catch (error) {
                        console.error('Error clearing cart:', error);
                        this.showNotification('Error clearing cart', 'error');
                    }
                }

                getTotal() {
                    try {
                        if (!this.items || !Array.isArray(this.items) || this.items.length === 0) {
                            return 0;
                        }
                        return this.items.reduce((total, item) => {
                            const price = parseFloat(item.price) || 0;
                            const quantity = parseInt(item.quantity) || 0;
                            return total + (price * quantity);
                        }, 0);
                    } catch (error) {
                        console.error('Error calculating total:', error);
                        return 0;
                    }
                }

                getTotalItems() {
                    try {
                        if (!this.items || !Array.isArray(this.items) || this.items.length === 0) {
                            return 0;
                        }
                        return this.items.reduce((total, item) => {
                            const quantity = parseInt(item.quantity) || 0;
                            return total + quantity;
                        }, 0);
                    } catch (error) {
                        console.error('Error calculating total items:', error);
                        return 0;
                    }
                }

                updateCartDisplay() {
                    console.log('Updating cart display with items:', this.items);
                    
                    // Update cart counter
                    const cartCounters = document.querySelectorAll('.cart-counter');
                    const totalItems = this.getTotalItems();
                    console.log('Total items:', totalItems);
                    
                    cartCounters.forEach(counter => {
                        counter.textContent = totalItems;
                    });

                    // Update cart items display
                    const cartItemsContainer = document.querySelector('.cart-items');
                    if (cartItemsContainer) {
                        const emptyCartMessage = document.querySelector('.empty-cart-message');
                        const cartContent = document.querySelector('.cart-content');
                        
                        if (!this.items || this.items.length === 0) {
                            console.log('Cart is empty, showing empty message');
                            if (emptyCartMessage) emptyCartMessage.classList.remove('hidden');
                            if (cartContent) cartContent.classList.add('hidden');
                        } else {
                            console.log('Cart has items, showing content');
                            if (emptyCartMessage) emptyCartMessage.classList.add('hidden');
                            if (cartContent) cartContent.classList.remove('hidden');
                            
                            try {
                                cartItemsContainer.innerHTML = this.items.map(item => {
                                    console.log('Rendering cart item:', item);
                                    
                                    // Check if the product is in stock
                                    const inStock = item.product && item.product.stock > 0;
                                    const maxQuantity = item.product ? item.product.stock : 0;
                                    
                                    // Disable the + button if the quantity is at the maximum stock level
                                    const disablePlus = !inStock || (item.quantity >= maxQuantity);
                                    
                                    return `
                                        <div class="cart-item flex items-center justify-between py-4">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-medium text-gray-900">${item.product ? item.product.name : 'Unknown Product'}</h3>
                                                <div class="flex items-center mt-2">
                                                    <div class="flex items-center border border-gray-300 rounded-md">
                                                        <button class="px-3 py-1 text-gray-600 hover:text-gray-800 focus:outline-none" 
                                                                onclick="window.cart.updateQuantity('${item.id}', ${item.quantity - 1})">
                                                            -
                                                        </button>
                                                        <span class="px-3 py-1 border-x border-gray-300">${item.quantity}</span>
                                                        <button class="px-3 py-1 text-gray-600 hover:text-gray-800 focus:outline-none ${disablePlus ? 'opacity-50 cursor-not-allowed' : ''}"
                                                                onclick="window.cart.updateQuantity('${item.id}', ${item.quantity + 1})"
                                                                ${disablePlus ? 'disabled' : ''}>
                                                            +
                                                        </button>
                                                    </div>
                                                    <span class="ml-4 text-gray-900">$${(item.price * item.quantity).toFixed(2)}</span>
                                                </div>
                                            </div>
                                            <button class="remove-from-cart-btn ml-4 text-red-600 hover:text-red-800" data-product-id="${item.id}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    `;
                                }).join('');
                            } catch (error) {
                                console.error('Error rendering cart items:', error);
                                cartItemsContainer.innerHTML = '<p class="text-red-500 p-4">Error displaying cart items</p>';
                            }
                        }
                    } else {
                        console.error('Cart items container not found');
                    }

                    // Update cart total
                    const cartTotal = document.querySelector('.cart-total');
                    if (cartTotal) {
                        try {
                            const total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                            console.log('Cart total:', total);
                            cartTotal.textContent = `$${total.toFixed(2)}`;
                        } catch (error) {
                            console.error('Error calculating cart total:', error);
                            cartTotal.textContent = '$0.00';
                        }
                    } else {
                        console.error('Cart total element not found');
                    }
                }

                showNotification(message, type = 'success') {
                    console.log('Showing notification:', message, 'type:', type);
                    
                    const toast = document.getElementById('notification-toast');
                    const messageElement = document.getElementById('notification-message');
                    
                    if (!toast || !messageElement) {
                        console.error('Notification elements not found');
                        return;
                    }
                    
                    messageElement.textContent = message;
                    
                    // Remove any existing classes and add the appropriate ones
                    toast.className = `fixed bottom-4 right-4 ${type === 'error' ? 'bg-red-500' : 'bg-green-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
                    
                    // Remove the transform class to show the toast
                    toast.classList.remove('translate-y-full');
                    
                    // Hide the toast after 3 seconds
                    setTimeout(() => {
                        toast.classList.add('translate-y-full');
                    }, 3000);
                }
            }

            // Initialize cart when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Cart sidebar functionality
                const cartSidebar = document.getElementById('cart-sidebar');
                const cartOverlay = document.getElementById('cart-overlay');
                const closeCart = document.getElementById('close-cart');
                
                // Initialize cart
                window.cart = new Cart();
                
                // Quantity controls for product page
                document.addEventListener('click', function(e) {
                    if (e.target.matches('.quantity-decrease')) {
                        const display = e.target.parentElement.querySelector('.quantity-display');
                        const addToCartBtn = e.target.closest('.product-form').querySelector('.add-to-cart-btn');
                        let quantity = parseInt(display.textContent);
                        if (quantity > 1) {
                            quantity--;
                            display.textContent = quantity;
                            addToCartBtn.dataset.quantity = quantity;
                        }
                    }
                    
                    if (e.target.matches('.quantity-increase')) {
                        const display = e.target.parentElement.querySelector('.quantity-display');
                        const addToCartBtn = e.target.closest('.product-form').querySelector('.add-to-cart-btn');
                        let quantity = parseInt(display.textContent);
                        quantity++;
                        display.textContent = quantity;
                        addToCartBtn.dataset.quantity = quantity;
                    }
                });
                
                window.openCart = function() {
                    cartSidebar.classList.remove('translate-x-full');
                    cartOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
                
                window.closeCart = function() {
                    cartSidebar.classList.add('translate-x-full');
                    cartOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                
                closeCart.addEventListener('click', window.closeCart);
                cartOverlay.addEventListener('click', window.closeCart);
            });

            // Logout with cart clearing
            document.addEventListener('DOMContentLoaded', function() {
                // Handle desktop logout form
                const desktopLogoutForm = document.getElementById('desktop-logout-form');
                if (desktopLogoutForm) {
                    desktopLogoutForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        try {
                            // Clear the cart first
                            if (window.cart) {
                                await window.cart.clearCart();
                            }
                        } catch (error) {
                            console.error('Error clearing cart during logout:', error);
                        }
                        
                        // Continue with form submission
                        this.submit();
                    });
                }
                
                // Handle mobile logout form
                const mobileLogoutForm = document.getElementById('mobile-logout-form');
                if (mobileLogoutForm) {
                    mobileLogoutForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        try {
                            // Clear the cart first
                            if (window.cart) {
                                await window.cart.clearCart();
                            }
                        } catch (error) {
                            console.error('Error clearing cart during logout:', error);
                        }
                        
                        // Continue with form submission
                        this.submit();
                    });
                }
            });
        </script>
    </body>
</html> 