@extends('layouts.shop')

@section('title', 'Shop')

@section('content')
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar / Filters -->
            <div class="w-full md:w-1/4 pr-0 md:pr-8 mb-8 md:mb-0">
                <div class="bg-gray-50 rounded-lg p-6 sticky top-24">
                    <h3 class="text-lg font-semibold mb-4">Filters</h3>
                    
                    <livewire:product-filters />
                </div>
            </div>
            
            <!-- Product Grid -->
            <div class="w-full md:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">All Products</h1>
                    
                    <div class="flex items-center">
                        <livewire:product-sort />
                    </div>
                </div>
                
                <livewire:product-list />
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Inline filters script loaded');
        
        // Find all filter elements
        const searchInputs = document.querySelectorAll('input[placeholder="Search products..."]');
        const categorySelects = document.querySelectorAll('select');
        const priceInputs = document.querySelectorAll('input[type="number"]');
        const resetButtons = document.querySelectorAll('button');
        
        console.log('Search inputs found:', searchInputs.length);
        console.log('Category selects found:', categorySelects.length);
        console.log('Price inputs found:', priceInputs.length);
        console.log('Reset buttons found:', resetButtons.length);
        
        // Add event listeners to all potential filter elements
        searchInputs.forEach(input => {
            input.addEventListener('input', function() {
                console.log('Search input changed:', this.value);
            });
        });
        
        categorySelects.forEach(select => {
            select.addEventListener('change', function() {
                console.log('Select changed:', this.value);
            });
        });
        
        priceInputs.forEach(input => {
            input.addEventListener('input', function() {
                console.log('Price input changed:', this.value);
            });
        });
        
        resetButtons.forEach(button => {
            if (button.textContent.includes('Reset')) {
                button.addEventListener('click', function() {
                    console.log('Reset button clicked');
                });
            }
        });
    });
</script>
@endsection 