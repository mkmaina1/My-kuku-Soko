<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Marketplace; // Use Marketplace model
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Seeding orders...');

        // Get farmers and agents
        $farmers = User::where('role', 'farmer')->take(5)->get();
        $agents = User::where('role', 'agent')->take(2)->get();

        if ($farmers->isEmpty()) {
            $this->command->error('No farmers found. Please run UserSeeder first.');
            return;
        }

        if ($agents->isEmpty()) {
            $this->command->warn('No agents found. Orders will be created without agents.');
        }

        // Get available marketplace products
        $products = Marketplace::where('is_available', true)
            ->where('quantity', '>', 0)
            ->limit(20)
            ->get();

        if ($products->isEmpty()) {
            $this->command->error('No available marketplace products found.');
            $this->command->info('Please add products to the marketplace first.');
            return;
        }

        $this->command->info('Found ' . $products->count() . ' marketplace products.');

        // Status distribution percentages
        $statuses = [
            'pending' => 20,    // 20% pending
            'processing' => 25,  // 25% processing
            'shipped' => 25,     // 25% shipped
            'delivered' => 20,   // 20% delivered
            'cancelled' => 10,   // 10% cancelled
        ];

        // Payment methods
        $paymentMethods = ['mpesa', 'cash', 'card', 'agent'];

        // Kenya counties for shipping addresses
        $counties = [
            'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret',
            'Thika', 'Kitale', 'Malindi', 'Kericho', 'Kakamega'
        ];

        $totalOrders = 20; // Create 20 orders

        for ($i = 1; $i <= $totalOrders; $i++) {
            $farmer = $farmers->random();
            $agent = $agents->isNotEmpty() ? $agents->random() : null;

            // Determine status based on percentages
            $statusRand = rand(1, 100);
            $cumulative = 0;
            $status = 'pending'; // default

            foreach ($statuses as $statusName => $percentage) {
                $cumulative += $percentage;
                if ($statusRand <= $cumulative) {
                    $status = $statusName;
                    break;
                }
            }

            // Set delivery date for delivered orders
            $deliveredAt = null;
            if ($status === 'delivered') {
                $deliveredAt = Carbon::now()->subDays(rand(1, 30));
            }

            // Set random dates for order creation (last 6 months)
            $createdAt = Carbon::now()->subDays(rand(0, 180))->subHours(rand(0, 23));

            // Generate totals (will be updated after items)
            $subtotal = 0; // Will be calculated
            $shipping = rand(200, 2000);
            $tax = 0; // Will be calculated
            $total = 0; // Will be calculated

            // Shipping address
            $county = $counties[array_rand($counties)];
            $street = rand(100, 999) . ' ' . $this->randomStreetName();
            $address = "{$street}, {$county} County, Kenya";

            // Tracking number for shipped/delivered orders
            $trackingNumber = null;
            if (in_array($status, ['shipped', 'delivered'])) {
                $trackingNumber = 'TRK' . strtoupper(Str::random(10));
            }

            // Create order
            $order = Order::create([
                'user_id' => $farmer->id,
                'agent_id' => $agent?->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'shipping_address' => $address,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'notes' => rand(0, 1) ? $this->randomNote() : null,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'status' => $status,
                'tracking_number' => $trackingNumber,
                'delivered_at' => $deliveredAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(1, 5)),
            ]);

            $this->command->info("Created order #{$order->order_number} for farmer {$farmer->name}");

            // Create order items (1-3 items per order)
            $numItems = rand(1, 3);
            $selectedProducts = $products->random(min($numItems, $products->count()));

            if (!is_iterable($selectedProducts)) {
                $selectedProducts = [$selectedProducts];
            }

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, min(5, $product->quantity)); // Don't order more than available
                $price = $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $quantity * $price,
                ]);

                $this->command->info("  - Added {$quantity} x {$product->title} @ KES {$price}");
            }

            // Update order totals based on actual items
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            $actualSubtotal = $orderItems->sum('total');
            $actualTax = round($actualSubtotal * 0.16, 2); // 16% VAT
            $actualTotal = $actualSubtotal + $order->shipping + $actualTax;

            $order->update([
                'subtotal' => $actualSubtotal,
                'tax' => $actualTax,
                'total' => $actualTotal,
            ]);

            $this->command->info("  Order total: KES {$actualTotal}");
        }

        $this->command->info('Seeded ' . $totalOrders . ' orders with items.');

        // Show statistics
        $this->command->info("\nOrder Statistics:");
        $this->command->info("-----------------");
        $this->command->info("Total Orders: " . Order::count());
        $this->command->info("By Status:");
        foreach ($statuses as $status => $percentage) {
            $count = Order::where('status', $status)->count();
            $this->command->info("  - {$status}: {$count} orders");
        }
    }

    private function randomStreetName(): string
    {
        $streets = [
            'Moi Avenue', 'Kenyatta Avenue', 'Uhuru Highway', 'Waiyaki Way',
            'Ngong Road', 'Limuru Road', 'Thika Road', 'Mombasa Road',
            'Koinange Street', 'Tom Mboya Street', 'Mama Ngina Street',
            'River Road', 'Biashara Street', 'Kisumu Road', 'Eldoret Road'
        ];
        return $streets[array_rand($streets)];
    }

    private function randomNote(): string
    {
        $notes = [
            'Please deliver before 5 PM',
            'Leave package at the gate',
            'Call before delivery',
            'Fragile items, handle with care',
            'Delivery to reception',
            'Leave with neighbor if not available',
            'Weekend delivery preferred',
            'Morning delivery requested',
            'Ring bell twice',
            'Secure packaging required'
        ];
        return $notes[array_rand($notes)];
    }
}
