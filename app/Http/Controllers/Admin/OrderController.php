<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'agent', 'items.product']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('tracking_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(7);
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();

        return view('admin.orders.index', compact('orders', 'totalRevenue', 'totalOrders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'agent', 'items.product.supplier', 'commission']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:50',
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
        ]);

        // If marked as delivered, set delivered_at timestamp
        if ($request->status === 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
        }

        // If cancelled, restore product quantities
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Order is already cancelled!');
        }

        $order->update(['status' => 'cancelled']);

        // Restore product quantities
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
        }

        return back()->with('success', 'Order cancelled successfully!');
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled or very old orders
        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled orders can be deleted!');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    /**
     * View order statistics.
     */
    public function statistics()
    {
        // Daily revenue for last 30 days
        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Orders by payment method
        $ordersByPayment = Order::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        // Top customers
        $topCustomers = User::where('role', 'farmer')
            ->withCount(['orders', 'orders as total_spent' => function($query) {
                $query->select(\DB::raw('SUM(total)'))
                    ->where('status', '!=', 'cancelled');
            }])
            ->having('orders_count', '>', 0)
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Top suppliers by orders
        $topSuppliers = User::where('role', 'supplier')
            ->withCount(['products', 'products as total_orders' => function($query) {
                $query->select(\DB::raw('SUM(orders_count)'));
            }])
            ->having('total_orders', '>', 0)
            ->orderBy('total_orders', 'desc')
            ->limit(10)
            ->get();

        return view('admin.orders.statistics', compact(
            'dailyRevenue',
            'ordersByStatus',
            'ordersByPayment',
            'topCustomers',
            'topSuppliers'
        ));
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Apply filters if any
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        $fileName = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Order Number',
                'Customer',
                'Customer Email',
                'Order Date',
                'Status',
                'Payment Method',
                'Subtotal',
                'Shipping',
                'Tax',
                'Total',
                'Items Count',
                'Tracking Number'
            ]);

            // Add data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->status,
                    $order->payment_method,
                    $order->subtotal,
                    $order->shipping,
                    $order->tax,
                    $order->total,
                    $order->items->count(),
                    $order->tracking_number ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
