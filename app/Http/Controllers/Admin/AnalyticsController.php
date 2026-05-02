<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Marketplace;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display Supplier Analytics Overview
     */
    public function supplierOverview()
    {
        // Get total suppliers count
        $totalSuppliers = User::where('role', 'supplier')->count();

        // Get suppliers by verification status - fixed query
        $verifiedSuppliers = User::where('role', 'supplier')
            ->where(function($query) {
                $query->where('verification_status', 'approved')
                      ->orWhere('is_verified', true);
            })
            ->count();

        $unverifiedSuppliers = $totalSuppliers - $verifiedSuppliers;

        // Get active suppliers (have products)
        $activeSuppliers = User::where('role', 'supplier')
            ->whereHas('products')
            ->count();

        // Get supplier products statistics
        $totalProducts = Marketplace::count();
        $averageProductsPerSupplier = $totalSuppliers > 0 ? $totalProducts / $totalSuppliers : 0;

        // Get top suppliers by product count
        $topSuppliersByProducts = User::where('role', 'supplier')
            ->with(['addresses', 'products'])
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get();

        // Get supplier performance metrics
        $suppliersWithOrders = User::where('role', 'supplier')
            ->with(['addresses'])
            ->whereHas('orders')
            ->withCount('products')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->orderBy('orders_sum_total', 'desc')
            ->take(10)
            ->get();

        // Get monthly supplier growth
        $supplierGrowth = $this->getMonthlyGrowth('supplier');

        // Get supplier locations from addresses table
        $supplierLocations = $this->getUserLocationsFromAddresses('supplier');

        // Get supplier product categories distribution
        $productCategories = Marketplace::select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.analytics.suppliers', [
            'stats' => [
                'total_suppliers' => $totalSuppliers,
                'verified_suppliers' => $verifiedSuppliers,
                'unverified_suppliers' => $unverifiedSuppliers,
                'active_suppliers' => $activeSuppliers,
                'total_products' => $totalProducts,
                'avg_products_per_supplier' => round($averageProductsPerSupplier, 1),
                'supplier_growth_rate' => $this->calculateGrowthRate($supplierGrowth),
            ],
            'topSuppliersByProducts' => $topSuppliersByProducts,
            'suppliersWithOrders' => $suppliersWithOrders,
            'supplierGrowth' => $supplierGrowth,
            'supplierLocations' => $supplierLocations,
            'productCategories' => $productCategories,
        ]);
    }

    /**
     * Display Farmer Analytics Overview
     */
    public function farmerOverview()
    {
        // Get total farmers count
        $totalFarmers = User::where('role', 'farmer')->count();

        // Get farmers by verification status - fixed query
        $verifiedFarmers = User::where('role', 'farmer')
            ->where(function($query) {
                $query->where('verification_status', 'approved')
                      ->orWhere('is_verified', true);
            })
            ->count();

        $unverifiedFarmers = $totalFarmers - $verifiedFarmers;

        // Get active farmers (made orders)
        $activeFarmers = User::where('role', 'farmer')
            ->whereHas('orders')
            ->count();

        // Get farmer purchase statistics
        $totalFarmerOrders = Order::whereHas('user', function($query) {
            $query->where('role', 'farmer');
        })->count();

        $totalFarmerSpending = Order::whereHas('user', function($query) {
            $query->where('role', 'farmer');
        })->where('status', 'delivered')->sum('total');

        $avgOrderValue = $totalFarmerOrders > 0 ? $totalFarmerSpending / $totalFarmerOrders : 0;

        // Get top farmers by spending
        $topFarmersBySpending = User::where('role', 'farmer')
            ->with(['addresses'])
            ->withSum(['orders' => function($query) {
                $query->where('status', 'delivered');
            }], 'total')
            ->withCount(['orders' => function($query) {
                $query->where('status', 'delivered');
            }])
            ->orderBy('orders_sum_total', 'desc')
            ->take(10)
            ->get();

        // Get farmer growth data
        $farmerGrowth = $this->getMonthlyGrowth('farmer');

        // Get farmer locations from addresses table
        $farmerLocations = $this->getUserLocationsFromAddresses('farmer');

        // Get purchase frequency analysis
        $purchaseFrequency = $this->analyzePurchaseFrequency();

        // Get average order value over time
        $avgOrderValueTrend = $this->getAvgOrderValueTrend();

        return view('admin.analytics.farmers', [
            'stats' => [
                'total_farmers' => $totalFarmers,
                'verified_farmers' => $verifiedFarmers,
                'unverified_farmers' => $unverifiedFarmers,
                'active_farmers' => $activeFarmers,
                'total_orders' => $totalFarmerOrders,
                'total_spending' => $totalFarmerSpending,
                'avg_order_value' => round($avgOrderValue, 2),
                'farmer_growth_rate' => $this->calculateGrowthRate($farmerGrowth),
            ],
            'topFarmersBySpending' => $topFarmersBySpending,
            'farmerGrowth' => $farmerGrowth,
            'farmerLocations' => $farmerLocations,
            'purchaseFrequency' => $purchaseFrequency,
            'avgOrderValueTrend' => $avgOrderValueTrend,
        ]);
    }

    /**
     * Display Agent Analytics Overview
     */
    public function agentOverview()
    {
        // Get total agents count
        $totalAgents = User::where('role', 'agent')->count();

        // Get agents by verification status - fixed query
        $verifiedAgents = User::where('role', 'agent')
            ->where(function($query) {
                $query->where('verification_status', 'approved')
                      ->orWhere('is_verified', true);
            })
            ->count();

        $unverifiedAgents = $totalAgents - $verifiedAgents;

        // Get active agents (have associated orders)
        $activeAgents = User::where('role', 'agent')
            ->whereHas('agentOrders')
            ->count();

        // Get agent performance metrics
        $totalAgentOrders = Order::whereNotNull('agent_id')->count();
        $totalAgentCommission = 0;

        // Calculate total commission (assuming 5% commission rate)
        $agentOrders = Order::whereNotNull('agent_id')
            ->where('status', 'delivered')
            ->get();

        foreach ($agentOrders as $order) {
            $totalAgentCommission += $order->total * 0.05;
        }

        // Get top performing agents
        $topAgents = User::where('role', 'agent')
            ->with(['addresses'])
            ->withCount(['agentOrders' => function($query) {
                $query->where('status', 'delivered');
            }])
            ->withSum(['agentOrders' => function($query) {
                $query->where('status', 'delivered');
            }], 'total')
            ->orderBy('agent_orders_count', 'desc')
            ->take(10)
            ->get()
            ->map(function($agent) {
                $agent->total_commission = ($agent->agent_orders_sum_total ?? 0) * 0.05;
                return $agent;
            });

        // Get agent growth data
        $agentGrowth = $this->getMonthlyGrowth('agent');

        // Get agent locations from addresses table
        $agentLocations = $this->getUserLocationsFromAddresses('agent');

        // Get agent performance metrics
        $agentPerformance = $this->calculateAgentPerformance();

        // Get commission distribution
        $commissionDistribution = $this->getCommissionDistribution();

        return view('admin.analytics.agents', [
            'stats' => [
                'total_agents' => $totalAgents,
                'verified_agents' => $verifiedAgents,
                'unverified_agents' => $unverifiedAgents,
                'active_agents' => $activeAgents,
                'total_orders' => $totalAgentOrders,
                'total_commission' => round($totalAgentCommission, 2),
                'avg_orders_per_agent' => $totalAgents > 0 ? round($totalAgentOrders / $totalAgents, 1) : 0,
                'agent_growth_rate' => $this->calculateGrowthRate($agentGrowth),
            ],
            'topAgents' => $topAgents,
            'agentGrowth' => $agentGrowth,
            'agentLocations' => $agentLocations,
            'agentPerformance' => $agentPerformance,
            'commissionDistribution' => $commissionDistribution,
        ]);
    }

    /**
     * Get user locations from addresses table
     */
    private function getUserLocationsFromAddresses($role)
    {
        try {
            // Get county distribution from addresses table
            return Address::join('users', 'addresses.user_id', '=', 'users.id')
                ->where('users.role', $role)
                ->select(
                    'addresses.county as location',
                    DB::raw('COUNT(DISTINCT addresses.user_id) as total')
                )
                ->whereNotNull('addresses.county')
                ->groupBy('addresses.county')
                ->orderBy('total', 'desc')
                ->get()
                ->map(function($item) {
                    return (object)[
                        'location' => $item->location,
                        'total' => $item->total
                    ];
                });

        } catch (\Exception $e) {
            // Log error and return empty collection
            \Log::error('Error fetching user locations from addresses: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get monthly growth data for a specific role
     */
    private function getMonthlyGrowth($role)
    {
        $growth = collect();

        for ($i = 11; $i >= 0; $i--) {
            $startDate = now()->subMonths($i)->startOfMonth();
            $endDate = now()->subMonths($i)->endOfMonth();

            $count = User::where('role', $role)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $growth->push([
                'month' => $startDate->format('M Y'),
                'count' => $count,
                'date' => $startDate->format('Y-m'),
            ]);
        }

        return $growth;
    }

    /**
     * Calculate growth rate from growth data
     */
    private function calculateGrowthRate($growthData)
    {
        if ($growthData->count() < 2) return 0;

        $current = $growthData->last()['count'] ?? 0;
        $previous = $growthData->slice(-2, 1)->first()['count'] ?? 0;

        if ($previous == 0) return $current > 0 ? 100 : 0;

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Analyze purchase frequency
     */
    private function analyzePurchaseFrequency()
    {
        $frequency = [
            'one_time' => 0,
            'regular' => 0,
            'frequent' => 0,
        ];

        try {
            $farmers = User::where('role', 'farmer')
                ->withCount('orders')
                ->get();

            foreach ($farmers as $farmer) {
                if ($farmer->orders_count == 1) {
                    $frequency['one_time']++;
                } elseif ($farmer->orders_count >= 2 && $farmer->orders_count <= 5) {
                    $frequency['regular']++;
                } elseif ($farmer->orders_count > 5) {
                    $frequency['frequent']++;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error analyzing purchase frequency: ' . $e->getMessage());
        }

        return $frequency;
    }

    /**
     * Get average order value trend
     */
    private function getAvgOrderValueTrend()
    {
        $trend = collect();

        try {
            for ($i = 5; $i >= 0; $i--) {
                $startDate = now()->subMonths($i)->startOfMonth();
                $endDate = now()->subMonths($i)->endOfMonth();

                $orders = Order::whereHas('user', function($query) {
                        $query->where('role', 'farmer');
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'delivered')
                    ->get();

                $totalOrders = $orders->count();
                $totalValue = $orders->sum('total');

                $avgValue = $totalOrders > 0 ? $totalValue / $totalOrders : 0;

                $trend->push([
                    'month' => $startDate->format('M Y'),
                    'avg_value' => round($avgValue, 2),
                    'total_orders' => $totalOrders,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error getting avg order value trend: ' . $e->getMessage());
        }

        return $trend;
    }

    /**
     * Calculate agent performance metrics
     */
    private function calculateAgentPerformance()
    {
        try {
            $agents = User::where('role', 'agent')
                ->withCount(['agentOrders' => function($query) {
                    $query->where('status', 'delivered');
                }])
                ->get();

            $totalOrders = $agents->sum('agent_orders_count');
            $avgOrders = $agents->count() > 0 ? $totalOrders / $agents->count() : 0;

            // Calculate performance tiers
            $tiers = [
                'top_performers' => $agents->where('agent_orders_count', '>', $avgOrders * 1.5)->count(),
                'average_performers' => $agents->whereBetween('agent_orders_count', [$avgOrders * 0.5, $avgOrders * 1.5])->count(),
                'low_performers' => $agents->where('agent_orders_count', '<', $avgOrders * 0.5)->count(),
                'inactive' => $agents->where('agent_orders_count', 0)->count(),
            ];

            return [
                'total_agents' => $agents->count(),
                'avg_orders_per_agent' => round($avgOrders, 1),
                'performance_tiers' => $tiers,
            ];
        } catch (\Exception $e) {
            \Log::error('Error calculating agent performance: ' . $e->getMessage());
            return [
                'total_agents' => 0,
                'avg_orders_per_agent' => 0,
                'performance_tiers' => [
                    'top_performers' => 0,
                    'average_performers' => 0,
                    'low_performers' => 0,
                    'inactive' => 0,
                ],
            ];
        }
    }

    /**
     * Get commission distribution
     */
    private function getCommissionDistribution()
    {
        $distribution = [
            '0-1000' => 0,
            '1001-5000' => 0,
            '5001-10000' => 0,
            '10001-50000' => 0,
            '50001+' => 0,
        ];

        try {
            $agents = User::where('role', 'agent')
                ->withSum(['agentOrders' => function($query) {
                    $query->where('status', 'delivered');
                }], 'total')
                ->get();

            foreach ($agents as $agent) {
                $commission = ($agent->agent_orders_sum_total ?? 0) * 0.05;

                if ($commission <= 1000) {
                    $distribution['0-1000']++;
                } elseif ($commission <= 5000) {
                    $distribution['1001-5000']++;
                } elseif ($commission <= 10000) {
                    $distribution['5001-10000']++;
                } elseif ($commission <= 50000) {
                    $distribution['10001-50000']++;
                } else {
                    $distribution['50001+']++;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error getting commission distribution: ' . $e->getMessage());
        }

        return $distribution;
    }
}
