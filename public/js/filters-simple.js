document.addEventListener('DOMContentLoaded', function() {
    console.log('Simple filters script loaded');
    
    // Store original querySelector
    const originalQuerySelector = document.querySelector;
    
    // Add a simple contains selector
    function findElementContainingText(text) {
        const elements = document.getElementsByTagName('button');
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].textContent.includes('Reset Filters')) {
                return elements[i];
            }
        }
        return null;
    }
    
    function initializeFilters() {
        // Get filter elements using more generic selectors
        const searchInput = document.querySelector('input[placeholder="Search products..."]');
        const categorySelect = document.querySelector('select#category, select[id*="category"]');
        const priceMinInput = document.querySelector('input[placeholder="Min"]');
        const priceMaxInput = document.querySelector('input[placeholder="Max"]');
        const resetButton = findElementContainingText('Reset Filters') || document.querySelector('button[type="button"]');
        
        // Log elements to verify they're found
        console.log('Search input:', searchInput);
        console.log('Category select:', categorySelect);
        console.log('Price min input:', priceMinInput);
        console.log('Price max input:', priceMaxInput);
        console.log('Reset button:', resetButton);
        
        // Add event listeners
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                console.log('Search input changed:', this.value);
            });
        }
        
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                console.log('Category changed:', this.value);
            });
        }
        
        if (priceMinInput) {
            priceMinInput.addEventListener('input', function() {
                console.log('Min price changed:', this.value);
            });
        }
        
        if (priceMaxInput) {
            priceMaxInput.addEventListener('input', function() {
                console.log('Max price changed:', this.value);
            });
        }
        
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                console.log('Reset filters clicked');
            });
        }
    }
    
    // Initialize filters on page load
    initializeFilters();
    
    // Add a more generic selector method
    document.querySelectorAll = document.querySelectorAll || function(selector) {
        return document.querySelector(selector);
    };
    
    // Add a contains selector for buttons
    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
    }
    
    if (typeof jQuery === 'undefined') {
        // Add a simple contains selector if jQuery is not available
        document.querySelector = document.querySelector || function(selector) {
            if (selector.includes(':contains(')) {
                const text = selector.match(/:contains\("(.+?)"\)/)[1];
                const elements = document.getElementsByTagName('*');
                for (let i = 0; i < elements.length; i++) {
                    if (elements[i].textContent.includes(text)) {
                        return elements[i];
                    }
                }
                return null;
            }
            return document._querySelector(selector);
        };
    }
}); 