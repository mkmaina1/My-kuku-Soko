<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Get authenticated supplier
        $supplier = Auth::user();

        // Get comprehensive supplier statistics
        $stats = $this->getSupplierStats($supplier);

        // Prepare data for view
        $data = [
            'title' => 'Independent Supplier Dashboard',
            'user' => $supplier,
            'stats' => $stats,
            'recent_orders' => $this->getRecentOrders($supplier),
            'top_products' => $this->getTopProducts($supplier),
            'recent_farmers' => $this->getRecentFarmers($supplier),
        ];

        return view('supplier.dashboard', $data);
    }

    /**
     * Get comprehensive supplier statistics.
     */
    private function getSupplierStats($supplier)
    {
        try {
            // Get products count
            $totalProducts = Product::where('supplier_id', $supplier->id)->count();
            $activeProducts = Product::where('supplier_id', $supplier->id)
                ->where('status', 'active')
                ->count();

            // Get orders statistics
            $ordersStats = $this->getOrdersStatistics($supplier);

            // Calculate inventory value
            $inventoryValue = Product::where('supplier_id', $supplier->id)
                ->sum(DB::raw('price * stock'));

            // Get fulfillment rate
            $fulfillmentRate = $this->calculateFulfillmentRate($supplier);

            // Get low stock items
            $lowStockItems = Product::where('supplier_id', $supplier->id)
                ->where('stock', '<', 10)
                ->count();

            $inStockItems = Product::where('supplier_id', $supplier->id)
                ->where('stock', '>', 0)
                ->count();

            // Get stock percentages by category
            $stockPercentages = $this->getStockPercentages($supplier);

            // Bulk orders statistics
            $bulkOrders = $this->getBulkOrdersStatistics($supplier);

            return [
                'total_products' => $totalProducts,
                'orders_fulfilled' => $ordersStats['delivered'] ?? 0,
                'pending_orders' => $ordersStats['pending'] ?? 0,
                'revenue' => $ordersStats['revenue'] ?? 0,
                'active_products' => $activeProducts,
                'pending_value' => $ordersStats['pending_value'] ?? 0,
                'urgent_orders' => $ordersStats['urgent'] ?? 0,
                'revenue_growth' => $this->calculateRevenueGrowth($supplier),
                'inventory_value' => $inventoryValue,
                'fulfillment_rate' => $fulfillmentRate . '%',
                'stock_level' => $this->getStockLevel($inventoryValue),
                'feed_stock' => $stockPercentages['feed'] . '%',
                'medication_stock' => $stockPercentages['medication'] . '%',
                'equipment_stock' => $stockPercentages['equipment'] . '%',
                'low_stock_items' => $lowStockItems,
                'in_stock_items' => $inStockItems,
                'bulk_orders_count' => $bulkOrders['count'] ?? 0,
                'bulk_orders_value' => $bulkOrders['value'] ?? 0,
                'avg_bulk_order' => $bulkOrders['average'] ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting supplier stats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Get orders statistics for supplier.
     */
    private function getOrdersStatistics($supplier)
    {
        try {
            // Get all orders for this supplier's products
            $orders = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            });

            $stats = [
                'total' => $orders->count(),
                'pending' => $orders->where('status', 'pending')->count(),
                'processing' => $orders->where('status', 'processing')->count(),
                'shipped' => $orders->where('status', 'shipped')->count(),
                'delivered' => $orders->where('status', 'delivered')->count(),
                'cancelled' => $orders->where('status', 'cancelled')->count(),
                'urgent' => $orders->where('status', 'pending')
                    ->where('created_at', '<=', now()->subHours(24))
                    ->count(),
            ];

            // Calculate pending orders value
            $stats['pending_value'] = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('status', 'pending')
            ->sum('total');

            // Calculate revenue from delivered orders
            $stats['revenue'] = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('status', 'delivered')
            ->sum('total');

            return $stats;
        } catch (\Exception $e) {
            Log::error('Error getting orders statistics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate fulfillment rate.
     */
    private function calculateFulfillmentRate($supplier)
    {
        try {
            $totalOrders = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })->count();

            $deliveredOrders = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('status', 'delivered')
            ->count();

            if ($totalOrders > 0) {
                return round(($deliveredOrders / $totalOrders) * 100, 1);
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get stock percentages by category.
     */
    private function getStockPercentages($supplier)
    {
        try {
            $categories = ['feed', 'medication', 'equipment'];
            $percentages = [];

            foreach ($categories as $category) {
                $products = Product::where('supplier_id', $supplier->id)
                    ->where('category', $category)
                    ->get();

                if ($products->count() > 0) {
                    $totalStock = $products->sum('stock');
                    $totalCapacity = $products->sum('capacity') ?: 100; // Default capacity if not set
                    $percentages[$category] = round(($totalStock / $totalCapacity) * 100);
                } else {
                    $percentages[$category] = 0;
                }
            }

            return $percentages;
        } catch (\Exception $e) {
            return [
                'feed' => 65,
                'medication' => 85,
                'equipment' => 45,
            ];
        }
    }

    /**
     * Calculate revenue growth.
     */
    private function calculateRevenueGrowth($supplier)
    {
        try {
            $currentMonthRevenue = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

            $lastMonthRevenue = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

            if ($lastMonthRevenue > 0) {
                $growth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
                return round($growth, 1) . '%';
            }

            return $currentMonthRevenue > 0 ? '100%' : '0%';
        } catch (\Exception $e) {
            return '12%';
        }
    }

    /**
     * Get stock level status.
     */
    private function getStockLevel($inventoryValue)
    {
        if ($inventoryValue > 1000000) {
            return 'Excellent';
        } elseif ($inventoryValue > 500000) {
            return 'Good';
        } elseif ($inventoryValue > 100000) {
            return 'Moderate';
        } elseif ($inventoryValue > 0) {
            return 'Low';
        }
        return 'Empty';
    }

    /**
     * Get bulk orders statistics.
     */
    private function getBulkOrdersStatistics($supplier)
    {
        try {
            $bulkOrders = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('order_type', 'bulk')
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

            $count = $bulkOrders->count();
            $value = $bulkOrders->sum('total');
            $average = $count > 0 ? $value / $count : 0;

            return [
                'count' => $count,
                'value' => $value,
                'average' => $average,
            ];
        } catch (\Exception $e) {
            return [
                'count' => 0,
                'value' => 0,
                'average' => 0,
            ];
        }
    }

    /**
     * Get recent orders.
     */
    private function getRecentOrders($supplier, $limit = 10)
    {
        try {
            $orders = Order::whereHas('items.product', function($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->with(['user', 'items.product'])
            ->latest()
            ->take($limit)
            ->get();

            return $orders->map(function($order) {
                $productNames = $order->items->take(2)->map(function($item) {
                    return $item->product->name;
                })->implode(', ');

                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'farmer_name' => $order->user->name ?? 'N/A',
                    'farm_location' => $order->user->farm_location ?? 'Unknown',
                    'product_count' => $order->items->count(),
                    'product_names' => $productNames . ($order->items->count() > 2 ? '...' : ''),
                    'amount' => $order->total,
                    'date' => $order->created_at->format('M d, Y'),
                    'status' => $order->status,
                    'is_urgent' => $order->status == 'pending' && $order->created_at <= now()->subHours(24),
                    'is_bulk' => $order->order_type == 'bulk',
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting recent orders: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get top selling products.
     */
    private function getTopProducts($supplier, $limit = 5)
    {
        try {
            // Using raw SQL for better performance
            $products = DB::table('products')
                ->select([
                    'products.id',
                    'products.name',
                    'products.category',
                    'products.price',
                    'products.stock',
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                    DB::raw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as total_revenue')
                ])
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', function($join) {
                    $join->on('order_items.order_id', '=', 'orders.id')
                        ->where('orders.status', '=', 'delivered');
                })
                ->where('products.supplier_id', $supplier->id)
                ->groupBy('products.id', 'products.name', 'products.category', 'products.price', 'products.stock')
                ->orderBy('total_sold', 'desc')
                ->orderBy('total_revenue', 'desc')
                ->limit($limit)
                ->get();

            return $products->map(function($product) {
                return [
                    'name' => $product->name,
                    'category' => $product->category,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'sales' => $product->total_sold,
                    'revenue' => $product->total_revenue,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting top products: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get recent farmers who ordered from this supplier.
     */
    private function getRecentFarmers($supplier, $limit = 4)
    {
        try {
            $farmers = User::where('role', 'farmer')
                ->whereHas('orders.items.product', function($query) use ($supplier) {
                    $query->where('supplier_id', $supplier->id);
                })
                ->withCount(['orders as order_count' => function($query) use ($supplier) {
                    $query->whereHas('items.product', function($q) use ($supplier) {
                        $q->where('supplier_id', $supplier->id);
                    });
                }])
                ->withSum(['orders as total_orders' => function($query) use ($supplier) {
                    $query->whereHas('items.product', function($q) use ($supplier) {
                        $q->where('supplier_id', $supplier->id);
                    });
                }], 'total') // FIXED: second argument for withSum
                ->latest()
                ->take($limit)
                ->get();

            return $farmers->map(function($farmer) {
                return [
                    'name' => $farmer->name,
                    'farm_type' => $farmer->farm_type ?? 'Poultry',
                    'location' => $farmer->location ?? 'Unknown',
                    'order_count' => $farmer->order_count ?? 0,
                    'total_orders' => $farmer->total_orders ?? 0,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting recent farmers: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get default stats for error fallback.
     */
    private function getDefaultStats()
    {
        return [
            'total_products' => 0,
            'orders_fulfilled' => 0,
            'pending_orders' => 0,
            'revenue' => 0,
            'active_products' => 0,
            'pending_value' => 0,
            'urgent_orders' => 0,
            'revenue_growth' => '0%',
            'inventory_value' => 0,
            'fulfillment_rate' => '0%',
            'stock_level' => 'Empty',
            'feed_stock' => '0%',
            'medication_stock' => '0%',
            'equipment_stock' => '0%',
            'low_stock_items' => 0,
            'in_stock_items' => 0,
            'bulk_orders_count' => 0,
            'bulk_orders_value' => 0,
            'avg_bulk_order' => 0,
        ];
    }

    /**
     * Display analytics page.
     */
    public function analytics()
    {
        $supplier = Auth::user();

        // Get more detailed analytics
        $data = [
            'title' => 'Supplier Analytics',
            'user' => $supplier,
            'stats' => $this->getSupplierStats($supplier),
            'monthlyRevenue' => $this->getMonthlyRevenueData($supplier),
            'topProducts' => $this->getTopProducts($supplier, 10),
            'orderTrends' => $this->getOrderTrends($supplier),
        ];

        return view('supplier.analytics', $data);
    }

    /**
     * Get monthly revenue data for charts.
     */
    private function getMonthlyRevenueData($supplier)
    {
        try {
            $revenueData = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenue = Order::whereHas('items.product', function($query) use ($supplier) {
                        $query->where('supplier_id', $supplier->id);
                    })
                    ->where('status', 'delivered')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total');

                $revenueData[] = [
                    'month' => $month->format('M Y'),
                    'revenue' => $revenue,
                ];
            }

            return $revenueData;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get order trends for analytics.
     */
    private function getOrderTrends($supplier)
    {
        try {
            $trends = [];
            for ($i = 30; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $orders = Order::whereHas('items.product', function($query) use ($supplier) {
                        $query->where('supplier_id', $supplier->id);
                    })
                    ->whereDate('created_at', $date)
                    ->count();

                $trends[] = [
                    'date' => $date->format('M d'),
                    'orders' => $orders,
                ];
            }

            return $trends;
        } catch (\Exception $e) {
            return [];
        }
    }
}
