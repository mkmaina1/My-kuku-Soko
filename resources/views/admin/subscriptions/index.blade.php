@extends('layouts.app')

@section('title', 'Manage Subscription Plans')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Subscription Plans</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Subscriptions</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-crown mr-2"></i>
                            Subscription Plans Management
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Add New Plan
                            </a>
                            <a href="{{ route('admin.subscriptions.statistics') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-chart-bar mr-1"></i> Statistics
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Plan Name</th>
                                    <th>Price (KES)</th>
                                    <th>Duration</th>
                                    <th>Features</th>
                                    <th>Status</th>
                                    <th>Subscribers</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                <tr>
                                    <td>{{ $plan->id }}</td>
                                    <td>
                                        <strong>{{ $plan->name }}</strong>
                                        @if($plan->slug === 'pro')
                                            <span class="badge badge-danger">PRO</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">KES {{ number_format($plan->price, 2) }}</strong>
                                    </td>
                                    <td>{{ ucfirst($plan->duration) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#featuresModal-{{ $plan->id }}">
                                            <i class="fas fa-list"></i> View Features
                                        </button>

                                        <!-- Features Modal -->
                                        <div class="modal fade" id="featuresModal-{{ $plan->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title">{{ $plan->name }} Plan Features</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
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
                                    </td>
                                    <td>
                                        @if($plan->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $plan->subscriptions->count() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.subscriptions.edit', $plan->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.subscriptions.toggle-status', $plan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-{{ $plan->is_active ? 'secondary' : 'success' }}" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $plan->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.subscriptions.show', $plan->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('admin.subscriptions.destroy', $plan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
