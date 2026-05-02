<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - My-Kuku-Soko</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #66bb6a;
            --dark-green: #1b5e20;
            --orange: #ff7c00;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Source Sans Pro', sans-serif;
            min-height: 100vh;
            padding: 20px;
            background-attachment: fixed;
        }

        .container-max {
            max-width: 1200px;
            margin: 0 auto;
        }

        .login-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            min-height: 85vh;
        }

        .login-sidebar {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1598514982733-e09c8a4c46ee?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover;
            opacity: 0.1;
        }

        .login-sidebar h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            position: relative;
            z-index: 1;
        }

        .login-sidebar p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
            position: relative;
            z-index: 1;
        }

        .features-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .features-list i {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .login-form {
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .form-header h2 {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 0.3rem rgba(102, 187, 106, 0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 187, 106, 0.3);
        }

        .register-link {
            color: var(--primary-green);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-link:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            z-index: 10;
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .alert-success {
            background-color: rgba(102, 187, 106, 0.1);
            border-color: var(--light-green);
            color: var(--dark-green);
        }

        .forgot-password {
            color: #666;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }

        .login-options {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .social-login {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .social-btn.google:hover {
            border-color: #DB4437;
            color: #DB4437;
        }

        .social-btn.facebook:hover {
            border-color: #4267B2;
            color: #4267B2;
        }

        @media (max-width: 992px) {
            .login-sidebar {
                padding: 30px;
            }

            .login-form {
                padding: 30px;
            }

            .login-sidebar h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-sidebar {
                order: 2;
            }

            .login-form {
                order: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container-max">
        <div class="login-wrapper d-flex">
            <!-- Left Sidebar with Information -->
            <div class="col-lg-6 login-sidebar">
                <div>
                    <h1>
                        <i class="fas fa-egg me-2"></i>
                        Welcome Back!
                    </h1>
                    <p>Access your My-Kuku-Soko account to manage your poultry business, track orders, and connect with buyers.</p>

                    <ul class="features-list">
                        <li>
                            <i class="fas fa-shopping-cart"></i>
                            <span>Manage your poultry listings and orders</span>
                        </li>
                        <li>
                            <i class="fas fa-chart-line"></i>
                            <span>Track sales and business analytics</span>
                        </li>
                        <li>
                            <i class="fas fa-users"></i>
                            <span>Connect with buyers and veterinaries</span>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure transactions and data protection</span>
                        </li>
                        <li>
                            <i class="fas fa-bell"></i>
                            <span>Get real-time notifications</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="display-6 fw-bold">95%</div>
                                <small>Satisfaction Rate</small>
                            </div>
                            <div class="col-4">
                                <div class="display-6 fw-bold">24/7</div>
                                <small>Support</small>
                            </div>
                            <div class="col-4">
                                <div class="display-6 fw-bold">Secure</div>
                                <small>Platform</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side with Login Form -->
            <div class="col-lg-6 login-form">
                <div class="form-header">
                    <h2>Login to Your Account</h2>
                    <p>Enter your credentials to continue</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2 text-success"></i>Email Address
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               placeholder="Enter your email address">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2 text-success"></i>Password
                        </label>
                        <div class="password-wrapper">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Enter your password">
                            <button type="button" class="toggle-password" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="remember_me"
                                   name="remember">
                            <label class="form-check-label" for="remember_me">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                <i class="fas fa-key me-1"></i>Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to Account
                        </button>
                    </div>

                    <!-- Demo Account Info (Optional) -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Demo Accounts Available</h6>
                                <small class="mb-0">Try farmer@example.com / password123 or client@example.com / password123</small>
                            </div>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-top">
                        <p class="mb-0">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="register-link">
                                <i class="fas fa-user-plus me-1"></i> Register here
                            </a>
                        </p>
                    </div>

                    <!-- Social Login Options -->
                    <div class="login-options">
                        <p class="text-center text-muted mb-3">Or login with</p>
                        <div class="social-login">
                            <a href="#" class="social-btn google">
                                <i class="fab fa-google text-danger"></i>
                                <span>Google</span>
                            </a>
                            <a href="#" class="social-btn facebook">
                                <i class="fab fa-facebook text-primary"></i>
                                <span>Facebook</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Toggle password visibility
        $('#togglePassword').click(function() {
            const passwordInput = $('#password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        // Auto-focus on email field
        $(document).ready(function() {
            $('#email').focus();
        });

        // Form validation on submit
        $('form').submit(function(e) {
            const email = $('#email').val().trim();
            const password = $('#password').val().trim();

            if (!email || !password) {
                e.preventDefault();

                if (!email) {
                    $('#email').addClass('is-invalid');
                    if (!$('#email').next('.invalid-feedback').length) {
                        $('#email').after('<div class="invalid-feedback">Email is required</div>');
                    }
                }

                if (!password) {
                    $('#password').addClass('is-invalid');
                    if (!$('#password').next('.invalid-feedback').length) {
                        $('#password').after('<div class="invalid-feedback">Password is required</div>');
                    }
                }

                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
            }
        });

        // Clear validation on input
        $('#email, #password').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });

        // Demo account quick fill
        $(document).ready(function() {
            // Add quick fill buttons for demo accounts
            const demoAccounts = [
                { email: 'farmer@example.com', password: 'password123', label: 'Farmer Demo' },
                { email: 'client@example.com', password: 'password123', label: 'Client Demo' },
                { email: 'admin@example.com', password: 'password123', label: 'Admin Demo' }
            ];

            const demoContainer = $('<div class="demo-accounts mt-3"></div>');
            const demoTitle = $('<small class="text-muted d-block mb-2">Quick Login:</small>');
            demoContainer.append(demoTitle);

            const demoButtons = $('<div class="d-flex gap-2 flex-wrap"></div>');

            demoAccounts.forEach(account => {
                const btn = $(`<button type="button" class="btn btn-sm btn-outline-success" data-email="${account.email}" data-password="${account.password}">${account.label}</button>`);
                demoButtons.append(btn);
            });

            demoContainer.append(demoButtons);
            $('#password').closest('.mb-4').after(demoContainer);

            // Quick fill functionality
            $('.demo-accounts button').click(function() {
                const email = $(this).data('email');
                const password = $(this).data('password');

                $('#email').val(email);
                $('#password').val(password);
                $('#remember_me').prop('checked', true);

                // Highlight the clicked button
                $('.demo-accounts button').removeClass('btn-success').addClass('btn-outline-success');
                $(this).removeClass('btn-outline-success').addClass('btn-success');

                // Show success message
                const alert = $(`<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    ${$(this).text()} credentials loaded. Click "Login to Account" to continue.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);

                $('.demo-accounts').after(alert);

                // Auto-remove alert after 5 seconds
                setTimeout(() => {
                    alert.alert('close');
                }, 5000);
            });
        });
    </script>
</body>
</html>
