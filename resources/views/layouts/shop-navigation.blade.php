<?php
$categories = \App\Models\Category::where('is_active', true)->take(6)->get();
?>
<nav class="bg-primary">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-bold text-xl">{{ config('app.name', 'E-Shop') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white hover:text-gray-100 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'border-b-2 border-white' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('shop') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white hover:text-gray-100 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('shop') ? 'border-b-2 border-white' : '' }}">
                        Shop
                    </a>
                    <div class="relative">
                        <button id="categoriesButton" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white hover:text-gray-100 focus:outline-none focus:text-white transition duration-150 ease-in-out">
                            Categories
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="categoriesDropdown" class="hidden absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                @foreach($categories as $category)
                                <a href="{{ route('categories.show', $category->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $category->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search, Cart, and User -->
            <div class="hidden sm:flex sm:items-center">
                <!-- Search Button -->
                <button id="searchButton" class="p-2 rounded-full hover:bg-[#099A95] transition-colors mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Cart Button -->
                <button id="cartButton" class="p-2 rounded-full hover:bg-[#099A95] transition-colors mr-2 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="cart-counter absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </button>

                <!-- User Menu -->
                @auth
                <div class="ml-3 relative">
                    <button id="userMenuButton" class="flex items-center text-sm font-medium text-white hover:text-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="userMenuDropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                            <form method="POST" action="{{ route('logout') }}" id="desktop-logout-form">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="text-white hover:text-gray-100 transition-colors">Login</a>
                    <span class="text-white">|</span>
                    <a href="{{ route('register') }}" class="text-white hover:text-gray-100 transition-colors">Register</a>
                </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button id="cartButtonMobile" class="p-2 rounded-full hover:bg-[#099A95] transition-colors mr-2 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="cart-counter absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </button>
                
                <button id="mobileMenuButton" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-[#099A95] focus:outline-none transition duration-150 ease-in-out">
                    <svg id="menuOpenIcon" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="menuCloseIcon" class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'bg-[#099A95]' : '' }}">
                Home
            </a>
            <a href="{{ route('shop') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out {{ request()->routeIs('shop') ? 'bg-[#099A95]' : '' }}">
                Shop
            </a>
            <div>
                <button id="mobileCategoryButton" class="w-full text-left block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out">
                    Categories
                    <svg class="inline-block ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="mobileCategoryDropdown" class="hidden pl-6 pr-4 py-2 space-y-1">
                    @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="block py-1 text-sm text-white hover:text-gray-200">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mobile User Menu -->
        <div class="pt-4 pb-3 border-t border-[#099A95]">
            @auth
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-200">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out">
                    Profile
                </a>
                <a href="{{ route('orders.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out">
                    My Orders
                </a>
                <form method="POST" action="{{ route('logout') }}" id="mobile-logout-form">
                    @csrf
                    <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out">
                        Log Out
                    </button>
                </form>
            </div>
            @else
            <div class="mt-3 space-y-1">
                <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-[#099A95] transition duration-150 ease-in-out">
                    Register
                </a>
            </div>
            @endauth
        </div>
    </div>

    <!-- Search Overlay -->
    <div id="searchOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Search Products</h2>
                <button id="closeSearchButton" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('shop') }}" method="GET" class="mt-4">
                <div class="flex">
                    <input type="text" name="search" placeholder="Search for products..." class="flex-1 border-gray-300 focus:border-[#0ABAB5] focus:ring focus:ring-[#0ABAB5] focus:ring-opacity-50 rounded-l-md shadow-sm">
                    <button type="submit" class="bg-[#0ABAB5] text-white px-4 py-2 rounded-r-md hover:bg-[#099A95] transition-colors">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-xl z-50 overflow-y-auto transform translate-x-full transition-transform duration-300">
        <div class="p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Your Cart</h2>
                <button id="close-cart" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
            <div class="cart-content">
                <div class="cart-items divide-y divide-gray-200">
                    <!-- Cart items will be inserted here by JavaScript -->
                </div>

                <!-- Cart Summary -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-primary cart-total">$0.00</span>
                    </div>

                    <div class="mt-6">
                        <button type="button" id="checkout-btn" class="w-full bg-primary text-white py-3 px-4 rounded-md hover:bg-primary-dark transition duration-200 flex items-center justify-center">
                            <a href="{{ route('checkout.index') }}" class="text-white">
                                Proceed to Checkout
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cart Overlay -->
    <div id="cart-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menu buttons
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOpenIcon = document.getElementById('menuOpenIcon');
    const menuCloseIcon = document.getElementById('menuCloseIcon');
    
    // Categories buttons
    const categoriesButton = document.getElementById('categoriesButton');
    const categoriesDropdown = document.getElementById('categoriesDropdown');
    const mobileCategoryButton = document.getElementById('mobileCategoryButton');
    const mobileCategoryDropdown = document.getElementById('mobileCategoryDropdown');
    
    // Search elements
    const searchButton = document.getElementById('searchButton');
    const searchOverlay = document.getElementById('searchOverlay');
    const closeSearchButton = document.getElementById('closeSearchButton');
    
    // User menu elements
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenuDropdown = document.getElementById('userMenuDropdown');
    
    // Cart buttons
    const cartButton = document.getElementById('cartButton');
    const cartButtonMobile = document.getElementById('cartButtonMobile');
    
    // Mobile menu toggle
    mobileMenuButton?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        menuOpenIcon.classList.toggle('hidden');
        menuCloseIcon.classList.toggle('hidden');
    });
    
    // Categories dropdown toggle
    categoriesButton?.addEventListener('click', () => {
        categoriesDropdown.classList.toggle('hidden');
    });
    
    // Mobile categories dropdown toggle
    mobileCategoryButton?.addEventListener('click', () => {
        mobileCategoryDropdown.classList.toggle('hidden');
    });
    
    // Search overlay toggle
    searchButton?.addEventListener('click', () => {
        searchOverlay.classList.remove('hidden');
    });
    
    closeSearchButton?.addEventListener('click', () => {
        searchOverlay.classList.add('hidden');
    });
    
    // User menu toggle
    userMenuButton?.addEventListener('click', () => {
        userMenuDropdown.classList.toggle('hidden');
    });
    
    // Cart toggle
    const openCart = () => {
        if (typeof window.openCart === 'function') {
            window.openCart();
        }
    };
    
    cartButton?.addEventListener('click', openCart);
    cartButtonMobile?.addEventListener('click', openCart);
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (event) => {
        if (categoriesButton && !categoriesButton.contains(event.target) && !categoriesDropdown.contains(event.target)) {
            categoriesDropdown.classList.add('hidden');
        }
        
        if (userMenuButton && !userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
            userMenuDropdown.classList.add('hidden');
        }
        
        if (searchOverlay && !searchOverlay.contains(event.target) && event.target !== searchButton) {
            searchOverlay.classList.add('hidden');
        }
    });
});
</script> 