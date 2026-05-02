@extends('layouts.app')

@section('title', 'Track Order #' . $order->order_number)

@section('styles')
<style>
    .tracking-timeline {
        position: relative;
        padding-left: 40px;
    }
    .tracking-timeline:before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }
    .tracking-step {
        position: relative;
        margin-bottom: 30px;
        min-height: 60px;
    }
    .tracking-step.completed .timeline-icon {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
        box-shadow: 0 0 0 5px rgba(40, 167, 69, 0.1);
    }
    .tracking-step.active .timeline-icon {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        animation: pulse 2s infinite;
        box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.1);
    }
    .tracking-step.pending .timeline-icon {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    .tracking-step.cancelled .timeline-icon {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    .tracking-step .timeline-icon {
        position: absolute;
        left: -48px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        transition: all 0.3s ease;
    }
    .tracking-step:hover .timeline-icon {
        transform: scale(1.1);
    }
    .tracking-details {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    .tracking-step.completed .tracking-details {
        border-left-color: #28a745;
        background-color: #f0f9f0;
    }
    .tracking-step.active .tracking-details {
        border-left-color: #007bff;
        background-color: #e3f2fd;
    }
    .tracking-step.pending .tracking-details {
        border-left-color: #6c757d;
        background-color: #f8f9fa;
    }
    .tracking-step.cancelled .tracking-details {
        border-left-color: #dc3545;
        background-color: #fdf2f2;
    }
    .order-status-badge {
        font-size: 0.85rem;
        padding: 5px 15px;
        border-radius: 20px;
    }
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }
    .tracking-map {
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .tracking-map:before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        animation: moveMap 20s linear infinite;
    }
    @keyframes moveMap {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(20px, 20px);
        }
    }
    .map-pin {
        position: relative;
        z-index: 2;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        padding: 20px;
        backdrop-filter: blur(10px);
    }
    .info-box-sm {
        min-height: 70px;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }
    .info-box-sm:hover {
        transform: translateY(-2px);
    }
    .product-img-container {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .progress-container {
        position: relative;
        height: 5px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin: 20px 0;
    }
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #007bff);
        border-radius: 10px;
        transition: width 1s ease;
    }
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        font-size: 0.8rem;
        color: #6c757d;
    }
    .estimated-time {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    }
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .order-summary-card .card-body {
        padding: 15px;
    }
</style>
@endsection

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-map-marker-alt mr-2"></i>Order Tracking
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active">Track Order #{{ $order->order_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <!-- Order Header Card -->
        <div class="card card-primary mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-shipping-fast mr-2"></i>
                            Order #{{ $order->order_number }}
                        </h3>
                        <p class="mb-1 text-muted">
                            <i class="far fa-calendar-alt mr-1"></i>
                            Ordered on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                        </p>
                        @if($order->estimated_delivery)
                        <p class="mb-0 text-muted">
                            <i class="far fa-clock mr-1"></i>
                            Estimated Delivery: {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('F d, Y') }}
                        </p>
                        @endif
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <span class="badge badge-{{ $order->status === 'cancelled' ? 'danger' : ($order->status === 'delivered' ? 'success' : 'primary') }} order-status-badge">
                            <span class="status-indicator bg-{{ $order->status === 'cancelled' ? 'danger' : ($order->status === 'delivered' ? 'success' : 'primary') }}"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-credit-card mr-1"></i>
                                {{ ucfirst($order->payment_method) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Tracking Timeline -->
            <div class="col-lg-8">
                <!-- Progress Tracking -->
                <div class="card card-outline card-primary mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-truck-loading mr-2"></i>
                            Order Progress
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $progressPercentage = 0;
                            $statusOrder = ['pending', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'];
                            $currentStatusIndex = array_search($order->status, $statusOrder);

                            if ($currentStatusIndex !== false) {
                                $progressPercentage = (($currentStatusIndex + 1) / count($statusOrder)) * 100;
                            }

                            if ($order->status === 'cancelled') {
                                $progressPercentage = 100;
                            }
                        @endphp

                        <div class="progress-container">
                            <div class="progress-bar" id="order-progress" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="progress-label">
                            <span>Placed</span>
                            <span>Processing</span>
                            <span>Shipped</span>
                            <span>Out for Delivery</span>
                            <span>Delivered</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>
                            Tracking Timeline
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="tracking-timeline">
                            @php
                                $trackingSteps = [
                                    [
                                        'status_key' => 'pending',
                                        'title' => 'Order Placed',
                                        'description' => 'Your order has been confirmed and payment received.',
                                        'date' => $order->created_at->format('M d, Y h:i A'),
                                        'icon' => 'shopping-cart'
                                    ],
                                    [
                                        'status_key' => 'processing',
                                        'title' => 'Processing',
                                        'description' => 'Seller is preparing your order for shipment.',
                                        'date' => $order->status !== 'pending' ? $order->updated_at->format('M d, Y h:i A') : null,
                                        'icon' => 'cogs'
                                    ],
                                    [
                                        'status_key' => 'shipped',
                                        'title' => 'Shipped',
                                        'description' => 'Your order has been shipped and is on its way.',
                                        'date' => in_array($order->status, ['shipped', 'delivered']) ? $order->updated_at->format('M d, Y h:i A') : null,
                                        'icon' => 'shipping-fast'
                                    ],
                                    [
                                        'status_key' => 'out_for_delivery',
                                        'title' => 'Out for Delivery',
                                        'description' => 'Your order is out for delivery today.',
                                        'date' => $order->status === 'delivered' ? $order->delivered_at?->format('M d, Y h:i A') ?? now()->subHours(3)->format('M d, Y h:i A') : null,
                                        'icon' => 'truck'
                                    ],
                                    [
                                        'status_key' => 'delivered',
                                        'title' => 'Delivered',
                                        'description' => 'Your order has been delivered successfully.',
                                        'date' => $order->status === 'delivered' ? $order->delivered_at?->format('M d, Y h:i A') ?? now()->format('M d, Y h:i A') : null,
                                        'icon' => 'check-circle'
                                    ]
                                ];
                            @endphp

                            @foreach($trackingSteps as $step)
                                @php
                                    $stepIndex = array_search($step['status_key'], $statusOrder);
                                    if ($order->status === 'cancelled') {
                                        $stepStatus = 'pending';
                                    } elseif ($currentStatusIndex === false) {
                                        $stepStatus = 'pending';
                                    } elseif ($stepIndex < $currentStatusIndex) {
                                        $stepStatus = 'completed';
                                    } elseif ($stepIndex === $currentStatusIndex) {
                                        $stepStatus = 'active';
                                    } elseif ($stepIndex > $currentStatusIndex) {
                                        $stepStatus = 'pending';
                                    } else {
                                        $stepStatus = 'pending';
                                    }
                                @endphp
                                <div class="tracking-step {{ $stepStatus }}">
                                    <div class="timeline-icon">
                                        <i class="fas fa-{{ $step['icon'] }}"></i>
                                    </div>
                                    <div class="tracking-details">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="mb-1">
                                                    {{ $step['title'] }}
                                                    @if($stepStatus === 'active')
                                                        <small class="badge badge-primary ml-2">Current</small>
                                                    @endif
                                                </h5>
                                                <p class="mb-0 text-muted">{{ $step['description'] }}</p>
                                            </div>
                                            @if($step['date'])
                                                <small class="text-muted">{{ $step['date'] }}</small>
                                            @endif
                                        </div>
                                        @if($step['status_key'] === 'shipped' && $order->tracking_number)
                                            <div class="mt-2">
                                                <small class="text-primary">
                                                    <i class="fas fa-barcode mr-1"></i>
                                                    Tracking: <strong>{{ $order->tracking_number }}</strong>
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($order->status === 'cancelled')
                                <div class="tracking-step cancelled">
                                    <div class="timeline-icon">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="tracking-details">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="mb-1">Order Cancelled</h5>
                                                <p class="mb-0 text-muted">This order has been cancelled.</p>
                                            </div>
                                            <small class="text-muted">{{ $order->updated_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Details & Actions -->
            <div class="col-lg-4">
                <!-- Delivery Status Card -->
                <div class="card card-outline card-success mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>Delivery Status
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="estimated-time">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h5 class="mb-1">
                                @if($order->status === 'delivered')
                                    Delivered
                                @elseif($order->status === 'cancelled')
                                    Cancelled
                                @else
                                    Estimated Delivery
                                @endif
                            </h5>
                            <p class="mb-0">
                                @if($order->status === 'delivered')
                                    {{ $order->delivered_at ? $order->delivered_at->format('M d, Y') : now()->format('M d, Y') }}
                                @elseif($order->status === 'cancelled')
                                    Order Cancelled
                                @elseif($order->estimated_delivery)
                                    {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d, Y') }}
                                @else
                                    Calculating...
                                @endif
                            </p>
                        </div>

                        <div class="tracking-map">
                            <div class="map-pin">
                                <i class="fas fa-map-marked-alt fa-3x mb-2"></i>
                                <h5 class="mb-0">Live Tracking</h5>
                                <small>Available for shipped orders</small>
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div class="info-box bg-gradient-info info-box-sm">
                            <span class="info-box-icon">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tracking Number</span>
                                <span class="info-box-number">
                                    {{ $order->tracking_number ?: 'Not assigned yet' }}
                                </span>
                            </div>
                        </div>

                        <div class="info-box bg-gradient-primary info-box-sm">
                            <span class="info-box-icon">
                                <i class="fas fa-truck"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Carrier</span>
                                <span class="info-box-number">{{ $order->carrier ?? 'Standard Shipping' }}</span>
                            </div>
                        </div>

                        <div class="info-box bg-gradient-success info-box-sm">
                            <span class="info-box-icon">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Payment Status</span>
                                <span class="info-box-number">
                                    {{ ucfirst($order->payment_method) }}
                                    @if($order->payment_status)
                                        <small class="d-block">{{ ucfirst($order->payment_status) }}</small>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card card-outline card-warning mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-2"></i>Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('farmer.orders.show', $order) }}" class="btn btn-info">
                                <i class="fas fa-eye mr-1"></i> View Order Details
                            </a>
                            <a href="{{ route('farmer.orders.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                            </a>
                            @if($order->status === 'pending' || $order->status === 'processing')
                                <form action="{{ route('farmer.orders.cancel', $order) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times mr-1"></i> Cancel Order
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('farmer.orders.reorder', $order) }}"
                               class="btn btn-success"
                               onclick="return confirm('Add all items from this order to your cart?');">
                                <i class="fas fa-redo mr-1"></i> Reorder Items
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="card card-outline card-secondary order-summary-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-receipt mr-2"></i>Order Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">Order Number:</small>
                                <p class="mb-0"><strong>{{ $order->order_number }}</strong></p>
                            </div>
                            <div class="col-6 text-right">
                                <small class="text-muted">Order Date:</small>
                                <p class="mb-0"><strong>{{ $order->created_at->format('M d, Y') }}</strong></p>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">Items:</small>
                                <p class="mb-0"><strong>{{ $order->items->count() }}</strong></p>
                            </div>
                            <div class="col-6 text-right">
                                <small class="text-muted">Total Items:</small>
                                <p class="mb-0"><strong>{{ $order->items->sum('quantity') }}</strong></p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">Subtotal:</small>
                            </div>
                            <div class="col-6 text-right">
                                <small>KES {{ number_format($order->subtotal, 2) }}</small>
                            </div>
                        </div>

                        @if($order->shipping > 0)
                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">Shipping:</small>
                            </div>
                            <div class="col-6 text-right">
                                <small>KES {{ number_format($order->shipping, 2) }}</small>
                            </div>
                        </div>
                        @endif

                        @if($order->tax > 0)
                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">Tax:</small>
                            </div>
                            <div class="col-6 text-right">
                                <small>KES {{ number_format($order->tax, 2) }}</small>
                            </div>
                        </div>
                        @endif

                        <hr>

                        <div class="row">
                            <div class="col-6">
                                <strong>Total:</strong>
                            </div>
                            <div class="col-6 text-right">
                                <strong class="text-success">KES {{ number_format($order->total, 2) }}</strong>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bar
        const progressBar = document.getElementById('order-progress');
        if (progressBar) {
            const width = progressBar.style.width;
            progressBar.style.width = '0';
            setTimeout(() => {
                progressBar.style.width = width;
            }, 500);
        }

        // Initialize tooltips
        if (typeof $ !== 'undefined') {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function showNotification(message, type = 'info') {
        // Check if Toast plugin is available
        if (typeof $ !== 'undefined' && $.fn.toast) {
            $(document).Toasts('create', {
                class: 'bg-' + type,
                title: 'Tracking Update',
                body: message,
                autohide: true,
                delay: 3000,
                position: 'bottomRight'
            });
        }
    }
</script>
@endsection
