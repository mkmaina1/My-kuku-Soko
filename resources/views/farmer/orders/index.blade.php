@extends('layouts.app')

@section('title', 'My Orders')

@section('styles')
<style>
    /* Enhanced Tab Navigation */
    .nav-tabs-custom {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1.5rem;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s ease;
        position: relative;
        margin-bottom: -2px;
        border-radius: 8px 8px 0 0;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #2e7d32;
        background-color: rgba(46, 125, 50, 0.05);
    }

    .nav-tabs-custom .nav-link.active {
        background-color: #2e7d32 !important;
        color: white !important;
        border: 1px solid #2e7d32;
        border-bottom-color: #2e7d32;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(46, 125, 50, 0.2);
    }

    .nav-tabs-custom .nav-link.active .badge {
        background-color: white !important;
        color: #2e7d32 !important;
    }

    /* Order Cards */
    .order-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: box-shadow 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .order-card-header {
        background-color: #f8f9fa;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
    }

    .order-card-body {
        padding: 1.25rem;
    }

    /* Status Badges */
    .order-status {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    .order-status-pending { background-color: #fff3cd; color: #856404; }
    .order-status-processing { background-color: #d1ecf1; color: #0c5460; }
    .order-status-shipped { background-color: #cce5ff; color: #004085; }
    .order-status-delivered { background-color: #d4edda; color: #155724; }
    .order-status-cancelled { background-color: #f8d7da; color: #721c24; }

    /* Payment Status Badges */
    .payment-status-paid { background-color: #d4edda; color: #155724; }
    .payment-status-pending { background-color: #fff3cd; color: #856404; }
    .payment-status-failed { background-color: #f8d7da; color: #721c24; }

    /* Order Items */
    .order-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #f1f1f1;
        transition: background-color 0.3s ease;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item:hover {
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .product-image-sm {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 1rem;
        border: 1px solid #dee2e6;
    }

    .product-image-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #adb5bd;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    /* Pagination */
    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }

    .pagination-wrapper .page-item.active .page-link {
        background-color: #2e7d32;
        border-color: #2e7d32;
        color: white;
    }

    .pagination-wrapper .page-link {
        color: #2e7d32;
        border: 1px solid #dee2e6;
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.2s ease;
        padding: 0.5rem 0.75rem;
        min-width: 38px;
        text-align: center;
    }

    .pagination-wrapper .page-link:hover {
        background-color: rgba(46, 125, 50, 0.1);
        border-color: #2e7d32;
        color: #2e7d32;
    }

    /* M-Pesa Payment Modal */
    .payment-modal .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }
    .payment-modal .modal-header {
        background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
        color: white;
    }
    .payment-status-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    .payment-status-icon.success {
        color: #28a745;
    }
    .payment-status-icon.failed {
        color: #dc3545;
    }
    .payment-status-icon.pending {
        color: #007bff;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .order-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .product-image-sm,
        .product-image-placeholder {
            margin-right: 0;
            margin-bottom: 0.75rem;
        }

        .nav-tabs-custom .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .pagination-wrapper .page-link {
            padding: 0.375rem 0.5rem;
            min-width: 32px;
            font-size: 0.875rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%); color: white;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-clipboard-list me-2"></i>My Orders
                            </h4>
                            <small class="opacity-75">Track and manage your orders</small>
                        </div>
                        <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-light">
                            <i class="fas fa-shopping-cart me-1"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs-custom" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('farmer.orders.index', ['status' => 'all']) }}"
                               class="nav-link {{ $status == 'all' ? 'active' : '' }}">
                                <i class="fas fa-list me-1"></i>All Orders
                                @if($counts['all'] > 0)
                                <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('farmer.orders.index', ['status' => 'pending']) }}"
                               class="nav-link {{ $status == 'pending' ? 'active' : '' }}">
                                <i class="fas fa-clock me-1"></i>Pending
                                @if($counts['pending'] > 0)
                                <span class="badge bg-warning ms-1">{{ $counts['pending'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('farmer.orders.index', ['status' => 'processing']) }}"
                               class="nav-link {{ $status == 'processing' ? 'active' : '' }}">
                                <i class="fas fa-cog me-1"></i>Processing
                                @if($counts['processing'] > 0)
                                <span class="badge bg-info ms-1">{{ $counts['processing'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('farmer.orders.index', ['status' => 'shipped']) }}"
                               class="nav-link {{ $status == 'shipped' ? 'active' : '' }}">
                                <i class="fas fa-shipping-fast me-1"></i>Shipped
                                @if($counts['shipped'] > 0)
                                <span class="badge bg-primary ms-1">{{ $counts['shipped'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('farmer.orders.index', ['status' => 'delivered']) }}"
                               class="nav-link {{ $status == 'delivered' ? 'active' : '' }}">
                                <i class="fas fa-check-circle me-1"></i>Delivered
                                @if($counts['delivered'] > 0)
                                <span class="badge bg-success ms-1">{{ $counts['delivered'] }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('farmer.orders.index', ['status' => 'cancelled']) }}"
                               class="nav-link {{ $status == 'cancelled' ? 'active' : '' }}">
                                <i class="fas fa-ban me-1"></i>Cancelled
                                @if($counts['cancelled'] > 0)
                                <span class="badge bg-danger ms-1">{{ $counts['cancelled'] }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="row">
        <div class="col-12">
            @if($orders->count() > 0)
                @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-card-header d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-1">Order #{{ $order->order_number }}</h5>
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ $order->created_at->format('M d, Y - h:i A') }}
                            </small>
                            @if($order->agent)
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-user-tie me-1"></i>Agent: {{ $order->agent->name }}
                            </small>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Payment Status Badge -->
                            @if($order->payment_status == 'paid')
                                <span class="order-status payment-status-paid">
                                    <i class="fas fa-check-circle me-1"></i>Paid
                                </span>
                            @elseif($order->payment_status == 'pending')
                                <span class="order-status payment-status-pending">
                                    <i class="fas fa-clock me-1"></i>Payment Pending
                                </span>
                            @elseif($order->payment_status == 'failed')
                                <span class="order-status payment-status-failed">
                                    <i class="fas fa-exclamation-circle me-1"></i>Payment Failed
                                </span>
                            @endif

                            <!-- Order Status Badge -->
                            <span class="order-status order-status-{{ $order->status }}">
                                @if($order->status == 'pending')
                                    <i class="fas fa-clock me-1"></i>Pending
                                @elseif($order->status == 'processing')
                                    <i class="fas fa-cog me-1"></i>Processing
                                @elseif($order->status == 'shipped')
                                    <i class="fas fa-shipping-fast me-1"></i>Shipped
                                @elseif($order->status == 'delivered')
                                    <i class="fas fa-check-circle me-1"></i>Delivered
                                @elseif($order->status == 'cancelled')
                                    <i class="fas fa-ban me-1"></i>Cancelled
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="order-card-body">
                        <!-- Order Items -->
                        @foreach($order->items->take(2) as $item)
                        <div class="order-item">
                            <div>
                                @if($item->product && $item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}"
                                     alt="{{ $item->product->name }}" class="product-image-sm">
                                @else
                                <div class="product-image-placeholder">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product->name ?? 'Product Unavailable' }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Quantity: {{ $item->quantity }}
                                    </small>
                                    <strong class="text-success">
                                        KES {{ number_format($item->price * $item->quantity, 2) }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($order->items->count() > 2)
                        <div class="text-center my-2">
                            <span class="badge bg-light text-dark">
                                +{{ $order->items->count() - 2 }} more items
                            </span>
                        </div>
                        @endif

                        <!-- M-Pesa Payment Info -->
                        @if($order->payment_method == 'mpesa' && $order->mpesaPayments->count() > 0)
                        <div class="alert alert-info mt-3 mb-2 py-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-mobile-alt fa-2x me-3"></i>
                                <div>
                                    <small class="d-block">
                                        <strong>M-Pesa Receipt:</strong>
                                        {{ $order->mpesaPayments->first()->mpesa_receipt_number ?? 'Pending' }}
                                    </small>
                                    <small class="d-block">
                                        <strong>Transaction Date:</strong>
                                        {{ $order->mpesaPayments->first()->transaction_date ? $order->mpesaPayments->first()->transaction_date->format('M d, Y h:i A') : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="row border-top pt-3 mt-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-credit-card me-1"></i>
                                        Payment: {{ ucfirst($order->payment_method) }}
                                    </small>
                                </div>
                                @if($order->tracking_number)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-truck me-1"></i>
                                        Tracking: {{ $order->tracking_number }}
                                    </small>
                                </div>
                                @endif
                                @if($order->shipping_address)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($order->shipping_address, 50) }}
                                    </small>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="mb-3">
                                    <h4 class="text-success mb-1">
                                        KES {{ number_format($order->total, 2) }}
                                    </h4>
                                    <small class="text-muted">
                                        @if($order->shipping > 0)
                                            Includes KES {{ number_format($order->shipping, 2) }} shipping
                                        @endif
                                    </small>
                                </div>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('farmer.orders.show', $order->id) }}"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    @if($order->status == 'shipped')
                                    <a href="{{ route('farmer.orders.track', $order->id) }}"
                                       class="btn btn-outline-info">
                                        <i class="fas fa-map-marker-alt me-1"></i>Track
                                    </a>
                                    @endif
                                    @if(in_array($order->status, ['pending', 'processing']))
                                    <button type="button"
                                            class="btn btn-outline-danger cancel-order-btn"
                                            data-order-id="{{ $order->id }}"
                                            data-order-number="{{ $order->order_number }}">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                    @endif
                                    @if($order->status == 'delivered' || $order->status == 'cancelled')
                                    <button type="button"
                                            class="btn btn-outline-success reorder-btn"
                                            data-order-id="{{ $order->id }}">
                                        <i class="fas fa-redo me-1"></i>Reorder
                                    </button>
                                    @endif
                                    @if($order->payment_status == 'failed' || ($order->payment_status == 'pending' && $order->status != 'cancelled'))
                                    <button type="button"
                                            class="btn btn-outline-warning retry-payment-btn"
                                            data-order-id="{{ $order->id }}"
                                            data-order-total="{{ $order->total }}">
                                        <i class="fas fa-money-bill me-1"></i>Retry Payment
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted mb-2 mb-md-0">
                                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                            </div>
                            <div class="pagination-wrapper">
                                <nav aria-label="Page navigation">
                                    {{ $orders->appends(['status' => $status])->links('vendor.pagination.bootstrap-4') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="empty-state">
                            @if($status == 'all')
                                <i class="fas fa-clipboard-list empty-state-icon"></i>
                                <h4>No Orders Yet</h4>
                                <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                                <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-success">
                                    <i class="fas fa-shopping-cart me-1"></i>Start Shopping
                                </a>
                            @else
                                @php
                                    $icons = [
                                        'pending' => 'fas fa-clock',
                                        'processing' => 'fas fa-cog',
                                        'shipped' => 'fas fa-shipping-fast',
                                        'delivered' => 'fas fa-check-circle',
                                        'cancelled' => 'fas fa-ban'
                                    ];
                                @endphp
                                <i class="{{ $icons[$status] ?? 'fas fa-clipboard-list' }} empty-state-icon"></i>
                                <h4>No {{ ucfirst($status) }} Orders</h4>
                                <p class="text-muted mb-4">You don't have any {{ $status }} orders.</p>
                                <a href="{{ route('farmer.orders.index', ['status' => 'all']) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-1"></i>View All Orders
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cancel Order
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="cancelOrderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to cancel order <strong id="orderNumberDisplay"></strong>?</p>
                    <p class="text-danger">
                        <i class="fas fa-info-circle me-1"></i>
                        This action cannot be undone.
                    </p>
                    <div class="form-group">
                        <label for="cancellation_reason">Reason for cancellation (optional):</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3"
                                  placeholder="Please let us know why you're cancelling this order..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Retry Payment Modal -->
<div class="modal fade" id="retryPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill me-2"></i>Retry Payment
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mpesa-info-box bg-light p-3 rounded mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-mobile-alt fa-3x text-success me-3"></i>
                        <div>
                            <h6 class="mb-1">M-Pesa Payment</h6>
                            <p class="mb-0 small text-muted">You'll receive an STK push prompt on your phone</p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="retry_phone_number">M-Pesa Phone Number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+254</span>
                        </div>
                        <input type="text" class="form-control" id="retry_phone_number"
                               placeholder="712345678" value="{{ Auth::user()->phone ?? '' }}">
                    </div>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Amount to pay: <strong id="retryAmount">KES 0.00</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmRetryPayment">
                    <i class="fas fa-paper-plane me-1"></i> Send Payment Prompt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentOrderId = null;

    // Cancel order button click
    $('.cancel-order-btn').click(function() {
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');

        $('#orderNumberDisplay').text('#' + orderNumber);
        $('#cancelOrderForm').attr('action', '{{ route("farmer.orders.cancel", ":id") }}'.replace(':id', orderId));
        $('#cancelOrderModal').modal('show');
    });

    // Reorder button click
    $('.reorder-btn').click(function() {
        const orderId = $(this).data('order-id');

        if (confirm('Add all items from this order to your cart?')) {
            $.ajax({
                url: '{{ route("farmer.orders.reorder", ":id") }}'.replace(':id', orderId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href = '{{ route("farmer.cart.index") }}';
                },
                error: function() {
                    alert('Failed to add items to cart. Please try again.');
                }
            });
        }
    });

    // Retry payment button click
    $('.retry-payment-btn').click(function() {
        currentOrderId = $(this).data('order-id');
        const orderTotal = $(this).data('order-total');

        $('#retryAmount').text('KES ' + Number(orderTotal).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#retryPaymentModal').modal('show');
    });

    // Confirm retry payment
    $('#confirmRetryPayment').click(function() {
        const phone = $('#retry_phone_number').val();

        if (!phone || !phone.match(/^[0-9]{9,12}$/)) {
            toastr.error('Please enter a valid phone number');
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Sending...');

        $.ajax({
            url: '{{ route("farmer.orders.retry-payment") }}',
            method: 'POST',
            data: {
                order_id: currentOrderId,
                phone_number: phone,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#retryPaymentModal').modal('hide');
                    toastr.success('Payment prompt sent! Please check your phone.');

                    // Show payment processing modal
                    showPaymentModal(response.payment.checkout_request_id, response.payment.amount);
                } else {
                    toastr.error(response.message || 'Failed to initiate payment');
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'An error occurred');
            },
            complete: function() {
                $('#confirmRetryPayment').prop('disabled', false)
                    .html('<i class="fas fa-paper-plane me-1"></i> Send Payment Prompt');
            }
        });
    });

    // Function to show payment processing modal
    function showPaymentModal(checkoutRequestId, amount) {
        const modalHtml = `
            <div class="modal fade payment-modal" id="paymentModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-mobile-alt me-2"></i>Payment Processing
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="payment-status-icon pending">
                                <i class="fas fa-spinner fa-pulse"></i>
                            </div>
                            <h4 id="paymentMessage">Please check your phone</h4>
                            <p class="text-muted" id="paymentDetails">STK push prompt sent to your phone</p>
                            <p class="font-weight-bold">Amount: KES ${Number(amount).toLocaleString()}</p>
                            <div class="alert alert-warning mt-3" id="paymentAlert" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span id="alertMessage"></span>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" id="checkStatusBtn">
                                <i class="fas fa-sync-alt me-2"></i>Check Status
                            </button>
                            <a href="{{ route('farmer.orders.index') }}" class="btn btn-success" id="viewOrdersBtn" style="display: none;">
                                <i class="fas fa-list me-2"></i>View Orders
                            </a>
                            <button type="button" class="btn btn-danger" id="cancelPaymentBtn">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHtml);
        $('#paymentModal').modal('show');

        // Start checking payment status
        let statusCheckInterval = setInterval(function() {
            $.ajax({
                url: '{{ url("/api/mpesa/status") }}/' + checkoutRequestId,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        if (response.status === 'completed') {
                            clearInterval(statusCheckInterval);
                            $('#paymentModal .payment-status-icon')
                                .removeClass('pending')
                                .addClass('success')
                                .html('<i class="fas fa-check-circle"></i>');
                            $('#paymentMessage').text('Payment Successful!');
                            $('#paymentDetails').text('Receipt: ' + (response.mpesa_receipt || 'N/A'));
                            $('#checkStatusBtn').hide();
                            $('#viewOrdersBtn').show();
                            $('#cancelPaymentBtn').hide();
                            toastr.success('Payment completed successfully!');

                            setTimeout(() => {
                                $('#paymentModal').modal('hide');
                                window.location.reload();
                            }, 2000);
                        } else if (response.status === 'failed') {
                            clearInterval(statusCheckInterval);
                            $('#paymentModal .payment-status-icon')
                                .removeClass('pending')
                                .addClass('failed')
                                .html('<i class="fas fa-times-circle"></i>');
                            $('#paymentMessage').text('Payment Failed');
                            $('#paymentDetails').text('Please try again');
                            $('#paymentAlert').show().find('#alertMessage').text('Your payment was not completed.');
                            $('#checkStatusBtn').hide();
                            $('#viewOrdersBtn').show();
                            toastr.error('Payment failed. Please try again.');
                        }
                    }
                }
            });
        }, 3000);

        // Check status manually
        $('#checkStatusBtn').click(function() {
            // Trigger the same check
        });

        // Cancel payment
        $('#cancelPaymentBtn').click(function() {
            clearInterval(statusCheckInterval);
            $('#paymentModal').modal('hide');
        });

        // Clean up on modal hide
        $('#paymentModal').on('hidden.bs.modal', function() {
            clearInterval(statusCheckInterval);
            $(this).remove();
        });
    }

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection
