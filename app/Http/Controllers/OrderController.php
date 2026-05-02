<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\MpesaPayment;
use App\Models\User;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Display orders with status filtering
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get counts for all statuses
        $counts = [
            'all' => $user->orders()->count(),
            'pending' => $user->orders()->where('status', 'pending')->count(),
            'processing' => $user->orders()->where('status', 'processing')->count(),
            'shipped' => $user->orders()->where('status', 'shipped')->count(),
            'delivered' => $user->orders()->where('status', 'delivered')->count(),
            'cancelled' => $user->orders()->where('status', 'cancelled')->count(),
        ];

        // Get status from request or default to 'all'
        $status = $request->get('status', 'all');

        // Get orders based on status filter
        $orders = $user->orders()->with(['items.product']);

        if ($status !== 'all') {
            $orders = $orders->where('status', $status);
        }

        $orders = $orders->latest()->paginate(10);

        return view('farmer.orders.index', compact('orders', 'counts', 'status'));
    }

    /**
     * Show checkout page
     */
    public function checkout()
{
    $user = Auth::user();

    // Get cart items - each row in carts table is a separate cart item
    $cartItems = Cart::where('user_id', $user->id)
        ->with('product')
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('farmer.cart.index')
            ->with('error', 'Your cart is empty.');
    }

    // Calculate totals
    $subtotal = $cartItems->sum(function ($item) {
        return $item->quantity * $item->price;
    });

    $shipping = 200; // Fixed shipping cost
    $tax = round($subtotal * 0.16, 2);
    $total = $subtotal + $shipping + $tax;

    // Create a cart object that mimics the structure your view expects
    $cart = (object) [
        'items' => $cartItems,
        'subtotal' => $subtotal,
        'shipping_cost' => $shipping,
        'tax' => $tax,
        'total' => $total,
    ];

    $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

    return view('farmer.orders.checkout', compact('cart', 'addresses'));
}

/**
 * Process checkout and initiate payment
 */
public function processCheckout(Request $request)
{
    $request->validate([
        'shipping_address_id' => 'required|exists:addresses,id',
        'phone_number' => 'required|string',
        'notes' => 'nullable|string|max:500',
    ]);

    $user = Auth::user();

    // Get cart items
    $cartItems = Cart::where('user_id', $user->id)
        ->with('product')
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Your cart is empty.'
        ], 400);
    }

    // Get the shipping address
    $shippingAddress = \App\Models\Address::find($request->shipping_address_id);

    if (!$shippingAddress) {
        return response()->json([
            'success' => false,
            'message' => 'Shipping address not found.'
        ], 400);
    }

    // Format the address as a string
    $formattedAddress = $this->formatAddress($shippingAddress);

    // Calculate totals
    $subtotal = $cartItems->sum(function ($item) {
        return $item->quantity * $item->price;
    });

    $shipping = 200; // Fixed shipping cost
    $tax = round($subtotal * 0.16, 2);
    $total = $subtotal + $shipping + $tax;

    DB::beginTransaction();

    try {
        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => 'mpesa',
            'payment_status' => 'pending',
            'shipping_address' => $formattedAddress,
            'shipping_address_id' => $request->shipping_address_id,
            'notes' => $request->notes,
            'estimated_delivery' => now()->addDays(rand(3, 7)),
        ]);

        // Create order items and update stock
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'supplier_id' => $cartItem->product->supplier_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'total' => $cartItem->quantity * $cartItem->price,
            ]);

            // Update product stock
            $cartItem->product->decrement('quantity', $cartItem->quantity);
        }

        // Clear the cart
        Cart::where('user_id', $user->id)->delete();

        DB::commit();

        // Initiate M-Pesa payment
        $mpesaResult = $this->mpesaService->stkPush(
            $request->phone_number,
            $order->total,
            'ORDER' . $order->order_number,
            'Payment for Order #' . $order->order_number,
            $order->id
        );

        if ($mpesaResult['success']) {
            // Save payment record
            $payment = MpesaPayment::create([
                'checkout_request_id' => $mpesaResult['checkout_request_id'],
                'order_id' => $order->id,
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'amount' => $order->total,
                'account_reference' => 'ORDER' . $order->order_number,
                'transaction_desc' => 'Payment for Order #' . $order->order_number,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully. Check your phone to complete payment.',
                'order' => $order,
                'payment' => [
                    'checkout_request_id' => $payment->checkout_request_id,
                    'customer_message' => $mpesaResult['customer_message']
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'warning' => 'Order created but payment initiation failed. You can try paying later.',
                'order' => $order,
                'payment_error' => $mpesaResult['message']
            ]);
        }

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Failed to process order: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Format address for storage
 */
private function formatAddress($address)
{
    return implode(', ', array_filter([
        $address->address ?? $address->street,
        $address->city,
        $address->state ?? $address->county,
        $address->postal_code,
        $address->country ?? 'Kenya'
    ]));
}

    /**
     * Show order details
     */
    public function show($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.product', 'mpesaPayments', 'shippingAddress'])
            ->findOrFail($orderId);

        return view('farmer.orders.show', compact('order'));
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore product quantities
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        return redirect()->route('farmer.orders.index')->with('success', 'Order cancelled successfully.');
    }

    /**
     * Reorder
     */
    public function reorder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();

        foreach ($order->items as $item) {
            // Check if product is still available
            if (!$item->product || !$item->product->is_active) {
                continue;
            }

            // Check if product already in cart
            $existingCartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingCartItem) {
                // Update quantity
                $existingCartItem->quantity += $item->quantity;
                $existingCartItem->save();
            } else {
                // Add new item to cart
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }
        }

        return redirect()->route('farmer.cart.index')->with('success', 'Items added to cart successfully!');
    }

    /**
     * Track order
     */
    public function trackOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('farmer.orders.track', compact('order'));
    }

    /**
     * Assign an agent
     */
    private function assignAgent()
    {
        return User::where('role', 'agent')
            ->inRandomOrder()
            ->first()
            ?->id;
    }

    /**
     * Format address
     */
    // private function formatAddress($address)
    // {
    //     return implode(', ', array_filter([
    //         $address->street,
    //         $address->city,
    //         $address->county . ' County',
    //         $address->postal_code,
    //         'Kenya'
    //     ]));
    // }
    /**
 * Retry payment for failed order
 */
public function retryPayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'phone_number' => 'required|string',
    ]);

    $order = Order::where('user_id', Auth::id())
        ->whereIn('payment_status', ['failed', 'pending'])
        ->findOrFail($request->order_id);

    // Initiate new M-Pesa payment
    $mpesaResult = $this->mpesaService->stkPush(
        $request->phone_number,
        $order->total,
        'ORDER' . $order->order_number,
        'Payment for Order #' . $order->order_number,
        $order->id
    );

    if ($mpesaResult['success']) {
        $payment = MpesaPayment::create([
            'checkout_request_id' => $mpesaResult['checkout_request_id'],
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'phone_number' => $request->phone_number,
            'amount' => $order->total,
            'account_reference' => 'ORDER' . $order->order_number,
            'transaction_desc' => 'Payment for Order #' . $order->order_number,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment prompt sent',
            'payment' => [
                'checkout_request_id' => $payment->checkout_request_id,
                'amount' => $order->total
            ]
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => $mpesaResult['message'] ?? 'Failed to initiate payment'
    ], 500);
}
}
