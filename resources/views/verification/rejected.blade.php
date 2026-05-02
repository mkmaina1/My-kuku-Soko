@extends('layouts.app')

@section('title', 'Verification Rejected')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-circle mr-2"></i>Verification Rejected
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-times-circle fa-5x text-danger mb-4"></i>
                    <h3 class="mb-3">Your verification was not approved</h3>

                    <div class="alert alert-warning text-left">
                        <h6><i class="fas fa-info-circle mr-2"></i>Reason for rejection:</h6>
                        <p class="mb-0">{{ auth()->user()->verificationRequest?->admin_notes ?? 'No specific reason provided.' }}</p>
                    </div>

                    <p class="text-muted mb-4">
                        Please submit a new verification request with correct documents.
                    </p>

                    <hr class="my-4">

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('verification.create') }}" class="btn btn-primary mx-2">
                            <i class="fas fa-redo mr-2"></i>Submit New Request
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-secondary mx-2">
                            <i class="fas fa-user mr-2"></i>Back to Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
