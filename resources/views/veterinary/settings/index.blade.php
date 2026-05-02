@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Veterinary Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('veterinary.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Settings Navigation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cog mr-1"></i>
                                Settings Menu
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item">
                                    <a href="#professional-info" class="nav-link">
                                        <i class="fas fa-user-md mr-2 text-info"></i>
                                        Professional Info
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#licenses" class="nav-link">
                                        <i class="fas fa-file-certificate mr-2 text-danger"></i>
                                        License & Certifications
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Status Info -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Account Status
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->is_approved)
                                <div class="alert alert-success">
                                    <h5><i class="fas fa-check-circle"></i> Account Approved</h5>
                                    <p class="mb-0">Your account is fully verified and active.</p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-clock"></i> Pending Approval</h5>
                                    <p class="mb-0">Your account is under review. Please complete your professional information.</p>
                                </div>
                            @endif

                            @if(auth()->user()->hasValidLicense())
                                <div class="alert alert-success mt-3">
                                    <h5><i class="fas fa-file-certificate"></i> License Active</h5>
                                    <p class="mb-0">Your veterinary license is valid until {{ auth()->user()->license_expiry?->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3">
                                    <h5><i class="fas fa-exclamation-triangle"></i> License Required</h5>
                                    <p class="mb-0">Please upload your valid veterinary license.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Professional Information -->
                    <div class="card card-primary" id="professional-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-md mr-1"></i>
                                Professional Information
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">Required for Verification</span>
                            </div>
                        </div>
                        <form action="{{ route('veterinary.settings.update-professional-info') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="professional_title">Professional Title *</label>
                                            <select class="form-control @error('professional_title') is-invalid @enderror"
                                                    id="professional_title" name="professional_title" required>
                                                <option value="">Select Title</option>
                                                <option value="Dr." {{ old('professional_title', auth()->user()->professional_title) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                                <option value="Vet." {{ old('professional_title', auth()->user()->professional_title) == 'Vet.' ? 'selected' : '' }}>Vet.</option>
                                                <option value="Prof." {{ old('professional_title', auth()->user()->professional_title) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                                <option value="Mr." {{ old('professional_title', auth()->user()->professional_title) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                                <option value="Mrs." {{ old('professional_title', auth()->user()->professional_title) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                                <option value="Ms." {{ old('professional_title', auth()->user()->professional_title) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                            </select>
                                            @error('professional_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="specialization">Specialization</label>
                                            <select class="form-control @error('specialization') is-invalid @enderror"
                                                    id="specialization" name="specialization">
                                                <option value="">Select Specialization</option>
                                                <option value="poultry" {{ old('specialization', auth()->user()->specialization) == 'poultry' ? 'selected' : '' }}>Poultry</option>
                                                <option value="livestock" {{ old('specialization', auth()->user()->specialization) == 'livestock' ? 'selected' : '' }}>Livestock</option>
                                                <option value="small_animals" {{ old('specialization', auth()->user()->specialization) == 'small_animals' ? 'selected' : '' }}>Small Animals</option>
                                                <option value="exotic_animals" {{ old('specialization', auth()->user()->specialization) == 'exotic_animals' ? 'selected' : '' }}>Exotic Animals</option>
                                                <option value="mixed_practice" {{ old('specialization', auth()->user()->specialization) == 'mixed_practice' ? 'selected' : '' }}>Mixed Practice</option>
                                            </select>
                                            @error('specialization')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="license_number">Veterinary License Number *</label>
                                            <input type="text" class="form-control @error('license_number') is-invalid @enderror"
                                                   id="license_number" name="license_number"
                                                   value="{{ old('license_number', auth()->user()->license_number) }}"
                                                   required placeholder="e.g., KVB/1234/2023">
                                            @error('license_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="license_expiry">License Expiry Date *</label>
                                            <input type="date" class="form-control @error('license_expiry') is-invalid @enderror"
                                                   id="license_expiry" name="license_expiry"
                                                   value="{{ old('license_expiry', auth()->user()->license_expiry?->format('Y-m-d')) }}"
                                                   required min="{{ date('Y-m-d') }}">
                                            @error('license_expiry')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="years_of_experience">Years of Experience *</label>
                                            <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror"
                                                   id="years_of_experience" name="years_of_experience"
                                                   value="{{ old('years_of_experience', auth()->user()->years_of_experience) }}"
                                                   required min="0" max="50" placeholder="e.g., 5">
                                            @error('years_of_experience')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="consultation_fee">Standard Consultation Fee (KES) *</label>
                                            <input type="number" class="form-control @error('consultation_fee') is-invalid @enderror"
                                                   id="consultation_fee" name="consultation_fee"
                                                   value="{{ old('consultation_fee', auth()->user()->consultation_fee) }}"
                                                   required min="0" step="0.01" placeholder="e.g., 2000.00">
                                            @error('consultation_fee')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="professional_bio">Professional Biography</label>
                                    <textarea class="form-control @error('professional_bio') is-invalid @enderror"
                                              id="professional_bio" name="professional_bio" rows="4"
                                              placeholder="Describe your professional background, education, and experience">{{ old('professional_bio', auth()->user()->professional_bio) }}</textarea>
                                    @error('professional_bio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">This will be visible to farmers on your profile.</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="qualifications">Qualifications</label>
                                            <textarea class="form-control @error('qualifications') is-invalid @enderror"
                                                      id="qualifications" name="qualifications" rows="3"
                                                      placeholder="List your academic qualifications (one per line)">{{ old('qualifications', auth()->user()->qualifications) }}</textarea>
                                            @error('qualifications')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="areas_of_expertise">Areas of Expertise</label>
                                            <textarea class="form-control @error('areas_of_expertise') is-invalid @enderror"
                                                      id="areas_of_expertise" name="areas_of_expertise" rows="3"
                                                      placeholder="List your areas of expertise (comma separated)">{{ old('areas_of_expertise', auth()->user()->areas_of_expertise) }}</textarea>
                                            @error('areas_of_expertise')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emergency_fee_multiplier">Emergency Fee Multiplier *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('emergency_fee_multiplier') is-invalid @enderror"
                                                       id="emergency_fee_multiplier" name="emergency_fee_multiplier"
                                                       value="{{ old('emergency_fee_multiplier', auth()->user()->emergency_fee_multiplier ?? 1.5) }}"
                                                       required min="1" max="3" step="0.1" placeholder="e.g., 1.5">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">x Normal Fee</span>
                                                </div>
                                            </div>
                                            @error('emergency_fee_multiplier')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">Emergency fee = Standard fee × this multiplier</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="after_hours_fee_multiplier">After Hours Fee Multiplier</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('after_hours_fee_multiplier') is-invalid @enderror"
                                                       id="after_hours_fee_multiplier" name="after_hours_fee_multiplier"
                                                       value="{{ old('after_hours_fee_multiplier', auth()->user()->after_hours_fee_multiplier ?? 1.2) }}"
                                                       min="1" max="3" step="0.1" placeholder="e.g., 1.2">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">x Normal Fee</span>
                                                </div>
                                            </div>
                                            @error('after_hours_fee_multiplier')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="weekend_fee_multiplier">Weekend Fee Multiplier</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('weekend_fee_multiplier') is-invalid @enderror"
                                                       id="weekend_fee_multiplier" name="weekend_fee_multiplier"
                                                       value="{{ old('weekend_fee_multiplier', auth()->user()->weekend_fee_multiplier ?? 1.3) }}"
                                                       min="1" max="3" step="0.1" placeholder="e.g., 1.3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">x Normal Fee</span>
                                                </div>
                                            </div>
                                            @error('weekend_fee_multiplier')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Save Professional Info
                                </button>
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- License & Certifications -->
                    <div class="card card-danger mt-4" id="licenses">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-certificate mr-1"></i>
                                License & Certifications
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-danger">Verification Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Upload License Form -->
                            <div class="card card-outline card-danger mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <i class="fas fa-upload mr-1"></i>
                                        Upload New License/Certificate
                                    </h4>
                                </div>
                                <form action="{{ route('veterinary.settings.upload-license') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="document_type">Document Type *</label>
                                                    <select class="form-control @error('document_type') is-invalid @enderror"
                                                            id="document_type" name="document_type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="veterinary_license" {{ old('document_type') == 'veterinary_license' ? 'selected' : '' }}>Veterinary License</option>
                                                        <option value="practice_license" {{ old('document_type') == 'practice_license' ? 'selected' : '' }}>Practice License</option>
                                                        <option value="certification" {{ old('document_type') == 'certification' ? 'selected' : '' }}>Professional Certification</option>
                                                        <option value="degree_certificate" {{ old('document_type') == 'degree_certificate' ? 'selected' : '' }}>Degree Certificate</option>
                                                        <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('document_type')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="document_number">Document Number</label>
                                                    <input type="text" class="form-control @error('document_number') is-invalid @enderror"
                                                           id="document_number" name="document_number"
                                                           value="{{ old('document_number') }}"
                                                           placeholder="e.g., KVB/1234/2023">
                                                    @error('document_number')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="issue_date">Issue Date</label>
                                                    <input type="date" class="form-control @error('issue_date') is-invalid @enderror"
                                                           id="issue_date" name="issue_date"
                                                           value="{{ old('issue_date') }}"
                                                           max="{{ date('Y-m-d') }}">
                                                    @error('issue_date')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="expiry_date">Expiry Date</label>
                                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                                           id="expiry_date" name="expiry_date"
                                                           value="{{ old('expiry_date') }}"
                                                           min="{{ date('Y-m-d') }}">
                                                    @error('expiry_date')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="issuing_authority">Issuing Authority</label>
                                            <input type="text" class="form-control @error('issuing_authority') is-invalid @enderror"
                                                   id="issuing_authority" name="issuing_authority"
                                                   value="{{ old('issuing_authority') }}"
                                                   placeholder="e.g., Kenya Veterinary Board">
                                            @error('issuing_authority')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="document">Upload Document *</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('document') is-invalid @enderror"
                                                       id="document" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                                <label class="custom-file-label" for="document">Choose file (PDF, JPG, PNG, DOC)</label>
                                                @error('document')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">
                                                Maximum file size: 5MB. Accepted formats: PDF, JPG, PNG, DOC, DOCX
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="notes">Notes (Optional)</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                                      id="notes" name="notes" rows="2"
                                                      placeholder="Any additional notes about this document">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-upload mr-1"></i> Upload Document
                                        </button>
                                        <button type="reset" class="btn btn-default">
                                            <i class="fas fa-times mr-1"></i> Clear
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Current Licenses/Certificates -->
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <i class="fas fa-list mr-1"></i>
                                        Current Documents
                                    </h4>
                                </div>
                                <div class="card-body">
                                    @if($licenses && $licenses->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Document Number</th>
                                                        <th>Issuing Authority</th>
                                                        <th>Issue Date</th>
                                                        <th>Expiry Date</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($licenses as $license)
                                                    <tr class="{{ $license->isExpired() ? 'table-danger' : ($license->isExpiringSoon() ? 'table-warning' : '') }}">
                                                        <td>
                                                            <span class="badge badge-{{ $license->getTypeBadge() }}">
                                                                {{ ucfirst(str_replace('_', ' ', $license->document_type)) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $license->document_number ?? 'N/A' }}</td>
                                                        <td>{{ $license->issuing_authority ?? 'N/A' }}</td>
                                                        <td>{{ $license->issue_date?->format('M d, Y') ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ $license->expiry_date?->format('M d, Y') ?? 'N/A' }}
                                                            @if($license->isExpiringSoon())
                                                                <span class="badge badge-warning ml-1">Expiring Soon</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($license->is_verified)
                                                                <span class="badge badge-success">
                                                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                                                </span>
                                                            @elseif($license->is_pending)
                                                                <span class="badge badge-warning">
                                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger">
                                                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                                                </span>
                                                                @if($license->rejection_reason)
                                                                    <small class="d-block text-danger">{{ $license->rejection_reason }}</small>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                @if($license->document_path)
                                                                    <a href="{{ Storage::url($license->document_path) }}"
                                                                       target="_blank" class="btn btn-sm btn-info"
                                                                       title="View Document">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @endif
                                                                <form action="{{ route('veterinary.settings.delete-license', $license) }}"
                                                                      method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this document?')"
                                                                            title="Delete Document">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <h5><i class="fas fa-info-circle mr-2"></i> No Documents Uploaded</h5>
                                            <p class="mb-0">Please upload your veterinary license and certifications for verification.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Verification Requirements -->
                            <div class="alert alert-info mt-4">
                                <h5><i class="fas fa-info-circle mr-2"></i> Verification Requirements</h5>
                                <p class="mb-2">To get your account verified, please upload the following documents:</p>
                                <ul class="mb-0">
                                    <li><strong>Valid Veterinary License</strong> - Must be current and issued by a recognized authority</li>
                                    <li><strong>Professional Certifications</strong> - Any additional certifications you hold</li>
                                    <li><strong>Degree Certificates</strong> - Your academic qualifications</li>
                                </ul>
                                <hr>
                                <p class="mb-0"><strong>Note:</strong> Documents typically take 2-3 business days to verify. You'll receive a notification once verification is complete.</p>
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
    .card {
        margin-bottom: 1.5rem;
        border-top: 3px solid;
    }

    .card-primary {
        border-top-color: #007bff;
    }

    .card-danger {
        border-top-color: #dc3545;
    }

    .card-outline {
        border-top-width: 1px;
    }

    .nav-pills .nav-link.active {
        background-color: #007bff;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75em;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .custom-file-label::after {
        content: "Browse";
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Smooth scrolling for navigation links
        $('.nav-link').on('click', function(event) {
            if (this.hash !== "") {
                event.preventDefault();
                const hash = this.hash;
                $('html, body').animate({
                    scrollTop: $(hash).offset().top - 70
                }, 800);
            }
        });

        // Initialize custom file input
        bsCustomFileInput.init();

        // Set default expiry date to one year from now for new licenses
        $('#document_type').change(function() {
            if ($(this).val() === 'veterinary_license' && !$('#expiry_date').val()) {
                const oneYearLater = new Date();
                oneYearLater.setFullYear(oneYearLater.getFullYear() + 1);
                $('#expiry_date').val(oneYearLater.toISOString().split('T')[0]);
            }
        });

        // Validate file size
        $('#document').on('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                if (fileSize > 5) {
                    alert('File size exceeds 5MB. Please choose a smaller file.');
                    $(this).val('');
                    $('.custom-file-label').text('Choose file (PDF, JPG, PNG, DOC)');
                }
            }
        });

        // Auto-fill document number for veterinary license
        $('#document_type').on('change', function() {
            if ($(this).val() === 'veterinary_license' && !$('#document_number').val()) {
                $('#document_number').val($('#license_number').val());
            }
        });

        // Auto-calculate emergency fees based on consultation fee
        $('#consultation_fee').on('input', function() {
            const consultationFee = parseFloat($(this).val()) || 0;
            const emergencyMultiplier = parseFloat($('#emergency_fee_multiplier').val()) || 1.5;
            const afterHoursMultiplier = parseFloat($('#after_hours_fee_multiplier').val()) || 1.2;
            const weekendMultiplier = parseFloat($('#weekend_fee_multiplier').val()) || 1.3;

            $('#emergency_fee_multiplier').on('input', function() {
                const newMultiplier = parseFloat($(this).val()) || 1.5;
                const emergencyFee = consultationFee * newMultiplier;
                $(this).next('.input-group-append').find('.input-group-text')
                    .text(`x = KES ${emergencyFee.toFixed(2)}`);
            }).trigger('input');

            // Show fee examples
            const emergencyFee = consultationFee * emergencyMultiplier;
            const afterHoursFee = consultationFee * afterHoursMultiplier;
            const weekendFee = consultationFee * weekendMultiplier;

            if (consultationFee > 0) {
                $('#feeExamples').remove();
                $('#consultation_fee').after(`
                    <small id="feeExamples" class="form-text text-muted">
                        <strong>Fee Examples:</strong>
                        Emergency: KES ${emergencyFee.toFixed(2)} |
                        After Hours: KES ${afterHoursFee.toFixed(2)} |
                        Weekend: KES ${weekendFee.toFixed(2)}
                    </small>
                `);
            }
        }).trigger('input');

        // Validate license expiry date
        $('#license_expiry').on('change', function() {
            const expiryDate = new Date($(this).val());
            const today = new Date();

            if (expiryDate < today) {
                alert('License expiry date cannot be in the past. Please enter a future date.');
                $(this).val('');
            }
        });

        // Check if license is expiring soon
        @if(auth()->user()->license_expiry)
            const expiryDate = new Date('{{ auth()->user()->license_expiry->format('Y-m-d') }}');
            const today = new Date();
            const daysDiff = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));

            if (daysDiff <= 30 && daysDiff > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'License Expiring Soon',
                    html: `Your veterinary license expires in <strong>${daysDiff} days</strong>.<br>Please renew it before {{ auth()->user()->license_expiry->format('M d, Y') }}.`,
                    confirmButtonText: 'Update Now',
                    showCancelButton: true,
                    cancelButtonText: 'Later'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('html, body').animate({
                            scrollTop: $('#licenses').offset().top - 70
                        }, 800);
                    }
                });
            } else if (daysDiff <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'License Expired',
                    text: 'Your veterinary license has expired. Please upload a renewed license immediately.',
                    confirmButtonText: 'Upload Renewal',
                    allowOutsideClick: false
                }).then(() => {
                    $('html, body').animate({
                        scrollTop: $('#licenses').offset().top - 70
                    }, 800);
                });
            }
        @endif

        // Professional bio character counter
        $('#professional_bio').on('input', function() {
            const length = $(this).val().length;
            const maxLength = 1000;
            const counter = $('#bio-counter');

            if (!counter.length) {
                $(this).after('<small class="form-text text-muted float-right" id="bio-counter">0/1000 characters</small>');
            }

            $('#bio-counter').text(`${length}/${maxLength} characters`);

            if (length > maxLength) {
                $('#bio-counter').addClass('text-danger');
            } else {
                $('#bio-counter').removeClass('text-danger');
            }
        }).trigger('input');

        // Areas of expertise suggestions
        const expertiseSuggestions = [
            'Poultry Health Management',
            'Disease Diagnosis & Treatment',
            'Vaccination Programs',
            'Biosecurity Implementation',
            'Feed Formulation',
            'Broiler Production',
            'Layer Management',
            'Hatchery Operations',
            'Livestock Health',
            'Preventive Medicine',
            'Farm Management Consulting',
            'Emergency Response'
        ];

        $('#areas_of_expertise').on('focus', function() {
            if (!$(this).data('autocomplete-initialized')) {
                $(this).autocomplete({
                    source: expertiseSuggestions,
                    minLength: 1
                });
                $(this).data('autocomplete-initialized', true);
            }
        });
    });
</script>
@endpush
