@extends('layouts.app')

@section('title', $title)

@section('styles')
<style>
    .farmer-sidebar .nav-link.active {
        background-color: rgba(28, 200, 138, 0.1);
        border-left: 4px solid #1cc88a;
    }

    .farmer-badge {
        background: linear-gradient(45deg, #1cc88a, #0f9d58);
        color: white;
        font-size: 0.7rem;
        padding: 3px 8px;
    }

    .farmer-header {
        color: #1cc88a;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .health-score-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
    }

    .feed-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
    }

    .production-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tractor text-success mr-2"></i>Farmer Dashboard
        </h1>
        <div class="d-flex">
            <span class="badge badge-pill badge-success p-2 mr-2">
                <i class="fas fa-leaf mr-1"></i>Active Farm
            </span>
            <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i>Add Batch
            </a>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-1">Welcome, {{ $user->name }}!</h4>
                            <p class="card-text text-muted mb-0">
                                <i class="fas fa-egg text-warning mr-1"></i>
                                You have {{ $stats['poultry_count'] ?? 0 }} poultry with {{ $stats['egg_production_today'] ?? 0 }} eggs produced today.
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ now()->format('l, F j, Y') }}
                            </small>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="d-flex justify-content-end">
                                <div class="mr-3 text-center">
                                    <div class="text-xs font-weight-bold text-success">Health Score</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['health_score'] ?? '0%' }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-warning">Feed Remaining</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['feed_remaining'] ?? '0 days' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Poultry -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Poultry</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['poultry_count'] ?? 0 }}</div>
                            <div class="mt-2">
                                <span class="text-success small">
                                    <i class="fas fa-egg mr-1"></i>{{ $stats['egg_production_today'] ?? 0 }} eggs today
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-kiwi-bird fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">KES {{ number_format($stats['monthly_revenue'] ?? 0) }}</div>
                            <div class="mt-2">
                                <span class="text-success small">
                                    <i class="fas fa-arrow-up mr-1"></i>{{ $stats['revenue_growth'] ?? '0%' }}
                                </span>
                                <span class="text-muted small ml-2">Growth</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] ?? 0 }}</div>
                            <div class="mt-2">
                                @if(($stats['cart_items_count'] ?? 0) > 0)
                                <span class="badge badge-info mr-1">
                                    {{ $stats['cart_items_count'] ?? 0 }} in cart
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers Connected -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Suppliers Connected</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['suppliers_connected'] ?? 0 }}</div>
                            <div class="mt-2">
                                <span class="text-primary small">
                                    <i class="fas fa-user-tie mr-1"></i>{{ $stats['connected_agents_count'] ?? 0 }} agents
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column: Recent Orders & Poultry Health -->
        <div class="col-lg-8">
            <!-- Recent Orders -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list mr-2"></i>Recent Orders
                    </h6>
                    <a href="{{ route('farmer.orders.index') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Agent</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('farmer.orders.show', $order['id']) }}" class="text-primary">
                                            {{ $order['order_number'] }}
                                        </a>
                                    </td>
                                    <td>{{ $order['date'] }}</td>
                                    <td>{{ $order['agent_name'] }}</td>
                                    <td>{{ $order['total'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order['status_color'] }}">
                                            {{ ucfirst($order['status']) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">No recent orders</p>
                        <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-success">
                            <i class="fas fa-shopping-cart mr-1"></i>Shop Supplies
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Poultry Health -->
            @if(!empty($poultry_health))
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heartbeat mr-2"></i>Poultry Health Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($poultry_health as $batch)
                        <div class="col-md-6 mb-3">
                            <div class="card border-{{ $batch['status'] == 'healthy' ? 'success' : 'warning' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $batch['batch'] }}</h6>
                                        <span class="badge badge-{{ $batch['status'] == 'healthy' ? 'success' : 'warning' }}">
                                            {{ $batch['status'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Health Score</small>
                                        <strong class="text-{{ $batch['status'] == 'healthy' ? 'success' : 'warning' }}">
                                            {{ $batch['health_score'] }}%
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Egg Production</small>
                                        <strong>{{ $batch['egg_production'] }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Feed Consumption</small>
                                        <strong>{{ $batch['feed_consumption'] }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Quick Actions, Health Stats, Tasks -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('farmer.marketplace.index', ['category' => 'feed']) }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-cart-plus fa-sm mr-1"></i>Order Feed
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-primary btn-block" onclick="alert('Vet service coming soon!')">
                                <i class="fas fa-stethoscope fa-sm mr-1"></i>Call Vet
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-info btn-block" onclick="alert('Sales report coming soon!')">
                                <i class="fas fa-chart-line fa-sm mr-1"></i>Sales Report
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-warning btn-block" onclick="alert('Record eggs coming soon!')">
                                <i class="fas fa-egg fa-sm mr-1"></i>Record Eggs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Recommendations -->
            @if(!empty($supplier_recommendations))
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-store-alt mr-2"></i>Recommended Suppliers
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($supplier_recommendations as $supplier)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0">{{ $supplier['name'] }}</h6>
                            <span class="badge badge-success">
                                <i class="fas fa-star fa-xs"></i> {{ $supplier['rating'] }}
                            </span>
                        </div>
                        <small class="text-muted d-block mb-1">{{ $supplier['products'] }}</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-info">
                                <i class="fas fa-truck mr-1"></i> {{ $supplier['delivery_time'] }}
                            </small>
                            <small class="text-success">
                                {{ $supplier['commission_rate'] }} commission
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upcoming Tasks -->
            @if(!empty($upcoming_tasks))
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks mr-2"></i>Upcoming Tasks
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($upcoming_tasks as $task)
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-{{ $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'info') }}-light text-{{ $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'info') }} p-2 mr-3">
                            <i class="{{ $task['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $task['title'] }}</h6>
                            <small class="text-muted">{{ $task['due_date'] }}</small>
                        </div>
                        <span class="badge badge-{{ $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'info') }}">
                            {{ $task['priority'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Add any dashboard specific JavaScript here
    console.log('Farmer dashboard loaded');
});
</script>
@endsection
