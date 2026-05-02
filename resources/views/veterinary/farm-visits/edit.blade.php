@extends('layouts.app')

@section('title', 'Edit Farm Visit #' . $farmVisit->visit_number)

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Farm Visit: {{ $farmVisit->visit_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.farm-visits.index') }}">Farm Visits</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.farm-visits.show', $farmVisit) }}">{{ $farmVisit->visit_number }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-1"></i>
                                Update Farm Visit Details
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-{{ $farmVisit->priority_badge }}">
                                    {{ ucfirst($farmVisit->priority) }}
                                </span>
                                <span class="badge badge-{{ $farmVisit->status_badge }} ml-1">
                                    {{ ucfirst($farmVisit->visit_status) }}
                                </span>
                                @if($farmVisit->is_emergency)
                                    <span class="badge badge-danger ml-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Emergency
                                    </span>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('veterinary.farm-visits.update', $farmVisit) }}" method="POST" id="farmVisitForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Visit Info -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="callout callout-info">
                                            <h5><i class="fas fa-info-circle mr-2"></i> Visit Information</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Visit #:</strong> {{ $farmVisit->visit_number }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Farmer:</strong> {{ $farmVisit->farmer->name }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Farm:</strong> {{ $farmVisit->farm_name }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Scheduled:</strong> {{ $farmVisit->scheduled_date->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-3">
                                                    <strong>Location:</strong> {{ $farmVisit->location }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Poultry Type:</strong> {{ ucfirst($farmVisit->poultry_type) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Flock Size:</strong> {{ $farmVisit->total_flock_size ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Visit Type:</strong> {{ ucfirst(str_replace('_', ' ', $farmVisit->visit_type)) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visit Purpose -->
                                <div class="form-group">
                                    <label for="visit_purpose">Visit Purpose *</label>
                                    <textarea class="form-control @error('visit_purpose') is-invalid @enderror"
                                              id="visit_purpose" name="visit_purpose" rows="3"
                                              placeholder="Describe the purpose of the visit"
                                              required>{{ old('visit_purpose', $farmVisit->visit_purpose) }}</textarea>
                                    @error('visit_purpose')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Visit Status -->
                                <div class="form-group">
                                    <label for="visit_status">Visit Status *</label>
                                    <select class="form-control @error('visit_status') is-invalid @enderror"
                                            id="visit_status" name="visit_status" required>
                                        <option value="scheduled" {{ old('visit_status', $farmVisit->visit_status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="in_progress" {{ old('visit_status', $farmVisit->visit_status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('visit_status', $farmVisit->visit_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('visit_status', $farmVisit->visit_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="rescheduled" {{ old('visit_status', $farmVisit->visit_status) == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                    </select>
                                    @error('visit_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Observations -->
                                <div class="form-group">
                                    <label for="observations">Observations *</label>
                                    <textarea class="form-control @error('observations') is-invalid @enderror"
                                              id="observations" name="observations" rows="4"
                                              placeholder="Record your observations during the farm visit"
                                              required>{{ old('observations', $farmVisit->observations) }}</textarea>
                                    @error('observations')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Issues Found -->
                                <div class="form-group">
                                    <label for="issues_found">Issues Found</label>
                                    <textarea class="form-control @error('issues_found') is-invalid @enderror"
                                              id="issues_found" name="issues_found" rows="3"
                                              placeholder="List any issues or problems identified">{{ old('issues_found', $farmVisit->issues_found) }}</textarea>
                                    @error('issues_found')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Health Metrics -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="mortality_rate">Mortality Rate (%)</label>
                                            <input type="number" class="form-control @error('mortality_rate') is-invalid @enderror"
                                                   id="mortality_rate" name="mortality_rate"
                                                   value="{{ old('mortality_rate', $farmVisit->mortality_rate) }}"
                                                   min="0" max="100" step="0.1" placeholder="e.g., 2.5">
                                            @error('mortality_rate')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="feed_intake">Feed Intake (kg/day)</label>
                                            <input type="number" class="form-control @error('feed_intake') is-invalid @enderror"
                                                   id="feed_intake" name="feed_intake"
                                                   value="{{ old('feed_intake', $farmVisit->feed_intake) }}"
                                                   min="0" step="0.01" placeholder="e.g., 100.5">
                                            @error('feed_intake')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="water_intake">Water Intake (L/day)</label>
                                            <input type="number" class="form-control @error('water_intake') is-invalid @enderror"
                                                   id="water_intake" name="water_intake"
                                                   value="{{ old('water_intake', $farmVisit->water_intake) }}"
                                                   min="0" step="0.01" placeholder="e.g., 200.0">
                                            @error('water_intake')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="egg_production">Egg Production (%)</label>
                                            <input type="number" class="form-control @error('egg_production') is-invalid @enderror"
                                                   id="egg_production" name="egg_production"
                                                   value="{{ old('egg_production', $farmVisit->egg_production) }}"
                                                   min="0" max="100" step="0.1" placeholder="e.g., 85.0">
                                            @error('egg_production')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Diagnosis & Recommendations -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="diagnosis">Diagnosis</label>
                                            <textarea class="form-control @error('diagnosis') is-invalid @enderror"
                                                      id="diagnosis" name="diagnosis" rows="4"
                                                      placeholder="Enter diagnosis based on observations">{{ old('diagnosis', $farmVisit->diagnosis) }}</textarea>
                                            @error('diagnosis')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="recommendations">Recommendations</label>
                                            <textarea class="form-control @error('recommendations') is-invalid @enderror"
                                                      id="recommendations" name="recommendations" rows="4"
                                                      placeholder="Provide recommendations for the farmer">{{ old('recommendations', $farmVisit->recommendations) }}</textarea>
                                            @error('recommendations')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Treatment & Vaccinations -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="treatment_administered">Treatment Administered</label>
                                            <textarea class="form-control @error('treatment_administered') is-invalid @enderror"
                                                      id="treatment_administered" name="treatment_administered" rows="3"
                                                      placeholder="List treatments given during visit">{{ old('treatment_administered', $farmVisit->treatment_administered) }}</textarea>
                                            @error('treatment_administered')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vaccinations_administered">Vaccinations Administered</label>
                                            <textarea class="form-control @error('vaccinations_administered') is-invalid @enderror"
                                                      id="vaccinations_administered" name="vaccinations_administered" rows="3"
                                                      placeholder="List vaccinations given">{{ old('vaccinations_administered', $farmVisit->vaccinations_administered) }}</textarea>
                                            @error('vaccinations_administered')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Biosecurity & Management -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="biosecurity_assessment">Biosecurity Assessment</label>
                                            <textarea class="form-control @error('biosecurity_assessment') is-invalid @enderror"
                                                      id="biosecurity_assessment" name="biosecurity_assessment" rows="3"
                                                      placeholder="Biosecurity measures assessment">{{ old('biosecurity_assessment', $farmVisit->biosecurity_assessment) }}</textarea>
                                            @error('biosecurity_assessment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="management_advice">Management Advice</label>
                                            <textarea class="form-control @error('management_advice') is-invalid @enderror"
                                                      id="management_advice" name="management_advice" rows="3"
                                                      placeholder="Management recommendations">{{ old('management_advice', $farmVisit->management_advice) }}</textarea>
                                            @error('management_advice')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Visit Timing -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="actual_start_time">Actual Start Time</label>
                                            <input type="datetime-local" class="form-control @error('actual_start_time') is-invalid @enderror"
                                                   id="actual_start_time" name="actual_start_time"
                                                   value="{{ old('actual_start_time', $farmVisit->actual_start_time ? $farmVisit->actual_start_time->format('Y-m-d\TH:i') : '') }}">
                                            @error('actual_start_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="actual_end_time">Actual End Time</label>
                                            <input type="datetime-local" class="form-control @error('actual_end_time') is-invalid @enderror"
                                                   id="actual_end_time" name="actual_end_time"
                                                   value="{{ old('actual_end_time', $farmVisit->actual_end_time ? $farmVisit->actual_end_time->format('Y-m-d\TH:i') : '') }}">
                                            @error('actual_end_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Visit Summary -->
                                <div class="form-group">
                                    <label for="visit_summary">Visit Summary</label>
                                    <textarea class="form-control @error('visit_summary') is-invalid @enderror"
                                              id="visit_summary" name="visit_summary" rows="3"
                                              placeholder="Brief summary of the visit">{{ old('visit_summary', $farmVisit->visit_summary) }}</textarea>
                                    @error('visit_summary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Veterinarian Notes -->
                                <div class="form-group">
                                    <label for="veterinarian_notes">Veterinarian Notes</label>
                                    <textarea class="form-control @error('veterinarian_notes') is-invalid @enderror"
                                              id="veterinarian_notes" name="veterinarian_notes" rows="3"
                                              placeholder="Additional notes or observations">{{ old('veterinarian_notes', $farmVisit->veterinarian_notes) }}</textarea>
                                    @error('veterinarian_notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Follow-up -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="follow_up_date">Follow-up Date</label>
                                            <input type="date" class="form-control @error('follow_up_date') is-invalid @enderror"
                                                   id="follow_up_date" name="follow_up_date"
                                                   value="{{ old('follow_up_date', $farmVisit->follow_up_date ? $farmVisit->follow_up_date->format('Y-m-d') : '') }}"
                                                   min="{{ date('Y-m-d') }}">
                                            @error('follow_up_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="follow_up_plan">Follow-up Plan</label>
                                            <textarea class="form-control @error('follow_up_plan') is-invalid @enderror"
                                                      id="follow_up_plan" name="follow_up_plan" rows="2"
                                                      placeholder="Follow-up instructions">{{ old('follow_up_plan', $farmVisit->follow_up_plan) }}</textarea>
                                            @error('follow_up_plan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Information -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_amount">Total Amount (KES)</label>
                                            <input type="number" class="form-control @error('total_amount') is-invalid @enderror"
                                                   id="total_amount" name="total_amount"
                                                   value="{{ old('total_amount', $farmVisit->total_amount) }}"
                                                   min="0" step="0.01" placeholder="e.g., 3500.00">
                                            @error('total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount_paid">Amount Paid (KES)</label>
                                            <input type="number" class="form-control @error('amount_paid') is-invalid @enderror"
                                                   id="amount_paid" name="amount_paid"
                                                   value="{{ old('amount_paid', $farmVisit->amount_paid) }}"
                                                   min="0" step="0.01" placeholder="e.g., 2000.00">
                                            @error('amount_paid')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_status">Payment Status *</label>
                                            <select class="form-control @error('payment_status') is-invalid @enderror"
                                                    id="payment_status" name="payment_status" required>
                                                <option value="pending" {{ old('payment_status', $farmVisit->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ old('payment_status', $farmVisit->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="waived" {{ old('payment_status', $farmVisit->payment_status) == 'waived' ? 'selected' : '' }}>Waived</option>
                                                <option value="partial" {{ old('payment_status', $farmVisit->payment_status) == 'partial' ? 'selected' : '' }}>Partial Payment</option>
                                            </select>
                                            @error('payment_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Feed Conversion Ratio -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="feed_conversion_ratio">Feed Conversion Ratio (FCR)</label>
                                            <input type="number" class="form-control @error('feed_conversion_ratio') is-invalid @enderror"
                                                   id="feed_conversion_ratio" name="feed_conversion_ratio"
                                                   value="{{ old('feed_conversion_ratio', $farmVisit->feed_conversion_ratio) }}"
                                                   min="0" step="0.01" placeholder="e.g., 1.8">
                                            @error('feed_conversion_ratio')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="age_weeks">Age (Weeks)</label>
                                            <input type="number" class="form-control @error('age_weeks') is-invalid @enderror"
                                                   id="age_weeks" name="age_weeks"
                                                   value="{{ old('age_weeks', $farmVisit->age_weeks) }}"
                                                   min="0" placeholder="e.g., 8">
                                            @error('age_weeks')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_flock_size">Total Flock Size</label>
                                            <input type="number" class="form-control @error('total_flock_size') is-invalid @enderror"
                                                   id="total_flock_size" name="total_flock_size"
                                                   value="{{ old('total_flock_size', $farmVisit->total_flock_size) }}"
                                                   min="1" placeholder="e.g., 1000">
                                            @error('total_flock_size')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Emergency Details (if applicable) -->
                                @if($farmVisit->is_emergency)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="callout callout-danger">
                                            <h5><i class="fas fa-exclamation-triangle mr-2"></i> Emergency Details</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Emergency Details:</strong><br>
                                                    {{ $farmVisit->emergency_details ?? 'No details provided' }}
                                                </div>
                                                @if($farmVisit->emergency_contact || $farmVisit->emergency_phone)
                                                <div class="col-md-6">
                                                    @if($farmVisit->emergency_contact)
                                                    <strong>Emergency Contact:</strong> {{ $farmVisit->emergency_contact }}<br>
                                                    @endif
                                                    @if($farmVisit->emergency_phone)
                                                    <strong>Emergency Phone:</strong> {{ $farmVisit->emergency_phone }}
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Update Visit
                                </button>
                                <a href="{{ route('veterinary.farm-visits.show', $farmVisit) }}" class="btn btn-default">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>

                                @if($farmVisit->visit_status == 'scheduled')
                                    <button type="button" class="btn btn-success float-right" id="startVisitBtn">
                                        <i class="fas fa-play mr-1"></i> Start Visit Now
                                    </button>
                                @endif

                                @if($farmVisit->visit_status == 'in_progress')
                                    <button type="button" class="btn btn-success float-right" id="completeVisitBtn">
                                        <i class="fas fa-check mr-1"></i> Complete Visit Now
                                    </button>
                                @endif

                                @if($farmVisit->visit_status != 'cancelled' && $farmVisit->visit_status != 'completed')
                                    <button type="button" class="btn btn-danger float-right mr-2" id="cancelVisitBtn">
                                        <i class="fas fa-times-circle mr-1"></i> Cancel Visit
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .callout {
        border-left-width: 5px;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
        padding: 1rem;
    }

    .callout-info {
        border-left-color: #17a2b8;
        background-color: #e8f4f8;
    }

    .callout-danger {
        border-left-color: #dc3545;
        background-color: #f8e8e8;
    }

    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.8em;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Start Visit Now button
    $('#startVisitBtn').click(function(e) {
        e.preventDefault();

        if (confirm('Are you sure you want to start this visit now? This will update the status to "In Progress" and set the start time to current time.')) {
            // Set visit status to in_progress
            $('#visit_status').val('in_progress');

            // Set actual start time to current time
            const now = new Date();
            const formattedDateTime = now.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:MM
            $('#actual_start_time').val(formattedDateTime);

            // Submit the form
            $('#farmVisitForm').submit();
        }
    });

    // Complete Visit Now button
    $('#completeVisitBtn').click(function(e) {
        e.preventDefault();

        if (confirm('Are you sure you want to complete this visit now? This will update the status to "Completed" and set the end time to current time.')) {
            // Set visit status to completed
            $('#visit_status').val('completed');

            // Set actual end time to current time
            const now = new Date();
            const formattedDateTime = now.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:MM
            $('#actual_end_time').val(formattedDateTime);

            // Submit the form
            $('#farmVisitForm').submit();
        }
    });

    // Cancel Visit button
    $('#cancelVisitBtn').click(function(e) {
        e.preventDefault();

        const cancelReason = prompt('Please provide a reason for cancelling this visit:');

        if (cancelReason !== null && cancelReason.trim() !== '') {
            // Set visit status to cancelled
            $('#visit_status').val('cancelled');

            // Add cancellation reason to veterinarian notes
            const currentNotes = $('#veterinarian_notes').val();
            const cancellationNote = `\n\nCANCELLATION REASON: ${cancelReason}`;
            $('#veterinarian_notes').val(currentNotes + cancellationNote);

            // Submit the form
            $('#farmVisitForm').submit();
        } else if (cancelReason !== null) {
            alert('Please provide a reason for cancellation.');
        }
    });

    // Auto-calculate balance when total amount or amount paid changes
    $('#total_amount, #amount_paid').on('input', function() {
        const total = parseFloat($('#total_amount').val()) || 0;
        const paid = parseFloat($('#amount_paid').val()) || 0;

        if (paid > total) {
            alert('Amount paid cannot exceed total amount.');
            $('#amount_paid').val(total);
            return;
        }

        // Update payment status based on amounts
        if (total === 0) {
            $('#payment_status').val('waived');
        } else if (paid === 0) {
            $('#payment_status').val('pending');
        } else if (paid === total) {
            $('#payment_status').val('paid');
        } else {
            $('#payment_status').val('partial');
        }
    });

    // Auto-calculate feed conversion ratio if feed intake and flock size provided
    $('#feed_intake, #total_flock_size').on('input', function() {
        const feedIntake = parseFloat($('#feed_intake').val());
        const flockSize = parseFloat($('#total_flock_size').val());

        if (feedIntake && flockSize && flockSize > 0) {
            const fcr = feedIntake / flockSize;
            $('#feed_conversion_ratio').val(fcr.toFixed(2));
        }
    });

    // Set min date for follow-up date to today
    const today = new Date().toISOString().split('T')[0];
    $('#follow_up_date').attr('min', today);

    // Set max date for actual times to today
    const now = new Date().toISOString().slice(0, 16);
    $('#actual_start_time').attr('max', now);
    $('#actual_end_time').attr('max', now);

    // Validate that end time is after start time
    $('#actual_end_time').on('change', function() {
        const startTime = $('#actual_start_time').val();
        const endTime = $(this).val();

        if (startTime && endTime && endTime < startTime) {
            alert('End time must be after start time.');
            $(this).val('');
        }
    });

    // Auto-fill current time for start/end when status changes
    $('#visit_status').change(function() {
        const status = $(this).val();
        const now = new Date().toISOString().slice(0, 16);

        if (status === 'in_progress' && !$('#actual_start_time').val()) {
            if (confirm('Do you want to set the start time to current time?')) {
                $('#actual_start_time').val(now);
            }
        }

        if (status === 'completed' && !$('#actual_end_time').val()) {
            if (confirm('Do you want to set the end time to current time?')) {
                $('#actual_end_time').val(now);
            }
        }
    });

    // Character counters for textareas
    $('textarea').each(function() {
        const textarea = $(this);
        const maxLength = textarea.attr('maxlength') || 1000;
        const counterId = textarea.attr('id') + '_counter';

        textarea.after('<small class="form-text text-muted float-right"><span id="' + counterId + '">0</span>/' + maxLength + ' characters</small>');

        textarea.on('input', function() {
            $('#' + counterId).text(textarea.val().length);
        }).trigger('input');
    });

    // Quick fill for mortality rate based on typical values
    $('#mortality_rate').on('dblclick', function() {
        const typicalValues = [0.5, 1.0, 1.5, 2.0, 2.5, 5.0, 10.0];
        const currentValue = parseFloat($(this).val()) || 0;
        const nextValue = typicalValues.find(val => val > currentValue) || typicalValues[0];
        $(this).val(nextValue);
    });
});
</script>
@endpush
