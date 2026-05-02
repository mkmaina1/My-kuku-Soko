<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders for the authenticated supplier.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get orders for this supplier's products
        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(7);

        // Get statistics
        $stats = [
            'total' => Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->count(),

            'pending' => Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->where('status', 'pending')->count(),

            'processing' => Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->where('status', 'processing')->count(),

            'shipped' => Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->where('status', 'shipped')->count(),

            'delivered' => Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->where('status', 'delivered')->count(),

            'cancelled' => Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->where('status', 'cancelled')->count(),
        ];

        // Calculate total revenue
        $totalRevenue = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('status', 'delivered')
            ->sum('total');

        return view('supplier.orders.index', compact('orders', 'stats', 'totalRevenue'));
    }

    /**
     * Display pending orders.
     */
    public function pending(Request $request)
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('status', 'pending')
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(5);

        return view('supplier.orders.index', [
            'orders' => $orders,
            'statusFilter' => 'pending',
            'title' => 'Pending Orders'
        ]);
    }

    /**
     * Display processing orders.
     */
    public function processing(Request $request)
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('status', 'processing')
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(5);

        return view('supplier.orders.index', [
            'orders' => $orders,
            'statusFilter' => 'processing',
            'title' => 'Processing Orders'
        ]);
    }

    /**
     * Display shipped orders.
     */
    public function shipped(Request $request)
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('status', 'shipped')
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(5);

        return view('supplier.orders.index', [
            'orders' => $orders,
            'statusFilter' => 'shipped',
            'title' => 'Shipped Orders'
        ]);
    }

    /**
     * Display delivered orders.
     */
    public function delivered(Request $request)
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('status', 'delivered')
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(5);

        return view('supplier.orders.index', [
            'orders' => $orders,
            'statusFilter' => 'delivered',
            'title' => 'Delivered Orders'
        ]);
    }

    /**
     * Display cancelled orders.
     */
    public function cancelled(Request $request)
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('status', 'cancelled')
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(5);

        return view('supplier.orders.index', [
            'orders' => $orders,
            'statusFilter' => 'cancelled',
            'title' => 'Cancelled Orders'
        ]);
    }

    /**
     * Display bulk orders.
     */
    public function bulk(Request $request)
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })
            ->where('order_type', 'bulk')
            ->with(['user', 'items.product']) // CHANGED: 'customer' to 'user'
            ->latest()
            ->paginate(5);

        return view('supplier.orders.index', [
            'orders' => $orders,
            'orderTypeFilter' => 'bulk',
            'title' => 'Bulk Orders'
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if the order belongs to this supplier
        $user = Auth::user();
        $supplierOrder = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('supplier_id', $user->id);
        })->first();

        if (!$supplierOrder) {
            abort(404, 'Order not found or you do not have permission to view it.');
        }

        $order->load(['user', 'items.product', 'shippingAddress']); // CHANGED: 'customer' to 'user'

        return view('supplier.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // Check if the order belongs to this supplier
        $user = Auth::user();
        $supplierOrder = $order->items()->whereHas('product', function($query) use ($user) {
            $query->where('supplier_id', $user->id);
        })->first();

        if (!$supplierOrder) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->update([
            'status' => $request->status,
            'status_updated_at' => now(),
        ]);

        // Add status history
        $order->statusHistories()->create([
            'status' => $request->status,
            'notes' => $request->notes ?? 'Status updated by supplier',
            'changed_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }

    /**
     * Bulk update order status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $user = Auth::user();
        $updatedCount = 0;

        foreach ($request->order_ids as $orderId) {
            $order = Order::find($orderId);

            // Check if the order belongs to this supplier
            $supplierOrder = $order->items()->whereHas('product', function($query) use ($user) {
                $query->where('supplier_id', $user->id);
            })->first();

            if ($supplierOrder) {
                $order->update([
                    'status' => $request->status,
                    'status_updated_at' => now(),
                ]);

                // Add status history
                $order->statusHistories()->create([
                    'status' => $request->status,
                    'notes' => 'Status updated via bulk action',
                    'changed_by' => $user->id,
                ]);

                $updatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully updated {$updatedCount} order(s) status",
            'updated_count' => $updatedCount
        ]);
    }
}
