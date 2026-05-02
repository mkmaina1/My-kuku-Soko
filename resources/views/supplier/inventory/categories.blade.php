@extends('layouts.app')

@section('title', 'Product Categories')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags text-info mr-2"></i>Product Categories
        </h1>
        <a href="{{ route('supplier.inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Inventory
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-list mr-2"></i>
                Product Categories
            </h6>
        </div>
        <div class="card-body">
            @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="categoriesTable">
                    <thead class="bg-info text-white">
                        <tr>
                            <th>Category</th>
                            <th>Number of Products</th>
                            <th>Total Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                <span class="badge badge-info" style="font-size: 1em; padding: 5px 10px;">
                                    {{ $category->category }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $category->product_count }}</strong> products
                            </td>
                            <td>
                                <span class="font-weight-bold">{{ $category->total_quantity }} units</span>
                            </td>
                            <td>
                                <a href="{{ route('marketplace.index') }}?category={{ urlencode($category->category) }}"
                                   class="btn btn-info btn-sm" title="View Products">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Categories Found</h4>
                <p class="text-muted">Your products are not categorized yet.</p>
                <a href="{{ route('supplier.marketplace.create') }}" class="btn btn-success mt-2">
                    <i class="fas fa-plus mr-1"></i> Add Products
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
