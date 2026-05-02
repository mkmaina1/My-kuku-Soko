<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Show the role selection page.
     */
    public function select()
    {
        // If user already has a role, redirect to appropriate dashboard
        if (Auth::check() && Auth::user()->role) {
            return $this->redirectToRoleDashboard(Auth::user()->role);
        }

        return view('auth.select-role');
    }

    /**
     * Assign role to the authenticated user.
     */
    public function assign(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,supplier,farmer,agent,veterinary' // Updated
        ]);

        // Get authenticated user
        $user = Auth::user();

        // Update user's role
        $user->role = $request->role;
        $user->save();

        // Redirect to appropriate dashboard based on role
        return $this->redirectToRoleDashboard($request->role)
            ->with('success', 'Role selected successfully! Welcome to My-Kuku-Soko! 🐔');
    }

    /**
     * Redirect to appropriate dashboard based on role.
     */
    private function redirectToRoleDashboard($role)
    {
        return match($role) {
            'supplier' => redirect()->route('supplier.dashboard')->with('info', 'Supplier dashboard coming soon!'), // Changed
            'farmer' => redirect()->route('farmer.dashboard')->with('info', 'Farmer dashboard coming soon!'),     // Changed
            'admin' => redirect()->route('dashboard')->with('info', 'Admin dashboard coming soon!'),
            'agent' => redirect()->route('dashboard')->with('info', 'Agent dashboard coming soon!'),
            'veterinary' => redirect()->route('dashboard')->with('info', 'Veterinary dashboard coming soon!'),
            default => redirect()->route('dashboard'),
        };
    }
}
