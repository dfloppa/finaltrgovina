<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample category
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and accessories',
        ]);

        // Create a sample product
        Product::create([
            'category_id' => $category->id,
            'name' => 'Samsung Galaxy S21',
            'slug' => 'samsung-galaxy-s21',
            'description' => 'The latest Samsung flagship smartphone with amazing camera capabilities.',
            'price' => 899.99,
            'sale_price' => 799.99,
            'stock' => 10,
            'sku' => 'SGS21-' . Str::random(6),
            'is_featured' => true,
            'is_active' => true,
            'image' => 'products/galaxy-s21.jpg',
            'thumbnail' => 'products/galaxy-s21-thumb.jpg',
        ]);
    }
}
