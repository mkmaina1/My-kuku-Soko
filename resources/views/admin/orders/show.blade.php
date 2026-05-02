@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('styles')
<style>
    .order-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 1.5rem;
    }

    .status-badge-lg {
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .item-card {
        border-left: 4px solid #007bff;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .customer-avatar-lg {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e0e0e0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #007bff;
        border: 2px solid white;
    }

    .timeline-item.completed::before {
        background-color: #28a745;
    }

    .badge-pending { background-color: #ffc107; color: #212529; }
    .badge-processing { background-color: #17a2b8; color: white; }
    .badge-shipped { background-color: #007bff; color: white; }
    .badge-delivered { background-color: #28a745; color: white; }
    .badge-cancelled { background-color: #dc3545; color: white; }

    .payment-badge-lg {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .payment-cash { background-color: #e7f5ff; color: #0d6efd; }
    .payment-mpesa { background-color: #f0f9ff; color: #0dcaf0; }
    .payment-card { background-color: #fff0f6; color: #d63384; }
    .payment-bank { background-color: #f8f9fa; color: #6c757d; }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .order-header {
            background: #6c757d !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Order Header -->
    <div class="card shadow border-0 mb-4">
        <div class="order-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 font-weight-bold mb-1">
                        <i class="fas fa-receipt mr-2"></i>Order #{{ $order->order_number }}
                    </h2>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $order->created_at->format('F d, Y h:i A') }}
                    </p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @php
                        $statusClass = 'badge-' . $order->status;
                    @endphp
                    <span class="status-badge-lg {{ $statusClass }}">
                        <i class="fas fa-circle mr-1" style="font-size: 0.5rem;"></i>
                        {{ ucfirst($order->status) }}
                    </span>
                    <button class="btn btn-light no-print" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Order Details & Items -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card order-card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-boxes mr-2"></i>Order Items
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="item-card p-3 mb-3 bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @php
                                        // Safe way to check if product and images exist
                                        $productImage = null;
                                        if ($item->product && $item->product->images && $item->product->images->count() > 0) {
                                            $productImage = $item->product->images->first();
                                        }
                                    @endphp

                                    @if($productImage)
                                        <img src="{{ asset('storage/' . $productImage->image_path) }}"
                                             alt="{{ $item->product->name ?? 'Product Image' }}"
                                             class="product-image"
                                             onerror="this.src='https://via.placeholder.com/80?text=Product'">
                                    @else
                                        <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                            <i class="fas fa-box text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold mb-1">{{ $item->product->name ?? 'Product Unavailable' }}</h6>
                                    @if($item->product && $item->product->sku)
                                        <p class="text-muted small mb-1">
                                            SKU: {{ $item->product->sku }}
                                        </p>
                                    @endif
                                    <p class="text-muted small mb-0">
                                        Supplier: {{ $item->product->supplier->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="mb-1">
                                        <span class="h6 font-weight-bold">KES {{ number_format($item->price) }}</span>
                                        <small class="text-muted"> x {{ $item->quantity }}</small>
                                    </div>
                                    <div class="text-success font-weight-bold">
                                        KES {{ number_format($item->price * $item->quantity) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Order Summary -->
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="font-weight-bold">KES {{ number_format($order->subtotal) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span class="font-weight-bold">KES {{ number_format($order->shipping) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span class="font-weight-bold">KES {{ number_format($order->tax) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="h5">Total:</span>
                                    <span class="h4 font-weight-bold text-primary">
                                        KES {{ number_format($order->total) }}
                                    </span>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Payment:
                                        @php
                                            $paymentClass = 'payment-' . $order->payment_method;
                                        @endphp
                                        <span class="payment-badge-lg {{ $paymentClass }}">
                                            {{ strtoupper($order->payment_method) }}
                                        </span>
                                        ({{ $order->payment_status == 'paid' ? 'Paid' : 'Pending' }})
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Notes & Delivery -->
            <div class="card order-card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-sticky-note mr-2"></i>Notes & Delivery
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold mb-2">Customer Notes</h6>
                            @if($order->notes)
                                <div class="p-3 bg-light rounded">
                                    {{ $order->notes }}
                                </div>
                            @else
                                <p class="text-muted mb-0">No notes provided</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold mb-2">Delivery Information</h6>
                            <div class="p-3 bg-light rounded">
                                <div class="mb-2">
                                    <strong>Address:</strong>
                                    <div>{{ $order->shipping_address ?? 'No shipping address provided' }}</div>
                                </div>
                                @if($order->tracking_number)
                                    <div class="mb-2">
                                        <strong>Tracking Number:</strong>
                                        <div class="text-primary">{{ $order->tracking_number }}</div>
                                    </div>
                                @endif
                                @if($order->delivered_at)
                                    <div class="mb-2">
                                        <strong>Delivered On:</strong>
                                        <div>{{ $order->delivered_at->format('F d, Y h:i A') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer & Actions -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card order-card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-user mr-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($order->user->avatar)
                            <img src="{{ asset('storage/' . $order->user->avatar) }}"
                                 alt="{{ $order->user->name }}"
                                 class="customer-avatar-lg mr-3"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&color=7F9CF5&background=EBF4FF'">
                        @else
                            <div class="customer-avatar-lg mr-3 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-muted fa-2x"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="font-weight-bold mb-1">{{ $order->user->name }}</h6>
                            <p class="text-muted small mb-1">{{ $order->user->email }}</p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-phone mr-1"></i>{{ $order->user->phone ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-external-link-alt mr-1"></i>View Customer Profile
                        </a>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold mb-2">Order Statistics</h6>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bg-light p-2 rounded">
                                <div class="h5 mb-1 font-weight-bold">{{ $order->user->orders_count ?? 0 }}</div>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-light p-2 rounded">
                                <div class="h5 mb-1 font-weight-bold">
                                    KES {{ number_format($order->user->total_spent ?? 0) }}
                                </div>
                                <small class="text-muted">Total Spent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card order-card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-history mr-2"></i>Order Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="mb-1">
                                <strong>Order Placed</strong>
                            </div>
                            <small class="text-muted">{{ $order->created_at->format('M d, h:i A') }}</small>
                        </div>

                        @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                            <div class="timeline-item">
                                <div class="mb-1">
                                    <strong>Order Processing</strong>
                                </div>
                                <small class="text-muted">
                                    {{ $order->updated_at->format('M d, h:i A') }}
                                </small>
                            </div>
                        @endif

                        @if($order->status == 'shipped' || $order->status == 'delivered')
                            <div class="timeline-item">
                                <div class="mb-1">
                                    <strong>Order Shipped</strong>
                                </div>
                                <small class="text-muted">
                                    {{ $order->updated_at->format('M d, h:i A') }}
                                </small>
                            </div>
                        @endif

                        @if($order->status == 'delivered')
                            <div class="timeline-item completed">
                                <div class="mb-1">
                                    <strong>Order Delivered</strong>
                                </div>
                                <small class="text-muted">
                                    {{ $order->delivered_at->format('M d, h:i A') }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card order-card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->status != 'delivered' && $order->status != 'cancelled')
                        <!-- Update Status Form -->
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="mb-3">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="status" class="font-weight-bold">Update Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="" disabled>Select new status</option>
                                    @foreach(['processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                        @if($status != $order->status)
                                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tracking_number" class="font-weight-bold">Tracking Number</label>
                                <input type="text" name="tracking_number" id="tracking_number"
                                       class="form-control" placeholder="Enter tracking number"
                                       value="{{ old('tracking_number', $order->tracking_number) }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sync-alt mr-1"></i>Update Status
                            </button>
                        </form>
                    @endif

                    @if($order->status != 'cancelled')
                        <!-- Cancel Order -->
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}"
                              onsubmit="return confirm('Are you sure you want to cancel this order? This will restore product quantities.')">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger btn-block mb-3">
                                <i class="fas fa-times-circle mr-1"></i>Cancel Order
                            </button>
                        </form>
                    @endif

                    @if($order->status == 'cancelled')
                        <!-- Delete Order -->
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                              onsubmit="return confirm('Are you sure you want to delete this cancelled order? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block mb-3">
                                <i class="fas fa-trash mr-1"></i>Delete Order
                            </button>
                        </form>
                    @endif

                    <!-- Send Email to Customer -->
                    <button type="button" class="btn btn-warning btn-block no-print"
                            onclick="sendEmailToCustomer('{{ $order->user->email }}', '{{ $order->order_number }}')">
                        <i class="fas fa-envelope mr-1"></i>Email Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-focus on tracking number when shipped is selected
    document.getElementById('status').addEventListener('change', function() {
        if (this.value === 'shipped') {
            document.getElementById('tracking_number').focus();
        }
    });

    // Print functionality
    function printOrder() {
        window.print();
    }

    // Send email to customer
    function sendEmailToCustomer(email, orderNumber) {
        const subject = encodeURIComponent(`Update on Your Order #${orderNumber}`);
        const body = encodeURIComponent(`Dear Customer,\n\nThis is regarding your order #${orderNumber}.\n\nBest regards,\nAdmin Team`);
        window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
    }

    // Auto-refresh page every 30 seconds for pending/processing orders
    @if(in_array($order->status, ['pending', 'processing']))
        setTimeout(function() {
            location.reload();
        }, 30000); // 30 seconds
    @endif

    // Show loading animation for form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';
                    submitBtn.disabled = true;
                }
            });
        });
    });
</script>
@endpush
