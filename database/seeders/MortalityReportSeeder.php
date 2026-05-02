<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MortalityReport;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MortalityReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table first
        DB::table('mortality_reports')->truncate();

        // Get some orders and users for seeding
        $orders = Order::take(30)->get();
        $users = User::whereIn('role', ['farmer', 'supplier', 'agent', 'admin'])->take(15)->get();
        $agents = User::where('role', 'agent')->take(5)->get();
        $admins = User::where('role', 'admin')->take(3)->get();

        if ($orders->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Not enough data to seed mortality reports. Please seed orders and users first.');
            return;
        }

        $reportTypes = ['complaint', 'report', 'suggestion', 'other'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'investigating', 'resolved', 'closed'];

        $titles = [
            'High Mortality Rate During Transport',
            'Poor Handling of Livestock',
            'Need Better Transport Conditions',
            'Unacceptable Mortality Levels',
            'Transport Delays Causing Issues',
            'Vehicle Maintenance Concerns',
            'Driver Training Suggestions',
            'Temperature Control Problems',
            'Overcrowding Issues',
            'Emergency Response Needed'
        ];

        $descriptions = [
            'Observed unusually high mortality during recent transport. Need immediate attention.',
            'Animals are not being handled properly during loading and unloading.',
            'Suggest implementing better monitoring systems for transport conditions.',
            'Recent deliveries have shown unacceptable mortality rates.',
            'Transport delays are causing stress and mortality in animals.',
            'Vehicles need better maintenance to prevent breakdowns during transport.',
            'Drivers require additional training on animal welfare during transport.',
            'Temperature fluctuations in transport vehicles are causing issues.',
            'Overcrowding is a serious concern that needs to be addressed.',
            'Emergency response protocol needs improvement for transport incidents.'
        ];

        $reports = [];

        for ($i = 0; $i < 40; $i++) {
            $order = $orders->random();
            $user = $users->random();
            $agent = rand(0, 1) ? $agents->random()->id : null;
            $assignedTo = rand(0, 1) ? $admins->random()->id : null;
            $status = $statuses[array_rand($statuses)];

            $reports[] = [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'agent_id' => $agent,
                'report_type' => $reportTypes[array_rand($reportTypes)],
                'title' => $titles[array_rand($titles)],
                'description' => $descriptions[array_rand($descriptions)] . ' Order #' . $order->order_number,
                'priority' => $priorities[array_rand($priorities)],
                'status' => $status,
                'assigned_to' => $assignedTo,
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(rand(1, 15)) : null,
                'resolution_notes' => in_array($status, ['resolved', 'closed']) ? $this->generateResolutionNotes() : null,
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(0, 15)),
            ];
        }

        // Insert in batches
        foreach (array_chunk($reports, 20) as $chunk) {
            MortalityReport::insert($chunk);
        }

        $this->command->info('Seeded 40 mortality reports.');
    }

    private function generateResolutionNotes(): string
    {
        $notes = [
            'Issue resolved with transport provider. Compensation provided to customer.',
            'Driver retrained on proper handling procedures.',
            'Vehicle maintenance schedule updated and implemented.',
            'New monitoring equipment installed in transport vehicles.',
            'Protocol updated to prevent similar incidents in future.',
            'Customer satisfied with resolution and compensation.',
            'Investigation complete, corrective actions implemented.',
            'Training program developed for all transport staff.',
            'Insurance claim processed successfully.',
            'System improvements implemented based on report findings.'
        ];

        return $notes[array_rand($notes)];
    }
}
