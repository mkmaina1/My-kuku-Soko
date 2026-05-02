@extends('layouts.app')

@section('title', 'New Poultry Consultation')

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Poultry Consultation</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.consultations.index') }}">Consultations</a></li>
                        <li class="breadcrumb-item active">New Consultation</li>
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
                            <h3 class="card-title">Consultation Details</h3>
                        </div>
                        <form action="{{ route('veterinary.consultations.store') }}" method="POST">
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Consultation Type -->
                                        <div class="form-group">
                                            <label for="consultation_type">Consultation Type *</label>
                                            <select class="form-control @error('consultation_type') is-invalid @enderror"
                                                    id="consultation_type" name="consultation_type" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="in_person" {{ old('consultation_type') == 'in_person' ? 'selected' : '' }}>In-Person Visit</option>
                                                <option value="telemedicine" {{ old('consultation_type') == 'telemedicine' ? 'selected' : '' }}>Telemedicine</option>
                                                <option value="emergency" {{ old('consultation_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                                <option value="follow_up" {{ old('consultation_type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                            </select>
                                            @error('consultation_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Priority -->
                                        <div class="form-group">
                                            <label for="priority">Priority Level *</label>
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

                                <!-- Poultry Information -->
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
                                            <label for="flock_size">Flock Size</label>
                                            <input type="number" class="form-control @error('flock_size') is-invalid @enderror"
                                                   id="flock_size" name="flock_size" value="{{ old('flock_size') }}"
                                                   min="1" placeholder="e.g., 1000">
                                            @error('flock_size')
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
                                                   min="0" placeholder="e.g., 6">
                                            @error('age_weeks')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Symptoms -->
                                <div class="form-group">
                                    <label for="symptoms">Symptoms & Observations *</label>
                                    <textarea class="form-control @error('symptoms') is-invalid @enderror"
                                              id="symptoms" name="symptoms" rows="4"
                                              placeholder="Describe the symptoms, mortality rate, feed/water intake, behavior changes, etc."
                                              required>{{ old('symptoms') }}</textarea>
                                    @error('symptoms')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Common poultry symptoms: Respiratory distress, diarrhea, reduced feed intake,
                                        decreased egg production, nervous signs, swollen joints, etc.
                                    </small>
                                </div>

                                <!-- Appointment Details -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="appointment_date">Appointment Date & Time</label>
                                            <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror"
                                                   id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}">
                                            @error('appointment_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Location (For In-person visits)</label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                                   id="location" name="location" value="{{ old('location') }}"
                                                   placeholder="Farm location/address">
                                            @error('location')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Farm Information -->
                                <div class="form-group">
                                    <label for="farm_name">Farm Name (Optional)</label>
                                    <input type="text" class="form-control @error('farm_name') is-invalid @enderror"
                                           id="farm_name" name="farm_name" value="{{ old('farm_name') }}"
                                           placeholder="e.g., Green Valley Poultry Farm">
                                    @error('farm_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Quick Symptoms Checkboxes -->
                                <div class="form-group">
                                    <label>Common Poultry Issues (Check all that apply)</label>
                                    <div class="row">
                                        @php
                                            $commonSymptoms = [
                                                'respiratory' => 'Respiratory Issues',
                                                'diarrhea' => 'Diarrhea',
                                                'reduced_feed' => 'Reduced Feed Intake',
                                                'mortality' => 'Increased Mortality',
                                                'egg_production' => 'Reduced Egg Production',
                                                'lameness' => 'Lameness',
                                                'swollen_joints' => 'Swollen Joints',
                                                'nervous_signs' => 'Nervous Signs',
                                                'feather_loss' => 'Feather Loss',
                                                'pale_comb' => 'Pale Comb/Wattles'
                                            ];
                                        @endphp
                                        @foreach($commonSymptoms as $key => $symptom)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input symptom-checkbox"
                                                           id="symptom_{{ $key }}" value="{{ $symptom }}">
                                                    <label class="form-check-label" for="symptom_{{ $key }}">
                                                        {{ $symptom }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Create Consultation
                                </button>
                                <a href="{{ route('veterinary.consultations.index') }}" class="btn btn-default">
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
                                Quick Tips
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info">
                                <h5>For Emergency Cases:</h5>
                                <p class="mb-2">• Immediate response required</p>
                                <p class="mb-2">• High mortality rates (>5% daily)</p>
                                <p class="mb-2">• Sudden disease outbreaks</p>
                            </div>

                            <div class="callout callout-warning">
                                <h5>Common Poultry Diseases:</h5>
                                <p class="mb-1"><strong>Newcastle Disease:</strong> Respiratory, nervous signs</p>
                                <p class="mb-1"><strong>Gumboro (IBD):</strong> Immunosuppression, mortality</p>
                                <p class="mb-1"><strong>Fowl Cholera:</strong> Swollen wattles, sudden death</p>
                                <p class="mb-1"><strong>Coccidiosis:</strong> Bloody diarrhea, poor growth</p>
                            </div>

                            <div class="callout callout-success">
                                <h5>Required Information:</h5>
                                <p class="mb-1">✓ Flock size and age</p>
                                <p class="mb-1">✓ Vaccination history</p>
                                <p class="mb-1">✓ Feed and water intake</p>
                                <p class="mb-1">✓ Mortality rate</p>
                                <p class="mb-1">✓ Clinical signs observed</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Farmers -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-1"></i>
                                Recent Farmers
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav nav-pills flex-column">
                                @foreach($farmers->take(5) as $farmer)
                                    <li class="nav-item">
                                        <a href="#" class="nav-link select-farmer" data-id="{{ $farmer->id }}">
                                            <i class="fas fa-user mr-2"></i>
                                            {{ $farmer->name }}
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
        $('#farmer_id').val(farmerId).trigger('change');
        $('html, body').animate({
            scrollTop: $('#farmer_id').offset().top - 100
        }, 500);
    });

    // Add symptoms from checkboxes to textarea
    $('.symptom-checkbox').change(function() {
        var textarea = $('#symptoms');
        var currentText = textarea.val();
        var symptom = $(this).val();

        if ($(this).is(':checked')) {
            if (currentText) {
                textarea.val(currentText + ', ' + symptom);
            } else {
                textarea.val(symptom);
            }
        } else {
            // Remove symptom if unchecked
            var newText = currentText.replace(symptom, '').replace(', ,', ',').replace(/^,\s*|\s*,$/g, '');
            textarea.val(newText.trim());
        }
    });

    // Show/hide location field based on consultation type
    $('#consultation_type').change(function() {
        var type = $(this).val();
        if (type === 'in_person') {
            $('#location').closest('.form-group').show();
            $('#location').prop('required', true);
        } else {
            $('#location').closest('.form-group').show();
            $('#location').prop('required', false);
        }
    });

    // Set default appointment time to next hour
    var now = new Date();
    now.setHours(now.getHours() + 1);
    now.setMinutes(0);
    var formatted = now.toISOString().slice(0, 16);
    $('#appointment_date').val(formatted);

    // Emergency priority auto-selection
    $('#consultation_type').change(function() {
        if ($(this).val() === 'emergency') {
            $('#priority').val('emergency').trigger('change');
        }
    });
});
</script>
@endpush
