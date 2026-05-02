<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Your Role - My-Kuku-Soko</title>

    <!-- AdminLTE + Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #66bb6a;
            --dark-green: #1b5e20;
            --pale-green: #e8f5e9;
        }

        body {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .role-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .role-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .role-card:hover::before {
            opacity: 1;
        }

        .role-card.selected {
            border-color: var(--light-green);
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .role-card.selected::before {
            opacity: 1;
        }

        .role-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 30px auto 20px;
            font-size: 36px;
            color: white;
            transition: all 0.3s;
        }
        .supplier-icon {
    background: linear-gradient(135deg, var(--primary-green), var(--light-green));
    box-shadow: 0 10px 20px rgba(102, 187, 106, 0.3);
}

        .farmer-icon {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            box-shadow: 0 10px 20px rgba(102, 187, 106, 0.3);
        }

        .client-icon {
            background: linear-gradient(135deg, #2196f3, #64b5f6);
            box-shadow: 0 10px 20px rgba(33, 150, 243, 0.3);
        }

        .agent-icon {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            box-shadow: 0 10px 20px rgba(255, 152, 0, 0.3);
        }

        .veterinary-icon {
            background: linear-gradient(135deg, #f44336, #ef5350);
            box-shadow: 0 10px 20px rgba(244, 67, 54, 0.3);
        }

        .role-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .role-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            padding: 0 15px;
        }

        .role-features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .role-features li {
            padding: 5px 0;
            font-size: 0.85rem;
            color: #555;
            display: flex;
            align-items: center;
        }

        .role-features li i {
            color: var(--primary-green);
            margin-right: 8px;
            font-size: 0.8rem;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 187, 106, 0.4);
        }

        .btn-success:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .header-section {
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--pale-green);
        }

        .header-icon {
            font-size: 48px;
            color: var(--primary-green);
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 10px;
        }

        .header-subtitle {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .selection-info {
            background: var(--pale-green);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
            border: 2px dashed var(--light-green);
        }

        .selection-icon {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 10px;
        }

        .role-badge {
            background: var(--pale-green);
            color: var(--primary-green);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 5px;
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }

            .header-section {
                padding: 30px 20px;
            }

            .role-icon {
                width: 70px;
                height: 70px;
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="role-container">
        <div class="card shadow-lg border-0">
            <!-- Header -->
            <div class="header-section">
                <div class="header-icon">
                    <i class="fas fa-user-tag"></i>
                </div>
                <h1 class="header-title">Choose Your Role</h1>
                <p class="header-subtitle">
                    Select how you want to use My-Kuku-Soko platform. Your role determines
                    the features you can access and how you interact with others.
                </p>
            </div>

            <!-- Role Selection Form -->
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('role.assign') }}" method="POST">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Please select a role to continue.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                   <div class="row g-4">
    <!-- Independent Supplier Card -->
    <div class="col-md-6 col-lg-3">
        <div class="role-card text-center p-4" onclick="selectRole('supplier')" id="supplier-card">
            <div class="role-icon supplier-icon">
            <i class="fas fa-warehouse"></i> <!-- Changed icon -->
        </div>
            <h3 class="role-title">Independent Supplier</h3>
            <p class="role-description">
                Supply poultry products to farmers and other buyers in bulk.
            </p>

            <ul class="role-features">
                <li><i class="fas fa-check"></i> Supply in bulk quantities</li>
                <li><i class="fas fa-check"></i> Manage product inventory</li>
                <li><i class="fas fa-check"></i> Supply to multiple farms</li>
                <li><i class="fas fa-check"></i> Track supply analytics</li>
            </ul>

            <div class="mt-3">
                <span class="role-badge">Supply Products</span>
                <span class="role-badge">Bulk Orders</span>
            </div>

            <input type="radio" name="role" value="supplier" id="supplier" class="d-none" required>
        </div>
    </div>

    <!-- Farmer Card -->
    <div class="col-md-6 col-lg-3">
        <div class="role-card text-center p-4" onclick="selectRole('farmer')" id="farmer-card">
            <div class="role-icon farmer-icon">
                <i class="fas fa-tractor"></i> <!-- This icon is free -->
            </div>
            <h3 class="role-title">Farmer</h3>
            <p class="role-description">
                Raise poultry and purchase supplies for your farm operations.
            </p>

            <ul class="role-features">
                <li><i class="fas fa-check"></i> Purchase farm supplies</li>
                <li><i class="fas fa-check"></i> Browse chicks & feeds</li>
                <li><i class="fas fa-check"></i> Place orders & Track order status</li>
                <li><i class="fas fa-check"></i> Raise poultry</li>
                <li><i class="fas fa-check"></i> Manage farm operations</li>
                <li><i class="fas fa-check"></i> Request veterinary services</li>
            </ul>

            <div class="mt-3">
                <span class="role-badge">Raise Poultry</span>
                <span class="role-badge">Buy Supplies</span>
            </div>

            <input type="radio" name="role" value="farmer" id="farmer" class="d-none" required>
        </div>
    </div>

    <!-- Agent Card -->
    <div class="col-md-6 col-lg-3">
        <div class="role-card text-center p-4" onclick="selectRole('agent')" id="agent-card">
            <div class="role-icon agent-icon">
                <i class="fas fa-user-tie"></i> <!-- This icon is free -->
            </div>
            <h3 class="role-title">Agent</h3>
            <p class="role-description">
                Represent suppliers and help them sell products to a wider market.
            </p>

            <ul class="role-features">
                 <li><i class="fas fa-check"></i> Verification from Suppliers</li>
                <li><i class="fas fa-check"></i> Represent multiple Suppliers</li>
                <li><i class="fas fa-check"></i> Manage listings on behalf</li>
                <li><i class="fas fa-check"></i> Earn sales commission</li>
                <li><i class="fas fa-check"></i> Connect farms to Suppliers</li>
            </ul>

            <div class="mt-3">
                <span class="role-badge">Represent Suppliers</span>
                <span class="role-badge">Earn Commission</span>
            </div>

            <input type="radio" name="role" value="agent" id="agent" class="d-none" required>
        </div>
    </div>

    <!-- Veterinary Card -->
    <div class="col-md-6 col-lg-3">
        <div class="role-card text-center p-4" onclick="selectRole('veterinary')" id="veterinary-card">
            <div class="role-icon veterinary-icon">
                <i class="fas fa-user-md"></i> <!-- This icon is free -->
            </div>
            <h3 class="role-title">Veterinary</h3>
            <p class="role-description">
                Offer professional poultry health services to farmers.
            </p>

            <ul class="role-features">
                <li><i class="fas fa-check"></i> List veterinary services</li>
                <li><i class="fas fa-check"></i> Schedule farm visits</li>
                <li><i class="fas fa-check"></i> Provide consultations</li>
                <li><i class="fas fa-check"></i> Build professional profile</li>
            </ul>

            <div class="mt-3">
                <span class="role-badge">Health Services</span>
                <span class="role-badge">Consultations</span>
            </div>

            <input type="radio" name="role" value="veterinary" id="veterinary" class="d-none" required>
        </div>
    </div>
</div>

                    <!-- Selection Info -->
                    <div class="selection-info mt-5">
                        <div class="selection-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h5 class="mb-2">Role Selection</h5>
                        <p class="mb-0 text-muted">
                            <span id="selected-role-text" class="fw-bold text-success">No role selected</span> •
                            You can update your role later from your profile settings
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-5">
                        <button type="submit" id="continue-btn" class="btn btn-success btn-lg px-5 py-3" disabled>
                            <i class="fas fa-check-circle me-2"></i> Confirm Role & Continue
                        </button>
                        <p class="text-muted mt-3">
                            <i class="fas fa-shield-alt me-1"></i>
                            Your role helps us personalize your experience and show relevant features
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer Note -->
            <div class="card-footer text-center py-3 bg-light">
                <small class="text-muted">
                    Need help choosing? <a href="#" class="text-success">Learn more about each role</a>
                </small>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        let selectedRole = '';
       const roleTitles = {
    'supplier': 'Independent Supplier', // Changed
    'farmer': 'Farmer',                 // Changed
    'agent': 'Agent',
    'veterinary': 'Veterinary'
};
        function selectRole(role) {
            // Reset all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Select clicked card
            const card = document.getElementById(role + '-card');
            card.classList.add('selected');

            // Select radio button
            document.getElementById(role).checked = true;
            selectedRole = role;

            // Update selection info
            document.getElementById('selected-role-text').textContent = roleTitles[role] + ' selected';
            document.getElementById('selected-role-text').className = 'fw-bold text-success';

            // Enable continue button
            document.getElementById('continue-btn').disabled = false;

            // Add animation effect
            card.style.animation = 'none';
            setTimeout(() => {
                card.style.animation = 'pulse 0.5s';
            }, 10);
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!selectedRole) {
                e.preventDefault();
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Please select a role to continue.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                const existingAlert = document.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                this.insertBefore(alertDiv, this.firstChild);

                // Auto-remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: translateY(-10px) scale(1); }
                50% { transform: translateY(-10px) scale(1.02); }
                100% { transform: translateY(-10px) scale(1); }
            }
        `;
        document.head.appendChild(style);

        // Auto-select role if coming from registration
        @if(request()->has('preferred_role'))
            window.onload = function() {
                const preferredRole = '{{ request()->preferred_role }}';
                if (preferredRole && ['farmer', 'client', 'agent', 'veterinary'].includes(preferredRole)) {
                    selectRole(preferredRole);

                    // Show info message
                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'alert alert-info alert-dismissible fade show mb-4';
                    infoDiv.innerHTML = `
                        <i class="fas fa-info-circle me-2"></i>
                        You're registering as a ${roleTitles[preferredRole]}. Confirm your selection below.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

                    const form = document.querySelector('form');
                    const existingAlert = form.querySelector('.alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }

                    form.insertBefore(infoDiv, form.firstChild);
                }
            }
        @endif
    </script>
</body>
</html>
