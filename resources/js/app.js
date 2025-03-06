import './bootstrap';

// Cart Functionality
class Cart {
    constructor() {
        this.items = [];
        this.initializeEventListeners();
        this.updateCartDisplay();
        console.log('Cart initialized');
        
        // Set up logout handlers
        this.setupLogoutHandlers();
    }

    initializeEventListeners() {
        // Add to cart buttons
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.add-to-cart-btn')) {
                const productId = e.target.dataset.productId;
                const quantity = parseInt(e.target.closest('.product-form')?.querySelector('.quantity-input')?.value || 1);
                
                console.log('Adding to cart:', { productId, quantity });
                await this.addItem(productId, quantity);
            }
        });

        // Remove from cart buttons
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.remove-from-cart-btn')) {
                const productId = e.target.dataset.productId;
                console.log('Removing from cart:', { productId });
                await this.removeItem(productId);
            }
        });

        // Quantity update inputs
        document.addEventListener('change', async (e) => {
            if (e.target.matches('.cart-quantity-input')) {
                const productId = e.target.dataset.productId;
                const quantity = parseInt(e.target.value);
                console.log('Updating quantity:', { productId, quantity });
                await this.updateQuantity(productId, quantity);
            }
        });

        // Clear cart button
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.clear-cart-btn')) {
                console.log('Clearing cart');
                await this.clearCart();
            }
        });

        console.log('Cart event listeners initialized');
    }

    async addItem(productId, quantity) {
        try {
            console.log('Making add to cart request:', { productId, quantity });
            
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!token) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            console.log('Add to cart response status:', response.status);
            const data = await response.json();
            console.log('Add to cart response data:', data);
            
            if (response.ok) {
                await this.updateCartDisplay();
                this.showNotification(data.message, 'success');
                if (typeof window.openCart === 'function') {
                    window.openCart();
                }
            } else {
                throw new Error(data.message || 'Failed to add item to cart');
            }
        } catch (error) {
            console.error('Error adding item to cart:', error);
            this.showNotification(error.message, 'error');
        }
    }

    async removeItem(productId) {
        try {
            console.log('Making remove from cart request:', { productId });
            
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!token) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch(`/cart/items/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });

            console.log('Remove from cart response status:', response.status);
            
            if (response.ok) {
                await this.updateCartDisplay();
                this.showNotification('Item removed from cart', 'success');
            } else {
                const data = await response.json();
                throw new Error(data.message || 'Failed to remove item from cart');
            }
        } catch (error) {
            console.error('Error removing item from cart:', error);
            this.showNotification(error.message, 'error');
        }
    }

    async updateQuantity(productId, quantity) {
        try {
            console.log('Making update quantity request:', { productId, quantity });
            
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!token) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch(`/cart/items/${productId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity })
            });

            console.log('Update quantity response status:', response.status);
            
            if (response.ok) {
                await this.updateCartDisplay();
                this.showNotification('Cart updated', 'success');
            } else {
                const data = await response.json();
                throw new Error(data.message || 'Failed to update quantity');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            this.showNotification(error.message, 'error');
        }
    }

    async clearCart() {
        try {
            console.log('Making clear cart request');
            
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!token) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });

            console.log('Clear cart response status:', response.status);
            
            if (response.ok) {
                await this.updateCartDisplay();
                this.showNotification('Cart cleared', 'success');
            } else {
                const data = await response.json();
                throw new Error(data.message || 'Failed to clear cart');
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showNotification(error.message, 'error');
        }
    }

    async updateCartDisplay() {
        try {
            console.log('Fetching cart data');
            
            const response = await fetch('/cart', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            console.log('Cart data response status:', response.status);

            if (!response.ok) {
                throw new Error('Failed to fetch cart data');
            }

            const data = await response.json();
            console.log('Cart data:', data);
            
            // Update cart counter
            const cartCounter = document.querySelector('.cart-counter');
            if (cartCounter) {
                cartCounter.textContent = data.total_items;
                console.log('Updated cart counter:', data.total_items);
            }

            // Update cart items display
            const cartItemsContainer = document.querySelector('.cart-items');
            if (cartItemsContainer && data.items) {
                cartItemsContainer.innerHTML = data.items.map(item => `
                    <div class="cart-item flex items-center justify-between p-4 border-b">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium">${item.product.name}</h3>
                            <div class="flex items-center mt-2">
                                <input type="number" 
                                       class="cart-quantity-input w-20 px-2 py-1 border rounded" 
                                       value="${item.quantity}" 
                                       min="1" 
                                       data-product-id="${item.product_id}">
                                <span class="ml-4">$${(item.price * item.quantity).toFixed(2)}</span>
                            </div>
                        </div>
                        <button class="remove-from-cart-btn ml-4 text-red-600" data-product-id="${item.product_id}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `).join('');
                console.log('Updated cart items display');
            }

            // Update cart total
            const cartTotal = document.querySelector('.cart-total');
            if (cartTotal) {
                cartTotal.textContent = `$${data.total_price.toFixed(2)}`;
                console.log('Updated cart total:', data.total_price);
            }

            // Toggle empty cart message
            const emptyCartMessage = document.querySelector('.empty-cart-message');
            const cartContent = document.querySelector('.cart-content');
            if (emptyCartMessage && cartContent) {
                const isEmpty = !data.items || data.items.length === 0;
                emptyCartMessage.classList.toggle('hidden', !isEmpty);
                cartContent.classList.toggle('hidden', isEmpty);
                console.log('Updated cart empty state:', isEmpty);
            }
        } catch (error) {
            console.error('Error updating cart display:', error);
            this.showNotification('Error updating cart display', 'error');
        }
    }

    showNotification(message, type = 'success') {
        console.log('Showing notification:', { message, type });
        
        const toast = document.getElementById('notification-toast');
        const messageElement = document.getElementById('notification-message');
        
        if (toast && messageElement) {
            messageElement.textContent = message;
            toast.classList.remove('translate-y-full');
            
            setTimeout(() => {
                toast.classList.add('translate-y-full');
            }, 3000);
        } else {
            console.warn('Notification elements not found');
        }
    }

    /**
     * Set up handlers for logout forms to clear cart
     */
    setupLogoutHandlers() {
        console.log('Setting up logout handlers');
        
        document.addEventListener('DOMContentLoaded', () => {
            // Find all logout forms
            const logoutForms = document.querySelectorAll('form[action*="logout"]');
            
            logoutForms.forEach(form => {
                console.log('Found logout form:', form);
                
                form.addEventListener('submit', async (e) => {
                    console.log('Logout form submitted, clearing cart');
                    
                    // Only prevent default if we need to clear the cart
                    if (this.items.length > 0) {
                        e.preventDefault();
                        
                        try {
                            // Clear the cart
                            await this.clearCart();
                            console.log('Cart cleared on logout');
                        } catch (error) {
                            console.error('Error clearing cart during logout:', error);
                        } finally {
                            // Continue with form submission
                            form.submit();
                        }
                    }
                });
            });
        });
    }
}

// Initialize cart globally
window.cart = new Cart();
