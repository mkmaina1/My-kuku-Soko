<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\SupplierConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $farmer = Auth::user();

        // Get comprehensive farmer statistics
        $stats = $this->getFarmerStats($farmer);

        // Prepare data for view
        $data = [
            'title' => 'Farmer Dashboard',
            'user' => $farmer,
            'stats' => $stats,
            'recent_orders' => $this->getRecentOrders($farmer),
            'poultry_health' => $this->getPoultryHealth($farmer),
            'supplier_recommendations' => $this->getSupplierRecommendations($farmer),
            'upcoming_tasks' => $this->getUpcomingTasks($farmer),
        ];

        return view('farmer.dashboard', $data);
    }

    /**
     * Get comprehensive farmer statistics.
     */
    private function getFarmerStats($farmer)
    {
        try {
            // Get farmer orders
            $orders = Order::where('user_id', $farmer->id);

            // Get cart items count
            $cartItemsCount = $this->getCartItemsCount($farmer);

            // Get connected suppliers count
            $connectedSuppliers = $this->getConnectedSuppliersCount($farmer);

            // Get connected agents count
            $connectedAgents = $this->getConnectedAgentsCount($farmer);

            // Calculate monthly revenue (if you have sales/order data)
            $monthlyRevenue = $this->calculateMonthlyRevenue($farmer);

            // Default values for demo/fallback
            $defaultStats = [
                'poultry_count' => 0,
                'egg_production_today' => 0,
                'feed_remaining' => '0 days',
                'health_score' => '0%',
                'revenue_growth' => '0%',
                'expenses_this_month' => 0,
                'profit_margin' => '0%',
            ];

            // Merge real data with defaults
            return array_merge($defaultStats, [
                'total_livestock' => $this->getLivestockCount($farmer),
                'pending_orders' => $orders->where('status', 'pending')->count(),
                'suppliers_connected' => $connectedSuppliers,
                'monthly_revenue' => $monthlyRevenue,
                'cart_items_count' => $cartItemsCount,
                'pending_vet_requests_count' => 0, // Implement if you have vet requests
                'connected_suppliers_count' => $connectedSuppliers,
                'connected_agents_count' => $connectedAgents,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting farmer stats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Get livestock count.
     */
    private function getLivestockCount($farmer)
    {
        try {
            // If you have a livestock model
            if (class_exists('App\Models\Livestock')) {
                return \App\Models\Livestock::where('farmer_id', $farmer->id)->count();
            }
            // Fallback for demo
            return 250;
        } catch (\Exception $e) {
            return 250;
        }
    }

    /**
     * Get cart items count.
     */
    private function getCartItemsCount($farmer)
    {
        try {
            if (class_exists('App\Models\Cart')) {
                return Cart::where('user_id', $farmer->id)
                    ->with('items')
                    ->count();
            }
            return 3; // Demo fallback
        } catch (\Exception $e) {
            return 3;
        }
    }

    /**
     * Get connected suppliers count.
     */
    private function getConnectedSuppliersCount($farmer)
    {
        try {
            if (class_exists('App\Models\SupplierConnection')) {
                return SupplierConnection::where('farmer_id', $farmer->id)
                    ->where('status', 'connected')
                    ->count();
            }
            return 5; // Demo fallback
        } catch (\Exception $e) {
            return 5;
        }
    }

    /**
     * Get connected agents count.
     */
    private function getConnectedAgentsCount($farmer)
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('agent_farmer_connections')) {
                return DB::table('agent_farmer_connections')
                    ->where('farmer_id', $farmer->id)
                    ->where('status', 'connected')
                    ->count();
            }
            return 2; // Demo fallback
        } catch (\Exception $e) {
            return 2;
        }
    }

    /**
     * Calculate monthly revenue.
     */
    private function calculateMonthlyRevenue($farmer)
    {
        try {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();

            return Order::where('user_id', $farmer->id)
                ->where('status', 'delivered')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total') ?? 150000;
        } catch (\Exception $e) {
            return 150000; // Demo fallback
        }
    }

    /**
     * Get recent orders.
     */
    private function getRecentOrders($farmer, $limit = 5)
    {
        try {
            return Order::where('user_id', $farmer->id)
                ->with(['items.product', 'agent'])
                ->latest()
                ->take($limit)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total' => 'KES ' . number_format($order->total, 2),
                        'status' => $order->status,
                        'status_color' => $this->getOrderStatusColor($order->status),
                        'date' => $order->created_at->format('M d, Y'),
                        'agent_name' => $order->agent->name ?? 'Direct',
                        'items_count' => $order->items->count(),
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error getting recent orders: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get poultry health data.
     */
    private function getPoultryHealth($farmer)
    {
        try {
            // If you have a poultry health monitoring system
            return [
                [
                    'batch' => 'Batch A',
                    'health_score' => 92,
                    'egg_production' => 120,
                    'feed_consumption' => 'Normal',
                    'status' => 'healthy',
                ],
                [
                    'batch' => 'Batch B',
                    'health_score' => 85,
                    'egg_production' => 95,
                    'feed_consumption' => 'Low',
                    'status' => 'warning',
                ],
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get supplier recommendations.
     */
    private function getSupplierRecommendations($farmer)
    {
        try {
            // Get top recommended suppliers
            return [
                [
                    'name' => 'Poultry Feed Experts',
                    'rating' => 4.8,
                    'products' => 'Premium Feed & Supplements',
                    'delivery_time' => '2-3 days',
                    'commission_rate' => '5%',
                ],
                [
                    'name' => 'Vet Care Kenya',
                    'rating' => 4.9,
                    'products' => 'Vaccines & Medications',
                    'delivery_time' => '1-2 days',
                    'commission_rate' => '3%',
                ],
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get upcoming tasks.
     */
    private function getUpcomingTasks($farmer)
    {
        try {
            return [
                [
                    'title' => 'Vaccination Schedule',
                    'due_date' => 'Tomorrow',
                    'priority' => 'high',
                    'icon' => 'fas fa-syringe',
                ],
                [
                    'title' => 'Feed Restocking',
                    'due_date' => 'In 3 days',
                    'priority' => 'medium',
                    'icon' => 'fas fa-seedling',
                ],
                [
                    'title' => 'Egg Collection',
                    'due_date' => 'Daily',
                    'priority' => 'low',
                    'icon' => 'fas fa-egg',
                ],
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get order status color.
     */
    private function getOrderStatusColor($status)
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
     * Get default stats for error fallback.
     */
    private function getDefaultStats()
    {
        return [
            'total_livestock' => 0,
            'pending_orders' => 0,
            'suppliers_connected' => 0,
            'monthly_revenue' => 0,
            'poultry_count' => 0,
            'cart_items_count' => 0,
            'pending_vet_requests_count' => 0,
            'connected_suppliers_count' => 0,
            'connected_agents_count' => 0,
            'egg_production_today' => 0,
            'feed_remaining' => '0 days',
            'health_score' => '0%',
            'revenue_growth' => '0%',
            'expenses_this_month' => 0,
            'profit_margin' => '0%',
        ];
    }

    /**
     * Display farmer's orders.
     */
    public function orders(Request $request)
    {
        $farmer = Auth::user();
        $status = $request->get('status', 'all');

        $query = Order::where('user_id', $farmer->id)
            ->with(['items.product', 'agent']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);

        // Get status counts
        $counts = $this->getOrderCounts($farmer);

        return view('farmer.orders.index', compact('orders', 'counts', 'status'));
    }

    /**
     * Get order counts by status.
     */
    private function getOrderCounts($farmer)
    {
        try {
            return [
                'all' => Order::where('user_id', $farmer->id)->count(),
                'pending' => Order::where('user_id', $farmer->id)->where('status', 'pending')->count(),
                'processing' => Order::where('user_id', $farmer->id)->where('status', 'processing')->count(),
                'shipped' => Order::where('user_id', $farmer->id)->where('status', 'shipped')->count(),
                'delivered' => Order::where('user_id', $farmer->id)->where('status', 'delivered')->count(),
                'cancelled' => Order::where('user_id', $farmer->id)->where('status', 'cancelled')->count(),
            ];
        } catch (\Exception $e) {
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
     * Display single order.
     */
    public function showOrder($id)
    {
        $farmer = Auth::user();

        $order = Order::where('user_id', $farmer->id)
            ->with(['items.product', 'agent', 'transactions'])
            ->findOrFail($id);

        return view('farmer.orders.show', compact('order'));
    }

    /**
     * Cancel order.
     */
    public function cancelOrder(Request $request, $id)
    {
        $farmer = Auth::user();

        $order = Order::where('user_id', $farmer->id)
            ->whereIn('status', ['pending', 'processing'])
            ->findOrFail($id);

        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('cancellation_reason'),
            'cancelled_at' => now(),
        ]);

        // You might want to refund payment here if already paid

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Reorder from previous order.
     */
    public function reorder($id)
    {
        $farmer = Auth::user();

        $order = Order::where('user_id', $farmer->id)
            ->whereIn('status', ['delivered', 'cancelled'])
            ->with('items.product')
            ->findOrFail($id);

        // Add items to cart
        foreach ($order->items as $item) {
            Cart::updateOrCreate(
                [
                    'user_id' => $farmer->id,
                    'product_id' => $item->product_id,
                ],
                [
                    'quantity' => DB::raw('quantity + ' . $item->quantity),
                    'price' => $item->product->price,
                ]
            );
        }

        return redirect()->route('farmer.cart.index')->with('success', 'Items added to cart.');
    }
}
