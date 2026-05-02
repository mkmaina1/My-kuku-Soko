<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportMortality;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransportMortalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table first
        DB::table('transport_mortality')->truncate();

        // Get some orders and agents for seeding
        $orders = Order::where('status', 'delivered')->take(20)->get();
        $agents = User::where('role', 'agent')->take(10)->get();
        $users = User::whereIn('role', ['admin', 'supplier', 'farmer'])->take(10)->get();

        if ($orders->isEmpty() || $agents->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Not enough data to seed transport mortality. Please seed orders and users first.');
            return;
        }

        $transportTypes = ['Pickup Truck', 'Delivery Van', 'Refrigerated Truck', 'Motorcycle', 'Bicycle'];
        $causes = [
            'Heat stress during transport',
            'Poor ventilation',
            'Overcrowding',
            'Long distance travel',
            'Rough handling',
            'Vehicle breakdown',
            'Traffic delays',
            'Extreme weather',
            'Improper loading',
            'Inadequate food/water'
        ];
        $statuses = ['reported', 'investigating', 'resolved'];

        $mortalityCases = [];

        for ($i = 0; $i < 50; $i++) {
            $order = $orders->random();
            $agent = $agents->random();
            $reporter = $users->random();

            $quantity = rand(1, 25);
            $status = $statuses[array_rand($statuses)];

            $mortalityCases[] = [
                'order_id' => $order->id,
                'agent_id' => $agent->id,
                'transport_type' => $transportTypes[array_rand($transportTypes)],
                'quantity' => $quantity,
                'cause' => $causes[array_rand($causes)],
                'notes' => $this->generateNotes($quantity, $status),
                'reported_by' => $reporter->id,
                'status' => $status,
                'resolved_at' => $status === 'resolved' ? now()->subDays(rand(1, 30)) : null,
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ];
        }

        // Insert in batches
        foreach (array_chunk($mortalityCases, 20) as $chunk) {
            TransportMortality::insert($chunk);
        }

        $this->command->info('Seeded 50 transport mortality cases.');
    }

    private function generateNotes($quantity, $status): string
    {
        $notes = [
            "Found $quantity birds dead upon arrival at destination.",
            "$quantity casualties reported during transit. Investigation ongoing.",
            "Mortality occurred due to sudden temperature changes.",
            "Animals showed signs of distress before expiring.",
            "Driver reported issues with vehicle cooling system.",
            "Delayed delivery may have contributed to mortality.",
            "Proper documentation and photos taken for insurance.",
            "Customer notified and compensation process initiated.",
            "Incident report filed with transport department.",
            "Preventive measures discussed with transport team."
        ];

        $selectedNote = $notes[array_rand($notes)];

        if ($status === 'resolved') {
            $selectedNote .= " Case resolved and closed.";
        } elseif ($status === 'investigating') {
            $selectedNote .= " Currently under investigation.";
        }

        return $selectedNote;
    }
}
