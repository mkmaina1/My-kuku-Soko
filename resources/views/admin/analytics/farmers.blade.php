@extends('layouts.app')

@section('title', 'Farmer Analytics')

@push('styles')
<style>
    .progress-thin {
        height: 8px;
    }
    .spending-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .order-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .card-icon {
        font-size: 2.5rem;
        opacity: 0.7;
    }
    .performance-meter {
        height: 100px;
        position: relative;
    }
    .meter-value {
        font-size: 2rem;
        font-weight: bold;
    }
    .address-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
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
                        <i class="fas fa-tractor text-success mr-2"></i>
                        Farmer Analytics Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                        <li class="breadcrumb-item active">Farmers</li>
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
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Farmers</span>
                            <span class="info-box-number">
                                {{ $stats['total_farmers'] }}
                                @if($stats['farmer_growth_rate'] > 0)
                                <small class="growth-positive">
                                    <i class="fas fa-arrow-up"></i> {{ abs($stats['farmer_growth_rate']) }}%
                                </small>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm spending-card">
                        <span class="info-box-icon elevation-1" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Spending</span>
                            <span class="info-box-number">
                                KES {{ number_format($stats['total_spending'], 0) }}
                            </span>
                            <span class="info-box-text">
                                Avg: KES {{ number_format($stats['avg_order_value'], 0) }}/order
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm order-card">
                        <span class="info-box-icon elevation-1" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Orders</span>
                            <span class="info-box-number">{{ $stats['total_orders'] }}</span>
                            <span class="info-box-text">
                                {{ $stats['active_farmers'] }} active farmers
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Farmers</span>
                            <span class="info-box-number">
                                {{ $stats['active_farmers'] }}
                                <small>
                                    ({{ $stats['total_farmers'] > 0 ? round(($stats['active_farmers']/$stats['total_farmers'])*100, 1) : 0 }}%)
                                </small>
                            </span>
                            <span class="info-box-text">Made at least 1 order</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-7">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Farmer Growth & Spending Trend
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="combinedChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Purchase Frequency Analysis
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="frequencyChart"></canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $purchaseFrequency['one_time'] }}</h4>
                                    <small class="text-warning">One-Time</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $purchaseFrequency['regular'] }}</h4>
                                    <small class="text-info">Regular</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $purchaseFrequency['frequent'] }}</h4>
                                    <small class="text-success">Frequent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Farmers -->
            <div class="row">
                <div class="col-12">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-trophy text-warning mr-2"></i>
                                Top Farmers by Spending
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-success">
                                    Total: KES {{ number_format($stats['total_spending'], 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Farmer</th>
                                            <th>Total Orders</th>
                                            <th>Total Spending</th>
                                            <th>Avg Order Value</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topFarmersBySpending as $index => $farmer)
                                        @php
                                            $defaultAddress = $farmer->addresses->where('is_default', true)->first() ?? $farmer->addresses->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($index == 0)
                                                <span class="badge badge-warning">#1</span>
                                                @elseif($index == 1)
                                                <span class="badge badge-secondary">#2</span>
                                                @elseif($index == 2)
                                                <span class="badge badge-danger">#3</span>
                                                @else
                                                <span class="badge badge-light">#{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-3"
                                                         style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($farmer->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $farmer->name }}</strong>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-envelope mr-1"></i>{{ $farmer->email }}
                                                        </div>
                                                        @if($defaultAddress)
                                                        <div class="small mt-1">
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
                                                <span class="badge badge-primary badge-pill">
                                                    {{ $farmer->orders_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    KES {{ number_format($farmer->orders_sum_total, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="text-info">
                                                    KES {{ number_format($farmer->orders_count > 0 ? $farmer->orders_sum_total / $farmer->orders_count : 0, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($defaultAddress)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                    {{ $defaultAddress->county }}
                                                </small>
                                                @else
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                    No address
                                                </small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($farmer->is_verified || $farmer->verification_status == 'approved')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i> Verified
                                                </span>
                                                @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $farmer) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
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

            <!-- Geographical Distribution -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marked-alt mr-2"></i>
                                Farmer Geographical Distribution (by County)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($farmerLocations->count() > 0)
                                    @foreach($farmerLocations->take(6) as $location)
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                                            {{ $location->location ?? 'Unknown County' }}
                                                        </h5>
                                                        <p class="text-muted mb-0">{{ $location->total }} farmers</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="h2 mb-0">{{ round(($location->total/$stats['total_farmers'])*100, 1) }}%</div>
                                                        <small>of total</small>
                                                    </div>
                                                </div>
                                                <div class="progress progress-thin mt-2">
                                                    <div class="progress-bar bg-success"
                                                         style="width: {{ ($location->total/$stats['total_farmers'])*100 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">No address data available</p>
                                    <small class="text-info">
                                        <i class="fas fa-info-circle"></i>
                                        Farmers need to add addresses to see geographical distribution
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Value Trends -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Average Order Value Trend (Last 6 Months)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="orderValueTrendChart"></canvas>
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
    // Combined Chart (Growth + Spending)
    const combinedCtx = document.getElementById('combinedChart').getContext('2d');
    const combinedChart = new Chart(combinedCtx, {
        type: 'line',
        data: {
            labels: @json($farmerGrowth->pluck('month')),
            datasets: [
                {
                    label: 'New Farmers',
                    data: @json($farmerGrowth->pluck('count')),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    yAxisID: 'y',
                    fill: true
                },
                {
                    label: 'Avg Order Value',
                    data: @json($avgOrderValueTrend->pluck('avg_value')),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    yAxisID: 'y1',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'New Farmers'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Avg Order Value (KES)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value;
                        }
                    }
                }
            }
        }
    });

    // Frequency Chart
    const frequencyCtx = document.getElementById('frequencyChart').getContext('2d');
    const frequencyChart = new Chart(frequencyCtx, {
        type: 'pie',
        data: {
            labels: ['One-Time Buyers', 'Regular Buyers (2-5)', 'Frequent Buyers (5+)'],
            datasets: [{
                data: [
                    {{ $purchaseFrequency['one_time'] }},
                    {{ $purchaseFrequency['regular'] }},
                    {{ $purchaseFrequency['frequent'] }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#17a2b8',
                    '#28a745'
                ],
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20
                    }
                }
            }
        }
    });

    // Order Value Trend Chart
    const trendCtx = document.getElementById('orderValueTrendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: @json($avgOrderValueTrend->pluck('month')),
            datasets: [{
                label: 'Average Order Value (KES)',
                data: @json($avgOrderValueTrend->pluck('avg_value')),
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: '#28a745',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value;
                        }
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
</script>
@endpush
