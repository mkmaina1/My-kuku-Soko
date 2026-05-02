<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category; // Use Category, NOT ProductCategory
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting ProductSeeder...');

        // Check if categories exist, if not create them
        if (DB::table('categories')->count() === 0) {
            $this->command->info('No categories found, creating default categories...');

            $categories = [
                ['name' => 'Poultry Feed', 'description' => 'Various types of poultry feeds'],
                ['name' => 'Medication', 'description' => 'Poultry vaccines and medicines'],
                ['name' => 'Equipment', 'description' => 'Farming equipment and tools'],
                ['name' => 'Day Old Chicks', 'description' => 'Various breeds of day old chicks'],
                ['name' => 'Supplements', 'description' => 'Nutritional supplements for poultry'],
            ];

            foreach ($categories as $category) {
                DB::table('categories')->insert([
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'supplier_id' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('Created 5 default categories.');
        }

        // Get all categories
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        if (empty($categoryIds)) {
            $this->command->error('No categories found. Please run CategorySeeder first.');
            return;
        }

        $this->command->info('Found ' . count($categoryIds) . ' categories.');

        // Get some suppliers (farmers)
        $suppliers = User::where('role', 'farmer')->take(3)->get();

        if ($suppliers->isEmpty()) {
            $this->command->info('No farmers found, creating a test farmer...');

            // Create a supplier if none exists
            $supplier = User::create([
                'name' => 'Test Farmer',
                'email' => 'farmer@example.com',
                'password' => bcrypt('password'),
                'phone' => '+254700000000',
                'role' => 'farmer',
                'county' => 'Nairobi',
                'address' => '123 Farm Road',
            ]);
            $suppliers = collect([$supplier]);

            $this->command->info('Created test farmer with ID: ' . $supplier->id);
        }

        $this->command->info('Found ' . $suppliers->count() . ' suppliers.');

        // Sample poultry products - matching your Product model fields
        $products = [
            [
                'name' => 'Chicken Grower Mash 25kg',
                'sku' => 'PCG-001',
                'description' => 'High quality grower mash for chickens aged 8-18 weeks',
                'price' => 2500.00,
                'cost_price' => 2000.00,
                'stock_quantity' => 100,
                'low_stock_threshold' => 20,
                'unit' => 'bag',
                'category_id' => $categoryIds[0] ?? 1,
                'supplier_id' => $suppliers->first()->id,
                'expiry_date' => Carbon::now()->addMonths(6),
            ],
            [
                'name' => 'Layer Mash 25kg',
                'sku' => 'PLM-002',
                'description' => 'Premium layer mash for egg production',
                'price' => 2800.00,
                'cost_price' => 2200.00,
                'stock_quantity' => 80,
                'low_stock_threshold' => 15,
                'unit' => 'bag',
                'category_id' => $categoryIds[0] ?? 1,
                'supplier_id' => $suppliers->first()->id,
                'expiry_date' => Carbon::now()->addMonths(6),
            ],
            [
                'name' => 'Broiler Starter 25kg',
                'sku' => 'PBS-003',
                'description' => 'Starter feed for broiler chicks',
                'price' => 2700.00,
                'cost_price' => 2100.00,
                'stock_quantity' => 120,
                'low_stock_threshold' => 25,
                'unit' => 'bag',
                'category_id' => $categoryIds[0] ?? 1,
                'supplier_id' => $suppliers->first()->id,
                'expiry_date' => Carbon::now()->addMonths(6),
            ],
            [
                'name' => 'Vaccine - Newcastle',
                'sku' => 'MNC-004',
                'description' => 'Newcastle disease vaccine for poultry',
                'price' => 1500.00,
                'cost_price' => 1200.00,
                'stock_quantity' => 50,
                'low_stock_threshold' => 10,
                'unit' => 'vial',
                'category_id' => $categoryIds[1] ?? 2,
                'supplier_id' => $suppliers->first()->id,
                'expiry_date' => Carbon::now()->addMonths(12),
            ],
            [
                'name' => 'Poultry Drinker',
                'sku' => 'EPD-005',
                'description' => 'Automatic poultry drinker 5 liters',
                'price' => 850.00,
                'cost_price' => 600.00,
                'stock_quantity' => 30,
                'low_stock_threshold' => 5,
                'unit' => 'piece',
                'category_id' => $categoryIds[2] ?? 3,
                'supplier_id' => $suppliers->first()->id,
            ],
            [
                'name' => 'Kienyeji Chicks',
                'sku' => 'DKC-006',
                'description' => 'Day old kienyeji chicken chicks',
                'price' => 120.00,
                'cost_price' => 80.00,
                'stock_quantity' => 200,
                'low_stock_threshold' => 30,
                'unit' => 'bird',
                'category_id' => $categoryIds[3] ?? 4,
                'supplier_id' => $suppliers->first()->id,
            ],
            [
                'name' => 'Broiler Chicks',
                'sku' => 'DBC-007',
                'description' => 'Day old broiler chicken chicks',
                'price' => 100.00,
                'cost_price' => 70.00,
                'stock_quantity' => 300,
                'low_stock_threshold' => 50,
                'unit' => 'bird',
                'category_id' => $categoryIds[3] ?? 4,
                'supplier_id' => $suppliers->first()->id,
            ],
            [
                'name' => 'Layer Chicks',
                'sku' => 'DLC-008',
                'description' => 'Day old layer chicken chicks',
                'price' => 110.00,
                'cost_price' => 75.00,
                'stock_quantity' => 250,
                'low_stock_threshold' => 40,
                'unit' => 'bird',
                'category_id' => $categoryIds[3] ?? 4,
                'supplier_id' => $suppliers->first()->id,
            ],
            [
                'name' => 'Vitamin Supplement',
                'sku' => 'SVS-009',
                'description' => 'Poultry vitamin and mineral supplement',
                'price' => 800.00,
                'cost_price' => 600.00,
                'stock_quantity' => 40,
                'low_stock_threshold' => 8,
                'unit' => 'kg',
                'category_id' => $categoryIds[4] ?? 5,
                'supplier_id' => $suppliers->first()->id,
                'expiry_date' => Carbon::now()->addMonths(12),
            ],
            [
                'name' => 'Feeding Trough',
                'sku' => 'EFT-010',
                'description' => 'Plastic feeding trough for poultry',
                'price' => 650.00,
                'cost_price' => 450.00,
                'stock_quantity' => 25,
                'low_stock_threshold' => 5,
                'unit' => 'piece',
                'category_id' => $categoryIds[2] ?? 3,
                'supplier_id' => $suppliers->first()->id,
            ],
        ];

        $createdCount = 0;
        foreach ($products as $productData) {
            try {
                Product::create([
                    'name' => $productData['name'],
                    'sku' => $productData['sku'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'cost_price' => $productData['cost_price'],
                    'stock_quantity' => $productData['stock_quantity'],
                    'low_stock_threshold' => $productData['low_stock_threshold'],
                    'unit' => $productData['unit'],
                    'category_id' => $productData['category_id'],
                    'supplier_id' => $productData['supplier_id'],
                    'expiry_date' => $productData['expiry_date'] ?? null,
                    'is_active' => true,
                ]);
                $createdCount++;
            } catch (\Exception $e) {
                $this->command->error('Failed to create product: ' . $productData['name']);
                $this->command->error('Error: ' . $e->getMessage());
            }
        }

        $this->command->info('Successfully created ' . $createdCount . ' out of ' . count($products) . ' products.');
    }
}
