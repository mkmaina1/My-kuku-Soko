<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this

class VerificationController extends Controller
{
    use AuthorizesRequests; // Add this

    /**
     * Show the verification application form.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->canApplyVerification()) {
            return redirect()->route('profile.edit')
                ->with('error', 'You cannot apply for verification at this time.');
        }

        return view('verification.apply', compact('user'));
    }

    /**
     * Store a verification request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user can apply
        if (!$user->canApplyVerification()) {
            return back()->with('error', 'You cannot submit a new verification request at this time.');
        }

        $validated = $request->validate([
            'document_type' => 'required|in:id_card,driving_license,business_registration,other',
            'document_front' => 'required|image|max:5120',
            'document_back' => 'nullable|image|max:5120',
            'additional_info' => 'nullable|string|max:1000',
            'terms' => 'required|accepted',
        ]);

        try {
            // Upload documents
            $frontPath = $request->file('document_front')->store('verification/documents', 'public');
            $backPath = null;

            if ($request->hasFile('document_back')) {
                $backPath = $request->file('document_back')->store('verification/documents', 'public');
            }

            // Create verification request
            $verificationRequest = VerificationRequest::create([
                'user_id' => $user->id,
                'document_type' => $validated['document_type'],
                'document_front' => $frontPath,
                'document_back' => $backPath,
                'additional_info' => $validated['additional_info'],
                'status' => 'pending'
            ]);

            // Update user's verification status
            $user->update([
                'verification_status' => 'pending',
                'is_verified' => false
            ]);

            // NOTIFY ALL ADMINS - Using custom notifications
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                // Use your custom Notification model
                Notification::create([
                    'user_id' => $admin->id,
                    'user_type' => $admin->role,
                    'type' => 'verification_request',
                    'title' => 'New Verification Request',
                    'message' => $user->name . ' (' . $user->getRoleName() . ') has submitted a verification request.',
                    'read' => false,
                    'data' => [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_role' => $user->role,
                        'verification_id' => $verificationRequest->id,
                        'document_type' => $verificationRequest->document_type,
                        'created_at' => now()->toDateTimeString(),
                    ],
                    'link' => '/admin/verifications/' . $verificationRequest->id,
                    'icon' => 'fas fa-shield-alt',
                    'color' => 'warning',
                    'created_by' => $user->id,
                ]);
            }

            return redirect()->route('profile.edit')
                ->with('success', 'Verification request submitted successfully! We will review it within 24-48 hours.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit verification request: ' . $e->getMessage());
        }
    }

    /**
     * Admin: List all verification requests.
     */
    public function index(Request $request)
    {
        // Skip authorization for now, or create proper policies
        // $this->authorize('viewAny', VerificationRequest::class);

        $status = $request->get('status', 'pending');

        $requests = VerificationRequest::with(['user'])
            ->when($status != 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.verification.index', compact('requests', 'status'));
    }

    /**
     * Admin: Show verification request details.
     */
    public function show(VerificationRequest $verificationRequest)
    {
        // Skip authorization for now, or create proper policies
        // $this->authorize('view', $verificationRequest);

        return view('admin.verification.show', compact('verificationRequest'));
    }

    /**
     * Cancel a verification request.
     */
    public function destroy($id)
    {
        $verificationRequest = VerificationRequest::findOrFail($id);

        // Check if user owns this request
        if ($verificationRequest->user_id != Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Check if request is still pending
        if ($verificationRequest->status != 'pending') {
            return back()->with('error', 'Cannot cancel a request that has already been processed.');
        }

        try {
            // Delete document files
            Storage::disk('public')->delete([
                $verificationRequest->document_front,
                $verificationRequest->document_back
            ]);

            $verificationRequest->delete();

            // Update user's verification status
            $verificationRequest->user->update([
                'verification_status' => null
            ]);

            return redirect()->route('profile.edit')
                ->with('success', 'Verification request cancelled successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel verification request.');
        }
    }

/**
 * Admin: Approve verification request.
 */
public function approve(Request $request, VerificationRequest $verificationRequest)
{
    $request->validate([
        'admin_notes' => 'nullable|string|max:500'
    ]);

    try {
        // Update verification request - DO THIS FIRST
        $verificationRequest->status = 'approved';
        $verificationRequest->admin_notes = $request->admin_notes;
        $verificationRequest->reviewed_by = Auth::id();
        $verificationRequest->reviewed_at = now();
        $verificationRequest->save();

        // Update user
        $user = $verificationRequest->user;
        $user->is_verified = true;
        $user->verification_status = 'approved';
        $user->verified_at = now();
        $user->verified_by = Auth::id();
        $user->save();

        // Redirect back to index with success message
        return redirect()->route('admin.verification.index')
            ->with('success', 'Verification request approved successfully!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to approve verification: ' . $e->getMessage());
    }
}

/**
 * Admin: Reject verification request.
 */
public function reject(Request $request, VerificationRequest $verificationRequest)
{
    $request->validate([
        'admin_notes' => 'required|string|max:500'
    ]);

    try {
        // Update verification request - DO THIS FIRST
        $verificationRequest->status = 'rejected';
        $verificationRequest->admin_notes = $request->admin_notes;
        $verificationRequest->reviewed_by = Auth::id();
        $verificationRequest->reviewed_at = now();
        $verificationRequest->save();

        // Update user
        $user = $verificationRequest->user;
        $user->is_verified = false;
        $user->verification_status = 'rejected';
        $user->save();

        // Redirect back to index with success message
        return redirect()->route('admin.verification.index')
            ->with('success', 'Verification request rejected.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to reject verification: ' . $e->getMessage());
    }
}

    /**
     * Get verification statistics.
     */
    public function statistics()
    {
        // Skip authorization for now, or create proper policies
        // $this->authorize('viewAny', VerificationRequest::class);

        $stats = [
            'pending' => VerificationRequest::where('status', 'pending')->count(),
            'approved' => VerificationRequest::where('status', 'approved')->count(),
            'rejected' => VerificationRequest::where('status', 'rejected')->count(),
            'today' => VerificationRequest::whereDate('created_at', today())->count(),
            'week' => VerificationRequest::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'month' => VerificationRequest::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }
}
