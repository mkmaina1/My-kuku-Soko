@extends('layouts.app')

@section('title', 'Verification Requests')

@section('styles')
<style>
    /* Custom Pagination Styling */
    .pagination {
        margin-bottom: 0;
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 5px;
    }

    .page-item {
        margin: 0 2px;
    }

    .page-item .page-link {
        border-radius: 8px;
        padding: 0.5rem 0.9rem;
        color: #2e7d32;
        border: 1px solid #dee2e6;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-item .page-link:hover {
        background-color: #2e7d32;
        border-color: #2e7d32;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(46, 125, 50, 0.2);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #2e7d32, #4caf50);
        border-color: #2e7d32;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(46, 125, 50, 0.3);
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .page-item:first-child .page-link {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .page-item:last-child .page-link {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    /* Pagination info styling */
    .pagination-info {
        background: white;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        color: #495057;
        font-size: 0.9rem;
    }

    .pagination-info i {
        color: #2e7d32;
        margin-right: 0.3rem;
    }

    .pagination-info strong {
        color: #2e7d32;
        font-weight: 600;
    }

    /* Card footer styling */
    .card-footer {
        background-color: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }

    /* Responsive pagination */
    @media (max-width: 768px) {
        .pagination {
            justify-content: center;
            margin-top: 1rem;
        }

        .page-item .page-link {
            padding: 0.4rem 0.7rem;
            font-size: 0.9rem;
        }

        .pagination-info {
            text-align: center;
            margin-bottom: 1rem;
        }

        .card-footer .row > div {
            text-align: center !important;
        }
    }

    /* Table hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(46, 125, 50, 0.03);
    }

    /* ========== BRIGHT STATUS BADGES ========== */

    /* Pending Badge - Bright Orange */
    .badge-pending {
        background: #ff9800 !important;
        color: white !important;
        padding: 0.6rem 1.2rem !important;
        border-radius: 50px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        box-shadow: 0 4px 8px rgba(255, 152, 0, 0.4) !important;
        border: 2px solid #ff9800 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .badge-pending i {
        color: white !important;
        font-size: 1rem !important;
    }

    .badge-pending:hover {
        background: #f57c00 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 152, 0, 0.5) !important;
    }

    /* Approved Badge - Bright Green */
    .badge-approved {
        background: #00c853 !important;
        color: white !important;
        padding: 0.6rem 1.2rem !important;
        border-radius: 50px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        box-shadow: 0 4px 8px rgba(0, 200, 83, 0.4) !important;
        border: 2px solid #00c853 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .badge-approved i {
        color: white !important;
        font-size: 1rem !important;
    }

    .badge-approved:hover {
        background: #00a844 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 200, 83, 0.5) !important;
    }

    /* Rejected Badge - Bright Red */
    .badge-rejected {
        background: #d32f2f !important;
        color: white !important;
        padding: 0.6rem 1.2rem !important;
        border-radius: 50px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        box-shadow: 0 4px 8px rgba(211, 47, 47, 0.4) !important;
        border: 2px solid #d32f2f !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .badge-rejected i {
        color: white !important;
        font-size: 1rem !important;
    }

    .badge-rejected:hover {
        background: #b71c1c !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(211, 47, 47, 0.5) !important;
    }

    /* Status column container */
    .status-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-width: 120px;
    }

    /* Filter buttons */
    .filter-btn {
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .filter-btn.active {
        background: #2e7d32 !important;
        color: white !important;
        border-color: #2e7d32 !important;
    }
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-shield-alt text-primary mr-2"></i>
                    Verification Requests
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    <li class="breadcrumb-item active">
                        <i class="fas fa-shield-alt"></i> Verifications
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            All Verification Requests
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}"
                                   class="btn btn-sm btn-warning filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </a>
                                <a href="{{ route('admin.verification.index', ['status' => 'approved']) }}"
                                   class="btn btn-sm btn-success filter-btn {{ request('status') == 'approved' ? 'active' : '' }}">
                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                </a>
                                <a href="{{ route('admin.verification.index', ['status' => 'rejected']) }}"
                                   class="btn btn-sm btn-danger filter-btn {{ request('status') == 'rejected' ? 'active' : '' }}">
                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                </a>
                                <a href="{{ route('admin.verification.index', ['status' => 'all']) }}"
                                   class="btn btn-sm btn-secondary filter-btn {{ request('status') == 'all' || !request('status') ? 'active' : '' }}">
                                    <i class="fas fa-list mr-1"></i> All
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Document Type</th>
                                        <th>Submitted</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $request)
                                    <tr>
                                        <td><span class="font-weight-bold">#{{ $request->id }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle mr-2" style="width: 35px; height: 35px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <span class="font-weight-bold text-primary">{{ substr($request->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <strong>{{ $request->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $request->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $request->user->role_badge_color }} badge-pill px-3 py-2">
                                                {{ $request->user->getRoleName() }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-id-card text-info mr-1"></i>
                                            {{ str_replace('_', ' ', ucfirst($request->document_type)) }}
                                        </td>
                                        <td>
                                            <i class="far fa-calendar-alt text-muted mr-1"></i>
                                            {{ $request->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $request->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="status-container">
                                                @if($request->status == 'pending')
                                                    <span class="badge-pending">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                @elseif($request->status == 'approved')
                                                    <span class="badge-approved">
                                                        <i class="fas fa-check-circle"></i> Approved
                                                    </span>
                                                @else
                                                    <span class="badge-rejected">
                                                        <i class="fas fa-times-circle"></i> Rejected
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.verification.show', $request->id) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-shield-alt fa-4x text-muted mb-3"></i>
                                                <h5 class="text-muted">No verification requests found</h5>
                                                <p class="text-muted mb-0">There are no {{ request('status', '') }} requests at the moment.</p>
                                                @if(request('status') && request('status') != 'all')
                                                    <a href="{{ route('admin.verification.index', ['status' => 'all']) }}" class="btn btn-sm btn-primary mt-3">
                                                        <i class="fas fa-list mr-1"></i> View All
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($requests->hasPages())
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="pagination-info">
                                    <i class="fas fa-clipboard-list"></i>
                                    Showing <strong>{{ $requests->firstItem() }}</strong>
                                    to <strong>{{ $requests->lastItem() }}</strong>
                                    of <strong>{{ $requests->total() }}</strong> entries
                                </div>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($requests->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $requests->previousPageUrl() }}" rel="prev">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($requests->getUrlRange(max(1, $requests->currentPage() - 2), min($requests->lastPage(), $requests->currentPage() + 2)) as $page => $url)
                                            @if ($page == $requests->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($requests->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $requests->nextPageUrl() }}" rel="next">
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
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
