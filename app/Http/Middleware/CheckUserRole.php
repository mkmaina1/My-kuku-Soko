<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Check if user has a role selected
        if (!$user->role) {
            return redirect('/select-role')->with('info', 'Please select your role first.');
        }

        // Check if user has the correct role for this route
        if ($user->role !== $role) {
            return redirect('/dashboard')->with('error', 'You do not have access to this area.');
        }

        // ========== VERIFICATION CHECK FOR NON-ADMIN USERS ==========
        // Only apply verification check to regular users (not admins)
        if ($user->role !== 'admin') {

            // Check if current route is exempt from verification
            $routeName = $request->route() ? $request->route()->getName() : null;

            $exemptRoutes = [
                // Verification routes
                'verification.create',
                'verification.store',
                'verification.pending',
                'verification.rejected',
                'verification.cancel',

                // Profile routes
                'profile.edit',
                'profile.update',
                'profile.password',

                // Role selection
                'select.role',
                'role.assign',

                // Logout
                'logout',
            ];

            // Add veterinary subscription routes to exempt list
            if (str_contains($routeName ?? '', 'veterinary.subscription.')) {
                return $next($request);
            }

            // Add farmer cart/checkout routes to exempt list
            if (str_contains($routeName ?? '', 'farmer.cart.') ||
                str_contains($routeName ?? '', 'farmer.orders.checkout') ||
                str_contains($routeName ?? '', 'farmer.orders.process-checkout')) {
                return $next($request);
            }

            // Add agent marketplace routes to exempt list (if needed)
            if (str_contains($routeName ?? '', 'agent.marketplace.')) {
                return $next($request);
            }

            // Add supplier inventory routes to exempt list (if needed)
            if (str_contains($routeName ?? '', 'supplier.inventory.')) {
                return $next($request);
            }

            if ($routeName && in_array($routeName, $exemptRoutes)) {
                return $next($request);
            }

            // Check verification status
            if (!$user->is_verified) {
                switch ($user->verification_status) {
                    case 'pending':
                        return redirect()->route('verification.pending')
                            ->with('info', 'Your verification request is pending approval. You will be notified once verified.');

                    case 'rejected':
                        return redirect()->route('verification.rejected')
                            ->with('error', 'Your verification was rejected. Please submit a new request.');

                    default:
                        return redirect()->route('verification.create')
                            ->with('info', 'Please verify your account to access this area.');
                }
            }
        }
        // ============================================================

        return $next($request);
    }
}
