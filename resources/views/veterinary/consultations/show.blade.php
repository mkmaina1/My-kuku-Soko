@extends('layouts.app')

@section('title', 'Consultation #' . $consultation->consultation_number)

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Consultation Details: {{ $consultation->consultation_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.consultations.index') }}">Consultations</a></li>
                        <li class="breadcrumb-item active">Consultation Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Consultation Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-list mr-1"></i>
                                Consultation Information
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-{{ $consultation->priority_badge }} badge-lg mr-2">
                                    {{ ucfirst($consultation->priority) }}
                                </span>
                                <span class="badge badge-{{ $consultation->status_badge }} badge-lg">
                                    {{ ucfirst($consultation->consultation_status) }}
                                </span>
                                @if($consultation->is_overdue)
                                    <span class="badge badge-danger ml-2">OVERDUE</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Consultation #:</dt>
                                        <dd class="col-sm-8">
                                            <strong>{{ $consultation->consultation_number }}</strong>
                                        </dd>

                                        <dt class="col-sm-4">Farmer:</dt>
                                        <dd class="col-sm-8">
                                            <div class="user-block">
                                                <span class="username">{{ $consultation->farmer->name }}</span>
                                                <span class="description">
                                                    <i class="fas fa-phone mr-1"></i>{{ $consultation->farmer->phone }}
                                                    @if($consultation->farmer->address)
                                                        <br><i class="fas fa-map-marker-alt mr-1"></i>{{ $consultation->farmer->address }}
                                                    @endif
                                                </span>
                                            </div>
                                        </dd>

                                        <dt class="col-sm-4">Farm:</dt>
                                        <dd class="col-sm-8">
                                            {{ $consultation->farm_name ?? 'Not specified' }}
                                            @if($consultation->location)
                                                <br><small class="text-muted">{{ $consultation->location }}</small>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Created:</dt>
                                        <dd class="col-sm-8">
                                            {{ $consultation->created_at->format('F d, Y h:i A') }}
                                            <br><small>({{ $consultation->created_at->diffForHumans() }})</small>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Poultry Type:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-light">
                                                <i class="fas fa-{{ $consultation->poultry_type == 'broilers' ? 'drumstick-bite' : ($consultation->poultry_type == 'layers' ? 'egg' : 'kiwi-bird') }} mr-1"></i>
                                                {{ $consultation->poultry_type_name }}
                                            </span>
                                        </dd>

                                        <dt class="col-sm-4">Flock Size:</dt>
                                        <dd class="col-sm-8">
                                            @if($consultation->flock_size)
                                                <span class="badge bg-secondary">{{ number_format($consultation->flock_size) }} birds</span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Age:</dt>
                                        <dd class="col-sm-8">
                                            @if($consultation->age_weeks)
                                                {{ $consultation->age_weeks }} weeks
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Consultation Type:</dt>
                                        <dd class="col-sm-8">
                                            {{ ucfirst(str_replace('_', ' ', $consultation->consultation_type)) }}
                                        </dd>

                                        @if($consultation->appointment_date)
                                        <dt class="col-sm-4">Appointment:</dt>
                                        <dd class="col-sm-8">
                                            {{ $consultation->appointment_date->format('F d, Y h:i A') }}
                                            @if($consultation->appointment_date->isPast() && $consultation->consultation_status == 'pending')
                                                <br><span class="badge badge-danger">Overdue</span>
                                            @endif
                                        </dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Symptoms & Observations -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Symptoms & Observations
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-warning">
                                <p class="mb-0">{{ $consultation->symptoms }}</p>
                            </div>

                            @if($consultation->observations)
                            <div class="mt-3">
                                <h5>Additional Observations:</h5>
                                <p>{{ $consultation->observations }}</p>
                            </div>
                            @endif

                            <!-- Vital Stats -->
                            <div class="row mt-3">
                                @if($consultation->mortality_rate)
                                <div class="col-md-3">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-skull-crossbones"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Mortality Rate</span>
                                            <span class="info-box-number">{{ $consultation->mortality_rate }}%</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($consultation->feed_intake)
                                <div class="col-md-3">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-wheat-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Feed Intake</span>
                                            <span class="info-box-number">{{ $consultation->feed_intake }} kg/day</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($consultation->water_intake)
                                <div class="col-md-3">
                                    <div class="info-box bg-primary">
                                        <span class="info-box-icon"><i class="fas fa-tint"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Water Intake</span>
                                            <span class="info-box-number">{{ $consultation->water_intake }} L/day</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($consultation->flock_size)
                                <div class="col-md-3">
                                    <div class="info-box bg-secondary">
                                        <span class="info-box-icon"><i class="fas fa-kiwi-bird"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Flock Size</span>
                                            <span class="info-box-number">{{ number_format($consultation->flock_size) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Treatment (Only if completed) -->
            @if($consultation->consultation_status == 'completed' || $consultation->consultation_status == 'in_progress')
            <div class="row">
                <!-- Diagnosis -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-stethoscope mr-1"></i>
                                Diagnosis
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($consultation->diagnosis)
                                <div class="callout callout-info">
                                    <h5>Primary Diagnosis:</h5>
                                    <p>{{ $consultation->diagnosis }}</p>
                                </div>
                            @else
                                <p class="text-muted">No diagnosis recorded yet.</p>
                            @endif

                            @if($consultation->differential_diagnosis)
                                <div class="mt-3">
                                    <h5>Differential Diagnosis:</h5>
                                    <p>{{ $consultation->differential_diagnosis }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Treatment Plan -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                <i class="fas fa-prescription-bottle-alt mr-1"></i>
                                Treatment Plan
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($consultation->treatment_plan)
                                <div class="callout callout-success">
                                    <p>{{ $consultation->treatment_plan }}</p>
                                </div>
                            @else
                                <p class="text-muted">No treatment plan recorded yet.</p>
                            @endif

                            @if($consultation->medications)
                                <div class="mt-3">
                                    <h5>Medications:</h5>
                                    <p>{{ $consultation->medications }}</p>
                                </div>
                            @endif

                            @if($consultation->vaccinations)
                                <div class="mt-3">
                                    <h5>Vaccinations:</h5>
                                    <p>{{ $consultation->vaccinations }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-check mr-1"></i>
                                Recommendations
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($consultation->biosecurity_measures)
                                <div class="col-md-4">
                                    <div class="callout callout-primary">
                                        <h5>Biosecurity Measures:</h5>
                                        <p>{{ $consultation->biosecurity_measures }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($consultation->feeding_recommendations)
                                <div class="col-md-4">
                                    <div class="callout callout-warning">
                                        <h5>Feeding Recommendations:</h5>
                                        <p>{{ $consultation->feeding_recommendations }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($consultation->management_recommendations)
                                <div class="col-md-4">
                                    <div class="callout callout-info">
                                        <h5>Management Recommendations:</h5>
                                        <p>{{ $consultation->management_recommendations }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($consultation->follow_up_instructions)
                            <div class="mt-3">
                                <h5>Follow-up Instructions:</h5>
                                <div class="callout callout-secondary">
                                    <p>{{ $consultation->follow_up_instructions }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Prescription (If issued) -->
            @if($consultation->prescription_issued)
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-prescription mr-1"></i>
                                Prescription
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle mr-2"></i> Prescription Issued</h5>
                                <p class="mb-0">{{ $consultation->prescription_notes }}</p>
                            </div>
                            @if($consultation->follow_up_date)
                                <div class="mt-3">
                                    <strong>Follow-up Date:</strong>
                                    {{ $consultation->follow_up_date->format('F d, Y') }}
                                </div>
                            @endif
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
                                    @if($consultation->consultation_status == 'pending')
                                        <a href="{{ route('veterinary.consultations.edit', $consultation) }}" class="btn btn-primary">
                                            <i class="fas fa-edit mr-1"></i> Update Diagnosis
                                        </a>
                                        <form action="{{ route('veterinary.consultations.complete', $consultation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this consultation as completed?')">
                                                <i class="fas fa-check mr-1"></i> Mark as Completed
                                            </button>
                                        </form>
                                    @endif

                                    @if($consultation->consultation_status == 'completed' && !$consultation->prescription_issued)
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#prescriptionModal">
                                            <i class="fas fa-prescription mr-1"></i> Add Prescription
                                        </button>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('veterinary.consultations.index') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Consultations
                                    </a>
                                    <button class="btn btn-secondary" onclick="window.print()">
                                        <i class="fas fa-print mr-1"></i> Print
                                    </button>
                                    @if($consultation->consultation_status == 'pending')
                                        <a href="{{ route('veterinary.consultations.edit', $consultation) }}" class="btn btn-warning">
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

<!-- Prescription Modal -->
@if($consultation->consultation_status == 'completed' && !$consultation->prescription_issued)
<div class="modal fade" id="prescriptionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Prescription</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('veterinary.consultations.prescription', $consultation) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="prescription_notes">Prescription Details *</label>
                        <textarea class="form-control" id="prescription_notes" name="prescription_notes" rows="4" required placeholder="Enter prescription details including medications, dosage, duration..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="medications">Medications (Optional)</label>
                        <textarea class="form-control" id="medications" name="medications" rows="2" placeholder="List specific medications if any"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="follow_up_date">Follow-up Date (Optional)</label>
                        <input type="date" class="form-control" id="follow_up_date" name="follow_up_date" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
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
