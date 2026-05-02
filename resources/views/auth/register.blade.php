<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - My-Kuku-Soko</title>

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

        .register-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            min-height: 85vh;
        }

        .register-sidebar {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-sidebar h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .register-sidebar p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .features-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
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

        .register-form {
            padding: 50px 40px;
            overflow-y: auto;
            max-height: 85vh;
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

        .input-group-text {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
            font-weight: 500;
            padding: 12px 15px;
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

        .login-link {
            color: var(--primary-green);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link:hover {
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

        .password-strength {
            margin-top: 10px;
        }

        .strength-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .strength-bar {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background 0.3s;
        }

        .strength-weak { width: 25%; background: #dc3545; }
        .strength-fair { width: 50%; background: #ffc107; }
        .strength-good { width: 75%; background: #28a745; }
        .strength-strong { width: 100%; background: var(--primary-green); }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .form-check-label a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .register-sidebar {
                padding: 30px;
            }

            .register-form {
                padding: 30px;
            }

            .register-sidebar h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .register-wrapper {
                flex-direction: column;
            }

            .register-sidebar {
                order: 2;
            }

            .register-form {
                order: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container-max">
        <div class="register-wrapper d-flex">
            <!-- Left Sidebar with Information -->
            <div class="col-lg-6 register-sidebar">
                <div>
                    <h1>
                        <i class="fas fa-egg me-2"></i>
                        Join My-Kuku-Soko
                    </h1>
                    <p>Kenya's leading digital marketplace for poultry farmers, buyers, and service providers.</p>

                    <ul class="features-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Sell poultry products directly to buyers</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Connect with verified veterinary services</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Track orders and manage your farm</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Secure payments and delivery tracking</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Grow your poultry business digitally</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="display-6 fw-bold">500+</div>
                                <small>Active Farmers</small>
                            </div>
                            <div class="col-4">
                                <div class="display-6 fw-bold">1K+</div>
                                <small>Daily Orders</small>
                            </div>
                            <div class="col-4">
                                <div class="display-6 fw-bold">100+</div>
                                <small>Veterinaries</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side with Registration Form -->
            <div class="col-lg-6 register-form">
                <div class="form-header">
                    <h2>Create Your Account</h2>
                    <p>Fill in your details to get started</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Role Badge from Welcome Page -->
                    @if(request()->has('role'))
                        <div class="alert alert-success d-flex align-items-center mb-4">
                            <i class="fas fa-user-tag fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">Registering as {{ ucfirst(request()->role) }}</h5>
                                <small class="mb-0">You'll confirm this role after email verification</small>
                                <input type="hidden" name="preferred_role" value="{{ request()->role }}">
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-12 mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2 text-success"></i>Full Name
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="Enter your full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-12 mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2 text-success"></i>Email Address
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="example@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-12 mb-4">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2 text-success"></i>Phone Number
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">+254</span>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       required
                                       placeholder="712 345 678">
                                <button class="btn btn-outline-secondary" type="button" id="formatPhone">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter your 9-digit mobile number without the leading 0
                            </small>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-2 text-success"></i>Location / Address
                            </label>
                            <input type="text"
                                   class="form-control @error('address') is-invalid @enderror"
                                   id="address"
                                   name="address"
                                   value="{{ old('address') }}"
                                   required
                                   placeholder="Enter your city/town and area">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                e.g., Nairobi, Westlands or Eldoret, Langas
                            </small>
                        </div>

                        <!-- Password -->
                        <div class="col-md-12 mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2 text-success"></i>Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Create a strong password">
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            <div class="password-strength">
                                <div class="strength-label">
                                    <span>Password Strength:</span>
                                    <span id="strengthText">None</span>
                                </div>
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthBar"></div>
                                </div>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Minimum 8 characters with letters and numbers
                            </small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-12 mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-2 text-success"></i>Confirm Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       placeholder="Confirm your password">
                                <button type="button" class="toggle-password" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                   type="checkbox"
                                   id="terms"
                                   name="terms"
                                   required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-success fw-bold">Terms of Service</a>
                                and <a href="#" class="text-success fw-bold">Privacy Policy</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i> Create Account
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-top">
                        <p class="mb-0">
                            Already have an account?
                            <a href="{{ route('login') }}" class="login-link">
                                <i class="fas fa-sign-in-alt me-1"></i> Login here
                            </a>
                        </p>
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
        // Password strength indicator
        function checkPasswordStrength(password) {
            let strength = 0;

            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;

            // Character type checks
            if (/[a-z]/.test(password)) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            return strength;
        }

        function updatePasswordStrength() {
            const password = $('#password').val();
            const strength = checkPasswordStrength(password);
            const strengthBar = $('#strengthBar');
            const strengthText = $('#strengthText');

            // Remove all strength classes
            strengthBar.removeClass('strength-weak strength-fair strength-good strength-strong');

            if (password.length === 0) {
                strengthText.text('None');
                strengthBar.css('width', '0%');
            } else if (strength <= 2) {
                strengthText.text('Weak');
                strengthBar.addClass('strength-weak');
            } else if (strength <= 3) {
                strengthText.text('Fair');
                strengthBar.addClass('strength-fair');
            } else if (strength <= 4) {
                strengthText.text('Good');
                strengthBar.addClass('strength-good');
            } else {
                strengthText.text('Strong');
                strengthBar.addClass('strength-strong');
            }
        }

        // Toggle password visibility
        $('#togglePassword').click(function() {
            const passwordInput = $('#password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        $('#togglePasswordConfirm').click(function() {
            const passwordInput = $('#password_confirmation');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        // Format phone number
        $('#formatPhone').click(function() {
            let phone = $('#phone').val().replace(/\D/g, '');

            // Remove leading 0 if present
            if (phone.startsWith('0')) {
                phone = phone.substring(1);
            }

            // Format with spaces for readability
            if (phone.length >= 9) {
                phone = phone.substring(0, 9);
                const formatted = phone.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
                $('#phone').val(formatted);
            }
        });

        // Auto-format phone number on input
        $('#phone').on('input', function() {
            let phone = $(this).val().replace(/\D/g, '');

            if (phone.length > 9) {
                phone = phone.substring(0, 9);
            }

            if (phone.length > 0) {
                const formatted = phone.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
                $(this).val(formatted);
            }
        });

        // Real-time password strength update
        $('#password').on('input', function() {
            updatePasswordStrength();
        });

        // Real-time password match check
        $('#password_confirmation').on('input', function() {
            const password = $('#password').val();
            const confirmPassword = $(this).val();

            if (confirmPassword !== '' && password !== confirmPassword) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Passwords do not match</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        // Initialize on page load
        $(document).ready(function() {
            updatePasswordStrength();
        });
    </script>
</body>
</html>
