@extends('layouts.app')

@section('title', $product['name'])

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-kiwi-bird text-success mr-2"></i>{{ $product['name'] }}
        </h1>
        <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i>Back to Marketplace
        </a>
    </div>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <div id="productCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://via.placeholder.com/600x400/1cc88a/ffffff?text=Product+Image+1"
                                     class="d-block w-100 rounded" alt="Product Image">
                            </div>
                            <div class="carousel-item">
                                <img src="https://via.placeholder.com/600x400/36b9cc/ffffff?text=Product+Image+2"
                                     class="d-block w-100 rounded" alt="Product Image">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title font-weight-bold">{{ $product['name'] }}</h3>
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <span class="badge badge-success">{{ $product['rating'] }}</span>
                        <span class="text-muted ml-2">({{ $product['reviews'] }} reviews)</span>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-success font-weight-bold">KES {{ number_format($product['price']) }}</h2>
                        <p class="text-muted">Minimum order: {{ $product['minimum_order'] }} units</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Product Details</h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2">
                                    <i class="fas fa-tractor text-success mr-2"></i>
                                    <strong>Farm:</strong> {{ $product['farm'] }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-success mr-2"></i>
                                    <strong>Location:</strong> {{ $product['location'] }}
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="mb-2">
                                    <i class="fas fa-clock text-success mr-2"></i>
                                    <strong>Age:</strong> {{ $product['age'] }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-box text-success mr-2"></i>
                                    <strong>Stock:</strong> {{ $product['stock'] }} available
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Description</h5>
                        <p>{{ $product['description'] }}</p>
                    </div>

                    <!-- Order Form -->
                    <div class="card border-left-success">
                        <div class="card-body">
                            <h6 class="font-weight-bold">Place Order</h6>
                            <form id="orderForm">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                        </div>
                                        <input type="number" class="form-control text-center" id="quantity"
                                               value="{{ $product['minimum_order'] }}" min="{{ $product['minimum_order'] }}"
                                               max="{{ $product['stock'] }}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Delivery Time: <span class="text-success">{{ $product['delivery_time'] }}</span></label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="font-weight-bold">Total:</span>
                                    <span class="h4 text-success" id="totalPrice">KES {{ number_format($product['price'] * $product['minimum_order']) }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-success btn-block" onclick="addToCart()">
                                            <i class="fas fa-cart-plus mr-1"></i>Add to Cart
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-success btn-block" onclick="buyNow()">
                                            <i class="fas fa-bolt mr-1"></i>Buy Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const pricePerUnit = {{ $product['price'] }};
    const minOrder = {{ $product['minimum_order'] }};
    const maxStock = {{ $product['stock'] }};

    function updateTotalPrice() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const total = pricePerUnit * quantity;
        document.getElementById('totalPrice').textContent = 'KES ' + total.toLocaleString();
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity');
        let quantity = parseInt(input.value);
        if (quantity < maxStock) {
            input.value = quantity + 1;
            updateTotalPrice();
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        let quantity = parseInt(input.value);
        if (quantity > minOrder) {
            input.value = quantity - 1;
            updateTotalPrice();
        }
    }

    function addToCart() {
        const quantity = document.getElementById('quantity').value;
        // AJAX call to add to cart
        toastr.success('Added to cart!', 'Success');
    }

    function buyNow() {
        const quantity = document.getElementById('quantity').value;
        // Redirect to checkout
        window.location.href = "{{ route('farmer.checkout') }}?product={{ $product['id'] }}&quantity=" + quantity;
    }

    document.getElementById('quantity').addEventListener('input', updateTotalPrice);
    document.addEventListener('DOMContentLoaded', updateTotalPrice);
</script>
@endpush
@endsection
