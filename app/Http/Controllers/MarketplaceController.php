<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MarketplaceController extends Controller
{
    // Public marketplace (accessible to all authenticated users)
public function index(Request $request)
{
    $query = Marketplace::where('is_available', true);

    // Apply filters
    if ($request->has('category')) {
        $query->where('category', $request->category);
    }

    if ($request->has('product_type')) {
        $query->where('product_type', $request->product_type);
    }

    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    if ($request->has('location')) {
        $query->where('location', 'like', '%' . $request->location . '%');
    }

    if ($request->has('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
            ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    // Apply sorting
    $sort = $request->get('sort', 'latest');
    switch ($sort) {
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        case 'popular':
            $query->orderBy('orders_count', 'desc');
            break;
        case 'rating':
            $query->orderBy('rating', 'desc');
            break;
        default:
            $query->latest();
    }

    $products = $query->paginate(12);

    $categories = Marketplace::distinct()->pluck('category');
    $productTypes = Marketplace::distinct()->pluck('product_type');
    $locations = Marketplace::distinct()->pluck('location');

    // Get cart count for authenticated users
    $cartCount = 0;
    if (Auth::check()) {
        $cartCount = Cart::where('user_id', Auth::id())->count();
    }

    return view('marketplace.index', compact('products', 'categories', 'productTypes', 'locations', 'cartCount'));
}

    public function show($id)
    {
        $product = Marketplace::findOrFail($id);

        // Increment view count
        $product->increment('views');

        // Get related products
        $relatedProducts = Marketplace::where('category', $product->category)
            ->where('id', '!=', $id)
            ->where('is_available', true)
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('product', 'relatedProducts'));
    }

    // Supplier-specific methods
    public function supplierProducts()
    {
        $products = Marketplace::where('supplier_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('supplier.marketplace.index', compact('products'));
    }

    public function create()
    {
        return view('supplier.marketplace.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:poultry,feed,medication,equipment,other',
            'product_type' => 'required|in:chicks,mature_birds,eggs,feed,medication,equipment,other',
            'breed' => 'nullable|string',
            'age' => 'nullable|numeric|min:0',
            'age_unit' => 'nullable|in:days,weeks,months',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|in:grams,kg',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:piece,dozen,tray,kg,bag,pack,carton,litre',
            'location' => 'required|string',
            'min_order' => 'required|numeric|min:1',
            'max_order' => 'nullable|numeric|min:1|gte:min_order',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['supplier_id'] = Auth::id();
        $data['is_available'] = $request->has('is_available');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('marketplace', 'public');
            $data['image'] = $path;
        }

        Marketplace::create($data);

        return redirect()->route('supplier.marketplace.index')
            ->with('success', 'Product created successfully!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    //          public function show($id)
    // {
    //     $product = Marketplace::findOrFail($id);
    //     return view('supplier.marketplace.show', compact('product'));
    // }


    public function edit($id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        return view('supplier.marketplace.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:poultry,feed,medication,equipment,other',
            'product_type' => 'required|in:chicks,mature_birds,eggs,feed,medication,equipment,other',
            'breed' => 'nullable|string',
            'age' => 'nullable|numeric|min:0',
            'age_unit' => 'nullable|in:days,weeks,months',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|in:grams,kg',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:piece,dozen,tray,kg,bag,pack,carton,litre',
            'location' => 'required|string',
            'min_order' => 'required|numeric|min:1',
            'max_order' => 'nullable|numeric|min:1|gte:min_order',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
            'is_available' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['is_available'] = $request->has('is_available');

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $data['image'] = null;
            }
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('marketplace', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('supplier.marketplace.index')
            ->with('success', 'Product updated successfully!');
    }
    public function destroy($id)
    {
        $product = Marketplace::where('supplier_id', Auth::id())
            ->findOrFail($id);

        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('supplier.marketplace.index')
            ->with('success', 'Product deleted successfully!');
    }

    // Cart functionality
    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Marketplace::findOrFail($id);

        // Check availability
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock!');
        }

        // Check min order
        if ($request->quantity < $product->min_order) {
            return back()->with('error', "Minimum order is {$product->min_order} {$product->unit}");
        }

        // Check max order
        if ($product->max_order && $request->quantity > $product->max_order) {
            return back()->with('error', "Maximum order is {$product->max_order} {$product->unit}");
        }

        // Add to cart
        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $id,
            ],
            [
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total' => $product->price * $request->quantity,
            ]
        );

        return back()->with('success', 'Product added to cart!');
    }

    public function removeFromCart($id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cart->delete();

        return back()->with('success', 'Product removed from cart!');
    }

    // View cart
    public function viewCart()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $subtotal = $cartItems->sum('total');
        $shipping = 500;
        $tax = $subtotal * 0.16;
        $total = $subtotal + $shipping + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    // Update cart item quantity
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $product = $cart->product;

        // Check availability
        if ($product->quantity < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock!'], 400);
        }

        // Check min order
        if ($request->quantity < $product->min_order) {
            return response()->json(['error' => "Minimum order is {$product->min_order}"], 400);
        }

        // Check max order
        if ($product->max_order && $request->quantity > $product->max_order) {
            return response()->json(['error' => "Maximum order is {$product->max_order}"], 400);
        }

        $cart->quantity = $request->quantity;
        $cart->total = $product->price * $request->quantity;
        $cart->save();

        return response()->json(['success' => true, 'new_total' => $cart->total]);
    }

    // Agent marketplace view (with commission)
    public function agentMarketplace()
    {
        $products = Marketplace::where('is_available', true)
            ->where('is_verified', true)
            ->latest()
            ->paginate(12);

        return view('agent.marketplace.index', compact('products'));
    }

    // Agent create order for farmer
    public function agentCreateOrder(Request $request, $id)
    {
        $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Marketplace::findOrFail($id);

        // Check if the selected user is a farmer
        $farmer = User::findOrFail($request->farmer_id);
        if ($farmer->role !== 'farmer') {
            return back()->with('error', 'Selected user must be a farmer.');
        }

        // Check availability
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock!');
        }

        // Check min order
        if ($request->quantity < $product->min_order) {
            return back()->with('error', "Minimum order is {$product->min_order} {$product->unit}");
        }

        // Check max order
        if ($product->max_order && $request->quantity > $product->max_order) {
            return back()->with('error', "Maximum order is {$product->max_order} {$product->unit}");
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = new Order();
            $order->user_id = $farmer->id;
            $order->agent_id = Auth::id();
            $order->order_number = 'ORD-' . strtoupper(uniqid());
            $order->shipping_address = $farmer->address ?? 'Not specified';
            $order->payment_method = 'agent';
            $order->notes = $request->notes;

            $subtotal = $product->price * $request->quantity;
            $shipping = 500;
            $tax = $subtotal * 0.16;

            $order->subtotal = $subtotal;
            $order->shipping = $shipping;
            $order->tax = $tax;
            $order->total = $subtotal + $shipping + $tax;

            $order->status = 'processing';
            $order->save();

            // Update product stock
            $product->decrement('quantity', $request->quantity);
            $product->increment('orders_count');

            // Create order item
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total' => $subtotal,
            ]);

            // Calculate agent commission (5% of subtotal)
            $commission = $subtotal * 0.05;

            // Store commission (you can create a Commission model later)
            \App\Models\Commission::create([
                'agent_id' => Auth::id(),
                'order_id' => $order->id,
                'amount' => $commission,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('agent.dashboard')
                ->with('success', 'Order created successfully for ' . $farmer->name . '! Order Number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    // Get farmers for agent to select
    public function getFarmers()
    {
        if (Auth::user()->role !== 'agent') {
            abort(403, 'Unauthorized');
        }

        $farmers = User::where('role', 'farmer')
            ->select('id', 'name', 'email', 'phone')
            ->get();

        return response()->json($farmers);
    }
}
