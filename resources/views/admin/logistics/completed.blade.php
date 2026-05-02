@extends('layouts.app')

@section('title', 'Completed Orders')

@push('styles')
<style>
    .completed-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
    }
    .completed-card:hover {
        transform: translateY(-5px);
    }
    .completion-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .completed-on-time { background: #28a745; color: #fff; }
    .completed-late { background: #ffc107; color: #000; }
    .rating-stars {
        color: #ffc107;
        font-size: 0.9em;
    }

    /* Table styling */
    .table-responsive {
        min-height: 300px;
    }
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }
    .table tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }

    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        color: #007bff;
        border: 1px solid #dee2e6;
        margin: 0 2px;
        border-radius: 4px;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
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
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        Completed Orders
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Logistics</a></li>
                        <li class="breadcrumb-item active">Completed</li>
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
                            <i class="fas fa-calendar-day"></i>
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
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-calendar-week"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Week</span>
                            <span class="info-box-number">{{ $stats['completed_this_week'] }}</span>
                            <span class="info-box-text">Weekly total</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Month</span>
                            <span class="info-box-number">{{ $stats['completed_this_month'] }}</span>
                            <span class="info-box-text">Monthly total</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">On Time Rate</span>
                            <span class="info-box-number">{{ $stats['on_time_delivery_rate'] }}%</span>
                            <span class="info-box-text">Delivery performance</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Trends -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card completed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Completion Trend (Last 7 Days)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="completionTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card completed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-trophy mr-2"></i>
                                Top Performing Agents
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($topAgents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Completed</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topAgents as $agent)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-info text-white mr-2"
                                                         style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $agent->name }}</strong>
                                                        <div class="small text-muted">{{ $agent->avg_delivery_time }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $agent->agent_orders_count }}</span>
                                            </td>
                                            <td>
                                                <div class="rating-stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <small class="text-muted">{{ $agent->satisfaction_rate }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No agent data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Orders List -->
            <div class="row">
                <div class="col-12">
                    <div class="card completed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                Recently Completed Orders (Last 30 Days)
                            </h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Search orders...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($completedOrders->count() > 0)
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th width="15%">Order #</th>
                                            <th width="20%">Customer</th>
                                            <th width="15%">Completed On</th>
                                            <th width="10%">Delivery Time</th>
                                            <th width="20%">Agent</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($completedOrders as $order)
                                        @php
                                            // Calculate if delivery was on time (within 3 days)
                                            $isOnTime = $order->created_at && $order->delivered_at
                                                      ? $order->delivered_at->diffInDays($order->created_at) <= 3
                                                      : true;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong class="text-primary">#{{ $order->order_number }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white mr-2"
                                                         style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                                        {{ strtoupper(substr($order->user->name ?? 'C', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $order->user->name ?? 'Customer' }}</strong>
                                                        <div class="small text-muted">{{ $order->user->phone ?? 'No phone' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $order->delivered_at->format('M d, Y') }}
                                                    <br>
                                                    <small>{{ $order->delivered_at->format('h:i A') }}</small>
                                                </span>
                                            </td>
                                            <td>
                                                @if($order->created_at && $order->delivered_at)
                                                @php
                                                    $hours = $order->delivered_at->diffInHours($order->created_at);
                                                    $days = floor($hours / 24);
                                                    $remainingHours = $hours % 24;
                                                    $timeText = '';
                                                    if ($days > 0) {
                                                        $timeText .= $days . ' day' . ($days > 1 ? 's' : '');
                                                    }
                                                    if ($remainingHours > 0) {
                                                        if ($days > 0) $timeText .= ', ';
                                                        $timeText .= $remainingHours . ' hour' . ($remainingHours > 1 ? 's' : '');
                                                    }
                                                @endphp
                                                <span class="badge badge-info" style="font-size: 0.8rem;">
                                                    {{ $timeText ?: 'Same day' }}
                                                </span>
                                                @else
                                                <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->agent)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-2"
                                                         style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                                        {{ strtoupper(substr($order->agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $order->agent->name }}</strong>
                                                        <div class="small text-muted">{{ $order->agent->phone ?? 'No phone' }}</div>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isOnTime)
                                                <span class="completion-badge completed-on-time" style="font-size: 0.8rem;">
                                                    <i class="fas fa-check mr-1"></i> On Time
                                                </span>
                                                @else
                                                <span class="completion-badge completed-late" style="font-size: 0.8rem;">
                                                    <i class="fas fa-clock mr-1"></i> Late
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-outline-info" data-toggle="tooltip" title="Contact Customer">
                                                        <i class="fas fa-phone"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" data-toggle="tooltip" title="Generate Report">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="dataTables_info" role="status">
                                        Showing {{ $completedOrders->firstItem() ?? 0 }} to {{ $completedOrders->lastItem() ?? 0 }} of {{ $completedOrders->total() }} entries
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation" class="float-right">
                                        <ul class="pagination pagination-sm">
                                            {{-- Previous Page Link --}}
                                            @if ($completedOrders->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $completedOrders->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($completedOrders->links()->elements as $element)
                                                {{-- "Three Dots" Separator --}}
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                                                @endif

                                                {{-- Array Of Links --}}
                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $completedOrders->currentPage())
                                                            <li class="page-item active">
                                                                <span class="page-link">{{ $page }}</span>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($completedOrders->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $completedOrders->nextPageUrl() }}" rel="next">&raquo;</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">&raquo;</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No completed orders in the last 30 days</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion by Location -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card completed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marked-alt mr-2"></i>
                                Completion by Location
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($completionByLocation->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Completed Orders</th>
                                            <th>Avg Delivery Time</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($completionByLocation as $location)
                                        <tr>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                                <strong>{{ $location->location }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $location->completed }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ round($location->avg_days, 1) }} days
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $performance = $location->avg_days <= 2 ? 'Excellent' :
                                                                  ($location->avg_days <= 3 ? 'Good' : 'Needs Improvement');
                                                    $badgeClass = $location->avg_days <= 2 ? 'badge-success' :
                                                                 ($location->avg_days <= 3 ? 'badge-warning' : 'badge-danger');
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $performance }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No location data available</p>
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
    // Completion Trend Chart
    const trendCtx = document.getElementById('completionTrendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($completionTrend->pluck('date')),
            datasets: [{
                label: 'Completed Orders',
                data: @json($completionTrend->pluck('count')),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
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
