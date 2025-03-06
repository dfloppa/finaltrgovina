<?php

namespace App\Livewire;

use Livewire\Component;

class ProductSort extends Component
{
    public $sortBy = 'newest';
    
    protected $queryString = [
        'sortBy' => ['except' => 'newest'],
    ];
    
    public function updatedSortBy()
    {
        $this->dispatch('sort-updated', $this->sortBy);
    }
    
    public function render()
    {
        return view('livewire.product-sort');
    }
} 