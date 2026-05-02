@extends('layouts.app')

@section('title', 'Inventory Management')

@section('styles')
<style>
    /* Custom Styles */
    .inventory-card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .inventory-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .inventory-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 1rem 1.5rem;
    }

    /* Progress Bar Customization */
    .progress {
        height: 12px;
        border-radius: 6px;
        background-color: #e9ecef;
    }
    .progress-bar {
        border-radius: 6px;
    }

    /* Stock Status Indicators */
    .stock-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .stock-high { background-color: #28a745; }
    .stock-medium { background-color: #ffc107; }
    .stock-low { background-color: #fd7e14; }
    .stock-empty { background-color: #dc3545; }

    /* Product Image Container */
    .product-img-container {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Category Badges */
    .category-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Status Badges */
    .status-available {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-unavailable {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-buttons .btn {
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.8rem;
        margin: 2px;
    }

    /* Stats Card Icons */
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.2;
        position: absolute;
        right: 20px;
        bottom: 20px;
    }

    /* Filter Controls */
    .filter-controls {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 1rem;
        text-align: center;
    }
    .empty-state-icon {
        font-size: 5rem;
        color: #e9ecef;
        margin-bottom: 1rem;
    }

    /* Price Display */
    .price-display {
        font-weight: 700;
        font-size: 1.1rem;
        color: #28a745;
    }
    .unit-display {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Row Hover Effect */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        transform: scale(1.002);
        transition: all 0.2s ease;
    }

    /* Custom Pagination Styles */
    .pagination-custom .page-item {
        margin: 0 2px;
    }

    .pagination-custom .page-link {
        color: #495057;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem 0.9rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .pagination-custom .page-link:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 123, 255, 0.3);
    }

    .pagination-custom .page-item.active .page-link {
        color: #fff;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #764ba2;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(102, 126, 234, 0.4);
    }

    .pagination-custom .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        opacity: 0.6;
    }

    .pagination-custom .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    /* Pagination wrapper */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1.5rem 0;
        background-color: #f8f9fa;
        border-radius: 0 0 10px 10px;
        border-top: 1px solid #e9ecef;
    }

    /* Pagination info */
    .pagination-info {
        font-size: 0.875rem;
        color: #6c757d;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        border: 1px solid #dee2e6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .pagination-info strong {
        color: #495057;
        font-weight: 600;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .action-buttons .btn-group {
            display: flex;
            flex-direction: column;
        }
        .action-buttons .btn {
            margin-bottom: 5px;
        }

        .pagination-custom .page-link {
            padding: 0.375rem 0.65rem;
            font-size: 0.8rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 15px;
        }

        .pagination-info {
            order: 2;
            margin-top: 10px;
        }

        .pagination-custom {
            order: 1;
        }
    }

    @media (max-width: 576px) {
        .pagination-custom {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-custom .page-item {
            margin: 2px;
        }
    }
</style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-0">Manage Your Inventory</h5>
                                <small class="text-muted">Track and manage all your products in one place</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus mr-2"></i> Add New Product
                                </a>
                                <a href="{{ route('supplier.marketplace.index') }}" class="btn btn-info">
                                    <i class="fas fa-store mr-2"></i> Marketplace
                                </a>
                                <a href="{{ route('supplier.inventory.low-stock') }}" class="btn btn-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock
                                </a>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                                    <i class="fas fa-download mr-2"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Products Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card inventory-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title text-muted mb-1">Total Products</h6>
                                <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                                <small class="text-success">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $stats['available'] }} Available
                                </small>
                            </div>
                            <div class="bg-primary text-white rounded-circle p-3">
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('supplier.inventory.index') }}" class="btn btn-sm btn-outline-primary">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Value Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card inventory-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title text-muted mb-1">Stock Value</h6>
                                <h3 class="mb-0">KES {{ number_format($stats['total_value']) }}</h3>
                                <small class="text-info">
                                    <i class="fas fa-tags mr-1"></i>
                                    {{ $stats['categories'] }} Categories
                                </small>
                            </div>
                            <div class="bg-success text-white rounded-circle p-3">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card inventory-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title text-muted mb-1">Low Stock</h6>
                                <h3 class="mb-0 text-warning">{{ $stats['low_stock'] }}</h3>
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Needs attention
                                </small>
                            </div>
                            <div class="bg-warning text-white rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('supplier.inventory.low-stock') }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Stock Card -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card inventory-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title text-muted mb-1">Out of Stock</h6>
                                <h3 class="mb-0 text-danger">{{ $stats['out_of_stock'] }}</h3>
                                <small class="text-danger">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Requires restocking
                                </small>
                            </div>
                            <div class="bg-danger text-white rounded-circle p-3">
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-plus mr-1"></i> Restock Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card filter-controls">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search products..." id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select class="form-control" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $categoryItem)
                                        <option value="{{ $categoryItem->category }}">{{ $categoryItem->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select class="form-control" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                    <option value="low_stock">Low Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary btn-block" id="resetFilters">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="row">
            <div class="col-12">
                <div class="card inventory-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-2"></i>Product Inventory
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $products->total() }} Products</span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover" id="inventoryTable">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $index => $product)
                                    <tr class="{{ $product->quantity <= 10 ? 'table-warning' : ($product->quantity <= 0 ? 'table-danger' : '') }}">
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-img-container mr-3">
                                                    @if($product->image)
                                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->title }}">
                                                    @else
                                                        <i class="fas fa-box text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong class="d-block">{{ $product->title }}</strong>
                                                    <small class="text-muted">ID: #{{ $product->id }}</small>
                                                    <div class="small">
                                                        @if($product->description)
                                                            <span class="text-muted">{{ Str::limit($product->description, 50) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge category-badge bg-info">{{ $product->category }}</span>
                                        </td>
                                        <td>
                                            <div class="price-display">KES {{ number_format($product->price) }}</div>
                                            <div class="unit-display">per {{ $product->unit }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center mb-1">
                                                @if($product->quantity > 20)
                                                    <span class="stock-indicator stock-high"></span>
                                                @elseif($product->quantity > 10)
                                                    <span class="stock-indicator stock-medium"></span>
                                                @elseif($product->quantity > 0)
                                                    <span class="stock-indicator stock-low"></span>
                                                @else
                                                    <span class="stock-indicator stock-empty"></span>
                                                @endif
                                                <strong class="{{ $product->quantity <= 10 ? 'text-warning' : ($product->quantity <= 0 ? 'text-danger' : 'text-success') }}">
                                                    {{ $product->quantity }} units
                                                </strong>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                @php
                                                    $maxStock = max($product->quantity, 100);
                                                    $percentage = ($product->quantity / $maxStock) * 100;
                                                    $color = $product->quantity > 20 ? 'success' : ($product->quantity > 10 ? 'warning' : ($product->quantity > 0 ? 'orange' : 'danger'));
                                                @endphp
                                                <div class="progress-bar bg-{{ $color }}"
                                                     style="width: {{ $percentage }}%"
                                                     title="{{ $product->quantity }} units in stock">
                                                </div>
                                            </div>
                                            <small class="text-muted mt-1 d-block">
                                                Min: {{ $product->min_order }}, Max: {{ $product->max_order ?? '∞' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($product->is_available)
                                                <span class="status-available">
                                                    <i class="fas fa-check-circle mr-1"></i> Available
                                                </span>
                                            @else
                                                <span class="status-unavailable">
                                                    <i class="fas fa-times-circle mr-1"></i> Unavailable
                                                </span>
                                            @endif
                                            @if($product->is_verified)
                                                <div class="mt-1">
                                                    <small class="text-info">
                                                        <i class="fas fa-shield-alt mr-1"></i> Verified
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $product->updated_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $product->updated_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group action-buttons">
                                                <a href="{{ route('marketplace.show', $product->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('supplier.marketplace.edit', $product->id) }}"
                                                   class="btn btn-warning btn-sm"
                                                   title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle"
                                                            type="button"
                                                            data-toggle="dropdown"
                                                            title="More Options">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ route('supplier.marketplace.index') }}">
                                                            <i class="fas fa-store mr-2"></i> Marketplace
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-chart-line mr-2"></i> View Analytics
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-warning" href="#">
                                                            <i class="fas fa-bullhorn mr-2"></i> Promote
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="fas fa-boxes empty-state-icon"></i>
                                                <h4 class="text-muted">No Products Found</h4>
                                                <p class="text-muted mb-4">You haven't added any products to your inventory yet.</p>
                                                <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-success btn-lg">
                                                    <i class="fas fa-plus mr-2"></i> Add Your First Product
                                                </a>
                                                <p class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-lightbulb mr-1"></i>
                                                        Tip: Start by adding products to sell on the marketplace
                                                    </small>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($products->hasPages())
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="pagination-info">
                                    <i class="fas fa-list mr-1"></i>
                                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="Page navigation" class="pagination-wrapper">
                                    <ul class="pagination pagination-custom justify-content-end mb-0">
                                        <!-- Previous Page Link -->
                                        @if ($products->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                    <span class="sr-only">Previous</span>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">
                                                    <i class="fas fa-chevron-left"></i>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Pagination Elements -->
                                        @php
                                            $current = $products->currentPage();
                                            $last = $products->lastPage();
                                            $start = max($current - 2, 1);
                                            $end = min($current + 2, $last);
                                        @endphp

                                        <!-- First Page Link -->
                                        @if ($start > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $products->url(1) }}">1</a>
                                            </li>
                                            @if ($start > 2)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                        @endif

                                        <!-- Page Numbers -->
                                        @for ($i = $start; $i <= $end; $i++)
                                            <li class="page-item {{ ($products->currentPage() == $i) ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        <!-- Last Page Link -->
                                        @if ($end < $last)
                                            @if ($end < $last - 1)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $products->url($last) }}">{{ $last }}</a>
                                            </li>
                                        @endif

                                        <!-- Next Page Link -->
                                        @if ($products->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">
                                                    <i class="fas fa-chevron-right"></i>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-right"></i>
                                                    <span class="sr-only">Next</span>
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-download mr-2"></i> Export Inventory
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="form-group">
                        <label>Export Format</label>
                        <select class="form-control" name="format">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Include Columns</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="product_name" checked>
                            <label class="form-check-label">Product Name</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="stock" checked>
                            <label class="form-check-label">Stock Quantity</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="price" checked>
                            <label class="form-check-label">Price</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#inventoryTable').DataTable({
        "paging": false,
        "searching": false,
        "info": false,
        "responsive": true,
        "order": [[6, 'desc']],
        "columnDefs": [
            {
                "orderable": false,
                "targets": [0, 7]
            },
            {
                "className": "dt-body-center",
                "targets": [7]
            }
        ],
        "language": {
            "emptyTable": "No products found"
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Category filter
    $('#categoryFilter').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        const status = this.value;

        if (status === '') {
            table.columns().search('').draw();
            return;
        }

        // Custom filtering for status
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const quantity = parseInt(data[4].match(/\d+/)[0]); // Extract number from stock column
            const isAvailable = data[5].includes('Available');

            switch(status) {
                case 'available':
                    return isAvailable;
                case 'unavailable':
                    return !isAvailable;
                case 'low_stock':
                    return quantity <= 10 && quantity > 0;
                case 'out_of_stock':
                    return quantity <= 0;
                default:
                    return true;
            }
        });

        table.draw();

        // Remove the custom filter after drawing
        $.fn.dataTable.ext.search.pop();
    });

    // Reset filters
    $('#resetFilters').click(function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('#statusFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Tooltips
    $('[title]').tooltip({
        trigger: 'hover',
        placement: 'top'
    });

    // Row click for mobile
    $('#inventoryTable tbody tr').click(function(e) {
        if ($(window).width() < 768 && !$(e.target).is('button') && !$(e.target).is('a') && !$(e.target).closest('button').length) {
            const productId = $(this).find('td:nth-child(2) small').text().match(/#(\d+)/)[1];
            if (productId) {
                window.location.href = '/marketplace/product/' + productId;
            }
        }
    });

    // Add active class to current page in pagination
    $('.pagination-custom .page-item.active').addClass('current-page');
});
</script>
@endpush
