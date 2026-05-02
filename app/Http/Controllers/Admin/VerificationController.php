<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Display a listing of pending verification requests.
     */
    public function pending()
    {
        $pendingUsers = User::where('verification_status', 'pending')
            ->whereIn('role', ['supplier', 'agent', 'farmer'])
            ->withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.verifications.pending', compact('pendingUsers'));
    }

    /**
     * Display a listing of verified users.
     */
    public function verified()
    {
        $verifiedUsers = User::where('verification_status', 'approved')
            ->where('is_verified', true)
            ->whereIn('role', ['supplier', 'agent', 'farmer'])
            ->with('verifier')
            ->orderBy('verified_at', 'desc')
            ->paginate(20);

        return view('admin.verifications.verified', compact('verifiedUsers'));
    }

    /**
     * Display the specified verification request.
     */
    public function show(User $user)
    {
        // Only show if user has pending verification
        if ($user->verification_status !== 'pending') {
            return redirect()->route('admin.verifications.pending')
                ->with('error', 'This user does not have a pending verification request.');
        }

        return view('admin.verifications.show', compact('user'));
    }

    /**
     * Approve a verification request.
     */
    public function approve(Request $request, User $user)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $user->update([
            'is_verified' => true,
            'verification_status' => 'approved',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'verification_notes' => $request->notes,
        ]);

        // If supplier, verify their products too
        if ($user->role === 'supplier') {
            $user->products()->update(['is_verified' => true]);
        }

        return redirect()->route('admin.verifications.pending')
            ->with('success', "{$user->name} has been verified successfully!");
    }

    /**
     * Reject a verification request.
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        $user->update([
            'is_verified' => false,
            'verification_status' => 'rejected',
            'verified_at' => null,
            'verified_by' => Auth::id(),
            'verification_notes' => $request->reason,
        ]);

        return redirect()->route('admin.verifications.pending')
            ->with('success', "Verification for {$user->name} has been rejected.");
    }

    /**
     * Revoke verification from a user.
     */
    public function revoke(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        $user->update([
            'is_verified' => false,
            'verification_status' => 'rejected',
            'verified_at' => null,
            'verified_by' => Auth::id(),
            'verification_notes' => $request->reason . ' (Revoked by admin)',
        ]);

        // If supplier, unverify their products too
        if ($user->role === 'supplier') {
            $user->products()->update(['is_verified' => false]);
        }

        return redirect()->route('admin.verifications.verified')
            ->with('success', "Verification for {$user->name} has been revoked.");
    }
}
