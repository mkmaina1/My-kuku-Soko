@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse text-success mr-2"></i>Independent Supplier Dashboard
        </h1>
        <div class="d-flex">
            <span class="badge badge-pill badge-success p-2 mr-2">
                <i class="fas fa-certificate mr-1"></i>Verified Supplier
            </span>
            <a href="#" class="btn btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i>Add Product
            </a>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-1">Welcome, {{ $user->name }}!</h4>
                            <p class="card-text text-muted mb-0">
                                <i class="fas fa-chart-line text-success mr-1"></i>
                                Your inventory is worth Ksh {{ number_format($stats['inventory_value'] ?? 0) }} with {{ $stats['total_products'] ?? 0 }} products in stock.
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="d-flex justify-content-end">
                                <div class="mr-3 text-center">
                                    <div class="text-xs font-weight-bold text-success">Order Fulfillment</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['fulfillment_rate'] ?? '94%' }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-warning">Stock Level</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['stock_level'] ?? 'Optimal' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                            <div class="mt-2">
                                <span class="text-success small">
                                    <i class="fas fa-boxes mr-1"></i>{{ $stats['active_products'] ?? 0 }} active
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Fulfilled -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Orders Fulfilled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['orders_fulfilled'] ?? 0 }}</div>
                            <div class="mt-2">
                                <span class="text-primary small">
                                    <i class="fas fa-truck mr-1"></i>{{ $stats['pending_orders'] ?? 0 }} pending
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] ?? 0 }}</div>
                            <div class="mt-2">
                                @if(($stats['urgent_orders'] ?? 0) > 0)
                                <span class="badge badge-danger mr-1">
                                    {{ $stats['urgent_orders'] }} urgent
                                </span>
                                @endif
                                <span class="text-muted small">Ksh {{ number_format($stats['pending_value'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Ksh {{ number_format($stats['revenue'] ?? 0) }}</div>
                            <div class="mt-2">
                                <span class="text-success small">
                                    <i class="fas fa-arrow-up mr-1"></i>{{ $stats['revenue_growth'] ?? '12%' }}
                                </span>
                                <span class="text-muted small ml-2">This month</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list mr-2"></i>Recent Orders
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">View All Orders</a>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Farmer</th>
                                    <th>Products</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_orders ?? [] as $order)
                                <tr class="{{ $order['is_urgent'] ? 'table-warning' : '' }}">
                                    <td>
                                        <span class="font-weight-bold">#{{ $order['id'] }}</span>
                                        <br>
                                        <small class="text-muted">{{ $order['date'] }}</small>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $order['farmer_name'] }}</div>
                                        <small class="text-muted">{{ $order['farm_location'] }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $order['product_count'] }} items</span>
                                        <small class="d-block text-muted">{{ $order['product_names'] }}</small>
                                    </td>
                                    <td class="font-weight-bold">Ksh {{ number_format($order['amount']) }}</td>
                                    <td>
                                        @if($order['status'] == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                        @elseif($order['status'] == 'processing')
                                        <span class="badge badge-info">Processing</span>
                                        @elseif($order['status'] == 'shipped')
                                        <span class="badge badge-primary">Shipped</span>
                                        @elseif($order['status'] == 'delivered')
                                        <span class="badge badge-success">Delivered</span>
                                        @elseif($order['status'] == 'cancelled')
                                        <span class="badge badge-danger">Cancelled</span>
                                        @endif
                                        @if($order['is_bulk'])
                                        <span class="badge badge-success ml-1">Bulk</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-success">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-muted">No recent orders</p>
                                        <a href="#" class="btn btn-success">
                                            <i class="fas fa-plus mr-1"></i>Add Products
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Inventory -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-success btn-block">
                                <i class="fas fa-box fa-sm mr-1"></i>Add Product
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-truck fa-sm mr-1"></i>Process Orders
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-info btn-block">
                                <i class="fas fa-chart-line fa-sm mr-1"></i>Analytics
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-warehouse fa-sm mr-1"></i>Inventory
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Status -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-boxes mr-2"></i>Inventory Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Feed Supplies</span>
                            <span class="font-weight-bold">{{ $stats['feed_stock'] ?? '65%' }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 65%"
                                 aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Medication</span>
                            <span class="font-weight-bold">{{ $stats['medication_stock'] ?? '85%' }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%"
                                 aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Equipment</span>
                            <span class="font-weight-bold">{{ $stats['equipment_stock'] ?? '45%' }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 45%"
                                 aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between small">
                        <div>
                            <i class="fas fa-exclamation-triangle text-danger mr-1"></i>
                            <span>Low Stock: {{ $stats['low_stock_items'] ?? 3 }}</span>
                        </div>
                        <div>
                            <i class="fas fa-check-circle text-success mr-1"></i>
                            <span>In Stock: {{ $stats['in_stock_items'] ?? 42 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products & Farmers -->
    <div class="row">
        <!-- Top Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star mr-2"></i>Top Selling Products
                    </h6>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus mr-1"></i>Add New
                    </a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($top_products ?? [] as $product)
                        <div class="list-group-item list-group-item-action flex-column align-items-start py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if($product['category'] == 'feed')
                                        <span class="badge badge-warning p-2">
                                            <i class="fas fa-seedling"></i>
                                        </span>
                                        @elseif($product['category'] == 'medication')
                                        <span class="badge badge-danger p-2">
                                            <i class="fas fa-pills"></i>
                                        </span>
                                        @else
                                        <span class="badge badge-info p-2">
                                            <i class="fas fa-tools"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $product['name'] }}</h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-tag mr-1"></i>Ksh {{ number_format($product['price']) }} |
                                            <i class="fas fa-box mr-1 ml-2"></i>{{ $product['stock'] }} in stock
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold">Ksh {{ number_format($product['revenue']) }}</div>
                                    <small class="text-muted">{{ $product['sales'] }} sales</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No products added yet</p>
                            <a href="#" class="btn btn-success">
                                <i class="fas fa-plus mr-1"></i>Add First Product
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Farmers -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-2"></i>Recent Farmers
                    </h6>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus mr-1"></i>Connect
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($recent_farmers ?? [] as $farmer)
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="supplier-farmer-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($farmer['name'], 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $farmer['name'] }}</h6>
                                            <small class="text-muted">{{ $farmer['farm_type'] }}</small>
                                            <div class="small mt-1">
                                                <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                                {{ $farmer['location'] }}
                                            </div>
                                        </div>
                                        <div class="ml-auto text-right">
                                            <div class="font-weight-bold text-success">Ksh {{ number_format($farmer['total_orders']) }}</div>
                                            <small class="text-muted">{{ $farmer['order_count'] }} orders</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">No farmers connected yet</p>
                                <a href="#" class="btn btn-success">
                                    <i class="fas fa-handshake mr-1"></i>Connect with Farmers
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Order Stats -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title text-success mb-1">
                                <i class="fas fa-weight-hanging mr-2"></i>Bulk Order Statistics
                            </h5>
                            <p class="card-text mb-0">
                                You've supplied {{ $stats['bulk_orders_count'] ?? 0 }} bulk orders worth
                                Ksh {{ number_format($stats['bulk_orders_value'] ?? 0) }} this month.
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="d-flex flex-column align-items-end">
                                <a href="#" class="btn btn-success btn-lg mb-2">
                                    <i class="fas fa-chart-bar mr-2"></i>View Analytics
                                </a>
                                <small class="text-muted">Average bulk order: Ksh {{ number_format($stats['avg_bulk_order'] ?? 50000) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Supplier Dashboard -->
<style>
    .role-icon.supplier-icon {
        color: #1cc88a;
        background: rgba(28, 200, 138, 0.1);
        width: 60px;
        height: 60px;
        line-height: 60px;
        border-radius: 50%;
        margin: 0 auto 20px;
        font-size: 24px;
    }

    .bulk-order-badge {
        background: linear-gradient(45deg, #1cc88a, #0f9d58);
        color: white;
        animation: pulse-green 3s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .supplier-product-card {
        transition: all 0.3s;
        border-left: 4px solid #1cc88a;
    }

    .supplier-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .inventory-warning {
        border-left: 4px solid #e74a3b !important;
    }

    .supplier-farmer-avatar {
        font-weight: bold;
        font-size: 16px;
    }

    .revenue-growth {
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 12px;
    }
</style>
@endsection

<!-- Optional JavaScript for Interactive Elements -->
@push('scripts')
<script>
    // Update order status
    function updateOrderStatus(orderId, status) {
        fetch(`/supplier/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order status updated', 'success');
                updateOrderRow(orderId, status);
            }
        });
    }

    // Handle bulk order processing
    function processBulkOrder(orderId) {
        if (confirm('Process this bulk order?')) {
            // Show processing modal
            $('#bulkOrderModal').modal('show');
            // Start order processing
            startOrderProcessing(orderId);
        }
    }

    function startOrderProcessing(orderId) {
        fetch(`/supplier/orders/${orderId}/process`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Bulk order processing started', 'info');
                updateOrderStatus(orderId, 'processing');
            }
        });
    }

    // Inventory management
    function checkLowStock() {
        fetch('/supplier/inventory/low-stock')
            .then(response => response.json())
            .then(data => {
                if (data.low_stock_items.length > 0) {
                    showLowStockAlert(data.low_stock_items);
                }
            });
    }

    // Quick action handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Order status buttons
        document.querySelectorAll('.order-status-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.id;
                const status = this.dataset.status;
                updateOrderStatus(orderId, status);
            });
        });

        // Bulk order buttons
        document.querySelectorAll('.bulk-order-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.id;
                processBulkOrder(orderId);
            });
        });

        // Check low stock on load
        checkLowStock();

        // Initialize inventory alerts
        initializeInventoryAlerts();
    });

    function initializeInventoryAlerts() {
        // Check inventory levels every 30 minutes
        setInterval(checkLowStock, 30 * 60 * 1000);
    }

    function updateOrderRow(orderId, status) {
        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
        if (row) {
            const statusCell = row.querySelector('.order-status');
            if (statusCell) {
                let badgeClass = 'badge-secondary';
                let badgeText = status;

                switch(status) {
                    case 'pending':
                        badgeClass = 'badge-warning';
                        badgeText = 'Pending';
                        break;
                    case 'processing':
                        badgeClass = 'badge-info';
                        badgeText = 'Processing';
                        break;
                    case 'shipped':
                        badgeClass = 'badge-primary';
                        badgeText = 'Shipped';
                        break;
                    case 'delivered':
                        badgeClass = 'badge-success';
                        badgeText = 'Delivered';
                        break;
                    case 'cancelled':
                        badgeClass = 'badge-danger';
                        badgeText = 'Cancelled';
                        break;
                }

                statusCell.innerHTML = `<span class="badge ${badgeClass}">${badgeText}</span>`;
            }
        }
    }

    function showLowStockAlert(items) {
        let message = `Low stock alert for ${items.length} items:\n`;
        items.forEach(item => {
            message += `- ${item.name}: ${item.stock} remaining\n`;
        });

        // Use toastr or custom alert
        toastr.warning(message, 'Low Stock Alert', {timeOut: 10000});
    }

    function showNotification(message, type = 'info') {
        toastr[type](message);
    }
</script>
@endpush
