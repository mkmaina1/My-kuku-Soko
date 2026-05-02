@extends('layouts.app')

@section('title', 'Agent Analytics')

@push('styles')
<style>
    .commission-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .performance-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .rank-badge {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    .rank-1 { background: #ffc107; }
    .rank-2 { background: #6c757d; }
    .rank-3 { background: #dc3545; }
    .rank-other { background: #17a2b8; }
    .performance-meter {
        position: relative;
        height: 120px;
        margin: 20px 0;
    }
    .meter-fill {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to top, #28a745, #20c997);
        border-radius: 5px 5px 0 0;
        transition: height 1s ease-in-out;
    }
    .address-badge {
        background: linear-gradient(45deg, #6f42c1, #e83e8c);
        color: white;
        font-size: 0.8em;
        padding: 3px 8px;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<!-- <div class="content-wrapper"> -->
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-user-tie text-info mr-2"></i>
                        Agent Analytics Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                        <li class="breadcrumb-item active">Agents</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Agents</span>
                            <span class="info-box-number">
                                {{ $stats['total_agents'] }}
                                <small class="text-success">
                                    {{ $stats['agent_growth_rate'] > 0 ? '+' : '' }}{{ $stats['agent_growth_rate'] }}%
                                </small>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm commission-card">
                        <span class="info-box-icon elevation-1" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Commission</span>
                            <span class="info-box-number">
                                KES {{ number_format($stats['total_commission'], 0) }}
                            </span>
                            <span class="info-box-text">5% commission rate</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Agents</span>
                            <span class="info-box-number">
                                {{ $stats['active_agents'] }}
                                <small>
                                    ({{ $stats['total_agents'] > 0 ? round(($stats['active_agents']/$stats['total_agents'])*100, 1) : 0 }}%)
                                </small>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm performance-card">
                        <span class="info-box-icon elevation-1" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Agent Orders</span>
                            <span class="info-box-number">{{ $stats['total_orders'] }}</span>
                            <span class="info-box-text">
                                Avg {{ $stats['avg_orders_per_agent'] }}/agent
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Overview -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Agent Performance Tiers
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="performance-meter">
                                @php
                                    $maxTier = max($agentPerformance['performance_tiers']);
                                @endphp
                                @foreach($agentPerformance['performance_tiers'] as $tier => $count)
                                    @php
                                        $height = $maxTier > 0 ? ($count / $maxTier) * 100 : 0;
                                    @endphp
                                    <div class="meter-fill"
                                         style="height: {{ $height }}%; width: calc(100% / 4); left: calc({{ array_search($tier, array_keys($agentPerformance['performance_tiers'])) }} * 25%);"
                                        title="{{ ucfirst(str_replace('_', ' ', $tier)) }}: {{ $count }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-3">
                                    <h5 class="mb-0">{{ $agentPerformance['performance_tiers']['top_performers'] }}</h5>
                                    <small class="text-success">Top</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0">{{ $agentPerformance['performance_tiers']['average_performers'] }}</h5>
                                    <small class="text-info">Average</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0">{{ $agentPerformance['performance_tiers']['low_performers'] }}</h5>
                                    <small class="text-warning">Low</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0">{{ $agentPerformance['performance_tiers']['inactive'] }}</h5>
                                    <small class="text-danger">Inactive</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-money-check-alt mr-2"></i>
                                Commission Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="commissionChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>
                                Agent Growth Trend
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="agentGrowthChart"></canvas>
                        </div>
                        <div class="card-footer text-center">
                            <span class="text-success">
                                <i class="fas fa-arrow-up mr-1"></i>
                                {{ $stats['agent_growth_rate'] }}% growth rate
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Agents -->
            <div class="row">
                <div class="col-12">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-crown text-warning mr-2"></i>
                                Top Performing Agents
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-success">
                                    Total Commission: KES {{ number_format($stats['total_commission'], 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 50px;">Rank</th>
                                            <th>Agent</th>
                                            <th>Completed Orders</th>
                                            <th>Total Sales</th>
                                            <th>Commission</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topAgents as $index => $agent)
                                        @php
                                            $defaultAddress = $agent->addresses->where('is_default', true)->first() ?? $agent->addresses->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }}">
                                                    {{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-info text-white mr-3"
                                                         style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $agent->name }}</strong>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-envelope mr-1"></i>{{ $agent->email }}
                                                        </div>
                                                        @if($defaultAddress)
                                                        <div class="small mt-1">
                                                            <span class="address-badge">
                                                                <i class="fas fa-home mr-1"></i>
                                                                {{ $defaultAddress->city }}, {{ $defaultAddress->county }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary badge-pill" style="font-size: 1em;">
                                                    {{ $agent->agent_orders_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    KES {{ number_format($agent->agent_orders_sum_total, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning badge-pill" style="font-size: 1em;">
                                                    KES {{ number_format($agent->total_commission, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($defaultAddress)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                    {{ $defaultAddress->county }}
                                                </small>
                                                @else
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                    No address
                                                </small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($agent->is_verified || $agent->verification_status == 'approved')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i> Active
                                                </span>
                                                @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.users.show', $agent) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $agent) }}"
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
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

            <!-- Geographical Distribution -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-globe-africa mr-2"></i>
                                Agent Geographical Distribution (by County)
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($agentLocations->count() > 0)
                            <div class="row">
                                @foreach($agentLocations->take(8) as $location)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                                            </div>
                                            <h5 class="card-title">{{ $location->location ?? 'Unknown County' }}</h5>
                                            <div class="h3 mb-2">{{ $location->total }}</div>
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-info"
                                                    style="width: {{ ($location->total/$stats['total_agents'])*100 }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ round(($location->total/$stats['total_agents'])*100, 1) }}% of total
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-globe fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No address data available</p>
                                <small class="text-info">
                                    <i class="fas fa-info-circle"></i>
                                    Agents need to add addresses to see geographical distribution
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Agent Growth Chart
    const agentGrowthCtx = document.getElementById('agentGrowthChart').getContext('2d');
    const agentGrowthChart = new Chart(agentGrowthCtx, {
        type: 'line',
        data: {
            labels: @json($agentGrowth->pluck('month')),
            datasets: [{
                label: 'New Agents',
                data: @json($agentGrowth->pluck('count')),
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                pointBackgroundColor: '#17a2b8',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Commission Distribution Chart
    const commissionCtx = document.getElementById('commissionChart').getContext('2d');
    const commissionChart = new Chart(commissionCtx, {
        type: 'bar',
        data: {
            labels: ['0-1K', '1K-5K', '5K-10K', '10K-50K', '50K+'],
            datasets: [{
                label: 'Number of Agents',
                data: [
                    {{ $commissionDistribution['0-1000'] }},
                    {{ $commissionDistribution['1001-5000'] }},
                    {{ $commissionDistribution['5001-10000'] }},
                    {{ $commissionDistribution['10001-50000'] }},
                    {{ $commissionDistribution['50001+'] }}
                ],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(23, 162, 184, 0.7)',
                    'rgba(108, 117, 125, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    '#ffc107',
                    '#28a745',
                    '#17a2b8',
                    '#6c757d',
                    '#dc3545'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
