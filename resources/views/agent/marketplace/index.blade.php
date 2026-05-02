@extends('layouts.app')

@section('title', 'Agent Marketplace')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store text-primary mr-2"></i>Agent Marketplace
        </h1>
        <div class="d-flex">
            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#addProductModal">
                <i class="fas fa-plus mr-1"></i>Add Product
            </button>
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Stats -->
@php
    // Ensure all variables are properly initialized
    $products = $products ?? collect();
    $myProducts = $myProducts ?? collect();
    $farmers = $farmers ?? collect();
    $allFarmers = $allFarmers ?? collect();
    $totalProducts = $totalProducts ?? 0;
    $totalCommission = $totalCommission ?? 0;
    $pendingCommission = $pendingCommission ?? 0;
    $totalOrders = $totalOrders ?? 0;
@endphp
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Commission
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            KES {{ number_format($totalCommission) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Orders Assisted
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            My Products
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Available Products
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Tabs for Switching Views -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="marketplaceTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="browse-tab" data-toggle="tab" href="#browse" role="tab">
                        <i class="fas fa-shopping-cart mr-1"></i>Browse Marketplace
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link" id="my-products-tab" data-toggle="tab" href="#my-products" role="tab">
        <i class="fas fa-box mr-1"></i>My Products
        <span class="badge badge-light ml-1">{{ $myProducts->count() }}</span>
    </a>
</li>
                <li class="nav-item">
                    <a class="nav-link" id="farmers-tab" data-toggle="tab" href="#farmers" role="tab">
                        <i class="fas fa-users mr-1"></i>Farmers
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="marketplaceContent">
        <!-- Browse Marketplace Tab -->
        <div class="tab-pane fade show active" id="browse" role="tabpanel">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Category</label>
                                    <select class="form-control" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        <option value="poultry">Poultry</option>
                                        <option value="livestock">Livestock</option>
                                        <option value="equipment">Equipment</option>
                                        <option value="feed">Feed</option>
                                        <option value="medicine">Medicine</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Product Type</label>
                                    <select class="form-control" id="typeFilter">
                                        <option value="">All Types</option>
                                        <option value="chicks">Chicks</option>
                                        <option value="feed">Feed</option>
                                        <option value="medicine">Medicine</option>
                                        <option value="equipment">Equipment</option>
                                        <option value="eggs">Eggs</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Location</label>
                                    <input type="text" class="form-control" id="locationFilter" placeholder="Enter location">
                                </div>
                                <div class="col-md-3">
                                    <label>Price Range</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="minPrice" placeholder="Min" min="0">
                                        <input type="number" class="form-control" id="maxPrice" placeholder="Max" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button class="btn btn-primary mr-2" id="applyFilters">
                                        <i class="fas fa-filter mr-1"></i>Apply Filters
                                    </button>
                                    <button class="btn btn-secondary" id="resetFilters">
                                        <i class="fas fa-redo mr-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="row" id="productsGrid">
                    @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-card shadow h-100">
                                <div class="position-relative">
                                    <img src="{{ $product->image_url }}"
                                         class="card-img-top" alt="{{ $product->title }}"
                                         style="height: 200px; object-fit: cover;">
                                    <span class="position-absolute top-0 start-0 bg-primary text-white px-2 py-1 m-2 rounded small">
                                        <i class="fas fa-check-circle mr-1"></i>Verified
                                    </span>
                                    <span class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded small">
                                        <i class="fas fa-star mr-1"></i>{{ number_format($product->rating, 1) }}
                                    </span>
                                    @if($product->is_low_stock)
                                        <span class="position-absolute bottom-0 start-0 bg-warning text-white px-2 py-1 m-2 rounded small">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Low Stock
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title font-weight-bold">{{ Str::limit($product->title, 50) }}</h6>
                                    <p class="card-text small text-muted mb-2">
                                        <i class="fas fa-user-tie mr-1"></i>{{ $product->supplier->name ?? 'Supplier' }}
                                    </p>
                                    <div class="mb-2">
                                        <span class="badge badge-info">{{ ucfirst($product->product_type) }}</span>
                                        <span class="badge badge-secondary">{{ ucfirst($product->category) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="font-weight-bold text-success">KES {{ number_format($product->price) }}</span>
                                            <small class="d-block text-muted">per {{ $product->unit }}</small>
                                        </div>
                                        <div class="text-warning small">
                                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $product->location }}
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <i class="fas fa-box mr-1"></i>{{ $product->quantity }} {{ $product->unit }} available
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('agent.marketplace.show', $product->id) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <button type="button" class="btn btn-primary btn-sm order-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-toggle="modal" data-target="#createOrderModal">
                                            <i class="fas fa-cart-plus mr-1"></i>Order for Farmer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-store fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No products available</h4>
                    <p class="text-muted">Check back later for new products.</p>
                </div>
            @endif
        </div>

<!-- My Products Tab -->
<div class="tab-pane fade" id="my-products" role="tabpanel">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-box mr-1"></i>My Listed Products
                        <span class="badge badge-light ml-1">{{ $myProducts->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($myProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="myProductsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Orders</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myProducts as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->thumbnail_url }}"
                                                     class="img-thumbnail mr-2"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <strong>{{ $product->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $product->location }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($product->category) }}</span>
                                            <br>
                                            <small>{{ ucfirst($product->product_type) }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-success">KES {{ number_format($product->price) }}</strong>
                                            <br>
                                            <small>per {{ $product->unit }}</small>
                                        </td>
                                        <td>
                                            {{ $product->quantity }} {{ $product->unit }}
                                            <br>
                                            <small class="text-{{ $product->stock_status_badge }}">
                                                {{ $product->stock_status }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($product->is_verified)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                            <br>
                                            @if($product->is_available)
                                                <span class="badge badge-primary">Available</span>
                                            @else
                                                <span class="badge badge-secondary">Unavailable</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->views }}</td>
                                        <td>{{ $product->orders_count }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('agent.marketplace.edit', $product->id) }}"
                                                class="btn btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('agent.marketplace.show', $product->id) }}"
                                                class="btn btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-warning update-stock-btn"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $product->title }}"
                                                        title="Update Stock">
                                                    <i class="fas fa-boxes"></i>
                                                </button>
                                                <form action="{{ route('agent.marketplace.delete', $product->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                                            title="Delete">
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
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Products Listed</h4>
                            <p class="text-muted">Start by adding your first product to the marketplace.</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
                                <i class="fas fa-plus mr-1"></i>Add Your First Product
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Farmers Tab -->
        <div class="tab-pane fade" id="farmers" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-users mr-1"></i>Farmers Marketplace
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($farmers->count() > 0)
                                <div class="row">
                                    @foreach($farmers as $farmer)
                                    <div class="col-md-4 mb-4">
                                        <div class="card farmer-card shadow h-100">
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <div class="avatar-circle mb-2" style="width: 80px; height: 80px; margin: 0 auto;">
                                                        <span class="avatar-text">
                                                            {{ substr($farmer->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                    <h5 class="card-title mb-1">{{ $farmer->name }}</h5>
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $farmer->location ?? 'Location not specified' }}
                                                    </p>
                                                </div>

                                                <div class="farmer-info mb-3">
                                                    <div class="row text-center">
                                                        <div class="col-6">
                                                            <div class="text-primary font-weight-bold">{{ $farmer->total_products ?? 0 }}</div>
                                                            <div class="text-muted small">Products</div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-primary font-weight-bold">{{ $farmer->rating ?? 'N/A' }}</div>
                                                            <div class="text-muted small">Rating</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="farmer-products mb-3">
                                                    <h6 class="small font-weight-bold text-muted mb-2">Recent Products</h6>
                                                    @if($farmer->recentProducts && $farmer->recentProducts->count() > 0)
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach($farmer->recentProducts->take(2) as $product)
                                                            <li class="mb-1">
                                                                <small>
                                                                    <i class="fas fa-chevron-right text-success mr-1"></i>
                                                                    {{ $product->title }}
                                                                    <span class="float-right text-success">KES {{ number_format($product->price) }}</span>
                                                                </small>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="small text-muted mb-0">No products listed yet</p>
                                                    @endif
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <a href="{{ route('agent.farmers.show', $farmer->id) }}"
                                                    class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-eye mr-1"></i>View Profile
                                                    </a>
                                                    <a href="{{ route('agent.farmers.marketplace', $farmer->id) }}"
                                                    class="btn btn-success btn-sm">
                                                        <i class="fas fa-store mr-1"></i>Browse Products
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Pagination for Farmers -->
                                <div class="d-flex justify-content-center">
                                    {{ $farmers->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Farmers Found</h4>
                                    <p class="text-muted">There are no farmers currently registered on the platform.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus mr-1"></i>Add New Product
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('agent.marketplace.store') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Product Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_type">Product Type *</label>
                                <select class="form-control" id="product_type" name="product_type" required>
                                    <option value="">Select Type</option>
                                    <option value="chicks">Chicks</option>
                                    <option value="feed">Feed</option>
                                    <option value="medicine">Medicine</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="eggs">Eggs</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category *</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="poultry">Chicks</option>
                                    <option value="poultry">Layers</option>
                                    <option value="poultry">Broilers</option>
                                    <option value="poultry">Kienyeji</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="feed">Feed</option>
                                    <option value="medicine">Medicine</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location *</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">Price (KES) *</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">Quantity *</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit">Unit *</label>
                                <select class="form-control" id="unit" name="unit" required>
                                    <option value="">Select Unit</option>
                                    <option value="piece">Piece</option>
                                    <option value="dozen">Dozen</option>
                                    <option value="kg">Kilogram</option>
                                    <option value="litre">Litre</option>
                                    <option value="bag">Bag</option>
                                    <option value="pack">Pack</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_order">Minimum Order</label>
                                <input type="number" class="form-control" id="min_order" name="min_order" min="1" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_order">Maximum Order</label>
                                <input type="number" class="form-control" id="max_order" name="max_order" min="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="tags">Tags (comma separated)</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="e.g., poultry, chicks, broiler">
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <small class="form-text text-muted">Recommended size: 800x600px. Max size: 2MB</small>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle mr-1"></i>
                            Your product will be reviewed and verified before appearing in the marketplace.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Order Modal -->
<div class="modal fade" id="createOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Order for Farmer</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('agent.marketplace.order.create') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" id="order_product_id" name="product_id">
                <div class="modal-body">
                    <h6 id="product_title"></h6>
                    <p class="text-muted" id="product_price"></p>

                    <div class="form-group">
                        <label for="farmer_id">Select Farmer</label>
                        <select class="form-control select2" id="farmer_id" name="farmer_id" required>
                            <option value="">-- Select Farmer --</option>
                            @foreach($allFarmers as $farmer)
                                <option value="{{ $farmer->id }}">
                                    {{ $farmer->name }} - {{ $farmer->phone ?? $farmer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="order_quantity" name="quantity" required>
                        <small class="form-text text-muted">
                            Min order: <span id="min_order">1</span>,
                            Max: <span id="max_order">No limit</span>,
                            Available: <span id="available_quantity">0</span>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle mr-1"></i>
                            You will earn 5% commission on this order.
                            Commission: KES <span id="commission_amount">0.00</span>
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

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stock</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('agent.marketplace.update-stock') }}" method="POST">
                @csrf
                <input type="hidden" id="stock_product_id" name="product_id">
                <div class="modal-body">
                    <h6 id="stock_product_name"></h6>
                    <p class="text-muted mb-3">Current Stock: <span id="current_stock" class="font-weight-bold">0</span> units</p>

                    <div class="form-group">
                        <label for="action">Action</label>
                        <select class="form-control" id="action" name="action" required>
                            <option value="">Select Action</option>
                            <option value="add">Add Stock</option>
                            <option value="remove">Remove Stock</option>
                            <option value="set">Set Stock</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="stock_quantity" name="quantity" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason (Optional)</label>
                        <textarea class="form-control" id="reason" name="reason" rows="2"
                                placeholder="e.g., New stock arrived, Sold out, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.product-card, .farmer-card {
    transition: transform 0.3s;
}
.product-card:hover, .farmer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.avatar-circle {
    width: 80px;
    height: 80px;
    background-color: #1cc88a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
}
.nav-tabs .nav-link.active {
    background-color: #f8f9fc;
    border-bottom-color: #f8f9fc;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Search for a farmer...',
        allowClear: true
    });

    // File input
    bsCustomFileInput.init();

    // Tab functionality
    $('#marketplaceTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');

        // Store active tab in localStorage
        localStorage.setItem('activeMarketplaceTab', $(this).attr('href'));
    });

    // Restore active tab
    const activeTab = localStorage.getItem('activeMarketplaceTab');
    if (activeTab) {
        $('#marketplaceTabs a[href="' + activeTab + '"]').tab('show');
    }

    // Order button click
    $('.order-btn').click(function() {
        const productId = $(this).data('product-id');

        // Get product details via AJAX
        $.ajax({
            url: '/agent/marketplace/product/' + productId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const product = response.data;

                    $('#order_product_id').val(product.id);
                    $('#product_title').text(product.title);
                    $('#product_price').text('Price: KES ' + product.price + ' per ' + product.unit);
                    $('#min_order').text(product.min_order || 1);
                    $('#max_order').text(product.max_order || 'No limit');
                    $('#available_quantity').text(product.quantity);

                    // Set quantity input limits
                    const orderQuantity = $('#order_quantity');
                    orderQuantity.attr('min', product.min_order || 1);
                    if (product.max_order) {
                        orderQuantity.attr('max', Math.min(product.max_order, product.quantity));
                    } else {
                        orderQuantity.attr('max', product.quantity);
                    }
                    orderQuantity.val(product.min_order || 1);

                    // Calculate commission
                    calculateCommission();
                }
            }
        });
    });

    // Calculate commission
    function calculateCommission() {
        const price = parseFloat($('#product_price').text().match(/KES ([\d.]+)/)?.[1] || 0);
        const quantity = parseInt($('#order_quantity').val()) || 0;
        const commission = (price * quantity * 0.05).toFixed(2);
        $('#commission_amount').text(commission);
    }

    // Quantity change
    $('#order_quantity').on('input', calculateCommission);

    // Update stock button
    $('.update-stock-btn').click(function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');

        $('#stock_product_id').val(productId);
        $('#stock_product_name').text(productName);

        // Get current stock via AJAX
        $.ajax({
            url: '/agent/marketplace/product/' + productId + '/stock',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#current_stock').text(response.data.quantity);
                }
            }
        });

        $('#updateStockModal').modal('show');
    });

    // Apply filters
    $('#applyFilters').click(function() {
        const category = $('#categoryFilter').val();
        const type = $('#typeFilter').val();
        const location = $('#locationFilter').val();
        const minPrice = $('#minPrice').val();
        const maxPrice = $('#maxPrice').val();

        let url = '{{ route("agent.marketplace.index") }}?';
        const params = [];

        if (category) params.push('category=' + category);
        if (type) params.push('type=' + type);
        if (location) params.push('location=' + encodeURIComponent(location));
        if (minPrice) params.push('min_price=' + minPrice);
        if (maxPrice) params.push('max_price=' + maxPrice);

        if (params.length > 0) {
            url += params.join('&');
            window.location.href = url;
        }
    });

    // Reset filters
    $('#resetFilters').click(function() {
        window.location.href = '{{ route("agent.marketplace.index") }}';
    });

    // Search functionality
    $('#searchBtn').click(function() {
        const searchTerm = $('#searchInput').val().trim();
        if (searchTerm) {
            window.location.href = '{{ route("agent.marketplace.index") }}?search=' + encodeURIComponent(searchTerm);
        }
    });

    // Enter key in search
    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            $('#searchBtn').click();
        }
    });

    // Image preview
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').remove();
                $('#image').parent().after(
                    '<img id="imagePreview" src="' + e.target.result + '" class="img-thumbnail mt-2" style="max-width: 200px;">'
                );
            }
            reader.readAsDataURL(file);
        }
    });

    // Auto-calculate max order based on quantity
    $('#quantity').on('input', function() {
        const quantity = parseInt($(this).val()) || 0;
        $('#max_order').attr('max', quantity);
    });

    // Form validation
    $('#addProductForm').validate({
        rules: {
            title: 'required',
            product_type: 'required',
            category: 'required',
            location: 'required',
            price: {
                required: true,
                min: 0
            },
            quantity: {
                required: true,
                min: 0
            },
            unit: 'required',
            description: 'required'
        },
        messages: {
            title: 'Please enter product title',
            product_type: 'Please select product type',
            category: 'Please select category',
            location: 'Please enter location',
            price: 'Please enter valid price',
            quantity: 'Please enter valid quantity',
            unit: 'Please select unit',
            description: 'Please enter description'
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Auto-suggest tags
    $('#tags').on('keyup', function() {
        const val = $(this).val();
        if (val.length > 2) {
            // You can implement AJAX tag suggestions here
        }
    });

    // Load product details for order modal
    @if(isset($product))
    $(document).ready(function() {
        $('#createOrderModal').modal('show');
    });
    @endif
});
</script>
@endpush
