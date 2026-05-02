@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list text-success mr-2"></i>My Orders
        </h1>
    </div>
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-success mb-3"></i>
            <h3>My Orders</h3>
            <p class="text-muted">This page is under construction</p>
            <a href="{{ route('farmer.dashboard') }}" class="btn btn-success">
                <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
