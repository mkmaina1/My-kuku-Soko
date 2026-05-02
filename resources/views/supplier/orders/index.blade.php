@extends('layouts.app')

@section('title', $title ?? 'Supplier Orders')

@section('content')
<!-- <div class="content-wrapper"> -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title ?? 'Supplier Orders' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Status Filter Tabs -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="order-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ !isset($statusFilter) && !isset($orderTypeFilter) ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.index') }}">
                                        <i class="fas fa-list mr-1"></i> All Orders
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'pending' ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.pending') }}">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'processing' ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.processing') }}">
                                        <i class="fas fa-cog mr-1"></i> Processing
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'shipped' ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.shipped') }}">
                                        <i class="fas fa-shipping-fast mr-1"></i> Shipped
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'delivered' ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.delivered') }}">
                                        <i class="fas fa-check-circle mr-1"></i> Delivered
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($statusFilter) && $statusFilter == 'cancelled' ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.cancelled') }}">
                                        <i class="fas fa-times-circle mr-1"></i> Cancelled
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($orderTypeFilter) && $orderTypeFilter == 'bulk' ? 'active' : '' }}"
                                       href="{{ route('supplier.orders.bulk') }}">
                                        <i class="fas fa-boxes mr-1"></i> Bulk Orders
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if(isset($stats))
            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total'] ?? 0 }}</h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('supplier.orders.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['pending'] ?? 0 }}</h3>
                            <p>Pending</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('supplier.orders.pending') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $stats['processing'] ?? 0 }}</h3>
                            <p>Processing</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <a href="{{ route('supplier.orders.processing') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['shipped'] ?? 0 }}</h3>
                            <p>Shipped</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <a href="{{ route('supplier.orders.shipped') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['delivered'] ?? 0 }}</h3>
                            <p>Delivered</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="{{ route('supplier.orders.delivered') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['cancelled'] ?? 0 }}</h3>
                            <p>Cancelled</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <a href="{{ route('supplier.orders.cancelled') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($totalRevenue))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible">
                        <h5><i class="icon fas fa-coins mr-2"></i> Total Revenue</h5>
                        <h3 class="mb-0">Ksh {{ number_format($totalRevenue, 2) }}</h3>
                        <small>From delivered orders only</small>
                    </div>
                </div>
            </div>
            @endif

            <!-- Orders Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title ?? 'All Orders' }}</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong><br>
                                            <small class="text-muted">#{{ $order->id }}</small>
                                        </td>
                                        <td>
                                            @if($order->user)
                                                <div class="user-block">
                                                    <span class="username">{{ $order->user->name }}</span>
                                                    <span class="description">{{ $order->user->email }}</span>
                                                </div>
                                            @else
                                                <span class="text-danger">Customer Not Found</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $order->items->count() }} item(s)
                                            </span>
                                        </td>
                                        <td>
                                            <strong>Ksh {{ number_format($order->total, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $order->status_badge }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('M d, Y') }}<br>
                                            <small>{{ $order->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('supplier.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ route('supplier.orders.show', $order) }}">
                                                        <i class="fas fa-eye mr-2"></i> View Details
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <h6 class="dropdown-header">Update Status</h6>
                                                    <a class="dropdown-item status-update" href="#" data-id="{{ $order->id }}" data-status="processing">
                                                        <i class="fas fa-cog mr-2"></i> Mark as Processing
                                                    </a>
                                                    <a class="dropdown-item status-update" href="#" data-id="{{ $order->id }}" data-status="shipped">
                                                        <i class="fas fa-shipping-fast mr-2"></i> Mark as Shipped
                                                    </a>
                                                    <a class="dropdown-item status-update" href="#" data-id="{{ $order->id }}" data-status="delivered">
                                                        <i class="fas fa-check-circle mr-2"></i> Mark as Delivered
                                                    </a>
                                                    <a class="dropdown-item status-update text-danger" href="#" data-id="{{ $order->id }}" data-status="cancelled">
                                                        <i class="fas fa-times-circle mr-2"></i> Cancel Order
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h4>No orders found</h4>
                                            <p class="text-muted">You don't have any orders yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix">
                            <div class="float-right">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update this order's status to <strong id="newStatus"></strong>?</p>
                <div class="form-group">
                    <label for="statusNotes">Notes (Optional)</label>
                    <textarea class="form-control" id="statusNotes" rows="3" placeholder="Add any notes about this status change..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentOrderId = null;
    let currentStatus = null;

    // Status update modal
    $('.status-update').on('click', function(e) {
        e.preventDefault();
        currentOrderId = $(this).data('id');
        currentStatus = $(this).data('status');

        const statusText = $(this).text().trim();
        $('#newStatus').text(statusText.replace('Mark as ', '').replace('Cancel ', ''));

        $('#statusUpdateModal').modal('show');
    });

    // Confirm status update
    $('#confirmStatusUpdate').on('click', function() {
        const notes = $('#statusNotes').val();

        // Use the correct route with supplier prefix
        $.ajax({
            url: `/supplier/orders/${currentOrderId}/status`,
            type: 'PUT',
            data: {
                status: currentStatus,
                notes: notes,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#statusUpdateModal').modal('hide');
                $('#statusNotes').val('');

                // Show success message
                toastr.success(response.message);

                // Reload page after 1.5 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.error || 'An error occurred');
            }
        });
    });
});
</script>
@endpush
