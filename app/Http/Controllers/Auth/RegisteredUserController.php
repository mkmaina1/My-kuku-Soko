<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:15', 'unique:'.User::class],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        // Format phone number
        $phone = $this->formatPhoneNumber($request->phone);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Mark email as verified immediately
            // Role will be selected on next page
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect directly to role selection page
        return redirect()->route('select.role')->with('success', 'Registration successful! Please select your role.');
    }

    /**
     * Format phone number to standard format
     */
    private function formatPhoneNumber($phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // If starts with 0, replace with 254
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }

        // If starts with 254, keep as is
        if (str_starts_with($phone, '254')) {
            return $phone;
        }

        // If starts with 7 (without 254), add 254
        if (str_starts_with($phone, '7') && strlen($phone) == 9) {
            return '254' . $phone;
        }

        return $phone;
    }
}
