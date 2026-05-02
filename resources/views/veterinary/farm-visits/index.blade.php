@extends('layouts.app')

@section('title', 'Farm Visits')

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title ?? 'Farm Visits' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Farm Visits</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Filter Tabs -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="farm-visit-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ !isset($statusFilter) && !isset($typeFilter) ? 'active' : '' }}"
                                       href="{{ route('veterinary.farm-visits.index') }}">
                                        <i class="fas fa-list mr-1"></i> All Visits
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'upcoming' ? 'active' : '' }}"
                                       href="{{ route('veterinary.farm-visits.upcoming') }}">
                                        <i class="fas fa-calendar-alt mr-1"></i> Upcoming
                                        @if($stats['upcoming'] > 0)
                                            <span class="badge badge-warning ml-1">{{ $stats['upcoming'] }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'history' ? 'active' : '' }}"
                                       href="{{ route('veterinary.farm-visits.history') }}">
                                        <i class="fas fa-history mr-1"></i> History
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($typeFilter) && $typeFilter == 'emergency' ? 'active' : '' }}"
                                       href="{{ route('veterinary.farm-visits.emergency') }}">
                                        <i class="fas fa-ambulance mr-1"></i> Emergency
                                        @if($stats['emergency'] > 0)
                                            <span class="badge badge-danger ml-1">{{ $stats['emergency'] }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($typeFilter) && $typeFilter == 'reports' ? 'active' : '' }}"
                                       href="{{ route('veterinary.farm-visits.reports') }}">
                                        <i class="fas fa-file-alt mr-1"></i> Reports
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            @if(isset($stats))
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total'] ?? 0 }}</h3>
                            <p>Total Visits</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <a href="{{ route('veterinary.farm-visits.index') }}" class="small-box-footer">
                            View All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['upcoming'] ?? 0 }}</h3>
                            <p>Upcoming</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="{{ route('veterinary.farm-visits.upcoming') }}" class="small-box-footer">
                            View Upcoming <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['completed'] ?? 0 }}</h3>
                            <p>Completed</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="{{ route('veterinary.farm-visits.history') }}" class="small-box-footer">
                            View History <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['emergency'] ?? 0 }}</h3>
                            <p>Emergency</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <a href="{{ route('veterinary.farm-visits.emergency') }}" class="small-box-footer">
                            View Emergency <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Farm Visits Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title ?? 'All Farm Visits' }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('veterinary.farm-visits.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Schedule Visit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Visit #</th>
                                            <th>Farm/Farmer</th>
                                            <th>Location</th>
                                            <th>Visit Date</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($farmVisits as $visit)
                                        <tr>
                                            <td>
                                                <strong>{{ $visit->visit_number }}</strong>
                                                @if($visit->is_emergency)
                                                    <span class="badge badge-danger ml-1">Emergency</span>
                                                @endif
                                                @if($visit->is_overdue)
                                                    <span class="badge badge-warning ml-1">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="user-block">
                                                    <span class="username">{{ $visit->farm_name }}</span>
                                                    <span class="description">
                                                        @if($visit->farmer)
                                                            {{ $visit->farmer->name }}
                                                            <br><i class="fas fa-phone mr-1"></i>{{ $visit->farmer->phone }}
                                                        @else
                                                            <span class="text-danger">Farmer not found</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td>{{ $visit->location }}</td>
                                            <td>
                                                {{ $visit->scheduled_date->format('M d, Y') }}<br>
                                                <small>{{ $visit->scheduled_date->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light">
                                                    {{ $visit->visit_type_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $visit->status_badge }}">
                                                    {{ ucfirst($visit->visit_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('veterinary.farm-visits.show', $visit) }}" class="btn btn-info btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($visit->visit_status == 'scheduled')
                                                        <a href="{{ route('veterinary.farm-visits.edit', $visit) }}" class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('veterinary.farm-visits.complete', $visit) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Mark as Complete" onclick="return confirm('Mark this visit as completed?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($visit->visit_status == 'completed' && !$visit->report_generated)
                                                        <form action="{{ route('veterinary.farm-visits.generate-report', $visit) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning btn-sm" title="Generate Report" onclick="return confirm('Generate visit report?')">
                                                                <i class="fas fa-file-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-tractor fa-3x text-muted mb-3"></i>
                                                <h4>No farm visits found</h4>
                                                <p class="text-muted">
                                                    @if(isset($statusFilter) && $statusFilter == 'upcoming')
                                                        You don't have any upcoming farm visits.
                                                    @elseif(isset($statusFilter) && $statusFilter == 'history')
                                                        You don't have any completed farm visits.
                                                    @elseif(isset($typeFilter) && $typeFilter == 'emergency')
                                                        You don't have any emergency farm visits.
                                                    @else
                                                        You don't have any farm visits scheduled yet.
                                                    @endif
                                                </p>
                                                <a href="{{ route('veterinary.farm-visits.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i> Schedule First Visit
                                                </a>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <div class="float-right">
                                {{ $farmVisits->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
