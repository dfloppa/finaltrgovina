<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $sortBy = 'newest';
    public $priceMin;
    public $priceMax;
    public $perPage = 12;
    public $addingToCart = false;
    public $productQuantity = 1;
    public $selectedProduct = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
        'priceMin' => ['except' => ''],
        'priceMax' => ['except' => ''],
    ];

    protected $listeners = [
        'filters-updated' => 'applyFilters',
        'sort-updated' => 'updateSort'
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function applyFilters($filters)
    {
        // Log the received filters for debugging
        \Illuminate\Support\Facades\Log::info('Filters received: ', $filters);
        
        $this->search = $filters['search'];
        $this->categoryFilter = $filters['categoryFilter'];
        $this->priceMin = $filters['priceMin'];
        $this->priceMax = $filters['priceMax'];
        $this->resetPage();
    }

    public function updateSort($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedPriceMin()
    {
        $this->resetPage();
    }

    public function updatedPriceMax()
    {
        $this->resetPage();
    }

    public function prepareToAddToCart($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->productQuantity = 1;
        $this->addingToCart = true;
    }

    public function addToCart()
    {
        if (!$this->selectedProduct) {
            return;
        }

        try {
            // Get cart using CartController's method
            $cartController = app(\App\Http\Controllers\CartController::class);
            $cart = $cartController->getCart();

            // Add product to cart
            $cart->addProduct($this->selectedProduct, $this->productQuantity);

            // Reset state
            $this->addingToCart = false;
            $this->selectedProduct = null;
            $this->productQuantity = 1;

            // Show success message
            session()->flash('success', 'Product added to cart successfully!');
            $this->dispatch('cartUpdated');
        } catch (\Exception $e) {
            Log::error('Error adding product to cart: ' . $e->getMessage());
            session()->flash('error', 'Error adding product to cart. Please try again.');
        }
    }

    public function cancelAddToCart()
    {
        $this->addingToCart = false;
        $this->selectedProduct = null;
        $this->productQuantity = 1;
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();

        $productsQuery = Product::query()->where('is_active', true);

        // Apply search filter
        if ($this->search) {
            $productsQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->categoryFilter) {
            // Log the category filter being applied
            \Illuminate\Support\Facades\Log::info('Applying category filter: ' . $this->categoryFilter);
            
            $productsQuery->where('category_id', $this->categoryFilter);
        }

        // Apply price filters
        if ($this->priceMin) {
            $productsQuery->where(function ($query) {
                $query->where('price', '>=', $this->priceMin)
                    ->orWhere('sale_price', '>=', $this->priceMin);
            });
        }

        if ($this->priceMax) {
            $productsQuery->where(function ($query) {
                $query->where('price', '<=', $this->priceMax)
                    ->orWhere('sale_price', '<=', $this->priceMax);
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_low_high':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name_a_z':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_z_a':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery->with('category')->paginate($this->perPage);

        // Log the number of products found
        \Illuminate\Support\Facades\Log::info('Products found: ' . $products->total());

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
