<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'My-Kuku-Soko')</title>

    @stack('styles')

    <!-- AdminLTE + Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Toastr for notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #66bb6a;
            --dark-green: #1b5e20;
            --pale-green: #e8f5e9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7f5;
        }

        .wrapper {
            background: #f4f7f5;
        }

        /* Sidebar Styling */
        .sidebar-dark-success {
            background-color: var(--dark-green) !important;
        }

        .brand-link {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green)) !important;
            border-bottom: none !important;
        }

        .brand-text {
            font-weight: 600;
            letter-spacing: .5px;
            font-size: 1.3rem;
        }

        .farm-badge {
            background: var(--pale-green);
            color: var(--primary-green);
            font-size: .75rem;
            padding: 3px 10px;
            border-radius: 12px;
            margin-top: 5px;
            display: inline-block;
        }

        .nav-sidebar .nav-link.active {
            background-color: var(--light-green) !important;
            color: var(--dark-green) !important;
            font-weight: 500;
        }

        .nav-sidebar .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-sidebar .nav-icon {
            color: rgba(255, 255, 255, 0.7);
        }

        .nav-sidebar .nav-link.active .nav-icon {
            color: var(--dark-green) !important;
        }

        /* Navbar Styling */
        .main-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .navbar-light .navbar-nav .nav-link {
            color: #333;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--primary-green);
        }

        /* Notification dropdown styling */
        .dropdown-menu-lg {
            min-width: 320px !important;
            max-width: 400px !important;
        }

        .notification-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-unread {
            background-color: rgba(102, 187, 106, 0.05) !important;
        }

        .notification-unread .notification-title {
            font-weight: 600;
            color: #212529;
        }

        .notification-read .notification-title {
            color: #6c757d;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .notification-icon-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .notification-icon-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .notification-icon-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .notification-icon-info {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .notification-icon-primary {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        /* Content Styling */
        .content-wrapper {
            background: #f4f7f5;
            min-height: calc(100vh - 56px - 60px);
        }

        .content-header {
            padding: 15px 0;
        }

        .content-header h1 {
            font-size: 1.8rem;
            color: var(--dark-green);
            margin: 0;
            font-weight: 600;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            border-radius: 10px 10px 0 0 !important;
            border: none;
            font-weight: 500;
        }

        /* Button Styling */
        .btn-success {
            background-color: var(--primary-green) !important;
            border-color: var(--primary-green) !important;
        }

        .btn-success:hover {
            background-color: var(--dark-green) !important;
            border-color: var(--dark-green) !important;
        }

        .btn-outline-success {
            color: var(--primary-green) !important;
            border-color: var(--primary-green) !important;
        }

        .btn-outline-success:hover {
            background-color: var(--primary-green) !important;
            color: white !important;
        }

        /* Role Badges */
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }

        .role-badge.admin { background: #ffeb3b; color: #333; }
        .role-badge.farmer { background: #4caf50; color: white; }
        .role-badge.client { background: #2196f3; color: white; }
        .role-badge.agent { background: #ff9800; color: white; }
        .role-badge.veterinary { background: #f44336; color: white; }

        /* User Dropdown */
        .user-menu .dropdown-toggle::after {
            display: none;
        }

        .user-menu .user-image {
            width: 35px;
            height: 35px;
            background: var(--light-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Footer */
        .main-footer {
            background: #ffffff;
            border-top: 1px solid #e0e0e0;
            padding: 15px 0;
            color: #666;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 767.98px) {
            .brand-text {
                font-size: 1.1rem;
            }

            .content-wrapper {
                padding: 15px;
            }

            .dropdown-menu-lg {
                min-width: 280px !important;
                max-width: 320px !important;
                transform: translateX(-50px) !important;
            }
        }

        /* Active Navigation Item */
        .nav-item .nav-link.active {
            position: relative;
        }

        .nav-item .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 0 2px 2px 0;
        }

        /* Verification Alert Styling */
        .verification-alert {
            border-radius: 0;
            margin-bottom: 0;
            border-left: 4px solid;
        }

        .verification-alert.info {
            border-left-color: #17a2b8;
        }

        .verification-alert.warning {
            border-left-color: #ffc107;
        }

        .verification-alert.danger {
            border-left-color: #dc3545;
        }

        .verification-alert .container {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-light shadow-sm">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link fw-semibold">
                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ms-auto align-items-center">
    @auth
        <!-- Notifications Dropdown -->
    @if(auth()->user()->hasRole())
    <li class="nav-item dropdown notification-dropdown">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="far fa-bell"></i>
            @php
                // USE CUSTOM NOTIFICATION MODEL
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('read', false)
                    ->count();
            @endphp
            @if($unreadCount > 0)
                <span class="badge badge-warning navbar-badge notification-count">{{ $unreadCount }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notificationDropdown">
            <span class="dropdown-header">{{ $unreadCount }} New Notifications</span>
            <div class="dropdown-divider"></div>

            <div id="notificationList">
                @php
                    // USE CUSTOM NOTIFICATION MODEL
                    $notifications = \App\Models\Notification::where('user_id', auth()->id())
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                @forelse($notifications as $notification)
                    @php
                        $title = $notification->title;
                        $message = $notification->message;
                        $icon = $notification->icon ?? 'fas fa-bell';
                        $color = $notification->color ?? 'info';
                        $link = $notification->link ?? '#';
                        $isRead = $notification->read;
                    @endphp
                    <a href="{{ $link }}" class="dropdown-item notification-item d-flex align-items-start p-3 {{ $isRead ? 'notification-read' : 'notification-unread' }}" data-id="{{ $notification->id }}">
                        <div class="notification-icon mr-3 {{ 'notification-icon-' . $color }}">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="notification-title mb-1">{{ $title }}</h6>
                                <small class="notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-sm mb-0">{{ Str::limit($message, 50) }}</p>
                            @if(!$isRead)
                                <small class="text-success"><i class="fas fa-circle fa-xs"></i> Unread</small>
                            @endif
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                @empty
                    <div class="dropdown-item text-center text-muted py-4">
                        <i class="far fa-bell-slash fa-2x mb-2"></i>
                        <p class="mb-0">No notifications</p>
                    </div>
                @endforelse
            </div>

            <div class="dropdown-divider"></div>
            <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer text-center">
                <i class="fas fa-eye mr-1"></i> View All Notifications
            </a>
            @if($unreadCount > 0)
                <div class="dropdown-divider"></div>
                <form action="{{ route('notifications.markAllRead') }}" method="POST" id="markAllReadForm">
                    @csrf
                    <button type="submit" class="dropdown-item dropdown-footer text-center text-success">
                        <i class="fas fa-check-double mr-1"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </li>
@endif

        <!-- User Account Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                <div class="user-image me-2">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block">
                    <span class="fw-medium">{{ auth()->user()->name }}</span>
                    <br>
                    <small class="text-muted">
                        <span class="role-badge {{ auth()->user()->role ?? 'none' }}">
                            {{ ucfirst(auth()->user()->role ?? 'No Role') }}
                        </span>
                    </small>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-header">
                    <p class="mb-0">{{ auth()->user()->name }}</p>
                    <small>{{ auth()->user()->email }}</small>
                </li>
                <li><div class="dropdown-divider"></div></li>
                <li>
                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                @if(!auth()->user()->role)
                    <li>
                        <a class="dropdown-item" href="{{ route('select.role') }}">
                            <i class="fas fa-user-tag me-2"></i> Select Role
                        </a>
                    </li>
                @endif
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell me-2"></i> Notifications
                        @php
                            $unreadCount = auth()->user()->unreadNotifications()->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="badge badge-warning float-right">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li><div class="dropdown-divider"></div></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    @else
        <!-- Guest Links -->
        <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link">
                <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link btn btn-outline-success btn-sm ms-2">
                <i class="fas fa-user-plus me-1"></i> Register
            </a>
        </li>
    @endauth
</ul>
    </nav>

    <!-- ========== VERIFICATION ALERT ========== -->
    @auth
        @if(!Auth::user()->is_verified && Auth::user()->role !== 'admin')
            @php
                $alertClass = 'warning';
                $message = '';
                $linkText = '';
                $linkRoute = '';

                if (Auth::user()->verification_status === 'pending') {
                    $alertClass = 'info';
                    $message = 'Your verification request is pending approval.';
                    $linkText = 'Check status';
                    $linkRoute = route('verification.pending');
                } elseif (Auth::user()->verification_status === 'rejected') {
                    $alertClass = 'danger';
                    $message = 'Your verification was rejected.';
                    $linkText = 'View details';
                    $linkRoute = route('verification.rejected');
                } else {
                    $alertClass = 'warning';
                    $message = 'Please verify your account to access all features.';
                    $linkText = 'Verify now';
                    $linkRoute = route('verification.create');
                }
            @endphp
            <div class="alert alert-{{ $alertClass }} alert-dismissible fade show verification-alert rounded-0 mb-0" role="alert">
                <div class="container">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ $message }}
                    @if($linkRoute)
                        <a href="{{ $linkRoute }}" class="alert-link ml-1">{{ $linkText }}</a>
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
    @endauth
    <!-- ====================================== -->

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-success elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link text-center py-3">
            <i class="fas fa-egg fa-lg"></i>
            <span class="brand-text ml-2">My-Kuku-Soko</span>
            <!-- <div class="farm-badge rounded-pill mt-1 mx-auto">Poultry Marketplace</div> -->
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <!-- Dashboard -->
                    <!-- <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li> -->

                    <!-- Role-based Navigation -->
                    @auth
                        @if(auth()->user()->hasRole())
                            <!-- Independent Supplier Navigation -->
                            @if(auth()->user()->isSupplier())
                                @php
                                    // Define variables here so they're available throughout the menu
                                    $user = auth()->user();

                                    // Inventory counts
                                    $totalProducts = \App\Models\Marketplace::where('supplier_id', $user->id)->count();
                                    $lowStockCount = \App\Models\Marketplace::where('supplier_id', $user->id)
                                        ->where('quantity', '<=', 10)
                                        ->count();
                                    $categoryCount = \App\Models\Marketplace::where('supplier_id', $user->id)
                                        ->distinct('category')
                                        ->count('category');

                                    // Order stats - using simpler query to avoid undefined variable issues
                                    try {
                                        $orderStatsQuery = \App\Models\Order::whereHas('items.product', function($query) use ($user) {
                                            $query->where('supplier_id', $user->id);
                                        });

                                        $orderStats = (object) [
                                            'total' => $orderStatsQuery->count(),
                                            'pending' => $orderStatsQuery->where('status', 'pending')->count(),
                                            'processing' => $orderStatsQuery->where('status', 'processing')->count(),
                                            'shipped' => $orderStatsQuery->where('status', 'shipped')->count(),
                                            'delivered' => $orderStatsQuery->where('status', 'delivered')->count(),
                                            'cancelled' => $orderStatsQuery->where('status', 'cancelled')->count(),
                                            'bulk' => $orderStatsQuery->where('order_type', 'bulk')->count(),
                                        ];
                                    } catch (\Exception $e) {
                                        // If there's an error (like missing table), create empty stats
                                        $orderStats = (object) [
                                            'total' => 0,
                                            'pending' => 0,
                                            'processing' => 0,
                                            'shipped' => 0,
                                            'delivered' => 0,
                                            'cancelled' => 0,
                                            'bulk' => 0,
                                        ];
                                    }
                                @endphp

                                <li class="nav-header mt-3">SUPPLIER MANAGEMENT</li>

                                <!-- Dashboard -->
                                <li class="nav-item">
                                    <a href="{{ route('supplier.dashboard') }}" class="nav-link {{ Request::is('supplier/dashboard*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>

                                <!-- Inventory Management -->
                               <li class="nav-item">
    <a href="{{ route('supplier.inventory.index') }}" class="nav-link {{ Request::routeIs('supplier.inventory.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-warehouse"></i>
        <p>Inventory</p>
    </a>
</li>     <!-- Order Management -->
                                <li class="nav-item">
    <a href="{{ route('supplier.orders.index') }}" class="nav-link {{ Request::routeIs('supplier.orders.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clipboard-list"></i>
        <p>Orders</p>
        <i class="right fas fa-angle-left"></i>
        <span class="badge badge-warning right">{{ $orderStats->pending ?? 0 }}</span>
    </a>
</li>
                                <!-- Supplier Marketplace -->
                                <li class="nav-item">
                                    <a href="{{ route('supplier.marketplace.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-box"></i>
                                        <p>Marketplace</p>
                                    </a>
                                </li>

                                <li class="nav-header mt-3">QUICK LINKS</li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-question-circle text-primary"></i>
                                        <p>Supplier Help</p>
                                    </a>
                                </li>
                            @endif

                            <!-- Farmer Navigation -->
                            @if(auth()->user()->isFarmer())
                                <li class="nav-header mt-3">FARM MANAGEMENT</li>

                                <!-- Dashboard -->
                                <li class="nav-item">
                                    <a href="{{ route('farmer.dashboard') }}" class="nav-link {{ Request::is('farmer/dashboard*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>

                                <!-- Marketplace -->
                                <li class="nav-item">
                                <a href="{{ route('farmer.marketplace.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-store"></i>
                                    <p>Marketplace</p>
                                </a>
                            </li>

                                <!-- Orders -->
                             <li class="nav-item">
                                <a href="{{ route('farmer.orders.index') }}" class="nav-link {{ Request::routeIs('farmer.orders.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>My Orders</p>
                                    @php
                                        // Get actual pending orders count
                                        $pendingCount = 0;
                                        if (auth()->check() && auth()->user()->isFarmer()) {
                                            $pendingCount = \App\Models\Order::where('user_id', auth()->id())
                                                ->where('status', 'pending')
                                                ->count();
                                        }
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge badge-warning right">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>

                                <li class="nav-header mt-3">QUICK ACTIONS</li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-exclamation-triangle text-danger"></i>
                                        <p>Health Alert</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-question-circle text-primary"></i>
                                        <p>Farmer Support</p>
                                    </a>
                                </li>
                            @endif

                            <!-- Veterinary Navigation -->
                            @if(auth()->user()->isVeterinary())
    <li class="nav-header mt-3">VETERINARY MANAGEMENT</li>

    <!-- Dashboard -->
    <li class="nav-item">
        <a href="{{ route('veterinary.dashboard') }}" class="nav-link {{ Request::is('veterinary/dashboard*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>

    <!-- Consultations -->
    <li class="nav-item {{ Request::is('veterinary/consultations*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('veterinary/consultations*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-comment-medical"></i>
        <p>Consultations</p>
        <i class="right fas fa-angle-left"></i>
        <span class="badge badge-info right">{{ auth()->user()->pendingPoultryConsultations()->count() ?? 0 }}</span>
    </a>
    <ul class="nav nav-treeview">
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.consultations.create') }}" class="nav-link {{ Request::is('veterinary/consultations/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>New Poultry Consultation</p>
            </a>
        </li> -->
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.consultations.pending') }}" class="nav-link {{ Request::is('veterinary/consultations/pending') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-info"></i>
                <p>Pending Consultations</p>
                <span class="badge badge-info right">{{ auth()->user()->pendingPoultryConsultations()->count() ?? 0 }}</span>
            </a>
        </li> -->
        <li class="nav-item">
            <a href="{{ route('veterinary.consultations.index') }}" class="nav-link {{ Request::is('veterinary/consultations') && !Request::is('veterinary/consultations/pending') && !Request::is('veterinary/consultations/completed') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Consultations</p>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.consultations.completed') }}" class="nav-link {{ Request::is('veterinary/consultations/completed') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-success"></i>
                <p>Completed</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('veterinary.consultations.telemedicine') }}" class="nav-link {{ Request::is('veterinary/consultations/telemedicine') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-primary"></i>
                <p>Telemedicine</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('veterinary.consultations.follow-ups') }}" class="nav-link {{ Request::is('veterinary/consultations/follow-ups') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-warning"></i>
                <p>Follow-ups</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('veterinary.consultations.emergency') }}" class="nav-link {{ Request::is('veterinary/consultations/emergency') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Emergency Cases</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('veterinary.consultations.disease-outbreak') }}" class="nav-link {{ Request::is('veterinary/consultations/disease-outbreak') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Disease Outbreaks</p>
            </a>
        </li> -->
    </ul>
</li>

    <!-- Farm Visits -->
   <li class="nav-item {{ Request::is('veterinary/farm-visits*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('veterinary/farm-visits*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tractor"></i>
        <p>Farm Visits</p>
        <i class="right fas fa-angle-left"></i>
        <span class="badge badge-warning right">{{ auth()->user()->upcomingFarmVisits()->count() ?? 0 }}</span>
    </a>
    <ul class="nav nav-treeview">
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.farm-visits.create') }}" class="nav-link {{ Request::is('veterinary/farm-visits/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Schedule Visit</p>
            </a>
        </li> -->
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.farm-visits.upcoming') }}" class="nav-link {{ Request::is('veterinary/farm-visits/upcoming') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-warning"></i>
                <p>Upcoming Visits</p>
                <span class="badge badge-warning right">{{ auth()->user()->upcomingFarmVisits()->count() ?? 0 }}</span>
            </a>
        </li> -->
        <li class="nav-item">
            <a href="{{ route('veterinary.farm-visits.index') }}" class="nav-link {{ Request::is('veterinary/farm-visits') && !Request::is('veterinary/farm-visits/upcoming') && !Request::is('veterinary/farm-visits/history') && !Request::is('veterinary/farm-visits/emergency') && !Request::is('veterinary/farm-visits/reports') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Visits</p>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.farm-visits.history') }}" class="nav-link {{ Request::is('veterinary/farm-visits/history') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-success"></i>
                <p>Visit History</p>
            </a>
        </li> -->
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.farm-visits.reports') }}" class="nav-link {{ Request::is('veterinary/farm-visits/reports') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-primary"></i>
                <p>Visit Reports</p>
            </a>
        </li> -->
        <!-- <li class="nav-item">
            <a href="{{ route('veterinary.farm-visits.emergency') }}" class="nav-link {{ Request::is('veterinary/farm-visits/emergency') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Emergency Visits</p>
                @if(auth()->user()->emergencyFarmVisits()->count() > 0)
                    <span class="badge badge-danger right">{{ auth()->user()->emergencyFarmVisits()->count() }}</span>
                @endif
            </a>
        </li> -->
        <li class="nav-item">
            <a href="{{ route('veterinary.consultations.disease-outbreak') }}" class="nav-link {{ Request::is('veterinary/consultations/disease-outbreak') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Disease Outbreaks</p>
            </a>
        </li>
    </ul>
</li>

    <!-- Veterinary Settings -->
    <li class="nav-item {{ Request::is('veterinary/settings*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('veterinary/settings*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>Settings</p>
            <i class="right fas fa-angle-left"></i>
        </a>
        <ul class="nav nav-treeview">
            <!-- <li class="nav-item">
                <a href="{{ route('veterinary.settings.index') }}" class="nav-link {{ Request::is('veterinary/settings') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Profile Settings</p>
                </a>
            </li> -->
            <li class="nav-item">
                <a href="{{ route('veterinary.settings.index') }}#professional-info" class="nav-link">
                    <i class="far fa-circle nav-icon text-info"></i>
                    <p>Professional Info</p>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a href="{{ route('veterinary.settings.index') }}#availability" class="nav-link">
                    <i class="far fa-circle nav-icon text-warning"></i>
                    <p>Availability Schedule</p>
                </a>
            </li> -->
            <!-- <li class="nav-item">
                <a href="{{ route('veterinary.settings.index') }}#service-areas" class="nav-link">
                    <i class="far fa-circle nav-icon text-success"></i>
                    <p>Service Areas</p>
                </a>
            </li> -->
            <li class="nav-item">
                <a href="{{ route('veterinary.settings.index') }}#licenses" class="nav-link">
                    <i class="far fa-circle nav-icon text-danger"></i>
                    <p>License & Certifications</p>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a href="{{ route('veterinary.settings.index') }}#notifications" class="nav-link">
                    <i class="far fa-circle nav-icon text-primary"></i>
                    <p>Notification Settings</p>
                </a>
            </li> -->
        </ul>
    </li>

    <li class="nav-header mt-3">EMERGENCY & QUICK ACCESS</li>

    <li class="nav-item">
        <a href="{{ route('veterinary.emergency-hotline') }}" class="nav-link {{ Request::is('veterinary/emergency-hotline') ? 'active' : '' }}">
            <i class="nav-icon fas fa-ambulance text-danger"></i>
            <p>Emergency Hotline</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('veterinary.help') }}" class="nav-link {{ Request::is('veterinary/help') ? 'active' : '' }}">
            <i class="nav-icon fas fa-question-circle text-primary"></i>
            <p>Veterinary Help</p>
        </a>
    </li>
@endif

                            <!-- Agent Navigation -->
                            @if(auth()->user()->isAgent())
                                <li class="nav-header mt-3">AGENT MANAGEMENT</li>

                                <!-- Dashboard -->
                                <li class="nav-item">
                                    <a href="{{ route('agent.dashboard') }}" class="nav-link {{ Request::is('agent/dashboard*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>

                                <!-- Agents Marketplace -->
                            <li class="nav-item">
                                <a href="{{ route('agent.marketplace.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-store"></i>
                                    <p>Marketplace</p>
                                </a>
                            </li>

                                <!-- Agent Settings -->
                                <li class="nav-item">
                                <a href="{{ route('agent.settings.index') }}" class="nav-link {{ Request::is('agent/settings*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>Agent Settings</p>
                                </a>
                            </li>

                                <li class="nav-header mt-3">QUICK ACTIONS</li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-question-circle text-primary"></i>
                                        <p>Agent Support</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-trophy text-danger"></i>
                                        <p>Achievements</p>
                                    </a>
                                </li>
                            @endif

                            <!-- Admin Navigation -->
@if(auth()->user()->isAdmin())
   <li class="nav-header mt-3">ADMINISTRATION</li>

    <!-- <li class="nav-item">
       <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
           <i class="nav-icon fas fa-bell"></i>
           <p>Notifications</p>
           @if(auth()->user()->unreadNotifications()->count() > 0)
               <span class="badge badge-danger float-right">
                   {{ auth()->user()->unreadNotifications()->count() }}
               </span>
           @endif
       </a>
   </li> -->
<li class="nav-item">
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Users</p>
    </a>
</li>
    <li class="nav-item {{ request()->is('admin/analytics*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->is('admin/analytics*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>
                Analytics
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
    <a href="{{ route('admin.analytics.suppliers') }}"
        class="nav-link {{ request()->routeIs('admin.analytics.suppliers') ? 'active' : '' }}">
        <i class="nav-icon fas fa-industry text-primary mr-2"></i>
        <p>Supplier Overview</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.analytics.farmers') }}"
        class="nav-link {{ request()->routeIs('admin.analytics.farmers') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tractor text-success mr-2"></i>
        <p>Farmer Overview</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.analytics.agents') }}"
        class="nav-link {{ request()->routeIs('admin.analytics.agents') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-tie text-info mr-2"></i>
        <p>Agent Overview</p>
    </a>
</li>
        </ul>
    </li>

    <!-- Subscription Management -->
<li class="nav-item has-treeview {{ Request::routeIs('admin.subscriptions.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::routeIs('admin.subscriptions.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-crown"></i>
        <p>
            Subscriptions
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.subscriptions.index') }}" class="nav-link {{ Request::routeIs('admin.subscriptions.index') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>All Plans</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.subscriptions.create') }}" class="nav-link {{ Request::routeIs('admin.subscriptions.create') ? 'active' : '' }}">
                <i class="fas fa-plus nav-icon"></i>
                <p>Add New Plan</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.subscriptions.statistics') }}" class="nav-link {{ Request::routeIs('admin.subscriptions.statistics') ? 'active' : '' }}">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>Statistics</p>
            </a>
        </li>
    </ul>
</li>

    <!-- Logistics Menu -->
    <li class="nav-item {{ request()->is('admin/logistics*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->is('admin/logistics*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-shipping-fast"></i>
            <p>
                Logistics
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
    <a href="{{ route('admin.logistics.deliveries') }}"
        class="nav-link {{ request()->routeIs('admin.logistics.deliveries') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shipping-fast text-warning mr-2"></i>
        <p>Deliveries Overview</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.logistics.delays') }}"
        class="nav-link {{ request()->routeIs('admin.logistics.delays') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clock text-danger mr-2"></i>
        <p>Delayed Orders</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.logistics.completed') }}"
        class="nav-link {{ request()->routeIs('admin.logistics.completed') ? 'active' : '' }}">
        <i class="nav-icon fas fa-check-circle text-success mr-2"></i>
        <p>Completed Orders</p>
    </a>
</li>
        </ul>
    </li>

    <!-- NEW: Mortality Track Menu -->
    <li class="nav-item {{ request()->is('admin/mortality*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->is('admin/mortality*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-heartbeat"></i>
            <p>
                Mortality Track
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
           <li class="nav-item">
    <a href="{{ route('admin.mortality.transport') }}"
        class="nav-link {{ request()->routeIs('admin.mortality.transport') ? 'active' : '' }}">
        <i class="nav-icon fas fa-truck-loading text-danger mr-2"></i>
        <p>Transport Mortality</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.mortality.expectation') }}"
        class="nav-link {{ request()->routeIs('admin.mortality.expectation') ? 'active' : '' }}">
        <i class="nav-icon fas fa-flag text-warning mr-2"></i>
        <p>Expectation Flagged</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.mortality.reports') }}"
        class="nav-link {{ request()->routeIs('admin.mortality.reports') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clipboard-list text-info mr-2"></i>
        <p>Reports & Complaints</p>
    </a>
</li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>Settings</p>
        </a>
    </li>
@endif

                            <!-- Common Navigation -->
                            <li class="nav-header mt-3">ACCOUNT</li>
                            <li class="nav-item">
                                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>Profile</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-bell"></i>
                                    <p>Notifications</p>
                                    @php
                                        $unreadCount = auth()->check() ? auth()->user()->notifications()->where('read', false)->count() : 0;
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="badge badge-warning right">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @else
                            <!-- No Role Selected -->
                            <li class="nav-item">
                                <a href="{{ route('select.role') }}" class="nav-link bg-warning text-dark">
                                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                                    <p>Select Role</p>
                                </a>
                            </li>
                        @endif
                    @endauth

                    <!-- Logout -->
                    @auth
                        <li class="nav-item mt-4">
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="nav-link bg-transparent border-0 text-start text-white w-100">
                                    <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                                    <p>Logout</p>
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            @yield('page-title', 'Dashboard')
                            @hasSection('page-subtitle')
                                <small class="text-muted">@yield('page-subtitle')</small>
                            @endif
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @hasSection('breadcrumb')
                                @yield('breadcrumb')
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">@yield('title', 'Home')</li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" id="flash-alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" id="flash-alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show mb-4" id="flash-alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(!auth()->user()->role && request()->route()->getName() !== 'select.role')
                    <div class="alert alert-warning alert-dismissible fade show mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please <a href="{{ route('select.role') }}" class="alert-link">select your role</a> to access all features.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center">
        <strong>&copy; {{ date('Y') }} My-Kuku-Soko</strong> — Empowering Poultry Farmers & Markets
        <div class="mt-2">
            <small class="text-muted">
                <i class="fas fa-envelope me-1"></i> support@my-kuku-soko.com |
                <i class="fas fa-phone me-1 ms-3"></i> +254 716 839 446
            </small>
        </div>
    </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        });
    }, 5000);

    // Toastr notifications
    // @if(session('toast-success'))
    //     toastr.success('{{ session('toast-success') }}');
    // @endif

    // @if(session('toast-error'))
    //     toastr.error('{{ session('toast-error') }}');
    // @endif

    // Initialize AdminLTE components
    $(document).ready(function () {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Auto-hide alerts when clicked
        $('.alert-dismissible').on('click', function() {
            $(this).alert('close');
        });

        // Active sidebar highlighting
        const currentPath = window.location.pathname;
        $('.nav-sidebar .nav-link').each(function() {
            const linkPath = $(this).attr('href');
            if (linkPath && currentPath.startsWith(linkPath) && linkPath !== '/') {
                $(this).addClass('active');
            }
        });

       // Notification functionality
initializeNotifications();

// Push menu toggle
$('[data-widget="pushmenu"]').on('click', function() {
    $('body').toggleClass('sidebar-collapse');
});

// Notification functions
function initializeNotifications() {
    // Update notification count badge
    updateNotificationCount();

    // Mark notification as read when clicked
    $(document).on('click', '.notification-item', function(e) {
        const notificationId = $(this).data('id');
        const link = $(this).attr('href');

        if (!notificationId) {
            if (link && link !== '#') {
                window.location.href = link;
            }
            return;
        }

        // Mark as read via AJAX
        $.ajax({
            url: '/notifications/' + notificationId + '/read',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH'  // Add this if your route uses PATCH
            },
            success: function(response) {
                // Update the notification item visually
                const item = $('.notification-item[data-id="' + notificationId + '"]');
                item.removeClass('notification-unread').addClass('notification-read');
                item.find('.notification-title').css('font-weight', 'normal');
                item.find('.text-success').remove();

                // Update count
                updateNotificationCount();

                // Navigate to link after marking as read
                if (link && link !== '#') {
                    setTimeout(() => {
                        window.location.href = link;
                    }, 300);
                }
            },
            error: function(xhr) {
                console.error('Failed to mark notification as read:', xhr);
                // Still navigate to link even if mark as read fails
                if (link && link !== '#') {
                    window.location.href = link;
                }
            }
        });
    });

    // Mark all as read form submission
    $('#markAllReadForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("notifications.markAllRead") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Update all notifications visually
                $('.notification-item').each(function() {
                    $(this).removeClass('notification-unread').addClass('notification-read');
                    $(this).find('.notification-title').css('font-weight', 'normal');
                    $(this).find('.text-success').remove();
                });

                // Update count
                updateNotificationCount();

                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success('All notifications marked as read');
                } else {
                    alert('All notifications marked as read');
                }
            },
            error: function(xhr) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to mark all as read');
                } else {
                    alert('Failed to mark all as read');
                }
            }
        });
    });

    // Refresh notifications every 30 seconds
    setInterval(updateNotificationCount, 30000);
}

function updateNotificationCount() {
    $.ajax({
        url: '{{ route("notifications.unreadCount") }}',
        method: 'GET',
        success: function(data) {
            const badge = $('.notification-count');
            const header = $('#notificationDropdown .dropdown-header');
            const userDropdownBadge = $('.dropdown-item[href*="notifications"] .badge');

            if (data.count > 0) {
                badge.text(data.count).show();
                header.text(data.count + ' New Notification' + (data.count !== 1 ? 's' : ''));
                if (userDropdownBadge.length) {
                    userDropdownBadge.text(data.count).show();
                }
            } else {
                badge.hide();
                header.text('No New Notifications');
                if (userDropdownBadge.length) {
                    userDropdownBadge.hide();
                }
            }
        },
        error: function(xhr) {
            console.error('Failed to fetch notification count:', xhr);
        }
    });
}

// Fetch latest notifications for dropdown
function fetchLatestNotifications() {
    $.ajax({
        url: '{{ route("notifications.latest") }}',
        method: 'GET',
        success: function(data) {
            const notificationList = $('#notificationList');
            notificationList.empty();

            if (!data || data.length === 0) {
                notificationList.html(`
                    <div class="dropdown-item text-center text-muted py-4">
                        <i class="far fa-bell-slash fa-2x mb-2"></i>
                        <p class="mb-0">No notifications</p>
                    </div>
                `);
                return;
            }

            // Clear existing content
            notificationList.html('');

            // Add each notification
            data.forEach(function(notification) {
                const iconClass = notification.icon || 'fas fa-bell';
                const colorClass = 'notification-icon-' + (notification.color || 'info');
                const readClass = notification.read ? 'notification-read' : 'notification-unread';
                const fontWeight = notification.read ? 'normal' : '600';

                // Ensure message is safe for HTML
                const safeMessage = $('<div>').text(notification.message).html();

                const notificationHtml = `
                    <a href="${notification.link || '#'}"
                       class="dropdown-item notification-item d-flex align-items-start p-3 ${readClass}"
                       data-id="${notification.id}">
                        <div class="notification-icon mr-3 ${colorClass}">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="notification-title mb-1" style="font-weight: ${fontWeight}">
                                    ${notification.title}
                                </h6>
                                <small class="notification-time">${notification.time_ago}</small>
                            </div>
                            <p class="text-sm mb-0">${safeMessage}</p>
                            ${!notification.read ? '<small class="text-success"><i class="fas fa-circle fa-xs"></i> Unread</small>' : ''}
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                `;

                notificationList.append(notificationHtml);
            });

            // Update mark all read form visibility
            const unreadCount = data.filter(n => !n.read).length;
            const markAllReadForm = $('#markAllReadForm');
            if (markAllReadForm.length) {
                if (unreadCount > 0) {
                    markAllReadForm.show();
                } else {
                    markAllReadForm.hide();
                }
            }
        },
        error: function(xhr) {
            console.error('Failed to fetch notifications:', xhr);
            const notificationList = $('#notificationList');
            notificationList.html(`
                <div class="dropdown-item text-center text-muted py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                    <p class="mb-0">Failed to load notifications</p>
                    <small>Please try again later</small>
                </div>
            `);
        }
    });
}

// Fetch notifications when dropdown is shown
$('#notificationDropdown').on('show.bs.dropdown', function() {
    fetchLatestNotifications();
});

// Update notification count when page loads
$(document).ready(function() {
    updateNotificationCount();
});

// Real-time updates with polling
setInterval(function() {
    // Only update if notifications dropdown might be visible
    if ($('#notificationDropdown').hasClass('show')) {
        fetchLatestNotifications();
    }
    updateNotificationCount();
}, 60000); // Update every minute
    });
</script>

@stack('scripts')
</body>
</html>
