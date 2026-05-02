@extends('layouts.app')

@section('title', $title)

@section('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-crown mr-2"></i>Welcome, Dr. {{ $user->name }}!
                            </h4>
                            <p class="mb-0 opacity-75">
                                <span class="badge badge-light text-danger mr-2">Pro Plan</span>
                                Full access • {{ $stats['days_remaining'] }} days remaining
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="h3 mb-0">{{ $stats['consultations_month'] }}</div>
                            <small>Consultations this month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Consultations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['consultations_total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Farm Visits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['farm_visits_total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tractor fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Telemedicine</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['telemedicine_sessions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-video fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Emergency Cases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['emergency_cases'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ambulance fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Consultations Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="consultationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Vaccination Rate</span>
                            <span class="font-weight-bold">{{ $stats['vaccination_rate'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 94%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Prevention Rate</span>
                            <span class="font-weight-bold">{{ $stats['prevention_rate'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 96%"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-clock text-info"></i>
                            <span class="ml-1">Avg Response: {{ $stats['avg_response_time'] }}</span>
                        </div>
                        <div>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="ml-1">{{ $stats['completed_today'] }} Today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day mr-2"></i>Today's Schedule
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Farm</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todays_appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment['time'] }}</td>
                                    <td>{{ $appointment['farm_name'] }}</td>
                                    <td>
                                        @if($appointment['service'] == 'Emergency')
                                        <span class="badge badge-danger">Emergency</span>
                                        @else
                                        <span class="badge badge-info">Regular</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $appointment['status'] == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($appointment['status']) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No appointments today</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Recent Patients
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($recent_patients as $patient)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $patient['farm_name'] }}</h6>
                                    <small class="text-muted">
                                        {{ $patient['animal_count'] }} {{ $patient['animal_type'] }} • {{ $patient['diagnosis'] }}
                                    </small>
                                </div>
                                <small class="text-muted">{{ $patient['last_visit'] }}</small>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center py-3">No recent patients</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-left-danger">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="text-danger mb-1">
                                <i class="fas fa-exclamation-circle mr-2"></i>Emergency Hotline
                            </h5>
                            <p class="mb-0">24/7 emergency support available</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="tel:+254712345678" class="btn btn-danger">
                                <i class="fas fa-phone mr-2"></i>Call Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    var ctx = document.getElementById('consultationsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Consultations',
                data: {!! json_encode($chartData['data']) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
