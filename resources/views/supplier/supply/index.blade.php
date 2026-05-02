@extends('layouts.app')

@section('title', $title ?? 'Order Management - Supplier')

@section('styles')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* Status Badges */
    .order-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    .status-processing {
        background-color: #cce5ff;
        color: #004085;
        border: 1px solid #b8daff;
    }
    .status-shipped {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    .status-delivered {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }

    /* Revenue Card */
    .revenue-card {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .revenue-card .info-box-icon {
        background: rgba(255,255,255,0.2);
        border-radius: 10px 0 0 10px;
    }

    /* Table Styling */
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr {
        transition: background-color 0.2s;
    }
    .table tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }

    /* Order Type Badge */
    .order-type-badge {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 12px;
    }

    /* Filter Buttons */
    .filter-btn-group .btn {
        border-radius: 20px;
        margin: 0 2px;
        padding: 6px 15px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    .filter-btn-group .btn.active {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Bulk Actions Panel */
    #bulkActionsPanel {
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    /* Customer Info */
    .customer-name {
        font-weight: 600;
        color: #333;
    }
    .customer-email {
        font-size: 0.8rem;
    }

    /* Action Buttons */
    .action-buttons .btn {
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.8rem;
    }

    /* Checkbox Styling */
    .order-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .order-checkbox:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Card Headers */
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.25rem;
    }
    .card-header .card-title {
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
        margin: 0;
    }
</style>
@endsection

@section('content')
<!-- <div class="content-wrapper"> -->
    <!-- Content Header -->
    <!-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title ?? 'Order Management' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title ?? 'Orders' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Statistics Cards -->
            @if(!isset($statusFilter) && !isset($orderTypeFilter))
            <div class="row">
                <!-- Total Orders -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                    <div class="info-box shadow-sm stat-card">
                        <span class="info-box-icon bg-secondary elevation-1">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Orders</span>
                            <span class="info-box-number">{{ $stats['total'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                    <div class="info-box shadow-sm stat-card">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $stats['pending'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Processing -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                    <div class="info-box shadow-sm stat-card">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-cog"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Processing</span>
                            <span class="info-box-number">{{ $stats['processing'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipped -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                    <div class="info-box shadow-sm stat-card">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-shipping-fast"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Shipped</span>
                            <span class="info-box-number">{{ $stats['shipped'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivered -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                    <div class="info-box shadow-sm stat-card">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Delivered</span>
                            <span class="info-box-number">{{ $stats['delivered'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Cancelled -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                    <div class="info-box shadow-sm stat-card">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Cancelled</span>
                            <span class="info-box-number">{{ $stats['cancelled'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                @if(isset($totalRevenue))
                <div class="col-12 col-md-6 mb-3">
                    <div class="info-box shadow-sm revenue-card">
                        <span class="info-box-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Revenue</span>
                            <span class="info-box-number">Ksh {{ number_format($totalRevenue, 2) }}</span>
                            <small>From delivered orders</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Filters Card -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Filter Orders</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="filter-btn-group" role="group">
                                        <a href="{{ route('supplier.orders.index') }}"
                                           class="btn {{ !isset($statusFilter) && !isset($orderTypeFilter) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                            All Orders
                                        </a>
                                        <a href="{{ route('supplier.orders.pending') }}"
                                           class="btn {{ ($statusFilter ?? '') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </a>
                                        <a href="{{ route('supplier.orders.processing') }}"
                                           class="btn {{ ($statusFilter ?? '') == 'processing' ? 'btn-info' : 'btn-outline-info' }}">
                                            <i class="fas fa-cog mr-1"></i> Processing
                                        </a>
                                        <a href="{{ route('supplier.orders.shipped') }}"
                                           class="btn {{ ($statusFilter ?? '') == 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">
                                            <i class="fas fa-shipping-fast mr-1"></i> Shipped
                                        </a>
                                        <a href="{{ route('supplier.orders.delivered') }}"
                                           class="btn {{ ($statusFilter ?? '') == 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
                                            <i class="fas fa-check-circle mr-1"></i> Delivered
                                        </a>
                                        <a href="{{ route('supplier.orders.cancelled') }}"
                                           class="btn {{ ($statusFilter ?? '') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelled
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('supplier.orders.bulk') }}"
                                           class="btn {{ ($orderTypeFilter ?? '') == 'bulk' ? 'btn-success' : 'btn-outline-success' }}">
                                            <i class="fas fa-boxes mr-1"></i> Bulk Orders
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Panel -->
            <div class="row mb-3 d-none" id="bulkActionsPanel">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-tasks fa-2x text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Bulk Actions</h5>
                                    <div class="d-flex align-items-center">
                                        <span class="mr-3"><strong id="selectedCount">0</strong> order(s) selected</span>
                                        <select class="form-control form-control-sm mr-2" style="width: auto; max-width: 200px;" id="bulkStatusAction">
                                            <option value="">Update Status To...</option>
                                            <option value="processing">📦 Processing</option>
                                            <option value="shipped">🚚 Shipped</option>
                                            <option value="delivered">✅ Delivered</option>
                                            <option value="cancelled">❌ Cancelled</option>
                                        </select>
                                        <button class="btn btn-sm btn-primary mr-2" id="applyBulkAction">
                                            <i class="fas fa-check mr-1"></i> Apply
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" id="clearSelection">
                                            <i class="fas fa-times mr-1"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Orders List</h3>
                            <div class="card-tools">
                                <form method="GET" class="form-inline">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Search orders..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50" class="text-center">
                                                <input type="checkbox" id="selectAllOrders" class="order-checkbox">
                                            </th>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr data-order-id="{{ $order->id }}" class="order-row">
                                                <td class="text-center" onclick="event.stopPropagation();">
                                                    <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                                </td>
                                                <td>
                                                    <div class="order-id">
                                                        <strong class="text-primary">#{{ $order->order_number ?? $order->id }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($order->customer)
                                                        <div class="customer-name">{{ $order->customer->name }}</div>
                                                        <div class="customer-email text-muted">
                                                            <small>{{ $order->customer->email }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Guest Customer</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $supplierItems = $order->items->filter(function($item) {
                                                            return $item->product && $item->product->supplier_id == auth()->id();
                                                        });
                                                    @endphp
                                                    <div class="font-weight-bold">{{ $supplierItems->count() }} item(s)</div>
                                                    <small class="text-muted">
                                                        @foreach($supplierItems->take(2) as $item)
                                                            {{ $item->product->name }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                        @if($supplierItems->count() > 2)
                                                            +{{ $supplierItems->count() - 2 }} more
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong class="text-success">Ksh {{ number_format($supplierItems->sum(function($item) {
                                                        return $item->quantity * $item->price;
                                                    }), 2) }}</strong>
                                                </td>
                                                <td>
                                                    <span class="order-status status-{{ $order->status }}">
                                                        <i class="fas fa-circle mr-1" style="font-size: 0.5rem;"></i>
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge order-type-badge {{ $order->order_type == 'bulk' ? 'badge-success' : 'badge-info' }}">
                                                        {{ ucfirst($order->order_type) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group action-buttons">
                                                        <a href="{{ route('supplier.orders.show', $order) }}"
                                                           class="btn btn-info btn-sm" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split"
                                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                                                <i class="fas fa-cog text-info mr-2"></i>Mark as Processing
                                                            </a>
                                                            <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
                                                                <i class="fas fa-shipping-fast text-primary mr-2"></i>Mark as Shipped
                                                            </a>
                                                            <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'delivered')">
                                                                <i class="fas fa-check-circle text-success mr-2"></i>Mark as Delivered
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger" href="#" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                                                <i class="fas fa-times-circle mr-2"></i>Cancel Order
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9">
                                                    <div class="empty-state">
                                                        <i class="fas fa-clipboard-list empty-state-icon text-muted"></i>
                                                        <h4 class="text-muted">No orders found</h4>
                                                        <p class="text-muted mb-3">
                                                            @if(isset($statusFilter))
                                                                You have no {{ $statusFilter }} orders.
                                                            @elseif(isset($orderTypeFilter))
                                                                You have no {{ $orderTypeFilter }} orders.
                                                            @else
                                                                You haven't received any orders yet.
                                                            @endif
                                                        </p>
                                                        <a href="{{ route('supplier.orders.index') }}" class="btn btn-primary">
                                                            <i class="fas fa-redo mr-1"></i> View All Orders
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($orders->count() > 0)
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info">
                                        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="float-right">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i> Update Order Status
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="orderId" name="order_id">
                    <div class="form-group">
                        <label for="statusSelect" class="font-weight-bold">Status</label>
                        <select class="form-control select2" id="statusSelect" name="status" style="width: 100%;" required>
                            <option value="pending" data-color="#ffc107">🟡 Pending</option>
                            <option value="processing" data-color="#17a2b8">🔵 Processing</option>
                            <option value="shipped" data-color="#007bff">🚚 Shipped</option>
                            <option value="delivered" data-color="#28a745">✅ Delivered</option>
                            <option value="cancelled" data-color="#dc3545">❌ Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusNotes" class="font-weight-bold">Notes <small class="text-muted">(Optional)</small></label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3"
                                  placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">
                    <i class="fas fa-check mr-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#statusSelect').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize DataTable
    const dataTable = $('.table').DataTable({
        "paging": false,
        "searching": false,
        "info": false,
        "responsive": true,
        "order": [[3, 'desc']],
        "columnDefs": [
            {
                "orderable": false,
                "targets": [0, 8]
            },
            {
                "className": "dt-body-center",
                "targets": [0, 8]
            }
        ],
        "language": {
            "emptyTable": "No orders available"
        }
    });

    // Bulk selection functionality
    let selectedOrders = new Set();

    $('#selectAllOrders').change(function() {
        const isChecked = $(this).prop('checked');
        $('.order-checkbox').prop('checked', isChecked);

        selectedOrders.clear();
        if (isChecked) {
            $('.order-checkbox').each(function() {
                selectedOrders.add($(this).val());
            });
        }

        updateBulkActionsPanel();
    });

    $('.order-checkbox').change(function(e) {
        e.stopPropagation();
        const orderId = $(this).val();

        if ($(this).prop('checked')) {
            selectedOrders.add(orderId);
        } else {
            selectedOrders.delete(orderId);
            $('#selectAllOrders').prop('checked', false);
        }

        updateBulkActionsPanel();
    });

    function updateBulkActionsPanel() {
        const count = selectedOrders.size;
        $('#selectedCount').text(count);

        if (count > 0) {
            $('#bulkActionsPanel').removeClass('d-none');
            $('html, body').animate({
                scrollTop: $('#bulkActionsPanel').offset().top - 100
            }, 300);
        } else {
            $('#bulkActionsPanel').addClass('d-none');
        }
    }

    $('#clearSelection').click(function() {
        selectedOrders.clear();
        $('.order-checkbox').prop('checked', false);
        $('#selectAllOrders').prop('checked', false);
        updateBulkActionsPanel();
    });

    // Bulk status update
    $('#applyBulkAction').click(function() {
        const status = $('#bulkStatusAction').val();

        if (!status) {
            toastr.warning('Please select a status');
            return;
        }

        if (selectedOrders.size === 0) {
            toastr.warning('Please select at least one order');
            return;
        }

        Swal.fire({
            title: 'Confirm Bulk Update',
            text: `Are you sure you want to update ${selectedOrders.size} order(s) to "${status}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("supplier.orders.bulkUpdateStatus") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_ids: Array.from(selectedOrders),
                        status: status
                    },
                    beforeSend: function() {
                        $('#applyBulkAction').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        toastr.error('An error occurred while updating orders');
                        $('#applyBulkAction').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Apply');
                    }
                });
            }
        });
    });

    // Individual order status update
    window.updateOrderStatus = function(orderId, status) {
        $('#orderId').val(orderId);
        $('#statusSelect').val(status).trigger('change');
        $('#statusNotes').val('');
        $('#statusUpdateModal').modal('show');
    };

    $('#confirmStatusUpdate').click(function() {
        const orderId = $('#orderId').val();
        const formData = {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            status: $('#statusSelect').val(),
            notes: $('#statusNotes').val()
        };

        $.ajax({
            url: '{{ route("supplier.orders.updateStatus", ["order" => ":orderId"]) }}'.replace(':orderId', orderId),
            method: 'POST',
            data: formData,
            beforeSend: function() {
                $('#confirmStatusUpdate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#statusUpdateModal').modal('hide');
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                console.error(xhr);
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                } else {
                    toastr.error('An error occurred while updating status');
                }
                $('#confirmStatusUpdate').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Update Status');
            }
        });
    });

    // Row click functionality
    $('tr.order-row').click(function(e) {
        if ($(e.target).is('input[type="checkbox"]') ||
            $(e.target).is('button') ||
            $(e.target).is('a') ||
            $(e.target).closest('button').length ||
            $(e.target).closest('a').length) {
            return;
        }

        const orderId = $(this).data('order-id');
        window.location.href = '{{ route("supplier.orders.show", ":orderId") }}'.replace(':orderId', orderId);
    });

    // Add keyboard navigation
    $(document).keydown(function(e) {
        if (e.key === 'Escape') {
            $('.modal').modal('hide');
        }
    });

    // Toastr notifications
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if(session('info'))
        toastr.info('{{ session('info') }}');
    @endif

    // Initialize tooltips
    $('[title]').tooltip({
        trigger: 'hover',
        placement: 'top'
    });
});
</script>
@endsection
