@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shopping-cart text-warning mr-2"></i>Shopping Cart
        </h1>
        <div>
            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-success mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Continue Shopping
            </a>
            @if($cartItems->count() > 0)
                <a href="{{ route('checkout') }}" class="btn btn-success">
                    <i class="fas fa-credit-card mr-1"></i> Proceed to Checkout
                </a>
            @endif
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Cart Items ({{ $cartItems->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php
                                            $product = $item->product;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/60' }}"
                                                         width="60" height="60" style="object-fit: cover;"
                                                         class="mr-3" alt="{{ $product->title }}">
                                                    <div>
                                                        <h6 class="font-weight-bold">{{ $product->title }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-user-tie mr-1"></i>{{ $product->supplier->name }}
                                                        </small><br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $product->location }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                KES {{ number_format($product->price) }}<br>
                                                <small class="text-muted">per {{ $product->unit }}</small>
                                            </td>
                                            <td>
                                                <div class="input-group" style="width: 120px;">
                                                    <input type="number" class="form-control form-control-sm"
                                                           value="{{ $item->quantity }}" min="{{ $product->min_order }}"
                                                           max="{{ min($product->max_order ?? $product->quantity, $product->quantity) }}"
                                                           data-id="{{ $item->id }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-sm btn-outline-success update-quantity"
                                                                data-id="{{ $item->id }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    Min: {{ $product->min_order }}, Max: {{ $product->max_order ?? 'No limit' }}
                                                </small>
                                            </td>
                                            <td class="font-weight-bold text-success">
                                                KES {{ number_format($item->total) }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger remove-item"
                                                        data-id="{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>KES {{ number_format($subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>KES {{ number_format($shipping) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax ({{ config('app.tax_rate', 16) }}%)</span>
                                <span>KES {{ number_format($tax) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong class="text-success">KES {{ number_format($total) }}</strong>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-success btn-block btn-lg">
                            <i class="fas fa-credit-card mr-1"></i> Checkout Now
                        </a>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card shadow mt-4">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Accepted Payment Methods</h6>
                        <div class="row text-center">
                            <div class="col-3">
                                <i class="fab fa-mpesa fa-2x text-success"></i>
                                <small class="d-block">M-Pesa</small>
                            </div>
                            <div class="col-3">
                                <i class="fas fa-credit-card fa-2x text-primary"></i>
                                <small class="d-block">Card</small>
                            </div>
                            <div class="col-3">
                                <i class="fas fa-university fa-2x text-info"></i>
                                <small class="d-block">Bank</small>
                            </div>
                            <div class="col-3">
                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                <small class="d-block">Cash</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <p class="text-muted mb-4">Add some products to get started!</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-success btn-lg">
                <i class="fas fa-store mr-1"></i> Browse Marketplace
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update quantity
    $('.update-quantity').click(function() {
        var cartId = $(this).data('id');
        var quantity = $(this).closest('td').find('input').val();

        $.ajax({
            url: '/cart/update/' + cartId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                location.reload();
            }
        });
    });

    // Remove item
    $('.remove-item').click(function() {
        if (confirm('Remove this item from cart?')) {
            var cartId = $(this).data('id');

            $.ajax({
                url: '/cart/remove/' + cartId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endpush
