@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<!-- <div class="content-wrapper"> -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order Details: {{ $order->order_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('supplier.orders.index') }}">Orders</a></li> {{-- CHANGED --}}
                        <li class="breadcrumb-item active">Order Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Order Summary -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-receipt mr-1"></i>
                                Order Information
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-{{ $order->status_badge }} badge-lg p-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Order Number:</dt>
                                        <dd class="col-sm-8">{{ $order->order_number }}</dd>

                                        <dt class="col-sm-4">Order Date:</dt>
                                        <dd class="col-sm-8">{{ $order->created_at->format('F d, Y h:i A') }}</dd>

                                        <dt class="col-sm-4">Payment Method:</dt>
                                        <dd class="col-sm-8">
                                            <i class="{{ $order->payment_method_icon }} mr-2"></i>
                                            {{ $order->payment_method_text }}
                                        </dd>

                                        <dt class="col-sm-4">Shipping Method:</dt>
                                        <dd class="col-sm-8">Standard Delivery</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Customer:</dt>
                                        <dd class="col-sm-8">
                                            @if($order->user)
                                                <div class="user-block">
                                                    <span class="username">{{ $order->user->name }}</span>
                                                    <span class="description">
                                                        <i class="fas fa-envelope mr-1"></i>{{ $order->user->email }}<br>
                                                        <i class="fas fa-phone mr-1"></i>{{ $order->user->phone }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-danger">Customer Not Found</span>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Shipping Address:</dt>
                                        <dd class="col-sm-8">
                                            <address>
                                                {{ $order->shipping_address }}<br>
                                                @if($order->user && $order->user->address)
                                                    {{ $order->user->address }}<br>
                                                @endif
                                            </address>
                                        </dd>

                                        <dt class="col-sm-4">Notes:</dt>
                                        <dd class="col-sm-8">
                                            {{ $order->notes ?? 'No notes provided' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Amounts -->
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-tag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Subtotal</span>
                            <span class="info-box-number">Ksh {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-truck"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Shipping</span>
                            <span class="info-box-number">Ksh {{ number_format($order->shipping, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tax</span>
                            <span class="info-box-number">Ksh {{ number_format($order->tax, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Amount</span>
                            <span class="info-box-number">Ksh {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-boxes mr-1"></i>
                                Order Items ({{ $order->items->count() }})
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Total</th>
                                            <th>Supplier</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="img-circle img-size-32 mr-3">
                                                    @else
                                                        <div class="img-circle img-size-32 bg-secondary d-flex align-items-center justify-content-center mr-3">
                                                            <i class="fas fa-box text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>
                                                            @if($item->product)
                                                                {{ $item->product->name }}
                                                            @else
                                                                <span class="text-danger">Product not found</span>
                                                            @endif
                                                        </strong><br>
                                                        <small class="text-muted">
                                                            SKU: {{ $item->product->sku ?? 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <strong>Ksh {{ number_format($item->price, 2) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary" style="font-size: 1em;">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-success">
                                                    Ksh {{ number_format($item->price * $item->quantity, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                @if($item->product && $item->product->supplier)
                                                    {{ $item->product->supplier->name }}
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td colspan="3" class="text-right">
                                                <strong>Grand Total:</strong>
                                            </td>
                                            <td class="text-center" colspan="2">
                                                <h4 class="mb-0 text-success">Ksh {{ number_format($order->total, 2) }}</h4>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Update Order Status</h5>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Change Status <i class="fas fa-caret-down"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <h6 class="dropdown-header">Select New Status</h6>
                                            <a class="dropdown-item status-action" href="#" data-status="processing">
                                                <i class="fas fa-cog mr-2 text-primary"></i> Mark as Processing
                                            </a>
                                            <a class="dropdown-item status-action" href="#" data-status="shipped">
                                                <i class="fas fa-shipping-fast mr-2 text-info"></i> Mark as Shipped
                                            </a>
                                            <a class="dropdown-item status-action" href="#" data-status="delivered">
                                                <i class="fas fa-check-circle mr-2 text-success"></i> Mark as Delivered
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item status-action text-danger" href="#" data-status="cancelled">
                                                <i class="fas fa-times-circle mr-2"></i> Cancel Order
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('supplier.orders.index') }}" class="btn btn-default"> {{-- CHANGED --}}
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                                    </a>
                                    <button class="btn btn-primary" onclick="window.print()">
                                        <i class="fas fa-print mr-1"></i> Print Invoice
                                    </button>
                                    <a href="#" class="btn btn-success">
                                        <i class="fas fa-download mr-1"></i> Export
                                    </a>
                                </div>
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
                <p>Update order status to <strong id="newStatus"></strong>?</p>
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
    let currentStatus = null;

    // Status update
    $('.status-action').on('click', function(e) {
        e.preventDefault();
        currentStatus = $(this).data('status');
        const statusText = $(this).text().trim();
        $('#newStatus').text(statusText.replace('Mark as ', '').replace('Cancel ', ''));
        $('#statusUpdateModal').modal('show');
    });

    // Confirm status update
    $('#confirmStatusUpdate').on('click', function() {
        const notes = $('#statusNotes').val();

        $.ajax({
            url: '{{ route("supplier.orders.updateStatus", $order) }}', // CHANGED
            type: 'PUT',
            data: {
                status: currentStatus,
                notes: notes,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#statusUpdateModal').modal('hide');
                $('#statusNotes').val('');

                toastr.success(response.message);

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
