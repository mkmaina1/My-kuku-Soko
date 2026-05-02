@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store text-success mr-2"></i>Marketplace
        </h1>
        <div class="d-flex">
            @if(auth()->user()->role == 'farmer')
                <a href="{{ route('cart.index') }}" class="btn btn-warning mr-2">
                    <i class="fas fa-shopping-cart"></i> Cart
                    @if(auth()->check() && $cartCount > 0)
                        <span class="badge badge-danger">{{ $cartCount }}</span>
                    @endif
                </a>
            @endif
            @if(auth()->user()->role == 'supplier')
                <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i> Add Product
                </a>
            @endif
        </div>
    </div>


    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('marketplace.index') }}" method="GET" class="row">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Category:</label>
                    <select name="category" class="form-control form-control-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Product Type:</label>
                    <select name="product_type" class="form-control form-control-sm">
                        <option value="">All Types</option>
                        @foreach($productTypes as $type)
                            <option value="{{ $type }}" {{ request('product_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold">Min Price:</label>
                    <input type="number" name="min_price" class="form-control form-control-sm"
                           value="{{ request('min_price') }}" placeholder="Min">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold">Max Price:</label>
                    <input type="number" name="max_price" class="form-control form-control-sm"
                           value="{{ request('max_price') }}" placeholder="Max">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold">Sort By:</label>
                    <select name="sort" class="form-control form-control-sm">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold">Search:</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           value="{{ request('search') }}" placeholder="Search products...">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold">Location:</label>
                    <select name="location" class="form-control form-control-sm">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success btn-sm btn-block">
                        <i class="fas fa-filter mr-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card shadow h-100">
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200/1cc88a/ffffff?text=Poultry' }}"
                                 class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: cover;">
                            @if($product->is_verified)
                                <span class="position-absolute top-0 start-0 bg-primary text-white px-2 py-1 m-2 rounded small">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                            @endif
                            <span class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded small">
                                <i class="fas fa-star mr-1"></i>{{ number_format($product->rating, 1) }}
                            </span>
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
                                <a href="{{ route('marketplace.show', $product->id) }}"
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                @if(auth()->user()->role == 'farmer' && $product->quantity > 0)
                                    <button type="button" class="btn btn-success btn-sm"
                                            data-toggle="modal" data-target="#addToCartModal{{ $product->id }}">
                                        <i class="fas fa-cart-plus mr-1"></i>Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Modal -->
                @if(auth()->user()->role == 'farmer')
                <div class="modal fade" id="addToCartModal{{ $product->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add to Cart</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('marketplace.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <h6>{{ $product->title }}</h6>
                                    <p class="text-muted">Price: KES {{ number_format($product->price) }} per {{ $product->unit }}</p>
                                    <div class="form-group">
                                        <label for="quantity">Quantity ({{ $product->unit }})</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                               min="{{ $product->min_order }}"
                                               max="{{ min($product->max_order ?? $product->quantity, $product->quantity) }}"
                                               value="{{ $product->min_order }}" required>
                                        <small class="form-text text-muted">
                                            Min order: {{ $product->min_order }}, Max: {{ $product->max_order ?? 'No limit' }}, Available: {{ $product->quantity }}
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes (Optional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Styled Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @elseif ($page >= $products->currentPage() - 2 && $page <= $products->currentPage() + 2)
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @elseif ($page == 1 || $page == $products->lastPage())
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @elseif ($page == $products->currentPage() - 3 || $page == $products->currentPage() + 3)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>

        <!-- Or use this simpler version if you prefer -->
        <!--
        <div class="d-flex justify-content-center mt-4">
            {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
        </div>
        -->
    @else
        <div class="text-center py-5">
            <i class="fas fa-store fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No products found</h4>
            <p class="text-muted">Try adjusting your filters or check back later.</p>
            @if(auth()->user()->role == 'supplier')
                <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i> Add Your First Product
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
.product-card {
    transition: transform 0.3s;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Custom Pagination Styles */
.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

.pagination-sm .page-item:first-child .page-link {
    border-top-left-radius: 0.2rem;
    border-bottom-left-radius: 0.2rem;
}

.pagination-sm .page-item:last-child .page-link {
    border-top-right-radius: 0.2rem;
    border-bottom-right-radius: 0.2rem;
}

.pagination {
    margin-bottom: 0;
}

.page-item.active .page-link {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

.page-link {
    color: #1cc88a;
}

.page-link:hover {
    color: #0f9d58;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Show page info (optional) */
.pagination-info {
    font-size: 0.875rem;
    color: #6c757d;
    margin-right: 1rem;
}
</style>
@endsection

@push('scripts')
<script>
// Optional: Add active class to pagination on click
$(document).ready(function() {
    $('.page-link').on('click', function() {
        $('.page-item').removeClass('active');
        $(this).parent().addClass('active');
    });
});
</script>
@endpush
