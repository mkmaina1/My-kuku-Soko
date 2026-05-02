@extends('layouts.app')

@section('title', 'Deliveries Overview')

@push('styles')
<style>
    .delivery-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
    }
    .delivery-card:hover {
        transform: translateY(-5px);
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .status-scheduled { background: #ffc107; color: #000; }
    .status-in-transit { background: #17a2b8; color: #fff; }
    .status-delivered { background: #28a745; color: #fff; }
    .status-delayed { background: #dc3545; color: #fff; }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #007bff;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -20px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #007bff;
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
                        <i class="fas fa-shipping-fast text-warning mr-2"></i>
                        Deliveries Overview
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Logistics</a></li>
                        <li class="breadcrumb-item active">Deliveries</li>
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
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Deliveries</span>
                            <span class="info-box-number">{{ $stats['today_deliveries'] }}</span>
                            <span class="info-box-text">Orders shipped today</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-calendar-week"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Week</span>
                            <span class="info-box-number">{{ $stats['week_deliveries'] }}</span>
                            <span class="info-box-text">Recent shipments</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Completed Today</span>
                            <span class="info-box-number">{{ $stats['completed_today'] }}</span>
                            <span class="info-box-text">Successful deliveries</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Agents</span>
                            <span class="info-box-number">
                                {{ $stats['active_delivery_agents'] }}/{{ $stats['total_delivery_agents'] }}
                            </span>
                            <span class="info-box-text">On delivery duty</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Performance -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card delivery-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Weekly Shipments
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="weeklyDeliveriesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card delivery-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Delivery Performance
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="h2 text-primary">{{ $performance['delivery_success_rate'] }}%</div>
                                    <small>Success Rate</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="h2 text-success">{{ $performance['on_time_delivery_rate'] }}%</div>
                                    <small>On Time Rate</small>
                                </div>
                                <div class="col-6">
                                    <div class="h2 text-info">{{ $performance['avg_delivery_time_days'] }} days</div>
                                    <small>Avg Delivery Time</small>
                                </div>
                                <div class="col-6">
                                    <div class="h2 text-warning">{{ $performance['customer_satisfaction'] }}</div>
                                    <small>Satisfaction</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Deliveries -->
            <div class="row">
                <div class="col-12">
                    <div class="card delivery-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-truck mr-2"></i>
                                Today's Shipments
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-warning">{{ date('F j, Y') }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($todayDeliveries->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Shipping Address</th>
                                            <th>Order Time</th>
                                            <th>Agent</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayDeliveries as $delivery)
                                        <tr>
                                            <td>
                                                <strong>#{{ $delivery->order_number }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white mr-2"
                                                         style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($delivery->user->name ?? 'C', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $delivery->user->name ?? 'Customer' }}</strong>
                                                        <div class="small text-muted">{{ $delivery->user->phone ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    @php
                                                        // Display truncated shipping address
                                                        $address = $delivery->shipping_address;
                                                        if (strlen($address) > 50) {
                                                            $address = substr($address, 0, 50) . '...';
                                                        }
                                                    @endphp
                                                    {{ $address }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $delivery->created_at->format('H:i') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($delivery->agent)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-2"
                                                         style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($delivery->agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $delivery->agent->name }}</strong>
                                                        <div class="small text-muted">{{ $delivery->agent->phone ?? '' }}</div>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="status-badge status-in-transit">
                                                    <i class="fas fa-truck mr-1"></i> Shipped
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.orders.show', $delivery) }}"
                                                       class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-success" data-toggle="tooltip" title="Mark Delivered">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" data-toggle="tooltip" title="Update Status">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-truck fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No shipments today</p>
                                <small class="text-info">
                                    Orders marked as 'shipped' will appear here
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geographical Distribution -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card delivery-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marked-alt mr-2"></i>
                                Delivery Locations
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($deliveryLocations->count() > 0)
                            <div class="row">
                                @foreach($deliveryLocations as $location)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                                            </div>
                                            <h5 class="card-title">{{ $location->location }}</h5>
                                            <div class="h3 mb-2">{{ $location->deliveries }}</div>
                                            <small class="text-muted">shipments</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No location data available</p>
                                <small class="text-info">
                                    Location data extracted from shipping addresses
                                </small>
                            </div>
                            @endif
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
    // Weekly Deliveries Chart
    const weeklyCtx = document.getElementById('weeklyDeliveriesChart').getContext('2d');

    // Prepare chart data
    const chartLabels = @json($weekDeliveries->pluck('formatted_date'));
    const chartData = @json($weekDeliveries->pluck('count'));

    const weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Shipments',
                data: chartData,
                backgroundColor: 'rgba(255, 193, 7, 0.7)',
                borderColor: '#ffc107',
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

    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
@endpush
