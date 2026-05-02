@extends('layouts.app')

@section('title', 'Edit Consultation #' . $consultation->consultation_number)

@section('content')
<!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Consultation: {{ $consultation->consultation_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.consultations.index') }}">Consultations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.consultations.show', $consultation) }}">{{ $consultation->consultation_number }}</a></li>
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
                                Update Consultation Details
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-{{ $consultation->priority_badge }}">
                                    {{ ucfirst($consultation->priority) }}
                                </span>
                                <span class="badge badge-{{ $consultation->status_badge }} ml-1">
                                    {{ ucfirst($consultation->consultation_status) }}
                                </span>
                            </div>
                        </div>
                        <form action="{{ route('veterinary.consultations.update', $consultation) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Consultation Info -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="callout callout-info">
                                            <h5><i class="fas fa-info-circle mr-2"></i> Consultation Information</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Consultation #:</strong> {{ $consultation->consultation_number }}
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Farmer:</strong> {{ $consultation->farmer->name }}
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Created:</strong> {{ $consultation->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Diagnosis Section -->
                                <div class="form-group">
                                    <label for="diagnosis">Diagnosis *</label>
                                    <textarea class="form-control @error('diagnosis') is-invalid @enderror"
                                              id="diagnosis" name="diagnosis" rows="4"
                                              placeholder="Enter primary diagnosis based on symptoms and observations"
                                              required>{{ old('diagnosis', $consultation->diagnosis) }}</textarea>
                                    @error('diagnosis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Common poultry diagnoses: Newcastle Disease, Gumboro (IBD), Fowl Cholera,
                                        Coccidiosis, Mycoplasmosis, Infectious Coryza, etc.
                                    </small>
                                </div>

                                <!-- Differential Diagnosis -->
                                <div class="form-group">
                                    <label for="differential_diagnosis">Differential Diagnosis (Optional)</label>
                                    <textarea class="form-control @error('differential_diagnosis') is-invalid @enderror"
                                              id="differential_diagnosis" name="differential_diagnosis" rows="3"
                                              placeholder="List other possible diagnoses to rule out">{{ old('differential_diagnosis', $consultation->differential_diagnosis) }}</textarea>
                                    @error('differential_diagnosis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Treatment Plan -->
                                <div class="form-group">
                                    <label for="treatment_plan">Treatment Plan *</label>
                                    <textarea class="form-control @error('treatment_plan') is-invalid @enderror"
                                              id="treatment_plan" name="treatment_plan" rows="4"
                                              placeholder="Describe the treatment plan including medications, duration, and administration method"
                                              required>{{ old('treatment_plan', $consultation->treatment_plan) }}</textarea>
                                    @error('treatment_plan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Medications & Vaccinations -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="medications">Medications (Optional)</label>
                                            <textarea class="form-control @error('medications') is-invalid @enderror"
                                                      id="medications" name="medications" rows="3"
                                                      placeholder="List specific medications, dosage, and administration schedule">{{ old('medications', $consultation->medications) }}</textarea>
                                            @error('medications')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vaccinations">Vaccinations (Optional)</label>
                                            <textarea class="form-control @error('vaccinations') is-invalid @enderror"
                                                      id="vaccinations" name="vaccinations" rows="3"
                                                      placeholder="List recommended vaccinations and schedule">{{ old('vaccinations', $consultation->vaccinations) }}</textarea>
                                            @error('vaccinations')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Recommendations -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="biosecurity_measures">Biosecurity Measures (Optional)</label>
                                            <textarea class="form-control @error('biosecurity_measures') is-invalid @enderror"
                                                      id="biosecurity_measures" name="biosecurity_measures" rows="3"
                                                      placeholder="Recommended biosecurity measures">{{ old('biosecurity_measures', $consultation->biosecurity_measures) }}</textarea>
                                            @error('biosecurity_measures')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="feeding_recommendations">Feeding Recommendations (Optional)</label>
                                            <textarea class="form-control @error('feeding_recommendations') is-invalid @enderror"
                                                      id="feeding_recommendations" name="feeding_recommendations" rows="3"
                                                      placeholder="Feed type, schedule, supplements">{{ old('feeding_recommendations', $consultation->feeding_recommendations) }}</textarea>
                                            @error('feeding_recommendations')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="management_recommendations">Management Recommendations (Optional)</label>
                                            <textarea class="form-control @error('management_recommendations') is-invalid @enderror"
                                                      id="management_recommendations" name="management_recommendations" rows="3"
                                                      placeholder="Housing, ventilation, stocking density">{{ old('management_recommendations', $consultation->management_recommendations) }}</textarea>
                                            @error('management_recommendations')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Follow-up Instructions -->
                                <div class="form-group">
                                    <label for="follow_up_instructions">Follow-up Instructions (Optional)</label>
                                    <textarea class="form-control @error('follow_up_instructions') is-invalid @enderror"
                                              id="follow_up_instructions" name="follow_up_instructions" rows="3"
                                              placeholder="Instructions for follow-up, monitoring, and when to contact again">{{ old('follow_up_instructions', $consultation->follow_up_instructions) }}</textarea>
                                    @error('follow_up_instructions')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Prescription & Status -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="consultation_status">Consultation Status *</label>
                                            <select class="form-control @error('consultation_status') is-invalid @enderror"
                                                    id="consultation_status" name="consultation_status" required>
                                                <option value="pending" {{ old('consultation_status', $consultation->consultation_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="in_progress" {{ old('consultation_status', $consultation->consultation_status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="completed" {{ old('consultation_status', $consultation->consultation_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ old('consultation_status', $consultation->consultation_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('consultation_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="consultation_fee">Consultation Fee (KES)</label>
                                            <input type="number" class="form-control @error('consultation_fee') is-invalid @enderror"
                                                   id="consultation_fee" name="consultation_fee"
                                                   value="{{ old('consultation_fee', $consultation->consultation_fee) }}"
                                                   min="0" step="0.01" placeholder="e.g., 1500.00">
                                            @error('consultation_fee')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Prescription Check -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input @error('prescription_issued') is-invalid @enderror"
                                               id="prescription_issued" name="prescription_issued" value="1"
                                               {{ old('prescription_issued', $consultation->prescription_issued) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="prescription_issued">
                                            Prescription Issued
                                        </label>
                                        @error('prescription_issued')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Prescription Notes -->
                                <div class="form-group" id="prescriptionNotesSection"
                                     style="{{ old('prescription_issued', $consultation->prescription_issued) ? '' : 'display: none;' }}">
                                    <label for="prescription_notes">Prescription Notes</label>
                                    <textarea class="form-control @error('prescription_notes') is-invalid @enderror"
                                              id="prescription_notes" name="prescription_notes" rows="3"
                                              placeholder="Enter prescription details">{{ old('prescription_notes', $consultation->prescription_notes) }}</textarea>
                                    @error('prescription_notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Veterinarian Notes -->
                                <div class="form-group">
                                    <label for="veterinarian_notes">Veterinarian Notes (Optional)</label>
                                    <textarea class="form-control @error('veterinarian_notes') is-invalid @enderror"
                                              id="veterinarian_notes" name="veterinarian_notes" rows="3"
                                              placeholder="Additional notes or observations">{{ old('veterinarian_notes', $consultation->veterinarian_notes) }}</textarea>
                                    @error('veterinarian_notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Update Consultation
                                </button>
                                <a href="{{ route('veterinary.consultations.show', $consultation) }}" class="btn btn-default">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                                @if($consultation->consultation_status == 'pending')
                                    <button type="button" class="btn btn-success float-right" id="completeAndPrescribe">
                                        <i class="fas fa-check-circle mr-1"></i> Complete & Add Prescription
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Disease Reference -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-book-medical mr-1"></i>
                                Poultry Disease Reference
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="callout callout-danger">
                                        <h5>Common Viral Diseases:</h5>
                                        <ul class="mb-0">
                                            <li><strong>Newcastle Disease:</strong> Respiratory, nervous signs, green diarrhea</li>
                                            <li><strong>Gumboro (IBD):</strong> Immunosuppression, trembling, mortality</li>
                                            <li><strong>Avian Influenza:</strong> Swelling, cyanosis, sudden death</li>
                                            <li><strong>Marek's Disease:</strong> Paralysis, tumors, blindness</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="callout callout-warning">
                                        <h5>Common Bacterial Diseases:</h5>
                                        <ul class="mb-0">
                                            <li><strong>Fowl Cholera:</strong> Swollen wattles, joint swelling</li>
                                            <li><strong>Infectious Coryza:</strong> Facial swelling, nasal discharge</li>
                                            <li><strong>Mycoplasmosis:</strong> Respiratory signs, poor growth</li>
                                            <li><strong>Salmonellosis:</strong> Diarrhea, dehydration, mortality</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="callout callout-success">
                                        <h5>Common Treatments:</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Antibiotics:</strong> Enrofloxacin, Oxytetracycline, Amoxicillin
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Anticoccidials:</strong> Amprolium, Toltrazuril, Sulfa drugs
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Vaccines:</strong> Newcastle, Gumboro, Fowl Pox, Marek's
                                            </div>
                                        </div>
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
.callout {
    border-left-width: 5px;
    border-radius: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide prescription notes based on checkbox
    $('#prescription_issued').change(function() {
        if ($(this).is(':checked')) {
            $('#prescriptionNotesSection').slideDown();
            $('#prescription_notes').prop('required', true);
        } else {
            $('#prescriptionNotesSection').slideUp();
            $('#prescription_notes').prop('required', false);
        }
    });

    // Complete and prescribe button
    $('#completeAndPrescribe').click(function() {
        $('#consultation_status').val('completed');
        $('#prescription_issued').prop('checked', true);
        $('#prescriptionNotesSection').show();
        $('#prescription_notes').prop('required', true);
        $('#prescription_notes').focus();

        // Optional: Auto-fill prescription notes
        if (!$('#prescription_notes').val()) {
            var treatment = $('#treatment_plan').val();
            var meds = $('#medications').val();
            var notes = 'Treatment prescribed: ' + treatment;
            if (meds) {
                notes += '\n\nMedications: ' + meds;
            }
            $('#prescription_notes').val(notes);
        }
    });

    // Quick diagnosis suggestions
    var commonDiagnoses = {
        'respiratory': 'Newcastle Disease, Infectious Bronchitis, Mycoplasmosis',
        'diarrhea': 'Coccidiosis, Salmonellosis, Colibacillosis',
        'nervous': 'Newcastle Disease, Marek\'s Disease, Avian Encephalomyelitis',
        'swollen': 'Fowl Cholera, Infectious Coryza, Swollen Head Syndrome'
    };

    // Monitor diagnosis field for keywords
    $('#diagnosis').on('input', function() {
        var text = $(this).val().toLowerCase();
        $.each(commonDiagnoses, function(key, value) {
            if (text.includes(key)) {
                // You could show suggestions here
                console.log('Consider: ' + value);
            }
        });
    });

    // Auto-save draft (optional)
    var autoSaveTimer;
    $('textarea, input').on('input', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Implement auto-save functionality here
            console.log('Auto-save triggered');
        }, 3000);
    });
});
</script>
@endpush
