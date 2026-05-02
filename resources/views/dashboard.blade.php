@extends('layouts.app')

@section('title', 'Dashboard')

@if(auth()->check() && auth()->user()->role === 'admin')
    <script>
        window.location.href = "{{ route('admin.dashboard') }}";
    </script>
@endif

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if(auth()->user()->isAdmin())
                                <h4 class="mb-0"><i class="fas fa-crown text-warning"></i> Admin Dashboard</h4>
                            @elseif(auth()->user()->isFarmer())
                                <h4 class="mb-0"><i class="fas fa-tractor text-success"></i> Farmer Dashboard</h4>
                            @elseif(auth()->user()->isClient())
                                <h4 class="mb-0"><i class="fas fa-shopping-cart text-primary"></i> Client Dashboard</h4>
                            @elseif(auth()->user()->isAgent())
                                <h4 class="mb-0"><i class="fas fa-user-tie text-warning"></i> Agent Dashboard</h4>
                            @elseif(auth()->user()->isVeterinary())
                                <h4 class="mb-0"><i class="fas fa-user-md text-danger"></i> Veterinary Dashboard</h4>
                            @endif
                        </div>
                        <span class="badge bg-success fs-6">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Welcome Message -->
                    <div class="text-center mb-5">
                        <h3 class="fw-bold">Welcome, {{ auth()->user()->name }}! 👋</h3>
                        <p class="text-muted">
                            @if(auth()->user()->isFarmer())
                                Ready to list and sell your poultry products?
                            @elseif(auth()->user()->isClient())
                                Ready to buy quality chicks and poultry products?
                            @elseif(auth()->user()->isAgent())
                                Ready to represent farms and boost sales?
                            @elseif(auth()->user()->isVeterinary())
                                Ready to offer poultry health services?
                            @else
                                Manage the entire My-Kuku-Soko platform
                            @endif
                        </p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                    <h6>Your Role</h6>
                                    <h4 class="fw-bold">{{ ucfirst(auth()->user()->role) }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-envelope fa-2x"></i>
                                    </div>
                                    <h6>Email</h6>
                                    <p class="fw-bold">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="text-info mb-2">
                                        <i class="fas fa-calendar-check fa-2x"></i>
                                    </div>
                                    <h6>Member Since</h6>
                                    <p class="fw-bold">{{ auth()->user()->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <h5 class="mb-3"><i class="fas fa-bolt text-warning me-2"></i>Quick Actions</h5>
                        <div class="row g-2">
                            @if(auth()->user()->isFarmer())
                                <div class="col-md-3">
                                    <a href="#" class="btn btn-outline-success w-100">
                                        <i class="fas fa-plus me-1"></i> Add Listing
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-list me-1"></i> My Listings
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" class="btn btn-outline-info w-100">
                                        <i class="fas fa-shopping-cart me-1"></i> Orders
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-chart-line me-1"></i> Analytics
                                    </a>
                                </div>
                            @endif

                            @if(auth()->user()->isClient())
                                <div class="col-md-4">
                                    <a href="#" class="btn btn-outline-success w-100">
                                        <i class="fas fa-store me-1"></i> Browse Market
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-history me-1"></i> My Orders
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="btn btn-outline-info w-100">
                                        <i class="fas fa-stethoscope me-1"></i> Services
                                    </a>
                                </div>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <div class="col-md-3">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-users me-1"></i> Users
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-chart-bar me-1"></i> Analytics
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" class="btn btn-outline-success w-100">
                                        <i class="fas fa-cog me-1"></i> Settings
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info w-100">
                                        <i class="fas fa-file-alt me-1"></i> Reports
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Role Information -->
                    <div class="mt-5 p-4 bg-light rounded">
                        <h6><i class="fas fa-info-circle me-2"></i>About Your Role</h6>
                        <p class="mb-0">
                            @if(auth()->user()->isFarmer())
                                As a <strong>Farmer</strong>, you can list chicks for sale, manage your farm profile, track orders, and request veterinary services for your poultry.
                            @elseif(auth()->user()->isClient())
                                As a <strong>Client</strong>, you can browse and purchase quality chicks, track your orders, and request veterinary services when needed.
                            @elseif(auth()->user()->isAgent())
                                As an <strong>Agent</strong>, you can represent multiple farms, manage listings on their behalf, and earn commissions from sales.
                            @elseif(auth()->user()->isVeterinary())
                                As a <strong>Veterinary</strong>, you can list your services, schedule farm visits, and help farmers maintain healthy poultry.
                            @else
                                As an <strong>Admin</strong>, you have full control over the platform including user management, content moderation, and system configuration.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
