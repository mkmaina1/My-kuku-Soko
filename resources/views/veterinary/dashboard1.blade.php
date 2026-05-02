@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-md text-danger mr-2"></i>Veterinary Dashboard
        </h1>
        <div class="d-flex">
            <span class="badge badge-pill badge-success p-2 mr-2">
                <i class="fas fa-certificate mr-1"></i>Licensed Veterinarian
            </span>
            <a href="#" class="btn btn-danger btn-sm">
                <i class="fas fa-plus mr-1"></i>New Appointment
            </a>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-danger shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-1">Welcome, Dr. {{ $user->name }}!</h4>
                            <p class="card-text text-muted mb-0">
                                <i class="fas fa-stethoscope text-danger mr-1"></i>
                                You have {{ $stats['appointments_today'] }} appointments today and {{ $stats['pending_consultations'] ?? 0 }} pending consultations.
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="d-flex justify-content-end">
                                <div class="mr-3 text-center">
                                    <div class="text-xs font-weight-bold text-danger">Emergency Cases</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['emergency_cases'] ?? 0 }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-success">Availability</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['availability_status'] ?? 'Available' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Appointments Today -->
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
                                    <i class="fas fa-clock mr-1"></i>{{ $stats['completed_today'] ?? 0 }} completed
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

        <!-- Animals Treated -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Animals Treated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['animals_treated'] ?? 0 }}</div>
                            <div class="mt-2">
                                <div class="d-flex">
                                    <span class="badge badge-success mr-1">Poultry: {{ $stats['poultry_treated'] ?? 0 }}</span>
                                    <span class="badge badge-primary">Livestock: {{ $stats['livestock_treated'] ?? 0 }}</span>
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

        <!-- Pending Consultations -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Consultations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_consultations'] ?? 0 }}</div>
                            <div class="mt-2">
                                @if($stats['emergency_pending'] ?? 0 > 0)
                                <span class="badge badge-danger mr-1">
                                    {{ $stats['emergency_pending'] }} Emergency
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Cases -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Emergency Cases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['emergency_cases'] ?? 0 }}</div>
                            <div class="mt-2">
                                <span class="text-danger small">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Response Time: {{ $stats['avg_response_time'] ?? '15min' }}
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

    <!-- Main Content Row -->
    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day mr-2"></i>Today's Schedule
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">View Week</a>
                            <a class="dropdown-item" href="#">Print Schedule</a>
                        </div>
                    </div>
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
                                @forelse($todays_appointments ?? [] as $appointment)
                                <tr class="{{ $appointment['is_emergency'] ? 'table-danger' : '' }}">
                                    <td>
                                        <i class="fas fa-clock mr-1 text-muted"></i>
                                        {{ $appointment['time'] }}
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $appointment['farm_name'] }}</div>
                                        <small class="text-muted">{{ $appointment['animal_type'] }}</small>
                                    </td>
                                    <td>{{ $appointment['service'] }}</td>
                                    <td>
                                        @if($appointment['status'] == 'scheduled')
                                        <span class="badge badge-info">Scheduled</span>
                                        @elseif($appointment['status'] == 'in_progress')
                                        <span class="badge badge-warning">In Progress</span>
                                        @elseif($appointment['status'] == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                        @elseif($appointment['status'] == 'cancelled')
                                        <span class="badge badge-danger">Cancelled</span>
                                        @endif
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
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-muted">No appointments scheduled for today</p>
                                        <a href="#" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i>Schedule Appointment
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Health Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-ambulance fa-sm mr-1"></i>Emergency
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-success btn-block">
                                <i class="fas fa-calendar-plus fa-sm mr-1"></i>Schedule
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-info btn-block">
                                <i class="fas fa-file-medical fa-sm mr-1"></i>Reports
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-pills fa-sm mr-1"></i>Prescribe
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Advisory -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heartbeat mr-2"></i>Health Advisory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Avian Flu Alert:</strong> Monitor for symptoms in poultry
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Vaccination Coverage</span>
                            <span class="font-weight-bold">{{ $stats['vaccination_rate'] ?? '85%' }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%"
                                 aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Disease Prevention</span>
                            <span class="font-weight-bold">{{ $stats['prevention_rate'] ?? '92%' }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 92%"
                                 aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between small">
                        <div>
                            <i class="fas fa-temperature-high text-danger mr-1"></i>
                            <span>High Risk: {{ $stats['high_risk_cases'] ?? 3 }}</span>
                        </div>
                        <div>
                            <i class="fas fa-shield-alt text-success mr-1"></i>
                            <span>Protected: {{ $stats['protected_farms'] ?? 42 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Recent Patients
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recent_patients ?? [] as $patient)
                        <div class="list-group-item list-group-item-action flex-column align-items-start py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if($patient['animal_type'] == 'poultry')
                                        <span class="badge badge-warning p-2">
                                            <i class="fas fa-kiwi-bird"></i>
                                        </span>
                                        @else
                                        <span class="badge badge-primary p-2">
                                            <i class="fas fa-cow"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $patient['farm_name'] }}</h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-paw mr-1"></i>{{ $patient['animal_count'] }} {{ $patient['animal_type'] }} |
                                            <i class="fas fa-stethoscope mr-1 ml-2"></i>{{ $patient['diagnosis'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted d-block">{{ $patient['last_visit'] }}</small>
                                    @if($patient['follow_up'])
                                    <span class="badge badge-info">Follow-up</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-paw fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No recent patient records</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Offered -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks mr-2"></i>Services Offered
                    </h6>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($services_offered ?? [
                            ['name' => 'Vaccination', 'icon' => 'fas fa-syringe', 'color' => 'success'],
                            ['name' => 'Check-up', 'icon' => 'fas fa-stethoscope', 'color' => 'info'],
                            ['name' => 'Surgery', 'icon' => 'fas fa-cut', 'color' => 'danger'],
                            ['name' => 'Consultation', 'icon' => 'fas fa-comment-medical', 'color' => 'primary'],
                            ['name' => 'Emergency', 'icon' => 'fas fa-ambulance', 'color' => 'warning'],
                            ['name' => 'Lab Tests', 'icon' => 'fas fa-vial', 'color' => 'secondary']
                        ] as $service)
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-{{ $service['color'] }} h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="{{ $service['icon'] }} fa-2x text-{{ $service['color'] }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $service['name'] }}</h6>
                                            <small class="text-muted">{{ $service['price'] ?? 'Ksh 1,500' }}</small>
                                        </div>
                                        <div class="ml-auto">
                                            <span class="badge badge-{{ $service['color'] }}">
                                                {{ $service['count'] ?? '12' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
                            <h5 class="card-title text-danger mb-1">
                                <i class="fas fa-exclamation-circle mr-2"></i>Emergency Hotline
                            </h5>
                            <p class="card-text mb-0">
                                Available 24/7 for emergency veterinary cases. Immediate response guaranteed.
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="d-flex flex-column align-items-end">
                                <a href="tel:+254712345678" class="btn btn-danger btn-lg mb-2">
                                    <i class="fas fa-phone mr-2"></i>Call Now
                                </a>
                                <small class="text-muted">Response time: 15 minutes or less</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Veterinary Dashboard -->
<style>
    .role-icon.veterinary-icon {
        color: #e74a3b;
        background: rgba(231, 74, 59, 0.1);
        width: 60px;
        height: 60px;
        line-height: 60px;
        border-radius: 50%;
        margin: 0 auto 20px;
        font-size: 24px;
    }

    .emergency-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    .veterinary-service-card {
        transition: all 0.3s;
        cursor: pointer;
    }

    .veterinary-service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .health-stat-card {
        border-top: 3px solid #36b9cc;
    }

    .animal-icon-badge {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>
@endsection

<!-- Optional JavaScript for Interactive Elements -->
@push('scripts')
<script>
    // Update appointment status in real-time
    function updateAppointmentStatus(appointmentId, status) {
        fetch(`/veterinary/appointments/${appointmentId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Status updated successfully', 'success');
                location.reload();
            }
        });
    }

    // Handle emergency call
    function initiateEmergencyCall() {
        if (confirm('Are you sure you want to initiate an emergency call?')) {
            // Start emergency protocol
            startEmergencyProtocol();
        }
    }

    function startEmergencyProtocol() {
        // Show emergency modal or redirect
        $('#emergencyModal').modal('show');
        // Log emergency start
        logEmergencyStart();
    }

    // Schedule management
    function loadSchedule(date) {
        // Load schedule for specific date
        fetch(`/veterinary/schedule?date=${date}`)
            .then(response => response.json())
            .then(data => {
                updateScheduleTable(data.appointments);
            });
    }

    // Quick action handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Emergency button
        document.getElementById('emergencyBtn')?.addEventListener('click', initiateEmergencyCall);

        // Schedule navigation
        document.querySelectorAll('.schedule-nav').forEach(button => {
            button.addEventListener('click', function() {
                const date = this.dataset.date;
                loadSchedule(date);
            });
        });

        // Appointment status buttons
        document.querySelectorAll('.appointment-status-btn').forEach(button => {
            button.addEventListener('click', function() {
                const appointmentId = this.dataset.id;
                const status = this.dataset.status;
                updateAppointmentStatus(appointmentId, status);
            });
        });

        // Initialize calendar
        initializeVetCalendar();
    });

    function initializeVetCalendar() {
        // Initialize full calendar or custom calendar
        console.log('Veterinary calendar initialized');
    }

    function showNotification(message, type = 'info') {
        // Use Toastr or custom notification
        toastr[type](message);
    }

    function logEmergencyStart() {
        // Log emergency start for tracking
        console.log('Emergency protocol initiated at:', new Date().toLocaleString());
    }
</script>
@endpush
