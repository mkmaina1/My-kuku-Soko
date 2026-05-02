<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Essential features for small practices',
                'price' => 5000.00,
                'duration' => 'monthly',
                'features' => [
                    'Up to 50 consultations per month',
                    'Up to 10 farm visits per month',
                    'Basic analytics',
                    'Email support',
                    'Consultation history'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features for growing practices',
                'price' => 15000.00,
                'duration' => 'monthly',
                'features' => [
                    'Unlimited consultations',
                    'Unlimited farm visits',
                    'Advanced analytics & reporting',
                    'Telemedicine capabilities',
                    'Emergency support 24/7',
                    'Priority phone support',
                    'Disease outbreak alerts',
                    'Multi-farm management',
                    'Prescription management',
                    'Revenue tracking'
                ],
                'is_active' => true
            ]
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
