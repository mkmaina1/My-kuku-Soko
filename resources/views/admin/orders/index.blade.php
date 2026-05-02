@extends('layouts.app')

@section('title', 'Manage Orders')

@section('styles')
<style>
    /* Enhanced Page Header */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
    }

    .page-header h1 {
        color: white;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .page-header .breadcrumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        backdrop-filter: blur(10px);
    }

    .page-header .breadcrumb-item {
        color: rgba(255, 255, 255, 0.9);
    }

    .page-header .breadcrumb-item.active {
        color: white;
        font-weight: 600;
    }

    .page-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .page-header .breadcrumb-item a:hover {
        opacity: 0.8;
    }

    /* Enhanced Statistics Cards */
    .stats-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .stats-card.bg-primary::before { background: rgba(255, 255, 255, 0.5); }
    .stats-card.bg-success::before { background: rgba(255, 255, 255, 0.5); }
    .stats-card.bg-warning::before { background: rgba(0, 0, 0, 0.2); }
    .stats-card.bg-info::before { background: rgba(255, 255, 255, 0.5); }

    .stats-card i {
        opacity: 0.8;
        transition: all 0.3s ease;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .stats-card:hover i {
        transform: scale(1.1) rotate(5deg);
        opacity: 1;
    }

    /* Enhanced Filter Card */
    .filter-card {
        background: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .filter-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .filter-card .card-body {
        padding: 1.5rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
    }

    .filter-card .input-group {
        border-radius: 8px;
        overflow: hidden;
    }

    .filter-card .input-group .form-control {
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

    .filter-card .input-group .btn {
        border-radius: 0 8px 8px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .filter-card .input-group .btn:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateX(2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .filter-card .btn-outline-secondary {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 0.625rem 1rem;
    }

    .filter-card .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
    }

    /* Enhanced Orders Table */
    .table-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
        border: 1px solid #e9ecef;
    }

    .table-container .card-body {
        padding: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table thead th {
        border: none;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f8f9fa;
    }

    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border: none;
        border-bottom: 1px solid #f8f9fa;
    }

    /* Enhanced Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .status-badge i {
        font-size: 0.6rem;
        transition: all 0.3s ease;
    }

    .badge-pending {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #212529;
        border-color: rgba(255, 193, 7, 0.3);
    }
    .badge-processing {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-color: rgba(23, 162, 184, 0.3);
    }
    .badge-shipped {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border-color: rgba(0, 123, 255, 0.3);
    }
    .badge-delivered {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
        border-color: rgba(40, 167, 69, 0.3);
    }
    .badge-cancelled {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border-color: rgba(220, 53, 69, 0.3);
    }

    /* Enhanced Payment Badges */
    .payment-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .payment-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .payment-cash {
        background: linear-gradient(135deg, #e7f5ff 0%, #d0ebff 100%);
        color: #0d6efd;
        border-color: #a5d8ff;
    }
    .payment-mpesa {
        background: linear-gradient(135deg, #f0f9ff 0%, #e1f5fe 100%);
        color: #0dcaf0;
        border-color: #99e9f2;
    }
    .payment-card {
        background: linear-gradient(135deg, #fff0f6 0%, #ffe6f0 100%);
        color: #d63384;
        border-color: #fcc2d7;
    }
    .payment-bank {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #6c757d;
        border-color: #dee2e6;
    }

    /* Enhanced Customer Avatar */
    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .customer-avatar:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Enhanced Order Items List */
    .order-items-list {
        max-height: 150px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .order-items-list::-webkit-scrollbar {
        width: 6px;
    }

    .order-items-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .order-items-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .order-items-list::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    /* Enhanced Action Dropdown */
    .action-dropdown {
        min-width: 220px;
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        padding: 0.5rem 0;
        border: 1px solid #e9ecef;
    }

    .action-dropdown .dropdown-item {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border-radius: 8px;
        margin: 0.25rem 0.75rem;
        width: calc(100% - 1.5rem);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .action-dropdown .dropdown-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .action-dropdown .dropdown-header {
        padding: 0.75rem 1.5rem;
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 0.5rem;
    }

    .action-dropdown .dropdown-divider {
        margin: 0.5rem 1.5rem;
        border-color: #e9ecef;
    }

    /* Enhanced Action Button */
    .btn-outline-primary {
        border: 2px solid #667eea;
        color: #667eea;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    /* Enhanced No Orders State */
    .no-orders-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        margin: 2rem;
    }

    .no-orders-state i {
        font-size: 5rem;
        color: #adb5bd;
        margin-bottom: 1.5rem;
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .no-orders-state:hover i {
        transform: scale(1.1);
        opacity: 0.7;
    }

    .no-orders-state h4 {
        color: #495057;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .no-orders-state p {
        color: #6c757d;
        max-width: 400px;
        margin: 0 auto 2rem;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .no-orders-state .btn {
        border-radius: 10px;
        padding: 0.875rem 2.5rem;
        font-weight: 600;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .no-orders-state .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* Bootstrap Pagination Styling */
    .pagination-container {
        background: white;
        border-top: 1px solid #e9ecef;
        padding: 1.5rem;
        border-radius: 0 0 12px 12px;
    }

    .pagination {
        margin: 0;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .page-link {
        border: 2px solid #e0e0e0;
        border-radius: 8px !important;
        color: #495057;
        font-weight: 600;
        padding: 0.625rem 1rem;
        min-width: 45px;
        text-align: center;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .page-link span {
        position: relative;
        z-index: 2;
    }

    .page-link:hover {
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .page-link:hover::before {
        opacity: 1;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .page-item.active .page-link::before {
        opacity: 1;
    }

    .page-item.disabled .page-link {
        background: #f8f9fa;
        color: #adb5bd;
        border-color: #e9ecef;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .page-item.disabled .page-link:hover::before {
        opacity: 0;
    }

    .page-item.disabled .page-link:hover {
        color: #adb5bd;
        border-color: #e9ecef;
        transform: none;
        box-shadow: none;
    }

    .pagination-info {
        font-size: 0.95rem;
        color: #495057;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-info strong {
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 1rem;
            text-align: center;
        }

        .page-header .breadcrumb {
            justify-content: center;
        }

        .stats-card {
            margin-bottom: 1rem;
        }

        .stats-card:hover {
            transform: translateY(-4px);
        }

        .filter-card .row > div {
            margin-bottom: 1rem;
        }

        .table-responsive {
            font-size: 0.85rem;
        }

        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination-info {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .status-badge,
        .payment-badge {
            font-size: 0.7rem;
            padding: 0.35rem 0.75rem;
        }

        .page-link {
            padding: 0.5rem 0.75rem;
            min-width: 40px;
            font-size: 0.9rem;
        }

        .action-dropdown {
            min-width: 180px;
        }
    }

    /* Loading Animation */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .loading-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.25rem;
        border-color: #667eea;
        border-right-color: transparent;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="h2 font-weight-bold mb-2">
                    <i class="fas fa-shopping-cart mr-2"></i>Manage Orders
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-primary text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-0 opacity-75">Total Orders</h6>
                        <h2 class="font-weight-bold mb-0">{{ $totalOrders }}</h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-success text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-0 opacity-75">Total Revenue</h6>
                        <h2 class="font-weight-bold mb-0">KES {{ number_format($totalRevenue) }}</h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-3x opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-warning text-dark p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-0 opacity-75">Pending Orders</h6>
                        <h2 class="font-weight-bold mb-0">{{ \App\Models\Order::where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-info text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-0 opacity-75">Today's Orders</h6>
                        <h2 class="font-weight-bold mb-0">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</h2>
                    </div>
                    <i class="fas fa-calendar-day fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label fw-semibold">Order Status</label>
                        <select name="status" id="status" class="form-select" onchange="submitFormWithLoading()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="payment_method" class="form-label fw-semibold">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-select" onchange="submitFormWithLoading()">
                            <option value="all" {{ request('payment_method') == 'all' ? 'selected' : '' }}>All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="mpesa" {{ request('payment_method') == 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label fw-semibold">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                               value="{{ request('date_from') }}" onchange="submitFormWithLoading()">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label fw-semibold">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                               value="{{ request('date_to') }}" onchange="submitFormWithLoading()">
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100" onclick="showLoading()">
                            <i class="fas fa-redo mr-1"></i>Reset Filters
                        </a>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by order number, tracking number, customer name or email..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit" onclick="showLoading()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card table-container">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $order->order_number }}</div>
                                    @if($order->tracking_number)
                                        <small class="text-muted">Tracking: {{ $order->tracking_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($order->user->avatar)
                                            <img src="{{ asset('storage/' . $order->user->avatar) }}"
                                                 alt="{{ $order->user->name }}"
                                                 class="customer-avatar mr-2"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&color=7F9CF5&background=EBF4FF'">
                                        @else
                                            <div class="customer-avatar mr-2 bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $order->user->name }}</div>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="order-items-list">
                                        @foreach($order->items->take(2) as $item)
                                            <div class="mb-1">
                                                <small>{{ $item->product->name }} (x{{ $item->quantity }})</small>
                                            </div>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <small class="text-primary">
                                                +{{ $order->items->count() - 2 }} more items
                                            </small>
                                        @endif
                                    </td>
                                </td>
                                <td>
                                    <div class="font-weight-bold">KES {{ number_format($order->total) }}</div>
                                    <small class="text-muted">
                                        {{ $order->items->count() }} item(s)
                                    </small>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'badge-' . $order->status;
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas fa-circle mr-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->status == 'delivered' && $order->delivered_at)
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                {{ $order->delivered_at->format('M d') }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $paymentClass = 'payment-' . $order->payment_method;
                                    @endphp
                                    <span class="payment-badge {{ $paymentClass }}">
                                        {{ strtoupper($order->payment_method) }}
                                    </span>
                                    @if($order->payment_status == 'paid')
                                        <div class="mt-1">
                                            <small class="text-success fw-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>Paid
                                            </small>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <small class="text-warning fw-semibold">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                type="button"
                                                id="orderActions{{ $order->id }}"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu action-dropdown" aria-labelledby="orderActions{{ $order->id }}">
                                            <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                                                <i class="fas fa-eye mr-2"></i>View Details
                                            </a>

                                            @if($order->status != 'delivered' && $order->status != 'cancelled')
                                                <div class="dropdown-divider"></div>
                                                <h6 class="dropdown-header">Update Status</h6>
                                                @foreach(['processing', 'shipped', 'delivered'] as $status)
                                                    @if($status != $order->status)
                                                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="{{ $status }}">
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Mark order as {{ $status }}?')">
                                                                <i class="fas fa-arrow-right mr-2"></i>Mark as {{ ucfirst($status) }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if($order->status != 'cancelled')
                                                <div class="dropdown-divider"></div>
                                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Cancel this order? This will restore product quantities.')">
                                                        <i class="fas fa-times-circle mr-2"></i>Cancel Order
                                                    </button>
                                                </form>
                                            @endif

                                            @if($order->status == 'cancelled')
                                                <div class="dropdown-divider"></div>
                                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline" onsubmit="return confirm('Delete this cancelled order permanently?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash mr-2"></i>Delete Order
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bootstrap Pagination -->
            <div class="pagination-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="pagination-info mb-3 mb-md-0">
                    Showing <strong>{{ $orders->firstItem() }}</strong> to <strong>{{ $orders->lastItem() }}</strong>
                    of <strong>{{ $orders->total() }}</strong> orders
                </div>

                @if($orders->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($orders->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev" onclick="showLoading()">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if($page == $orders->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}" onclick="showLoading()">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($orders->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next" onclick="showLoading()">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        @else
            <div class="no-orders-state">
                <i class="fas fa-shopping-cart"></i>
                <h4>No orders found</h4>
                <p>There are no orders matching your filters. Try adjusting your search criteria or resetting the filters.</p>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary" onclick="showLoading()">
                    <i class="fas fa-redo mr-1"></i>Reset Filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Loading overlay functions
    function showLoading() {
        document.getElementById('loadingOverlay').classList.add('active');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }

    // Auto-hide loading when page is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        hideLoading();

        // Auto-refresh page every 60 seconds if there are pending orders
        @if(\App\Models\Order::where('status', 'pending')->exists())
            setTimeout(function() {
                showLoading();
                location.reload();
            }, 60000); // 60 seconds
        @endif
    });

    // Form submission with loading
    function submitFormWithLoading() {
        showLoading();
        setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 300);
    }

    // Quick status update with confirmation
    function updateOrderStatus(orderId, status) {
        if (confirm('Mark order as ' + status + '?')) {
            showLoading();

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/orders/' + orderId + '/status';

            // Add _method for PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrf);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            form.appendChild(statusInput);

            if (status === 'shipped') {
                const tracking = prompt('Enter tracking number:');
                if (tracking !== null) {
                    const trackingInput = document.createElement('input');
                    trackingInput.type = 'hidden';
                    trackingInput.name = 'tracking_number';
                    trackingInput.value = tracking;
                    form.appendChild(trackingInput);
                } else {
                    hideLoading();
                    return; // User cancelled
                }
            }

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Add smooth animations for table rows
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';

            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });

        // Add click animation to pagination links
        const paginationLinks = document.querySelectorAll('.page-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.closest('.disabled')) {
                    const parent = this.parentElement;
                    parent.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        parent.style.transform = '';
                    }, 300);
                }
            });
        });

        // Add filter animation
        const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.style.boxShadow = '0 0 0 0.2rem rgba(102, 126, 234, 0.4)';
                setTimeout(() => {
                    this.style.boxShadow = '';
                }, 500);
            });
        });
    });
</script>
@endpush
