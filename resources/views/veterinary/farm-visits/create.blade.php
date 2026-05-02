@extends('layouts.app')

@section('title', 'Schedule Farm Visit')

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Schedule New Farm Visit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.farm-visits.index') }}">Farm Visits</a></li>
                        <li class="breadcrumb-item active">Schedule Visit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Farm Visit Details</h3>
                        </div>
                        <form action="{{ route('veterinary.farm-visits.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- Farmer Selection -->
                                <div class="form-group">
                                    <label for="farmer_id">Select Farmer *</label>
                                    <select class="form-control select2 @error('farmer_id') is-invalid @enderror"
                                            id="farmer_id" name="farmer_id" required>
                                        <option value="">-- Select Farmer --</option>
                                        @foreach($farmers as $farmer)
                                            <option value="{{ $farmer->id }}" {{ old('farmer_id') == $farmer->id ? 'selected' : '' }}>
                                                {{ $farmer->name }} ({{ $farmer->phone }}) - {{ $farmer->address ?? 'No address' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('farmer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Farm Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="farm_name">Farm Name *</label>
                                            <input type="text" class="form-control @error('farm_name') is-invalid @enderror"
                                                   id="farm_name" name="farm_name" value="{{ old('farm_name') }}"
                                                   required placeholder="e.g., Green Valley Poultry Farm">
                                            @error('farm_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Location *</label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                                   id="location" name="location" value="{{ old('location') }}"
                                                   required placeholder="e.g., Nairobi, Kiambu Road">
                                            @error('location')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Details -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="county">County</label>
                                            <input type="text" class="form-control @error('county') is-invalid @enderror"
                                                   id="county" name="county" value="{{ old('county') }}"
                                                   placeholder="e.g., Nairobi">
                                            @error('county')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sub_county">Sub-County</label>
                                            <input type="text" class="form-control @error('sub_county') is-invalid @enderror"
                                                   id="sub_county" name="sub_county" value="{{ old('sub_county') }}"
                                                   placeholder="e.g., Westlands">
                                            @error('sub_county')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ward">Ward</label>
                                            <input type="text" class="form-control @error('ward') is-invalid @enderror"
                                                   id="ward" name="ward" value="{{ old('ward') }}"
                                                   placeholder="e.g., Kangemi">
                                            @error('ward')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="farm_address">Farm Address (Optional)</label>
                                    <textarea class="form-control @error('farm_address') is-invalid @enderror"
                                              id="farm_address" name="farm_address" rows="2"
                                              placeholder="Detailed farm address">{{ old('farm_address') }}</textarea>
                                    @error('farm_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Visit Details -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="visit_type">Visit Type *</label>
                                            <select class="form-control @error('visit_type') is-invalid @enderror"
                                                    id="visit_type" name="visit_type" required>
                                                <option value="">-- Select Visit Type --</option>
                                                <option value="routine" {{ old('visit_type') == 'routine' ? 'selected' : '' }}>Routine Checkup</option>
                                                <option value="emergency" {{ old('visit_type') == 'emergency' ? 'selected' : '' }}>Emergency Visit</option>
                                                <option value="follow_up" {{ old('visit_type') == 'follow_up' ? 'selected' : '' }}>Follow-up Visit</option>
                                                <option value="consultation" {{ old('visit_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                                <option value="vaccination" {{ old('visit_type') == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                                                <option value="inspection" {{ old('visit_type') == 'inspection' ? 'selected' : '' }}>Farm Inspection</option>
                                            </select>
                                            @error('visit_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="priority">Priority *</label>
                                            <select class="form-control @error('priority') is-invalid @enderror"
                                                    id="priority" name="priority" required>
                                                <option value="">-- Select Priority --</option>
                                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                                <option value="emergency" {{ old('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                            </select>
                                            @error('priority')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Poultry Details -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="poultry_type">Poultry Type *</label>
                                            <select class="form-control @error('poultry_type') is-invalid @enderror"
                                                    id="poultry_type" name="poultry_type" required>
                                                <option value="">-- Select Poultry Type --</option>
                                                <option value="broilers" {{ old('poultry_type') == 'broilers' ? 'selected' : '' }}>Broilers</option>
                                                <option value="layers" {{ old('poultry_type') == 'layers' ? 'selected' : '' }}>Layers</option>
                                                <option value="kienyeji" {{ old('poultry_type') == 'kienyeji' ? 'selected' : '' }}>Kienyeji</option>
                                                <option value="breeding" {{ old('poultry_type') == 'breeding' ? 'selected' : '' }}>Breeding Stock</option>
                                                <option value="mixed" {{ old('poultry_type') == 'mixed' ? 'selected' : '' }}>Mixed Flock</option>
                                                <option value="other" {{ old('poultry_type') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('poultry_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="total_flock_size">Total Flock Size</label>
                                            <input type="number" class="form-control @error('total_flock_size') is-invalid @enderror"
                                                   id="total_flock_size" name="total_flock_size" value="{{ old('total_flock_size') }}"
                                                   min="1" placeholder="e.g., 1000">
                                            @error('total_flock_size')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="age_weeks">Age (Weeks)</label>
                                            <input type="number" class="form-control @error('age_weeks') is-invalid @enderror"
                                                   id="age_weeks" name="age_weeks" value="{{ old('age_weeks') }}"
                                                   min="0" placeholder="e.g., 8">
                                            @error('age_weeks')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Visit Purpose -->
                                <div class="form-group">
                                    <label for="visit_purpose">Visit Purpose *</label>
                                    <textarea class="form-control @error('visit_purpose') is-invalid @enderror"
                                              id="visit_purpose" name="visit_purpose" rows="3"
                                              placeholder="Describe the purpose of the visit, issues to address, etc."
                                              required>{{ old('visit_purpose') }}</textarea>
                                    @error('visit_purpose')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="specific_issues">Specific Issues to Address (Optional)</label>
                                    <textarea class="form-control @error('specific_issues') is-invalid @enderror"
                                              id="specific_issues" name="specific_issues" rows="2"
                                              placeholder="List any specific issues reported by the farmer">{{ old('specific_issues') }}</textarea>
                                    @error('specific_issues')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Scheduling -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="scheduled_date">Scheduled Date & Time *</label>
                                            <input type="datetime-local" class="form-control @error('scheduled_date') is-invalid @enderror"
                                                   id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}"
                                                   required min="{{ date('Y-m-d\TH:i') }}">
                                            @error('scheduled_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="distance_km">Distance (KM)</label>
                                            <input type="number" class="form-control @error('distance_km') is-invalid @enderror"
                                                   id="distance_km" name="distance_km" value="{{ old('distance_km') }}"
                                                   min="0" step="0.1" placeholder="e.g., 25.5">
                                            @error('distance_km')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="transport_cost">Transport Cost (KES)</label>
                                            <input type="number" class="form-control @error('transport_cost') is-invalid @enderror"
                                                   id="transport_cost" name="transport_cost" value="{{ old('transport_cost') }}"
                                                   min="0" step="0.01" placeholder="e.g., 1500.00">
                                            @error('transport_cost')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Emergency Details -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input @error('is_emergency') is-invalid @enderror"
                                               id="is_emergency" name="is_emergency" value="1"
                                               {{ old('is_emergency') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_emergency">
                                            This is an Emergency Visit
                                        </label>
                                        @error('is_emergency')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row" id="emergencyDetails" style="{{ old('is_emergency') ? '' : 'display: none;' }}">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergency_details">Emergency Details</label>
                                            <textarea class="form-control @error('emergency_details') is-invalid @enderror"
                                                      id="emergency_details" name="emergency_details" rows="2"
                                                      placeholder="Describe the emergency situation">{{ old('emergency_details') }}</textarea>
                                            @error('emergency_details')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="emergency_contact">Emergency Contact</label>
                                            <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror"
                                                   id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}"
                                                   placeholder="Contact person">
                                            @error('emergency_contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="emergency_phone">Emergency Phone</label>
                                            <input type="tel" class="form-control @error('emergency_phone') is-invalid @enderror"
                                                   id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone') }}"
                                                   placeholder="Phone number">
                                            @error('emergency_phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Consultation Fee -->
                                <div class="form-group">
                                    <label for="consultation_fee">Consultation Fee (KES)</label>
                                    <input type="number" class="form-control @error('consultation_fee') is-invalid @enderror"
                                           id="consultation_fee" name="consultation_fee" value="{{ old('consultation_fee') }}"
                                           min="0" step="0.01" placeholder="e.g., 2000.00">
                                    @error('consultation_fee')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus mr-1"></i> Schedule Visit
                                </button>
                                <a href="{{ route('veterinary.farm-visits.index') }}" class="btn btn-default">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Quick Tips Card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Farm Visit Tips
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info">
                                <h5>Before Visiting:</h5>
                                <p class="mb-1">• Confirm appointment with farmer</p>
                                <p class="mb-1">• Prepare necessary equipment</p>
                                <p class="mb-1">• Review farm history if available</p>
                                <p class="mb-1">• Plan route and estimate travel time</p>
                            </div>

                            <div class="callout callout-warning">
                                <h5>Emergency Visits:</h5>
                                <p class="mb-1">• High mortality rates (>5% daily)</p>
                                <p class="mb-1">• Disease outbreaks</p>
                                <p class="mb-1">• Mass deaths</p>
                                <p class="mb-1">• Poisoning cases</p>
                            </div>

                            <div class="callout callout-success">
                                <h5>Required Equipment:</h5>
                                <p class="mb-1">✓ Protective clothing & boots</p>
                                <p class="mb-1">✓ Thermometer</p>
                                <p class="mb-1">✓ Stethoscope</p>
                                <p class="mb-1">✓ Sample collection kits</p>
                                <p class="mb-1">✓ Notebook & camera</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Farmers -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-1"></i>
                                Recent Farms Visited
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav nav-pills flex-column">
                                @foreach($farmers->take(5) as $farmer)
                                    <li class="nav-item">
                                        <a href="#" class="nav-link select-farmer" data-id="{{ $farmer->id }}" data-farm="{{ $farmer->name }} Farm">
                                            <i class="fas fa-tractor mr-2"></i>
                                            {{ $farmer->name }}'s Farm
                                            <span class="float-right text-muted text-sm">
                                                {{ $farmer->phone }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
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
.callout {
    border-left-width: 5px;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}
.select2-container .select2-selection--single {
    height: 38px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Auto-select farmer from recent list
    $('.select-farmer').click(function(e) {
        e.preventDefault();
        var farmerId = $(this).data('id');
        var farmName = $(this).data('farm');

        $('#farmer_id').val(farmerId).trigger('change');
        $('#farm_name').val(farmName);

        $('html, body').animate({
            scrollTop: $('#farmer_id').offset().top - 100
        }, 500);
    });

    // Show/hide emergency details
    $('#is_emergency').change(function() {
        if ($(this).is(':checked')) {
            $('#emergencyDetails').slideDown();
            $('#priority').val('emergency').trigger('change');
            $('#emergency_details').prop('required', true);
        } else {
            $('#emergencyDetails').slideUp();
            $('#priority').val('normal').trigger('change');
            $('#emergency_details').prop('required', false);
        }
    });

    // Emergency priority auto-selection
    $('#priority').change(function() {
        if ($(this).val() === 'emergency') {
            $('#is_emergency').prop('checked', true);
            $('#emergencyDetails').slideDown();
            $('#emergency_details').prop('required', true);
        } else if ($(this).val() !== 'emergency' && $('#is_emergency').is(':checked')) {
            $('#is_emergency').prop('checked', false);
            $('#emergencyDetails').slideUp();
            $('#emergency_details').prop('required', false);
        }
    });

    // Set default scheduled date to next day at 9 AM
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(9, 0, 0, 0);
    var formatted = tomorrow.toISOString().slice(0, 16);
    $('#scheduled_date').val(formatted);

    // Auto-calculate transport cost based on distance
    $('#distance_km').on('input', function() {
        var distance = $(this).val();
        if (distance && distance > 0) {
            var cost = distance * 60; // Assume 60 KES per km
            $('#transport_cost').val(cost.toFixed(2));
        }
    });

    // Auto-fill consultation fee based on visit type
    $('#visit_type').change(function() {
        var type = $(this).val();
        var fee = 0;

        switch(type) {
            case 'routine':
                fee = 1500;
                break;
            case 'emergency':
                fee = 3000;
                break;
            case 'follow_up':
                fee = 1000;
                break;
            case 'consultation':
                fee = 2000;
                break;
            case 'vaccination':
                fee = 2500;
                break;
            case 'inspection':
                fee = 2000;
                break;
        }

        if (fee > 0) {
            $('#consultation_fee').val(fee);
        }
    });
});
</script>
@endpush
