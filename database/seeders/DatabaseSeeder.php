<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        Schema::disableForeignKeyConstraints();
        
        // Create a test user if none exists
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
        
        // Call seeders in the correct order
        $this->call([
            PaymentMethodSeeder::class,
            ShippingMethodSeeder::class,
            ComputerComponentsSeeder::class,
        ]);
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
