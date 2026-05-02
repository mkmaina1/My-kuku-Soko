@extends('layouts.app')

@section('title', 'Edit Address')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-edit mr-2"></i>Edit Address
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.addresses.index') }}">Addresses</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Edit Shipping Address
                        </h3>
                    </div>
                    <form action="{{ route('farmer.addresses.update', $address) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Address Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $address->name) }}"
                                               placeholder="e.g., Home, Work, Farm" required>
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_name">Contact Name</label>
                                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                               id="contact_name" name="contact_name"
                                               value="{{ old('contact_name', $address->contact_name) }}"
                                               placeholder="Optional: Different from your name">
                                        @error('contact_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number *</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone', $address->phone) }}"
                                               placeholder="e.g., 0712 345 678" required>
                                        @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="street">Street Address *</label>
                                        <input type="text" class="form-control @error('street') is-invalid @enderror"
                                               id="street" name="street" value="{{ old('street', $address->street) }}"
                                               placeholder="e.g., 123 Main Street" required>
                                        @error('street')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City/Town *</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                               id="city" name="city" value="{{ old('city', $address->city) }}"
                                               placeholder="e.g., Nairobi" required>
                                        @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="county">County *</label>
                                        <select class="form-control @error('county') is-invalid @enderror"
                                                id="county" name="county" required>
                                            <option value="">Select County</option>
                                            @foreach($counties as $county)
                                            <option value="{{ $county }}" {{ old('county', $address->county) == $county ? 'selected' : '' }}>
                                                {{ $county }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('county')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postal_code">Postal Code *</label>
                                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                               id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}"
                                               placeholder="e.g., 00100" required>
                                        @error('postal_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="landmark">Landmark (Optional)</label>
                                        <input type="text" class="form-control @error('landmark') is-invalid @enderror"
                                               id="landmark" name="landmark" value="{{ old('landmark', $address->landmark) }}"
                                               placeholder="e.g., Near shopping center, Opposite petrol station">
                                        @error('landmark')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_default" name="is_default"
                                           value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_default">
                                        Set as default shipping address
                                    </label>
                                    <small class="form-text text-muted">
                                        This address will be used as your primary shipping address for all orders.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('farmer.addresses.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left mr-1"></i> Cancel
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i> Update Address
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
