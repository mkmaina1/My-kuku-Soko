@extends('layouts.app')

@section('title', 'Low Stock Products')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Low Stock Products
        </h1>
        <a href="{{ route('supplier.inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Inventory
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $lowStockCount }} Low Stock Product(s)
            </h6>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="lowStockTable">
                    <thead class="bg-warning text-dark">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr class="{{ $product->quantity <= 0 ? 'table-danger' : '' }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" class="rounded mr-2" width="40" height="40" alt="{{ $product->title }}">
                                    @else
                                        <div class="rounded bg-light mr-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->title }}</strong>
                                        <div class="small text-muted">#{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $product->category }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 mr-2" style="height: 10px;">
                                        @php
                                            $percentage = min(100, ($product->quantity / 10) * 100);
                                        @endphp
                                        <div class="progress-bar bg-{{ $product->quantity <= 0 ? 'danger' : 'warning' }}"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="font-weight-bold text-{{ $product->quantity <= 0 ? 'danger' : 'warning' }}">
                                        {{ $product->quantity }} {{ $product->unit }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-success font-weight-bold">
                                KES {{ number_format($product->price) }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('marketplace.show', $product->id) }}" class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('supplier.marketplace.edit', $product->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
            @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4 class="text-success">Great! No Low Stock Products</h4>
                <p class="text-muted">All your products have sufficient stock levels.</p>
                <a href="{{ route('supplier.inventory.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-warehouse mr-1"></i> View All Products
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
