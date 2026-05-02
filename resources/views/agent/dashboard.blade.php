@extends('layouts.app')

@section('title', 'Agent Dashboard')

@section('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid #2e7d32;
    }

    .stat-card.warning {
        border-left-color: #ffc107;
    }

    .stat-card.info {
        border-left-color: #17a2b8;
    }

    .stat-card.primary {
        border-left-color: #007bff;
    }

    .stat-card.danger {
        border-left-color: #dc3545;
    }

    .target-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }

    .target-progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
        margin-top: 10px;
    }

    .target-progress-bar {
        height: 100%;
        transition: width 0.3s;
    }

    .activity-item {
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Welcome card specific styles */
    .welcome-card {
        border-left: 4px solid #17a2b8;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .welcome-card .card-title {
        color: #2c3e50;
    }

    .welcome-stats {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        padding: 15px;
        backdrop-filter: blur(10px);
    }

    .welcome-stat-item {
        text-align: center;
        padding: 0 10px;
    }

    .welcome-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }

    .welcome-stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>Agent Dashboard
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Overview</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card for Agent -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card border-left-info shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-1">Welcome, {{ auth()->user()->name }}! <span class="badge badge-success">Agent</span></h4>
                            <p class="card-text text-muted mb-0">
                                <i class="fas fa-handshake text-primary mr-1"></i>
                                You have connected {{ $stats['farmersCount'] ?? 0 }} farmers with {{ $stats['suppliersCount'] ?? 0 }} suppliers.
                            </p>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-shopping-cart text-success mr-1"></i>
                                Processed {{ $stats['totalOrders'] ?? 0 }} orders worth KES {{ number_format($stats['commissionEarned'] ?? 0, 2) }} in commissions.
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ now()->format('l, F j, Y') }}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <div class="welcome-stats">
                                <div class="row">
                                    <div class="col-6 text-center border-right">
                                        <div class="welcome-stat-item">
                                            <div class="welcome-stat-value text-success">
                                                {{ $stats['pendingOrders'] ?? 0 }}
                                            </div>
                                            <div class="welcome-stat-label">Pending Orders</div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <div class="welcome-stat-item">
                                            <div class="welcome-stat-value text-info">
                                                {{ $stats['commission_rate_formatted'] ?? '5.0%' }}
                                            </div>
                                            <div class="welcome-stat-label">Commission Rate</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Targets Summary -->
    <!-- @if(!$performanceTargets->isEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bullseye me-2"></i>Performance Targets
                        <span class="float-right badge badge-light">{{ number_format($stats['targetCompletionRate'] ?? 0, 1) }}% Overall</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($performanceTargets as $target)
                        @php
                            $percentage = $target->progress_percentage ?? 0;
                            $color = $percentage >= 100 ? 'success' : ($percentage >= 70 ? 'info' : ($percentage >= 40 ? 'warning' : 'danger'));
                            $unit = '';
                            if (in_array($target->target_type ?? '', ['sales', 'revenue'])) {
                                $unit = 'KES';
                            } elseif (($target->target_type ?? '') == 'completion_rate') {
                                $unit = '%';
                            }
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="target-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $target->name ?? 'Target' }}</h6>
                                    <span class="badge badge-{{ $color }}">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                </div>
                                <small class="text-muted d-block mb-2">
                                    @if(isset($target->start_date) && isset($target->end_date))
                                        {{ $target->start_date->format('M d') }} - {{ $target->end_date->format('M d') }}
                                    @else
                                        {{ now()->startOfMonth()->format('M d') }} - {{ now()->endOfMonth()->format('M d') }}
                                    @endif
                                </small>
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Progress</small>
                                    <small>
                                        {{ number_format($target->progress ?? 0) }}/{{ number_format($target->target_value ?? 0) }}
                                        {{ $unit }}
                                    </small>
                                </div>
                                <div class="target-progress">
                                    <div class="target-progress-bar bg-{{ $color }}"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif -->

    <!-- Order Tracking Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="fas fa-shopping-cart me-2"></i>Order Tracking</h5>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-list-alt fa-2x text-success"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $stats['totalOrders'] ?? 0 }}</h4>
                        <small class="text-muted">Total Orders</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card warning">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $stats['pendingOrders'] ?? 0 }}</h4>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-cog fa-2x text-info"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $stats['processingOrders'] ?? 0 }}</h4>
                        <small class="text-muted">Processing</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card primary">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $stats['inTransitOrders'] ?? 0 }}</h4>
                        <small class="text-muted">In Transit</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $stats['deliveredOrders'] ?? 0 }}</h4>
                        <small class="text-muted">Delivered</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card danger">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $stats['cancelledOrders'] ?? 0 }}</h4>
                        <small class="text-muted">Cancelled</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['farmersCount'] ?? 0 }}</h4>
                            <small class="text-muted">Farmers Registered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-hand-holding-usd fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">KES {{ number_format($stats['commissionEarned'] ?? 0, 2) }}</h4>
                            <small class="text-muted">Commission Earned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-warehouse fa-2x text-info"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['suppliersCount'] ?? 0 }}</h4>
                            <small class="text-muted">Suppliers Represented</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-percentage fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['commission_rate_formatted'] ?? '5.0%' }}</h4>
                            <small class="text-muted">Commission Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Recent Orders -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
                </div>
                <div class="card-body">
                    @if(!empty($recent_activities))
                        @foreach($recent_activities as $activity)
                        <div class="activity-item">
                            <div class="d-flex align-items-start">
                                <div class="activity-icon bg-{{ $activity['color'] ?? 'secondary' }} text-white me-3">
                                    <i class="{{ $activity['icon'] ?? 'fas fa-info-circle' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $activity['title'] ?? 'Activity' }}</h6>
                                    <p class="mb-1 text-muted">{{ $activity['description'] ?? 'No description' }}</p>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-{{ $activity['color'] ?? 'secondary' }}">
                                            <i class="fas fa-circle me-1"></i>{{ ucfirst($activity['status'] ?? 'unknown') }}
                                        </small>
                                        <small class="text-muted">{{ $activity['time'] ?? 'Just now' }}</small>
                                    </div>
                                </div>
                                @if(!empty($activity['amount']))
                                <div class="ms-3">
                                    <span class="badge badge-light">{{ $activity['amount'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Recent Orders</h5>
                    <a href="{{ route('agent.orders.index') }}" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="#" class="text-primary">{{ $order['order_number'] ?? 'N/A' }}</a>
                                    </td>
                                    <td>{{ $order['customer_name'] ?? 'N/A' }}</td>
                                    <td>{{ $order['total'] ?? 'KES 0.00' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order['status_color'] ?? 'secondary' }}">
                                            {{ ucfirst($order['status'] ?? 'unknown') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">No orders yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Represented -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Suppliers Represented</h5>
                </div>
                <div class="card-body">
                    @if(!empty($suppliers_represented))
                    <div class="row">
                        @foreach($suppliers_represented as $supplier)
                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1">{{ $supplier['name'] ?? 'Supplier' }}</h6>
                                            <small class="text-muted">{{ $supplier['products'] ?? 0 }} Products</small>
                                        </div>
                                        <span class="badge badge-{{ ($supplier['status'] ?? 'active') == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($supplier['status'] ?? 'active') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-star text-warning me-1"></i>
                                            <small>{{ $supplier['rating'] ?? 0.0 }}</small>
                                        </div>
                                        <small class="text-success">
                                            <i class="fas fa-percentage me-1"></i>
                                            {{ $supplier['commission_rate'] ?? '0%' }} Commission
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No suppliers represented yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Add any dashboard JavaScript here
});
</script>
@endsection
