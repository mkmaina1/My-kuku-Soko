@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store text-success mr-2"></i>Marketplace
        </h1>
        <div class="d-flex">
            <div class="input-group mr-2" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Search chicks, feed, supplies...">
                <div class="input-group-append">
                    <button class="btn btn-success" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <a href="{{ route('farmer.cart.index') }}" class="btn btn-warning">
                <i class="fas fa-shopping-cart mr-1"></i>Cart
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Filter by Farm:</label>
                    <select class="form-control form-control-sm">
                        <option value="">All Farms</option>
                        @foreach($filters['farms'] as $farm)
                        <option value="{{ $farm }}">{{ $farm }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Price Range:</label>
                    <select class="form-control form-control-sm">
                        <option value="">All Prices</option>
                        @foreach($filters['price_ranges'] as $range)
                        <option value="{{ $range }}">KES {{ $range }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Location:</label>
                    <select class="form-control form-control-sm">
                        <option value="">All Locations</option>
                        @foreach($filters['locations'] as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Age:</label>
                    <select class="form-control form-control-sm">
                        <option value="">All Ages</option>
                        @foreach($filters['ages'] as $age)
                        <option value="{{ $age }}">{{ $age }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @for($i = 1; $i <= 8; $i++)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card product-card shadow h-100">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/300x200/1cc88a/ffffff?text=Poultry"
                         class="card-img-top" alt="Product Image">
                    <span class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded">
                        <i class="fas fa-star mr-1"></i>4.8
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="card-title font-weight-bold">Kienyeji Chicks (Day Old)</h6>
                    <p class="card-text small text-muted mb-2">
                        <i class="fas fa-tractor mr-1"></i>Green Pastures Farm
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="font-weight-bold text-success">KES 250</span>
                            <small class="d-block text-muted">per chick</small>
                        </div>
                        <div class="text-warning small">
                            <i class="fas fa-map-marker-alt mr-1"></i>Kiambu
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('farmer.marketplace.product.details', $i) }}"
                           class="btn btn-outline-success btn-sm btn-block">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection
