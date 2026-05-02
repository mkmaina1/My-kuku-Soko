@extends('layouts.app')

@section('title', 'Transport Mortality')

@push('styles')
<style>
    .mortality-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
    }
    .mortality-card:hover {
        transform: translateY(-5px);
    }
    .mortality-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .status-reported { background: #dc3545; color: #fff; }
    .status-investigating { background: #ffc107; color: #000; }
    .status-resolved { background: #28a745; color: #fff; }
    .priority-high { border-left: 4px solid #dc3545; }
    .priority-medium { border-left: 4px solid #ffc107; }
    .priority-low { border-left: 4px solid #17a2b8; }

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
                        <i class="fas fa-truck-loading text-danger mr-2"></i>
                        Transport Mortality
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Mortality Track</a></li>
                        <li class="breadcrumb-item active">Transport Mortality</li>
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
                            <i class="fas fa-skull-crossbones"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Cases</span>
                            <span class="info-box-number">{{ $stats['total_cases'] }}</span>
                            <span class="info-box-text">Reported cases</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Cases</span>
                            <span class="info-box-number">{{ $stats['today_cases'] }}</span>
                            <span class="info-box-text">Reported today</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Weekly Cases</span>
                            <span class="info-box-number">{{ $stats['weekly_cases'] }}</span>
                            <span class="info-box-text">This week</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-percentage"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mortality Rate</span>
                            <span class="info-box-number">{{ $stats['mortality_rate'] }}%</span>
                            <span class="info-box-text">Overall rate</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transport Type Analysis -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mortality-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Mortality by Transport Type
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($byTransportType->count() > 0)
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="transportTypeChart"></canvas>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No transport type data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mortality-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-tie mr-2"></i>
                                Agents with Most Cases
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($byAgent->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Cases</th>
                                            <th>Last Case</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($byAgent as $agent)
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
                                                <span class="badge badge-danger">{{ $agent->transport_mortality_cases_count }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    @if($agent->transportMortalityCases->last())
                                                        {{ $agent->transportMortalityCases->last()->created_at->diffForHumans() }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($agent->transport_mortality_cases_count > 5)
                                                <span class="badge badge-danger">High Risk</span>
                                                @elseif($agent->transport_mortality_cases_count > 2)
                                                <span class="badge badge-warning">Medium Risk</span>
                                                @else
                                                <span class="badge badge-success">Low Risk</span>
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
                                <p class="text-muted">No agent data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transport Mortality Cases List -->
            <div class="row">
                <div class="col-12">
                    <div class="card mortality-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                Transport Mortality Cases
                            </h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Search cases...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($transportCases->count() > 0)
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th width="10%">Case #</th>
                                            <th width="15%">Order</th>
                                            <th width="15%">Transport Type</th>
                                            <th width="10%">Quantity</th>
                                            <th width="15%">Cause</th>
                                            <th width="15%">Agent</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transportCases as $case)
                                        @php
                                            $priorityClass = $case->quantity > 10 ? 'priority-high' :
                                                           ($case->quantity > 5 ? 'priority-medium' : 'priority-low');
                                        @endphp
                                        <tr class="{{ $priorityClass }}">
                                            <td>
                                                <strong class="text-primary">#{{ $case->id }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $case->created_at->format('M d') }}</small>
                                            </td>
                                            <td>
                                                <strong>#{{ $case->order->order_number ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $case->order->user->name ?? 'Customer' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info text-uppercase" style="font-size: 0.8rem;">
                                                    {{ $case->transport_type }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger" style="font-size: 0.9rem;">
                                                    {{ $case->quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ Str::limit($case->cause, 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($case->agent)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-2"
                                                         style="width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($case->agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong style="font-size: 0.9rem;">{{ $case->agent->name }}</strong>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($case->status == 'reported')
                                                <span class="mortality-badge status-reported">
                                                    {{ ucfirst($case->status) }}
                                                </span>
                                                @elseif($case->status == 'investigating')
                                                <span class="mortality-badge status-investigating">
                                                    {{ ucfirst($case->status) }}
                                                </span>
                                                @else
                                                <span class="mortality-badge status-resolved">
                                                    {{ ucfirst($case->status) }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" data-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" data-toggle="tooltip" title="Update Status">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" data-toggle="tooltip" title="Resolve Case">
                                                        <i class="fas fa-check"></i>
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
                                        Showing {{ $transportCases->firstItem() ?? 0 }} to {{ $transportCases->lastItem() ?? 0 }} of {{ $transportCases->total() }} entries
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation" class="float-right">
                                        <ul class="pagination pagination-sm">
                                            {{-- Previous Page Link --}}
                                            @if ($transportCases->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $transportCases->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($transportCases->links()->elements as $element)
                                                {{-- "Three Dots" Separator --}}
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                                                @endif

                                                {{-- Array Of Links --}}
                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $transportCases->currentPage())
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
                                            @if ($transportCases->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $transportCases->nextPageUrl() }}" rel="next">&raquo;</a>
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
                                <p class="text-success h4">No Transport Mortality Cases!</p>
                                <p class="text-muted">All transports are successful</p>
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
    // Transport Type Chart
    @if($byTransportType->count() > 0)
    const transportCtx = document.getElementById('transportTypeChart').getContext('2d');
    const transportChart = new Chart(transportCtx, {
        type: 'bar',
        data: {
            labels: @json($byTransportType->pluck('transport_type')),
            datasets: [{
                label: 'Mortality Cases',
                data: @json($byTransportType->pluck('count')),
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14',
                    '#ffc107',
                    '#17a2b8',
                    '#28a745',
                    '#6c757d'
                ],
                borderColor: '#fff',
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
    @endif

    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
@endpush
