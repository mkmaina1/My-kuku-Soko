<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserVerificationMail;
use App\Mail\AdminNotificationMail;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by verification status
        if ($request->has('verification_status') && $request->verification_status !== 'all') {
            $query->where('verification_status', $request->verification_status);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,supplier,farmer,agent,veterinary',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'business_name' => $request->business_name,
            'address' => $request->address,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'is_active' => true, // Default to active
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['products', 'orders', 'agentOrders', 'commissions']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,supplier,farmer,agent,veterinary',
            'is_active' => 'required|in:0,1',
            'verification_status' => 'nullable|in:pending,approved,rejected',
            'verification_notes' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except('password');

        // Convert string to boolean for is_active
        $data['is_active'] = (bool) $request->is_active;

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Verify a single user (simple POST method).
     */
    public function verify(Request $request, User $user)
    {
        try {
            $user->update([
                'is_verified' => true,
                'verification_status' => 'approved',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $request->notes ?? $user->verification_notes,
            ]);

            // Send verification email to user
            try {
                Mail::to($user->email)->send(new UserVerificationMail($user, 'approved', $request->notes ?? ''));
            } catch (\Exception $e) {
                \Log::error('Verification email failed: ' . $e->getMessage());
            }

            return redirect()->back()
                ->with('success', 'User verified successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while verifying the user: ' . $e->getMessage());
        }
    }

    /**
     * Unverify a single user (simple POST method).
     */
    public function unverify(Request $request, User $user)
    {
        try {
            $user->update([
                'is_verified' => false,
                'verification_status' => 'rejected',
                'verified_at' => null,
                'verified_by' => null,
                'verification_notes' => $request->notes ?? $user->verification_notes,
            ]);

            // Send rejection email to user
            try {
                Mail::to($user->email)->send(new UserVerificationMail($user, 'rejected', $request->notes ?? ''));
            } catch (\Exception $e) {
                \Log::error('Rejection email failed: ' . $e->getMessage());
            }

            return redirect()->back()
                ->with('success', 'User unverified successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while unverifying the user: ' . $e->getMessage());
        }
    }

    /**
     * Handle verification actions (verify/unverify) via AJAX.
     */
    public function updateVerification(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:verify,unverify',
                'notes' => 'nullable|string',
            ]);

            $action = $validated['action'];

            if ($action === 'verify') {
                $user->update([
                    'is_verified' => true,
                    'verification_status' => 'approved',
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                    'verification_notes' => $validated['notes'] ?? $user->verification_notes,
                ]);

                // Send verification email
                try {
                    Mail::to($user->email)->send(new UserVerificationMail($user, 'approved', $validated['notes'] ?? ''));
                } catch (\Exception $e) {
                    \Log::error('Verification email failed: ' . $e->getMessage());
                }

                $message = 'User verified successfully!';
            } else {
                $user->update([
                    'is_verified' => false,
                    'verification_status' => 'rejected',
                    'verified_at' => null,
                    'verified_by' => null,
                    'verification_notes' => $validated['notes'] ?? $user->verification_notes,
                ]);

                // Send rejection email
                try {
                    Mail::to($user->email)->send(new UserVerificationMail($user, 'rejected', $validated['notes'] ?? ''));
                } catch (\Exception $e) {
                    \Log::error('Rejection email failed: ' . $e->getMessage());
                }

                $message = 'User unverified successfully!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            \Log::error('Verification error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating verification status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send email to user.
     */
    public function sendEmail(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to($user->email)->send(new AdminNotificationMail(
                $user,
                $request->subject,
                $request->message
            ));

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Perform bulk actions on users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->users)->get();

        switch ($request->action) {
            case 'verify':
                foreach ($users as $user) {
                    $user->update([
                        'is_verified' => true,
                        'verification_status' => 'approved',
                        'verified_at' => now(),
                        'verified_by' => auth()->id(),
                    ]);
                }
                $message = 'Selected users have been verified!';
                break;

            case 'unverify':
                foreach ($users as $user) {
                    $user->update([
                        'is_verified' => false,
                        'verification_status' => 'rejected',
                        'verified_at' => null,
                    ]);
                }
                $message = 'Selected users have been unverified!';
                break;

            case 'delete':
                foreach ($users as $user) {
                    if ($user->id !== auth()->id()) {
                        $user->delete();
                    }
                }
                $message = 'Selected users have been deleted!';
                break;
        }

        return back()->with('success', $message);
    }
}
