@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-map-marker-alt mr-2"></i>My Addresses
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Addresses</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-address-book mr-2"></i>
                            Saved Addresses
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('farmer.addresses.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus mr-1"></i> Add New Address
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border-{{ $address->is_default ? 'success' : 'secondary' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-{{ $address->is_default ? 'home' : 'map-marker-alt' }} mr-2"></i>
                                            {{ $address->name }}
                                        </h5>
                                        @if($address->is_default)
                                        <span class="badge badge-success">Default</span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            <i class="fas fa-user mr-2 text-muted"></i>
                                            {{ $address->contact_name ?: Auth::user()->name }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-phone mr-2 text-muted"></i>
                                            {{ $address->phone }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-map-pin mr-2 text-muted"></i>
                                            {{ $address->street }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-city mr-2 text-muted"></i>
                                            {{ $address->city }}, {{ $address->county }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-mail-bulk mr-2 text-muted"></i>
                                            {{ $address->postal_code }}
                                        </p>
                                        @if($address->landmark)
                                        <p class="mb-0">
                                            <i class="fas fa-landmark mr-2 text-muted"></i>
                                            {{ $address->landmark }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            @if(!$address->is_default)
                                            <form action="{{ route('farmer.addresses.set-default', $address) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-star mr-1"></i> Set Default
                                                </button>
                                            </form>
                                            @else
                                            <span class="btn btn-sm btn-success disabled">
                                                <i class="fas fa-check mr-1"></i> Default Address
                                            </span>
                                            @endif

                                            <div>
                                                <a href="{{ route('farmer.addresses.edit', $address) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $address->id }})">
                                                    <i class="fas fa-trash mr-1"></i> Delete
                                                </button>
                                                <form id="delete-form-{{ $address->id }}" action="{{ route('farmer.addresses.destroy', $address) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">No Addresses Found</h4>
                            <p class="text-muted mb-4">You haven't added any shipping addresses yet.</p>
                            <a href="{{ route('farmer.addresses.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus mr-1"></i> Add Your First Address
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function confirmDelete(addressId) {
        if (confirm('Are you sure you want to delete this address?')) {
            document.getElementById('delete-form-' + addressId).submit();
        }
    }
</script>
@endsection
