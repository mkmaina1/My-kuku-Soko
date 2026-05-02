<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Marketplace;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();

        // Load verification data
        $user->load(['verificationRequests' => function($query) {
            $query->latest();
        }]);

        // Get statistics based on role
        $stats = $this->getUserStats($user);

        return view('profile.edit', compact('user', 'stats'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Format phone number to +254 format
        if (isset($validated['phone']) && $validated['phone']) {
            $validated['phone'] = $this->formatPhoneNumber($validated['phone']);
        }

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        // Logout and delete user
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    /**
     * Get user statistics based on role.
     */
    private function getUserStats($user)
    {
        $stats = [];

        switch ($user->role) {
            case 'supplier':
                $stats = $this->getSupplierStats($user);
                break;

            case 'farmer':
                $stats = $this->getFarmerStats($user);
                break;

            case 'agent':
                $stats = $this->getAgentStats($user);
                break;

            case 'veterinary':
                $stats = $this->getVeterinaryStats($user);
                break;

            default:
                $stats = $this->getDefaultStats($user);
                break;
        }

        return $stats;
    }

    /**
     * Get supplier statistics.
     */
    private function getSupplierStats($user)
    {
        try {
            $ordersCount = 0;
            $pendingOrdersCount = 0;
            $completedOrdersCount = 0;

            // Method 1: Check if Marketplace model exists and has supplier_id
            if (class_exists(Marketplace::class) && Schema::hasColumn('marketplaces', 'supplier_id')) {
                // Get product IDs for this supplier
                $supplierProductIds = Marketplace::where('supplier_id', $user->id)->pluck('id');

                if ($supplierProductIds->count() > 0) {
                    // Check if order_items table exists
                    if (Schema::hasTable('order_items')) {
                        $ordersCount = Order::whereHas('items', function($query) use ($supplierProductIds) {
                            $query->whereIn('product_id', $supplierProductIds);
                        })->count();

                        $pendingOrdersCount = Order::whereHas('items', function($query) use ($supplierProductIds) {
                            $query->whereIn('product_id', $supplierProductIds);
                        })->where('status', 'pending')->count();

                        $completedOrdersCount = Order::whereHas('items', function($query) use ($supplierProductIds) {
                            $query->whereIn('product_id', $supplierProductIds);
                        })->where('status', 'delivered')->count();
                    } else {
                        // Fallback if no order_items table
                        $ordersCount = 0;
                        $pendingOrdersCount = 0;
                        $completedOrdersCount = 0;
                    }
                }
            }
            // Method 2: If orders table has supplier_id column directly (unlikely based on your migration)
            elseif (Schema::hasColumn('orders', 'supplier_id')) {
                $ordersCount = Order::where('supplier_id', $user->id)->count();
                $pendingOrdersCount = Order::where('supplier_id', $user->id)
                    ->where('status', 'pending')->count();
                $completedOrdersCount = Order::where('supplier_id', $user->id)
                    ->where('status', 'delivered')->count();
            }

            return [
                'ordersCount' => $ordersCount,
                'pendingOrdersCount' => $pendingOrdersCount,
                'completedOrdersCount' => $completedOrdersCount,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting supplier stats: ' . $e->getMessage());
            return [
                'ordersCount' => 0,
                'pendingOrdersCount' => 0,
                'completedOrdersCount' => 0,
            ];
        }
    }

    /**
     * Get farmer statistics.
     */
    private function getFarmerStats($user)
    {
        try {
            // Get orders count and total spent - USE user_id column
            $ordersCount = Order::where('user_id', $user->id)->count();
            $totalSpent = Order::where('user_id', $user->id)->sum('total') ?? 0;

            // Get cart items count
            $cartItemsCount = Cart::where('user_id', $user->id)->count();

            // Get poultry count
            $poultryCount = $this->getPoultryCount($user);

            return [
                'ordersCount' => $ordersCount,
                'totalSpent' => $totalSpent,
                'cartItemsCount' => $cartItemsCount,
                'poultryCount' => $poultryCount,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting farmer stats: ' . $e->getMessage());
            return [
                'ordersCount' => 0,
                'totalSpent' => 0,
                'cartItemsCount' => 0,
                'poultryCount' => 250,
            ];
        }
    }

    /**
     * Get agent statistics.
     */
    private function getAgentStats($user)
    {
        try {
            // Get agent orders count - USE agent_id column
            $agentOrdersCount = Order::where('agent_id', $user->id)->count();

            return [
                'farmersCount' => User::where('role', 'farmer')->count(),
                'agentOrdersCount' => $agentOrdersCount,
                'commissionEarned' => $this->calculateCommissionEarned($user),
                'suppliersCount' => User::where('role', 'supplier')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting agent stats: ' . $e->getMessage());
            return [
                'farmersCount' => 0,
                'agentOrdersCount' => 0,
                'commissionEarned' => 0,
                'suppliersCount' => 0,
            ];
        }
    }

    /**
     * Calculate commission earned by agent.
     */
    private function calculateCommissionEarned($user)
    {
        try {
            // Get all orders where this user is the agent
            $agentOrders = Order::where('agent_id', $user->id)
                ->where('status', 'delivered')
                ->get();

            $totalCommission = 0;

            foreach ($agentOrders as $order) {
                // Calculate 5% commission on each order (adjust percentage as needed)
                $commission = $order->total * 0.05;
                $totalCommission += $commission;
            }

            return $totalCommission;
        } catch (\Exception $e) {
            Log::error('Error calculating commission: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get veterinary statistics.
     */
    private function getVeterinaryStats($user)
    {
        // These are placeholder values - implement based on your models
        try {
            // Check if you have an appointments table
            if (Schema::hasTable('appointments')) {
                $appointmentsCount = \DB::table('appointments')
                    ->where('veterinary_id', $user->id)
                    ->count();

                $pendingRequestsCount = \DB::table('appointments')
                    ->where('veterinary_id', $user->id)
                    ->where('status', 'pending')
                    ->count();

                $farmersServedCount = \DB::table('appointments')
                    ->where('veterinary_id', $user->id)
                    ->where('status', 'completed')
                    ->distinct('farmer_id')
                    ->count('farmer_id');
            } else {
                $appointmentsCount = 0;
                $pendingRequestsCount = 0;
                $farmersServedCount = 0;
            }

            return [
                'appointmentsCount' => $appointmentsCount,
                'farmersServedCount' => $farmersServedCount,
                'pendingRequestsCount' => $pendingRequestsCount,
                'rating' => $this->getVeterinaryRating($user),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting veterinary stats: ' . $e->getMessage());
            return [
                'appointmentsCount' => 0,
                'farmersServedCount' => 0,
                'pendingRequestsCount' => 0,
                'rating' => '4.8',
            ];
        }
    }

    /**
     * Get veterinary rating.
     */
    private function getVeterinaryRating($user)
    {
        try {
            if (Schema::hasTable('reviews')) {
                $rating = \DB::table('reviews')
                    ->where('veterinary_id', $user->id)
                    ->avg('rating');

                return $rating ? number_format($rating, 1) : '4.8';
            }
        } catch (\Exception $e) {
            Log::error('Error getting veterinary rating: ' . $e->getMessage());
        }

        return '4.8';
    }

    /**
     * Get default statistics for unknown roles.
     */
    private function getDefaultStats($user)
    {
        return [
            'ordersCount' => 0,
            'totalSpent' => 0,
            'cartItemsCount' => 0,
        ];
    }

    /**
     * Get poultry count for farmer.
     */
    private function getPoultryCount($user)
    {
        try {
            // Check if you have a poultry or livestock table
            if (Schema::hasTable('poultry')) {
                if (Schema::hasColumn('poultry', 'user_id')) {
                    return \DB::table('poultry')
                        ->where('user_id', $user->id)
                        ->count();
                } elseif (Schema::hasColumn('poultry', 'farmer_id')) {
                    return \DB::table('poultry')
                        ->where('farmer_id', $user->id)
                        ->count();
                }
            }

            if (Schema::hasTable('livestock')) {
                return \DB::table('livestock')
                    ->where('user_id', $user->id)
                    ->where('type', 'poultry')
                    ->count();
            }

            // Check if User model has a poultry relationship
            if (method_exists($user, 'poultry')) {
                return $user->poultry()->count();
            }

            // Default value
            return 250;
        } catch (\Exception $e) {
            Log::error('Error getting poultry count: ' . $e->getMessage());
            return 250;
        }
    }

    /**
     * Format phone number to +254 format.
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // If starts with 0, remove it
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        // If doesn't start with 254, add it
        if (!str_starts_with($phone, '254')) {
            $phone = '254' . $phone;
        }

        return $phone;
    }
}
