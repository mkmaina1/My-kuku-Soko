@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box text-primary mr-2"></i>{{ $product->title }}
        </h1>
        <div class="d-flex">
            <a href="{{ route('agent.marketplace.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left mr-1"></i>Back to Marketplace
            </a>
            @if($product->supplier_id == auth()->id())
                <a href="{{ route('agent.marketplace.edit', $product->id) }}" class="btn btn-warning mr-2">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            @else
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createOrderModal">
                    <i class="fas fa-cart-plus mr-1"></i>Order for Farmer
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <img src="{{ $product->image_url }}"
                         class="img-fluid rounded mb-3"
                         alt="{{ $product->title }}"
                         style="max-height: 400px; width: 100%; object-fit: contain;">

                    @if($product->supplier_id != auth()->id())
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg"
                                data-toggle="modal" data-target="#createOrderModal">
                            <i class="fas fa-cart-plus mr-2"></i>Order for Farmer
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title font-weight-bold">{{ $product->title }}</h3>

                    <!-- Supplier Info -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle mr-3">
                            <span>{{ substr($product->supplier->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $product->supplier->name }}</h6>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $product->location }}
                            </p>
                        </div>
                        <div class="ml-auto">
                            {!! $product->verification_badge !!}
                        </div>
                    </div>

                    <!-- Price and Stock -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-success font-weight-bold mb-1">
                                    KES {{ number_format($product->price, 2) }}
                                </h4>
                                <p class="text-muted mb-0">per {{ $product->unit }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <h4 class="font-weight-bold mb-1">
                                    {{ $product->quantity }} {{ $product->unit }}
                                </h4>
                                <p class="text-muted mb-0">
                                    <span class="badge badge-{{ $product->stock_status_badge }}">
                                        {{ $product->stock_status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <p class="mb-1"><strong>Product Type:</strong></p>
                            <span class="badge badge-info">
                                <i class="fas {{ $product->product_type_icon }} mr-1"></i>
                                {{ ucfirst($product->product_type) }}
                            </span>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Category:</strong></p>
                            <span class="badge badge-secondary">
                                <i class="fas {{ $product->category_icon }} mr-1"></i>
                                {{ ucfirst($product->category) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Limits -->
                    @if($product->min_order || $product->max_order)
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle mr-2"></i>Order Information
                        </h6>
                        <ul class="mb-0 pl-3">
                            @if($product->min_order)
                            <li>Minimum order: {{ $product->min_order }} {{ $product->unit }}</li>
                            @endif
                            @if($product->max_order)
                            <li>Maximum order: {{ $product->max_order }} {{ $product->unit }}</li>
                            @endif
                            <li>Available for order: {{ $product->available_for_order }} {{ $product->unit }}</li>
                        </ul>
                    </div>
                    @endif

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Description</h5>
                        <p class="text-justify">{{ $product->description }}</p>
                    </div>

                    <!-- Product Statistics -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h5 class="font-weight-bold text-primary mb-1">{{ $product->rating }}</h5>
                                <p class="text-muted small mb-0">Rating</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h5 class="font-weight-bold text-primary mb-1">{{ $product->orders_count }}</h5>
                                <p class="text-muted small mb-0">Orders</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h5 class="font-weight-bold text-primary mb-1">{{ $product->views }}</h5>
                                <p class="text-muted small mb-0">Views</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($product->tags && count($product->tags) > 0)
                    <div class="mb-4">
                        <h6 class="font-weight-bold mb-2">Tags</h6>
                        <div>
                            @foreach($product->tags as $tag)
                            <span class="badge badge-light border mr-1 mb-1">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Products -->
    @if($similarProducts->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-boxes mr-1"></i>Similar Products
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($similarProducts as $similar)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 border">
                                <img src="{{ $similar->thumbnail_url }}"
                                     class="card-img-top"
                                     alt="{{ $similar->title }}"
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($similar->title, 40) }}</h6>
                                    <p class="card-text text-success font-weight-bold">
                                        KES {{ number_format($similar->price) }}
                                    </p>
                                    <a href="{{ route('agent.marketplace.show', $similar->id) }}"
                                       class="btn btn-sm btn-outline-primary btn-block">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Create Order Modal -->
@if($product->supplier_id != auth()->id())
<div class="modal fade" id="createOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order for Farmer</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('agent.marketplace.order.create') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    <h6>{{ $product->title }}</h6>
                    <p class="text-muted">Price: KES {{ number_format($product->price) }} per {{ $product->unit }}</p>

                    <div class="form-group">
                        <label for="farmer_id">Select Farmer</label>
                        <select class="form-control select2" id="farmer_id" name="farmer_id" required>
                            <option value="">-- Select Farmer --</option>
                            @foreach(\App\Models\User::where('role', 'farmer')->get() as $farmer)
                                <option value="{{ $farmer->id }}">
                                    {{ $farmer->name }} - {{ $farmer->phone ?? $farmer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity ({{ $product->unit }})</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            min="{{ $product->min_order }}"
                            max="{{ $product->max_order ? min($product->max_order, $product->quantity) : $product->quantity }}"
                            value="{{ $product->min_order }}" required>
                        <small class="form-text text-muted">
                            Available: {{ $product->quantity }} {{ $product->unit }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"
                                placeholder="Any special instructions..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle mr-1"></i>
                            You will earn 5% commission on this order.
                            <span id="commissionText"></span>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    background-color: #1cc88a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

    // Calculate commission
    function calculateCommission() {
        const price = {{ $product->price }};
        const quantity = parseInt($('#quantity').val()) || {{ $product->min_order }};
        const commission = (price * quantity * 0.05).toFixed(2);
        $('#commissionText').text('Commission: KES ' + commission);
    }

    // Initial calculation
    calculateCommission();

    // Update on quantity change
    $('#quantity').on('input', calculateCommission);
});
</script>
@endpush
