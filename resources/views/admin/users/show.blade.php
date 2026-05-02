@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user text-primary mr-2"></i>User Details
        </h1>
        <div class="d-flex">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning mr-2">
                <i class="fas fa-edit mr-1"></i> Edit User
            </a>
            @if($user->id !== auth()->id())
                <button type="button" class="btn btn-danger"
                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')">
                    <i class="fas fa-trash mr-1"></i> Delete User
                </button>
            @endif
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

    <div class="row">
        <!-- User Information -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="img-thumbnail rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 100px; height: 100px; background-color: #4e73df;">
                            <span class="h3 text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        <h4 class="mt-3">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>

                        <div class="mb-3">
                            @if($user->role == 'admin')
                                <span class="badge badge-danger badge-lg p-2">Admin</span>
                            @elseif($user->role == 'supplier')
                                <span class="badge badge-info badge-lg p-2">Supplier</span>
                            @elseif($user->role == 'farmer')
                                <span class="badge badge-success badge-lg p-2">Farmer</span>
                            @elseif($user->role == 'agent')
                                <span class="badge badge-warning badge-lg p-2">Agent</span>
                            @elseif($user->role == 'veterinary')
                                <span class="badge badge-primary badge-lg p-2">Veterinary</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            @if($user->is_verified && $user->verification_status == 'approved')
                                <span class="badge badge-success badge-lg p-2">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @elseif($user->verification_status == 'pending')
                                <span class="badge badge-warning badge-lg p-2">
                                    <i class="fas fa-clock mr-1"></i> Pending Verification
                                </span>
                            @elseif($user->verification_status == 'rejected')
                                <span class="badge badge-danger badge-lg p-2">
                                    <i class="fas fa-times-circle mr-1"></i> Verification Rejected
                                </span>
                            @else
                                <span class="badge badge-secondary badge-lg p-2">
                                    <i class="fas fa-user mr-1"></i> Not Verified
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-id-card mr-2"></i>Contact Information
                        </h6>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <strong>Phone:</strong><br>
                                <span class="text-muted">{{ $user->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong>Address:</strong><br>
                                <span class="text-muted">{{ $user->address ?? 'Not provided' }}</span>
                            </div>
                            <div class="col-12">
                                <strong>Joined:</strong><br>
                                <span class="text-muted">{{ $user->created_at->format('F d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($user->role == 'supplier')
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-info mb-3">
                                <i class="fas fa-building mr-2"></i>Business Information
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <strong>Business Name:</strong><br>
                                    <span class="text-muted">{{ $user->business_name ?? 'Not provided' }}</span>
                                </div>
                                <div class="col-12">
                                    <strong>Registration:</strong><br>
                                    <span class="text-muted">{{ $user->business_registration ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($user->role == 'farmer')
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-success mb-3">
                                <i class="fas fa-tractor mr-2"></i>Farm Information
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <strong>Farm Name:</strong><br>
                                    <span class="text-muted">{{ $user->farm_name ?? 'Not provided' }}</span>
                                </div>
                                <div class="col-12 mb-2">
                                    <strong>Location:</strong><br>
                                    <span class="text-muted">{{ $user->farm_location ?? 'Not provided' }}</span>
                                </div>
                                <div class="col-12">
                                    <strong>Size:</strong><br>
                                    <span class="text-muted">{{ $user->farm_size ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($user->role == 'agent')
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-warning mb-3">
                                <i class="fas fa-id-badge mr-2"></i>Agent Information
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <strong>Agent ID:</strong><br>
                                    <span class="text-muted">{{ $user->agent_id_number ?? 'Not provided' }}</span>
                                </div>
                                <div class="col-12">
                                    <strong>License Number:</strong><br>
                                    <span class="text-muted">{{ $user->license_number ?? 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($user->is_verified && $user->verified_at)
                        <div class="alert alert-success">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-check-circle mr-2"></i>Verification Details
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <strong>Verified On:</strong><br>
                                    <span class="text-muted">{{ $user->verified_at->format('F d, Y H:i') }}</span>
                                </div>
                                @if($user->verifier)
                                    <div class="col-12 mb-2">
                                        <strong>Verified By:</strong><br>
                                        <span class="text-muted">{{ $user->verifier->name }}</span>
                                    </div>
                                @endif
                                @if($user->verification_notes)
                                    <div class="col-12">
                                        <strong>Notes:</strong><br>
                                        <span class="text-muted">{{ $user->verification_notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="col-xl-8 col-lg-7">
            <!-- Statistics -->
            <div class="row">
                @if($user->role == 'supplier')
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Products Listed
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $user->products_count ?? 0 }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-4 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Orders Placed
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $user->orders_count ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->role == 'agent')
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Assisted Orders
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $user->agent_orders_count ?? 0 }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body">
                    @if($user->role == 'supplier' && $user->products->count() > 0)
                        <h6 class="font-weight-bold text-info mb-3">
                            <i class="fas fa-box mr-2"></i>Recent Products
                        </h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->products->take(5) as $product)
                                        <tr>
                                            <td>
                                                <strong>{{ Str::limit($product->title, 30) }}</strong><br>
                                                <small class="text-muted">{{ $product->product_type }}</small>
                                            </td>
                                            <td class="text-success">KES {{ number_format($product->price) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $product->quantity > 10 ? 'success' : 'warning' }}">
                                                    {{ $product->quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($product->is_verified)
                                                    <span class="badge badge-success">Verified</span>
                                                @else
                                                    <span class="badge badge-secondary">Unverified</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($user->orders->count() > 0)
                        <h6 class="font-weight-bold text-success mb-3">
                            <i class="fas fa-shopping-cart mr-2"></i>Recent Orders
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->take(5) as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->order_number }}</strong>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="text-success">KES {{ number_format($order->total) }}</td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($order->status == 'processing')
                                                    <span class="badge badge-info">Processing</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge badge-primary">Shipped</span>
                                                @elseif($order->status == 'delivered')
                                                    <span class="badge badge-success">Delivered</span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge badge-danger">Cancelled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent activity found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verification Documents -->
            @if($user->verification_documents && count($user->verification_documents) > 0)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-file-alt mr-2"></i>Verification Documents
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($user->verification_documents as $index => $document)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                Document {{ $index + 1 }}
                                            </h6>
                                            <p class="card-text">
                                                <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $document['type'])) }}<br>
                                                <strong>Uploaded:</strong> {{ \Carbon\Carbon::parse($document['uploaded_at'])->format('M d, Y H:i') }}
                                            </p>
                                            <a href="{{ asset('storage/' . $document['path']) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-download mr-1"></i> View Document
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(userId, userName) {
    if (confirm('Are you sure you want to delete "' + userName + '"? This action cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/users/' + userId;
        form.submit();
    }
}
</script>
@endpush
