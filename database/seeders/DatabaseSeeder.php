<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // // Create a test admin user
        // \App\Models\User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'admin',
        // ]);

        // Run the marketplace seeder
        $this->call(MarketplaceSeeder::class);
        // Run the notification seeder
        $this->call(NotificationSeeder::class);
        // Run the category seeder
        $this->call(CategorySeeder::class);
        // Run the product seeder
        $this->call(ProductSeeder::class);
         // Run the order seeder
        $this->call(OrderSeeder::class);
        // Run the mortality order flag seeder
        $this->call(MortalityOrderFlagSeeder::class);
        $this->call(TransportMortalitySeeder::class);
        $this->call(MortalityReportSeeder::class);
        // Run the subscription plans seeder
        $this->call(SubscriptionPlansSeeder::class);
    }
}
