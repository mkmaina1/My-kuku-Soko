@extends('layouts.app')

@section('title', $product->title . ' - Marketplace')

@section('styles')
<style>
    .product-image-main {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .product-image-main:hover {
        transform: scale(1.02);
    }
    .product-thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .product-thumbnail:hover {
        border-color: #007bff;
        transform: translateY(-2px);
    }
    .product-thumbnail.active {
        border-color: #007bff;
        box-shadow: 0 2px 8px rgba(0,123,255,0.3);
    }
    .product-info-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 20px;
    }
    .product-price {
        font-size: 2rem;
        font-weight: bold;
        color: #28a745;
    }
    .stock-badge {
        font-size: 0.85rem;
        padding: 5px 15px;
        border-radius: 20px;
    }
    .supplier-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    .quantity-control {
        max-width: 120px;
    }
    .add-to-cart-btn {
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40,167,69,0.3);
    }
    .product-tabs .nav-link {
        font-weight: 600;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        transition: all 0.3s ease;
    }
    .product-tabs .nav-link.active {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        transform: translateY(-2px);
    }
    .related-product-card {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
    }
    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        border-color: #007bff;
    }
    .rating-stars {
        color: #ffc107;
        font-size: 1.2rem;
    }
    .specification-item {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .specification-item:last-child {
        border-bottom: none;
    }
    .view-count {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .delivery-info-card {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-left: 4px solid #2196f3;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    .verified-badge {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>
@endsection

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-store mr-2"></i>Marketplace
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->title, 30) }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column: Product Images & Gallery -->
            <div class="col-lg-5 mb-4">
                <div class="product-info-card">
                    <!-- Main Product Image -->
                    <div class="mb-4 text-center">
                        @if($product->images && count(json_decode($product->images)) > 0)
                            <img id="main-product-image" src="{{ asset('storage/' . json_decode($product->images)[0]) }}"
                                 alt="{{ $product->title }}" class="product-image-main">
                        @else
                            <div class="product-image-main bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Image Thumbnails -->
                    @if($product->images && count(json_decode($product->images)) > 1)
                    <div class="row">
                        @foreach(json_decode($product->images) as $index => $image)
                        <div class="col-3 mb-3">
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="{{ $product->title }} - Image {{ $index + 1 }}"
                                 class="product-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <div class="row g-2">
                            @if(Auth::check() && Auth::user()->role == 'farmer')
                            <div class="col-md-6">
                                <form action="{{ route('marketplace.cart.add', $product->id) }}" method="POST" id="add-to-cart-form">
                                    @csrf
                                    <div class="d-flex gap-2 mb-3">
                                        <div class="quantity-control">
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                                                <input type="number" class="form-control text-center"
                                                       id="quantity" name="quantity"
                                                       value="{{ max($product->min_order, 1) }}"
                                                       min="{{ $product->min_order }}"
                                                       max="{{ $product->max_order ?: 1000 }}"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg flex-grow-1 add-to-cart-btn">
                                            <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('marketplace.wishlist.add', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-lg w-100">
                                        <i class="fas fa-heart mr-2"></i>Add to Wishlist
                                    </button>
                                </form>
                            </div>
                            @elseif(Auth::check() && Auth::user()->role == 'agent')
                            <div class="col-12">
                                <button class="btn btn-primary btn-lg w-100" data-toggle="modal" data-target="#agentOrderModal">
                                    <i class="fas fa-user-tie mr-2"></i>Order for Farmer
                                </button>
                            </div>
                            @else
                            <div class="col-12">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Purchase
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Supplier Information -->
                @if($product->supplier)
                <div class="supplier-card">
                    <h5 class="font-weight-bold mb-3">
                        <i class="fas fa-store-alt mr-2"></i>Seller Information
                    </h5>
                    <div class="d-flex align-items-start mb-3">
                        <div class="mr-3">
                            @if($product->supplier->avatar)
                                <img src="{{ asset('storage/' . $product->supplier->avatar) }}"
                                     alt="{{ $product->supplier->name }}"
                                     class="rounded-circle" width="60" height="60">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold mb-1">{{ $product->supplier->name }}</h6>
                            <div class="mb-2">
                                @if($product->supplier->is_verified)
                                <span class="verified-badge">
                                    <i class="fas fa-check-circle"></i> Verified Supplier
                                </span>
                                @endif
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $product->supplier->address ?? 'Location not specified' }}
                            </p>
                            @if($product->supplier->phone)
                            <p class="text-muted small mb-0">
                                <i class="fas fa-phone mr-1"></i>{{ $product->supplier->phone }}
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye mr-1"></i> View Supplier Profile
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-comment-dots mr-1"></i> Contact Seller
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Product Details -->
            <div class="col-lg-7">
                <div class="product-info-card">
                    <!-- Product Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="font-weight-bold mb-1">{{ $product->title }}</h2>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                                    @endfor
                                    <small class="text-muted ml-2">({{ $product->reviews_count }} reviews)</small>
                                </div>
                                <span class="view-count">
                                    <i class="fas fa-eye mr-1"></i>{{ $product->views }} views
                                </span>
                            </div>
                        </div>
                        <div>
                            @if($product->is_verified)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Price & Stock -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="product-price mb-2">KES {{ number_format($product->price, 2) }}</div>
                            <div class="text-muted mb-2">per {{ $product->unit }}</div>
                        </div>
                        <div class="col-md-6">
                            @if($product->quantity > 0)
                            <span class="stock-badge badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i> In Stock
                            </span>
                            <p class="mb-1 text-success">Available: {{ $product->quantity }} {{ $product->unit }}</p>
                            @else
                            <span class="stock-badge badge badge-danger">
                                <i class="fas fa-times-circle mr-1"></i> Out of Stock
                            </span>
                            <p class="mb-1 text-danger">Currently unavailable</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Specifications -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="specification-item">
                                <strong><i class="fas fa-tag mr-2"></i>Category:</strong>
                                <span class="float-right">{{ $product->category }}</span>
                            </div>
                            <div class="specification-item">
                                <strong><i class="fas fa-box mr-2"></i>Product Type:</strong>
                                <span class="float-right">{{ $product->product_type }}</span>
                            </div>
                            <div class="specification-item">
                                <strong><i class="fas fa-balance-scale mr-2"></i>Unit:</strong>
                                <span class="float-right">{{ $product->unit }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="specification-item">
                                <strong><i class="fas fa-map-marker-alt mr-2"></i>Location:</strong>
                                <span class="float-right">{{ $product->location }}</span>
                            </div>
                            <div class="specification-item">
                                <strong><i class="fas fa-sort-amount-up mr-2"></i>Minimum Order:</strong>
                                <span class="float-right">{{ $product->min_order }} {{ $product->unit }}</span>
                            </div>
                            @if($product->max_order)
                            <div class="specification-item">
                                <strong><i class="fas fa-sort-amount-down mr-2"></i>Maximum Order:</strong>
                                <span class="float-right">{{ $product->max_order }} {{ $product->unit }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="delivery-info-card">
                        <h6 class="font-weight-bold mb-2">
                            <i class="fas fa-shipping-fast mr-2"></i>Delivery Information
                        </h6>
                        <div class="row small">
                            <div class="col-md-6">
                                <p class="mb-1"><i class="fas fa-clock text-primary mr-2"></i>Estimated Delivery: Within 24Hrs</p>
                                <p class="mb-1"><i class="fas fa-truck text-primary mr-2"></i>Shipping: KES 500 Nationwide</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><i class="fas fa-sync-alt text-primary mr-2"></i>Returns: 7 days return policy</p>
                                <p class="mb-0"><i class="fas fa-shield-alt text-primary mr-2"></i>Warranty: 1 month warranty</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Tabs -->
                <div class="product-info-card">
                    <ul class="nav nav-tabs product-tabs" id="productTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab">
                                <i class="fas fa-file-alt mr-2"></i>Description
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab">
                                <i class="fas fa-list-ul mr-2"></i>Specifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab">
                                <i class="fas fa-star mr-2"></i>Reviews ({{ $product->reviews_count }})
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="productTabContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="product-description">
                                {!! nl2br(e($product->description)) !!}
                            </div>

                            <!-- Key Features -->
                            <div class="mt-4">
                                <h5 class="font-weight-bold mb-3">
                                    <i class="fas fa-check-circle text-success mr-2"></i>Key Features
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>High quality product</li>
                                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Fresh from farm</li>
                                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Organic certified</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Proper packaging</li>
                                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Fast delivery</li>
                                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Customer support</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane fade" id="specifications" role="tabpanel">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Product Name</strong></td>
                                        <td>{{ $product->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category</strong></td>
                                        <td>{{ $product->category }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Product Type</strong></td>
                                        <td>{{ $product->product_type }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Price per Unit</strong></td>
                                        <td>KES {{ number_format($product->price, 2) }} / {{ $product->unit }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Available Quantity</strong></td>
                                        <td>{{ $product->quantity }} {{ $product->unit }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Minimum Order</strong></td>
                                        <td>{{ $product->min_order }} {{ $product->unit }}</td>
                                    </tr>
                                    @if($product->max_order)
                                    <tr>
                                        <td><strong>Maximum Order</strong></td>
                                        <td>{{ $product->max_order }} {{ $product->unit }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Location</strong></td>
                                        <td>{{ $product->location }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Seller</strong></td>
                                        <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @if($product->reviews_count > 0)
                            <div class="review-summary mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mr-4">
                                        <div class="display-4 font-weight-bold">{{ $product->rating }}</div>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= round($product->rating) ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small text-muted">Based on {{ $product->reviews_count }} reviews</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Reviews (Replace with actual reviews from database) -->
                            <div class="review-list">
                                @for($i = 1; $i <= min(3, $product->reviews_count); $i++)
                                <div class="review-item mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <strong>Customer {{ $i }}</strong>
                                            <div class="rating-stars small">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted">2 weeks ago</small>
                                    </div>
                                    <p class="mb-2">Excellent product quality and timely delivery. Highly recommended!</p>
                                </div>
                                @endfor
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted mb-3">No Reviews Yet</h5>
                                <p class="text-muted">Be the first to review this product!</p>
                            </div>
                            @endif

                            <!-- Add Review Form -->
                            @if(Auth::check() && Auth::user()->role == 'farmer')
                            <div class="mt-4 pt-4 border-top">
                                <h5 class="font-weight-bold mb-3">Write a Review</h5>
                                <form action="{{ route('marketplace.review.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Rating</label>
                                        <div class="rating-stars" id="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star-o fa-lg mr-1"
                                                   style="cursor: pointer;"
                                                   data-rating="{{ $i }}"></i>
                                            @endfor
                                            <input type="hidden" name="rating" id="rating-value" value="5">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Your Review</label>
                                        <textarea class="form-control" name="review" rows="3"
                                                  placeholder="Share your experience with this product..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-2"></i>Submit Review
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="product-info-card">
                    <h4 class="font-weight-bold mb-4">
                        <i class="fas fa-random mr-2"></i>Related Products
                    </h4>
                    <div class="row">
                        @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="related-product-card h-100">
                                <a href="{{ route('marketplace.show', $relatedProduct->id) }}" class="text-decoration-none text-dark">
                                    <div class="position-relative">
                                        @if($relatedProduct->images && count(json_decode($relatedProduct->images)) > 0)
                                            <img src="{{ asset('storage/' . json_decode($relatedProduct->images)[0]) }}"
                                                 alt="{{ $relatedProduct->title }}"
                                                 style="width: 100%; height: 200px; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 200px; background: #f8f9fa;
                                                       display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        @if($relatedProduct->is_verified)
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-success">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <h6 class="font-weight-bold mb-1 text-truncate">{{ $relatedProduct->title }}</h6>
                                        <div class="rating-stars small mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $relatedProduct->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-success font-weight-bold">
                                                KES {{ number_format($relatedProduct->price, 2) }}
                                            </span>
                                            <small class="text-muted">/{{ $relatedProduct->unit }}</small>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $relatedProduct->location }}
                                        </small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Agent Order Modal -->
@if(Auth::check() && Auth::user()->role == 'agent')
<div class="modal fade" id="agentOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-tie mr-2"></i>Order for Farmer
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('marketplace.order.create', $product->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Select Farmer</label>
                        <select class="form-control select2" name="farmer_id" required>
                            <option value="">Select a farmer...</option>
                            <!-- Farmers will be loaded via AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Quantity</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="quantity"
                                   value="{{ max($product->min_order, 1) }}"
                                   min="{{ $product->min_order }}"
                                   max="{{ $product->max_order ?: 1000 }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text">{{ $product->unit }}</span>
                            </div>
                        </div>
                        <small class="text-muted">
                            Min: {{ $product->min_order }} {{ $product->unit }} |
                            @if($product->max_order)
                            Max: {{ $product->max_order }} {{ $product->unit }}
                            @else
                            Max: No limit
                            @endif
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Order Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3"
                                  placeholder="Add any special instructions for this order..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        You will earn a 5% commission on this order. The farmer will be responsible for payment.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check mr-2"></i>Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image gallery functionality
        function changeMainImage(src, element) {
            document.getElementById('main-product-image').src = src;
            document.querySelectorAll('.product-thumbnail').forEach(img => {
                img.classList.remove('active');
            });
            element.classList.add('active');
        }

        // Quantity control
        const quantityInput = document.getElementById('quantity');

        window.incrementQuantity = function() {
            const max = parseInt(quantityInput.max) || 1000;
            const current = parseInt(quantityInput.value) || 1;
            if (current < max) {
                quantityInput.value = current + 1;
            }
        }

        window.decrementQuantity = function() {
            const min = parseInt(quantityInput.min) || 1;
            const current = parseInt(quantityInput.value) || 1;
            if (current > min) {
                quantityInput.value = current - 1;
            }
        }

        // Review rating stars
        const reviewStars = document.querySelectorAll('#review-rating .fa-star-o');
        const ratingValue = document.getElementById('rating-value');

        reviewStars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const rating = this.getAttribute('data-rating');
                updateStars(rating);
            });

            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingValue.value = rating;
            });
        });

        function updateStars(rating) {
            reviewStars.forEach(star => {
                const starRating = star.getAttribute('data-rating');
                if (starRating <= rating) {
                    star.classList.remove('fa-star-o');
                    star.classList.add('fa-star');
                } else {
                    star.classList.remove('fa-star');
                    star.classList.add('fa-star-o');
                }
            });
        }

        // Initialize Select2 for farmer selection in agent modal
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({
                ajax: {
                    url: '{{ route("agent.farmers.list") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' (' + item.email + ')'
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Search for a farmer...'
            });
        }

        // Form validation
        const addToCartForm = document.getElementById('add-to-cart-form');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                const quantity = parseInt(quantityInput.value);
                const minOrder = parseInt(quantityInput.min);
                const maxOrder = parseInt(quantityInput.max);

                if (quantity < minOrder) {
                    e.preventDefault();
                    showAlert(`Minimum order is ${minOrder} ${'{{ $product->unit }}'}`, 'warning');
                    return;
                }

                if (maxOrder && quantity > maxOrder) {
                    e.preventDefault();
                    showAlert(`Maximum order is ${maxOrder} ${'{{ $product->unit }}'}`, 'warning');
                    return;
                }
            });
        }
    });

    function showAlert(message, type = 'info') {
        // Create alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'danger' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;

        // Add to body
        document.body.appendChild(alertDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
</script>
@endsection
