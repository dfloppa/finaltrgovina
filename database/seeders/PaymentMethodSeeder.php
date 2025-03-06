<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing payment methods
        PaymentMethod::truncate();
        
        // Create payment methods
        PaymentMethod::create([
            'name' => 'Credit Card',
            'code' => 'credit_card',
            'description' => 'Pay with your credit card (Visa, MasterCard, American Express)',
            'icon' => 'fa-credit-card',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        
        PaymentMethod::create([
            'name' => 'PayPal',
            'code' => 'paypal',
            'description' => 'Pay with your PayPal account',
            'icon' => 'fa-paypal',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        
        PaymentMethod::create([
            'name' => 'Cash on Delivery',
            'code' => 'cod',
            'description' => 'Pay with cash when your order is delivered',
            'icon' => 'fa-money-bill',
            'is_active' => true,
            'sort_order' => 3,
        ]);
        
        PaymentMethod::create([
            'name' => 'Bank Transfer',
            'code' => 'bank_transfer',
            'description' => 'Pay directly to our bank account',
            'icon' => 'fa-university',
            'is_active' => true,
            'sort_order' => 4,
        ]);
    }
}
