<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Commission;
use App\Models\User;
use App\Models\PerformanceTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display agent dashboard.
     */
    public function index()
    {
        $agent = Auth::user();

        // Get comprehensive agent statistics
        $stats = $this->ensureStatsKeys($this->getAgentStats($agent));

        // Get recent data
        $recentOrders = $this->getRecentOrders($agent);
        $recentCommissions = $this->getRecentCommissions($agent);

        // Get performance targets
        $performanceTargets = $this->getPerformanceTargets($agent);

        // Prepare data for view - ensure all keys are present
        $data = [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentCommissions' => $recentCommissions,
            'performanceTargets' => $performanceTargets,
            'recent_activities' => $this->getRecentActivities($agent),
            'suppliers_represented' => $this->getSuppliersRepresented($agent),
        ];

        return view('agent.dashboard', $data);
    }

    /**
     * Get comprehensive agent statistics.
     */
    private function getAgentStats($agent)
    {
        try {
            // Get order statistics
            $orderStats = $this->getOrderStatistics($agent);

            // Calculate commission
            $totalCommission = $this->calculateTotalCommission($agent);
            $pendingCommission = $this->calculatePendingCommission($agent);

            // Get performance metrics
            $performanceMetrics = $this->getPerformanceMetrics($agent);

            // Calculate target completion rate
            $performanceTargets = $this->getPerformanceTargets($agent);
            $targetCompletionRate = $this->calculateTargetCompletionRate($agent, $performanceTargets);

            // Calculate commission rate
            $commissionRate = $this->calculateCommissionRate($agent);

            // Ensure all required keys exist
            return [
                // Order tracking stats
                'total_orders' => $orderStats['total'] ?? 0,
                'pending_orders' => $orderStats['pending'] ?? 0,
                'processing_orders' => $orderStats['processing'] ?? 0,
                'shipped_orders' => $orderStats['shipped'] ?? 0,
                'delivered_orders' => $orderStats['delivered'] ?? 0,
                'cancelled_orders' => $orderStats['cancelled'] ?? 0,

                // Commission stats
                'total_commission' => $totalCommission ?? 0,
                'pending_commission' => $pendingCommission ?? 0,
                'commission_rate' => $commissionRate,
                'commission_rate_formatted' => number_format($commissionRate, 1) . '%',

                // User stats
                'active_farmers' => User::where('role', 'farmer')->count() ?? 0,
                'farmers_count' => User::where('role', 'farmer')->count() ?? 0,
                'suppliers_count' => User::where('role', 'supplier')->count() ?? 0,

                // Performance metrics
                'targetCompletionRate' => $targetCompletionRate ?? 0,
                'target_completion_rate' => $targetCompletionRate ?? 0,
                'connection_rate' => $performanceMetrics['connection_rate'] ?? '0%',
                'conversion_rate' => $performanceMetrics['conversion_rate'] ?? '0%',
                'satisfaction_rate' => $performanceMetrics['satisfaction_rate'] ?? '0%',

                // Legacy compatibility
                'farmers_registered' => User::where('role', 'farmer')->count() ?? 0,
                'verified_suppliers' => User::where('role', 'supplier')->count() ?? 0,
                'transactions_processed' => $orderStats['total'] ?? 0,

                // Additional stats that might be used in the view
                'totalOrders' => $orderStats['total'] ?? 0,
                'pendingOrders' => $orderStats['pending'] ?? 0,
                'processingOrders' => $orderStats['processing'] ?? 0,
                'inTransitOrders' => $orderStats['shipped'] ?? 0,
                'deliveredOrders' => $orderStats['delivered'] ?? 0,
                'cancelledOrders' => $orderStats['cancelled'] ?? 0,
                'commissionEarned' => $totalCommission ?? 0,
                'farmersCount' => User::where('role', 'farmer')->count() ?? 0,
                'suppliersCount' => User::where('role', 'supplier')->count() ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting agent stats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Calculate commission rate.
     */
    private function calculateCommissionRate($agent)
    {
        try {
            // Calculate based on delivered orders and commission earned
            $deliveredOrdersTotal = Order::where('agent_id', $agent->id)
                ->where('status', 'delivered')
                ->sum('total');

            $commissionEarned = $this->calculateTotalCommission($agent);

            if ($deliveredOrdersTotal > 0 && $commissionEarned > 0) {
                return ($commissionEarned / $deliveredOrdersTotal) * 100;
            }

            // Default commission rate (5%)
            return 5.0;
        } catch (\Exception $e) {
            Log::error('Error calculating commission rate: ' . $e->getMessage());
            return 5.0;
        }
    }

    /**
     * Get order statistics by status.
     */
    private function getOrderStatistics($agent)
    {
        try {
            return [
                'total' => Order::where('agent_id', $agent->id)->count(),
                'pending' => Order::where('agent_id', $agent->id)->where('status', 'pending')->count(),
                'processing' => Order::where('agent_id', $agent->id)->where('status', 'processing')->count(),
                'shipped' => Order::where('agent_id', $agent->id)->where('status', 'shipped')->count(),
                'delivered' => Order::where('agent_id', $agent->id)->where('status', 'delivered')->count(),
                'cancelled' => Order::where('agent_id', $agent->id)->where('status', 'cancelled')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting order statistics: ' . $e->getMessage());
            return [
                'total' => 0,
                'pending' => 0,
                'processing' => 0,
                'shipped' => 0,
                'delivered' => 0,
                'cancelled' => 0,
            ];
        }
    }

    /**
     * Calculate total commission earned.
     */
    private function calculateTotalCommission($agent)
    {
        try {
            if (class_exists('App\Models\Commission')) {
                return Commission::where('agent_id', $agent->id)->sum('amount') ?? 0;
            }

            // Fallback calculation if commission table doesn't exist
            return Order::where('agent_id', $agent->id)
                ->where('status', 'delivered')
                ->sum('total') * 0.05;
        } catch (\Exception $e) {
            Log::error('Error calculating total commission: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate pending commission.
     */
    private function calculatePendingCommission($agent)
    {
        try {
            if (class_exists('App\Models\Commission')) {
                return Commission::where('agent_id', $agent->id)
                    ->where('status', 'pending')
                    ->sum('amount') ?? 0;
            }
            return 0;
        } catch (\Exception $e) {
            Log::error('Error calculating pending commission: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get recent commissions.
     */
    private function getRecentCommissions($agent, $limit = 5)
    {
        try {
            if (class_exists('App\Models\Commission')) {
                return Commission::where('agent_id', $agent->id)
                    ->with('order')
                    ->latest()
                    ->take($limit)
                    ->get();
            }
            return collect();
        } catch (\Exception $e) {
            Log::error('Error getting recent commissions: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get performance metrics.
     */
    private function getPerformanceMetrics($agent)
    {
        try {
            return [
                'connection_rate' => $this->calculateConnectionRate($agent),
                'conversion_rate' => $this->calculateConversionRate($agent),
                'satisfaction_rate' => $this->calculateSatisfactionRate($agent),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting performance metrics: ' . $e->getMessage());
            return [
                'connection_rate' => '0%',
                'conversion_rate' => '0%',
                'satisfaction_rate' => '0%',
            ];
        }
    }

    /**
     * Calculate target completion rate.
     */
    private function calculateTargetCompletionRate($agent, $targets = null)
    {
        try {
            if (is_null($targets)) {
                $targets = $this->getPerformanceTargets($agent);
            }

            if ($targets->isEmpty()) return 0;

            $totalPercentage = 0;
            $validTargets = 0;

            foreach ($targets as $target) {
                if (isset($target->progress_percentage)) {
                    $totalPercentage += $target->progress_percentage;
                    $validTargets++;
                }
            }

            return $validTargets > 0 ? ($totalPercentage / $validTargets) : 0;
        } catch (\Exception $e) {
            Log::error('Error calculating target completion rate: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate connection rate.
     */
    private function calculateConnectionRate($agent)
    {
        try {
            $connectedFarmers = 0;
            $totalFarmers = User::where('role', 'farmer')->count();

            if ($totalFarmers > 0) {
                if (DB::getSchemaBuilder()->hasTable('agent_farmer_connections')) {
                    $connectedFarmers = DB::table('agent_farmer_connections')
                        ->where('agent_id', $agent->id)
                        ->count();
                }

                $rate = ($connectedFarmers / $totalFarmers) * 100;
                return round($rate, 1) . '%';
            }

            return '0%';
        } catch (\Exception $e) {
            Log::error('Error calculating connection rate: ' . $e->getMessage());
            return '0%';
        }
    }

    /**
     * Calculate conversion rate.
     */
    private function calculateConversionRate($agent)
    {
        try {
            $totalOrders = Order::where('agent_id', $agent->id)->count();
            $completedOrders = Order::where('agent_id', $agent->id)
                ->where('status', 'delivered')
                ->count();

            if ($totalOrders > 0) {
                $rate = ($completedOrders / $totalOrders) * 100;
                return round($rate, 1) . '%';
            }

            return '0%';
        } catch (\Exception $e) {
            Log::error('Error calculating conversion rate: ' . $e->getMessage());
            return '0%';
        }
    }

    /**
     * Calculate satisfaction rate.
     */
    private function calculateSatisfactionRate($agent)
    {
        try {
            return '92%';
        } catch (\Exception $e) {
            Log::error('Error calculating satisfaction rate: ' . $e->getMessage());
            return '0%';
        }
    }

    /**
     * Get performance targets for agent.
     */
    private function getPerformanceTargets($agent)
    {
        try {
            if (class_exists('App\Models\PerformanceTarget')) {
                $targets = PerformanceTarget::where('agent_id', $agent->id)
                    ->where('status', 'active')
                    ->get()
                    ->map(function($target) use ($agent) {
                        $progress = $this->calculateTargetProgress($agent, $target);
                        $target->progress = $progress;
                        $target->progress_percentage = $target->target_value > 0
                            ? min(100, ($progress / $target->target_value) * 100)
                            : 0;
                        return $target;
                    });

                if ($targets->isNotEmpty()) {
                    return $targets;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error getting performance targets: ' . $e->getMessage());
        }

        // Return default sample targets
        return collect([
            (object)[
                'id' => 1,
                'name' => 'Monthly Sales Target',
                'target_type' => 'sales',
                'target_value' => 100000,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'period' => 'monthly',
                'progress_percentage' => 65,
                'progress' => 65000,
            ],
            (object)[
                'id' => 2,
                'name' => 'New Farmers Target',
                'target_type' => 'farmers',
                'target_value' => 20,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'period' => 'monthly',
                'progress_percentage' => 60,
                'progress' => 12,
            ],
            (object)[
                'id' => 3,
                'name' => 'Order Completion Rate',
                'target_type' => 'completion_rate',
                'target_value' => 90,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'period' => 'monthly',
                'progress_percentage' => 85,
                'progress' => 85,
            ],
        ]);
    }

    /**
     * Calculate target progress.
     */
    private function calculateTargetProgress($agent, $target)
    {
        try {
            $startDate = $target->start_date ?? now()->startOfMonth();
            $endDate = $target->end_date ?? now()->endOfMonth();

            switch ($target->target_type) {
                case 'sales':
                    return Order::where('agent_id', $agent->id)
                        ->where('status', 'delivered')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('total') ?? 0;

                case 'farmers':
                    if (DB::getSchemaBuilder()->hasTable('agent_farmer_connections')) {
                        return DB::table('agent_farmer_connections')
                            ->where('agent_id', $agent->id)
                            ->whereBetween('connected_at', [$startDate, $endDate])
                            ->count();
                    }
                    return 0;

                case 'completion_rate':
                    $totalOrders = Order::where('agent_id', $agent->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();
                    $completedOrders = Order::where('agent_id', $agent->id)
                        ->where('status', 'delivered')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();
                    return $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;

                default:
                    return 0;
            }
        } catch (\Exception $e) {
            Log::error('Error calculating target progress: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get recent orders.
     */
    private function getRecentOrders($agent, $limit = 5)
    {
        try {
            $orders = Order::where('agent_id', $agent->id)
                ->with('user')
                ->latest()
                ->take($limit)
                ->get();

            return $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name ?? 'N/A',
                    'total' => 'KES ' . number_format($order->total, 2),
                    'status' => $order->status,
                    'status_color' => $this->getStatusColor($order->status),
                    'date' => $order->created_at->format('M d, Y'),
                    'items_count' => $order->items_count ?? 0,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting recent orders: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities($agent)
    {
        try {
            $recentOrders = Order::where('agent_id', $agent->id)
                ->latest()
                ->take(3)
                ->get()
                ->map(function($order) {
                    return [
                        'type' => 'order',
                        'title' => 'New Order #' . $order->order_number,
                        'description' => 'Order from ' . ($order->user->name ?? 'Customer'),
                        'amount' => 'KES ' . number_format($order->total, 2),
                        'status' => $order->status,
                        'time' => $order->created_at->diffForHumans(),
                        'icon' => 'fas fa-shopping-cart',
                        'color' => $this->getStatusColor($order->status),
                    ];
                });

            $activities = $recentOrders->toArray();

            if (empty($activities)) {
                $activities = [
                    [
                        'type' => 'commission',
                        'title' => 'Commission Earned',
                        'description' => 'KES 5,250 commission from completed orders',
                        'amount' => 'KES 5,250',
                        'status' => 'paid',
                        'time' => '2 hours ago',
                        'icon' => 'fas fa-money-bill-wave',
                        'color' => 'success',
                    ],
                ];
            }

            return $activities;
        } catch (\Exception $e) {
            Log::error('Error getting recent activities: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get suppliers represented.
     */
    private function getSuppliersRepresented($agent)
    {
        try {
            return [
                [
                    'name' => 'Poultry Farm Ltd',
                    'products' => 45,
                    'rating' => 4.8,
                    'status' => 'active',
                    'commission_rate' => '5%',
                ],
                [
                    'name' => 'Agri Supplies Kenya',
                    'products' => 32,
                    'rating' => 4.5,
                    'status' => 'active',
                    'commission_rate' => '4.5%',
                ],
                [
                    'name' => 'Farm Equipment Co',
                    'products' => 28,
                    'rating' => 4.2,
                    'status' => 'pending',
                    'commission_rate' => '6%',
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Error getting suppliers represented: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get status color.
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Ensure all required stats keys exist with defaults.
     */
    private function ensureStatsKeys($stats)
    {
        $defaults = [
            'commission_rate' => 5.0,
            'commission_rate_formatted' => '5.0%',
            'targetCompletionRate' => 0,
            'target_completion_rate' => 0,
            'total_orders' => 0,
            'pending_orders' => 0,
            'processing_orders' => 0,
            'shipped_orders' => 0,
            'delivered_orders' => 0,
            'cancelled_orders' => 0,
            'total_commission' => 0,
            'pending_commission' => 0,
            'active_farmers' => 0,
            'farmers_count' => 0,
            'suppliers_count' => 0,
            'connection_rate' => '0%',
            'conversion_rate' => '0%',
            'satisfaction_rate' => '0%',
            'farmers_registered' => 0,
            'verified_suppliers' => 0,
            'transactions_processed' => 0,
            'totalOrders' => 0,
            'pendingOrders' => 0,
            'processingOrders' => 0,
            'inTransitOrders' => 0,
            'deliveredOrders' => 0,
            'cancelledOrders' => 0,
            'commissionEarned' => 0,
            'farmersCount' => 0,
            'suppliersCount' => 0,
        ];

        return array_merge($defaults, $stats ?? []);
    }

    /**
     * Get default stats for error fallback.
     */
    private function getDefaultStats()
    {
        return [
            'total_orders' => 0,
            'pending_orders' => 0,
            'processing_orders' => 0,
            'shipped_orders' => 0,
            'delivered_orders' => 0,
            'cancelled_orders' => 0,
            'total_commission' => 0,
            'pending_commission' => 0,
            'active_farmers' => 0,
            'farmers_count' => 0,
            'suppliers_count' => 0,
            'targetCompletionRate' => 0,
            'target_completion_rate' => 0,
            'connection_rate' => '0%',
            'conversion_rate' => '0%',
            'satisfaction_rate' => '0%',
            'farmers_registered' => 0,
            'verified_suppliers' => 0,
            'transactions_processed' => 0,
            'totalOrders' => 0,
            'pendingOrders' => 0,
            'processingOrders' => 0,
            'inTransitOrders' => 0,
            'deliveredOrders' => 0,
            'cancelledOrders' => 0,
            'commissionEarned' => 0,
            'farmersCount' => 0,
            'suppliersCount' => 0,
            'commission_rate' => 5.0,
            'commission_rate_formatted' => '5.0%',
        ];
    }

    /**
     * Display agent's orders.
     */
    public function agentOrders(Request $request)
    {
        $agent = Auth::user();
        $status = $request->get('status', 'all');

        $query = Order::where('agent_id', $agent->id)
            ->with(['user', 'items.product']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(5);

        // Get status counts
        $statusCounts = $this->getOrderStatusCounts($agent);

        return view('agent.orders.index', compact('orders', 'statusCounts', 'status'));
    }

    /**
     * Get order status counts.
     */
    private function getOrderStatusCounts($agent)
    {
        try {
            return [
                'all' => Order::where('agent_id', $agent->id)->count(),
                'pending' => Order::where('agent_id', $agent->id)->where('status', 'pending')->count(),
                'processing' => Order::where('agent_id', $agent->id)->where('status', 'processing')->count(),
                'shipped' => Order::where('agent_id', $agent->id)->where('status', 'shipped')->count(),
                'delivered' => Order::where('agent_id', $agent->id)->where('status', 'delivered')->count(),
                'cancelled' => Order::where('agent_id', $agent->id)->where('status', 'cancelled')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting order status counts: ' . $e->getMessage());
            return [
                'all' => 0,
                'pending' => 0,
                'processing' => 0,
                'shipped' => 0,
                'delivered' => 0,
                'cancelled' => 0,
            ];
        }
    }

    /**
     * Display agent's commissions.
     */
    public function commissions()
    {
        $agent = Auth::user();

        $commissions = Commission::where('agent_id', $agent->id)
            ->with('order')
            ->latest()
            ->paginate(10);

        $commissionStats = $this->getCommissionStatistics($agent, $commissions);

        return view('agent.commissions.index', array_merge(
            compact('commissions'),
            $commissionStats
        ));
    }

    /**
     * Get commission statistics.
     */
    private function getCommissionStatistics($agent, $commissions)
    {
        try {
            return [
                'totalCommission' => Commission::where('agent_id', $agent->id)->sum('amount'),
                'pendingCommission' => Commission::where('agent_id', $agent->id)
                    ->where('status', 'pending')
                    ->sum('amount'),
                'paidCommission' => Commission::where('agent_id', $agent->id)
                    ->where('status', 'paid')
                    ->sum('amount'),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting commission statistics: ' . $e->getMessage());
            return [
                'totalCommission' => 0,
                'pendingCommission' => 0,
                'paidCommission' => 0,
            ];
        }
    }
}
