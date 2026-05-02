<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Subscription Plan - Kuku Soko Veterinary</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            padding: 20px;
        }

        /* Fixed Logout Button - Outside container */
        .fixed-logout-btn {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            z-index: 9999;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }

        .fixed-logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #5a67d8, #6b46a0);
            color: white;
        }

        .fixed-logout-btn i {
            font-size: 1.1rem;
        }

        .subscription-container {
            max-width: 1200px;
            width: 100%;
            margin: 80px auto 0;
            position: relative;
        }

        .header-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 36px;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .header-subtitle {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            border: 2px solid transparent;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .pricing-card.basic:hover {
            border-color: #28a745;
        }

        .pricing-card.pro:hover {
            border-color: #dc3545;
        }

        .popular-badge {
            position: absolute;
            top: 20px;
            right: -35px;
            background: #ffc107;
            color: #212529;
            padding: 8px 40px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .pricing-header {
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .pricing-header.basic {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .pricing-header.pro {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .plan-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 5px;
        }

        .plan-price small {
            font-size: 1rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .plan-duration {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .pricing-body {
            padding: 30px 25px;
        }

        .features-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .features-title i {
            color: #28a745;
            margin-right: 10px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-icon {
            width: 30px;
            color: #28a745;
            font-size: 1.1rem;
        }

        .feature-text {
            flex: 1;
            font-size: 0.95rem;
            color: #555;
        }

        .btn-select {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
            margin-top: 25px;
        }

        .btn-select.basic {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-select.pro {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
        }

        .btn-select:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .payment-info {
            text-align: center;
            margin-top: 15px;
            font-size: 0.85rem;
            color: #888;
        }

        .payment-info i {
            margin-right: 5px;
            color: #28a745;
        }

        .alert-custom {
            background: white;
            border-left: 4px solid #17a2b8;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .alert-custom i {
            font-size: 1.5rem;
            color: #17a2b8;
            margin-right: 15px;
        }

        .alert-custom .alert-content {
            flex: 1;
        }

        .alert-custom .alert-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .alert-custom .alert-message {
            color: #666;
            font-size: 0.9rem;
        }

        .footer-note {
            text-align: center;
            margin-top: 40px;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }

        .footer-note a {
            color: white;
            text-decoration: underline;
            font-weight: 500;
        }

        .footer-note a:hover {
            color: #ffc107;
        }

        /* Logout Confirmation Modal */
        .logout-modal .modal-content {
            border-radius: 15px;
            border: none;
        }

        .logout-modal .modal-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .logout-modal .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .logout-modal .modal-footer {
            border-top: none;
            padding: 1.5rem;
        }

        .btn-logout-confirm {
            background: #dc3545;
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 50px;
            border: none;
            transition: all 0.3s;
        }

        .btn-logout-confirm:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-logout-cancel {
            background: #6c757d;
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 50px;
            border: none;
            transition: all 0.3s;
        }

        .btn-logout-cancel:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }

            .plan-price {
                font-size: 2.5rem;
            }

            .fixed-logout-btn {
                top: 15px;
                right: 15px;
                padding: 8px 15px;
                font-size: 0.85rem;
            }

            body {
                padding: 10px;
            }

            .subscription-container {
                margin-top: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Fixed Logout Button - Always Visible -->
    <a href="#" class="fixed-logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>

    <div class="subscription-container">
        <!-- Header Card -->
        <div class="header-card">
            <div class="header-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
            <h1 class="header-title">Welcome, Veterinary Professional! 👋</h1>
            <p class="header-subtitle">
                To start using our platform, please choose a subscription plan that best fits your practice.
                Your subscription gives you access to all veterinary features.
            </p>

            <!-- Alert Messages -->
            @if(session('info'))
            <div class="alert-custom mt-4" style="border-left-color: #17a2b8;">
                <i class="fas fa-info-circle" style="color: #17a2b8;"></i>
                <div class="alert-content">
                    <div class="alert-title">Information</div>
                    <div class="alert-message">{{ session('info') }}</div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert-custom mt-4" style="border-left-color: #dc3545;">
                <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
                <div class="alert-content">
                    <div class="alert-title">Notice</div>
                    <div class="alert-message">{{ session('error') }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Pricing Cards -->
        <div class="row g-4">
            @foreach($plans as $plan)
            <div class="col-md-6">
                <div class="pricing-card {{ $plan->slug }}">
                    @if($plan->slug === 'pro')
                    <div class="popular-badge">🔥 MOST POPULAR</div>
                    @endif

                    <div class="pricing-header {{ $plan->slug }}">
                        <div class="plan-name">{{ $plan->name }} Plan</div>
                        <div class="plan-price">
                            KES {{ number_format($plan->price) }}<small>/mo</small>
                        </div>
                        <div class="plan-duration">Billed monthly</div>
                    </div>

                    <div class="pricing-body">
                        <div class="features-title">
                            <i class="fas fa-check-circle"></i>
                            What's included:
                        </div>

                        @foreach($plan->features_list as $feature)
                        <div class="feature-item">
                            <span class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span class="feature-text">{{ $feature }}</span>
                        </div>
                        @endforeach

                        <a href="{{ route('veterinary.subscription.show', $plan->slug) }}"
                           class="btn-select {{ $plan->slug }}">
                            <i class="fas fa-crown me-2"></i>
                            Select {{ $plan->name }} Plan
                        </a>

                        <div class="payment-info">
                            <i class="fas fa-shield-alt"></i>
                            Secure M-Pesa payment
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Trust Badges -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <div class="text-center px-3">
                        <i class="fas fa-lock text-white mb-2" style="font-size: 24px;"></i>
                        <p class="text-white mb-0 small">Secure Payments</p>
                    </div>
                    <div class="text-center px-3">
                        <i class="fas fa-clock text-white mb-2" style="font-size: 24px;"></i>
                        <p class="text-white mb-0 small">Instant Activation</p>
                    </div>
                    <div class="text-center px-3">
                        <i class="fas fa-headset text-white mb-2" style="font-size: 24px;"></i>
                        <p class="text-white mb-0 small">24/7 Support</p>
                    </div>
                    <div class="text-center px-3">
                        <i class="fas fa-undo-alt text-white mb-2" style="font-size: 24px;"></i>
                        <p class="text-white mb-0 small">30-Day Refund</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <p>
                <i class="fas fa-arrow-left me-2"></i>
                By selecting a plan, you agree to our
                <a href="#" onclick="alert('Terms and conditions would be shown here'); return false;">Terms of Service</a>
                and
                <a href="#" onclick="alert('Privacy policy would be shown here'); return false;">Privacy Policy</a>
            </p>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade logout-modal" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Confirm Logout
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="fas fa-question-circle text-warning" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h5 class="mb-3">Are you sure you want to logout?</h5>
                    <p class="text-muted mb-0">
                        You'll need to log in again to access your account and complete your subscription.
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-3">
                    <button type="button" class="btn-logout-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>

                    <!-- Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout-confirm">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Add smooth scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Optional: Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert-custom').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
