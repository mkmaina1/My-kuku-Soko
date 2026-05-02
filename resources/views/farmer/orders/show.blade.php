@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('styles')
<style>
    .order-header-bg {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
    }
    .product-img-sm {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .status-badge-lg {
        font-size: 0.9rem;
        padding: 8px 20px;
        border-radius: 20px;
    }
    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 10px;
    }
    .timeline-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }
    .timeline-vertical {
        position: relative;
        padding-left: 40px;
    }
    .timeline-vertical::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .invoice-total {
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-file-invoice mr-2"></i>Order Details
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.orders.index') }}">My Orders</a></li>
                    <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Order Status Header -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary order-header-bg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="card-title mb-1 text-white">
                                    <i class="fas fa-receipt mr-2"></i>Order #{{ $order->order_number }}
                                </h3>
                                <p class="card-text text-white-50 mb-2">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                </p>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusIcons = [
                                        'pending' => 'clock',
                                        'processing' => 'cogs',
                                        'shipped' => 'shipping-fast',
                                        'delivered' => 'check-circle',
                                        'cancelled' => 'times-circle'
                                    ];
                                @endphp
                                <div class="mt-3">
                                    <span class="badge badge-light status-badge-lg">
                                        <i class="fas fa-{{ $statusIcons[$order->status] ?? 'circle' }} mr-1"></i>
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="d-flex flex-column align-items-md-end gap-2">
                                    <a href="{{ route('farmer.orders.track', $order->id) }}"
                                       class="btn btn-light btn-sm">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Track Order
                                    </a>
                                    <button onclick="window.print()" class="btn btn-light btn-sm">
                                        <i class="fas fa-print mr-1"></i>Print Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Items Column -->
            <div class="col-lg-8">
                <!-- Order Items Card -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-2"></i>Order Items
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="width: 100px">Image</th>
                                        <th>Product</th>
                                        <th style="width: 100px" class="text-center">Price</th>
                                        <th style="width: 100px" class="text-center">Quantity</th>
                                        <th style="width: 120px" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->product->images)
                                                <img src="{{ asset('storage/' . json_decode($item->product->images)[0]) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="img-fluid product-img-sm">
                                            @else
                                                <div class="product-img-sm bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-box text-muted fa-2x"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>{{ $item->product->name }}</strong>
                                                <small class="text-muted">
                                                    SKU: {{ $item->product->sku }}
                                                    @if($item->product->supplier)
                                                    <br>
                                                    <i class="fas fa-user-tag mr-1"></i>{{ $item->product->supplier->name }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            KES {{ number_format($item->price, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary px-3 py-1">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-right">
                                            <strong>KES {{ number_format($item->price * $item->quantity, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card card-outline card-info mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>Order Timeline
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline-vertical">
                            <!-- Timeline Item 1 -->
                            <div class="timeline-item mb-4">
                                <div class="timeline-item-icon bg-success d-inline-flex">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                                <div class="ml-5">
                                    <h5 class="mb-1">Order Placed</h5>
                                    <p class="text-muted mb-1">{{ $order->created_at->format('F d, Y h:i A') }}</p>
                                    <p class="mb-0">Your order has been successfully placed.</p>
                                </div>
                            </div>

                            <!-- Timeline Item 2 -->
                            <div class="timeline-item mb-4">
                                <div class="timeline-item-icon {{ $order->status != 'pending' ? 'bg-info' : 'bg-secondary' }} d-inline-flex">
                                    <i class="fas {{ $order->status != 'pending' ? 'fa-cogs' : 'fa-clock' }} text-white"></i>
                                </div>
                                <div class="ml-5">
                                    <h5 class="mb-1">Processing</h5>
                                    @if($order->status != 'pending')
                                        <p class="text-muted mb-1">{{ $order->updated_at->format('F d, Y h:i A') }}</p>
                                        <p class="mb-0">Your order is being processed.</p>
                                    @else
                                        <p class="mb-0 text-muted">Pending</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Timeline Item 3 -->
                            <div class="timeline-item mb-4">
                                <div class="timeline-item-icon {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-primary' : 'bg-secondary' }} d-inline-flex">
                                    <i class="fas fa-shipping-fast text-white"></i>
                                </div>
                                <div class="ml-5">
                                    <h5 class="mb-1">Shipped</h5>
                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                        <p class="text-muted mb-1">Shipped on {{ now()->subDays(2)->format('F d, Y') }}</p>
                                        @if($order->tracking_number)
                                        <p class="mb-0">Tracking: <code>{{ $order->tracking_number }}</code></p>
                                        @endif
                                    @else
                                        <p class="mb-0 text-muted">Not yet shipped</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Timeline Item 4 -->
                            <div class="timeline-item">
                                <div class="timeline-item-icon {{ $order->status == 'delivered' ? 'bg-success' : 'bg-secondary' }} d-inline-flex">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="ml-5">
                                    <h5 class="mb-1">Delivered</h5>
                                    @if($order->status == 'delivered')
                                        <p class="text-muted mb-1">Delivered on {{ now()->format('F d, Y') }}</p>
                                        <p class="mb-0">Your order has been delivered successfully.</p>
                                    @else
                                        <p class="mb-0 text-muted">In transit</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                <div class="card card-outline card-warning mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-sticky-note mr-2"></i>Order Notes
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-info">
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Order Summary Card -->
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-receipt mr-2"></i>Order Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Subtotal:</strong>
                            </div>
                            <div class="col-6 text-right">
                                KES {{ number_format($order->subtotal, 2) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Shipping:</strong>
                            </div>
                            <div class="col-6 text-right">
                                KES {{ number_format($order->shipping, 2) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Tax:</strong>
                            </div>
                            <div class="col-6 text-right">
                                KES {{ number_format($order->tax, 2) }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong class="invoice-total">Total:</strong>
                            </div>
                            <div class="col-6 text-right">
                                <span class="invoice-total text-success">
                                    KES {{ number_format($order->total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information Card -->
                <div class="card card-outline card-info mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-truck mr-2"></i>Shipping Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Shipping Address</span>
                                <span class="info-box-number">{{ $order->shipping_address }}</span>
                            </div>
                        </div>

                        @if($order->tracking_number)
                        <div class="info-box bg-light mb-3">
                            <span class="info-box-icon bg-primary">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tracking Number</span>
                                <span class="info-box-number">
                                    <code>{{ $order->tracking_number }}</code>
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Payment Method</span>
                                <span class="info-box-number">{{ ucfirst($order->payment_method) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Actions Card -->
                <div class="card card-outline card-primary mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cogs mr-2"></i>Order Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if(in_array($order->status, ['pending', 'processing']))
                            <form action="{{ route('farmer.orders.cancel', $order->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-times mr-1"></i>Cancel Order
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('farmer.orders.reorder', $order->id) }}"
                               class="btn btn-success btn-block"
                               onclick="return confirm('Add all items from this order to your cart?');">
                                <i class="fas fa-redo mr-1"></i>Reorder
                            </a>

                            <button onclick="downloadInvoice({{ $order->id }})" class="btn btn-primary btn-block">
                                <i class="fas fa-download mr-1"></i>Download Invoice
                            </button>

                            <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-shopping-cart mr-1"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card card-outline card-secondary mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>Order Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 border-right">
                                <div class="text-muted">Items</div>
                                <div class="h3">{{ $order->items->count() }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Total Quantity</div>
                                <div class="h3">{{ $order->items->sum('quantity') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function downloadInvoice(orderId) {
    // Show loading state
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Generating...';
    btn.disabled = true;

    // Simulate API call (replace with actual AJAX call)
    setTimeout(() => {
        // In a real application, this would be an AJAX call to generate/download PDF
        const invoiceUrl = `/farmer/orders/${orderId}/invoice/download`;

        // Create temporary link for download
        const link = document.createElement('a');
        link.href = invoiceUrl;
        link.download = `invoice-${orderId}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;

        // Show success message
        showToast('Invoice downloaded successfully!', 'success');
    }, 1500);
}

function showToast(message, type = 'success') {
    // Simple toast notification using AdminLTE toast
    $(document).Toasts('create', {
        class: `bg-${type}`,
        title: 'Success',
        body: message,
        autohide: true,
        delay: 3000
    });
}

// Initialize tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    // Print functionality
    $('.print-btn').on('click', function() {
        window.print();
    });
});
</script>
@endsection
