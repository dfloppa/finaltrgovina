<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if shipping methods already exist
        if (ShippingMethod::count() > 0) {
            $this->command->info('Shipping methods already exist. Skipping...');
            return;
        }
        
        // Temporarily disable foreign key constraints
        Schema::disableForeignKeyConstraints();
        
        // Create shipping methods
        ShippingMethod::create([
            'name' => 'Standard Shipping',
            'code' => 'standard',
            'description' => 'Delivery in 3-5 business days',
            'price' => 5.99,
            'delivery_time' => '3-5 business days',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        
        ShippingMethod::create([
            'name' => 'Express Shipping',
            'code' => 'express',
            'description' => 'Delivery in 1-2 business days',
            'price' => 12.99,
            'delivery_time' => '1-2 business days',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        
        ShippingMethod::create([
            'name' => 'Next Day Delivery',
            'code' => 'next_day',
            'description' => 'Delivery on the next business day',
            'price' => 19.99,
            'delivery_time' => 'Next business day',
            'is_active' => true,
            'sort_order' => 3,
        ]);
        
        ShippingMethod::create([
            'name' => 'Free Shipping',
            'code' => 'free',
            'description' => 'Free shipping for orders over $100',
            'price' => 0.00,
            'delivery_time' => '5-7 business days',
            'is_active' => true,
            'sort_order' => 4,
        ]);
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }
}
