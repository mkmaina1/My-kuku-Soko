@extends('layouts.app')

@section('title', 'Add New Poultry Product')

@section('styles')
<style>
    .product-image-upload {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        position: relative;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .product-image-upload:hover {
        border-color: #1cc88a;
        background-color: rgba(28, 200, 138, 0.05);
    }

    .product-image-upload.has-image {
        border-color: #1cc88a;
        background-color: rgba(28, 200, 138, 0.1);
    }

    .product-image-preview {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: #1cc88a;
        box-shadow: 0 0 0 0.2rem rgba(28, 200, 138, 0.25);
    }

    .required-field::after {
        content: " *";
        color: #e74a3b;
    }

    .poultry-category-icon {
        font-size: 1.2rem;
        margin-right: 8px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>Add New Poultry Product
            </h1>
            <p class="text-muted">List your poultry products for sale in the marketplace</p>
        </div>
        <div>
            <a href="{{ route('supplier.marketplace.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Create Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-feather-alt mr-2"></i>Poultry Product Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier.marketplace.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="productForm">
                        @csrf

                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle mr-2"></i>Basic Information
                            </h6>

                            <div class="form-group mb-3">
                                <label for="title" class="font-weight-bold required-field">Product Title</label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       placeholder="e.g., Day-Old Chicks (Broilers), Layer Feed, Fresh Eggs"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="font-weight-bold required-field">Product Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Describe your poultry product (breed, age, quality, etc.)"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                  <!-- Poultry Category & Type -->
<div class="mb-4">
    <h6 class="text-primary mb-3">
        <i class="fas fa-drumstick-bite mr-2"></i>Poultry Category
    </h6>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="category" class="font-weight-bold required-field">Category</label>
            <select class="form-control @error('category') is-invalid @enderror"
                    id="category"
                    name="category"
                    required>
                <option value="">Select Category</option>
                <option value="poultry" {{ old('category') == 'poultry' ? 'selected' : '' }}>Poultry</option>
                <option value="feed" {{ old('category') == 'feed' ? 'selected' : '' }}>Feed</option>
                <option value="medication" {{ old('category') == 'medication' ? 'selected' : '' }}>Medication & Vaccines</option>
                <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="product_type" class="font-weight-bold required-field">Product Type</label>
            <select class="form-control @error('product_type') is-invalid @enderror"
                    id="product_type"
                    name="product_type"
                    required>
                <option value="">Select Product Type</option>
                <option value="chicks" {{ old('product_type') == 'chicks' ? 'selected' : '' }}>
                    <i class="fas fa-egg poultry-category-icon"></i>Chicks
                </option>
                <option value="mature_birds" {{ old('product_type') == 'mature_birds' ? 'selected' : '' }}>
                    <i class="fas fa-crow poultry-category-icon"></i>Mature Birds
                </option>
                <option value="eggs" {{ old('product_type') == 'eggs' ? 'selected' : '' }}>
                    <i class="fas fa-egg poultry-category-icon"></i>Eggs
                </option>
                <option value="feed" {{ old('product_type') == 'feed' ? 'selected' : '' }}>
                    <i class="fas fa-seedling poultry-category-icon"></i>Feed
                </option>
                <option value="medication" {{ old('product_type') == 'medication' ? 'selected' : '' }}>
                    <i class="fas fa-pills poultry-category-icon"></i>Medication & Vaccines
                </option>
                <option value="equipment" {{ old('product_type') == 'equipment' ? 'selected' : '' }}>
                    <i class="fas fa-tools poultry-category-icon"></i>Equipment
                </option>
                <option value="other" {{ old('product_type') == 'other' ? 'selected' : '' }}>
                    <i class="fas fa-box poultry-category-icon"></i>Other
                </option>
            </select>
            @error('product_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Breed Selection (Conditional) -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="breed" class="font-weight-bold">Breed (Optional)</label>
            <select class="form-control @error('breed') is-invalid @enderror"
                    id="breed"
                    name="breed">
                <option value="">Select Breed</option>
                <option value="broiler" {{ old('breed') == 'broiler' ? 'selected' : '' }}>Broiler</option>
                <option value="layer" {{ old('breed') == 'layer' ? 'selected' : '' }}>Layer</option>
                <option value="kienyeji" {{ old('breed') == 'kienyeji' ? 'selected' : '' }}>Kienyeji</option>
                <option value="hybrid" {{ old('breed') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                <option value="other" {{ old('breed') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('breed')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Age/Weight (Conditional) -->
    <div class="row" id="ageWeightSection">
        <div class="col-md-6 mb-3">
            <label for="age" class="font-weight-bold">Age (Optional)</label>
            <div class="input-group">
                <input type="number"
                       class="form-control @error('age') is-invalid @enderror"
                       id="age"
                       name="age"
                       value="{{ old('age') }}"
                       placeholder="e.g., 1"
                       min="0">
                <div class="input-group-append">
                    <select class="form-control" id="age_unit" name="age_unit">
                        <option value="days" {{ old('age_unit') == 'days' ? 'selected' : '' }}>Days</option>
                        <option value="weeks" {{ old('age_unit') == 'weeks' ? 'selected' : '' }}>Weeks</option>
                        <option value="months" {{ old('age_unit') == 'months' ? 'selected' : '' }}>Months</option>
                    </select>
                </div>
            </div>
            @error('age')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="weight" class="font-weight-bold">Weight (Optional)</label>
            <div class="input-group">
                <input type="number"
                       class="form-control @error('weight') is-invalid @enderror"
                       id="weight"
                       name="weight"
                       value="{{ old('weight') }}"
                       placeholder="e.g., 2.5"
                       step="0.01"
                       min="0">
                <div class="input-group-append">
                    <select class="form-control" id="weight_unit" name="weight_unit">
                        <option value="grams" {{ old('weight_unit') == 'grams' ? 'selected' : '' }}>Grams</option>
                        <option value="kg" {{ old('weight_unit') == 'kg' ? 'selected' : '' }}>Kg</option>
                    </select>
                </div>
            </div>
            @error('weight')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
                        <!-- Pricing & Stock -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-money-bill-wave mr-2"></i>Pricing & Stock
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="font-weight-bold required-field">Price (KES)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">KES</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('price') is-invalid @enderror"
                                               id="price"
                                               name="price"
                                               value="{{ old('price') }}"
                                               placeholder="e.g., 1500"
                                               step="0.01"
                                               min="0"
                                               required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="font-weight-bold required-field">Quantity Available</label>
                                    <input type="number"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity"
                                           name="quantity"
                                           value="{{ old('quantity') }}"
                                           placeholder="e.g., 100"
                                           min="0"
                                           required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="unit" class="font-weight-bold required-field">Unit of Measure</label>
                                    <select class="form-control @error('unit') is-invalid @enderror"
                                            id="unit"
                                            name="unit"
                                            required>
                                        <option value="">Select Unit</option>
                                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                                        <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                        <option value="tray" {{ old('unit') == 'tray' ? 'selected' : '' }}>Tray (30 eggs)</option>
                                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="bag" {{ old('unit') == 'bag' ? 'selected' : '' }}>Bag (70kg/50kg)</option>
                                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="carton" {{ old('unit') == 'carton' ? 'selected' : '' }}>Carton</option>
                                        <option value="litre" {{ old('unit') == 'litre' ? 'selected' : '' }}>Litre</option>
                                    </select>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="location" class="font-weight-bold required-field">Location</label>
                                    <input type="text"
                                           class="form-control @error('location') is-invalid @enderror"
                                           id="location"
                                           name="location"
                                           value="{{ old('location') }}"
                                           placeholder="e.g., Nairobi, Kiambu Road"
                                           required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Order Limits -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shopping-cart mr-2"></i>Order Limits
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_order" class="font-weight-bold required-field">Minimum Order Quantity</label>
                                    <input type="number"
                                           class="form-control @error('min_order') is-invalid @enderror"
                                           id="min_order"
                                           name="min_order"
                                           value="{{ old('min_order', 1) }}"
                                           min="1"
                                           required>
                                    @error('min_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_order" class="font-weight-bold">Maximum Order Quantity (Optional)</label>
                                    <input type="number"
                                           class="form-control @error('max_order') is-invalid @enderror"
                                           id="max_order"
                                           name="max_order"
                                           value="{{ old('max_order') }}"
                                           placeholder="Leave empty for no limit"
                                           min="1">
                                    @error('max_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-camera mr-2"></i>Product Image
                            </h6>

                            <div class="form-group">
                                <label class="font-weight-bold">Upload Image (Optional)</label>
                                <div class="product-image-upload" id="imageUploadArea">
                                    <img id="imagePreview" class="product-image-preview mb-3" style="display: none;" alt="Preview">
                                    <div id="uploadPlaceholder">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small">Click to upload product image</p>
                                        <small class="text-muted">Recommended: 800x800px, max 2MB</small>
                                    </div>
                                    <input type="file"
                                           class="d-none"
                                           id="imageInput"
                                           name="image"
                                           accept="image/*">
                                </div>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_available"
                                       name="is_available"
                                       value="1"
                                       {{ old('is_available', true) ? 'checked' : '' }}>
                                <label class="form-check-label font-weight-bold" for="is_available">
                                    Make product available immediately
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-top pt-4 mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-plus-circle mr-2"></i>Create Product
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('supplier.marketplace.index') }}" class="btn btn-outline-secondary btn-lg btn-block">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tips Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb text-warning mr-2"></i>Tips for Better Product Listings
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Use clear, high-quality images</strong> - Show your product from multiple angles
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Write detailed descriptions</strong> - Include specifications, uses, and benefits
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Set competitive prices</strong> - Research similar products in the marketplace
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Accurate stock levels</strong> - Update quantities regularly to avoid overselling
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Realistic order limits</strong> - Consider your capacity and delivery capabilities
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Poultry-Specific Tips -->
            <div class="card shadow mt-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-drumstick-bite text-primary mr-2"></i>Poultry-Specific Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>For Chicks:</strong> Specify breed, age in days, and vaccination status
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>For Eggs:</strong> Mention size, freshness, and whether they're for consumption or hatching
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>For Feed:</strong> Include ingredients, nutritional value, and target bird age
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>For Mature Birds:</strong> Specify purpose (layers, broilers, breeders) and weight range
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Debug helper function
    function debugLog(message, data = null) {
        console.log(`[Image Upload Debug] ${message}`, data || '');
    }

    document.addEventListener('DOMContentLoaded', function() {
        debugLog('DOM Content Loaded');

        // Image Upload Handling
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');

        debugLog('Elements found:', {
            imageUploadArea: !!imageUploadArea,
            imageInput: !!imageInput,
            imagePreview: !!imagePreview,
            uploadPlaceholder: !!uploadPlaceholder
        });

        if (imageUploadArea && imageInput) {
            debugLog('Setting up image upload handlers');

            // Make file input cover the entire upload area for better click handling
            imageInput.style.position = 'absolute';
            imageInput.style.width = '100%';
            imageInput.style.height = '100%';
            imageInput.style.top = '0';
            imageInput.style.left = '0';
            imageInput.style.opacity = '0';
            imageInput.style.cursor = 'pointer';
            imageInput.style.zIndex = '10';

            // Click on upload area triggers file input
            imageUploadArea.addEventListener('click', function(e) {
                debugLog('Upload area clicked');
                e.stopPropagation();
                imageInput.click();
            });

            // Also allow clicking on the placeholder specifically
            if (uploadPlaceholder) {
                uploadPlaceholder.addEventListener('click', function(e) {
                    debugLog('Placeholder clicked');
                    e.stopPropagation();
                    imageInput.click();
                });
            }

            // Handle file selection
            imageInput.addEventListener('change', function(e) {
                debugLog('File input changed');
                const file = e.target.files[0];
                if (file) {
                    debugLog('File selected:', {
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        sizeMB: (file.size / (1024 * 1024)).toFixed(2) + 'MB'
                    });

                    // Validate file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        imageInput.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPG, PNG, GIF, or WEBP)');
                        imageInput.value = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        debugLog('File loaded, showing preview');
                        if (imagePreview) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        }
                        if (uploadPlaceholder) {
                            uploadPlaceholder.style.display = 'none';
                        }
                        imageUploadArea.classList.add('has-image');
                        debugLog('Preview shown, placeholder hidden');
                    };

                    reader.onerror = function(e) {
                        debugLog('Error reading file:', e);
                        alert('Error reading the image file. Please try another image.');
                    };

                    reader.readAsDataURL(file);
                } else {
                    debugLog('No file selected');
                }
            });

            // Test if event listener is working
            debugLog('Event listeners attached successfully');
        } else {
            debugLog('ERROR: Required elements not found!');
            // Show a visible error for debugging
            if (!imageUploadArea) {
                console.error('Element #imageUploadArea not found in DOM');
            }
            if (!imageInput) {
                console.error('Element #imageInput not found in DOM');
            }
        }

        // Show/hide age/weight section based on product type
        const productTypeSelect = document.getElementById('product_type');
        const ageWeightSection = document.getElementById('ageWeightSection');

        function toggleAgeWeightSection() {
            const selectedType = productTypeSelect.value;
            const showSection = ['chicks', 'mature_birds', 'feed'].includes(selectedType);

            if (ageWeightSection) {
                ageWeightSection.style.display = showSection ? 'flex' : 'none';
                debugLog('Age/Weight section toggled:', {
                    selectedType: selectedType,
                    showSection: showSection,
                    display: ageWeightSection.style.display
                });
            }
        }

        if (productTypeSelect) {
            productTypeSelect.addEventListener('change', toggleAgeWeightSection);
            toggleAgeWeightSection(); // Initial call
        }

        // Form validation
        const productForm = document.getElementById('productForm');
        if (productForm) {
            productForm.addEventListener('submit', function(e) {
                debugLog('Form submit triggered');
                let valid = true;
                const requiredFields = productForm.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        valid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Validate max order if provided
                const minOrder = document.getElementById('min_order');
                const maxOrder = document.getElementById('max_order');

                if (maxOrder && maxOrder.value && parseInt(maxOrder.value) < parseInt(minOrder.value)) {
                    maxOrder.classList.add('is-invalid');
                    alert('Maximum order must be greater than or equal to minimum order');
                    valid = false;
                }

                // Validate price
                const price = document.getElementById('price');
                if (price && parseFloat(price.value) <= 0) {
                    price.classList.add('is-invalid');
                    alert('Price must be greater than 0');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly.');
                } else {
                    debugLog('Form validation passed, submitting...');
                }
            });
        }

        // Test button to manually trigger file input
        const testButton = document.createElement('button');
        testButton.textContent = 'Test Image Upload';
        testButton.style.position = 'fixed';
        testButton.style.bottom = '10px';
        testButton.style.right = '10px';
        testButton.style.zIndex = '9999';
        testButton.style.padding = '10px';
        testButton.style.backgroundColor = '#ff4444';
        testButton.style.color = 'white';
        testButton.style.border = 'none';
        testButton.style.borderRadius = '5px';
        testButton.style.cursor = 'pointer';

        testButton.addEventListener('click', function() {
            debugLog('Test button clicked');
            if (imageInput) {
                debugLog('Manually triggering file input click');
                imageInput.click();
            } else {
                debugLog('ERROR: imageInput not found for test button');
            }
        });

        document.body.appendChild(testButton);
    });
</script>
@endsection
