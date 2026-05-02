<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Marketplace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketplaceSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear existing data
        Marketplace::truncate();
        User::where('role', '!=', 'admin')->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Rest of your seeder code remains the same...
        $this->command->info('Creating users and products...');

        // Create users with different roles
        $suppliers = User::factory()->count(15)->supplier()->create();
        $farmers = User::factory()->count(25)->farmer()->create();
        $agents = User::factory()->count(8)->agent()->create();
        $veterinaries = User::factory()->count(5)->veterinary()->create();

        $this->command->info('✅ Users created:');
        $this->command->info("   Suppliers: {$suppliers->count()}");
        $this->command->info("   Farmers: {$farmers->count()}");
        $this->command->info("   Agents: {$agents->count()}");
        $this->command->info("   Veterinaries: {$veterinaries->count()}");

        // Create marketplace products
        $productTypes = [
            'chicks' => 25,
            'feed' => 20,
            'medicine' => 10,
            'equipment' => 10,
            'eggs' => 5,
        ];

        foreach ($productTypes as $type => $count) {
            $products = Marketplace::factory()
                ->count($count)
                ->{$type}()
                ->create();

            $this->command->info("   {$type}: {$products->count()} products");
        }

        // Create some popular products
        Marketplace::factory()
            ->count(5)
            ->popular()
            ->chicks()
            ->create();

        Marketplace::factory()
            ->count(3)
            ->popular()
            ->feed()
            ->create();

        // Create some new products
    Marketplace::factory()
    ->count(7)
    ->fresh()  // NEW NAME
    ->create();

        $totalCreated = Marketplace::count();

        $this->command->info("\n🎉 Marketplace seeding completed!");
        $this->command->info("📊 Total products created: {$totalCreated}");

        // Display sample credentials
        $this->command->info("\n🔐 Sample Login Credentials (password: 'password'):");
        $this->command->info("==========================================");

        $sampleSupplier = User::where('role', 'supplier')->first();
        $sampleFarmer = User::where('role', 'farmer')->first();
        $sampleAgent = User::where('role', 'agent')->first();
        $sampleVet = User::where('role', 'veterinary')->first();

        if ($sampleSupplier) {
            $this->command->info("👨‍💼 Supplier: {$sampleSupplier->email}");
        }
        if ($sampleFarmer) {
            $this->command->info("👨‍🌾 Farmer: {$sampleFarmer->email}");
        }
        if ($sampleAgent) {
            $this->command->info("👨‍💼 Agent: {$sampleAgent->email}");
        }
        if ($sampleVet) {
            $this->command->info("👨‍⚕️ Veterinary: {$sampleVet->email}");
        }

        $this->command->info("==========================================");

        // Display some statistics
        $this->command->info("\n📈 Marketplace Statistics:");
        $this->command->info("=========================");
        $this->command->info("Total Products: " . Marketplace::count());
        $this->command->info("Available Products: " . Marketplace::where('is_available', true)->count());
        $this->command->info("Average Price: KES " . number_format(Marketplace::avg('price') ?? 0, 2));
        $this->command->info("Total Stock Value: KES " . number_format(Marketplace::sum(\DB::raw('price * quantity')) ?? 0, 2));
        $this->command->info("=========================");
    }
}
