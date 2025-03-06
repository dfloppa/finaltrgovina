document.addEventListener('DOMContentLoaded', function() {
    console.log('Filters script loaded');
    
    function initializeFilters() {
        // Get filter elements
        const searchInput = document.querySelector('input[wire\\:model\\.live="search"]');
        const categorySelect = document.querySelector('select[wire\\:model\\.live="categoryFilter"]');
        const priceMinInput = document.querySelector('input[wire\\:model\\.live="priceMin"]');
        const priceMaxInput = document.querySelector('input[wire\\:model\\.live="priceMax"]');
        const resetButton = document.querySelector('button[wire\\:click="resetFilters"]');
        
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
                // The Livewire component will handle the actual filtering
            });
        }
        
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                console.log('Category changed:', this.value);
                // The Livewire component will handle the actual filtering
            });
        }
        
        if (priceMinInput) {
            priceMinInput.addEventListener('input', function() {
                console.log('Min price changed:', this.value);
                // The Livewire component will handle the actual filtering
            });
        }
        
        if (priceMaxInput) {
            priceMaxInput.addEventListener('input', function() {
                console.log('Max price changed:', this.value);
                // The Livewire component will handle the actual filtering
            });
        }
        
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                console.log('Reset filters clicked');
                // The Livewire component will handle the actual reset
            });
        }
    }
    
    // Initialize filters on page load
    initializeFilters();
    
    // Also initialize filters after Livewire updates
    document.addEventListener('livewire:initialized', function() {
        console.log('Livewire initialized, reinitializing filters');
        initializeFilters();
    });
    
    document.addEventListener('livewire:update', function() {
        console.log('Livewire updated, reinitializing filters');
        initializeFilters();
    });
}); 