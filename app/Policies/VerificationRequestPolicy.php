<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VerificationRequest;

class VerificationRequestPolicy
{
    public function viewAny(User $user)
    {
        return $user->role == 'admin';
    }

    public function view(User $user, VerificationRequest $verificationRequest)
    {
        return $user->role == 'admin' || $user->id == $verificationRequest->user_id;
    }

    public function create(User $user)
    {
        return !in_array($user->role, ['admin']) &&
               $user->canApplyVerification();
    }

    public function update(User $user, VerificationRequest $verificationRequest)
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, VerificationRequest $verificationRequest)
    {
        return $user->id == $verificationRequest->user_id &&
               $verificationRequest->status == 'pending';
    }
}
