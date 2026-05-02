@extends('layouts.app')

@section('title', 'Apply for Verification')

@section('styles')
<style>
    .verification-icon {
        font-size: 3rem;
        color: #2e7d32;
        margin-bottom: 1rem;
    }

    .document-requirements {
        background: #f8f9fa;
        border-left: 4px solid #2e7d32;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
    }

    .custom-file-label::after {
        content: "Browse";
    }

    .custom-file-input:lang(en) ~ .custom-file-label::after {
        content: "Browse";
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Apply for Account Verification
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Display session messages -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Info Alert -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Verification helps build trust and gives you access to all platform features.
                        Your documents will be securely stored and only visible to administrators.
                    </div>

                    <!-- Document Requirements -->
                    <div class="document-requirements">
                        <h5><i class="fas fa-file-image mr-2 text-primary"></i>Document Requirements:</h5>
                        <ul class="mb-0">
                            <li>Document must be valid and not expired</li>
                            <li>Clear, well-lit photo showing all four corners</li>
                            <li>All information must be clearly visible and readable</li>
                            <li>Maximum file size: 5MB per image</li>
                            <li>Accepted formats: JPG, PNG</li>
                        </ul>
                    </div>

                    <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Document Type -->
                        <div class="form-group">
                            <label for="document_type">
                                <i class="fas fa-id-card mr-1 text-primary"></i>
                                Document Type *
                            </label>
                            <select class="form-control @error('document_type') is-invalid @enderror"
                                    id="document_type"
                                    name="document_type"
                                    required>
                                <option value="">Select document type</option>
                                <option value="id_card" {{ old('document_type') == 'id_card' ? 'selected' : '' }}>National ID Card</option>
                                <option value="driving_license" {{ old('document_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                <option value="business_registration" {{ old('document_type') == 'business_registration' ? 'selected' : '' }}>Business Registration</option>
                                <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Other Official Document</option>
                            </select>
                            @error('document_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Document Front -->
                        <div class="form-group">
                            <label for="document_front">
                                <i class="fas fa-file-image mr-1 text-primary"></i>
                                Document Front Photo *
                            </label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('document_front') is-invalid @enderror"
                                       id="document_front"
                                       name="document_front"
                                       accept="image/*"
                                       required>
                                <label class="custom-file-label" for="document_front">Choose file...</label>
                            </div>
                            <small class="text-muted">Clear photo of the front side of your document (max 5MB)</small>
                            @error('document_front')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Document Back -->
                        <div class="form-group">
                            <label for="document_back">
                                <i class="fas fa-file-image mr-1 text-secondary"></i>
                                Document Back Photo (Optional)
                            </label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('document_back') is-invalid @enderror"
                                       id="document_back"
                                       name="document_back"
                                       accept="image/*">
                                <label class="custom-file-label" for="document_back">Choose file...</label>
                            </div>
                            <small class="text-muted">Clear photo of the back side of your document (max 5MB)</small>
                            @error('document_back')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Additional Info -->
                        <div class="form-group">
                            <label for="additional_info">
                                <i class="fas fa-edit mr-1 text-primary"></i>
                                Additional Information (Optional)
                            </label>
                            <textarea class="form-control @error('additional_info') is-invalid @enderror"
                                      id="additional_info"
                                      name="additional_info"
                                      rows="3"
                                      placeholder="Add any additional information that might help with verification...">{{ old('additional_info') }}</textarea>
                            @error('additional_info')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="form-group form-check">
                            <input type="checkbox"
                                   class="form-check-input @error('terms') is-invalid @enderror"
                                   id="terms"
                                   name="terms"
                                   value="1"
                                   {{ old('terms') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="terms">
                                I certify that the information provided is accurate and belongs to me.
                                I understand that providing false information may result in account suspension.
                            </label>
                            @error('terms')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        <!-- Submit Buttons -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary px-5 py-2">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submit Application
                            </button>
                            <a href="{{ route('profile.edit') }}" class="btn btn-secondary px-5 py-2 ml-2">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-clock mr-1"></i>
                        Verification typically takes 24-48 hours. You'll receive a notification once processed.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update file input labels with selected filename
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass('selected').html(fileName);
    });

    // Preview images before upload (optional)
    function readURL(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#document_front').on('change', function() {
        readURL(this, 'frontPreview');
    });

    $('#document_back').on('change', function() {
        readURL(this, 'backPreview');
    });
</script>
@endpush
