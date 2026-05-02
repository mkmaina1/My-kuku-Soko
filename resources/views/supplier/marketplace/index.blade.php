@extends('layouts.app')

@section('title', 'Supplier Marketplace')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store mr-2"></i>My Products
        </h1>
        <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i> Add New Product
        </a>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Products
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                                Available Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->sum('quantity') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
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
                                Total Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->sum('orders_count') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Average Rating
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($products->avg('rating') ?? 0, 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">My Products</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th>Views</th>
                            <th>Orders</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @php
                                    $imageSrc = 'https://via.placeholder.com/50';
                                    if ($product->image) {
                                        // Check if it's a full URL
                                        if (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://')) {
                                            $imageSrc = $product->image;
                                        } else {
                                            // Assume it's a local storage path
                                            $imageSrc = asset('storage/' . $product->image);
                                        }
                                    }
                                @endphp

                                <img src="{{ $imageSrc }}"
                                     width="50" height="50"
                                     style="object-fit: cover;"
                                     alt="{{ $product->title }}"
                                     onerror="this.src='https://via.placeholder.com/50'">
                            </td>
                            <td>
                                <strong>{{ $product->title }}</strong><br>
                                <small class="text-muted">{{ $product->category }} - {{ $product->product_type }}</small>
                            </td>
                            <td class="text-success font-weight-bold">
                                KES {{ number_format($product->price) }}<br>
                                <small class="text-muted">per {{ $product->unit }}</small>
                            </td>
                            <td>
                                {{ $product->quantity }} {{ $product->unit }}
                                @if($product->quantity <= 10)
                                    <span class="badge badge-warning">Low Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_available)
                                    <span class="badge badge-success">Available</span>
                                @else
                                    <span class="badge badge-secondary">Unavailable</span>
                                @endif
                                @if($product->is_verified)
                                    <span class="badge badge-primary">Verified</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-warning mr-1">
                                        <i class="fas fa-star"></i> {{ number_format($product->rating, 1) }}
                                    </div>
                                    <small class="text-muted">({{ $product->total_ratings }})</small>
                                </div>
                            </td>
                            <td>{{ $product->views }}</td>
                            <td>{{ $product->orders_count }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('marketplace.show', $product->id) }}"
                                    class="btn btn-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('supplier.marketplace.edit', $product->id) }}"
                                    class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete('{{ $product->id }}', '{{ $product->title }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $product->id }}"
                                        action="{{ route('supplier.marketplace.destroy', $product->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id, title) {
    if (confirm('Are you sure you want to delete "' + title + '"?')) {
        event.preventDefault();
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
