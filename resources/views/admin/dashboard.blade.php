@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    /* AdminLTE Enhanced Styling */
    .small-box {
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .info-box {
        min-height: 100px;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }

    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .info-box-icon {
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .timeline-item {
        border-radius: 0.5rem;
        transition: background-color 0.2s;
    }

    .timeline-item:hover {
        background-color: #f8f9fa;
    }

    .progress-sm {
        height: 0.5rem;
        border-radius: 0.25rem;
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
        background-color: transparent;
    }

    .card-header .card-title {
        font-weight: 600;
        color: #1f2d3d;
    }

    .badge-pill {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .trend-up {
        color: #28a745;
        background: rgba(40, 167, 69, 0.1);
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
    }

    .trend-down {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s;
    }

    .activity-item:hover {
        background-color: #f8f9fa;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Custom gradients */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%);
    }

    /* Metric cards */
    .metric-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        transition: all 0.3s ease;
        height: 100%;
        border-left: 4px solid transparent;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .metric-card.primary { border-left-color: #007bff; }
    .metric-card.success { border-left-color: #28a745; }
    .metric-card.info { border-left-color: #17a2b8; }
    .metric-card.warning { border-left-color: #ffc107; }

    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .metric-icon.primary { background: rgba(0, 123, 255, 0.1); color: #007bff; }
    .metric-icon.success { background: rgba(40, 167, 69, 0.1); color: #28a745; }
    .metric-icon.info { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }
    .metric-icon.warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header with Welcome Message -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-crown mr-2"></i>Welcome back, {{ auth()->user()->name }}!
                            </h4>
                            <p class="mb-0 opacity-75">
                                Here's what's happening with your platform today. You have
                                <strong>{{ number_format($stats['total_orders']) }} orders</strong> and
                                <strong>{{ number_format($stats['total_users']) }} users</strong> across your marketplace.
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-light p-2">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ now()->format('l, F j, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Executive KPI Row - Using AdminLTE Small Boxes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['total_users']) }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="small-box-footer">
                    <span class="mr-2">
                        <span class="text-white">{{ $stats['total_suppliers'] }} suppliers</span> •
                        <span class="text-white">{{ $stats['total_farmers'] }} farmers</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['total_products']) }}</h3>
                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">{{ $stats['available_products'] ?? 0 }} available</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['total_orders']) }}</h3>
                    <p>Total Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">{{ $stats['pending_orders'] }} pending • {{ $stats['completed_orders'] }} completed</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>KES {{ number_format($stats['total_revenue']) }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">{{ $subscriptionStats['subscription_growth'] }} from last month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity & Alerts Row -->
    <div class="row">
        <!-- Today's Activity - Info Boxes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2 text-info"></i>
                        Today's Activity
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info">{{ now()->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-user-plus"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">New Users</span>
                                    <span class="info-box-number">{{ $todayStats['new_users_today'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-shopping-cart"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Orders</span>
                                    <span class="info-box-number">{{ $todayStats['orders_today'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Revenue</span>
                                    <span class="info-box-number">KES {{ number_format($todayStats['revenue_today']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Progress -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>Verification Rate</span>
                            <span class="font-weight-bold text-success">{{ $verificationStats['verification_success_rate'] }}%</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: {{ $verificationStats['verification_success_rate'] }}%"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
                            <span>Avg. Verification Time</span>
                            <span class="font-weight-bold text-info">{{ $verificationStats['avg_verification_time'] }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span>Active Subscriptions</span>
                            <span class="font-weight-bold text-success">{{ $subscriptionStats['active_subscriptions'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Actions Alerts -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title text-white">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Requires Attention
                    </h3>
                    <div class="card-tools">
                        @if(array_sum($pendingActions) > 0)
                            <span class="badge badge-danger">{{ array_sum($pendingActions) }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @if($pendingActions['pending_verifications'] > 0)
                                <tr>
                                    <td><i class="fas fa-clock text-warning mr-2"></i> Pending Verifications</td>
                                    <td class="text-right"><span class="badge badge-warning">{{ $pendingActions['pending_verifications'] }}</span></td>
                                </tr>
                                @endif
                                @if($pendingActions['stuck_payments'] > 0)
                                <tr>
                                    <td><i class="fas fa-credit-card text-danger mr-2"></i> Stuck Payments (>24h)</td>
                                    <td class="text-right"><span class="badge badge-danger">{{ $pendingActions['stuck_payments'] }}</span></td>
                                </tr>
                                @endif
                                @if($pendingActions['unprocessed_orders'] > 0)
                                <tr>
                                    <td><i class="fas fa-truck text-info mr-2"></i> Unprocessed Orders (>48h)</td>
                                    <td class="text-right"><span class="badge badge-info">{{ $pendingActions['unprocessed_orders'] }}</span></td>
                                </tr>
                                @endif
                                @if($pendingActions['low_stock_alerts'] > 0)
                                <tr>
                                    <td><i class="fas fa-box-open text-secondary mr-2"></i> Low Stock Alerts</td>
                                    <td class="text-right"><span class="badge badge-secondary">{{ $pendingActions['low_stock_alerts'] }}</span></td>
                                </tr>
                                @endif
                                @if(array_sum($pendingActions) == 0)
                                <tr>
                                    <td colspan="2" class="text-center text-success py-4">
                                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                                        <p class="mb-0">All systems operational!</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.verification.index') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-eye mr-1"></i> View All Pending
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Stats Row -->
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Pending Verifications</span>
                    <span class="info-box-number">{{ $subscriptionStats['pending_verifications'] }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: 100%"></div>
                    </div>
                    <span class="progress-description text-muted small">
                        Avg. time: {{ $verificationStats['avg_verification_time'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Subscriptions</span>
                    <span class="info-box-number">{{ $subscriptionStats['active_subscriptions'] }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                    <span class="progress-description text-muted small">
                        Revenue: KES {{ number_format($subscriptionStats['total_subscription_revenue']) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-secondary">
                    <i class="fas fa-percentage"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Verification Rate</span>
                    <span class="info-box-number">{{ $verificationStats['verification_success_rate'] }}%</span>
                    <div class="progress">
                        <div class="progress-bar bg-secondary" style="width: {{ $verificationStats['verification_success_rate'] }}%"></div>
                    </div>
                    <span class="progress-description text-muted small">
                        {{ $verificationStats['verifications_this_month'] }} this month
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2 text-primary"></i>
                        Revenue Trend (Last 30 Days)
                    </h3>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2 text-success"></i>
                        User Distribution
                    </h3>
                </div>
                <div class="card-body">
                    <div style="height: 200px;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-info mr-1"></i> Suppliers</span>
                            <span class="font-weight-bold">{{ $roleDistribution['suppliers'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-success mr-1"></i> Farmers</span>
                            <span class="font-weight-bold">{{ $roleDistribution['farmers'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-warning mr-1"></i> Agents</span>
                            <span class="font-weight-bold">{{ $roleDistribution['agents'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-danger mr-1"></i> Veterinarians</span>
                            <span class="font-weight-bold">{{ $roleDistribution['veterinarians'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Performance & Top Suppliers -->
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-crown mr-2 text-warning"></i>
                        Plan Performance
                    </h3>
                </div>
                <div class="card-body">
                    @foreach($planPerformance as $plan)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold">{{ $plan['name'] }} Plan</span>
                            <span class="badge badge-{{ $plan['name'] == 'Pro' ? 'danger' : 'success' }} badge-pill">
                                {{ $plan['subscribers'] }} subs
                            </span>
                        </div>
                        <div class="progress progress-sm">
                            @php
                                $maxSubscribers = $planPerformance->max('subscribers') ?: 1;
                                $width = ($plan['subscribers'] / $maxSubscribers) * 100;
                            @endphp
                            <div class="progress-bar bg-{{ $plan['name'] == 'Pro' ? 'danger' : 'success' }}"
                                 style="width: {{ $width }}%"></div>
                        </div>
                        <small class="text-muted">KES {{ number_format($plan['revenue']) }} revenue</small>
                    </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Pending Verifications</span>
                            <h5 class="mb-0 text-warning">{{ $subscriptionStats['pending_verifications'] }}</h5>
                        </div>
                        <div>
                            <span class="text-muted small">Expired</span>
                            <h5 class="mb-0 text-secondary">{{ $subscriptionStats['expired_subscriptions'] }}</h5>
                        </div>
                        <div>
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-primary">
                                Manage <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy mr-2 text-warning"></i>
                        Top Performing Suppliers
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.index', ['role' => 'supplier']) }}" class="btn btn-sm btn-primary">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Supplier</th>
                                <th class="text-center">Products</th>
                                <th class="text-center">Orders</th>
                                <th class="text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSuppliers as $index => $supplier)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $index + 1 == 1 ? 'warning' : ($index + 1 == 2 ? 'secondary' : 'info') }} badge-pill">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td><strong>{{ $supplier['name'] }}</strong></td>
                                <td class="text-center">{{ $supplier['products_count'] }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">{{ $supplier['orders_count'] }}</span>
                                </td>
                                <td class="text-right font-weight-bold text-success">
                                    KES {{ number_format($supplier['revenue']) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-store fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">No supplier data available</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Stream -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-stream mr-2 text-secondary"></i>
                        Recent Activity
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="timeline">
                        @foreach($recentActivity as $activity)
                        <div class="timeline-item d-flex align-items-center p-3">
                            <div class="activity-icon bg-{{ $activity['color'] }} text-white mr-3">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1">{{ $activity['message'] }}</p>
                                <small class="text-muted">
                                    <i class="far fa-clock mr-1"></i> {{ $activity['time'] }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-secondary">
                        View All Activity <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users & Orders -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-2 text-primary"></i>
                        Recent Users
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->role_badge_color }} badge-pill">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart mr-2 text-success"></i>
                        Recent Orders
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-success">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong><br>
                                        <small class="text-muted">{{ $order->user->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{
                                            $order->status == 'delivered' ? 'success' :
                                            ($order->status == 'pending' ? 'warning' :
                                            ($order->status == 'cancelled' ? 'danger' : 'info'))
                                        }} badge-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-success">KES {{ number_format($order->total) }}</strong><br>
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Verifications -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2 text-warning"></i>
                        Recent Verifications
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.verification.index') }}" class="btn btn-sm btn-warning">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Document Type</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentVerifications as $verification)
                                <tr>
                                    <td>
                                        <strong>{{ $verification->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $verification->user->email ?? '' }}</small>
                                    </td>
                                    <td>{{ str_replace('_', ' ', ucfirst($verification->document_type)) }}</td>
                                    <td>
                                        <span class="badge badge-{{
                                            $verification->status == 'approved' ? 'success' :
                                            ($verification->status == 'pending' ? 'warning' : 'danger')
                                        }} badge-pill">
                                            {{ ucfirst($verification->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $verification->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueLabels = {!! json_encode($revenueTrend->pluck('date')->map(function($date) {
        return \Carbon\Carbon::parse($date)->format('M d');
    })) !!};
    const revenueData = {!! json_encode($revenueTrend->pluck('revenue')) !!};

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue (KES)',
                data: revenueData,
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderColor: '#28a745',
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#28a745',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'KES ' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [3, 3] },
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // User Distribution Chart
    const distributionCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Suppliers', 'Farmers', 'Agents', 'Veterinarians'],
            datasets: [{
                data: [
                    {{ $roleDistribution['suppliers'] }},
                    {{ $roleDistribution['farmers'] }},
                    {{ $roleDistribution['agents'] }},
                    {{ $roleDistribution['veterinarians'] }}
                ],
                backgroundColor: [
                    '#17a2b8', // info
                    '#28a745', // success
                    '#ffc107', // warning
                    '#dc3545'  // danger
                ],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '60%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
