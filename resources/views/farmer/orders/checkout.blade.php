@extends('layouts.app')

@section('title', 'Checkout')

@section('styles')
<style>
    .checkout-progress {
        position: relative;
        margin-bottom: 30px;
    }
    .checkout-progress:before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e9ecef;
        z-index: 1;
    }
    .progress-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .progress-step.active .step-circle {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
        transform: scale(1.1);
    }
    .progress-step.completed .step-circle {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: white;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .step-label {
        font-size: 0.85rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .progress-step.active .step-label {
        color: #007bff;
        font-weight: bold;
    }
    .progress-step.completed .step-label {
        color: #28a745;
    }

    .payment-method-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    .payment-method-card:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    .payment-method-card.selected {
        border-color: #007bff;
        background-color: #e7f3ff;
        box-shadow: 0 2px 10px rgba(0, 123, 255, 0.1);
    }
    .payment-method-icon {
        font-size: 24px;
        margin-right: 15px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    .payment-method-mpesa .payment-method-icon {
        background: linear-gradient(135deg, #00B300, #008000);
        color: white;
    }
    .payment-method-cash .payment-method-icon {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    .payment-method-card .payment-method-icon {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    .payment-method-agent .payment-method-icon {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }

    .address-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    .address-card:hover {
        border-color: #28a745;
        transform: translateY(-2px);
    }
    .address-card.selected {
        border-color: #28a745;
        background-color: #f0f9f0;
        box-shadow: 0 2px 10px rgba(40, 167, 69, 0.1);
    }

    .product-checkout-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .order-summary-card {
        position: sticky;
        top: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }

    .mpesa-input-group {
        max-width: 300px;
        margin: 15px 0;
    }

    .mpesa-info-box {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-left: 4px solid #00B300;
        border-radius: 5px;
        padding: 15px;
        margin: 15px 0;
    }

    .btn-place-order {
        font-size: 1.1rem;
        font-weight: 600;
        padding: 12px;
        transition: all 0.3s ease;
    }
    .btn-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .estimated-delivery-box {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .need-help-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    /* Payment Modal Styles */
    .payment-modal .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }
    .payment-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
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
</style>
@endsection

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-shopping-cart mr-2"></i>Checkout
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}"><i class="fas fa-tachometer-alt mr-1"></i>Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.cart.index') }}"><i class="fas fa-shopping-cart mr-1"></i>Cart</a></li>
                    <li class="breadcrumb-item active"><i class="fas fa-credit-card mr-1"></i>Checkout</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <!-- Checkout Progress -->
        <div class="card card-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center checkout-progress">
                    <div class="progress-step completed">
                        <div class="step-circle">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="step-label">Cart Review</div>
                    </div>
                    <div class="progress-step active">
                        <div class="step-circle">2</div>
                        <div class="step-label">Checkout Details</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-circle">3</div>
                        <div class="step-label">Payment</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-circle">4</div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form - Using AJAX for M-Pesa -->
        <form id="checkoutForm" method="POST">
            @csrf
            <div class="row">
                <!-- Left Column: Order Details & Shipping -->
                <div class="col-lg-8">
                    <!-- Order Summary -->
                    <div class="card card-info mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-boxes mr-2"></i>
                                Order Summary
                                <span class="badge badge-primary ml-2">{{ $cart->items->count() }} items</span>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @if($cart->items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 70px" class="text-center">Image</th>
                                            <th>Product Details</th>
                                            <th class="text-center" style="width: 100px">Price</th>
                                            <th class="text-center" style="width: 80px">Qty</th>
                                            <th class="text-right" style="width: 120px">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart->items as $item)
                                        <tr>
                                            <td class="text-center">
                                                @if($item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="product-checkout-img">
                                                @else
                                                    <div class="product-checkout-img bg-light d-flex align-items-center justify-content-center mx-auto">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-store-alt mr-1"></i>Seller: {{ $item->product->supplier->name ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light">KES {{ number_format($item->product->price, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-primary px-3">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                KES {{ number_format($item->product->price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                <p class="text-muted mb-4">Your cart is empty</p>
                                <a href="{{ route('farmer.marketplace.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag mr-1"></i> Continue Shopping
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card card-success mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Shipping Address
                            </h3>
                            <a href="{{ route('farmer.addresses.create') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-plus mr-1"></i> Add New Address
                            </a>
                        </div>
                        <div class="card-body">
                            @if($addresses->count() > 0)
                            <div class="row">
                                @foreach($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="address-card h-100" onclick="selectAddress({{ $address->id }})">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio"
                                                   name="shipping_address_id"
                                                   id="address-{{ $address->id }}"
                                                   value="{{ $address->id }}"
                                                   {{ $address->is_default ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label w-100" for="address-{{ $address->id }}">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <strong class="text-primary">{{ $address->name ?? 'Address' }}</strong>
                                                    @if($address->is_default)
                                                        <span class="badge badge-success">Default</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 text-muted small">
                                                    <i class="fas fa-map-pin mr-1"></i>{{ $address->address }}<br>
                                                    <i class="fas fa-city mr-1"></i>{{ $address->city }}, {{ $address->state }}<br>
                                                    <i class="fas fa-phone mr-1"></i>{{ $address->phone }}
                                                </p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-map-marker-alt fa-4x text-muted"></i>
                                </div>
                                <p class="text-muted mb-3">No shipping addresses found</p>
                                <a href="{{ route('farmer.addresses.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i> Add Shipping Address
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- M-Pesa Payment Section -->
                    <div class="card card-warning mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-mobile-alt mr-2"></i>
                                M-Pesa Payment
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mpesa-info-box">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt fa-3x text-success mr-3"></i>
                                    <div>
                                        <h5 class="mb-1">Secure M-Pesa Payment</h5>
                                        <p class="mb-0 small text-muted">You'll receive an STK push prompt on your phone to enter your PIN</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_number" class="font-weight-bold">
                                    <i class="fas fa-mobile-alt mr-1 text-success"></i>
                                    M-Pesa Phone Number
                                </label>
                                <div class="input-group mpesa-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+254</span>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number"
                                           name="phone_number"
                                           placeholder="712345678"
                                           value="{{ old('phone_number', Auth::user()->phone ?? '') }}"
                                           required>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Enter the M-Pesa registered phone number (without leading 0)
                                </small>
                            </div>

                            <!-- Order Notes -->
                            <div class="form-group mt-4">
                                <label for="notes">Order Notes (Optional)</label>
                                <textarea class="form-control"
                                          id="notes"
                                          name="notes"
                                          rows="2"
                                          placeholder="Any special instructions for delivery?"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary & Actions -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <!-- Order Summary -->
                        <div class="card card-primary order-summary-card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-receipt mr-2"></i>
                                    Order Total
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="order-summary">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal:</span>
                                        <span class="font-weight-bold">KES {{ number_format($cart->subtotal, 2) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Shipping:</span>
                                        <span class="font-weight-bold">KES {{ number_format($cart->shipping_cost ?? 200, 2) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Tax (16% VAT):</span>
                                        <span class="font-weight-bold">KES {{ number_format($cart->tax ?? ($cart->subtotal * 0.16), 2) }}</span>
                                    </div>

                                    <hr class="my-3">

                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="h5 mb-0">Total:</span>
                                        <span class="h4 mb-0 text-success font-weight-bold">
                                            KES {{ number_format($cart->total, 2) }}
                                        </span>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg btn-block btn-place-order" id="placeOrderBtn">
                                        <i class="fas fa-lock mr-1"></i>
                                        Place Order & Pay
                                    </button>

                                    <div class="text-center mt-3">
                                        <a href="{{ route('farmer.cart.index') }}" class="text-primary">
                                            <i class="fas fa-arrow-left mr-1"></i> Return to cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estimated Delivery -->
                        <div class="estimated-delivery-box mb-4">
                            <div class="d-flex">
                                <i class="fas fa-shipping-fast fa-2x mr-3 text-primary"></i>
                                <div>
                                    <h5 class="mb-1">Estimated Delivery</h5>
                                    <p class="mb-1 font-weight-bold">3-5 Business Days</p>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        After payment confirmation
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Need Help? -->
                        <div class="need-help-box">
                            <h5 class="mb-3">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                Need Help?
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-phone text-primary mr-2"></i>
                                    <strong>0700 000 000</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-envelope text-primary mr-2"></i>
                                    <strong>support@kukusoko.com</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Payment Status Modal -->
<div class="modal fade payment-modal" id="paymentModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    Payment Processing
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="payment-status-icon pending" id="paymentStatusIcon">
                    <i class="fas fa-spinner fa-pulse"></i>
                </div>
                <h4 id="paymentMessage">Please check your phone</h4>
                <p class="text-muted" id="paymentDetails">STK push prompt has been sent to your phone</p>

                <div class="alert alert-warning mt-3" id="paymentAlert" style="display: none;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="alertMessage"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" id="checkStatusBtn">
                    <i class="fas fa-sync-alt mr-2"></i> Check Status
                </button>
                <a href="{{ route('farmer.orders.index') }}" class="btn btn-success" id="viewOrdersBtn" style="display: none;">
                    <i class="fas fa-list mr-2"></i> View Orders
                </a>
                <button type="button" class="btn btn-danger" id="cancelPaymentBtn">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let checkoutRequestId = null;
    let statusCheckInterval = null;

    // Initialize address selection
    const defaultAddress = $('input[name="shipping_address_id"]:checked');
    if (defaultAddress.length) {
        selectAddress(defaultAddress.val());
    }

    // Handle form submission
    $('#checkoutForm').on('submit', function(e) {
        e.preventDefault();

        // Validate shipping address
        if (!$('input[name="shipping_address_id"]:checked').length) {
            toastr.error('Please select a shipping address');
            return;
        }

        // Validate phone number
        let phone = $('#phone_number').val();
        if (!phone || !phone.match(/^[0-9]{9,12}$/)) {
            toastr.error('Please enter a valid M-Pesa phone number');
            return;
        }

        // Disable button
        $('#placeOrderBtn').prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');

        // Submit order
        $.ajax({
            url: '{{ route("farmer.orders.process-checkout") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    // Show payment modal
                    $('#paymentModal').modal('show');

                    if (response.payment) {
                        checkoutRequestId = response.payment.checkout_request_id;
                        $('#paymentMessage').text('Please check your phone');
                        $('#paymentDetails').text('Amount: KES ' + response.order.total.toLocaleString());

                        // Start checking payment status
                        startStatusCheck(checkoutRequestId);
                    } else {
                        // Order created but payment initiation failed
                        $('#paymentStatusIcon').html('<i class="fas fa-exclamation-triangle text-warning"></i>');
                        $('#paymentMessage').text('Payment Initiation Failed');
                        $('#paymentDetails').text(response.payment_error || 'Please try paying later');
                        $('#paymentAlert').show();
                        $('#alertMessage').text('Order created but payment failed to initiate. You can retry payment from your orders page.');
                        $('#checkStatusBtn').hide();
                        $('#viewOrdersBtn').show();
                        $('#cancelPaymentBtn').text('Close');
                    }
                } else {
                    toastr.error(response.message);
                    $('#placeOrderBtn').prop('disabled', false)
                        .html('<i class="fas fa-lock mr-2"></i> Place Order & Pay');
                }
            },
            error: function(xhr) {
                let message = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                $('#placeOrderBtn').prop('disabled', false)
                    .html('<i class="fas fa-lock mr-2"></i> Place Order & Pay');
            }
        });
    });

    // Start checking payment status
    function startStatusCheck(checkoutId) {
        statusCheckInterval = setInterval(function() {
            checkPaymentStatus(checkoutId);
        }, 3000); // Check every 3 seconds
    }

    // Check payment status
    function checkPaymentStatus(checkoutId) {
        $.ajax({
            url: '{{ url("/api/mpesa/status") }}/' + checkoutId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    if (response.status === 'completed') {
                        clearInterval(statusCheckInterval);
                        $('#paymentStatusIcon').html('<i class="fas fa-check-circle text-success"></i>');
                        $('#paymentMessage').text('Payment Successful!');
                        $('#paymentDetails').text('Receipt: ' + (response.mpesa_receipt || 'N/A'));
                        $('#checkStatusBtn').hide();
                        $('#viewOrdersBtn').show();
                        $('#cancelPaymentBtn').hide();

                        toastr.success('Payment completed successfully!');

                        // Redirect to orders page after 3 seconds
                        setTimeout(function() {
                            window.location.href = '{{ route("farmer.orders.index") }}';
                        }, 3000);
                    } else if (response.status === 'failed') {
                        clearInterval(statusCheckInterval);
                        $('#paymentStatusIcon').html('<i class="fas fa-times-circle text-danger"></i>');
                        $('#paymentMessage').text('Payment Failed');
                        $('#paymentDetails').text('Please try again from your orders page');
                        $('#paymentAlert').show();
                        $('#alertMessage').text('Your payment was not completed.');
                        $('#checkStatusBtn').hide();
                        $('#viewOrdersBtn').show();

                        toastr.error('Payment failed. Please try again.');
                    }
                }
            },
            error: function() {
                // Silent fail - continue checking
            }
        });
    }

    // Check status manually
    $('#checkStatusBtn').on('click', function() {
        if (checkoutRequestId) {
            checkPaymentStatus(checkoutRequestId);
        }
    });

    // Cancel payment
    $('#cancelPaymentBtn').on('click', function() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        $('#paymentModal').modal('hide');
        $('#placeOrderBtn').prop('disabled', false)
            .html('<i class="fas fa-lock mr-2"></i> Place Order & Pay');
    });

    // Clean up on modal hide
    $('#paymentModal').on('hidden.bs.modal', function() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
    });
});

function selectAddress(addressId) {
    // Update radio button
    $(`#address-${addressId}`).prop('checked', true);

    // Update card styles
    $('.address-card').removeClass('selected');
    $(`#address-${addressId}`).closest('.address-card').addClass('selected');
}
</script>
@endpush
