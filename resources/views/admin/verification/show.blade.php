@extends('layouts.app')

@section('title', 'Verification Request Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Verification Request #{{ $verificationRequest->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.verification.index') }}">Verifications</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">User Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $verificationRequest->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $verificationRequest->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $verificationRequest->user->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td>
                                    <span class="badge badge-{{ $verificationRequest->user->role_badge_color }}">
                                        {{ $verificationRequest->user->getRoleName() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Current Status:</th>
                                <td>
                                    @if($verificationRequest->user->is_verified)
                                        <span class="badge badge-success">Verified</span>
                                    @else
                                        <span class="badge badge-warning">Not Verified</span>
                                    @endif
                                    ({{ $verificationRequest->user->verification_status ?? 'none' }})
                                </td>
                            </tr>
                            <tr>
                                <th>Member Since:</th>
                                <td>{{ $verificationRequest->user->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">Document Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Document Type:</th>
                                <td>{{ str_replace('_', ' ', ucfirst($verificationRequest->document_type)) }}</td>
                            </tr>
                            <tr>
                                <th>Submitted On:</th>
                                <td>{{ $verificationRequest->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($verificationRequest->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($verificationRequest->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                            @if($verificationRequest->additional_info)
                            <tr>
                                <th>Additional Info:</th>
                                <td>{{ $verificationRequest->additional_info }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">Document Front</h3>
                    </div>
                    <div class="card-body text-center">
                        @if($verificationRequest->document_front)
                            {{-- FIXED: Use asset() helper for both image and link --}}
                            <img src="{{ asset('storage/' . $verificationRequest->document_front) }}"
                                 class="img-fluid img-thumbnail"
                                 style="max-height: 300px;"
                                 alt="Document Front"
                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $verificationRequest->document_front) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt mr-1"></i> View Full Size
                                </a>
                            </div>
                        @else
                            <p class="text-muted">No document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="card-title">Document Back</h3>
                    </div>
                    <div class="card-body text-center">
                        @if($verificationRequest->document_back)
                            {{-- FIXED: Use asset() helper for both image and link --}}
                            <img src="{{ asset('storage/' . $verificationRequest->document_back) }}"
                                 class="img-fluid img-thumbnail"
                                 style="max-height: 300px;"
                                 alt="Document Back"
                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $verificationRequest->document_back) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt mr-1"></i> View Full Size
                                </a>
                            </div>
                        @else
                            <p class="text-muted">No document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($verificationRequest->status == 'pending')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">Review Request</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('admin.verification.approve', $verificationRequest->id) }}"
                                      method="POST"
                                      id="approveForm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="approve_notes">Notes (Optional)</label>
                                        <textarea name="admin_notes" id="approve_notes" rows="3"
                                                  class="form-control"
                                                  placeholder="Add any notes about this approval..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block"
                                            onclick="return confirm('Approve this verification request?')">
                                        <i class="fas fa-check-circle mr-2"></i> Approve Request
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('admin.verification.reject', $verificationRequest->id) }}"
                                      method="POST"
                                      id="rejectForm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="reject_notes">Rejection Reason <span class="text-danger">*</span></label>
                                        <textarea name="admin_notes" id="reject_notes" rows="3"
                                                  class="form-control"
                                                  placeholder="Explain why this request is being rejected..."
                                                  required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block"
                                            onclick="return confirm('Reject this verification request? This action cannot be undone.')">
                                        <i class="fas fa-times-circle mr-2"></i> Reject Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <a href="{{ route('admin.verification.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
