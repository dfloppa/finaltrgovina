<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $featuredProducts = Product::active()->featured()->with('category')->latest()->take(4)->get();
        $newArrivals = Product::active()->with('category')->latest()->take(4)->get();
        $categories = Category::active()->take(6)->get();
        
        return view('shop.home', compact('featuredProducts', 'newArrivals', 'categories'));
    }
}
