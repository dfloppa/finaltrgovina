<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class ProductFilters extends Component
{
    public $search = '';
    public $categoryFilter = '';
    public $priceMin = '';
    public $priceMax = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'priceMin' => ['except' => ''],
        'priceMax' => ['except' => ''],
    ];
    
    public function mount()
    {
        $this->resetFilters();
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->priceMin = '';
        $this->priceMax = '';
        
        $this->dispatch('filters-updated', [
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
        ]);
    }
    
    public function updatedSearch()
    {
        $this->dispatch('filters-updated', [
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
        ]);
    }
    
    public function updatedCategoryFilter()
    {
        // Log the selected category for debugging
        \Illuminate\Support\Facades\Log::info('Category filter updated: ' . $this->categoryFilter);
        
        $this->dispatch('filters-updated', [
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
        ]);
    }
    
    public function updatedPriceMin()
    {
        $this->dispatch('filters-updated', [
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
        ]);
    }
    
    public function updatedPriceMax()
    {
        $this->dispatch('filters-updated', [
            'search' => $this->search,
            'categoryFilter' => $this->categoryFilter,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
        ]);
    }
    
    public function render()
    {
        $categories = Category::active()->get();
        
        return view('livewire.product-filters', [
            'categories' => $categories,
        ]);
    }
}
