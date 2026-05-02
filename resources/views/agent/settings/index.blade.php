@extends('layouts.app')

@section('title', 'Agent Settings')

@section('styles')
<style>
    .settings-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .settings-card .card-header {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        color: white;
        padding: 15px 20px;
        border-bottom: none;
    }

    .settings-card .card-body {
        padding: 20px;
    }

    .settings-nav {
        border-right: 1px solid #e9ecef;
    }

    .settings-nav .nav-link {
        padding: 12px 20px;
        border-radius: 5px;
        margin-bottom: 5px;
        color: #495057;
        transition: all 0.3s;
    }

    .settings-nav .nav-link.active {
        background: rgba(102, 187, 106, 0.1);
        color: #2e7d32;
        border-left: 3px solid #2e7d32;
        font-weight: 600;
    }

    .settings-nav .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }

    .settings-nav .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }

    .target-progress {
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
        background: #e9ecef;
    }

    .target-progress-bar {
        height: 100%;
        transition: width 0.3s;
    }

    .notification-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .notification-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .notification-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .notification-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .notification-slider {
        background-color: #66bb6a;
    }

    input:checked + .notification-slider:before {
        transform: translateX(26px);
    }

    .business-info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-left: 4px solid #2e7d32;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Agent Settings
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3">
            <div class="settings-card">
                <div class="card-body p-0">
                    <div class="settings-nav">
                        <ul class="nav flex-column" id="settingsTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="business-tab" data-toggle="tab" href="#business" role="tab">
                                    <i class="fas fa-building"></i> Business Information
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab">
                                    <i class="fas fa-bell"></i> Notification Settings
                                </a>
                            </li> -->
<li class="nav-item">
    <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab">
        <i class="fas fa-bell"></i> Notifications
        @if(isset($unreadCount) && $unreadCount > 0)
        <span class="badge badge-danger badge-pill ms-1">{{ $unreadCount }}</span>
        @endif
    </a>
</li>
<li class="nav-item">
                                <a class="nav-link" id="performance-tab" data-toggle="tab" href="#performance" role="tab">
                                    <i class="fas fa-bullseye"></i> Performance Targets
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="working-hours-tab" data-toggle="tab" href="#working-hours" role="tab">
                                    <i class="fas fa-clock"></i> Working Hours
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="settings-card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <small class="text-muted">Current Commission Rate</small>
                            <h4 class="mb-0">{{ $settings->commission_rate ?? '5.00' }}%</h4>
                        </div>
                        <div class="col-12 mb-3">
                            <small class="text-muted">Active Targets</small>
                            <h4 class="mb-0">{{ $performanceTargets->count() }}</h4>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Notification Status</small>
                            <div class="d-flex align-items-center mt-2">
                                <div class="me-3">
                                    <span class="badge badge-{{ $settings->email_notifications ?? true ? 'success' : 'secondary' }}">
                                        Email
                                    </span>
                                </div>
                                <div>
                                    <span class="badge badge-{{ $settings->sms_notifications ?? true ? 'success' : 'secondary' }}">
                                        SMS
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="tab-content" id="settingsTabsContent">
                <!-- Business Information Tab -->
                <div class="tab-pane fade show active" id="business" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-building me-2"></i>Business Information</h5>
                        </div>
                        <div class="card-body">
                            <form id="businessInfoForm" action="{{ route('agent.settings.business.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="business_name" class="form-label">Business Name *</label>
                                        <input type="text" class="form-control" id="business_name" name="business_name"
                                               value="{{ old('business_name', $settings->business_name ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="business_registration_number" class="form-label">Registration Number</label>
                                        <input type="text" class="form-control" id="business_registration_number"
                                               name="business_registration_number"
                                               value="{{ old('business_registration_number', $settings->business_registration_number ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tax_identification_number" class="form-label">Tax ID Number</label>
                                        <input type="text" class="form-control" id="tax_identification_number"
                                               name="tax_identification_number"
                                               value="{{ old('tax_identification_number', $settings->tax_identification_number ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="business_phone" class="form-label">Business Phone</label>
                                        <input type="tel" class="form-control" id="business_phone" name="business_phone"
                                               value="{{ old('business_phone', $settings->business_phone ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="business_email" class="form-label">Business Email</label>
                                        <input type="email" class="form-control" id="business_email" name="business_email"
                                               value="{{ old('business_email', $settings->business_email ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="business_website" class="form-label">Business Website</label>
                                        <input type="url" class="form-control" id="business_website" name="business_website"
                                               value="{{ old('business_website', $settings->business_website ?? '') }}">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="business_address" class="form-label">Business Address</label>
                                        <textarea class="form-control" id="business_address" name="business_address"
                                                  rows="2">{{ old('business_address', $settings->business_address ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="business_description" class="form-label">Business Description</label>
                                        <textarea class="form-control" id="business_description" name="business_description"
                                                  rows="3">{{ old('business_description', $settings->business_description ?? '') }}</textarea>
                                        <small class="text-muted">Brief description of your business operations</small>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Save Business Information
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings Tab -->

<div class="tab-pane fade" id="notifications" role="tabpanel">
    <div class="settings-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-bell me-2"></i>Notifications
                @if(isset($unreadCount) && $unreadCount > 0)
                <span class="badge badge-danger ml-2">{{ $unreadCount }} unread</span>
                @endif
            </h5>
            @if(isset($notifications) && count($notifications) > 0)
                <div>
                    <form action="{{ route('agent.settings.notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success" id="markAllRead">
                            <i class="fas fa-check-double me-1"></i>Mark All as Read
                        </button>
                    </form>
                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="clearAllNotifications">
                        <i class="fas fa-trash me-1"></i>Clear All
                    </button>
                </div>
            @endif
        </div>
        <div class="card-body">
            @if(isset($notifications) && count($notifications) > 0)
                <div class="notification-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item mb-3 p-3 border rounded {{ $notification->read ? 'bg-light' : 'bg-white border-primary' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($notification->type == 'email')
                                            <i class="fas fa-envelope text-primary me-2"></i>
                                        @elseif($notification->type == 'sms')
                                            <i class="fas fa-sms text-success me-2"></i>
                                        @elseif($notification->type == 'order')
                                            <i class="fas fa-shopping-cart text-info me-2"></i>
                                        @elseif($notification->type == 'commission')
                                            <i class="fas fa-money-bill-wave text-warning me-2"></i>
                                        @elseif($notification->type == 'target')
                                            <i class="fas fa-bullseye text-danger me-2"></i>
                                        @elseif($notification->type == 'marketplace')
                                            <i class="fas fa-store text-secondary me-2"></i>
                                        @else
                                            <i class="fas fa-bell text-muted me-2"></i>
                                        @endif
                                        <h6 class="mb-0 {{ $notification->read ? 'text-muted' : 'font-weight-bold' }}">
                                            {{ $notification->title }}
                                        </h6>
                                        @if(!$notification->read)
                                            <span class="badge badge-primary badge-pill ms-2">New</span>
                                        @endif
                                    </div>
                                    <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </small>
                                        <div>
                                            @if(!$notification->read)
                                                <form action="{{ route('agent.settings.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary mark-as-read-btn">
                                                        <i class="fas fa-check me-1"></i>Mark as Read
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('agent.settings.notifications.delete', $notification->id) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger delete-notification-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <!-- Pagination -->

@if($notifications->hasPages())
    <div class="mt-4">
        <nav aria-label="Notifications pagination">
            <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if($notifications->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i> Previous
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $notifications->previousPageUrl() }}" data-page="{{ $notifications->currentPage() - 1 }}">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $start = max(1, $notifications->currentPage() - 2);
                    $end = min($notifications->lastPage(), $start + 4);
                @endphp

                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $notifications->url(1) }}" data-page="1">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $notifications->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link pagination-link" href="{{ $notifications->url($page) }}" data-page="{{ $page }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                @if($end < $notifications->lastPage())
                    @if($end < $notifications->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $notifications->url($notifications->lastPage()) }}" data-page="{{ $notifications->lastPage() }}">
                            {{ $notifications->lastPage() }}
                        </a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if($notifications->hasMorePages())
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $notifications->nextPageUrl() }}" data-page="{{ $notifications->currentPage() + 1 }}">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            Next <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif


                <!-- Bulk Actions -->
                <div class="mt-4 pt-3 border-top">
                    <form action="{{ route('agent.settings.notifications.bulk-action') }}" method="POST" id="bulkNotificationForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <select class="form-control" name="bulk_action" id="bulkAction">
                                    <option value="">Bulk Actions</option>
                                    <option value="mark_read">Mark as Read</option>
                                    <option value="mark_unread">Mark as Unread</option>
                                    <option value="delete">Delete</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <button type="submit" class="btn btn-primary w-100" id="applyBulkAction">
                                    <i class="fas fa-check me-1"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h5>No Notifications</h5>
                    <p class="text-muted">You don't have any notifications yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
                <!-- Performance Targets Tab -->
                <div class="tab-pane fade" id="performance" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i>Performance Targets</h5>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addTargetModal">
                                <i class="fas fa-plus me-1"></i>Add Target
                            </button>
                        </div>
                        <div class="card-body">
                            @if($performanceTargets->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-bullseye fa-3x text-muted mb-3"></i>
                                    <h5>No Performance Targets</h5>
                                    <p class="text-muted">Set your first performance target to track your progress.</p>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTargetModal">
                                        <i class="fas fa-plus me-1"></i>Create Target
                                    </button>
                                </div>
                            @else
                                <div class="row">
                                    @foreach($performanceTargets as $target)
                                        @php
                                            $progressPercentage = $target->progress_percentage ?? 0;
                                            $targetColor = $progressPercentage >= 100 ? 'success' : ($progressPercentage >= 70 ? 'info' : ($progressPercentage >= 40 ? 'warning' : 'danger'));
                                            $targetUnit = $target->target_type == 'sales' || $target->target_type == 'revenue' ? 'KES' : ($target->target_type == 'completion_rate' ? '%' : '');
                                            $targetIcon = $target->target_type == 'sales' ? 'money-bill-wave' : ($target->target_type == 'orders' ? 'shopping-cart' : ($target->target_type == 'farmers' ? 'users' : ($target->target_type == 'completion_rate' ? 'check-circle' : ($target->target_type == 'revenue' ? 'chart-line' : 'bullseye'))));
                                        @endphp

                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h6 class="mb-1">{{ $target->name }}</h6>
                                                            <small class="text-muted">
                                                                <i class="far fa-calendar me-1"></i>
                                                                {{ \Carbon\Carbon::parse($target->start_date)->format('M d, Y') }} -
                                                                {{ \Carbon\Carbon::parse($target->end_date)->format('M d, Y') }}
                                                            </small>
                                                        </div>
                                                        <span class="badge badge-{{ $targetColor }}">
                                                            {{ number_format($progressPercentage, 1) }}%
                                                        </span>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <small>Progress</small>
                                                            <small>
                                                                {{ number_format($target->progress ?? 0) }} / {{ number_format($target->target_value) }}
                                                                {{ $targetUnit }}
                                                            </small>
                                                        </div>
                                                        <div class="target-progress">
                                                            <div class="target-progress-bar bg-{{ $targetColor }}"
                                                                 style="width: {{ $progressPercentage }}%"></div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-{{ $targetIcon }} me-1"></i>
                                                            {{ ucfirst($target->target_type) }} Target
                                                        </small>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                    data-toggle="modal" data-target="#editTargetModal"
                                                                    data-target-id="{{ $target->id }}"
                                                                    data-target-name="{{ $target->name }}"
                                                                    data-target-type="{{ $target->target_type }}"
                                                                    data-target-value="{{ $target->target_value }}"
                                                                    data-period="{{ $target->period }}"
                                                                    data-start-date="{{ \Carbon\Carbon::parse($target->start_date)->format('Y-m-d') }}"
                                                                    data-end-date="{{ \Carbon\Carbon::parse($target->end_date)->format('Y-m-d') }}"
                                                                    data-description="{{ $target->description ?? '' }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger delete-target"
                                                                    data-target-id="{{ $target->id }}"
                                                                    data-target-name="{{ $target->name }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Working Hours Tab -->
                <div class="tab-pane fade" id="working-hours" role="tabpanel">
                    <div class="settings-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Working Hours</h5>
                        </div>
                        <div class="card-body">
                            <form id="workingHoursForm" action="{{ route('agent.settings.working-hours.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="working_days" class="form-label">Working Days</label>
                                        <input type="text" class="form-control" id="working_days" name="working_days"
                                               value="{{ old('working_days', $settings->working_days ?? 'Monday-Friday') }}"
                                               placeholder="e.g., Monday-Friday or Mon-Fri, Sat">
                                        <small class="text-muted">Specify your working days</small>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="working_hours_start" class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="working_hours_start"
                                               name="working_hours_start"
                                               value="{{ old('working_hours_start', $settings->working_hours_start ? \Carbon\Carbon::parse($settings->working_hours_start)->format('H:i') : '08:00') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="working_hours_end" class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="working_hours_end"
                                               name="working_hours_end"
                                               value="{{ old('working_hours_end', $settings->working_hours_end ? \Carbon\Carbon::parse($settings->working_hours_end)->format('H:i') : '17:00') }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            These working hours will be shown to farmers and suppliers who want to contact you.
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Save Working Hours
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Target Modal -->
<div class="modal fade" id="addTargetModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-bullseye me-2"></i>Add Performance Target</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addTargetForm" action="{{ route('agent.settings.targets.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="target_name">Target Name *</label>
                        <input type="text" class="form-control" id="target_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="target_type">Target Type *</label>
                        <select class="form-control" id="target_type" name="target_type" required>
                            <option value="sales">Sales Target (KES)</option>
                            <option value="orders">Number of Orders</option>
                            <option value="farmers">New Farmers</option>
                            <option value="completion_rate">Order Completion Rate (%)</option>
                            <option value="revenue">Revenue Target</option>
                            <option value="custom">Custom Target</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="target_value">Target Value *</label>
                        <input type="number" class="form-control" id="target_value" name="target_value"
                               step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="period">Period *</label>
                        <select class="form-control" id="period" name="period" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Target</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Target Modal -->
<div class="modal fade" id="editTargetModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Performance Target</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editTargetForm" action="{{ route('agent.settings.targets.update') }}" method="POST">
                @csrf
                <input type="hidden" name="target_id" id="edit_target_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_target_name">Target Name *</label>
                        <input type="text" class="form-control" id="edit_target_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_target_type">Target Type *</label>
                        <select class="form-control" id="edit_target_type" name="target_type" required>
                            <option value="sales">Sales Target (KES)</option>
                            <option value="orders">Number of Orders</option>
                            <option value="farmers">New Farmers</option>
                            <option value="completion_rate">Order Completion Rate (%)</option>
                            <option value="revenue">Revenue Target</option>
                            <option value="custom">Custom Target</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_target_value">Target Value *</label>
                        <input type="number" class="form-control" id="edit_target_value" name="target_value"
                               step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_period">Period *</label>
                        <select class="form-control" id="edit_period" name="period" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_start_date">Start Date *</label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_end_date">End Date *</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Target</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Tab switching
    $('#settingsTabs .nav-link').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Set end date to 30 days from start date by default
    $('#start_date').change(function() {
        const startDate = new Date($(this).val());
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 30);
        $('#end_date').val(endDate.toISOString().split('T')[0]);
    });

    // Set default end date
    const startDate = new Date($('#start_date').val());
    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + 30);
    $('#end_date').val(endDate.toISOString().split('T')[0]);

    // Edit target modal
    $('#editTargetModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const modal = $(this);

        modal.find('#edit_target_id').val(button.data('target-id'));
        modal.find('#edit_target_name').val(button.data('target-name'));
        modal.find('#edit_target_type').val(button.data('target-type'));
        modal.find('#edit_target_value').val(button.data('target-value'));
        modal.find('#edit_period').val(button.data('period'));
        modal.find('#edit_start_date').val(button.data('start-date'));
        modal.find('#edit_end_date').val(button.data('end-date'));
        modal.find('#edit_description').val(button.data('description'));
    });

    // Delete target confirmation
    $('.delete-target').click(function() {
        const targetId = $(this).data('target-id');
        const targetName = $(this).data('target-name');
        const button = $(this);

        if (confirm(`Are you sure you want to delete the target "${targetName}"?`)) {
            // Show loading state
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i>');

            // Create a form dynamically to submit DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("agent.settings.targets.delete", ":id") }}'.replace(':id', targetId);

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add method spoofing for DELETE
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Form submissions with loading states
    $('form').submit(function() {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
    });

    // ========== AJAX PAGINATION FOR NOTIFICATIONS ==========
// AJAX Pagination for notifications
// ========== AJAX PAGINATION FOR NOTIFICATIONS ==========

$(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = $(this).data('page');

        console.log('Pagination clicked:', url, 'Page:', page);

        // Show loading indicator
        showNotificationsLoading();

        // Make AJAX request
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                ajax: true,
                partial: 'notifications',
                page: page
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                console.log('AJAX response received:', response);

                if (typeof response === 'object' && response.html) {
                    // Replace only the notifications content
                    $('#notifications .card-body').html(response.html);

                    // Update URL without page reload
                    updateUrlForNotificationsTab(page);

                    // Update notification counts
                    updateNotificationCounts();

                    console.log('Notifications pagination successful');
                } else {
                    console.error('Unexpected response format');
                    // Fallback to page reload
                    window.location.href = url + '#notifications';
                }

                hideNotificationsLoading();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                // On error, reload page with notifications hash
                window.location.href = url + '#notifications';
                hideNotificationsLoading();
            }
        });
    });

    // Show loading state for notifications
    function showNotificationsLoading() {
        const notificationList = $('.notification-list');
        if (notificationList.length) {
            notificationList.addClass('opacity-50');
        }
        $('.pagination').addClass('opacity-50');
        $('#applyBulkAction').prop('disabled', true);
    }

    // Hide loading state
    function hideNotificationsLoading() {
        $('.notification-list').removeClass('opacity-50');
        $('.pagination').removeClass('opacity-50');
        $('#applyBulkAction').prop('disabled', false);
    }

    // Update URL for notifications tab
    function updateUrlForNotificationsTab(page) {
        const baseUrl = window.location.pathname;
        const newUrl = page > 1 ? `${baseUrl}?page=${page}#notifications` : `${baseUrl}#notifications`;

        history.pushState({
            url: newUrl,
            tab: 'notifications',
            page: page
        }, '', newUrl);
    }

    // Function to activate notifications tab
    function activateNotificationsTab() {
        // Remove active class from all tabs
        $('#settingsTabs .nav-link').removeClass('active');
        $('#notifications-tab').addClass('active');

        // Hide all tab panes
        $('.tab-pane').removeClass('show active');

        // Show notifications tab pane
        $('#notifications').addClass('show active');
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (window.location.hash === '#notifications') {
            activateNotificationsTab();

            // If we need to load content, we can do it here
            if (event.state && event.state.tab === 'notifications') {
                // Extract page from URL if present
                const urlParams = new URLSearchParams(window.location.search);
                const page = urlParams.get('page') || 1;

                // Load notifications for that page
                loadNotificationsPage(page);
            }
        }
    });

    // Load specific notifications page
    function loadNotificationsPage(page) {
        const baseUrl = '{{ route("agent.settings.index") }}';
        const url = page > 1 ? `${baseUrl}?page=${page}` : baseUrl;

        showNotificationsLoading();

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                ajax: true,
                partial: 'notifications',
                page: page
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (typeof response === 'object' && response.html) {
                    $('#notifications .card-body').html(response.html);
                    updateNotificationCounts();
                }
                hideNotificationsLoading();
            },
            error: function() {
                // Fallback to full page reload
                window.location.href = url + '#notifications';
                hideNotificationsLoading();
            }
        });
    }

    // Initialize notification counts on page load
    $(document).ready(function() {
        // Check if we're on notifications tab from URL hash
        if (window.location.hash === '#notifications') {
            activateNotificationsTab();

            // Check if we have a page parameter
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            if (page && page > 1) {
                // Update the active page in pagination
                $('.pagination-link').removeClass('active');
                $(`.pagination-link[data-page="${page}"]`).addClass('active');
            }
        }

        // Update notification counts
        updateNotificationCounts();

        // When notifications tab is shown, update URL
        $('#notifications-tab').on('shown.bs.tab', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            const newUrl = page ?
                window.location.pathname + '?page=' + page + '#notifications' :
                window.location.pathname + '#notifications';
            history.replaceState(null, null, newUrl);
        });
    });

    // ========== NOTIFICATION HANDLING ==========

    // AJAX: Mark single notification as read
    $(document).on('click', '.mark-as-read-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const notificationItem = form.closest('.notification-item');

        // Show loading on button
        const button = $(this);
        const originalHtml = button.html();
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Update UI without page reload
                notificationItem.removeClass('bg-white border-primary');
                notificationItem.addClass('bg-light');
                notificationItem.find('.badge-primary').remove();
                notificationItem.find('.font-weight-bold').removeClass('font-weight-bold').addClass('text-muted');
                form.remove(); // Remove the "Mark as Read" button

                // Update unread count in tab
                updateUnreadCount(-1);

                // Show success message
                showToast('Notification marked as read', 'success');

                // Re-enable button
                button.prop('disabled', false).html(originalHtml);
            },
            error: function() {
                button.prop('disabled', false).html(originalHtml);
                showToast('Failed to mark as read. Please try again.', 'error');
            }
        });
    });

    // AJAX: Delete single notification
    $(document).on('click', '.delete-notification-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this notification?')) {
            const form = $(this).closest('form');
            const notificationItem = form.closest('.notification-item');
            const wasUnread = notificationItem.hasClass('bg-white border-primary');

            // Show loading
            const button = $(this);
            const originalHtml = button.html();
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: form.attr('action'),
                method: 'DELETE',
                data: form.serialize(),
                success: function(response) {
                    notificationItem.fadeOut(300, function() {
                        $(this).remove();

                        // Update unread count if it was unread
                        if (wasUnread) {
                            updateUnreadCount(-1);
                        }

                        // Show message if no notifications left
                        if ($('.notification-item').length === 0) {
                            showNoNotificationsMessage();
                        }
                    });
                    showToast('Notification deleted', 'success');
                },
                error: function() {
                    button.prop('disabled', false).html(originalHtml);
                    showToast('Failed to delete notification. Please try again.', 'error');
                }
            });
        }
    });

    // Mark all notifications as read (AJAX version)
    $('#markAllRead').click(function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to mark all notifications as read?')) {
            const button = $(this);
            const originalHtml = button.html();
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');

            $.ajax({
                url: '{{ route("agent.settings.notifications.mark-all-read") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Update all notifications in UI
                    $('.notification-item').each(function() {
                        $(this).removeClass('bg-white border-primary')
                               .addClass('bg-light')
                               .find('.badge-primary').remove();
                        $(this).find('.font-weight-bold').removeClass('font-weight-bold').addClass('text-muted');
                        $(this).find('.mark-as-read-btn').closest('form').remove();
                    });

                    // Update unread count
                    const unreadCount = parseInt($('#notifications-tab .badge').text()) || 0;
                    updateUnreadCount(-unreadCount);

                    button.prop('disabled', false).html(originalHtml);
                    showToast('All notifications marked as read', 'success');
                },
                error: function() {
                    button.prop('disabled', false).html(originalHtml);
                    showToast('Failed to mark all as read. Please try again.', 'error');
                }
            });
        }
    });

    // Clear all notifications
    $('#clearAllNotifications').click(function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
            const button = $(this);
            const originalHtml = button.html();
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Clearing...');

            $.ajax({
                url: '{{ route("agent.settings.notifications.clear-all") }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Fade out all notifications
                    $('.notification-list').fadeOut(300, function() {
                        $(this).html('');
                        showNoNotificationsMessage();
                    });

                    // Reset unread count
                    updateUnreadCount(-999); // Large negative number to reset

                    // Hide action buttons
                    $('.card-header .btn-group').remove();

                    button.prop('disabled', false).html(originalHtml);
                    showToast('All notifications cleared', 'success');
                },
                error: function() {
                    button.prop('disabled', false).html(originalHtml);
                    showToast('Failed to clear notifications. Please try again.', 'error');
                }
            });
        }
    });

    // Bulk notification action (AJAX version)
    $('#applyBulkAction').click(function(e) {
        e.preventDefault();
        const bulkAction = $('#bulkAction').val();

        if (!bulkAction) {
            showToast('Please select a bulk action first.', 'warning');
            return;
        }

        if (confirm(`Are you sure you want to ${bulkAction.replace('_', ' ')} the selected notifications?`)) {
            const button = $(this);
            const originalHtml = button.html();
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');

            $.ajax({
                url: $('#bulkNotificationForm').attr('action'),
                method: 'POST',
                data: $('#bulkNotificationForm').serialize(),
                success: function(response) {
                    button.prop('disabled', false).html(originalHtml);
                    showToast(response.message || 'Bulk action completed', 'success');

                    // Reload notifications after bulk action
                    setTimeout(() => {
                        loadCurrentNotificationsPage();
                    }, 1000);
                },
                error: function() {
                    button.prop('disabled', false).html(originalHtml);
                    showToast('Failed to apply bulk action. Please try again.', 'error');
                }
            });
        }
    });

    // ========== HELPER FUNCTIONS ==========

    // Load current notifications page via AJAX
    function loadCurrentNotificationsPage() {
        const currentUrl = window.location.href;
        $.ajax({
            url: currentUrl,
            method: 'GET',
            data: { ajax: true },
            success: function(response) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                const newNotificationsContent = $(doc).find('#notifications .card-body').html();
                $('#notifications .card-body').html(newNotificationsContent);
                initNotificationHandlers();
            }
        });
    }

    // Initialize notification handlers
    function initNotificationHandlers() {
        // Update notification counts
        updateNotificationCounts();

        // Re-attach any event handlers that might have been lost
        // Event delegation on $(document) handles most, but we might need specific ones
    }

    // Update notification counts via AJAX
    function updateNotificationCounts() {
        $.ajax({
            url: '{{ route("agent.settings.notifications.counts") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    // Update tab badge
                    const tabBadge = $('#notifications-tab .badge');
                    if (response.unread_count > 0) {
                        if (tabBadge.length) {
                            tabBadge.text(response.unread_count);
                        } else {
                            $('#notifications-tab').append(`<span class="badge badge-danger ms-1">${response.unread_count}</span>`);
                        }
                    } else {
                        tabBadge.remove();
                    }

                    // Update header badge
                    const headerBadge = $('.card-header .badge-danger');
                    if (response.unread_count > 0) {
                        if (headerBadge.length) {
                            headerBadge.text(`${response.unread_count} unread`);
                        }
                    } else {
                        headerBadge.remove();
                    }
                }
            },
            // Silent fail - don't show error for count updates
            error: function() {}
        });
    }

    // Update unread count badge
    function updateUnreadCount(change) {
        // Update badge in navigation tab
        const tabBadge = $('#notifications-tab .badge');
        if (tabBadge.length) {
            let currentCount = parseInt(tabBadge.text());
            if (isNaN(currentCount)) currentCount = 0;

            const newCount = Math.max(0, currentCount + change);
            if (newCount > 0) {
                tabBadge.text(newCount);
            } else {
                tabBadge.remove();
            }
        }

        // Update header badge
        const headerBadge = $('.card-header .badge-danger');
        if (headerBadge.length) {
            const badgeText = headerBadge.text();
            const match = badgeText.match(/(\d+)/);
            let currentCount = match ? parseInt(match[1]) : 0;

            const newCount = Math.max(0, currentCount + change);
            if (newCount > 0) {
                headerBadge.text(newCount + ' unread');
            } else {
                headerBadge.remove();
            }
        }

        // Also update via AJAX to get accurate count
        updateNotificationCounts();
    }

    // Show no notifications message
    function showNoNotificationsMessage() {
        const noNotificationsHTML = `
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <h5>No Notifications</h5>
                <p class="text-muted">You don't have any notifications yet.</p>
            </div>
        `;
        $('.card-body').html(noNotificationsHTML);
    }

    // Show toast message
    function showToast(message, type = 'success') {
        // Remove existing toasts
        $('.custom-toast').remove();

        const toastClass = type === 'success' ? 'bg-success' :
                          type === 'error' ? 'bg-danger' :
                          type === 'warning' ? 'bg-warning' : 'bg-info';

        const toast = $(`
            <div class="custom-toast position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="toast ${toastClass} text-white" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
                    <div class="toast-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>${message}</span>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('body').append(toast);
        $('.custom-toast .toast').toast('show');

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Initialize Bootstrap tooltips if any
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize notification counts on page load
    updateNotificationCounts();
});

// Target helper functions
function getTargetUnit(targetType) {
    switch(targetType) {
        case 'sales':
        case 'revenue':
            return 'KES';
        case 'completion_rate':
            return '%';
        default:
            return '';
    }
}

function getTargetIcon(targetType) {
    switch(targetType) {
        case 'sales':
            return 'money-bill-wave';
        case 'orders':
            return 'shopping-cart';
        case 'farmers':
            return 'users';
        case 'completion_rate':
            return 'check-circle';
        case 'revenue':
            return 'chart-line';
        default:
            return 'bullseye';
    }
}

function getTargetColor(percentage) {
    if (percentage >= 100) return 'success';
    if (percentage >= 70) return 'info';
    if (percentage >= 40) return 'warning';
    return 'danger';
}
</script>
@endsection
