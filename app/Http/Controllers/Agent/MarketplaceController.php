<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Marketplace;
use App\Models\User;
use App\Models\Order;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MarketplaceController extends Controller
{
    /**
     * Display agent marketplace.
     */
    public function agentMarketplace(Request $request)
    {
        $agent = Auth::user();

        // Initialize variables with default values
        $products = collect();
        $myProducts = collect();
        $farmers = collect();
        $allFarmers = collect();
        $totalProductsCount = 0;
        $totalCommission = 0;
        $pendingCommission = 0;
        $totalOrders = 0;

        try {
            // Get products for browsing (from all suppliers except agent's own)
            $productsQuery = Marketplace::verified()
                ->available()
                ->inStock()
                ->with('supplier')
                ->where('supplier_id', '!=', $agent->id);

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $productsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('tags', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category')) {
                $productsQuery->where('category', $request->category);
            }

            if ($request->filled('type')) {
                $productsQuery->where('product_type', $request->type);
            }

            if ($request->filled('location')) {
                $productsQuery->where('location', 'like', "%{$request->location}%");
            }

            if ($request->filled('min_price')) {
                $productsQuery->where('price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $productsQuery->where('price', '<=', $request->max_price);
            }

            $products = $productsQuery->orderBy('created_at', 'desc')->paginate(12);

            // Get agent's own products using supplier_id
            $myProducts = Marketplace::where('supplier_id', $agent->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get farmers with their products
            $farmers = User::where('role', 'farmer')
                ->where('id', '!=', $agent->id)
                ->withCount(['marketplaceProducts as total_products' => function($query) {
                    $query->where('is_verified', true)
                          ->where('is_available', true);
                }])
                ->with(['marketplaceProducts' => function($query) {
                    $query->where('is_verified', true)
                          ->where('is_available', true)
                          ->orderBy('created_at', 'desc')
                          ->limit(3);
                }])
                ->paginate(9);

            // Get all farmers for order modal
            $allFarmers = User::where('role', 'farmer')->get();

            // Calculate stats correctly
            $totalProductsCount = Marketplace::where('supplier_id', $agent->id)->count();

            // Commission calculations
            if (class_exists('App\Models\Commission')) {
                $totalCommission = Commission::where('agent_id', $agent->id)->sum('amount') ?? 0;
                $pendingCommission = Commission::where('agent_id', $agent->id)
                    ->where('status', 'pending')
                    ->sum('amount') ?? 0;
            }

            // Orders count
            if (class_exists('App\Models\Order')) {
                $totalOrders = Order::where('agent_id', $agent->id)->count();
            }

        } catch (\Exception $e) {
            // Log error but continue
            \Log::error('Marketplace controller error: ' . $e->getMessage());
        }

        return view('agent.marketplace.index', [
            'products' => $products,
            'myProducts' => $myProducts,
            'farmers' => $farmers,
            'allFarmers' => $allFarmers,
            'totalProducts' => $totalProductsCount,
            'totalCommission' => $totalCommission,
            'pendingCommission' => $pendingCommission,
            'totalOrders' => $totalOrders
        ]);
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'category' => 'required|string|max:50',
            'product_type' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'min_order' => 'nullable|integer|min:1',
            'max_order' => 'nullable|integer|min:1',
            'tags' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('marketplace/products', 'public');
        }

        // Create product
        $product = Marketplace::create([
            'supplier_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'category' => $request->category,
            'product_type' => $request->product_type,
            'location' => $request->location,
            'min_order' => $request->min_order,
            'max_order' => $request->max_order,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'image' => $imagePath,
            'is_verified' => false, // Agent products need verification
            'is_available' => $request->quantity > 0,
        ]);

        return redirect()->route('agent.marketplace.index')
            ->with('success', 'Product added successfully! It will be reviewed by our team.');
    }

    /**
     * Show product details.
     */
    public function show($id)
    {
        $product = Marketplace::with('supplier')->findOrFail($id);

        // Increment views
        $product->increment('views');

        // Get similar products
        $similarProducts = $product->similarProducts(4);

        return view('agent.marketplace.show', compact('product', 'similarProducts'));
    }

    /**
     * Edit product.
     */
    public function edit($id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        return view('agent.marketplace.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'category' => 'required|string|max:50',
            'product_type' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'min_order' => 'nullable|integer|min:1',
            'max_order' => 'nullable|integer|min:1',
            'tags' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('marketplace/products', 'public');
            $product->image = $imagePath;
        }

        // Update product
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'category' => $request->category,
            'product_type' => $request->product_type,
            'location' => $request->location,
            'min_order' => $request->min_order,
            'max_order' => $request->max_order,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'is_available' => $request->quantity > 0,
        ]);

        return redirect()->route('agent.marketplace.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product.
     */
    public function destroy($id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('agent.marketplace.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Get product details for AJAX.
     */
    public function getProductDetails($id)
    {
        $product = Marketplace::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'unit' => $product->unit,
                'quantity' => $product->quantity,
                'min_order' => $product->min_order,
                'max_order' => $product->max_order,
                'available_for_order' => $product->available_for_order,
            ]
        ]);
    }

    /**
     * Get product stock for AJAX.
     */
    public function getProductStock($id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'quantity' => $product->quantity,
                'available_for_order' => $product->available_for_order,
                'stock_status' => $product->stock_status,
            ]
        ]);
    }

    /**
     * Update product stock.
     */
    public function updateStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:marketplaces,id',
            'action' => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($request->product_id);

        $oldQuantity = $product->quantity;

        switch ($request->action) {
            case 'add':
                $product->increment('quantity', $request->quantity);
                break;
            case 'remove':
                if ($request->quantity > $product->quantity) {
                    return redirect()->back()
                        ->with('error', 'Cannot remove more than available stock.');
                }
                $product->decrement('quantity', $request->quantity);
                break;
            case 'set':
                $product->quantity = $request->quantity;
                $product->save();
                break;
        }

        // Update availability status
        if ($product->quantity > 0 && !$product->is_available) {
            $product->update(['is_available' => true]);
        } elseif ($product->quantity <= 0 && $product->is_available) {
            $product->update(['is_available' => false]);
        }

        // Log stock change (you can create a StockLog model for this)
        // StockLog::create([...]);

        return redirect()->route('agent.marketplace.index')
            ->with('success', 'Stock updated successfully!');
    }

    /**
     * Get farmers list.
     */
    public function getFarmers(Request $request)
    {
        $farmers = User::where('role', 'farmer')
            ->withCount(['marketplaceProducts as total_products' => function($query) {
                $query->where('is_verified', true)
                      ->where('is_available', true);
            }])
            ->with(['marketplaceProducts' => function($query) {
                $query->where('is_verified', true)
                      ->where('is_available', true)
                      ->orderBy('created_at', 'desc')
                      ->limit(5);
            }])
            ->paginate(12);

        return view('agent.farmers.index', compact('farmers'));
    }

    /**
     * Show farmer details.
     */
    public function showFarmer($id)
    {
        $farmer = User::where('role', 'farmer')
            ->withCount(['marketplaceProducts as total_products' => function($query) {
                $query->where('is_verified', true)
                      ->where('is_available', true);
            }])
            ->with(['marketplaceProducts' => function($query) {
                $query->where('is_verified', true)
                      ->where('is_available', true)
                      ->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        return view('agent.farmers.show', compact('farmer'));
    }

    /**
     * Show farmer's marketplace.
     */
    public function farmerMarketplace($id, Request $request)
    {
        $farmer = User::where('role', 'farmer')->findOrFail($id);

        $products = Marketplace::where('supplier_id', $id)
            ->verified()
            ->available()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                return $query->where('category', $request->category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('agent.farmers.marketplace', compact('farmer', 'products'));
    }

    /**
     * Create order for farmer (agent ordering for farmer).
     */
    public function createOrderForFarmer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:marketplaces,id',
            'farmer_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Marketplace::findOrFail($request->product_id);
        $farmer = User::findOrFail($request->farmer_id);

        // Check if product is available
        if (!$product->is_available || $product->quantity < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Product is not available in the requested quantity.');
        }

        // Check quantity limits
        if (!$product->canOrderQuantity($request->quantity)) {
            return redirect()->back()
                ->with('error', 'Quantity must be between ' . $product->min_order . ' and ' .
                      ($product->max_order ?: $product->quantity));
        }

        // Calculate total
        $total = $product->price * $request->quantity;

        // Create order
        $order = Order::create([
            'user_id' => $farmer->id,
            'agent_id' => Auth::id(),
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Add order item
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'total' => $total,
        ]);

        // Create commission record
        $commissionAmount = $total * 0.05; // 5% commission
        Commission::create([
            'agent_id' => Auth::id(),
            'order_id' => $order->id,
            'amount' => $commissionAmount,
            'percentage' => 5,
            'status' => 'pending',
        ]);

        // Update product quantity
        $product->decrement('quantity', $request->quantity);

        // Increment product orders count
        $product->increment('orders_count');

        return redirect()->route('agent.orders.show', $order->id)
            ->with('success', 'Order created successfully! You will earn KES ' .
                  number_format($commissionAmount) . ' commission.');
    }

    /**
     * Get farmer's products for AJAX.
     */
    public function getFarmerProducts($id)
    {
        $products = Marketplace::where('supplier_id', $id)
            ->verified()
            ->available()
            ->inStock()
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'unit' => $product->unit,
                    'quantity' => $product->quantity,
                    'image_url' => $product->thumbnail_url,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
