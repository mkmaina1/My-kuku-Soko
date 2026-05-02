@extends('layouts.app')

@section('title', 'Subscription Statistics')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Subscription Statistics</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a></li>
                    <li class="breadcrumb-item active">Statistics</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_plans'] }}</h3>
                        <p>Total Plans</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['active_plans'] }}</h3>
                        <p>Active Plans</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['active_subscriptions'] }}</h3>
                        <p>Active Subscribers</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>KES {{ number_format($stats['revenue'], 2) }}</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions by Plan -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Subscriptions by Plan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="subscriptionsChart" style="min-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">Plan Performance</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Price</th>
                                    <th>Subscribers</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['by_plan'] as $plan)
                                <tr>
                                    <td><strong>{{ $plan->name }}</strong></td>
                                    <td>KES {{ number_format($plan->price, 2) }}</td>
                                    <td><span class="badge badge-primary">{{ $plan->subscriptions_count }}</span></td>
                                    <td>KES {{ number_format($plan->subscriptions->sum('amount_paid'), 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Subscriptions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">Recent Subscriptions</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Veterinarian</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>M-Pesa Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $sub->user->name }}</td>
                                    <td>{{ $sub->plan_name }}</td>
                                    <td>KES {{ number_format($sub->amount_paid, 2) }}</td>
                                    <td>
                                        @if($sub->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($sub->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">{{ ucfirst($sub->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sub->mpesa_receipt ?? 'N/A' }}</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    var ctx = document.getElementById('subscriptionsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($stats['by_plan'] as $plan)
                    '{{ $plan->name }} ({{ $plan->subscriptions_count }})',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($stats['by_plan'] as $plan)
                        {{ $plan->subscriptions_count }},
                    @endforeach
                ],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
