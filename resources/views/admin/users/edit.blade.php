@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('styles')
<style>
    /* User Profile Header */
    .user-profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 2rem 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        background-color: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        margin: 0 auto;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .profile-avatar img {
        border-radius: 50%;
    }

    .verification-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .stats-card {
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .form-section {
        background: #fff;
        border-left: 4px solid #007bff;
        padding: 20px 15px 20px 20px;
        margin-bottom: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-section h6 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 10px;
        border-bottom: 2px solid #f8f9fa;
    }

    .role-badge {
        font-size: 0.8rem;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .verification-history {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .verification-history::-webkit-scrollbar {
        width: 5px;
    }

    .verification-history::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .verification-history::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quick-action-btn {
        border-radius: 8px;
        padding: 12px;
        text-align: left;
        transition: all 0.3s ease;
        margin-bottom: 8px;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .quick-action-btn:hover {
        transform: translateX(5px);
    }

    .quick-action-btn i {
        font-size: 1.1rem;
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    .user-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .info-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px 15px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .info-label {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }

    .info-value {
        font-weight: 600;
        font-size: 1rem;
    }

    /* Form Styling */
    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .user-profile-header {
            padding: 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }

        .form-section {
            padding: 15px;
        }
    }

    /* Loading spinner */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- User Profile Header -->
    <div class="card shadow border-0 mb-4">
        <div class="user-profile-header">
            <div class="row align-items-center">
                <div class="col-lg-2 text-center mb-3 mb-lg-0">
                    <div class="profile-avatar position-relative">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="w-100 h-100"
                                 onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<i class=\"fas fa-user\"></i>';">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                        @if($user->is_active)
                            <span class="position-absolute bottom-0 end-0 badge bg-success rounded-circle p-2"
                                  style="transform: translate(25%, 25%);"
                                  data-toggle="tooltip"
                                  title="Active Account">
                                <i class="fas fa-check"></i>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7">
                    <h2 class="h3 font-weight-bold mb-2">{{ $user->name }}</h2>
                    <p class="mb-3">
                        <i class="fas fa-envelope mr-2 opacity-75"></i>
                        {{ $user->email }}
                        @if($user->phone)
                            <span class="ml-4">
                                <i class="fas fa-phone mr-2 opacity-75"></i>
                                {{ $user->phone }}
                            </span>
                        @endif
                    </p>

                    @if($user->business_name)
                        <p class="mb-3">
                            <i class="fas fa-building mr-2 opacity-75"></i>
                            <strong>{{ $user->business_name }}</strong>
                        </p>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        <!-- Role Badge -->
                        @if($user->role == 'admin')
                            <span class="role-badge bg-danger">
                                <i class="fas fa-crown mr-1"></i>Admin
                            </span>
                        @elseif($user->role == 'supplier')
                            <span class="role-badge bg-info">
                                <i class="fas fa-store mr-1"></i>Supplier
                            </span>
                        @elseif($user->role == 'farmer')
                            <span class="role-badge bg-success">
                                <i class="fas fa-tractor mr-1"></i>Farmer
                            </span>
                        @elseif($user->role == 'agent')
                            <span class="role-badge bg-warning">
                                <i class="fas fa-user-tie mr-1"></i>Agent
                            </span>
                        @elseif($user->role == 'veterinary')
                            <span class="role-badge bg-primary">
                                <i class="fas fa-stethoscope mr-1"></i>Veterinary
                            </span>
                        @endif

                        <!-- Verification Status -->
                        @if($user->is_verified && $user->verification_status == 'approved')
                            <span class="verification-badge bg-success text-white" id="verificationBadge">
                                <i class="fas fa-shield-check"></i>Verified
                            </span>
                        @elseif($user->verification_status == 'pending')
                            <span class="verification-badge bg-warning text-dark" id="verificationBadge">
                                <i class="fas fa-clock"></i>Pending
                            </span>
                        @elseif($user->verification_status == 'rejected')
                            <span class="verification-badge bg-danger text-white" id="verificationBadge">
                                <i class="fas fa-times-circle"></i>Rejected
                            </span>
                        @else
                            <span class="verification-badge bg-secondary text-white" id="verificationBadge">
                                <i class="fas fa-user"></i>Not Applied
                            </span>
                        @endif

                        <!-- Account Status -->
                        @if($user->is_active)
                            <span class="badge badge-success role-badge">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                        @else
                            <span class="badge badge-danger role-badge">
                                <i class="fas fa-ban mr-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="user-info-grid">
                        <div class="info-item">
                            <div class="info-label">Member Since</div>
                            <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>

                        @if($user->last_login_at)
                            <div class="info-item">
                                <div class="info-label">Last Login</div>
                                <div class="info-value">{{ $user->last_login_at->diffForHumans() }}</div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">User ID</div>
                            <div class="info-value">#{{ $user->id }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Edit Form -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-edit mr-2"></i>Edit User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="form-section">
                            <h6>
                                <i class="fas fa-info-circle mr-2 text-primary"></i>Basic Information
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="font-weight-bold text-gray-700">Full Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="font-weight-bold text-gray-700">Email Address *</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="font-weight-bold text-gray-700">Phone Number</label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_name" class="font-weight-bold text-gray-700">Business Name</label>
                                    <input type="text"
                                           class="form-control @error('business_name') is-invalid @enderror"
                                           id="business_name"
                                           name="business_name"
                                           value="{{ old('business_name', $user->business_name) }}">
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="font-weight-bold text-gray-700">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="form-section">
                            <h6>
                                <i class="fas fa-cog mr-2 text-primary"></i>Account Settings
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="font-weight-bold text-gray-700">User Role *</label>
                                    <select class="form-control @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            required>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="supplier" {{ old('role', $user->role) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                        <option value="farmer" {{ old('role', $user->role) == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                        <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                                        <option value="veterinary" {{ old('role', $user->role) == 'veterinary' ? 'selected' : '' }}>Veterinary</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-gray-700 d-block">Account Status</label>
                                    <div class="mt-2 d-flex gap-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="is_active"
                                                   id="active"
                                                   value="1"
                                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label text-success font-weight-bold" for="active">
                                                <i class="fas fa-check-circle mr-1"></i>Active
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="is_active"
                                                   id="inactive"
                                                   value="0"
                                                   {{ !old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger font-weight-bold" for="inactive">
                                                <i class="fas fa-times-circle mr-1"></i>Inactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Management -->
                        <div class="form-section">
                            <h6>
                                <i class="fas fa-shield-alt mr-2 text-primary"></i>Verification Management
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="verification_status" class="font-weight-bold text-gray-700">Verification Status</label>
                                    <select class="form-control @error('verification_status') is-invalid @enderror"
                                            id="verification_status"
                                            name="verification_status">
                                        <option value="">Select Status</option>
                                        <option value="pending" {{ old('verification_status', $user->verification_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('verification_status', $user->verification_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('verification_status', $user->verification_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('verification_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-gray-700 d-block">Quick Verification Actions</label>
                                    <div class="mt-2">
                                        <div class="btn-group w-100" role="group">
                                            @if($user->verification_status != 'approved')
                                                <button type="button"
                                                        class="btn btn-success verify-btn"
                                                        onclick="handleVerification('verify', '{{ $user->id }}', '{{ addslashes($user->name) }}')"
                                                        id="verifyBtn">
                                                    <i class="fas fa-check-circle mr-1"></i> Verify User
                                                </button>
                                            @endif

                                            @if($user->verification_status == 'approved')
                                                <button type="button"
                                                        class="btn btn-danger unverify-btn"
                                                        onclick="handleVerification('unverify', '{{ $user->id }}', '{{ addslashes($user->name) }}')"
                                                        id="unverifyBtn">
                                                    <i class="fas fa-times-circle mr-1"></i> Unverify User
                                                </button>
                                            @endif
                                        </div>
                                        <small class="text-muted mt-2 d-block">Quick actions will verify/unverify immediately and send email notifications.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="verification_notes" class="font-weight-bold text-gray-700">Verification Notes (Optional)</label>
                                <textarea class="form-control"
                                          id="verification_notes"
                                          name="verification_notes"
                                          rows="2"
                                          placeholder="Add notes about verification decision...">{{ old('verification_notes', $user->verification_notes) }}</textarea>
                                <small class="text-muted">These notes will be visible to the user.</small>
                            </div>
                        </div>

                        <!-- Password Reset (Optional) -->
                        <div class="form-section">
                            <h6>
                                <i class="fas fa-key mr-2 text-primary"></i>Password Reset
                            </h6>

                            <div class="alert alert-info border-0 bg-light-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Leave password fields empty if you don't want to change the password.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="font-weight-bold text-gray-700">New Password</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="font-weight-bold text-gray-700">Confirm Password</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-top pt-4 mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm">
                                        <i class="fas fa-save mr-2"></i>Save Changes
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary btn-lg btn-block">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - User Stats & Actions -->
        <div class="col-xl-4 col-lg-5">
            <!-- User Statistics -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-2"></i>User Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $stats = [
                                'Products' => [
                                    'value' => $user->products_count ?? 0,
                                    'icon' => 'fas fa-box',
                                    'color' => 'bg-info text-white'
                                ],
                                'Orders' => [
                                    'value' => $user->orders_count ?? 0,
                                    'icon' => 'fas fa-shopping-cart',
                                    'color' => 'bg-success text-white'
                                ],
                                'Revenue' => [
                                    'value' => 'KES ' . number_format($user->total_revenue ?? 0),
                                    'icon' => 'fas fa-money-bill-wave',
                                    'color' => 'bg-warning text-dark'
                                ],
                                'Rating' => [
                                    'value' => $user->rating ?? 'N/A',
                                    'icon' => 'fas fa-star',
                                    'color' => 'bg-danger text-white'
                                ],
                            ];
                        @endphp

                        @foreach($stats as $label => $stat)
                            <div class="col-6 mb-3">
                                <div class="stats-card {{ $stat['color'] }} p-3 text-center">
                                    <i class="{{ $stat['icon'] }} fa-2x mb-2 opacity-75"></i>
                                    <div class="h4 mb-1 font-weight-bold">{{ $stat['value'] }}</div>
                                    <div class="stat-label">{{ $label }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($user->role == 'supplier')
                            <a href="{{ route('admin.products.index', ['supplier' => $user->id]) }}"
                               class="quick-action-btn btn-info">
                                <span><i class="fas fa-boxes mr-2"></i>View Products</span>
                                <span class="badge badge-light">{{ $user->products_count ?? 0 }}</span>
                            </a>
                        @endif

                        @if($user->role == 'agent')
                            <a href="{{ route('admin.orders.index', ['agent' => $user->id]) }}"
                               class="quick-action-btn btn-info">
                                <span><i class="fas fa-shopping-cart mr-2"></i>View Orders</span>
                                <span class="badge badge-light">{{ $user->orders_count ?? 0 }}</span>
                            </a>
                        @endif

                        <button type="button"
                                class="quick-action-btn btn-warning send-email-btn"
                                data-user-id="{{ $user->id }}"
                                data-user-email="{{ $user->email }}">
                            <span><i class="fas fa-envelope mr-2"></i>Send Email</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>

                        @if($user->id !== auth()->id())
                            <button type="button"
                                    class="quick-action-btn btn-danger"
                                    onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')">
                                <span><i class="fas fa-trash mr-2"></i>Delete User</span>
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Verification History -->
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Verification History
                    </h6>
                </div>
                <div class="card-body verification-history" id="verificationHistory">
                    @if($user->verified_at || $user->verified_by || $user->verification_notes)
                        <div class="mb-3">
                            @if($user->is_verified && $user->verified_at)
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-calendar-check mr-1"></i>Verified On
                                    </div>
                                    <div class="font-weight-bold">{{ $user->verified_at->format('M d, Y h:i A') }}</div>
                                </div>
                            @endif

                            @if($user->verified_by)
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-user-check mr-1"></i>Verified By
                                    </div>
                                    <div class="font-weight-bold">
                                        {{ \App\Models\User::find($user->verified_by)->name ?? 'Unknown' }}
                                    </div>
                                </div>
                            @endif

                            @if($user->verification_notes)
                                <div class="mb-3">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-sticky-note mr-1"></i>Notes
                                    </div>
                                    <div class="font-italic">{{ $user->verification_notes }}</div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">No verification history available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Email to {{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="sendEmailForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email_subject" class="font-weight-bold">Subject</label>
                        <input type="text" class="form-control" id="email_subject" required>
                    </div>
                    <div class="form-group">
                        <label for="email_message" class="font-weight-bold">Message</label>
                        <textarea class="form-control" id="email_message" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Verification handling
    window.handleVerification = function(action, userId, userName) {
        const confirmationMsg = action === 'verify'
            ? `Are you sure you want to verify "${userName}"? This will approve their verification request and send an email notification.`
            : `Are you sure you want to unverify "${userName}"? This will revoke their verification status and send an email notification.`;

        if (confirm(confirmationMsg)) {
            // Get verification notes
            const notes = document.getElementById('verification_notes')?.value || '';

            // Show loading state on the clicked button
            const button = event.target;
            const originalText = button.innerHTML;
            button.classList.add('btn-loading');
            button.disabled = true;

            // Disable the other button if it exists
            const otherButton = action === 'verify' ? document.getElementById('unverifyBtn') : document.getElementById('verifyBtn');
            if (otherButton) {
                otherButton.disabled = true;
            }

            // Send AJAX request
            fetch(`/admin/users/${userId}/verification`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    notes: notes
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);

                    // Update UI immediately without reloading
                    updateVerificationUI(action, notes);
                } else {
                    showAlert('error', data.message || 'An error occurred');
                    resetButtonState(button, originalText);
                    if (otherButton) otherButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred. Please try again.');
                resetButtonState(button, originalText);
                if (otherButton) otherButton.disabled = false;
            });
        }
    };

    // Update UI after verification
    function updateVerificationUI(action, notes) {
        const verificationBadge = document.getElementById('verificationBadge');
        const verifyBtn = document.getElementById('verifyBtn');
        const unverifyBtn = document.getElementById('unverifyBtn');
        const verificationStatusSelect = document.getElementById('verification_status');
        const verificationHistory = document.getElementById('verificationHistory');

        if (action === 'verify') {
            // Update badge
            verificationBadge.className = 'verification-badge bg-success text-white';
            verificationBadge.innerHTML = '<i class="fas fa-shield-check"></i>Verified';

            // Update select dropdown
            verificationStatusSelect.value = 'approved';

            // Update buttons
            if (verifyBtn) verifyBtn.remove();
            if (!unverifyBtn) {
                const buttonGroup = document.querySelector('.btn-group');
                buttonGroup.innerHTML = `
                    <button type="button"
                            class="btn btn-danger unverify-btn"
                            onclick="handleVerification('unverify', '{{ $user->id }}', '{{ addslashes($user->name) }}')"
                            id="unverifyBtn">
                        <i class="fas fa-times-circle mr-1"></i> Unverify User
                    </button>
                `;
            }

            // Update history
            const now = new Date();
            const formattedDate = now.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            verificationHistory.innerHTML = `
                <div class="mb-3">
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="text-muted small mb-1">
                            <i class="fas fa-calendar-check mr-1"></i>Verified On
                        </div>
                        <div class="font-weight-bold">${formattedDate}</div>
                    </div>

                    <div class="mb-3 pb-2 border-bottom">
                        <div class="text-muted small mb-1">
                            <i class="fas fa-user-check mr-1"></i>Verified By
                        </div>
                        <div class="font-weight-bold">{{ auth()->user()->name }}</div>
                    </div>

                    ${notes ? `
                    <div class="mb-3">
                        <div class="text-muted small mb-1">
                            <i class="fas fa-sticky-note mr-1"></i>Notes
                        </div>
                        <div class="font-italic">${notes}</div>
                    </div>
                    ` : ''}
                </div>
            `;

        } else { // unverify
            // Update badge
            verificationBadge.className = 'verification-badge bg-danger text-white';
            verificationBadge.innerHTML = '<i class="fas fa-times-circle"></i>Rejected';

            // Update select dropdown
            verificationStatusSelect.value = 'rejected';

            // Update buttons
            if (unverifyBtn) unverifyBtn.remove();
            if (!verifyBtn) {
                const buttonGroup = document.querySelector('.btn-group');
                buttonGroup.innerHTML = `
                    <button type="button"
                            class="btn btn-success verify-btn"
                            onclick="handleVerification('verify', '{{ $user->id }}', '{{ addslashes($user->name) }}')"
                            id="verifyBtn">
                        <i class="fas fa-check-circle mr-1"></i> Verify User
                    </button>
                `;
            }

            // Update history
            verificationHistory.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-3x mb-3 opacity-25"></i>
                    <p class="mb-0">User has been unverified</p>
                    ${notes ? `<small class="mt-2">Notes: ${notes}</small>` : ''}
                </div>
            `;
        }

        // Reset button states
        const currentButton = action === 'verify' ? document.getElementById('unverifyBtn') : document.getElementById('verifyBtn');
        if (currentButton) {
            resetButtonState(currentButton, currentButton.innerHTML);
        }
    }

    // Reset button loading state
    function resetButtonState(button, originalText) {
        button.classList.remove('btn-loading');
        button.innerHTML = originalText;
        button.disabled = false;
    }

    // Alert function
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-dismissible');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
        alertDiv.setAttribute('role', 'alert');

        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        alertDiv.innerHTML = `
            <i class="fas ${icon} mr-2"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;

        // Insert at the top of content
        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Send email button
    const sendEmailBtn = document.querySelector('.send-email-btn');
    const sendEmailModal = new bootstrap.Modal(document.getElementById('sendEmailModal'));

    if (sendEmailBtn) {
        sendEmailBtn.addEventListener('click', function() {
            sendEmailModal.show();
        });
    }

    // Send email form
    document.getElementById('sendEmailForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            subject: document.getElementById('email_subject').value,
            message: document.getElementById('email_message').value,
            _token: document.querySelector('input[name="_token"]').value
        };

        // Show loading on submit button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
        submitBtn.disabled = true;

        fetch(`/admin/users/{{ $user->id }}/send-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Email sent successfully!');
                sendEmailModal.hide();
                document.getElementById('sendEmailForm').reset();
            } else {
                showAlert('error', data.message || 'Failed to send email');
            }
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred. Please try again.');
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    });

    // Delete user
    window.confirmDelete = function(userId, userName) {
        if (confirm(`Are you sure you want to delete "${userName}"? This action cannot be undone.`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/users/${userId}`;
            form.submit();
        }
    };

    // Form validation
    const editForm = document.getElementById('editUserForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password && password !== confirmPassword) {
                e.preventDefault();
                showAlert('error', 'Passwords do not match!');
                return false;
            }

            if (password && password.length < 8) {
                e.preventDefault();
                showAlert('error', 'Password must be at least 8 characters long!');
                return false;
            }

            return true;
        });
    }

    // Role change confirmation
    const roleSelect = document.getElementById('role');
    const originalRole = roleSelect.value;

    roleSelect.addEventListener('change', function() {
        if (this.value !== originalRole) {
            if (!confirm(`Changing role from ${originalRole} to ${this.value}. Are you sure?`)) {
                this.value = originalRole;
            }
        }
    });

    // If verification status changed, confirm if it's a major change
    const verificationSelect = document.getElementById('verification_status');
    const originalVerificationStatus = verificationSelect.value;

    verificationSelect.addEventListener('change', function() {
        if (this.value !== originalVerificationStatus) {
            const oldStatus = originalVerificationStatus || 'Not Applied';
            const newStatus = this.value || 'Not Applied';

            if (!confirm(`Changing verification status from "${oldStatus}" to "${newStatus}". This will not send email notifications. Are you sure?`)) {
                this.value = originalVerificationStatus;
            }
        }
    });
});
</script>
@endpush
