@extends('layouts.app')

@section('title', 'Create Subscription Plan')

@section('styles')
<style>
    .feature-dropdown-container {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        max-height: 250px;
        overflow-y: auto;
        padding: 0.5rem;
        background: #f8f9fa;
    }

    .feature-item-checkbox {
        display: block;
        padding: 0.5rem;
        margin-bottom: 0.25rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .feature-item-checkbox:hover {
        background: #e9ecef;
    }

    .feature-item-checkbox input {
        margin-right: 0.75rem;
    }

    .selected-features-tag {
        background: #e8f5e9;
        border: 1px solid #28a745;
        border-radius: 50px;
        padding: 0.25rem 1rem;
        display: inline-flex;
        align-items: center;
        margin: 0.25rem;
        font-size: 0.85rem;
    }

    .selected-features-tag i {
        margin-left: 0.5rem;
        cursor: pointer;
        color: #dc3545;
    }

    .selected-features-tag i:hover {
        color: #bd2130;
    }

    .filter-features {
        margin-bottom: 1rem;
    }

    .feature-badge {
        background: #007bff;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        margin-left: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Subscription Plan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">New Subscription Plan</h3>
                    </div>
                    <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Plan Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug *</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                               id="slug" name="slug" value="{{ old('slug') }}" required>
                                        <small class="text-muted">URL-friendly name (e.g., basic, pro, premium)</small>
                                        @error('slug')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price (KES) *</label>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                               id="price" name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duration">Duration *</label>
                                        <select class="form-control @error('duration') is-invalid @enderror"
                                                id="duration" name="duration" required>
                                            <option value="monthly" {{ old('duration') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="yearly" {{ old('duration') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        </select>
                                        @error('duration')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="features">Features *</label>

                                <!-- Search/Filter Features -->
                                <div class="filter-features input-group mb-3">
                                    <input type="text" class="form-control" id="featureSearch" placeholder="Search features...">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Selected Features Tags -->
                                <div class="selected-features mb-3" id="selectedFeaturesContainer">
                                    <label class="text-success font-weight-bold">
                                        <i class="fas fa-check-circle mr-1"></i>Selected Features:
                                    </label>
                                    <div id="selectedTags" class="d-flex flex-wrap"></div>
                                </div>

                                <!-- Hidden input to store selected features -->
                                <input type="hidden" name="features_json" id="selectedFeaturesInput">

                                <!-- Features Dropdown -->
                                <div class="feature-dropdown-container" id="featureDropdown">
                                    <label class="text-primary font-weight-bold mb-2">
                                        <i class="fas fa-list mr-1"></i>Available Features:
                                    </label>

                                    @php
                                        $availableFeatures = [
                                            'Consultations' => [
                                                'Up to 50 consultations per month',
                                                'Up to 100 consultations per month',
                                                'Unlimited consultations',
                                                'Priority consultation queue',
                                                'Emergency consultations',
                                                'Telemedicine consultations',
                                                'Video consultations',
                                            ],
                                            'Farm Visits' => [
                                                'Up to 10 farm visits per month',
                                                'Up to 20 farm visits per month',
                                                'Unlimited farm visits',
                                                'Emergency farm visits',
                                                'Scheduled farm visits',
                                                'Multi-farm visits',
                                            ],
                                            'Analytics' => [
                                                'Basic analytics',
                                                'Advanced analytics & reporting',
                                                'Revenue tracking',
                                                'Performance metrics',
                                                'Custom reports',
                                                'Data export',
                                            ],
                                            'Support' => [
                                                'Email support',
                                                'Priority phone support',
                                                '24/7 emergency support',
                                                'Dedicated account manager',
                                                'Live chat support',
                                            ],
                                            'Features' => [
                                                'Telemedicine capabilities',
                                                'Disease outbreak alerts',
                                                'Multi-farm management',
                                                'Prescription management',
                                                'Vaccination tracking',
                                                'Health records management',
                                                'Mobile app access',
                                                'API access',
                                            ],
                                        ];
                                    @endphp

                                    @foreach($availableFeatures as $category => $features)
                                    <div class="category-group mb-3">
                                        <h6 class="bg-light p-2 rounded">
                                            <i class="fas fa-folder-open text-primary mr-1"></i>
                                            {{ $category }}
                                            <span class="feature-badge">{{ count($features) }}</span>
                                        </h6>
                                        @foreach($features as $feature)
                                        <label class="feature-item-checkbox">
                                            <input type="checkbox" class="feature-checkbox" value="{{ $feature }}">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            {{ $feature }}
                                        </label>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                                @error('features')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active (visible to users)</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Create Plan
                            </button>
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary float-right">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedFeatures = [];

    // Auto-generate slug from name
    $('#name').on('keyup', function() {
        let slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '-');
        $('#slug').val(slug);
    });

    // Handle feature selection
    $(document).on('change', '.feature-checkbox', function() {
        const feature = $(this).val();

        if ($(this).is(':checked')) {
            if (!selectedFeatures.includes(feature)) {
                selectedFeatures.push(feature);
            }
        } else {
            selectedFeatures = selectedFeatures.filter(f => f !== feature);
        }

        updateSelectedTags();
        updateHiddenInput();
    });

    // Search/filter features
    $('#featureSearch').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();

        $('.feature-item-checkbox').each(function() {
            const featureText = $(this).text().toLowerCase();
            if (featureText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Show/hide category groups
        $('.category-group').each(function() {
            const visibleItems = $(this).find('.feature-item-checkbox:visible').length;
            if (visibleItems === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // Update selected tags display
    function updateSelectedTags() {
        const container = $('#selectedTags');
        container.empty();

        if (selectedFeatures.length === 0) {
            container.html('<p class="text-muted small">No features selected yet</p>');
            return;
        }

        selectedFeatures.forEach(feature => {
            container.append(`
                <span class="selected-features-tag">
                    <i class="fas fa-check-circle text-success mr-1"></i>
                    ${feature}
                    <i class="fas fa-times-circle remove-feature-tag" data-feature="${feature}"></i>
                </span>
            `);
        });
    }

    // Remove feature by clicking tag
    $(document).on('click', '.remove-feature-tag', function() {
        const feature = $(this).data('feature');
        selectedFeatures = selectedFeatures.filter(f => f !== feature);

        // Uncheck the corresponding checkbox
        $(`.feature-checkbox[value="${feature}"]`).prop('checked', false);

        updateSelectedTags();
        updateHiddenInput();
    });

    // Update hidden input with selected features
    function updateHiddenInput() {
        $('#selectedFeaturesInput').val(JSON.stringify(selectedFeatures));
    }

    // Load initial features if editing
    @if(isset($plan) && $plan->features)
        selectedFeatures = {!! json_encode($plan->features_list) !!};

        // Check the corresponding checkboxes
        selectedFeatures.forEach(feature => {
            $(`.feature-checkbox[value="${feature}"]`).prop('checked', true);
        });

        updateSelectedTags();
        updateHiddenInput();
    @endif

    // Add feature via custom input (optional)
    $('#add-custom-feature').click(function() {
        const customFeature = $('#customFeature').val().trim();
        if (customFeature && !selectedFeatures.includes(customFeature)) {
            selectedFeatures.push(customFeature);

            // Add to checkboxes (optional)
            $('#featureDropdown .category-group:last').append(`
                <label class="feature-item-checkbox">
                    <input type="checkbox" class="feature-checkbox" value="${customFeature}" checked>
                    <i class="fas fa-check-circle text-success mr-1"></i>
                    ${customFeature}
                </label>
            `);

            $('#customFeature').val('');
            updateSelectedTags();
            updateHiddenInput();
        }
    });
});
</script>
@endpush
