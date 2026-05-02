@extends('layouts.app')

@section('title', 'Expired Products')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-times text-danger mr-2"></i>Expired Products
        </h1>
        <a href="{{ route('supplier.inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Inventory
        </a>
    </div>

    <!-- Expired Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $expiredCount }} Expired Product(s)
            </h6>
            <div>
                <button class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                    <i class="fas fa-trash mr-1"></i> Delete Selected
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
            <form id="bulkDeleteForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="expiredTable" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Expiry Date</th>
                                <th>Days Expired</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox" name="products[]" value="{{ $product->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" class="rounded mr-2" width="40" height="40" alt="{{ $product->name }}">
                                        @else
                                            <div class="rounded bg-light mr-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <div class="small text-muted">{{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge badge-info">{{ $product->category->name }}</span>
                                    @else
                                        <span class="badge badge-secondary">Uncategorized</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-danger">
                                        {{ $product->expiry_date ? $product->expiry_date->format('M d, Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->expiry_date)
                                        <span class="badge badge-danger">
                                            {{ $product->expiry_date->diffInDays(now()) }} days
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $product->stock_quantity }} {{ $product->unit }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('supplier.inventory.edit', $product) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger delete-product" data-id="{{ $product->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </form>
            @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4 class="text-success">Great! No Expired Products</h4>
                <p class="text-muted">All your products are within their expiry dates.</p>
                <a href="{{ route('supplier.inventory.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-warehouse mr-1"></i> View All Products
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').change(function() {
        $('.product-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Bulk delete
    $('#bulkDeleteBtn').click(function() {
        const selected = $('.product-checkbox:checked');
        if (selected.length === 0) {
            toastr.warning('Please select at least one product to delete.');
            return;
        }

        if (!confirm(`Are you sure you want to delete ${selected.length} expired product(s)? This action cannot be undone.`)) {
            return;
        }

        const productIds = selected.map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: '{{ route("supplier.inventory.bulk-delete") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_ids: productIds
            },
            success: function(response) {
                toastr.success(response.message);
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                toastr.error('An error occurred while deleting products.');
            }
        });
    });

    // Individual delete
    $('.delete-product').click(function() {
        const productId = $(this).data('id');

        if (!confirm('Are you sure you want to delete this expired product? This action cannot be undone.')) {
            return;
        }

        $.ajax({
            url: '{{ route("supplier.inventory.destroy", ":id") }}'.replace(':id', productId),
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                toastr.error('An error occurred while deleting the product.');
            }
        });
    });
});
</script>
@endpush
@endsection
