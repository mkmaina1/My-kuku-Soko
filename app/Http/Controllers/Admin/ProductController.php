<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index(Request $request)
    {
        $query = Marketplace::with('supplier');

        // Filter by verification status
        if ($request->has('verification_status') && $request->verification_status !== 'all') {
            if ($request->verification_status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->verification_status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        // Filter by availability
        if ($request->has('availability') && $request->availability !== 'all') {
            if ($request->availability === 'available') {
                $query->where('is_available', true)->where('quantity', '>', 0);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('quantity', '<=', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        // Filter by product type
        if ($request->has('product_type') && $request->product_type !== 'all') {
            $query->where('product_type', $request->product_type);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supplier', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('business_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $products = $query->latest()->paginate(20);
        $productTypes = Marketplace::distinct()->pluck('product_type');

        return view('admin.products.index', compact('products', 'productTypes'));
    }

    /**
     * Display the specified product.
     */
    public function show(Marketplace $product)
    {
        $product->load('supplier', 'orderItems.order');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Marketplace $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Marketplace $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'is_verified' => 'boolean',
            'is_available' => 'boolean',
            'min_order' => 'required|integer|min:1',
            'max_order' => 'nullable|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Marketplace $product)
    {
        // Delete product image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Verify a product.
     */
    public function verify(Marketplace $product)
    {
        $product->update(['is_verified' => true]);

        return back()->with('success', 'Product verified successfully!');
    }

    /**
     * Unverify a product.
     */
    public function unverify(Marketplace $product)
    {
        $product->update(['is_verified' => false]);

        return back()->with('success', 'Product verification removed!');
    }

    /**
     * Toggle product availability.
     */
    public function toggleAvailability(Marketplace $product)
    {
        $product->update(['is_available' => !$product->is_available]);

        $status = $product->is_available ? 'available' : 'unavailable';
        return back()->with('success', "Product marked as {$status}!");
    }

    /**
     * Bulk actions for products.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,delete,make_available,make_unavailable',
            'products' => 'required|array',
            'products.*' => 'exists:marketplaces,id',
        ]);

        $products = Marketplace::whereIn('id', $request->products)->get();

        switch ($request->action) {
            case 'verify':
                foreach ($products as $product) {
                    $product->update(['is_verified' => true]);
                }
                $message = 'Selected products have been verified!';
                break;

            case 'unverify':
                foreach ($products as $product) {
                    $product->update(['is_verified' => false]);
                }
                $message = 'Selected products have been unverified!';
                break;

            case 'make_available':
                foreach ($products as $product) {
                    $product->update(['is_available' => true]);
                }
                $message = 'Selected products are now available!';
                break;

            case 'make_unavailable':
                foreach ($products as $product) {
                    $product->update(['is_available' => false]);
                }
                $message = 'Selected products are now unavailable!';
                break;

            case 'delete':
                foreach ($products as $product) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $product->delete();
                }
                $message = 'Selected products have been deleted!';
                break;
        }

        return back()->with('success', $message);
    }
}
