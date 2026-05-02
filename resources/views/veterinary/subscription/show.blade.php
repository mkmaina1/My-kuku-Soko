<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe to {{ $plan->name }} Plan - Kuku Soko Veterinary</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Toastr CSS for notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
        }

        .back-link {
            margin-bottom: 20px;
        }

        .back-link a {
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            transition: all 0.3s;
        }

        .back-link a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(-5px);
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .payment-header {
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .payment-header.basic {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .payment-header.pro {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .payment-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(30deg);
        }

        .payment-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .payment-header p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .payment-body {
            padding: 40px;
        }

        .plan-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }

        .plan-summary-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .plan-summary-title i {
            margin-right: 10px;
            color: #28a745;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #666;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: #28a745;
        }

        .summary-value.price {
            font-size: 1.3rem;
            color: #dc3545;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            color: #555;
        }

        .feature-list li i {
            color: #28a745;
            margin-right: 12px;
            font-size: 1rem;
        }

        .payment-form {
            background: white;
            border-radius: 15px;
        }

        .form-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .form-title i {
            margin-right: 10px;
            color: #28a745;
        }

        .mpesa-input-group {
            margin-bottom: 20px;
        }

        .mpesa-input-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-custom {
            display: flex;
            align-items: stretch;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .input-group-custom:focus-within {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .input-group-text {
            background: #f8f9fa;
            border: none;
            padding: 12px 15px;
            font-weight: 600;
            color: #28a745;
        }

        .input-group-custom input {
            flex: 1;
            border: none;
            padding: 12px 15px;
            font-size: 1rem;
            outline: none;
        }

        .help-text {
            font-size: 0.85rem;
            color: #888;
            margin-top: 8px;
        }

        .help-text i {
            margin-right: 5px;
            color: #28a745;
        }

        .info-alert {
            background: #e7f3ff;
            border-left: 4px solid #17a2b8;
            border-radius: 10px;
            padding: 15px 20px;
            margin: 25px 0;
            display: flex;
            align-items: center;
        }

        .info-alert i {
            font-size: 1.5rem;
            color: #17a2b8;
            margin-right: 15px;
        }

        .info-alert-content {
            flex: 1;
        }

        .info-alert-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .info-alert-message {
            color: #666;
            font-size: 0.9rem;
        }

        .btn-pay {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-pay.basic {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-pay.pro {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-pay i {
            margin-right: 10px;
        }

        .btn-pay:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .security-note {
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 0.85rem;
        }

        .security-note i {
            color: #28a745;
            margin-right: 5px;
        }

        /* Modal Styles */
        .payment-modal .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }

        .payment-modal .modal-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 20px;
        }

        .payment-modal .modal-body {
            padding: 40px;
            text-align: center;
        }

        .spinner-custom {
            width: 60px;
            height: 60px;
            border: 4px solid #e9ecef;
            border-top-color: #28a745;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .modal-message {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .modal-details {
            color: #666;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .payment-body {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Back Link (only to plans page) -->
        <div class="back-link">
            <a href="{{ route('veterinary.subscription.plans') }}">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Plans
            </a>
        </div>

        <!-- Payment Card -->
        <div class="payment-card">
            <div class="payment-header {{ $plan->slug }}">
                <h2>{{ $plan->name }} Plan</h2>
                <p>Complete your subscription to access {{ $plan->name }} features</p>
            </div>

            <div class="payment-body">
                <div class="row">
                    <!-- Left Column: Plan Details -->
                    <div class="col-md-6">
                        <div class="plan-summary">
                            <div class="plan-summary-title">
                                <i class="fas fa-receipt"></i>
                                Order Summary
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">Plan:</span>
                                <span class="summary-value">{{ $plan->name }}</span>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">Billing:</span>
                                <span class="summary-value">Monthly</span>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">Amount:</span>
                                <span class="summary-value price">KES {{ number_format($plan->price, 2) }}</span>
                            </div>
                        </div>

                        <h5 class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Features Included:
                        </h5>

                        <ul class="feature-list">
                            @foreach($plan->features_list as $feature)
                            <li>
                                <i class="fas fa-check-circle"></i>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Right Column: Payment Form -->
                    <div class="col-md-6">
                        <div class="payment-form">
                            <div class="form-title">
                                <i class="fas fa-lock"></i>
                                Payment Details
                            </div>

                            <form id="subscriptionForm">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                                <div class="mpesa-input-group">
                                    <label for="phone_number">
                                        <i class="fas fa-mobile-alt me-2 text-success"></i>
                                        M-Pesa Phone Number
                                    </label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text">+254</span>
                                        <input type="text"
                                               id="phone_number"
                                               name="phone_number"
                                               placeholder="712345678"
                                               value="{{ Auth::user()->phone ?? '' }}"
                                               required>
                                    </div>
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Enter your M-Pesa registered phone number (without the leading 0)
                                    </div>
                                </div>

                                <div class="info-alert">
                                    <i class="fas fa-bell"></i>
                                    <div class="info-alert-content">
                                        <div class="info-alert-title">STK Push Prompt</div>
                                        <div class="info-alert-message">
                                            You'll receive an STK push prompt on your phone. Enter your M-Pesa PIN to complete payment.
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn-pay {{ $plan->slug }}" id="payBtn">
                                    <i class="fas fa-lock me-2"></i>
                                    Pay KES {{ number_format($plan->price, 2) }}
                                </button>

                                <div class="security-note">
                                    <i class="fas fa-shield-alt"></i>
                                    Your payment is secured by M-Pesa
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <div class="text-center px-3">
                        <i class="fas fa-bolt text-white mb-2" style="font-size: 20px;"></i>
                        <p class="text-white mb-0 small">Instant Activation</p>
                    </div>
                    <div class="text-center px-3">
                        <i class="fas fa-shield-alt text-white mb-2" style="font-size: 20px;"></i>
                        <p class="text-white mb-0 small">Secure Payment</p>
                    </div>
                    <div class="text-center px-3">
                        <i class="fas fa-undo-alt text-white mb-2" style="font-size: 20px;"></i>
                        <p class="text-white mb-0 small">30-Day Refund</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status Modal -->
    <div class="modal fade payment-modal" id="paymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Processing Payment
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="spinner-custom"></div>
                    <h5 class="modal-message" id="paymentMessage">Please check your phone</h5>
                    <p class="modal-details" id="paymentDetails"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Configure Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

        $(document).ready(function() {
            $('#subscriptionForm').on('submit', function(e) {
                e.preventDefault();

                let phone = $('#phone_number').val();
                if (!phone || !phone.match(/^[0-9]{9,12}$/)) {
                    toastr.error('Please enter a valid M-Pesa phone number');
                    return;
                }

                $('#payBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Processing...');

                $.ajax({
                    url: '{{ route("veterinary.subscription.process") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#paymentMessage').text('Please check your phone');
                            $('#paymentDetails').text('Amount: KES {{ number_format($plan->price, 2) }}');
                            $('#paymentModal').modal('show');

                            // Start checking payment status
                            checkPaymentStatus(response.checkout_request_id);
                        } else {
                            toastr.error(response.message);
                            $('#payBtn').prop('disabled', false).html('Pay KES {{ number_format($plan->price, 2) }}');
                        }
                    },
                    error: function(xhr) {
                        let message = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                        $('#payBtn').prop('disabled', false).html('Pay KES {{ number_format($plan->price, 2) }}');
                    }
                });
            });

            function checkPaymentStatus(checkoutId) {
                let checkInterval = setInterval(function() {
                    $.ajax({
                        url: '{{ url("/api/veterinary/subscription/status") }}/' + checkoutId,
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.status === 'active') {
                                clearInterval(checkInterval);
                                $('#paymentModal').modal('hide');
                                toastr.success('Subscription activated successfully!');

                                // Redirect to dashboard
                                window.location.href = '{{ route("veterinary.dashboard") }}';
                            }
                        },
                        error: function() {
                            // Silent fail - continue checking
                        }
                    });
                }, 3000);
            }
        });
    </script>
</body>
</html>
