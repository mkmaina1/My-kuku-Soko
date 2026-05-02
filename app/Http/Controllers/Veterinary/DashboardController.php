<?php

namespace App\Http\Controllers\Veterinary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VeterinarySubscription;

class DashboardController extends Controller
{
    /**
     * Main dashboard entry point - redirects based on subscription
     */
    public function index()
{
    $user = Auth::user();

    // DIRECT DATABASE CHECK - ignore user record
    $activeSub = VeterinarySubscription::with('plan')
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->latest()
        ->first();

    // If active subscription exists in database
    if ($activeSub && $activeSub->plan) {
        // FORCE UPDATE user record to match database
        $user->has_active_subscription = true;
        $user->subscription_plan = $activeSub->plan->slug;
        $user->subscription_expires_at = $activeSub->expires_at ?? now()->addMonth();
        $user->subscription_features = $activeSub->plan->features;
        $user->save();

        // Refresh user
        $user = $user->fresh();

        // Show appropriate dashboard
        if ($activeSub->plan->slug === 'pro') {
            return $this->pro($user);
        } else {
            return $this->basic($user);
        }
    }

    // No active subscription found
    return redirect()->route('veterinary.subscription.plans')
        ->with('info', 'Please choose a subscription plan to access your dashboard.');
}

    /**
     * Basic plan dashboard
     */
  /**
 * Basic plan dashboard
 */
public function basic($user)
{
    $currentMonth = now()->month;

    // Get today's appointments - using veterinarian_id instead of user_id
    $todaysAppointments = $user->consultations()
        ->with('farmer') // Assuming you have a farmer relationship
        ->whereDate('appointment_date', today())
        ->orderBy('appointment_date')
        ->get()
        ->map(function ($consultation) {
            return [
                'time' => $consultation->appointment_date ? $consultation->appointment_date->format('h:i A') : 'N/A',
                'farm_name' => $consultation->farm_name ?? ($consultation->farmer->name ?? 'Unknown Farm'),
                'animal_type' => $consultation->poultry_type ?? 'N/A',
                'service' => $consultation->consultation_type ?? 'Consultation',
                'status' => $consultation->consultation_status,
                'is_emergency' => $consultation->priority === 'emergency',
            ];
        })->toArray();

    // Get recent patients
    $recentPatients = $user->consultations()
        ->with('farmer')
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($consultation) {
            return [
                'farm_name' => $consultation->farm_name ?? ($consultation->farmer->name ?? 'Unknown Farm'),
                'animal_type' => $consultation->poultry_type ?? 'poultry',
                'animal_count' => $consultation->flock_size ?? 0,
                'diagnosis' => $consultation->diagnosis ?? 'Check-up',
                'last_visit' => $consultation->appointment_date ? $consultation->appointment_date->format('M d, Y') : 'N/A',
                'follow_up' => $consultation->follow_up_date ? true : false,
            ];
        })->toArray();

    // Calculate usage stats
    $consultationsUsed = $user->consultations()
        ->whereMonth('created_at', $currentMonth)
        ->count();

    $farmVisitsUsed = $user->consultations() // Assuming farm visits are consultations with 'in_person' type
        ->where('consultation_type', 'in_person')
        ->whereMonth('created_at', $currentMonth)
        ->count();

    $stats = [
        'appointments_today' => count($todaysAppointments),
        'animals_treated' => $user->consultations()->count(),
        'pending_consultations' => $user->consultations()->where('consultation_status', 'pending')->count(),
        'emergency_cases' => $user->consultations()->where('priority', 'emergency')->count(),
        'completed_today' => $user->consultations()->whereDate('updated_at', today())->where('consultation_status', 'completed')->count(),
        'poultry_treated' => $user->consultations()->whereIn('poultry_type', ['broilers', 'layers', 'kienyeji'])->count(),
        'livestock_treated' => 0, // You might need a separate table for livestock
        'avg_response_time' => '15min',
        'vaccination_rate' => '85%',
        'prevention_rate' => '92%',
        'availability_status' => 'Available',

        // Usage limits for basic plan
        'consultations_used' => $consultationsUsed,
        'consultations_limit' => 50,
        'farm_visits_used' => $farmVisitsUsed,
        'farm_visits_limit' => 10,
        'days_remaining' => $user->subscription_expires_at ? now()->diffInDays($user->subscription_expires_at) : 30,
    ];

    $data = [
        'title' => 'Veterinary Dashboard - Basic Plan',
        'user' => $user,
        'stats' => $stats,
        'todays_appointments' => $todaysAppointments,
        'recent_patients' => $recentPatients,
    ];

    return view('veterinary.dashboard.basic', $data);
}

/**
 * Pro plan dashboard
 */
public function pro($user)
{
    $currentMonth = now()->month;

    // Get today's appointments - using veterinarian_id
    $todaysAppointments = $user->consultations()
        ->with('farmer')
        ->whereDate('appointment_date', today())
        ->orderBy('appointment_date')
        ->get()
        ->map(function ($consultation) {
            return [
                'time' => $consultation->appointment_date ? $consultation->appointment_date->format('h:i A') : 'N/A',
                'farm_name' => $consultation->farm_name ?? ($consultation->farmer->name ?? 'Unknown Farm'),
                'animal_type' => $consultation->poultry_type ?? 'N/A',
                'service' => $consultation->consultation_type ?? 'Consultation',
                'status' => $consultation->consultation_status,
                'is_emergency' => $consultation->priority === 'emergency',
            ];
        })->toArray();

    // Get recent patients
    $recentPatients = $user->consultations()
        ->with('farmer')
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($consultation) {
            return [
                'farm_name' => $consultation->farm_name ?? ($consultation->farmer->name ?? 'Unknown Farm'),
                'animal_type' => $consultation->poultry_type ?? 'poultry',
                'animal_count' => $consultation->flock_size ?? 0,
                'diagnosis' => $consultation->diagnosis ?? 'Check-up',
                'last_visit' => $consultation->appointment_date ? $consultation->appointment_date->format('M d, Y') : 'N/A',
                'follow_up' => $consultation->follow_up_date ? true : false,
            ];
        })->toArray();

    // Pro plan stats (unlimited)
    $stats = [
        'appointments_today' => count($todaysAppointments),
        'animals_treated' => $user->consultations()->count(),
        'pending_consultations' => $user->consultations()->where('consultation_status', 'pending')->count(),
        'emergency_cases' => $user->consultations()->where('priority', 'emergency')->count(),
        'completed_today' => $user->consultations()->whereDate('updated_at', today())->where('consultation_status', 'completed')->count(),
        'poultry_treated' => $user->consultations()->whereIn('poultry_type', ['broilers', 'layers', 'kienyeji'])->count(),
        'livestock_treated' => 0,
        'avg_response_time' => '12min',
        'vaccination_rate' => '94%',
        'prevention_rate' => '96%',
        'availability_status' => 'Available',

        // Advanced stats for pro
        'telemedicine_sessions' => $user->consultations()->where('consultation_type', 'telemedicine')->count(),
        'farm_visits_total' => $user->consultations()->where('consultation_type', 'in_person')->count(),
        'farm_visits_month' => $user->consultations()->where('consultation_type', 'in_person')->whereMonth('created_at', $currentMonth)->count(),
        'consultations_total' => $user->consultations()->count(),
        'consultations_month' => $consultationsUsed = $user->consultations()->whereMonth('created_at', $currentMonth)->count(),
        'days_remaining' => $user->subscription_expires_at ? now()->diffInDays($user->subscription_expires_at) : 30,
    ];

    // Chart data for pro
    $chartData = [
        'labels' => [],
        'data' => []
    ];

    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $chartData['labels'][] = $date->format('M Y');
        $chartData['data'][] = $user->consultations()
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
    }

    $data = [
        'title' => 'Veterinary Dashboard - Pro Plan',
        'user' => $user,
        'stats' => $stats,
        'todays_appointments' => $todaysAppointments,
        'recent_patients' => $recentPatients,
        'chartData' => $chartData,
    ];

    return view('veterinary.dashboard.pro', $data);
}
}
