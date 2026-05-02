<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Get a supplier (farmer) to assign to categories
        $supplier = User::where('role', 'farmer')->first();

        if (!$supplier) {
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
        }

        $categories = [
            ['name' => 'Poultry Feed', 'description' => 'Various types of poultry feeds'],
            ['name' => 'Medication', 'description' => 'Poultry vaccines and medicines'],
            ['name' => 'Equipment', 'description' => 'Farming equipment and tools'],
            ['name' => 'Day Old Chicks', 'description' => 'Various breeds of day old chicks'],
            ['name' => 'Supplements', 'description' => 'Nutritional supplements for poultry'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'supplier_id' => $supplier->id, // Add supplier_id
                'is_active' => true,
            ]);
        }

        $this->command->info('Created ' . count($categories) . ' categories.');
    }
}
