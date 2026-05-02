<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all agent users
        $agents = User::where('role', 'agent')->get();

        // If no agents exist, create a sample agent
        if ($agents->isEmpty()) {
            $agent = User::create([
                'name' => 'Test Agent',
                'email' => 'agent@example.com',
                'password' => bcrypt('password'),
                'role' => 'agent',
                'email_verified_at' => Carbon::now(),
            ]);
            $agents = collect([$agent]);
        }

        foreach ($agents as $agent) {
            // Sample notifications for agents
            $sampleNotifications = [
                [
                    'type' => 'order',
                    'title' => 'New Order Received',
                    'message' => 'You have received a new order #ORD-00123 from John Doe.',
                    'read' => false,
                    'created_at' => Carbon::now()->subDays(2),
                ],
                [
                    'type' => 'commission',
                    'title' => 'Commission Earned',
                    'message' => 'KES 5,250 commission has been added to your account from completed orders.',
                    'read' => false,
                    'created_at' => Carbon::now()->subDays(1),
                ],
                [
                    'type' => 'target',
                    'title' => 'Target Progress Update',
                    'message' => 'Your monthly sales target is 65% complete. Keep it up!',
                    'read' => true,
                    'created_at' => Carbon::now()->subDays(3),
                ],
                [
                    'type' => 'marketplace',
                    'title' => 'New Product Available',
                    'message' => 'New poultry feed has been added to the marketplace.',
                    'read' => false,
                    'created_at' => Carbon::now()->subHours(6),
                ],
                [
                    'type' => 'system',
                    'title' => 'System Update',
                    'message' => 'The system has been updated with new features. Check them out!',
                    'read' => true,
                    'created_at' => Carbon::now()->subDays(5),
                ],
                [
                    'type' => 'email',
                    'title' => 'Welcome to AgriConnect',
                    'message' => 'Thank you for joining AgriConnect as an agent. Start connecting farmers with suppliers!',
                    'read' => true,
                    'created_at' => Carbon::now()->subDays(7),
                ],
                [
                    'type' => 'order',
                    'title' => 'Order Delivered Successfully',
                    'message' => 'Order #ORD-00120 has been delivered to Farmer Jane.',
                    'read' => false,
                    'created_at' => Carbon::now()->subHours(2),
                ],
                [
                    'type' => 'commission',
                    'title' => 'Monthly Commission Report',
                    'message' => 'Your total commission for this month is KES 12,800. Great work!',
                    'read' => false,
                    'created_at' => Carbon::now()->subHours(12),
                ],
            ];

            foreach ($sampleNotifications as $sample) {
                Notification::create([
                    'user_id' => $agent->id,
                    'user_type' => 'agent',
                    'type' => $sample['type'],
                    'title' => $sample['title'],
                    'message' => $sample['message'],
                    'read' => $sample['read'],
                    'data' => [
                        'sample' => true,
                        'agent_id' => $agent->id,
                        'agent_name' => $agent->name,
                    ],
                    'created_at' => $sample['created_at'],
                    'updated_at' => $sample['created_at'],
                ]);
            }
        }

        // Also create notifications for other user types if they exist
        $farmers = User::where('role', 'farmer')->limit(3)->get();
        foreach ($farmers as $farmer) {
            Notification::create([
                'user_id' => $farmer->id,
                'user_type' => 'farmer',
                'type' => 'order',
                'title' => 'Order Confirmed',
                'message' => 'Your order has been confirmed and is being processed.',
                'read' => false,
                'data' => ['sample' => true],
            ]);
        }

        $suppliers = User::where('role', 'supplier')->limit(2)->get();
        foreach ($suppliers as $supplier) {
            Notification::create([
                'user_id' => $supplier->id,
                'user_type' => 'supplier',
                'type' => 'order',
                'title' => 'New Purchase Order',
                'message' => 'You have received a new purchase order from a farmer.',
                'read' => false,
                'data' => ['sample' => true],
            ]);
        }

        $this->command->info('Sample notifications seeded successfully!');
    }
}
