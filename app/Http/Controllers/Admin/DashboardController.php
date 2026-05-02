<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Marketplace;
use App\Models\Order;
use App\Models\VerificationRequest;
use App\Models\VeterinarySubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== EXECUTIVE LEVEL (Strategic KPIs) ==========
        $stats = [
            'total_users' => User::count(),
            'total_suppliers' => User::where('role', 'supplier')->count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'total_veterinarians' => User::where('role', 'veterinary')->count(),
            'total_products' => Marketplace::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total') ?? 0,
            'available_products' => Marketplace::where('is_available', true)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
        ];

        // ========== MANAGERIAL LEVEL (Department Performance) ==========

        // Subscription Revenue & Performance
        $subscriptionStats = [
            'active_subscriptions' => VeterinarySubscription::where('status', 'active')->count(),
            'pending_verifications' => VeterinarySubscription::where('payment_verified', false)
                ->where('status', 'pending')
                ->count(),
            'expired_subscriptions' => VeterinarySubscription::where('status', 'expired')->count(),
            'total_subscription_revenue' => VeterinarySubscription::where('status', 'active')
                ->sum('amount_paid'),
            'subscription_growth' => $this->calculateSubscriptionGrowth(),
        ];

        // Plan Performance
        $planPerformance = SubscriptionPlan::withCount('subscriptions')
            ->get()
            ->map(function($plan) {
                $revenue = VeterinarySubscription::where('subscription_plan_id', $plan->id)
                    ->where('status', 'active')
                    ->sum('amount_paid');
                return [
                    'name' => $plan->name,
                    'subscribers' => $plan->subscriptions_count,
                    'revenue' => $revenue,
                    'price' => $plan->price,
                ];
            });

        // Verification Efficiency
        $verificationStats = [
            'pending_user_verifications' => VerificationRequest::where('status', 'pending')->count(),
            'avg_verification_time' => $this->calculateAvgVerificationTime(),
            'verification_success_rate' => $this->calculateVerificationSuccessRate(),
            'verifications_this_month' => VerificationRequest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Order Performance by Status
        $orderStatusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // User Role Distribution
        $roleDistribution = [
            'suppliers' => $stats['total_suppliers'],
            'farmers' => $stats['total_farmers'],
            'agents' => $stats['total_agents'],
            'veterinarians' => $stats['total_veterinarians'],
            'admins' => User::where('role', 'admin')->count(),
        ];

        // ========== OPERATIONAL LEVEL (Real-time Activity) ==========

        // Recent Activity Stream
        $recentActivity = $this->getRecentActivity();

        // Today's Stats
        $todayStats = [
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'revenue_today' => Order::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'verifications_today' => VerificationRequest::whereDate('created_at', today())->count(),
        ];

        // Pending Actions (Alerts)
        $pendingActions = [
            'pending_verifications' => VerificationRequest::where('status', 'pending')->count(),
            'stuck_payments' => VeterinarySubscription::where('status', 'pending')
                ->where('created_at', '<=', now()->subHours(24))
                ->count(),
            'unprocessed_orders' => Order::whereIn('status', ['pending', 'processing'])
                ->where('created_at', '<=', now()->subHours(48))
                ->count(),
            'low_stock_alerts' => Marketplace::where('quantity', '<=', 5)
                ->where('is_available', true)
                ->count(),
        ];

        // ========== ANALYTICAL LEVEL (Trends & Insights) ==========

        // Revenue Trend (Last 30 days)
        $revenueTrend = Order::where('created_at', '>=', now()->subDays(30))
            ->where('status', 'delivered')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // User Growth Trend (Last 30 days)
        $userGrowth = User::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Performing Suppliers
        $topSuppliers = User::where('role', 'supplier')
            ->withCount(['products', 'orders' => function($query) {
                $query->where('status', 'delivered');
            }])
            ->withSum(['orders' => function($query) {
                $query->where('status', 'delivered');
            }], 'total')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($supplier) {
                return [
                    'name' => $supplier->name,
                    'products_count' => $supplier->products_count,
                    'orders_count' => $supplier->orders_count,
                    'revenue' => $supplier->orders_sum_total ?? 0,
                ];
            });

        // Product Category Distribution
        $categoryDistribution = Marketplace::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // ========== RECENT DATA TABLES ==========
        $recentUsers = User::latest()->limit(5)->get();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $recentVerifications = VerificationRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'subscriptionStats',
            'planPerformance',
            'verificationStats',
            'orderStatusCounts',
            'roleDistribution',
            'recentActivity',
            'todayStats',
            'pendingActions',
            'revenueTrend',
            'userGrowth',
            'topSuppliers',
            'categoryDistribution',
            'recentUsers',
            'recentOrders',
            'recentVerifications'
        ));
    }

    /**
     * Calculate average verification time in hours
     */
    private function calculateAvgVerificationTime()
    {
        $avg = VerificationRequest::where('status', 'approved')
            ->whereNotNull('reviewed_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_hours'))
            ->first();

        return $avg && $avg->avg_hours ? round($avg->avg_hours) . ' hours' : 'N/A';
    }

    /**
     * Calculate verification success rate
     */
    private function calculateVerificationSuccessRate()
    {
        $total = VerificationRequest::count();
        if ($total === 0) return 0;

        $approved = VerificationRequest::where('status', 'approved')->count();
        return round(($approved / $total) * 100);
    }

    /**
     * Calculate subscription growth percentage (MoM)
     */
    private function calculateSubscriptionGrowth()
    {
        $currentMonth = VeterinarySubscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = VeterinarySubscription::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) return '+100%';

        $growth = (($currentMonth - $lastMonth) / $lastMonth) * 100;
        return ($growth >= 0 ? '+' : '') . round($growth) . '%';
    }

    /**
     * Get recent activity stream
     */
    private function getRecentActivity()
    {
        $activities = collect();

        // New user registrations
        User::latest()->limit(3)->get()->each(function($user) use ($activities) {
            $activities->push([
                'type' => 'user',
                'icon' => 'fas fa-user-plus',
                'color' => 'success',
                'message' => "New {$user->role} registered: {$user->name}",
                'time' => $user->created_at->diffForHumans(),
            ]);
        });

        // New orders
        Order::with('user')->latest()->limit(3)->get()->each(function($order) use ($activities) {
            $activities->push([
                'type' => 'order',
                'icon' => 'fas fa-shopping-cart',
                'color' => 'info',
                'message' => "New order #{$order->order_number} from {$order->user->name}",
                'time' => $order->created_at->diffForHumans(),
            ]);
        });

        // New verifications
        VerificationRequest::with('user')->latest()->limit(3)->get()->each(function($req) use ($activities) {
            $activities->push([
                'type' => 'verification',
                'icon' => 'fas fa-shield-alt',
                'color' => 'warning',
                'message' => "Verification request from {$req->user->name}",
                'time' => $req->created_at->diffForHumans(),
            ]);
        });

        // New subscriptions
        VeterinarySubscription::with('user', 'plan')->latest()->limit(3)->get()->each(function($sub) use ($activities) {
            $activities->push([
                'type' => 'subscription',
                'icon' => 'fas fa-crown',
                'color' => 'primary',
                'message' => "New {$sub->plan->name} subscription from {$sub->user->name}",
                'time' => $sub->created_at->diffForHumans(),
            ]);
        });

        return $activities->sortByDesc('time')->take(8)->values();
    }
}
