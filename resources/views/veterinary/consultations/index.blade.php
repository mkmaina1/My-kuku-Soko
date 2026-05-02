@extends('layouts.app')

@section('title', 'Poultry Consultations')

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Poultry Consultations</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Consultations</li>
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
                            <ul class="nav nav-tabs" id="consultation-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ !isset($statusFilter) && !isset($typeFilter) && !isset($priorityFilter) ? 'active' : '' }}"
                                       href="{{ route('veterinary.consultations.index') }}">
                                        <i class="fas fa-list mr-1"></i> All Consultations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'pending' ? 'active' : '' }}"
                                       href="{{ route('veterinary.consultations.pending') }}">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                        @if($stats['pending'] > 0)
                                            <span class="badge badge-danger ml-1">{{ $stats['pending'] }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'completed' ? 'active' : '' }}"
                                       href="{{ route('veterinary.consultations.completed') }}">
                                        <i class="fas fa-check-circle mr-1"></i> Completed
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($typeFilter) && $typeFilter == 'telemedicine' ? 'active' : '' }}"
                                       href="{{ route('veterinary.consultations.telemedicine') }}">
                                        <i class="fas fa-video mr-1"></i> Telemedicine
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($typeFilter) && $typeFilter == 'follow_up' ? 'active' : '' }}"
                                       href="{{ route('veterinary.consultations.follow-ups') }}">
                                        <i class="fas fa-sync-alt mr-1"></i> Follow-ups
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($priorityFilter) && $priorityFilter == 'emergency' ? 'active' : '' }}"
                                       href="{{ route('veterinary.consultations.emergency') }}">
                                        <i class="fas fa-ambulance mr-1"></i> Emergency
                                        @if($stats['emergency'] > 0)
                                            <span class="badge badge-danger ml-1">{{ $stats['emergency'] }}</span>
                                        @endif
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
                <p>Total Consultations</p>
            </div>
            <div class="icon">
                <i class="fas fa-comment-medical"></i>
            </div>
            <a href="{{ route('veterinary.consultations.index') }}" class="small-box-footer">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending'] ?? 0 }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('veterinary.consultations.pending') }}" class="small-box-footer">
                View Pending <i class="fas fa-arrow-circle-right"></i>
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
            <a href="{{ route('veterinary.consultations.completed') }}" class="small-box-footer">
                View Completed <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['emergency'] ?? 0 }}</h3>
                <p>Emergency Cases</p>
            </div>
            <div class="icon">
                <i class="fas fa-ambulance"></i>
            </div>
            <a href="{{ route('veterinary.consultations.emergency') }}" class="small-box-footer">
                View Emergency <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
@endif

            <!-- Poultry Type Distribution -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Poultry Type Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-drumstick-bite"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Broilers</span>
                                            <span class="info-box-number">
                                                {{ $consultations->where('poultry_type', 'broilers')->count() }}
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" style="width: {{ $consultations->count() > 0 ? ($consultations->where('poultry_type', 'broilers')->count() / $consultations->count()) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-egg"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Layers</span>
                                            <span class="info-box-number">
                                                {{ $consultations->where('poultry_type', 'layers')->count() }}
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" style="width: {{ $consultations->count() > 0 ? ($consultations->where('poultry_type', 'layers')->count() / $consultations->count()) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-success"><i class="fas fa-kiwi-bird"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Kienyeji</span>
                                            <span class="info-box-number">
                                                {{ $consultations->where('poultry_type', 'kienyeji')->count() }}
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: {{ $consultations->count() > 0 ? ($consultations->where('poultry_type', 'kienyeji')->count() / $consultations->count()) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-info"><i class="fas fa-seedling"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Breeding</span>
                                            <span class="info-box-number">
                                                {{ $consultations->where('poultry_type', 'breeding')->count() }}
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" style="width: {{ $consultations->count() > 0 ? ($consultations->where('poultry_type', 'breeding')->count() / $consultations->count()) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultations Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title ?? 'All Poultry Consultations' }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('veterinary.consultations.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> New Consultation
                                </a>
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <h6 class="dropdown-header">Filter by Status</h6>
                                        <a class="dropdown-item" href="{{ route('veterinary.consultations.index') }}">All</a>
                                        <a class="dropdown-item" href="{{ route('veterinary.consultations.pending') }}">Pending</a>
                                        <a class="dropdown-item" href="{{ route('veterinary.consultations.completed') }}">Completed</a>
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header">Filter by Type</h6>
                                        <a class="dropdown-item" href="{{ route('veterinary.consultations.telemedicine') }}">Telemedicine</a>
                                        <a class="dropdown-item" href="{{ route('veterinary.consultations.follow-ups') }}">Follow-ups</a>
                                        <a class="dropdown-item" href="{{ route('veterinary.consultations.emergency') }}">Emergency</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Consultation #</th>
                                            <th>Farmer</th>
                                            <th>Poultry Type</th>
                                            <th>Flock Size</th>
                                            <th>Symptoms</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($consultations as $consultation)
                                        <tr>
                                            <td>
                                                <strong>{{ $consultation->consultation_number }}</strong>
                                                @if($consultation->is_overdue)
                                                    <span class="badge badge-danger ml-1">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($consultation->farmer)
                                                    <div class="user-block">
                                                        <span class="username">{{ $consultation->farmer->name }}</span>
                                                        <span class="description">{{ $consultation->farmer->phone }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-danger">Farmer not found</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light">
                                                    <i class="fas fa-{{ $consultation->poultry_type == 'broilers' ? 'drumstick-bite' : ($consultation->poultry_type == 'layers' ? 'egg' : 'kiwi-bird') }} mr-1"></i>
                                                    {{ $consultation->poultry_type_name }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($consultation->flock_size)
                                                    <span class="badge bg-secondary">
                                                        {{ number_format($consultation->flock_size) }} birds
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $consultation->symptoms }}">
                                                    {{ Str::limit($consultation->symptoms, 50) }}
                                                </div>
                                                @if($consultation->mortality_rate)
                                                    <small class="text-danger">
                                                        <i class="fas fa-skull-crossbones"></i> {{ $consultation->mortality_rate }}% mortality
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $consultation->priority_badge }}">
                                                    {{ ucfirst($consultation->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $consultation->status_badge }}">
                                                    {{ ucfirst($consultation->consultation_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $consultation->created_at->format('M d, Y') }}<br>
                                                <small>{{ $consultation->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('veterinary.consultations.show', $consultation) }}" class="btn btn-info btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($consultation->consultation_status == 'pending')
                                                        <a href="{{ route('veterinary.consultations.edit', $consultation) }}" class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('veterinary.consultations.complete', $consultation) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Mark as Complete" onclick="return confirm('Mark this consultation as completed?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($consultation->priority == 'emergency' && $consultation->consultation_status == 'pending')
                                                        <a href="#" class="btn btn-danger btn-sm" title="Emergency Action">
                                                            <i class="fas fa-ambulance"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h4>No consultations found</h4>
                                                <p class="text-muted">
                                                    @if(isset($statusFilter) && $statusFilter == 'pending')
                                                        You don't have any pending consultations.
                                                    @elseif(isset($typeFilter) && $typeFilter == 'telemedicine')
                                                        You don't have any telemedicine consultations.
                                                    @elseif(isset($priorityFilter) && $priorityFilter == 'emergency')
                                                        You don't have any emergency cases.
                                                    @else
                                                        You don't have any consultations yet.
                                                    @endif
                                                </p>
                                                <a href="{{ route('veterinary.consultations.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i> Create First Consultation
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
                                {{ $consultations->links() }}
                            </div>
                            <div class="float-left">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#"><i class="fas fa-file-excel text-success mr-2"></i> Excel</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-file-pdf text-danger mr-2"></i> PDF</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-file-csv text-primary mr-2"></i> CSV</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Quick Statistics
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-6 text-center">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Avg. Response Time</small>
                                        <br>
                                        <strong class="h4">2.4 hrs</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <div class="callout callout-success">
                                        <small class="text-muted">Completion Rate</small>
                                        <br>
                                        <strong class="h4">85%</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <div class="callout callout-warning">
                                        <small class="text-muted">Avg. Flock Size</small>
                                        <br>
                                        <strong class="h4">1,250</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <div class="callout callout-danger">
                                        <small class="text-muted">Avg. Mortality Rate</small>
                                        <br>
                                        <strong class="h4">3.2%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.callout {
    border-radius: 0.25rem;
    margin-bottom: 0;
}
.user-block .username {
    font-size: 14px;
    font-weight: 600;
}
.user-block .description {
    font-size: 12px;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[title]').tooltip();

    // Auto-refresh for emergency cases
    @if(isset($priorityFilter) && $priorityFilter == 'emergency')
    setInterval(function() {
        $.ajax({
            url: '{{ route("veterinary.consultations.emergency") }}',
            type: 'GET',
            success: function(data) {
                // You can update specific elements or reload the page
                console.log('Emergency cases refreshed');
            }
        });
    }, 30000); // Refresh every 30 seconds
    @endif
});
</script>
@endpush
