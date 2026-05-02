<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class MortalityOrderFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset all orders' mortality flags first
        DB::table('orders')->update([
            'mortality_expectation_flag' => false,
            'mortality_risk_level' => null,
            'mortality_resolved' => false,
            'mortality_notes' => null,
        ]);

        // Get a subset of orders to flag
        $orders = Order::inRandomOrder()->take(25)->get();

        $riskLevels = ['low', 'medium', 'high'];
        $riskFactors = [
            'Long distance transport (>500km)',
            'Extreme weather forecast',
            'Night time delivery',
            'New transport route',
            'High-value livestock',
            'Young/sensitive animals',
            'Previous mortality history',
            'Unfamiliar destination',
            'Holiday season delivery',
            'Multiple pickup points'
        ];

        foreach ($orders as $order) {
            $riskLevel = $riskLevels[array_rand($riskLevels)];
            $isResolved = rand(0, 1);
            $riskFactor = $riskFactors[array_rand($riskFactors)];

            $order->update([
                'mortality_expectation_flag' => true,
                'mortality_risk_level' => $riskLevel,
                'mortality_resolved' => $isResolved,
                'mortality_notes' => "Flagged for: $riskFactor. " .
                    ($isResolved ? 'Risk mitigated and resolved.' : 'Monitoring required.'),
            ]);
        }

        $this->command->info('Flagged 25 orders for mortality expectation.');
    }
}
