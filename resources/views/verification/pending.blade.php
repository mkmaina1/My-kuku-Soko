@extends('layouts.app')

@section('title', 'Verification Pending')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-clock mr-2"></i>Verification Pending
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-shield-alt fa-5x text-warning mb-4"></i>
                    <h3 class="mb-3">Your verification request is pending</h3>
                    <p class="lead text-muted mb-4">
                        We've received your verification documents and they're being reviewed by our team.
                        This usually takes 24-48 hours. You'll be notified once your account is verified.
                    </p>

                    <div class="alert alert-info text-left">
                        <h6><i class="fas fa-info-circle mr-2"></i>What happens next?</h6>
                        <ul class="mb-0">
                            <li>Our team will review your documents</li>
                            <li>You'll receive a notification once verified</li>
                            <li>After verification, you'll have full access to all features</li>
                        </ul>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary mx-2">
                            <i class="fas fa-user mr-2"></i>Edit Profile
                        </a>
                        @if(auth()->user()->verificationRequest)
                            <form action="{{ route('verification.cancel', auth()->user()->verificationRequest->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mx-2" onclick="return confirm('Are you sure? This will cancel your verification request.')">
                                    <i class="fas fa-times mr-2"></i>Cancel Request
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
