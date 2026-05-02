<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogisticsController extends Controller
{
    /**
     * Display Deliveries Overview
     */
    public function deliveriesOverview()
    {
        // Get today's date
        $today = Carbon::today();

        // Get orders shipped today (using created_at or updated_at since no delivery_date)
        $todayDeliveries = Order::whereDate('created_at', $today)
            ->where('status', 'shipped')
            ->with(['user', 'agent'])
            ->orderBy('created_at')
            ->get();

        // Get deliveries for the next 7 days (using created_at + estimated delivery)
        $weekDeliveries = Order::whereBetween('created_at', [$today, $today->copy()->addDays(7)])
            ->where('status', 'shipped')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                // Format date for chart labels
                $item->formatted_date = Carbon::parse($item->date)->format('D, M j');
                return $item;
            });

        // Get delivery statistics
        $stats = [
            'today_deliveries' => $todayDeliveries->count(),
            'week_deliveries' => $weekDeliveries->sum('count'),
            'pending_deliveries' => Order::where('status', 'shipped')->count(),
            'completed_today' => Order::whereDate('delivered_at', $today)
                ->where('status', 'delivered')
                ->count(),
            'total_delivery_agents' => User::where('role', 'agent')->count(),
            'active_delivery_agents' => User::where('role', 'agent')
                ->whereHas('agentOrders', function($query) {
                    $query->where('status', 'shipped');
                })
                ->count(),
        ];

        // Get delivery performance metrics
        $performance = $this->calculateDeliveryPerformance();

        // Get geographical distribution of deliveries (using shipping_address)
        $deliveryLocations = $this->getDeliveryLocations();

        return view('admin.logistics.deliveries', compact(
            'todayDeliveries',
            'weekDeliveries',
            'stats',
            'performance',
            'deliveryLocations'
        ));
    }

    /**
     * Display Delayed Orders
     */
    public function delayedOrders()
    {
        // Get orders that are delayed (orders older than 3 days and not delivered)
        // Since we don't have delivery_date, we'll use created_at + 3 days as estimated delivery
        $delayedOrders = Order::where('created_at', '<', Carbon::today()->subDays(3))
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'cancelled')
            ->with(['user', 'agent'])
            ->orderBy('created_at')
            ->paginate(6);

        // Calculate delay statistics
        $stats = [
            'total_delayed' => Order::where('created_at', '<', Carbon::today()->subDays(3))
                ->where('status', '!=', 'delivered')
                ->where('status', '!=', 'cancelled')
                ->count(),
            'delayed_by_1_day' => Order::whereBetween('created_at', [Carbon::today()->subDays(4), Carbon::today()->subDays(3)])
                ->where('status', '!=', 'delivered')
                ->where('status', '!=', 'cancelled')
                ->count(),
            'delayed_by_2_3_days' => Order::whereBetween('created_at', [Carbon::today()->subDays(6), Carbon::today()->subDays(4)])
                ->where('status', '!=', 'delivered')
                ->where('status', '!=', 'cancelled')
                ->count(),
            'delayed_over_3_days' => Order::where('created_at', '<', Carbon::today()->subDays(6))
                ->where('status', '!=', 'delivered')
                ->where('status', '!=', 'cancelled')
                ->count(),
        ];

        // Get reasons for delays (if you have a delays table)
        $delayReasons = $this->getDelayReasons();

        // Get agent performance for delayed deliveries
        $agentPerformance = $this->getAgentDelayPerformance();

        return view('admin.logistics.delays', compact(
            'delayedOrders',
            'stats',
            'delayReasons',
            'agentPerformance'
        ));
    }

    /**
     * Display Completed Orders
     */
    public function completedOrders()
    {
        // Get recently completed orders (last 30 days)
        $completedOrders = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', Carbon::today()->subDays(30))
            ->with(['user', 'agent'])
            ->orderBy('delivered_at', 'desc')
            ->paginate(6);

        // Get completion statistics
        $stats = [
            'completed_today' => Order::whereDate('delivered_at', Carbon::today())
                ->where('status', 'delivered')
                ->count(),
            'completed_yesterday' => Order::whereDate('delivered_at', Carbon::yesterday())
                ->where('status', 'delivered')
                ->count(),
            'completed_this_week' => Order::whereBetween('delivered_at', [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()])
                ->where('status', 'delivered')
                ->count(),
            'completed_this_month' => Order::whereBetween('delivered_at', [Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()])
                ->where('status', 'delivered')
                ->count(),
            'avg_delivery_time' => $this->calculateAverageDeliveryTime(),
            'on_time_delivery_rate' => $this->calculateOnTimeDeliveryRate(),
        ];

        // Get completion trends
        $completionTrend = $this->getCompletionTrend();

        // Get top performing agents
        $topAgents = $this->getTopDeliveryAgents();

        // Get geographical completion data
        $completionByLocation = $this->getCompletionByLocation();

        return view('admin.logistics.completed', compact(
            'completedOrders',
            'stats',
            'completionTrend',
            'topAgents',
            'completionByLocation'
        ));
    }

    /**
     * Calculate delivery performance metrics
     */
    private function calculateDeliveryPerformance()
    {
        $last30Days = Carbon::today()->subDays(30);

        $totalOrders = Order::where('created_at', '>=', $last30Days)
            ->whereIn('status', ['delivered', 'shipped', 'cancelled'])
            ->count();

        $deliveredOrders = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', $last30Days)
            ->count();

        // Calculate on-time delivery (assuming 3 days is standard delivery time)
        $onTimeOrders = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', $last30Days)
            ->whereRaw('TIMESTAMPDIFF(DAY, created_at, delivered_at) <= 3')
            ->count();

        return [
            'delivery_success_rate' => $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0,
            'on_time_delivery_rate' => $deliveredOrders > 0 ? round(($onTimeOrders / $deliveredOrders) * 100, 1) : 0,
            'avg_delivery_time_days' => $this->calculateAverageDeliveryTime(),
            'customer_satisfaction' => '92%', // This would come from reviews/ratings
        ];
    }

    /**
     * Get delivery locations (parsing from shipping_address)
     */
    private function getDeliveryLocations()
    {
        try {
            // Try to extract locations from shipping_address
            // This is a simplified approach - you might need to adjust based on your address format
            $locations = collect();

            $orders = Order::where('status', 'shipped')
                ->select('shipping_address')
                ->take(100)
                ->get();

            $locationCounts = [];

            foreach ($orders as $order) {
                // Simple extraction - look for common Kenyan counties/cities
                $address = strtolower($order->shipping_address);

                // List of common Kenyan locations
                $kenyanLocations = [
                    'nairobi', 'mombasa', 'kisumu', 'nakuru', 'eldoret', 'thika',
                    'kakamega', 'kisii', 'meru', 'nanyuki', 'nyeri', 'embu',
                    'machakos', 'kitui', 'garissa', 'wajir', 'mandera', 'marsabit',
                    'isiolo', 'lamu', 'kilifi', 'taita taveta', 'tana river',
                    'kwale', 'turkana', 'west pokot', 'samburu', 'narok', 'kajiado',
                    'kericho', 'bomet', 'bungoma', 'busia', 'vihiga', 'siaya',
                    'homa bay', 'migori', 'nyamira', 'nyandarua', 'kiambu', 'muranga',
                    'kirinyaga'
                ];

                foreach ($kenyanLocations as $location) {
                    if (strpos($address, $location) !== false) {
                        if (!isset($locationCounts[$location])) {
                            $locationCounts[$location] = 0;
                        }
                        $locationCounts[$location]++;
                        break;
                    }
                }
            }

            // Convert to collection
            foreach ($locationCounts as $location => $count) {
                $locations->push((object)[
                    'location' => ucwords($location),
                    'deliveries' => $count
                ]);
            }

            return $locations->sortByDesc('deliveries')->take(10);

        } catch (\Exception $e) {
            \Log::error('Error getting delivery locations: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get delay reasons
     */
    private function getDelayReasons()
    {
        // Sample delay reasons - in a real app, this would come from a delays table
        return [
            'Weather Conditions' => 15,
            'Traffic Delays' => 25,
            'Vehicle Issues' => 10,
            'Address Issues' => 20,
            'Customer Not Available' => 30,
        ];
    }

    /**
     * Get agent delay performance
     */
    private function getAgentDelayPerformance()
    {
        return User::where('role', 'agent')
            ->withCount(['agentOrders' => function($query) {
                // Delayed = orders older than 3 days and not delivered
                $query->where('created_at', '<', Carbon::today()->subDays(3))
                      ->where('status', '!=', 'delivered')
                      ->where('status', '!=', 'cancelled');
            }])
            ->withCount(['agentOrders' => function($query) {
                $query->where('status', 'delivered');
            }])
            ->having('agent_orders_count', '>', 0)
            ->orderBy('agent_orders_count', 'desc')
            ->take(10)
            ->get()
            ->map(function($agent) {
                $totalOrders = $agent->agent_orders_count + $agent->agent_orders_count;
                $agent->delay_rate = $totalOrders > 0
                    ? round(($agent->agent_orders_count / $totalOrders) * 100, 1)
                    : 0;
                return $agent;
            });
    }

    /**
     * Calculate average delivery time
     */
    private function calculateAverageDeliveryTime()
    {
        $deliveredOrders = Order::where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->whereNotNull('created_at')
            ->take(100)
            ->get();

        if ($deliveredOrders->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($deliveredOrders as $order) {
            $deliveryTime = $order->delivered_at->diffInHours($order->created_at);
            $totalHours += $deliveryTime;
        }

        return round($totalHours / $deliveredOrders->count() / 24, 1); // Convert to days
    }

    /**
     * Calculate on-time delivery rate
     */
    private function calculateOnTimeDeliveryRate()
    {
        $deliveredOrders = Order::where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->whereNotNull('created_at')
            ->take(100)
            ->get();

        if ($deliveredOrders->isEmpty()) {
            return 0;
        }

        $onTimeCount = 0;
        foreach ($deliveredOrders as $order) {
            // Consider on-time if delivered within 3 days
            $deliveryDays = $order->delivered_at->diffInDays($order->created_at);
            if ($deliveryDays <= 3) {
                $onTimeCount++;
            }
        }

        return round(($onTimeCount / $deliveredOrders->count()) * 100, 1);
    }

    /**
     * Get completion trend
     */
    private function getCompletionTrend()
    {
        $trend = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Order::whereDate('delivered_at', $date)
                ->where('status', 'delivered')
                ->count();

            $trend->push([
                'date' => $date->format('M d'),
                'count' => $count,
                'day' => $date->format('D'),
            ]);
        }

        return $trend;
    }

    /**
     * Get top delivery agents
     */
    private function getTopDeliveryAgents()
    {
        return User::where('role', 'agent')
            ->withCount(['agentOrders' => function($query) {
                $query->where('status', 'delivered')
                      ->where('delivered_at', '>=', Carbon::today()->subDays(30));
            }])
            ->withSum(['agentOrders' => function($query) {
                $query->where('status', 'delivered')
                      ->where('delivered_at', '>=', Carbon::today()->subDays(30));
            }], 'total')
            ->orderBy('agent_orders_count', 'desc')
            ->take(10)
            ->get()
            ->map(function($agent) {
                // Calculate average delivery time for this agent
                $agentOrders = $agent->agentOrders()
                    ->where('status', 'delivered')
                    ->where('delivered_at', '>=', Carbon::today()->subDays(30))
                    ->whereNotNull('delivered_at')
                    ->whereNotNull('created_at')
                    ->get();

                if ($agentOrders->count() > 0) {
                    $totalHours = 0;
                    foreach ($agentOrders as $order) {
                        $totalHours += $order->delivered_at->diffInHours($order->created_at);
                    }
                    $agent->avg_delivery_time = round($totalHours / $agentOrders->count() / 24, 1) . ' days';
                } else {
                    $agent->avg_delivery_time = 'N/A';
                }

                $agent->satisfaction_rate = '95%'; // This would come from ratings
                return $agent;
            });
    }

    /**
     * Get completion by location
     */
    private function getCompletionByLocation()
    {
        try {
            // Similar to getDeliveryLocations but for completed orders
            $locations = collect();

            $orders = Order::where('status', 'delivered')
                ->where('delivered_at', '>=', Carbon::today()->subDays(30))
                ->select('shipping_address', 'created_at', 'delivered_at')
                ->take(100)
                ->get();

            $locationData = [];

            foreach ($orders as $order) {
                // Simple extraction - look for common Kenyan counties/cities
                $address = strtolower($order->shipping_address);

                // List of common Kenyan locations
                $kenyanLocations = [
                    'nairobi', 'mombasa', 'kisumu', 'nakuru', 'eldoret', 'thika',
                    'kakamega', 'kisii', 'meru', 'nanyuki', 'nyeri', 'embu',
                    'machakos', 'kitui', 'garissa', 'wajir', 'mandera', 'marsabit',
                    'isiolo', 'lamu', 'kilifi', 'taita taveta', 'tana river',
                    'kwale', 'turkana', 'west pokot', 'samburu', 'narok', 'kajiado',
                    'kericho', 'bomet', 'bungoma', 'busia', 'vihiga', 'siaya',
                    'homa bay', 'migori', 'nyamira', 'nyandarua', 'kiambu', 'muranga',
                    'kirinyaga'
                ];

                foreach ($kenyanLocations as $location) {
                    if (strpos($address, $location) !== false) {
                        if (!isset($locationData[$location])) {
                            $locationData[$location] = [
                                'completed' => 0,
                                'total_days' => 0,
                                'count' => 0
                            ];
                        }
                        $locationData[$location]['completed']++;

                        if ($order->created_at && $order->delivered_at) {
                            $deliveryDays = $order->delivered_at->diffInDays($order->created_at);
                            $locationData[$location]['total_days'] += $deliveryDays;
                            $locationData[$location]['count']++;
                        }
                        break;
                    }
                }
            }

            // Convert to collection
            foreach ($locationData as $location => $data) {
                $avgDays = $data['count'] > 0 ? $data['total_days'] / $data['count'] : 0;
                $locations->push((object)[
                    'location' => ucwords($location),
                    'completed' => $data['completed'],
                    'avg_days' => round($avgDays, 1)
                ]);
            }

            return $locations->sortByDesc('completed')->take(8);

        } catch (\Exception $e) {
            \Log::error('Error getting completion by location: ' . $e->getMessage());
            return collect();
        }
    }
}
