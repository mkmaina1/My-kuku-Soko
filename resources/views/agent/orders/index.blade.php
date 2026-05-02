@extends('layouts.app')

@section('title', 'Agent Orders')

@section('styles')
<style>
    .order-status-badge {
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .order-card {
        border-left: 4px solid #007bff;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    /* Custom pagination styling */
    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }
    .page-link {
        color: #007bff;
        border: 1px solid #dee2e6;
        margin: 0 2px;
        border-radius: 4px;
        font-weight: 500;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>
@endsection

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-clipboard-list mr-2"></i>Agent Orders
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <!-- Quick Stats - MOVED TO TOP -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="info-box bg-gradient-info shadow-sm">
                    <span class="info-box-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Orders</span>
                        <span class="info-box-number">{{ $statusCounts['all'] }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            All time orders
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="info-box bg-gradient-warning shadow-sm">
                    <span class="info-box-icon">
                        <i class="fas fa-clock"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending</span>
                        <span class="info-box-number">{{ $statusCounts['pending'] }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $statusCounts['all'] > 0 ? ($statusCounts['pending'] / $statusCounts['all']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            Awaiting processing
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="info-box bg-gradient-success shadow-sm">
                    <span class="info-box-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Delivered</span>
                        <span class="info-box-number">{{ $statusCounts['delivered'] }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $statusCounts['all'] > 0 ? ($statusCounts['delivered'] / $statusCounts['all']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            Successfully delivered
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="info-box bg-gradient-danger shadow-sm">
                    <span class="info-box-icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cancelled</span>
                        <span class="info-box-number">{{ $statusCounts['cancelled'] }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $statusCounts['all'] > 0 ? ($statusCounts['cancelled'] / $statusCounts['all']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            Cancelled orders
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="card card-outline card-primary mb-4">
            <div class="card-body p-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('agent.orders.index') }}"
                       class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        All <span class="badge badge-light">{{ $statusCounts['all'] }}</span>
                    </a>
                    <a href="{{ route('agent.orders.index', ['status' => 'pending']) }}"
                       class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Pending <span class="badge badge-light">{{ $statusCounts['pending'] }}</span>
                    </a>
                    <a href="{{ route('agent.orders.index', ['status' => 'processing']) }}"
                       class="btn btn-sm {{ $status == 'processing' ? 'btn-info' : 'btn-outline-info' }}">
                        Processing <span class="badge badge-light">{{ $statusCounts['processing'] }}</span>
                    </a>
                    <a href="{{ route('agent.orders.index', ['status' => 'shipped']) }}"
                       class="btn btn-sm {{ $status == 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Shipped <span class="badge badge-light">{{ $statusCounts['shipped'] }}</span>
                    </a>
                    <a href="{{ route('agent.orders.index', ['status' => 'delivered']) }}"
                       class="btn btn-sm {{ $status == 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
                        Delivered <span class="badge badge-light">{{ $statusCounts['delivered'] }}</span>
                    </a>
                    <a href="{{ route('agent.orders.index', ['status' => 'cancelled']) }}"
                       class="btn btn-sm {{ $status == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Cancelled <span class="badge badge-light">{{ $statusCounts['cancelled'] }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Orders List
                    <span class="badge badge-primary ml-2">{{ $orders->total() }} orders</span>
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search orders...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr class="bg-light">
                                <th style="width: 10%">Order #</th>
                                <th style="width: 20%">Farmer</th>
                                <th style="width: 15%">Date</th>
                                <th style="width: 15%" class="text-right">Amount</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 15%">Payment</th>
                                <th style="width: 10%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->order_number }}</strong>
                                </td>
                                <td>
                                    @if($order->user)
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            @if($order->user->avatar)
                                                <img src="{{ asset('storage/' . $order->user->avatar) }}"
                                                     alt="{{ $order->user->name }}"
                                                     class="rounded-circle" width="30" height="30">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                     style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                    {{ substr($order->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $order->user->name }}</div>
                                            <small class="text-muted">{{ $order->user->phone ?? 'No phone' }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">User not found</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $order->created_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-right">
                                    <strong class="text-success">KES {{ number_format($order->total, 2) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->items->count() }} items</small>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }} order-status-badge">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->estimated_delivery)
                                    <br>
                                    <small class="text-muted">
                                        Est: {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d') }}
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('farmer.orders.show', $order) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('farmer.orders.track', $order) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Track Order">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Orders Found</h5>
                                    <p class="text-muted">You haven't created any orders yet.</p>
                                    @if($status !== 'all')
                                    <a href="{{ route('agent.orders.index') }}" class="btn btn-primary">
                                        <i class="fas fa-list mr-1"></i> View All Orders
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bootstrap Pagination at Bottom -->
            @if($orders->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted mb-2 mb-md-0">
                        Showing <strong>{{ $orders->firstItem() ?: 0 }}</strong> to <strong>{{ $orders->lastItem() ?: 0 }}</strong>
                        of <strong>{{ $orders->total() }}</strong> orders
                    </div>

                    <div class="pagination-wrapper">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center justify-content-md-end mb-0">
                                {{-- Previous Page Link --}}
                                @if($orders->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                        <span class="page-link" aria-hidden="true">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($orders->links()->elements[0] as $page => $url)
                                    @if($page == $orders->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if($orders->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                        <span class="page-link" aria-hidden="true">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>

                {{-- Alternative: Using Laravel's built-in pagination with Bootstrap classes --}}
                {{--
                <div class="d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
                --}}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        $('[title]').tooltip();

        // Search functionality
        const searchInput = document.querySelector('input[name="table_search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
