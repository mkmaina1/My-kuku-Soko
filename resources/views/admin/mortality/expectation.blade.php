@extends('layouts.app')

@section('title', 'Expectation Flagged')

@push('styles')
<style>
    .expectation-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
    }
    .expectation-card:hover {
        transform: translateY(-5px);
    }
    .risk-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .risk-high { background: #dc3545; color: #fff; }
    .risk-medium { background: #ffc107; color: #000; }
    .risk-low { background: #28a745; color: #fff; }
    .flagged-high { border-left: 4px solid #dc3545; }
    .flagged-medium { border-left: 4px solid #ffc107; }
    .flagged-low { border-left: 4px solid #28a745; }

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
                        <i class="fas fa-flag text-warning mr-2"></i>
                        Expectation Flagged
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Mortality Track</a></li>
                        <li class="breadcrumb-item active">Expectation Flagged</li>
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
                            <i class="fas fa-flag"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Flagged</span>
                            <span class="info-box-number">{{ $stats['total_flagged'] }}</span>
                            <span class="info-box-text">Orders flagged</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">High Risk</span>
                            <span class="info-box-number">{{ $stats['high_risk'] }}</span>
                            <span class="info-box-text">Critical cases</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Medium Risk</span>
                            <span class="info-box-number">{{ $stats['medium_risk'] }}</span>
                            <span class="info-box-text">Warning cases</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolved</span>
                            <span class="info-box-number">{{ $stats['resolved_cases'] }}</span>
                            <span class="info-box-text">Cases resolved</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Risk Analysis -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card expectation-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Risk Level Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($riskDistribution->count() > 0)
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="riskDistributionChart"></canvas>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No risk distribution data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card expectation-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Common Risk Factors
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(!empty($riskFactors))
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Risk Factor</th>
                                            <th>Frequency</th>
                                            <th>Risk Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riskFactors as $factor => $frequency)
                                        <tr>
                                            <td>
                                                <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                                                <strong>{{ $factor }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $frequency }}%</span>
                                            </td>
                                            <td>
                                                @if($frequency > 30)
                                                <span class="risk-badge risk-high">High</span>
                                                @elseif($frequency > 15)
                                                <span class="risk-badge risk-medium">Medium</span>
                                                @else
                                                <span class="risk-badge risk-low">Low</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No risk factor data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flagged Orders List -->
            <div class="row">
                <div class="col-12">
                    <div class="card expectation-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                Flagged Orders
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
                            @if($flaggedOrders->count() > 0)
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th width="10%">Order #</th>
                                            <th width="20%">Customer</th>
                                            <th width="15%">Order Date</th>
                                            <th width="15%">Destination</th>
                                            <th width="10%">Risk Level</th>
                                            <th width="15%">Agent</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flaggedOrders as $order)
                                        @php
                                            $flaggedClass = $order->mortality_risk_level == 'high' ? 'flagged-high' :
                                                          ($order->mortality_risk_level == 'medium' ? 'flagged-medium' : 'flagged-low');
                                            $riskBadge = $order->mortality_risk_level == 'high' ? 'risk-high' :
                                                        ($order->mortality_risk_level == 'medium' ? 'risk-medium' : 'risk-low');
                                        @endphp
                                        <tr class="{{ $flaggedClass }}">
                                            <td>
                                                <strong class="text-primary">#{{ $order->order_number }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $order->created_at->format('M d') }}</small>
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
                                                <small class="text-muted">
                                                    @php
                                                        $address = $order->shipping_address;
                                                        if (strlen($address) > 30) {
                                                            $address = substr($address, 0, 30) . '...';
                                                        }
                                                    @endphp
                                                    {{ $address }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="risk-badge {{ $riskBadge }}">
                                                    {{ ucfirst($order->mortality_risk_level) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($order->agent)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-2"
                                                         style="width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($order->agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong style="font-size: 0.9rem;">{{ $order->agent->name }}</strong>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" data-toggle="tooltip" title="View Order">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" data-toggle="tooltip" title="Update Risk">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" data-toggle="tooltip" title="Mark Resolved">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-outline-warning" data-toggle="tooltip" title="Add Note">
                                                        <i class="fas fa-sticky-note"></i>
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
                                        Showing {{ $flaggedOrders->firstItem() ?? 0 }} to {{ $flaggedOrders->lastItem() ?? 0 }} of {{ $flaggedOrders->total() }} entries
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation" class="float-right">
                                        <ul class="pagination pagination-sm">
                                            {{-- Previous Page Link --}}
                                            @if ($flaggedOrders->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $flaggedOrders->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($flaggedOrders->links()->elements as $element)
                                                {{-- "Three Dots" Separator --}}
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                                                @endif

                                                {{-- Array Of Links --}}
                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $flaggedOrders->currentPage())
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
                                            @if ($flaggedOrders->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $flaggedOrders->nextPageUrl() }}" rel="next">&raquo;</a>
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
                                <p class="text-success h4">No Flagged Orders!</p>
                                <p class="text-muted">All orders are within acceptable risk levels</p>
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
    // Risk Distribution Chart
    @if($riskDistribution->count() > 0)
    const riskCtx = document.getElementById('riskDistributionChart').getContext('2d');
    const riskChart = new Chart(riskCtx, {
        type: 'doughnut',
        data: {
            labels: @json($riskDistribution->pluck('mortality_risk_level')),
            datasets: [{
                data: @json($riskDistribution->pluck('count')),
                backgroundColor: [
                    '#dc3545',
                    '#ffc107',
                    '#28a745'
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
    @endif

    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
@endpush
