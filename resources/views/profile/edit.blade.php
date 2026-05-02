@extends('layouts.app')

@section('title', 'My Profile')

@section('styles')
<style>
    :root {
        --primary-green: #2e7d32;
        --light-green: #66bb6a;
        --dark-green: #1b5e20;
    }

    .profile-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 20px;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        color: white;
        padding: 40px 30px;
        position: relative;
    }

    .avatar-container {
        width: 120px;
        height: 120px;
        margin-bottom: 20px;
    }

    .avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .role-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 10px;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s;
        border: 1px solid #e9ecef;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.12);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 1.2rem;
    }

    .stats-icon-primary {
        background: rgba(40, 167, 69, 0.1);
        color: var(--primary-green);
    }

    .stats-icon-success {
        background: rgba(102, 187, 106, 0.1);
        color: var(--light-green);
    }

    .stats-icon-warning {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .stats-icon-info {
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }

    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
        background: #f8f9fa;
        padding: 0 20px;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 15px 20px;
        margin-right: 5px;
        transition: all 0.3s;
        border-radius: 8px 8px 0 0;
    }

    .nav-tabs-custom .nav-link.active {
        color: var(--primary-green);
        background: white;
        border-bottom: 3px solid var(--primary-green);
        font-weight: 600;
    }

    .nav-tabs-custom .nav-link:hover:not(.active) {
        color: var(--dark-green);
        background: rgba(102, 187, 106, 0.1);
    }

    .form-control:focus {
        border-color: var(--light-green);
        box-shadow: 0 0 0 0.2rem rgba(102, 187, 106, 0.25);
    }

    .btn-success-custom {
        background: linear-gradient(135deg, var(--primary-green), var(--light-green));
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
        color: white;
    }

    .btn-success-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 187, 106, 0.3);
        color: white;
    }

    .btn-outline-success-custom {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        padding: 8px 20px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-outline-success-custom:hover {
        background: var(--primary-green);
        color: white;
        transform: translateY(-2px);
    }

    .btn-warning-custom {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
        color: #212529;
    }

    .btn-warning-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        color: #212529;
    }

    .input-group-text {
        background: var(--primary-green);
        color: white;
        border-color: var(--primary-green);
        font-weight: 500;
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

    .verification-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .verification-badge.verified {
        background: rgba(40, 167, 69, 0.1);
        color: var(--primary-green);
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    /* Verification specific styles */
    .verification-status-card {
        border-left: 4px solid;
        transition: all 0.3s;
    }

    .verification-status-card:hover {
        transform: translateX(5px);
    }

    .document-preview {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 10px;
        background: #f8f9fa;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .document-preview img {
        max-height: 140px;
        max-width: 100%;
        object-fit: contain;
    }

    .verification-history-table {
        font-size: 0.9rem;
    }

    .verification-history-table td {
        vertical-align: middle;
    }

    .benefit-card {
        height: 100%;
        transition: transform 0.3s;
    }

    .benefit-card:hover {
        transform: translateY(-5px);
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 30px 20px;
            text-align: center;
        }

        .nav-tabs-custom .nav-link {
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .avatar-container {
            margin: 0 auto 20px;
        }
    }

    /* AdminLTE style password field with eye icon */
    .input-group-append .btn {
        border-color: #ced4da;
        background-color: #f8f9fa;
    }

    .input-group-append .btn:hover {
        background-color: #e9ecef;
    }

    /* Password requirements styling */
    .password-requirements {
        background-color: #f8f9fa;
        border-radius: .25rem;
        padding: .75rem 1rem;
        margin-top: 1rem;
        border-left: 4px solid var(--primary-green);
    }

    .password-requirements ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }

    .password-requirements li {
        margin-bottom: .25rem;
        font-size: .875rem;
        color: #6c757d;
    }

    .password-requirements li.requirement-met {
        color: var(--primary-green);
    }

    .password-requirements li.requirement-met:before {
        content: "✓ ";
        color: var(--primary-green);
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Add Verification and Change Password Buttons in Header -->
<div class="d-flex justify-content-end mb-3">
    <!-- Add Verification Button FIRST -->
    @php
        $canApply = $user->canApplyVerification();
        $hasPendingRequest = $user->hasPendingVerificationRequest();
        $verificationStatus = $user->verification_request_status;
    @endphp

    @if($canApply)
        <button type="button" class="btn btn-warning-custom mr-2" data-toggle="modal" data-target="#applyVerificationModal">
            <i class="fas fa-shield-alt me-2"></i>Get Verified
        </button>
    @elseif($hasPendingRequest)
        <button type="button" class="btn btn-warning-custom mr-2" disabled>
            <i class="fas fa-clock me-2"></i>Verification Pending
        </button>
    @elseif($user->isVerified())
        <button type="button" class="btn btn-success-custom mr-2" disabled>
            <i class="fas fa-check-circle me-2"></i>Verified
        </button>
    @elseif($verificationStatus === 'rejected')
        <button type="button" class="btn btn-warning-custom mr-2" data-toggle="modal" data-target="#applyVerificationModal">
            <i class="fas fa-redo me-2"></i>Reapply for Verification
        </button>
    @endif

    <!-- Change Password Button -->
    <button type="button" class="btn btn-success-custom" data-toggle="modal" data-target="#changePasswordModal">
        <i class="fas fa-key me-2"></i>Change Password
    </button>
</div>
    <div class="profile-wrapper">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-auto text-center text-md-left">
                    <div class="avatar-container">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=66bb6a&color=fff&size=120&bold=true&font-size=0.8"
                            alt="{{ $user->name }}"
                            class="avatar">
                    </div>
                </div>

                <div class="col-md">
                    <h2 class="mb-2">{{ $user->name }}</h2>
                    <p class="mb-1">
                        <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                    </p>
                    @if($user->phone)
                    <p class="mb-1">
                        <i class="fas fa-phone me-2"></i>
                        {{ preg_replace('/(\d{3})(\d{3})(\d{3})/', '$1 $2 $3', substr($user->phone, 3)) }}
                    </p>
                    @endif
                    @if($user->address)
                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ $user->address }}
                    </p>
                    @endif

                    <div class="d-flex align-items-center flex-wrap mt-3">
                        <span class="role-badge">
                            <i class="fas fa-user-tag me-2"></i>{{ ucfirst($user->role) }}
                        </span>

                        <!-- Update verification badge to use dynamic colors -->
                        <span class="verification-badge ml-3" style="
                            background: rgba(var(--badge-color-rgb), 0.1);
                            color: var(--badge-color);
                            border: 1px solid rgba(var(--badge-color-rgb), 0.2);
                        ">
                            <i class="fas {{ $user->verificationBadge['icon'] ?? 'fa-user' }} me-2"></i>
                            {{ $user->verificationBadge['text'] ?? 'Not Verified' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-auto text-md-right text-center mt-3 mt-md-0">
                    <div class="mb-2">
                        <small class="d-block">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Member since {{ $user->created_at->format('M d, Y') }}
                        </small>
                        @if($user->last_login_at)
                        <small class="d-block mt-1">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Last login {{ $user->last_login_at->diffForHumans() }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="px-4 pt-4">
            <h4 class="mb-3">Profile Overview</h4>
            <div class="row">
                @if($user->isSupplier())
                    <!-- Supplier Stats -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-primary">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h5 class="mb-1">{{ $user->products->count() }}</h5>
                            <p class="text-muted mb-0">Products</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-success">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h5 class="mb-1">{{ $ordersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="mb-1">{{ $pendingOrdersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Pending Orders</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-info">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5 class="mb-1">{{ $completedOrdersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Completed Orders</p>
                        </div>
                    </div>

                @elseif($user->isFarmer())
                    <!-- Farmer Stats -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-primary">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h5 class="mb-1">{{ $ordersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">My Orders</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5 class="mb-1">KES {{ number_format($totalSpent ?? 0) }}</h5>
                            <p class="text-muted mb-0">Total Spent</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-warning">
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                            <h5 class="mb-1">{{ $cartItemsCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Cart Items</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-info">
                                <i class="fas fa-tractor"></i>
                            </div>
                            <h5 class="mb-1">{{ $poultryCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Poultry</p>
                        </div>
                    </div>

                @elseif($user->isAgent())
                    <!-- Agent Stats -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="mb-1">{{ $farmersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Farmers</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-success">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h5 class="mb-1">{{ $agentOrdersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Agent Orders</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-warning">
                                <i class="fas fa-money-bill"></i>
                            </div>
                            <h5 class="mb-1">KES {{ number_format($commissionEarned ?? 0) }}</h5>
                            <p class="text-muted mb-0">Commission</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-info">
                                <i class="fas fa-store"></i>
                            </div>
                            <h5 class="mb-1">{{ $suppliersCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Suppliers</p>
                        </div>
                    </div>

                @elseif($user->isVeterinary())
                    <!-- Veterinary Stats -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-primary">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5 class="mb-1">{{ $appointmentsCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Appointments</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-success">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <h5 class="mb-1">{{ $farmersServedCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Farmers Served</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="mb-1">{{ $pendingRequestsCount ?? 0 }}</h5>
                            <p class="text-muted mb-0">Pending Requests</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon stats-icon-info">
                                <i class="fas fa-star"></i>
                            </div>
                            <h5 class="mb-1">{{ $rating ?? '4.8' }}/5.0</h5>
                            <p class="text-muted mb-0">Rating</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-toggle="tab" data-target="#personal" type="button" role="tab">
                        <i class="fas fa-user-edit me-2"></i>Personal Information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-toggle="tab" data-target="#password" type="button" role="tab">
                        <i class="fas fa-key me-2"></i>Change Password
                    </button>
                </li>

                <!-- Add Verification Tab -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="verification-tab" data-toggle="tab" data-target="#verification" type="button" role="tab">
                        <i class="fas fa-shield-alt me-2"></i>Account Verification
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" data-toggle="tab" data-target="#account" type="button" role="tab">
                        <i class="fas fa-cog me-2"></i>Account Settings
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content p-4" id="profileTabsContent">
            <!-- Personal Information Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2 text-success"></i>Full Name
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   placeholder="Enter your full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2 text-success"></i>Email Address
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   placeholder="example@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-success mt-1">
                                <i class="fas fa-check-circle me-1"></i>
                                Email verified
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2 text-success"></i>Phone Number
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">+254</span>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $user->phone ? preg_replace('/[^0-9]/', '', substr($user->phone, 3)) : '') }}"
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

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-2 text-success"></i>Location / Address
                            </label>
                            <input type="text"
                                   class="form-control @error('address') is-invalid @enderror"
                                   id="address"
                                   name="address"
                                   value="{{ old('address', $user->address) }}"
                                   placeholder="Enter your city/town and area">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                e.g., Nairobi, Westlands or Eldoret, Langas
                            </small>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user-tag me-2 text-success"></i>Account Role
                            </label>
                            <div class="form-control bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ $user->getRoleIcon() }} me-3 text-{{ $user->getRoleBadgeColor() }}"></i>
                                    <div>
                                        <strong>{{ $user->getRoleName() }}</strong>
                                        <small class="d-block text-muted">This role determines your access and permissions</small>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Role cannot be changed here. Contact support for role changes.
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success-custom">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Tab -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-2 text-success"></i>Current Password
                            </label>
                            <div class="position-relative">
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       required
                                       placeholder="Enter current password">
                                <button type="button" class="toggle-password" id="toggleCurrentPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-key me-2 text-success"></i>New Password
                            </label>
                            <div class="position-relative">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Create new password">
                                <button type="button" class="toggle-password" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            <div class="password-strength mt-2">
                                <small class="d-block mb-1">Password Strength: <span id="strengthText">None</span></small>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" id="strengthBar" role="progressbar"></div>
                                </div>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-key me-2 text-success"></i>Confirm New Password
                            </label>
                            <div class="position-relative">
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    placeholder="Confirm new password">
                                <button type="button" class="toggle-password" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Password requirements:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Minimum 8 characters</li>
                            <li>Include both letters and numbers</li>
                            <li>Should not match your current password</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success-custom">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Verification Tab -->
            <div class="tab-pane fade" id="verification" role="tabpanel">
                <div class="verification-section">
                    <h5 class="mb-4">
                        <i class="fas fa-shield-alt me-2 text-success"></i>Account Verification
                    </h5>

                    <!-- Verification Status -->
<div class="card mb-4 verification-status-card border-left-{{ $user->verificationBadge['color'] ?? 'secondary' }}">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="card-title">
                    <i class="fas {{ $user->verificationBadge['icon'] ?? 'fa-user' }} text-{{ $user->verificationBadge['color'] ?? 'secondary' }} me-2"></i>
                    Verification Status:
                    <span class="badge badge-{{ $user->verificationBadge['color'] ?? 'secondary' }}">
                        {{ $user->verificationBadge['text'] ?? 'Not Verified' }}
                    </span>
                </h6>

                @if($user->isVerified())
                    <p class="mb-0">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Your account is verified. You have access to all features.
                        @if($user->verified_at)
                            <small class="d-block text-muted mt-1">
                                Verified on: {{ $user->verified_at->format('M d, Y') }}
                            </small>
                        @endif
                    </p>
                @elseif($hasPendingRequest)
                    <p class="mb-0">
                        <i class="fas fa-clock text-warning me-1"></i>
                        Your verification request is under review. This usually takes 24-48 hours.
                        @if($user->latestVerificationRequest)
                            <small class="d-block text-muted mt-1">
                                Submitted on: {{ $user->latestVerificationRequest->created_at->format('M d, Y') }}
                            </small>
                        @endif
                    </p>
                @elseif($verificationStatus === 'rejected')
                    <p class="mb-0">
                        <i class="fas fa-times-circle text-danger me-1"></i>
                        Your verification request was rejected. Please review the feedback and reapply.
                    </p>
                    @if($user->latestVerificationRequest && $user->latestVerificationRequest->admin_notes)
                        <div class="alert alert-warning mt-2">
                            <strong>Admin Feedback:</strong>
                            {{ $user->latestVerificationRequest->admin_notes }}
                        </div>
                    @endif
                @else
                    <p class="mb-0">
                        <i class="fas fa-info-circle text-secondary me-1"></i>
                        Your account is not verified. Apply now to unlock all features.
                    </p>
                @endif
            </div>

            <div class="col-md-4 text-md-right">
                @if($canApply)
                    <button class="btn btn-success-custom" data-toggle="modal" data-target="#applyVerificationModal">
                        <i class="fas fa-paper-plane me-2"></i>Apply for Verification
                    </button>
                @elseif($hasPendingRequest && $user->latestVerificationRequest)
                    <form action="{{ route('verification.cancel', $user->latestVerificationRequest->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Cancel your verification request?')">
                            <i class="fas fa-times me-2"></i>Cancel Request
                        </button>
                    </form>
                @elseif($verificationStatus === 'rejected')
                    <button class="btn btn-warning" data-toggle="modal" data-target="#applyVerificationModal">
                        <i class="fas fa-redo me-2"></i>Reapply
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

                    <!-- Benefits of Verification -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Benefits of Verified Account:</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 benefit-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                            <h6>Trust & Credibility</h6>
                                            <p class="text-muted small">Build trust with other users and increase your credibility</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 benefit-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-unlock fa-2x text-success mb-3"></i>
                                            <h6>Full Access</h6>
                                            <p class="text-muted small">Access all platform features without restrictions</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 benefit-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-star fa-2x text-success mb-3"></i>
                                            <h6>Priority Support</h6>
                                            <p class="text-muted small">Get priority customer support and faster response times</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification History -->
                    @if($user->verificationRequests && $user->verificationRequests->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-history me-2"></i>Verification History
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm verification-history-table">
                                        <thead>
                                            <tr>
                                                <th>Date Applied</th>
                                                <th>Document Type</th>
                                                <th>Status</th>
                                                <th>Reviewed On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->verificationRequests as $request)
                                                <tr>
                                                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $request->document_type)) }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $request->statusBadge['color'] ?? 'secondary' }}">
                                                            <i class="fas {{ $request->statusBadge['icon'] ?? 'fa-question' }} me-1"></i>
                                                            {{ ucfirst($request->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($request->reviewed_at)
                                                            {{ $request->reviewed_at->format('M d, Y') }}
                                                        @else
                                                            <span class="text-muted">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($request->status == 'pending')
                                                            <form action="{{ route('verification.cancel', $request) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this request?')">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Settings Tab -->
            <div class="tab-pane fade" id="account" role="tabpanel">
                <div class="row">
                    <!-- Account Information -->
                    <div class="col-md-12 mb-4">
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    <i class="fas fa-info-circle me-2 text-success"></i>Account Information
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row mb-3">
                                            <dt class="col-sm-5">Account ID:</dt>
                                            <dd class="col-sm-7"><code>{{ $user->id }}</code></dd>

                                            <dt class="col-sm-5">Role:</dt>
                                            <dd class="col-sm-7">
                                                <span class="badge badge-{{ $user->getRoleBadgeColor() }}">
                                                    <i class="fas fa-{{ $user->getRoleIcon() }} me-1"></i>
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </dd>

                                            <dt class="col-sm-5">Account Created:</dt>
                                            <dd class="col-sm-7">{{ $user->created_at->format('M d, Y h:i A') }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row mb-3">
                                            <dt class="col-sm-5">Last Updated:</dt>
                                            <dd class="col-sm-7">{{ $user->updated_at->format('M d, Y h:i A') }}</dd>

                                            <dt class="col-sm-5">Last Login:</dt>
                                            <dd class="col-sm-7">
                                                @if($user->last_login_at)
                                                    {{ $user->last_login_at->format('M d, Y h:i A') }}
                                                    <small class="d-block text-muted">({{ $user->last_login_at->diffForHumans() }})</small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </dd>

                                            <dt class="col-sm-5">Verification Status:</dt>
                                            <dd class="col-sm-7">
                                                <span class="badge badge-{{ $user->verificationBadge['color'] ?? 'secondary' }}">
                                                    <i class="fas {{ $user->verificationBadge['icon'] ?? 'fa-user' }} me-1"></i>
                                                    {{ $user->verificationBadge['text'] ?? 'Not Verified' }}
                                                </span>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="col-md-12">
                        <div class="card border border-danger">
                            <div class="card-body">
                                <h5 class="card-title text-danger mb-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <h6>Export Your Data</h6>
                                        <p class="text-muted small mb-3">
                                            Download all your personal data in a readable format.
                                        </p>
                                        <button class="btn btn-outline-success-custom w-100" data-toggle="modal" data-target="#exportModal">
                                            <i class="fas fa-download me-2"></i>Export Data
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-danger">Delete Account</h6>
                                        <p class="text-muted small mb-3">
                                            Permanently delete your account and all associated data.
                                        </p>
                                        <button class="btn btn-outline-danger w-100" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash-alt me-2"></i>Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal (AdminLTE/Bootstrap Style) -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="changePasswordModalLabel">
                    <i class="fas fa-key mr-2"></i>Change Password
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="passwordChangeForm" method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="modal_current_password" class="form-label">
                            <i class="fas fa-lock mr-2 text-success"></i>Current Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="modal_current_password"
                                   name="current_password"
                                   required
                                   placeholder="Enter your current password">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password-modal" data-target="modal_current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="currentPasswordError"></div>
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="modal_password" class="form-label">
                            <i class="fas fa-key mr-2 text-success"></i>New Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="modal_password"
                                   name="password"
                                   required
                                   placeholder="Enter new password">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password-modal" data-target="modal_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <small class="text-muted">Password Strength: <span id="modalStrengthText">None</span></small>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" id="modalStrengthBar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="form-group">
                        <label for="modal_password_confirmation" class="form-label">
                            <i class="fas fa-key mr-2 text-success"></i>Confirm New Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="modal_password_confirmation"
                                   name="password_confirmation"
                                   required
                                   placeholder="Confirm new password">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password-modal" data-target="modal_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="passwordConfirmationError"></div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="password-requirements">
                        <h6><i class="fas fa-info-circle mr-2 text-success"></i>Password Requirements</h6>
                        <ul class="mb-0">
                            <li id="reqLength">Minimum 8 characters</li>
                            <li id="reqLetter">At least one letter</li>
                            <li id="reqNumber">At least one number</li>
                            <li id="reqMatch">Passwords must match</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="savePasswordBtn" disabled>
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Apply Verification Modal -->
<div class="modal fade" id="applyVerificationModal" tabindex="-1" role="dialog" aria-labelledby="applyVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="applyVerificationModalLabel">
                    <i class="fas fa-shield-alt mr-2"></i>Apply for Account Verification
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data" id="verificationForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Verification helps build trust and gives you access to all platform features.
                        Your documents will be securely stored and only visible to administrators.
                    </div>

                    <!-- Document Type -->
                    <div class="form-group">
                        <label for="document_type" class="form-label">
                            <i class="fas fa-id-card mr-2 text-success"></i>Document Type
                        </label>
                        <select class="form-control @error('document_type') is-invalid @enderror"
                                id="document_type"
                                name="document_type"
                                required>
                            <option value="">Select document type</option>
                            <option value="id_card">National ID Card</option>
                            <option value="driving_license">Driving License</option>
                            <option value="business_registration">Business Registration</option>
                            <option value="other">Other Official Document</option>
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Document Front -->
                    <div class="form-group">
                        <label for="document_front" class="form-label">
                            <i class="fas fa-image mr-2 text-success"></i>Document Front Photo
                        </label>
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input @error('document_front') is-invalid @enderror"
                                   id="document_front"
                                   name="document_front"
                                   accept="image/*"
                                   required>
                            <label class="custom-file-label" for="document_front" id="frontLabel">
                                Choose file...
                            </label>
                        </div>
                        <small class="text-muted">
                            Clear photo of the front side of your document (max 5MB)
                        </small>
                        <div class="mt-2" id="frontPreview"></div>
                        @error('document_front')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Document Back (Optional) -->
                    <div class="form-group">
                        <label for="document_back" class="form-label">
                            <i class="fas fa-image mr-2 text-success"></i>Document Back Photo (Optional)
                        </label>
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input @error('document_back') is-invalid @enderror"
                                   id="document_back"
                                   name="document_back"
                                   accept="image/*">
                            <label class="custom-file-label" for="document_back" id="backLabel">
                                Choose file...
                            </label>
                        </div>
                        <small class="text-muted">
                            Clear photo of the back side of your document (max 5MB)
                        </small>
                        <div class="mt-2" id="backPreview"></div>
                        @error('document_back')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Additional Information -->
                    <div class="form-group">
                        <label for="additional_info" class="form-label">
                            <i class="fas fa-comment-alt mr-2 text-success"></i>Additional Information (Optional)
                        </label>
                        <textarea class="form-control @error('additional_info') is-invalid @enderror"
                                  id="additional_info"
                                  name="additional_info"
                                  rows="3"
                                  placeholder="Add any additional information that might help with verification..."></textarea>
                        @error('additional_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group form-check">
                        <input type="checkbox"
                               class="form-check-input @error('terms') is-invalid @enderror"
                               id="terms"
                               name="terms"
                               required>
                        <label class="form-check-label" for="terms">
                            I certify that the information provided is accurate and belongs to me.
                            I understand that providing false information may result in account suspension.
                        </label>
                        @error('terms')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="submitVerificationBtn">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-export me-2"></i>Export Your Data
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Select the data you want to export:</p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="exportProfile" checked>
                    <label class="form-check-label" for="exportProfile">
                        Profile Information
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="exportOrders" checked>
                    <label class="form-check-label" for="exportOrders">
                        Order History
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="exportProducts">
                    <label class="form-check-label" for="exportProducts">
                        Products (Suppliers Only)
                    </label>
                </div>
                <div class="mt-3">
                    <label class="form-label">Export Format</label>
                    <select class="form-control">
                        <option value="json">JSON</option>
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-circle me-2"></i>Warning: This action cannot be undone!</h5>
                </div>
                <p>Deleting your account will:</p>
                <ul>
                    <li>Permanently delete your profile</li>
                    <li>Remove all your orders and history</li>
                    <li>Delete your products (if you're a supplier)</li>
                    <li>Cancel any pending transactions</li>
                </ul>
                <div class="form-group mt-3">
                    <label for="deletePassword" class="form-label">Enter your password to confirm:</label>
                    <input type="password" class="form-control" id="deletePassword" placeholder="Your password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="password" id="deletePasswordInput">
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash-alt me-2"></i>Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Phone number formatting
    function formatPhoneNumber(phone) {
        let phoneStr = phone.toString().replace(/\D/g, '');

        // Remove leading 0 if present
        if (phoneStr.startsWith('0')) {
            phoneStr = phoneStr.substring(1);
        }

        // Format with spaces for readability
        if (phoneStr.length >= 9) {
            phoneStr = phoneStr.substring(0, 9);
            return phoneStr.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
        }

        return phoneStr;
    }

    // Password strength indicator for main form
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

        // Update progress bar
        let width = 0;
        let color = '#dc3545';
        let text = 'Weak';

        if (password.length === 0) {
            width = 0;
            text = 'None';
        } else if (strength <= 2) {
            width = 25;
            color = '#dc3545';
            text = 'Weak';
        } else if (strength <= 3) {
            width = 50;
            color = '#ffc107';
            text = 'Fair';
        } else if (strength <= 4) {
            width = 75;
            color = '#28a745';
            text = 'Good';
        } else {
            width = 100;
            color = '#2e7d32';
            text = 'Strong';
        }

        strengthBar.css('width', width + '%');
        strengthBar.css('background-color', color);
        strengthText.text(text);
    }

    // Modal Password Strength Check
    function checkModalPasswordStrength(password) {
        let strength = 0;
        let requirements = {
            length: false,
            letter: false,
            number: false,
            match: false
        };

        // Length check
        if (password.length >= 8) {
            strength += 1;
            requirements.length = true;
            $('#reqLength').addClass('requirement-met');
        } else {
            $('#reqLength').removeClass('requirement-met');
        }

        // Character type checks
        if (/[a-zA-Z]/.test(password)) {
            strength += 1;
            requirements.letter = true;
            $('#reqLetter').addClass('requirement-met');
        } else {
            $('#reqLetter').removeClass('requirement-met');
        }

        if (/[0-9]/.test(password)) {
            strength += 1;
            requirements.number = true;
            $('#reqNumber').addClass('requirement-met');
        } else {
            $('#reqNumber').removeClass('requirement-met');
        }

        // Update progress bar
        const strengthBar = $('#modalStrengthBar');
        const strengthText = $('#modalStrengthText');
        let width = 0;
        let color = '#dc3545';
        let text = 'Weak';

        if (password.length === 0) {
            width = 0;
            text = 'None';
            strengthBar.removeClass('bg-danger bg-warning bg-success bg-primary');
        } else if (strength <= 2) {
            width = 25;
            color = '#dc3545';
            text = 'Weak';
            strengthBar.removeClass('bg-warning bg-success bg-primary').addClass('bg-danger');
        } else if (strength <= 3) {
            width = 50;
            color = '#ffc107';
            text = 'Fair';
            strengthBar.removeClass('bg-danger bg-success bg-primary').addClass('bg-warning');
        } else if (strength <= 4) {
            width = 75;
            color = '#28a745';
            text = 'Good';
            strengthBar.removeClass('bg-danger bg-warning bg-primary').addClass('bg-success');
        } else {
            width = 100;
            color = '#2e7d32';
            text = 'Strong';
            strengthBar.removeClass('bg-danger bg-warning bg-success').addClass('bg-primary');
        }

        strengthBar.css('width', width + '%');
        strengthBar.css('background-color', color);
        strengthText.text(text);

        // Check password match
        const confirmPassword = $('#modal_password_confirmation').val();
        if (confirmPassword !== '' && password === confirmPassword) {
            requirements.match = true;
            $('#reqMatch').addClass('requirement-met');
            $('#modal_password_confirmation').removeClass('is-invalid');
            $('#passwordConfirmationError').text('');
        } else if (confirmPassword !== '') {
            $('#reqMatch').removeClass('requirement-met');
            $('#modal_password_confirmation').addClass('is-invalid');
            $('#passwordConfirmationError').text('Passwords do not match');
        } else {
            $('#reqMatch').removeClass('requirement-met');
        }

        return requirements;
    }

    // Toggle password visibility for modal
    $(document).on('click', '.toggle-password-modal', function() {
        const target = $(this).data('target');
        const input = $('#' + target);
        const icon = $(this).find('i');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        icon.toggleClass('fa-eye fa-eye-slash');
    });

    // Toggle password visibility for main form
    function setupPasswordToggle(buttonId, inputId) {
        $(buttonId).click(function() {
            const passwordInput = $(inputId);
            const icon = $(this).find('i');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
    }

    // Setup password toggles for main form
    setupPasswordToggle('#toggleCurrentPassword', '#current_password');
    setupPasswordToggle('#toggleNewPassword', '#password');
    setupPasswordToggle('#toggleConfirmPassword', '#password_confirmation');

    // Format phone button
    $('#formatPhone').click(function() {
        const phoneInput = $('#phone');
        const currentValue = phoneInput.val();
        const formatted = formatPhoneNumber(currentValue);
        phoneInput.val(formatted);
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

    // Real-time password strength update for main form
    $('#password').on('input', function() {
        updatePasswordStrength();

        // Check password match in real-time
        const confirmPassword = $('#password_confirmation').val();
        if (confirmPassword !== '' && $(this).val() !== confirmPassword) {
            $('#password_confirmation').addClass('is-invalid');
            if (!$('#password_confirmation').next('.invalid-feedback').length) {
                $('#password_confirmation').after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else {
            $('#password_confirmation').removeClass('is-invalid');
            $('#password_confirmation').next('.invalid-feedback').remove();
        }
    });

    // Check password match on confirm password input (main form)
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

    // Modal password validation
    function validateModalForm() {
        const currentPassword = $('#modal_current_password').val();
        const newPassword = $('#modal_password').val();
        const confirmPassword = $('#modal_password_confirmation').val();

        const requirements = checkModalPasswordStrength(newPassword);

        let isValid = true;

        // Clear previous errors
        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        // Validate current password
        if (!currentPassword) {
            $('#modal_current_password').addClass('is-invalid');
            $('#currentPasswordError').text('Please enter your current password');
            isValid = false;
        }

        // Validate new password
        if (!newPassword) {
            $('#modal_password').addClass('is-invalid');
            $('#passwordError').text('Please enter a new password');
            isValid = false;
        } else if (!requirements.length || !requirements.letter || !requirements.number) {
            $('#modal_password').addClass('is-invalid');
            $('#passwordError').text('Password does not meet requirements');
            isValid = false;
        }

        // Validate password confirmation
        if (!confirmPassword) {
            $('#modal_password_confirmation').addClass('is-invalid');
            $('#passwordConfirmationError').text('Please confirm your new password');
            isValid = false;
        } else if (newPassword !== confirmPassword) {
            $('#modal_password_confirmation').addClass('is-invalid');
            $('#passwordConfirmationError').text('Passwords do not match');
            isValid = false;
        }

        // Enable/disable save button
        $('#savePasswordBtn').prop('disabled', !isValid);

        return isValid;
    }

    // Modal password input events
    $('#modal_current_password, #modal_password, #modal_password_confirmation').on('input', function() {
        if ($(this).attr('id') === 'modal_password') {
            checkModalPasswordStrength($(this).val());
        }
        validateModalForm();
    });

    // Modal form submission
    $('#passwordChangeForm').submit(function(e) {
        e.preventDefault();

        if (!validateModalForm()) {
            return;
        }

        // Show loading state
        const saveBtn = $('#savePasswordBtn');
        const originalText = saveBtn.html();
        saveBtn.prop('disabled', true);
        saveBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');

        // In a real application, you would use AJAX here
        // For now, we'll simulate a successful save
        setTimeout(() => {
            // Success state
            saveBtn.html('<i class="fas fa-check mr-2"></i>Saved!');

            setTimeout(() => {
                // Close modal using Bootstrap's modal method
                $('#changePasswordModal').modal('hide');

                // Reset form and button after modal is hidden
                setTimeout(() => {
                    $('#passwordChangeForm')[0].reset();
                    saveBtn.html(originalText);

                    // Clear all validation states
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('.requirement-met').removeClass('requirement-met');
                    $('#modalStrengthBar').css('width', '0%');
                    $('#modalStrengthText').text('None');

                    // Reset eye icons
                    $('.toggle-password-modal i').removeClass('fa-eye-slash').addClass('fa-eye');

                    // Show success toast/alert
                    showNotification('Password updated successfully!', 'success');
                }, 500);
            }, 1000);
        }, 1500);
    });

    // Reset modal when closed
    $('#changePasswordModal').on('hidden.bs.modal', function () {
        $('#passwordChangeForm')[0].reset();
        $('#savePasswordBtn').prop('disabled', true);
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.requirement-met').removeClass('requirement-met');
        $('#modalStrengthBar').css('width', '0%');
        $('#modalStrengthText').text('None');
        $('.toggle-password-modal i').removeClass('fa-eye-slash').addClass('fa-eye');
    });

    // Verification Modal JavaScript
    // File upload preview
    $('#document_front').on('change', function(e) {
        const file = e.target.files[0];
        const label = $('#frontLabel');
        const preview = $('#frontPreview');

        if (file) {
            label.text(file.name);

            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                $(this).val('');
                label.text('Choose file...');
                preview.empty();
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.html(`
                    <div class="document-preview">
                        <img src="${e.target.result}" alt="Preview" class="img-fluid">
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        } else {
            label.text('Choose file...');
            preview.empty();
        }
    });

    $('#document_back').on('change', function(e) {
        const file = e.target.files[0];
        const label = $('#backLabel');
        const preview = $('#backPreview');

        if (file) {
            label.text(file.name);

            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                $(this).val('');
                label.text('Choose file...');
                preview.empty();
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.html(`
                    <div class="document-preview">
                        <img src="${e.target.result}" alt="Preview" class="img-fluid">
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        } else {
            label.text('Choose file...');
            preview.empty();
        }
    });

    // Reset verification modal when closed
    $('#applyVerificationModal').on('hidden.bs.modal', function () {
        $('#verificationForm')[0].reset();
        $('#frontLabel').text('Choose file...');
        $('#backLabel').text('Choose file...');
        $('#frontPreview').empty();
        $('#backPreview').empty();
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
    });

    // Verification form validation
    $('#verificationForm').submit(function(e) {
        const frontFile = $('#document_front')[0].files[0];
        const backFile = $('#document_back')[0].files[0];
        const terms = $('#terms').is(':checked');

        let isValid = true;

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Validate document type
        if (!$('#document_type').val()) {
            $('#document_type').addClass('is-invalid');
            $('#document_type').after('<div class="invalid-feedback">Please select a document type</div>');
            isValid = false;
        }

        // Validate front file
        if (!frontFile) {
            $('#document_front').addClass('is-invalid');
            $('#document_front').closest('.custom-file').after('<div class="invalid-feedback d-block">Please upload document front photo</div>');
            isValid = false;
        } else if (frontFile.size > 5 * 1024 * 1024) {
            $('#document_front').addClass('is-invalid');
            $('#document_front').closest('.custom-file').after('<div class="invalid-feedback d-block">File size must be less than 5MB</div>');
            isValid = false;
        }

        // Validate back file if provided
        if (backFile && backFile.size > 5 * 1024 * 1024) {
            $('#document_back').addClass('is-invalid');
            $('#document_back').closest('.custom-file').after('<div class="invalid-feedback d-block">File size must be less than 5MB</div>');
            isValid = false;
        }

        // Validate terms
        if (!terms) {
            $('#terms').addClass('is-invalid');
            $('#terms').closest('.form-check').after('<div class="invalid-feedback d-block">You must accept the terms and conditions</div>');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        const submitBtn = $('#submitVerificationBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...');

        return true;
    });

    // Delete account confirmation
    $('#confirmDelete').click(function() {
        const password = $('#deletePassword').val();
        if (!password) {
            alert('Please enter your password to confirm deletion.');
            return;
        }

        $('#deletePasswordInput').val(password);
        $('#deleteAccountForm').submit();
    });

    // Tab switching
    $('#profileTabs button').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Notification function (AdminLTE style)
    function showNotification(message, type) {
        // Create toast notification
        const toast = $(`
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="toast-header bg-${type} text-white">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong class="mr-auto">Success</strong>
                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `);

        $('body').append(toast);
        toast.toast('show');

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.toast('dispose');
            toast.remove();
        }, 5000);
    }

    // Initialize on page load
    updatePasswordStrength();
});
</script>
@endpush
