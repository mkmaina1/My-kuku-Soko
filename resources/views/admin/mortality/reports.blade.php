@extends('layouts.app')

@section('title', 'Reports & Complaints')

@push('styles')
<style>
    .reports-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border: none;
    }
    .reports-card:hover {
        transform: translateY(-5px);
    }
    .priority-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .priority-urgent { background: #dc3545; color: #fff; }
    .priority-high { background: #fd7e14; color: #fff; }
    .priority-medium { background: #ffc107; color: #000; }
    .priority-low { background: #17a2b8; color: #fff; }
    .status-open { background: #dc3545; color: #fff; }
    .status-investigating { background: #ffc107; color: #000; }
    .status-resolved { background: #28a745; color: #fff; }
    .status-closed { background: #6c757d; color: #fff; }

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
                        <i class="fas fa-clipboard-list text-info mr-2"></i>
                        Reports & Complaints
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Mortality Track</a></li>
                        <li class="breadcrumb-item active">Reports & Complaints</li>
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
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-clipboard-list"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Reports</span>
                            <span class="info-box-number">{{ $stats['total_reports'] }}</span>
                            <span class="info-box-text">All reports</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Open Reports</span>
                            <span class="info-box-number">{{ $stats['open_reports'] }}</span>
                            <span class="info-box-text">Require attention</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-search"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Investigating</span>
                            <span class="info-box-number">{{ $stats['investigating'] }}</span>
                            <span class="info-box-text">Under review</span>
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
                            <span class="info-box-number">{{ $stats['resolved'] }}</span>
                            <span class="info-box-text">Completed cases</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Analysis -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card reports-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Report Types Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($reportTypes->count() > 0)
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="reportTypesChart"></canvas>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No report type data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card reports-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Reports by Status
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($byStatus->count() > 0)
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="reportsStatusChart"></canvas>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No status data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports List -->
            <div class="row">
                <div class="col-12">
                    <div class="card reports-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                All Reports & Complaints
                            </h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Search reports...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($reports->count() > 0)
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="20%">Title</th>
                                            <th width="10%">Type</th>
                                            <th width="10%">Priority</th>
                                            <th width="15%">Reported By</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Assigned To</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $report)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">#{{ $report->id }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $report->created_at->format('M d') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($report->title, 40) }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Order: #{{ $report->order->order_number ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info text-uppercase" style="font-size: 0.8rem;">
                                                    {{ $report->report_type }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($report->priority == 'urgent')
                                                <span class="priority-badge priority-urgent">
                                                    {{ ucfirst($report->priority) }}
                                                </span>
                                                @elseif($report->priority == 'high')
                                                <span class="priority-badge priority-high">
                                                    {{ ucfirst($report->priority) }}
                                                </span>
                                                @elseif($report->priority == 'medium')
                                                <span class="priority-badge priority-medium">
                                                    {{ ucfirst($report->priority) }}
                                                </span>
                                                @else
                                                <span class="priority-badge priority-low">
                                                    {{ ucfirst($report->priority) }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white mr-2"
                                                         style="width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong style="font-size: 0.9rem;">{{ $report->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Anonymous</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->status == 'open')
                                                <span class="badge status-open">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                @elseif($report->status == 'investigating')
                                                <span class="badge status-investigating">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                @elseif($report->status == 'resolved')
                                                <span class="badge status-resolved">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                @else
                                                <span class="badge status-closed">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->assignedTo)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-2"
                                                         style="width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($report->assignedTo->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong style="font-size: 0.9rem;">{{ $report->assignedTo->name }}</strong>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" data-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" data-toggle="tooltip" title="Assign">
                                                        <i class="fas fa-user-tie"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" data-toggle="tooltip" title="Resolve">
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
                                        Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} entries
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation" class="float-right">
                                        <ul class="pagination pagination-sm">
                                            {{-- Previous Page Link --}}
                                            @if ($reports->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $reports->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($reports->links()->elements as $element)
                                                {{-- "Three Dots" Separator --}}
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                                                @endif

                                                {{-- Array Of Links --}}
                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $reports->currentPage())
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
                                            @if ($reports->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $reports->nextPageUrl() }}" rel="next">&raquo;</a>
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
                                <p class="text-success h4">No Reports or Complaints!</p>
                                <p class="text-muted">All systems are functioning properly</p>
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
    // Report Types Chart
    @if($reportTypes->count() > 0)
    const reportTypesCtx = document.getElementById('reportTypesChart').getContext('2d');
    const reportTypesChart = new Chart(reportTypesCtx, {
        type: 'pie',
        data: {
            labels: @json($reportTypes->pluck('report_type')),
            datasets: [{
                data: @json($reportTypes->pluck('count')),
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14',
                    '#ffc107',
                    '#17a2b8',
                    '#28a745',
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
    @endif

    // Reports Status Chart
    @if($byStatus->count() > 0)
    const statusCtx = document.getElementById('reportsStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: @json($byStatus->pluck('status')),
            datasets: [{
                label: 'Reports',
                data: @json($byStatus->pluck('count')),
                backgroundColor: [
                    '#dc3545',
                    '#ffc107',
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
