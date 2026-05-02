@extends('layouts.app')

@section('title', 'Delayed Orders')

@push('styles')
<style>
    .delay-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
    }
    .delay-card:hover {
        transform: translateY(-5px);
    }
    .delay-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .delay-1 { background: #ffc107; color: #000; }
    .delay-2-3 { background: #fd7e14; color: #fff; }
    .delay-3plus { background: #dc3545; color: #fff; }
    .urgency-high { border-left: 4px solid #dc3545; }
    .urgency-medium { border-left: 4px solid #fd7e14; }
    .urgency-low { border-left: 4px solid #ffc107; }

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
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        Delayed Orders
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Logistics</a></li>
                        <li class="breadcrumb-item active">Delays</li>
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
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Delayed</span>
                            <span class="info-box-number">{{ $stats['total_delayed'] }}</span>
                            <span class="info-box-text">Orders pending</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">1 Day Delay</span>
                            <span class="info-box-number">{{ $stats['delayed_by_1_day'] }}</span>
                            <span class="info-box-text">Yesterday's orders</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-orange elevation-1" style="background-color: #fd7e14;">
                            <i class="fas fa-calendar-week"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">2-3 Days Delay</span>
                            <span class="info-box-number">{{ $stats['delayed_by_2_3_days'] }}</span>
                            <span class="info-box-text">Moderate delay</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-dark elevation-1">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">3+ Days Delay</span>
                            <span class="info-box-number">{{ $stats['delayed_over_3_days'] }}</span>
                            <span class="info-box-text">Critical delay</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delay Analysis -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card delay-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Delay Reasons Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="delayReasonsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card delay-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-tie mr-2"></i>
                                Agents with Most Delays
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($agentPerformance->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Delayed Orders</th>
                                            <th>Completed Orders</th>
                                            <th>Delay Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agentPerformance as $agent)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-info text-white mr-2"
                                                         style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $agent->name }}</strong>
                                                        <div class="small text-muted">{{ $agent->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">{{ $agent->agent_orders_count }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $agent->agent_orders_count }}</span>
                                            </td>
                                            <td>
                                                @if($agent->delay_rate > 20)
                                                <span class="badge badge-danger">{{ $agent->delay_rate }}%</span>
                                                @elseif($agent->delay_rate > 10)
                                                <span class="badge badge-warning">{{ $agent->delay_rate }}%</span>
                                                @else
                                                <span class="badge badge-success">{{ $agent->delay_rate }}%</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No agent delay data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delayed Orders List -->
            <div class="row">
                <div class="col-12">
                    <div class="card delay-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                Delayed Orders List
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
                            @if($delayedOrders->count() > 0)
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th width="10%">Order #</th>
                                            <th width="20%">Customer</th>
                                            <th width="15%">Scheduled Date</th>
                                            <th width="15%">Days Delayed</th>
                                            <th width="20%">Agent</th>
                                            <th width="10%">Status</th>
                                            <th width="5%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($delayedOrders as $order)
                                        @php
                                            $daysDelayed = Carbon\Carbon::parse($order->created_at)->diffInDays(Carbon\Carbon::today());
                                            $urgencyClass = $daysDelayed >= 3 ? 'urgency-high' : ($daysDelayed >= 2 ? 'urgency-medium' : 'urgency-low');
                                            $delayBadge = $daysDelayed == 1 ? 'delay-1' : ($daysDelayed <= 3 ? 'delay-2-3' : 'delay-3plus');
                                        @endphp
                                        <tr class="{{ $urgencyClass }}">
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
                                                    {{ $order->created_at->format('M d, Y') }}
                                                    <br>
                                                    <small>{{ $order->created_at->format('h:i A') }}</small>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="delay-badge {{ $delayBadge }}">
                                                    {{ $daysDelayed }} day{{ $daysDelayed > 1 ? 's' : '' }}
                                                </span>
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
                                                <span class="badge badge-warning text-uppercase" style="font-size: 0.8rem;">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" data-toggle="tooltip" title="Contact Customer">
                                                        <i class="fas fa-phone"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" data-toggle="tooltip" title="Update Status">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                    <!-- <button class="btn btn-outline-success" data-toggle="tooltip" title="Reschedule">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button> -->
                                                    <!-- <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary" data-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a> -->
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
                                        Showing {{ $delayedOrders->firstItem() ?? 0 }} to {{ $delayedOrders->lastItem() ?? 0 }} of {{ $delayedOrders->total() }} entries
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation" class="float-right">
                                        <ul class="pagination pagination-sm">
                                            {{-- Previous Page Link --}}
                                            @if ($delayedOrders->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $delayedOrders->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($delayedOrders->links()->elements as $element)
                                                {{-- "Three Dots" Separator --}}
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                                                @endif

                                                {{-- Array Of Links --}}
                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $delayedOrders->currentPage())
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
                                            @if ($delayedOrders->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $delayedOrders->nextPageUrl() }}" rel="next">&raquo;</a>
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
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <p class="text-success h4">No Delayed Orders!</p>
                                <p class="text-muted">All orders are on schedule</p>
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
    // Delay Reasons Chart
    const delayCtx = document.getElementById('delayReasonsChart').getContext('2d');
    const delayChart = new Chart(delayCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($delayReasons)),
            datasets: [{
                data: @json(array_values($delayReasons)),
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14',
                    '#ffc107',
                    '#17a2b8',
                    '#6c757d'
                ],
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true
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
