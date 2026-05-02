@extends('layouts.app')

@section('title', 'Farm Visit #' . $farmVisit->visit_number)

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Farm Visit Details: {{ $farmVisit->visit_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.farm-visits.index') }}">Farm Visits</a></li>
                        <li class="breadcrumb-item active">Visit Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Visit Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-list mr-1"></i>
                                Visit Information
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-{{ $farmVisit->priority_badge }} badge-lg mr-2">
                                    {{ ucfirst($farmVisit->priority) }}
                                </span>
                                <span class="badge badge-{{ $farmVisit->status_badge }} badge-lg">
                                    {{ ucfirst($farmVisit->visit_status) }}
                                </span>
                                @if($farmVisit->is_emergency)
                                    <span class="badge badge-danger ml-2">EMERGENCY</span>
                                @endif
                                @if($farmVisit->is_overdue)
                                    <span class="badge badge-warning ml-2">OVERDUE</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Visit #:</dt>
                                        <dd class="col-sm-8">
                                            <strong>{{ $farmVisit->visit_number }}</strong>
                                        </dd>

                                        <dt class="col-sm-4">Farmer:</dt>
                                        <dd class="col-sm-8">
                                            <div class="user-block">
                                                <span class="username">{{ $farmVisit->farmer->name }}</span>
                                                <span class="description">
                                                    <i class="fas fa-phone mr-1"></i>{{ $farmVisit->farmer->phone }}
                                                    @if($farmVisit->farmer->address)
                                                        <br><i class="fas fa-map-marker-alt mr-1"></i>{{ $farmVisit->farmer->address }}
                                                    @endif
                                                </span>
                                            </div>
                                        </dd>

                                        <dt class="col-sm-4">Farm:</dt>
                                        <dd class="col-sm-8">
                                            <strong>{{ $farmVisit->farm_name }}</strong><br>
                                            <small class="text-muted">
                                                {{ $farmVisit->location }}
                                                @if($farmVisit->county)
                                                    , {{ $farmVisit->county }}
                                                @endif
                                            </small>
                                            @if($farmVisit->farm_address)
                                                <br><small>{{ $farmVisit->farm_address }}</small>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Created:</dt>
                                        <dd class="col-sm-8">
                                            {{ $farmVisit->created_at->format('F d, Y h:i A') }}
                                            <br><small>({{ $farmVisit->created_at->diffForHumans() }})</small>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Visit Type:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-light">
                                                {{ $farmVisit->visit_type_name }}
                                            </span>
                                        </dd>

                                        <dt class="col-sm-4">Poultry Type:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-info">
                                                {{ $farmVisit->poultry_type_name }}
                                            </span>
                                        </dd>

                                        <dt class="col-sm-4">Flock Size:</dt>
                                        <dd class="col-sm-8">
                                            @if($farmVisit->total_flock_size)
                                                <span class="badge bg-secondary">{{ number_format($farmVisit->total_flock_size) }} birds</span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Age:</dt>
                                        <dd class="col-sm-8">
                                            @if($farmVisit->age_weeks)
                                                {{ $farmVisit->age_weeks }} weeks
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Scheduled:</dt>
                                        <dd class="col-sm-8">
                                            {{ $farmVisit->scheduled_date->format('F d, Y h:i A') }}
                                            @if($farmVisit->is_overdue)
                                                <br><span class="badge badge-warning">Overdue</span>
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visit Purpose -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-bullseye mr-1"></i>
                                Visit Purpose
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-primary">
                                <p class="mb-0">{{ $farmVisit->visit_purpose }}</p>
                            </div>

                            @if($farmVisit->specific_issues)
                            <div class="mt-3">
                                <h5>Specific Issues:</h5>
                                <p>{{ $farmVisit->specific_issues }}</p>
                            </div>
                            @endif

                            @if($farmVisit->is_emergency && $farmVisit->emergency_details)
                            <div class="mt-3">
                                <div class="alert alert-danger">
                                    <h5><i class="fas fa-exclamation-triangle mr-2"></i> Emergency Details</h5>
                                    <p class="mb-0">{{ $farmVisit->emergency_details }}</p>
                                    @if($farmVisit->emergency_contact || $farmVisit->emergency_phone)
                                        <div class="mt-2">
                                            <strong>Emergency Contact:</strong>
                                            {{ $farmVisit->emergency_contact ?? 'N/A' }}
                                            @if($farmVisit->emergency_phone)
                                                | <strong>Phone:</strong> {{ $farmVisit->emergency_phone }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observations & Findings (Only if visit in progress/completed) -->
            @if(in_array($farmVisit->visit_status, ['in_progress', 'completed']))
            <div class="row">
                <!-- Observations -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-search mr-1"></i>
                                Observations
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($farmVisit->observations)
                                <div class="callout callout-info">
                                    <p>{{ $farmVisit->observations }}</p>
                                </div>
                            @else
                                <p class="text-muted">No observations recorded yet.</p>
                            @endif

                            @if($farmVisit->issues_found)
                                <div class="mt-3">
                                    <h5>Issues Found:</h5>
                                    <p>{{ $farmVisit->issues_found }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Poultry Health Metrics -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Health Metrics
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($farmVisit->mortality_rate)
                                <div class="col-md-6">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-skull-crossbones"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Mortality Rate</span>
                                            <span class="info-box-number">{{ $farmVisit->mortality_rate }}%</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($farmVisit->feed_intake)
                                <div class="col-md-6">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-wheat-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Feed Intake</span>
                                            <span class="info-box-number">{{ $farmVisit->feed_intake }} kg/day</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($farmVisit->water_intake)
                                <div class="col-md-6">
                                    <div class="info-box bg-primary">
                                        <span class="info-box-icon"><i class="fas fa-tint"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Water Intake</span>
                                            <span class="info-box-number">{{ $farmVisit->water_intake }} L/day</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($farmVisit->egg_production)
                                <div class="col-md-6">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-egg"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Egg Production</span>
                                            <span class="info-box-number">{{ $farmVisit->egg_production }}%</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Recommendations -->
            @if($farmVisit->diagnosis || $farmVisit->recommendations)
            <div class="row">
                <!-- Diagnosis -->
                @if($farmVisit->diagnosis)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">
                                <i class="fas fa-stethoscope mr-1"></i>
                                Diagnosis
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-warning">
                                <p>{{ $farmVisit->diagnosis }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recommendations -->
                @if($farmVisit->recommendations)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-check mr-1"></i>
                                Recommendations
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-success">
                                <p>{{ $farmVisit->recommendations }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Treatment & Management -->
            <div class="row">
                @if($farmVisit->treatment_administered)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-pills mr-1"></i>
                                Treatment
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $farmVisit->treatment_administered }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($farmVisit->vaccinations_administered)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-syringe mr-1"></i>
                                Vaccinations
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $farmVisit->vaccinations_administered }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($farmVisit->biosecurity_assessment)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Biosecurity
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $farmVisit->biosecurity_assessment }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Management Advice & Follow-up -->
            <div class="row">
                @if($farmVisit->management_advice)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs mr-1"></i>
                                Management Advice
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $farmVisit->management_advice }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($farmVisit->follow_up_plan)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-check mr-1"></i>
                                Follow-up Plan
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $farmVisit->follow_up_plan }}</p>
                            @if($farmVisit->follow_up_date)
                                <div class="mt-2">
                                    <strong>Next Follow-up:</strong>
                                    {{ $farmVisit->follow_up_date->format('F d, Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Visit Summary -->
            @if($farmVisit->visit_summary)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt mr-1"></i>
                                Visit Summary
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-secondary">
                                <p>{{ $farmVisit->visit_summary }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Visit Timing -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1"></i>
                                Visit Timing
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="callout callout-info">
                                        <h5>Scheduled</h5>
                                        <p class="mb-0">{{ $farmVisit->scheduled_date->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                @if($farmVisit->actual_start_time)
                                <div class="col-md-3">
                                    <div class="callout callout-warning">
                                        <h5>Started</h5>
                                        <p class="mb-0">{{ $farmVisit->actual_start_time->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                                @if($farmVisit->actual_end_time)
                                <div class="col-md-3">
                                    <div class="callout callout-success">
                                        <h5>Ended</h5>
                                        <p class="mb-0">{{ $farmVisit->actual_end_time->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                                @if($farmVisit->duration_minutes)
                                <div class="col-md-3">
                                    <div class="callout callout-primary">
                                        <h5>Duration</h5>
                                        <p class="mb-0">{{ $farmVisit->duration_minutes }} minutes</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                Financial Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-car"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Transport Cost</span>
                                            <span class="info-box-number">KES {{ number_format($farmVisit->transport_cost ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-user-md"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Consultation Fee</span>
                                            <span class="info-box-number">KES {{ number_format($farmVisit->consultation_fee ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-money-check-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Amount</span>
                                            <span class="info-box-number">KES {{ number_format($farmVisit->total_amount ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-{{ $farmVisit->payment_status == 'paid' ? 'success' : ($farmVisit->payment_status == 'pending' ? 'warning' : 'secondary') }}">
                                        <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Payment Status</span>
                                            <span class="info-box-number">{{ ucfirst($farmVisit->payment_status) }}</span>
                                            @if($farmVisit->balance && $farmVisit->balance > 0)
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ (($farmVisit->total_amount - $farmVisit->balance) / $farmVisit->total_amount) * 100 }}%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Balance: KES {{ number_format($farmVisit->balance, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($farmVisit->visit_status == 'scheduled')
                                        <a href="{{ route('veterinary.farm-visits.edit', $farmVisit) }}" class="btn btn-primary">
                                            <i class="fas fa-edit mr-1"></i> Update Visit
                                        </a>
                                        <form action="{{ route('veterinary.farm-visits.complete', $farmVisit) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this visit as in progress?')">
                                                <i class="fas fa-play mr-1"></i> Start Visit
                                            </button>
                                        </form>
                                    @endif

                                    @if($farmVisit->visit_status == 'in_progress')
                                        <a href="{{ route('veterinary.farm-visits.edit', $farmVisit) }}" class="btn btn-warning">
                                            <i class="fas fa-edit mr-1"></i> Record Findings
                                        </a>
                                        <form action="{{ route('veterinary.farm-visits.complete', $farmVisit) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this visit as completed?')">
                                                <i class="fas fa-check mr-1"></i> Complete Visit
                                            </button>
                                        </form>
                                    @endif

                                    @if($farmVisit->visit_status == 'completed' && !$farmVisit->report_generated)
                                        <form action="{{ route('veterinary.farm-visits.generate-report', $farmVisit) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info" onclick="return confirm('Generate visit report?')">
                                                <i class="fas fa-file-alt mr-1"></i> Generate Report
                                            </button>
                                        </form>
                                    @endif

                                    @if($farmVisit->is_emergency && $farmVisit->visit_status != 'completed')
                                        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#emergencyModal">
                                            <i class="fas fa-ambulance mr-1"></i> Emergency Update
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('veterinary.farm-visits.index') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Visits
                                    </a>
                                    <button class="btn btn-secondary" onclick="window.print()">
                                        <i class="fas fa-print mr-1"></i> Print
                                    </button>
                                    @if($farmVisit->visit_status == 'scheduled')
                                        <a href="{{ route('veterinary.farm-visits.edit', $farmVisit) }}" class="btn btn-warning">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Emergency Update Modal -->
@if($farmVisit->is_emergency && $farmVisit->visit_status != 'completed')
<div class="modal fade" id="emergencyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Emergency Update</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('veterinary.farm-visits.emergency', $farmVisit) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="emergency_details">Emergency Update *</label>
                        <textarea class="form-control" id="emergency_details" name="emergency_details" rows="4" required placeholder="Provide emergency update...">{{ $farmVisit->emergency_details }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emergency_contact">Emergency Contact</label>
                                <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="{{ $farmVisit->emergency_contact }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emergency_phone">Emergency Phone</label>
                                <input type="tel" class="form-control" id="emergency_phone" name="emergency_phone" value="{{ $farmVisit->emergency_phone }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Update Emergency</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.callout {
    border-left-width: 5px;
    border-radius: 0.25rem;
}
.user-block .username {
    font-size: 16px;
    font-weight: 600;
}
.user-block .description {
    font-size: 13px;
    color: #6c757d;
}
.info-box {
    margin-bottom: 0;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Print styling
    $('button[onclick="window.print()"]').click(function() {
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    });
});
</script>
@endpush
