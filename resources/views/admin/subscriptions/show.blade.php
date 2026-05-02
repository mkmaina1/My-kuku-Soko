@extends('layouts.app')

@section('title', 'Plan Details: ' . $plan->name)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Plan Details: {{ $plan->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-{{ $plan->slug === 'pro' ? 'danger' : 'primary' }} text-white">
                        <h3 class="card-title">Plan Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $plan->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td><code>{{ $plan->slug }}</code></td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td><strong class="text-success">KES {{ number_format($plan->price, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Duration:</th>
                                <td>{{ ucfirst($plan->duration) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Total Subscribers:</th>
                                <td><span class="badge badge-primary">{{ $plan->subscriptions->count() }}</span></td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $plan->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Plan Features</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($plan->features_list as $feature)
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Subscribers ({{ $subscriptions->total() }})</h3>
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible mt-2">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                        @endif
                        @if(session('info'))
                        <div class="alert alert-info alert-dismissible mt-2">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
                        </div>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Veterinarian</th>
                                    <th>Email</th>
                                    <th>Subscribed On</th>
                                    <th>Expires On</th>
                                    <th>Amount Paid</th>
                                    <th>Payment Status</th>
                                    <th>Subscription Status</th>
                                    <th>Receipt</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->user->name }}</td>
                                    <td>{{ $sub->user->email }}</td>
                                    <td>{{ $sub->starts_at ? $sub->starts_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $sub->expires_at ? $sub->expires_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>KES {{ number_format($sub->amount_paid, 2) }}</td>
                                    <td>
                                        @if($sub->payment_verified)
                                            <span class="badge badge-success">Verified</span>
                                        @else
                                            <span class="badge badge-warning">Pending Verification</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sub->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($sub->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($sub->status === 'expired')
                                            <span class="badge badge-secondary">Expired</span>
                                        @else
                                            <span class="badge badge-danger">{{ ucfirst($sub->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sub->mpesa_receipt ?? 'N/A' }}</td>
                                    <td>
                                        @if(!$sub->payment_verified && $sub->status === 'pending')
                                        <form action="{{ route('admin.subscriptions.verify-payment', $sub->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verify payment and activate subscription?')">
                                                <i class="fas fa-check-circle mr-1"></i> Verify Payment
                                            </button>
                                        </form>
                                        @elseif($sub->payment_verified)
                                            <span class="badge badge-success">Verified</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="float-right">
                            {{ $subscriptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
