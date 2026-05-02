@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users text-primary mr-2"></i>Manage Users
        </h1>
        <div class="d-flex">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                <i class="fas fa-filter mr-1"></i> Filters
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Bulk Actions Form -->
    <form id="bulkActionForm" action="{{ route('admin.users.bulk-action') }}" method="POST">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Users List ({{ $users->total() }} total)
                </h6>
                <div class="d-flex">
                    <select name="action" class="form-control form-control-sm mr-2" style="width: 150px;" required>
                        <option value="">Bulk Action</option>
                        <option value="verify">Verify Selected</option>
                        <option value="unverify">Unverify Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary" id="bulkActionBtn">
                        Apply
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Contact</th>
                                <th>Verification</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox">
                                    </td>
                                    <td>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->business_name)
                                            <div class="mt-1">
                                                <small class="text-info">
                                                    <i class="fas fa-building mr-1"></i>{{ $user->business_name }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge badge-danger">Admin</span>
                                        @elseif($user->role == 'supplier')
                                            <span class="badge badge-info">Supplier</span>
                                        @elseif($user->role == 'farmer')
                                            <span class="badge badge-success">Farmer</span>
                                        @elseif($user->role == 'agent')
                                            <span class="badge badge-warning">Agent</span>
                                        @elseif($user->role == 'veterinary')
                                            <span class="badge badge-primary">Veterinary</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->phone)
                                            <div><i class="fas fa-phone mr-1"></i> {{ $user->phone }}</div>
                                        @endif
                                        @if($user->address)
                                            <div><i class="fas fa-map-marker-alt mr-1"></i> {{ Str::limit($user->address, 30) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_verified && $user->verification_status == 'approved')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>Verified
                                            </span>
                                            @if($user->verified_at)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        {{ $user->verified_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            @endif
                                        @elseif($user->verification_status == 'pending')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @elseif($user->verification_status == 'rejected')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-user mr-1"></i>Not Applied
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->created_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <button type="button" class="btn btn-danger"
                                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bootstrap Styled Pagination -->
                @if($users->hasPages())
                <div class="row mt-4">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($users->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($users->links()->elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            <li class="page-item disabled">
                                                <span class="page-link">{{ $element }}</span>
                                            </li>
                                        @endif

                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $users->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($users->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
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
    </form>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Users</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="all">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="supplier" {{ request('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="veterinary" {{ request('role') == 'veterinary' ? 'selected' : '' }}>Veterinary</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="verification_status">Verification Status</label>
                        <select class="form-control" id="verification_status" name="verification_status">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('verification_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Search by name, email, or phone...">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset Filters</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
/* Custom Pagination Styles */
.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #4e73df;
    background-color: #fff;
    border: 1px solid #dddfeb;
    margin: 0 2px;
    border-radius: 4px;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
}

.page-link:hover {
    z-index: 2;
    color: #2e59d9;
    text-decoration: none;
    background-color: #eaecf4;
    border-color: #dddfeb;
}

.page-item.disabled .page-link {
    color: #858796;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dddfeb;
}

/* Custom checkbox styling */
#selectAll {
    cursor: pointer;
    transform: scale(1.2);
}

.user-checkbox {
    cursor: pointer;
    transform: scale(1.1);
}

/* Table row hover effect */
#dataTable tbody tr:hover {
    background-color: #f8f9fc;
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
}
</style>
@endpush

@push('scripts')
<script>
// Select All Checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk Action Form Submission
document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
    if (selectedUsers.length === 0) {
        e.preventDefault();
        alert('Please select at least one user.');
        return false;
    }

    const action = this.querySelector('select[name="action"]').value;
    if (!action) {
        e.preventDefault();
        alert('Please select an action.');
        return false;
    }

    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete selected users? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    }
});

// Confirm Delete
function confirmDelete(userId, userName) {
    if (confirm('Are you sure you want to delete "' + userName + '"? This action cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/users/' + userId;
        form.submit();
    }
}
</script>
@endpush
