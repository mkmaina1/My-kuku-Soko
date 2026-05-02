@extends('layouts.app')

@section('title', 'Supplier Analytics')

@push('styles')
<style>
    .analytics-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
        margin-bottom: 20px;
    }
    .analytics-card:hover {
        transform: translateY(-5px);
    }
    .stat-card {
        border-left: 4px solid #007bff;
    }
    .growth-positive {
        color: #28a745;
    }
    .growth-negative {
        color: #dc3545;
    }
    .table-custom thead {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: white;
    }
    .badge-verified {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .address-badge {
        background: linear-gradient(45deg, #6f42c1, #e83e8c);
        color: white;
        font-size: 0.8em;
        padding: 3px 8px;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<!-- <div class="content-wrapper"> -->
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-chart-line text-primary mr-2"></i>
                        Supplier Analytics Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                        <li class="breadcrumb-item active">Suppliers</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Suppliers</span>
                            <span class="info-box-number">
                                {{ $stats['total_suppliers'] }}
                                @if($stats['supplier_growth_rate'] > 0)
                                <small class="growth-positive">
                                    <i class="fas fa-arrow-up"></i> {{ abs($stats['supplier_growth_rate']) }}%
                                </small>
                                @elseif($stats['supplier_growth_rate'] < 0)
                                <small class="growth-negative">
                                    <i class="fas fa-arrow-down"></i> {{ abs($stats['supplier_growth_rate']) }}%
                                </small>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Verified Suppliers</span>
                            <span class="info-box-number">{{ $stats['verified_suppliers'] }}</span>
                            <span class="info-box-text">
                                {{ $stats['total_suppliers'] > 0 ? round(($stats['verified_suppliers']/$stats['total_suppliers'])*100, 1) : 0 }}% of total
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-store"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Suppliers</span>
                            <span class="info-box-number">{{ $stats['active_suppliers'] }}</span>
                            <span class="info-box-text">With products listed</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-boxes"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Avg Products/Supplier</span>
                            <span class="info-box-number">{{ $stats['avg_products_per_supplier'] }}</span>
                            <span class="info-box-text">Total: {{ $stats['total_products'] }} products</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Supplier Growth Trend (Last 12 Months)
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="supplierGrowthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Verification Status
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="verificationChart"></canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="mb-0">{{ $stats['verified_suppliers'] }}</h4>
                                    <span class="text-success">Verified</span>
                                </div>
                                <div class="col-6">
                                    <h4 class="mb-0">{{ $stats['unverified_suppliers'] }}</h4>
                                    <span class="text-warning">Unverified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Distribution -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Supplier Geographical Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($supplierLocations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>County</th>
                                            <th>Suppliers</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplierLocations as $location)
                                        <tr>
                                            <td>
                                                <i class="fas fa-map-pin text-danger mr-2"></i>
                                                {{ $location->location ?? 'Unknown County' }}
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $location->total }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: {{ ($location->total/$stats['total_suppliers'])*100 }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ round(($location->total/$stats['total_suppliers'])*100, 1) }}%
                                                </small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No address data available</p>
                                <small class="text-info">
                                    <i class="fas fa-info-circle"></i>
                                    Suppliers need to add addresses to see geographical distribution
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tags mr-2"></i>
                                Product Categories Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="categoriesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Suppliers Tables -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-trophy text-warning mr-2"></i>
                                Top Suppliers by Products
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-custom">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Products</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topSuppliersByProducts as $index => $supplier)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white mr-3"
                                                        style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $supplier->name }}</strong>
                                                        <div class="small text-muted">{{ $supplier->email }}</div>
                                                        @php
                                                            $defaultAddress = $supplier->addresses->where('is_default', true)->first() ?? $supplier->addresses->first();
                                                        @endphp
                                                        @if($defaultAddress)
                                                        <div class="small">
                                                            <span class="address-badge">
                                                                <i class="fas fa-home mr-1"></i>
                                                                {{ $defaultAddress->city }}, {{ $defaultAddress->county }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-info" style="font-size: 1em;">
                                                    {{ $supplier->products_count }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($supplier->is_verified || $supplier->verification_status == 'approved')
                                                <span class="badge badge-verified">
                                                    <i class="fas fa-check mr-1"></i> Verified
                                                </span>
                                                @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($defaultAddress)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $defaultAddress->county }}
                                                </small>
                                                @else
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                    No address
                                                </small>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar text-success mr-2"></i>
                                Top Performing Suppliers (Sales)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-custom">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Orders</th>
                                            <th>Revenue</th>
                                            <th>Products</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($suppliersWithOrders as $supplier)
                                        @php
                                            $defaultAddress = $supplier->addresses->where('is_default', true)->first() ?? $supplier->addresses->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $supplier->name }}</strong>
                                                <div class="small text-muted">{{ $supplier->email }}</div>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $supplier->orders_count }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    KES {{ number_format($supplier->orders_sum_total, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $supplier->products_count }}</span>
                                            </td>
                                            <td>
                                                @if($defaultAddress)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                    {{ $defaultAddress->county }}
                                                </small>
                                                @else
                                                <small class="text-muted">N/A</small>
                                                @endif
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
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Supplier Growth Chart
    const growthCtx = document.getElementById('supplierGrowthChart').getContext('2d');
    const growthChart = new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: @json($supplierGrowth->pluck('month')),
            datasets: [{
                label: 'New Suppliers',
                data: @json($supplierGrowth->pluck('count')),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Verification Status Chart
    const verificationCtx = document.getElementById('verificationChart').getContext('2d');
    const verificationChart = new Chart(verificationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Verified', 'Unverified'],
            datasets: [{
                data: [
                    {{ $stats['verified_suppliers'] }},
                    {{ $stats['unverified_suppliers'] }}
                ],
                backgroundColor: ['#28a745', '#ffc107'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Product Categories Chart
    @if($productCategories->count() > 0)
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    const categoriesChart = new Chart(categoriesCtx, {
        type: 'bar',
        data: {
            labels: @json($productCategories->pluck('category')),
            datasets: [{
                label: 'Number of Products',
                data: @json($productCategories->pluck('total')),
                backgroundColor: 'rgba(23, 162, 184, 0.7)',
                borderColor: '#17a2b8',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45
                    }
                }
            }
        }
    });
    @endif
</script>
@endpush
