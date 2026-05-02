<?php

namespace App\Http\Controllers\Veterinary;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Display a listing of poultry consultations.
     */
    public function index()
    {
        $user = Auth::user();
        $consultations = $user->poultryConsultations()->with('farmer')->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', compact('consultations', 'stats'));
    }

    /**
     * Get consultation statistics for the user.
     */
    private function getStats($user)
    {
        return [
            'total' => $user->poultryConsultations()->count(),
            'pending' => $user->poultryConsultations()->where('consultation_status', 'pending')->count(),
            'completed' => $user->poultryConsultations()->where('consultation_status', 'completed')->count(),
            'emergency' => $user->poultryConsultations()->where('priority', 'emergency')->count(),
        ];
    }

    /**
     * Show the form for creating a new poultry consultation.
     */
    public function create()
    {
        $farmers = User::where('role', 'farmer')->get();
        return view('veterinary.consultations.create', compact('farmers'));
    }

    /**
     * Store a newly created poultry consultation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'consultation_type' => 'required|in:in_person,telemedicine,emergency,follow_up',
            'poultry_type' => 'required|in:broilers,layers,kienyeji,breeding,other',
            'flock_size' => 'nullable|integer|min:1',
            'age_weeks' => 'nullable|integer|min:0',
            'priority' => 'required|in:low,normal,high,emergency',
            'symptoms' => 'required|string|min:10',
            'appointment_date' => 'nullable|date',
            'location' => 'required_if:consultation_type,in_person|nullable|string',
            'farm_name' => 'nullable|string',
        ]);

        $validated['veterinarian_id'] = Auth::id();
        $validated['consultation_status'] = 'pending';

        $consultation = Consultation::create($validated);

        return redirect()->route('veterinary.consultations.show', $consultation)
            ->with('success', 'Poultry consultation created successfully.');
    }

    /**
     * Display the specified poultry consultation.
     */
    public function show(Consultation $consultation)
    {
        if ($consultation->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }

        $consultation->load('farmer');
        return view('veterinary.consultations.show', compact('consultation'));
    }

    /**
     * Show the form for editing the specified consultation.
     */
    public function edit(Consultation $consultation)
    {
        if ($consultation->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }

        $farmers = User::where('role', 'farmer')->get();
        return view('veterinary.consultations.edit', compact('consultation', 'farmers'));
    }

    /**
     * Update the specified consultation.
     */
    public function update(Request $request, Consultation $consultation)
    {
        if ($consultation->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }

        $validated = $request->validate([
            'diagnosis' => 'required|string|min:10',
            'treatment_plan' => 'required|string|min:10',
            'medications' => 'nullable|string',
            'vaccinations' => 'nullable|string',
            'biosecurity_measures' => 'nullable|string',
            'feeding_recommendations' => 'nullable|string',
            'management_recommendations' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'consultation_status' => 'required|in:pending,in_progress,completed,cancelled',
            'prescription_issued' => 'boolean',
            'prescription_notes' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        $consultation->update($validated);

        if ($request->consultation_status === 'completed') {
            $consultation->update(['consultation_date' => now()]);
        }

        return redirect()->route('veterinary.consultations.show', $consultation)
            ->with('success', 'Consultation updated successfully.');
    }

    /**
     * Display pending poultry consultations.
     */
    public function pending()
    {
        $user = Auth::user();
        $consultations = $user->pendingPoultryConsultations()
            ->with('farmer')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', [
            'consultations' => $consultations,
            'stats' => $stats,
            'statusFilter' => 'pending',
            'title' => 'Pending Poultry Consultations'
        ]);
    }

    /**
     * Display completed poultry consultations.
     */
    public function completed()
    {
        $user = Auth::user();
        $consultations = $user->poultryConsultations()
            ->where('consultation_status', 'completed')
            ->with('farmer')
            ->orderBy('consultation_date', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', [
            'consultations' => $consultations,
            'stats' => $stats,
            'statusFilter' => 'completed',
            'title' => 'Completed Poultry Consultations'
        ]);
    }

    /**
     * Display telemedicine consultations.
     */
    public function telemedicine()
    {
        $user = Auth::user();
        $consultations = $user->poultryConsultations()
            ->where('consultation_type', 'telemedicine')
            ->with('farmer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', [
            'consultations' => $consultations,
            'stats' => $stats,
            'typeFilter' => 'telemedicine',
            'title' => 'Telemedicine Consultations'
        ]);
    }

    /**
     * Display follow-up consultations.
     */
    public function followUps()
    {
        $user = Auth::user();
        $consultations = $user->poultryConsultations()
            ->where('consultation_type', 'follow_up')
            ->with('farmer')
            ->orderBy('follow_up_date', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', [
            'consultations' => $consultations,
            'stats' => $stats,
            'typeFilter' => 'follow_up',
            'title' => 'Follow-up Consultations'
        ]);
    }

    /**
     * Display emergency consultations.
     */
    public function emergency()
    {
        $user = Auth::user();
        $consultations = $user->poultryConsultations()
            ->where('priority', 'emergency')
            ->with('farmer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', [
            'consultations' => $consultations,
            'stats' => $stats,
            'priorityFilter' => 'emergency',
            'title' => 'Emergency Poultry Cases'
        ]);
    }

    /**
     * Mark consultation as completed.
     */
    public function markComplete(Consultation $consultation)
    {
        if ($consultation->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }

        $consultation->update([
            'consultation_status' => 'completed',
            'consultation_date' => now()
        ]);

        return redirect()->back()->with('success', 'Consultation marked as completed.');
    }

    /**
     * Add prescription to consultation.
     */
    public function addPrescription(Request $request, Consultation $consultation)
    {
        if ($consultation->veterinarian_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this consultation.');
        }

        $validated = $request->validate([
            'prescription_notes' => 'required|string|min:10',
            'medications' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $consultation->update(array_merge($validated, [
            'prescription_issued' => true
        ]));

        return redirect()->back()->with('success', 'Prescription added successfully.');
    }

    /**
     * Display disease outbreak consultations.
     */
    public function diseaseOutbreak()
    {
        $user = Auth::user();
        $consultations = $user->poultryConsultations()
            ->where('priority', 'emergency')
            ->orWhere('mortality_rate', '>', 5)
            ->with('farmer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = $this->getStats($user);

        return view('veterinary.consultations.index', [
            'consultations' => $consultations,
            'stats' => $stats,
            'typeFilter' => 'disease_outbreak',
            'title' => 'Disease Outbreak Cases'
        ]);
    }
}
