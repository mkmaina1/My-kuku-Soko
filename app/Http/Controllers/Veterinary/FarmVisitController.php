<?php

namespace App\Http\Controllers\Veterinary;

use App\Http\Controllers\Controller;
use App\Models\FarmVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmVisitController extends Controller
{
    /**
     * Display a listing of farm visits.
     */
    public function index()
    {
        $user = Auth::user();
        $farmVisits = $user->veterinarianFarmVisits()
            ->with('farmer')
            ->orderBy('scheduled_date', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.farm-visits.index', compact('farmVisits', 'stats'));
    }

    /**
     * Get farm visit statistics.
     */
    private function getStats($user)
    {
        return [
            'total' => $user->veterinarianFarmVisits()->count(),
            'upcoming' => $user->upcomingFarmVisits()->count(),
            'completed' => $user->completedFarmVisits()->count(),
            'emergency' => $user->emergencyFarmVisits()->count(),
            'in_progress' => $user->inProgressFarmVisits()->count(),
        ];
    }

    /**
     * Show the form for creating a new farm visit.
     */
    public function create()
    {
        $farmers = User::where('role', 'farmer')->get();
        return view('veterinary.farm-visits.create', compact('farmers'));
    }

    /**
     * Store a newly created farm visit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'farm_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'county' => 'nullable|string|max:100',
            'sub_county' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'farm_address' => 'nullable|string',
            'visit_type' => 'required|in:routine,emergency,follow_up,consultation,vaccination,inspection',
            'poultry_type' => 'required|in:broilers,layers,kienyeji,breeding,mixed,other',
            'total_flock_size' => 'nullable|integer|min:1',
            'visit_purpose' => 'required|string|min:10',
            'specific_issues' => 'nullable|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,normal,high,emergency',
            'is_emergency' => 'boolean',
            'emergency_details' => 'nullable|required_if:is_emergency,true|string',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
            'distance_km' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        $validated['veterinarian_id'] = Auth::id();
        $validated['visit_status'] = 'scheduled';

        // Set emergency flag based on priority
        if ($validated['priority'] === 'emergency') {
            $validated['is_emergency'] = true;
        }

        $farmVisit = FarmVisit::create($validated);

        return redirect()->route('veterinary.farm-visits.show', $farmVisit)
            ->with('success', 'Farm visit scheduled successfully.');
    }

    /**
     * Display the specified farm visit.
     */
    public function show(FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        $farmVisit->load('farmer');
        return view('veterinary.farm-visits.show', compact('farmVisit'));
    }

    /**
     * Show the form for editing the specified farm visit.
     */
    public function edit(FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        $farmers = User::where('role', 'farmer')->get();
        return view('veterinary.farm-visits.edit', compact('farmVisit', 'farmers'));
    }

    /**
     * Update the specified farm visit.
     */
    public function update(Request $request, FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        $validated = $request->validate([
            'visit_status' => 'required|in:scheduled,in_progress,completed,cancelled,rescheduled',
            'observations' => 'nullable|string|min:10',
            'issues_found' => 'nullable|string',
            'mortality_rate' => 'nullable|numeric|between:0,100',
            'feed_intake' => 'nullable|numeric|min:0',
            'water_intake' => 'nullable|numeric|min:0',
            'egg_production' => 'nullable|numeric|between:0,100',
            'feed_conversion_ratio' => 'nullable|numeric|min:0',
            'diagnosis' => 'nullable|string|min:10',
            'recommendations' => 'nullable|string|min:10',
            'treatment_administered' => 'nullable|string',
            'vaccinations_administered' => 'nullable|string',
            'biosecurity_assessment' => 'nullable|string',
            'management_advice' => 'nullable|string',
            'follow_up_plan' => 'nullable|string',
            'visit_summary' => 'nullable|string|min:10',
            'veterinarian_notes' => 'nullable|string',
            'actual_start_time' => 'nullable|date',
            'actual_end_time' => 'nullable|date|after_or_equal:actual_start_time',
            'follow_up_date' => 'nullable|date|after_or_equal:today',
            'follow_up_notes' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,waived,partial',
        ]);

        // Update visit status and timestamps
        if ($validated['visit_status'] === 'in_progress' && !$farmVisit->actual_start_time) {
            $validated['actual_start_time'] = now();
        }

        if ($validated['visit_status'] === 'completed' && !$farmVisit->actual_end_time) {
            $validated['actual_end_time'] = now();

            // Calculate duration
            if ($farmVisit->actual_start_time) {
                $validated['duration_minutes'] = $farmVisit->actual_start_time->diffInMinutes(now());
            }
        }

        $farmVisit->update($validated);

        // Update balance if payment info provided
        if (isset($validated['total_amount']) && isset($validated['amount_paid'])) {
            $farmVisit->update([
                'balance' => $validated['total_amount'] - $validated['amount_paid']
            ]);
        }

        return redirect()->route('veterinary.farm-visits.show', $farmVisit)
            ->with('success', 'Farm visit updated successfully.');
    }

    /**
     * Remove the specified farm visit.
     */
    public function destroy(FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        $farmVisit->delete();

        return redirect()->route('veterinary.farm-visits.index')
            ->with('success', 'Farm visit deleted successfully.');
    }

    /**
     * Display upcoming farm visits.
     */
    public function upcoming()
    {
        $user = Auth::user();
        $farmVisits = $user->upcomingFarmVisits()
            ->with('farmer')
            ->orderBy('scheduled_date', 'asc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.farm-visits.index', [
            'farmVisits' => $farmVisits,
            'stats' => $stats,
            'statusFilter' => 'upcoming',
            'title' => 'Upcoming Farm Visits'
        ]);
    }

    /**
     * Display completed farm visits history.
     */
    public function history()
    {
        $user = Auth::user();
        $farmVisits = $user->completedFarmVisits()
            ->with('farmer')
            ->orderBy('actual_end_time', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.farm-visits.index', [
            'farmVisits' => $farmVisits,
            'stats' => $stats,
            'statusFilter' => 'history',
            'title' => 'Farm Visit History'
        ]);
    }

    /**
     * Display emergency farm visits.
     */
    public function emergency()
    {
        $user = Auth::user();
        $farmVisits = $user->emergencyFarmVisits()
            ->with('farmer')
            ->orderBy('scheduled_date', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.farm-visits.index', [
            'farmVisits' => $farmVisits,
            'stats' => $stats,
            'typeFilter' => 'emergency',
            'title' => 'Emergency Farm Visits'
        ]);
    }

    /**
     * Display farm visit reports.
     */
    public function reports()
    {
        $user = Auth::user();
        $farmVisits = $user->completedFarmVisits()
            ->where('report_generated', true)
            ->with('farmer')
            ->orderBy('report_generated_at', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.farm-visits.index', [
            'farmVisits' => $farmVisits,
            'stats' => $stats,
            'typeFilter' => 'reports',
            'title' => 'Farm Visit Reports'
        ]);
    }

    /**
     * Mark farm visit as completed.
     */
    public function markComplete(FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        $farmVisit->update([
            'visit_status' => 'completed',
            'actual_end_time' => now(),
            'duration_minutes' => $farmVisit->actual_start_time ? $farmVisit->actual_start_time->diffInMinutes(now()) : null
        ]);

        return redirect()->back()->with('success', 'Farm visit marked as completed.');
    }

    /**
     * Mark farm visit as emergency.
     */
    public function markEmergency(Request $request, FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        $validated = $request->validate([
            'emergency_details' => 'required|string|min:10',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
        ]);

        $farmVisit->update(array_merge($validated, [
            'is_emergency' => true,
            'priority' => 'emergency'
        ]));

        return redirect()->back()->with('success', 'Farm visit marked as emergency.');
    }

    /**
     * Generate farm visit report.
     */
    public function generateReport(FarmVisit $farmVisit)
    {
        if ($farmVisit->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this farm visit.');
        }

        // Generate report logic here
        $farmVisit->update([
            'report_generated' => true,
            'report_generated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Farm visit report generated successfully.');
    }
}
