@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-user-md mr-2"></i>Welcome, Dr. {{ $user->name }}!
                            </h4>
                            <p class="mb-0 opacity-75">
                                <span class="badge badge-light text-primary mr-2">Basic Plan</span>
                                Your subscription expires in {{ $stats['days_remaining'] }} days
                            </p>
                        </div>
                        <a href="{{ route('veterinary.subscription.show', 'pro') }}" class="btn btn-warning">
                            <i class="fas fa-crown mr-2"></i>Upgrade to Pro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upgrade Alert -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle mr-2"></i>
        You're on the Basic Plan. Upgrade to Pro for unlimited consultations and farm visits!
        <a href="{{ route('veterinary.subscription.show', 'pro') }}" class="alert-link ml-2">Upgrade Now →</a>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Appointments Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['appointments_today'] }}</div>
                            <div class="mt-2">
                                <span class="text-info small">
                                    <i class="fas fa-clock mr-1"></i>{{ $stats['completed_today'] }} completed
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                Animals Treated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['animals_treated'] }}</div>
                            <div class="mt-2">
                                <div class="d-flex">
                                    <span class="badge badge-success mr-1">Poultry: {{ $stats['poultry_treated'] }}</span>
                                    <span class="badge badge-primary">Livestock: {{ $stats['livestock_treated'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paw fa-2x text-gray-300"></i>
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
                                Pending Consultations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_consultations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-medical fa-2x text-gray-300"></i>
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
                            <div class="mt-2">
                                <span class="text-danger small">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Response: {{ $stats['avg_response_time'] }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ambulance fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Monthly Usage (Basic Plan Limits)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Consultations</h6>
                            <div class="progress mb-2" style="height: 10px;">
                                @php
                                    $consultPercent = ($stats['consultations_used'] / $stats['consultations_limit']) * 100;
                                @endphp
                                <div class="progress-bar bg-primary" style="width: {{ $consultPercent }}%"></div>
                            </div>
                            <p class="text-muted small">
                                {{ $stats['consultations_used'] }} of {{ $stats['consultations_limit'] }} used
                                ({{ $stats['consultations_limit'] - $stats['consultations_used'] }} remaining)
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Farm Visits</h6>
                            <div class="progress mb-2" style="height: 10px;">
                                @php
                                    $visitPercent = ($stats['farm_visits_used'] / $stats['farm_visits_limit']) * 100;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $visitPercent }}%"></div>
                            </div>
                            <p class="text-muted small">
                                {{ $stats['farm_visits_used'] }} of {{ $stats['farm_visits_limit'] }} used
                                ({{ $stats['farm_visits_limit'] - $stats['farm_visits_used'] }} remaining)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day mr-2"></i>Today's Schedule
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Patient/Farm</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todays_appointments as $appointment)
                                <tr class="{{ $appointment['is_emergency'] ? 'table-danger' : '' }}">
                                    <td><i class="fas fa-clock mr-1 text-muted"></i>{{ $appointment['time'] }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $appointment['farm_name'] }}</div>
                                        <small class="text-muted">{{ $appointment['animal_type'] }}</small>
                                    </td>
                                    <td>{{ $appointment['service'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $appointment['status'] == 'completed' ? 'success' : 'info' }}">
                                            {{ ucfirst($appointment['status']) }}
                                        </span>
                                        @if($appointment['is_emergency'])
                                        <span class="badge badge-danger ml-1">Emergency</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No appointments scheduled for today</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-ambulance mr-1"></i>Emergency
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-success btn-block">
                                <i class="fas fa-calendar-plus mr-1"></i>Schedule
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-info btn-block">
                                <i class="fas fa-file-medical mr-1"></i>Reports
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-pills mr-1"></i>Prescribe
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Advisory -->
            <div class="card shadow mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heartbeat mr-2"></i>Health Advisory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Avian Flu Alert:</strong> Monitor for symptoms
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Vaccination Coverage</span>
                            <span class="font-weight-bold">{{ $stats['vaccination_rate'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
