<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller  // Or SupplierInventoryController if you choose Option B
{
    /**
     * Display a listing of the marketplace products for supplier.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $products = Marketplace::where('supplier_id', $user->id)
            ->latest()
            ->paginate(5);

        // Get statistics
        $stats = [
            'total_products' => Marketplace::where('supplier_id', $user->id)->count(),
            'available' => Marketplace::where('supplier_id', $user->id)
                ->where('quantity', '>', 0)
                ->where('is_available', true)
                ->count(),
            'low_stock' => Marketplace::where('supplier_id', $user->id)
                ->where('quantity', '<=', 10)
                ->where('is_available', true)
                ->count(),
            'out_of_stock' => Marketplace::where('supplier_id', $user->id)
                ->where('quantity', '<=', 0)
                ->count(),
            'categories' => Marketplace::where('supplier_id', $user->id)
                ->distinct('category')
                ->count('category'),
            'total_value' => Marketplace::where('supplier_id', $user->id)
                ->sum(DB::raw('quantity * price')),
        ];

        $categories = Marketplace::where('supplier_id', $user->id)
            ->select('category')
            ->distinct()
            ->get();

        return view('supplier.inventory.index', compact('products', 'stats', 'categories'));
    }

    /**
     * Display low stock products.
     */
    public function lowStock(Request $request)
    {
        $user = Auth::user();

        $products = Marketplace::where('supplier_id', $user->id)
            ->where('quantity', '<=', 10)
            ->where('is_available', true)
            ->latest()
            ->paginate(5);

        $lowStockCount = Marketplace::where('supplier_id', $user->id)
            ->where('quantity', '<=', 10)
            ->where('is_available', true)
            ->count();

        return view('supplier.inventory.low-stock', compact('products', 'lowStockCount'));
    }

    /**
     * Display categories.
     */
    public function categories(Request $request)
    {
        $user = Auth::user();

        $categories = Marketplace::where('supplier_id', $user->id)
            ->select('category', DB::raw('count(*) as product_count'), DB::raw('sum(quantity) as total_quantity'))
            ->groupBy('category')
            ->latest()
            ->paginate(5);

        return view('supplier.inventory.categories', compact('categories'));
    }

    /**
     * Display expired products (not applicable for Marketplace, so redirect).
     */
    public function expired(Request $request)
    {
        // Since Marketplace doesn't have expiry_date, redirect to index
        return redirect()->route('supplier.inventory.index')
            ->with('info', 'Expiry tracking is not available for marketplace products.');
    }

    /**
     * These methods don't apply to Marketplace - redirect or remove
     */
    public function create()
    {
        // Redirect to marketplace create instead
        return redirect()->route('supplier.marketplace.create');
    }

    public function store(Request $request)
    {
        // Redirect to marketplace store instead
        return redirect()->route('supplier.marketplace.create');
    }

    public function show($id)
    {
        // Redirect to marketplace show
        return redirect()->route('marketplace.show', $id);
    }

    public function edit($id)
    {
        // Redirect to marketplace edit
        return redirect()->route('supplier.marketplace.edit', $id);
    }

    // public function update(Request $request, $id)
    // {
    //     // Redirect to marketplace update
    //     return redirect()->route('supplier.marketplace.edit', $id);
    // }

    // public function destroy($id)
    // {
    //     // Redirect to marketplace destroy
    //     return redirect()->route('supplier.marketplace.index');
    // }
}
