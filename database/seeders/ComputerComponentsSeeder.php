<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ComputerComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if categories already exist
        if (Category::where('slug', 'computer-components')->exists()) {
            $this->command->info('Computer components category already exists. Skipping category creation...');
            $mainCategory = Category::where('slug', 'computer-components')->first();
        } else {
            // Create main Computer Components category
            $mainCategory = Category::create([
                'name' => 'Computer Components',
                'slug' => 'computer-components',
                'description' => 'Hardware components for building and upgrading computers',
                'is_active' => true,
            ]);
        }

        // Create subcategories if they don't exist
        if (!Category::where('slug', 'processors-cpu')->exists()) {
            $cpuCategory = Category::create([
                'name' => 'Processors (CPU)',
                'slug' => 'processors-cpu',
                'description' => 'Central Processing Units from leading manufacturers',
                'parent_id' => $mainCategory->id,
                'is_active' => true,
            ]);
        } else {
            $cpuCategory = Category::where('slug', 'processors-cpu')->first();
        }

        if (!Category::where('slug', 'memory-ram')->exists()) {
            $ramCategory = Category::create([
                'name' => 'Memory (RAM)',
                'slug' => 'memory-ram',
                'description' => 'Random Access Memory modules for system memory',
                'parent_id' => $mainCategory->id,
                'is_active' => true,
            ]);
        } else {
            $ramCategory = Category::where('slug', 'memory-ram')->first();
        }

        if (!Category::where('slug', 'graphics-cards')->exists()) {
            $gpuCategory = Category::create([
                'name' => 'Graphics Cards',
                'slug' => 'graphics-cards',
                'description' => 'Video cards for gaming and professional graphics work',
                'parent_id' => $mainCategory->id,
                'is_active' => true,
            ]);
        } else {
            $gpuCategory = Category::where('slug', 'graphics-cards')->first();
        }

        if (!Category::where('slug', 'storage')->exists()) {
            $storageCategory = Category::create([
                'name' => 'Storage',
                'slug' => 'storage',
                'description' => 'Hard drives, SSDs, and other storage solutions',
                'parent_id' => $mainCategory->id,
                'is_active' => true,
            ]);
        } else {
            $storageCategory = Category::where('slug', 'storage')->first();
        }

        if (!Category::where('slug', 'motherboards')->exists()) {
            $motherboardCategory = Category::create([
                'name' => 'Motherboards',
                'slug' => 'motherboards',
                'description' => 'Motherboards for various CPU sockets and form factors',
                'parent_id' => $mainCategory->id,
                'is_active' => true,
            ]);
        } else {
            $motherboardCategory = Category::where('slug', 'motherboards')->first();
        }

        // Check if products already exist
        if (Product::count() > 0) {
            $this->command->info('Products already exist. Skipping product creation...');
            return;
        }

        // Create products for each category
        $this->createCPUProducts($cpuCategory);
        $this->createRAMProducts($ramCategory);
        $this->createGPUProducts($gpuCategory);
        $this->createStorageProducts($storageCategory);
        $this->createMotherboardProducts($motherboardCategory);
    }

    private function createCPUProducts($category)
    {
        $cpus = [
            [
                'name' => 'AMD Ryzen 9 7950X',
                'description' => 'AMD Ryzen 9 7950X Desktop Processor 16 cores 32 Threads 80MB Cache 4.5 GHz Max Boost',
                'price' => 699.99,
                'sale_price' => 649.99,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'name' => 'Intel Core i9-13900K',
                'description' => 'Intel Core i9-13900K Desktop Processor 24 cores (8 P-cores + 16 E-cores) 36MB Cache, up to 5.8 GHz',
                'price' => 589.99,
                'sale_price' => null,
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'name' => 'AMD Ryzen 7 7700X',
                'description' => 'AMD Ryzen 7 7700X Desktop Processor 8 cores 16 Threads 40MB Cache 4.5 GHz Max Boost',
                'price' => 399.99,
                'sale_price' => 349.99,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'name' => 'Intel Core i5-13600K',
                'description' => 'Intel Core i5-13600K Desktop Processor 14 cores (6 P-cores + 8 E-cores) 24MB Cache, up to 5.1 GHz',
                'price' => 319.99,
                'sale_price' => 299.99,
                'stock' => 30,
                'is_featured' => false,
            ],
        ];

        foreach ($cpus as $cpu) {
            Product::create([
                'category_id' => $category->id,
                'name' => $cpu['name'],
                'slug' => Str::slug($cpu['name']),
                'description' => $cpu['description'],
                'price' => $cpu['price'],
                'sale_price' => $cpu['sale_price'],
                'stock' => $cpu['stock'],
                'sku' => 'CPU-' . Str::random(6),
                'is_featured' => $cpu['is_featured'],
                'is_active' => true,
            ]);
        }
    }

    private function createRAMProducts($category)
    {
        $rams = [
            [
                'name' => 'Corsair Vengeance RGB Pro 32GB (2x16GB) DDR4 3600',
                'description' => 'Corsair Vengeance RGB Pro 32GB (2x16GB) DDR4 3600MHz C18 LED Desktop Memory - Black',
                'price' => 129.99,
                'sale_price' => 109.99,
                'stock' => 40,
                'is_featured' => true,
            ],
            [
                'name' => 'G.SKILL Trident Z5 RGB 32GB (2x16GB) DDR5 6000',
                'description' => 'G.SKILL Trident Z5 RGB Series 32GB (2x16GB) DDR5 6000MHz CL36 Desktop Memory',
                'price' => 189.99,
                'sale_price' => null,
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'name' => 'Crucial 16GB (2x8GB) DDR4 3200',
                'description' => 'Crucial 16GB Kit (2x8GB) DDR4 3200MHz CL22 Desktop Memory',
                'price' => 59.99,
                'sale_price' => 49.99,
                'stock' => 50,
                'is_featured' => false,
            ],
            [
                'name' => 'Kingston FURY Beast 64GB (2x32GB) DDR5 5200',
                'description' => 'Kingston FURY Beast 64GB (2x32GB) DDR5 5200MHz CL40 Desktop Memory Kit',
                'price' => 249.99,
                'sale_price' => 229.99,
                'stock' => 15,
                'is_featured' => false,
            ],
        ];

        foreach ($rams as $ram) {
            Product::create([
                'category_id' => $category->id,
                'name' => $ram['name'],
                'slug' => Str::slug($ram['name']),
                'description' => $ram['description'],
                'price' => $ram['price'],
                'sale_price' => $ram['sale_price'],
                'stock' => $ram['stock'],
                'sku' => 'RAM-' . Str::random(6),
                'is_featured' => $ram['is_featured'],
                'is_active' => true,
            ]);
        }
    }

    private function createGPUProducts($category)
    {
        $gpus = [
            [
                'name' => 'NVIDIA GeForce RTX 4090',
                'description' => 'NVIDIA GeForce RTX 4090 24GB GDDR6X Graphics Card - Founders Edition',
                'price' => 1599.99,
                'sale_price' => null,
                'stock' => 10,
                'is_featured' => true,
            ],
            [
                'name' => 'AMD Radeon RX 7900 XTX',
                'description' => 'AMD Radeon RX 7900 XTX 24GB GDDR6 Graphics Card',
                'price' => 999.99,
                'sale_price' => 949.99,
                'stock' => 12,
                'is_featured' => true,
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4070',
                'description' => 'NVIDIA GeForce RTX 4070 12GB GDDR6X Graphics Card',
                'price' => 599.99,
                'sale_price' => 579.99,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'name' => 'AMD Radeon RX 6700 XT',
                'description' => 'AMD Radeon RX 6700 XT 12GB GDDR6 Graphics Card',
                'price' => 429.99,
                'sale_price' => 399.99,
                'stock' => 25,
                'is_featured' => false,
            ],
        ];

        foreach ($gpus as $gpu) {
            Product::create([
                'category_id' => $category->id,
                'name' => $gpu['name'],
                'slug' => Str::slug($gpu['name']),
                'description' => $gpu['description'],
                'price' => $gpu['price'],
                'sale_price' => $gpu['sale_price'],
                'stock' => $gpu['stock'],
                'sku' => 'GPU-' . Str::random(6),
                'is_featured' => $gpu['is_featured'],
                'is_active' => true,
            ]);
        }
    }

    private function createStorageProducts($category)
    {
        $storages = [
            [
                'name' => 'Samsung 980 PRO 2TB NVMe SSD',
                'description' => 'Samsung 980 PRO 2TB PCIe 4.0 NVMe M.2 Internal Solid State Drive',
                'price' => 249.99,
                'sale_price' => 219.99,
                'stock' => 30,
                'is_featured' => true,
            ],
            [
                'name' => 'WD Black SN850X 1TB NVMe SSD',
                'description' => 'Western Digital WD Black SN850X 1TB PCIe Gen4 NVMe M.2 Internal Gaming SSD',
                'price' => 159.99,
                'sale_price' => null,
                'stock' => 35,
                'is_featured' => true,
            ],
            [
                'name' => 'Seagate Barracuda 4TB HDD',
                'description' => 'Seagate Barracuda 4TB 5400 RPM SATA 6Gb/s 256MB Cache 3.5" Internal Hard Drive',
                'price' => 89.99,
                'sale_price' => 79.99,
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'name' => 'Crucial MX500 1TB SATA SSD',
                'description' => 'Crucial MX500 1TB 3D NAND SATA 2.5 Inch Internal Solid State Drive',
                'price' => 89.99,
                'sale_price' => 79.99,
                'stock' => 45,
                'is_featured' => false,
            ],
        ];

        foreach ($storages as $storage) {
            Product::create([
                'category_id' => $category->id,
                'name' => $storage['name'],
                'slug' => Str::slug($storage['name']),
                'description' => $storage['description'],
                'price' => $storage['price'],
                'sale_price' => $storage['sale_price'],
                'stock' => $storage['stock'],
                'sku' => 'STR-' . Str::random(6),
                'is_featured' => $storage['is_featured'],
                'is_active' => true,
            ]);
        }
    }

    private function createMotherboardProducts($category)
    {
        $motherboards = [
            [
                'name' => 'ASUS ROG Maximus Z790 Hero',
                'description' => 'ASUS ROG Maximus Z790 Hero ATX Gaming Motherboard - LGA1700, DDR5, PCIe 5.0',
                'price' => 629.99,
                'sale_price' => 599.99,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'name' => 'MSI MPG X670E Carbon WiFi',
                'description' => 'MSI MPG X670E Carbon WiFi Gaming Motherboard - AM5, DDR5, PCIe 5.0',
                'price' => 479.99,
                'sale_price' => null,
                'stock' => 18,
                'is_featured' => true,
            ],
            [
                'name' => 'Gigabyte B650 AORUS Elite AX',
                'description' => 'Gigabyte B650 AORUS Elite AX AM5 ATX Motherboard for AMD Ryzen 7000 Series CPUs',
                'price' => 229.99,
                'sale_price' => 209.99,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'name' => 'ASRock B760M Pro RS/D4',
                'description' => 'ASRock B760M Pro RS/D4 Micro ATX Motherboard - LGA1700, DDR4',
                'price' => 139.99,
                'sale_price' => 129.99,
                'stock' => 30,
                'is_featured' => false,
            ],
        ];

        foreach ($motherboards as $motherboard) {
            Product::create([
                'category_id' => $category->id,
                'name' => $motherboard['name'],
                'slug' => Str::slug($motherboard['name']),
                'description' => $motherboard['description'],
                'price' => $motherboard['price'],
                'sale_price' => $motherboard['sale_price'],
                'stock' => $motherboard['stock'],
                'sku' => 'MB-' . Str::random(6),
                'is_featured' => $motherboard['is_featured'],
                'is_active' => true,
            ]);
        }
    }
} 