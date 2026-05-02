@extends('layouts.app')

@section('title', 'Edit Product - ' . $product->title)

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

    .current-image-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .current-image {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }

    .availability-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
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
                <i class="fas fa-edit text-primary mr-2"></i>Edit Poultry Product
            </h1>
            <p class="text-muted">Update your poultry product information</p>
        </div>
        <div>
            <a href="{{ route('supplier.marketplace.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left mr-1"></i>Back to Products
            </a>
            <form action="{{ route('supplier.marketplace.destroy', $product->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash mr-1"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Product Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->title }}"
                                     class="img-fluid rounded"
                                     style="max-height: 100px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px;">
                                    <i class="fas fa-drumstick-bite text-muted fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-1">{{ $product->title }}</h4>
                            <div class="d-flex flex-wrap mb-2">
                                <span class="badge badge-primary mr-2 mb-1">
                                    <i class="fas fa-drumstick-bite mr-1"></i>{{ ucfirst(str_replace('_', ' ', $product->product_type)) }}
                                </span>
                                @if($product->breed)
                                    <span class="badge badge-info mr-2 mb-1">{{ ucfirst($product->breed) }} Breed</span>
                                @endif
                                <span class="badge badge-success mr-2 mb-1">KES {{ number_format($product->price) }}</span>
                                <span class="badge badge-warning mb-1">{{ $product->quantity }} {{ $product->unit }} available</span>
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $product->location }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar-alt mr-1"></i> Listed {{ $product->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <span class="availability-badge badge-{{ $product->is_available ? 'success' : 'danger' }}">
                                {{ $product->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-edit mr-2"></i>Edit Product Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier.marketplace.update', $product->id) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="productForm">
                        @csrf
                        @method('PUT')

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
                                       value="{{ old('title', $product->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="font-weight-bold required-field">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          required>{{ old('description', $product->description) }}</textarea>
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
                                    <label for="product_type" class="font-weight-bold required-field">Product Type</label>
                                    <select class="form-control @error('product_type') is-invalid @enderror"
                                            id="product_type"
                                            name="product_type"
                                            required>
                                        <option value="chicks" {{ old('product_type', $product->product_type) == 'chicks' ? 'selected' : '' }}>
                                            <i class="fas fa-egg poultry-category-icon"></i>Chicks
                                        </option>
                                        <option value="mature_birds" {{ old('product_type', $product->product_type) == 'mature_birds' ? 'selected' : '' }}>
                                            <i class="fas fa-crow poultry-category-icon"></i>Mature Birds
                                        </option>
                                        <option value="eggs" {{ old('product_type', $product->product_type) == 'eggs' ? 'selected' : '' }}>
                                            <i class="fas fa-egg poultry-category-icon"></i>Eggs
                                        </option>
                                        <option value="feed" {{ old('product_type', $product->product_type) == 'feed' ? 'selected' : '' }}>
                                            <i class="fas fa-seedling poultry-category-icon"></i>Feed
                                        </option>
                                        <option value="medication" {{ old('product_type', $product->product_type) == 'medication' ? 'selected' : '' }}>
                                            <i class="fas fa-pills poultry-category-icon"></i>Medication & Vaccines
                                        </option>
                                        <option value="equipment" {{ old('product_type', $product->product_type) == 'equipment' ? 'selected' : '' }}>
                                            <i class="fas fa-tools poultry-category-icon"></i>Equipment
                                        </option>
                                        <option value="other" {{ old('product_type', $product->product_type) == 'other' ? 'selected' : '' }}>
                                            <i class="fas fa-box poultry-category-icon"></i>Other
                                        </option>
                                    </select>
                                    @error('product_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="breed" class="font-weight-bold">Breed (Optional)</label>
                                    <select class="form-control @error('breed') is-invalid @enderror"
                                            id="breed"
                                            name="breed">
                                        <option value="">Select Breed</option>
                                        <option value="broiler" {{ old('breed', $product->breed) == 'broiler' ? 'selected' : '' }}>Broiler</option>
                                        <option value="layer" {{ old('breed', $product->breed) == 'layer' ? 'selected' : '' }}>Layer</option>
                                        <option value="kienyeji" {{ old('breed', $product->breed) == 'kienyeji' ? 'selected' : '' }}>Kienyeji</option>
                                        <option value="hybrid" {{ old('breed', $product->breed) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                        <option value="other" {{ old('breed', $product->breed) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('breed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Age/Weight -->
                            <div class="row" id="ageWeightSection">
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="font-weight-bold">Age (Optional)</label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control @error('age') is-invalid @enderror"
                                               id="age"
                                               name="age"
                                               value="{{ old('age', $product->age) }}"
                                               min="0">
                                        <div class="input-group-append">
                                            <select class="form-control" id="age_unit" name="age_unit">
                                                <option value="days" {{ old('age_unit', $product->age_unit) == 'days' ? 'selected' : '' }}>Days</option>
                                                <option value="weeks" {{ old('age_unit', $product->age_unit) == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                                <option value="months" {{ old('age_unit', $product->age_unit) == 'months' ? 'selected' : '' }}>Months</option>
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
                                               value="{{ old('weight', $product->weight) }}"
                                               step="0.01"
                                               min="0">
                                        <div class="input-group-append">
                                            <select class="form-control" id="weight_unit" name="weight_unit">
                                                <option value="grams" {{ old('weight_unit', $product->weight_unit) == 'grams' ? 'selected' : '' }}>Grams</option>
                                                <option value="kg" {{ old('weight_unit', $product->weight_unit) == 'kg' ? 'selected' : '' }}>Kg</option>
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
                                               value="{{ old('price', $product->price) }}"
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
                                           value="{{ old('quantity', $product->quantity) }}"
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
                                        <option value="piece" {{ old('unit', $product->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                                        <option value="dozen" {{ old('unit', $product->unit) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                        <option value="tray" {{ old('unit', $product->unit) == 'tray' ? 'selected' : '' }}>Tray (30 eggs)</option>
                                        <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="bag" {{ old('unit', $product->unit) == 'bag' ? 'selected' : '' }}>Bag (70kg/50kg)</option>
                                        <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="carton" {{ old('unit', $product->unit) == 'carton' ? 'selected' : '' }}>Carton</option>
                                        <option value="litre" {{ old('unit', $product->unit) == 'litre' ? 'selected' : '' }}>Litre</option>
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
                                           value="{{ old('location', $product->location) }}"
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
                                           value="{{ old('min_order', $product->min_order) }}"
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
                                           value="{{ old('max_order', $product->max_order) }}"
                                           min="1">
                                    @error('max_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Availability & Image -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-camera mr-2"></i>Product Image & Availability
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="current-image-container">
                                        <p class="font-weight-bold mb-2">Current Image</p>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->title }}"
                                                 class="current-image mb-2">
                                            <br>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    id="removeImageBtn">
                                                <i class="fas fa-trash mr-1"></i>Remove Image
                                            </button>
                                            <input type="hidden" name="remove_image" id="removeImage" value="0">
                                        @else
                                            <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center"
                                                 style="height: 150px;">
                                                <i class="fas fa-image text-muted fa-3x mb-2"></i>
                                                <p class="text-muted">No image uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Update Image (Optional)</label>
                                        <div class="product-image-upload" id="imageUploadArea">
                                            <img id="imagePreview" class="product-image-preview mb-3" style="display: none;" alt="Preview">
                                            <div id="uploadPlaceholder">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted small">Click to upload new image</p>
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
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_available"
                                               name="is_available"
                                               value="1"
                                               {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold" for="is_available">
                                            Product is available for purchase
                                        </label>
                                    </div>
                                    <small class="text-muted">Uncheck to temporarily hide this product from the marketplace</small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-top pt-4 mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-save mr-2"></i>Update Product
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
                        <i class="fas fa-lightbulb text-warning mr-2"></i>Editing Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <strong>Keep information accurate</strong> - Update stock levels and prices regularly
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <strong>Refresh images</strong> - Upload current photos to show product condition
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <strong>Review descriptions</strong> - Update details based on customer feedback
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <strong>Check availability</strong> - Set to unavailable if out of stock
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <strong>Update location</strong> - Ensure delivery information is current
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Poultry-Specific Editing Tips -->
            <div class="card shadow mt-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-drumstick-bite text-primary mr-2"></i>Poultry Product Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>Chicks:</strong> Update age in days and vaccination status
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>Eggs:</strong> Specify if they're for consumption or hatching
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>Feed:</strong> Update nutritional information and batch details
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <strong>Mature Birds:</strong> Update weight and health status regularly
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow mt-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar text-info mr-2"></i>Product Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Date Added</small>
                        <strong>{{ $product->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Last Updated</small>
                        <strong>{{ $product->updated_at->diffForHumans() }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Product ID</small>
                        <strong>#{{ $product->id }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Views</small>
                        <strong>{{ $product->views ?? 0 }}</strong>
                    </div>
                    @if($product->orders_count > 0)
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Orders</small>
                        <strong>{{ $product->orders_count }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Upload Handling
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const removeImageInput = document.getElementById('removeImage');

        if (imageUploadArea && imageInput) {
            // Click on upload area triggers file input
            imageUploadArea.addEventListener('click', function() {
                imageInput.click();
            });

            // Handle file selection
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
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
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        uploadPlaceholder.style.display = 'none';
                        imageUploadArea.classList.add('has-image');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Remove Image Button
        if (removeImageBtn && removeImageInput) {
            removeImageBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove the current image?')) {
                    removeImageInput.value = '1';
                    this.closest('.current-image-container').innerHTML = `
                        <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center"
                             style="height: 150px;">
                            <i class="fas fa-image text-muted fa-3x mb-2"></i>
                            <p class="text-muted">Image removed</p>
                        </div>
                    `;
                }
            });
        }

        // Show/hide age/weight section based on product type
        const productTypeSelect = document.getElementById('product_type');
        const ageWeightSection = document.getElementById('ageWeightSection');

        function toggleAgeWeightSection() {
            const selectedType = productTypeSelect.value;
            const showSection = ['chicks', 'mature_birds', 'feed'].includes(selectedType);

            if (ageWeightSection) {
                ageWeightSection.style.display = showSection ? 'flex' : 'none';
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
                    maxOrder.nextElementSibling.innerHTML = 'Maximum order must be greater than or equal to minimum order';
                    valid = false;
                }

                // Validate price
                const price = document.getElementById('price');
                if (price && parseFloat(price.value) <= 0) {
                    price.classList.add('is-invalid');
                    alert('Price must be greater than 0');
                    valid = false;
                }

                // Validate quantity
                const quantity = document.getElementById('quantity');
                if (quantity && parseInt(quantity.value) < 0) {
                    quantity.classList.add('is-invalid');
                    alert('Quantity cannot be negative');
                    valid = false;
                }

                // Validate min order
                if (minOrder && parseInt(minOrder.value) < 1) {
                    minOrder.classList.add('is-invalid');
                    alert('Minimum order must be at least 1');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly.');
                } else {
                    // Show loading state
                    const submitBtn = productForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                    submitBtn.disabled = true;

                    // Re-enable after 3 seconds if form hasn't submitted
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                }
            });
        }
    });
</script>
@endsection
