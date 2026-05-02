<?php

namespace App\Http\Controllers\Veterinary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\VeterinaryLicense;

class VeterinarySettingsController extends Controller
{
    /**
     * Display the veterinary settings page.
     */
    public function index()
    {
        $user = Auth::user();
        $licenses = $user->veterinaryLicenses()->orderBy('created_at', 'desc')->get();

        return view('veterinary.settings.index', compact('licenses'));
    }

    /**
     * Update the professional information.
     */
    public function updateProfessionalInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'professional_title' => 'required|string|max:10',
            'specialization' => 'nullable|string|max:50',
            'license_number' => 'required|string|max:50',
            'license_expiry' => 'required|date|after:today',
            'years_of_experience' => 'required|integer|min:0|max:50',
            'consultation_fee' => 'required|numeric|min:0|max:100000',
            'professional_bio' => 'nullable|string|max:1000',
            'qualifications' => 'nullable|string|max:500',
            'areas_of_expertise' => 'nullable|string|max:500',
            'emergency_fee_multiplier' => 'required|numeric|min:1|max:3',
            'after_hours_fee_multiplier' => 'nullable|numeric|min:1|max:3',
            'weekend_fee_multiplier' => 'nullable|numeric|min:1|max:3',
        ]);

        $user->update($validated);

        // If this is the first time setting professional info, mark for approval
        if (!$user->has_professional_info) {
            $user->update(['has_professional_info' => true]);
        }

        return redirect()->route('veterinary.settings.index', '#professional-info')
            ->with('success', 'Professional information updated successfully.');
    }

    /**
     * Upload a license or certification document.
     */
    public function uploadLicense(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:50',
            'document_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'nullable|string|max:200',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        // Upload the document
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('veterinary/licenses', 'public');
            $validated['document_path'] = $path;
        }

        // Create the license record
        $license = new VeterinaryLicense($validated);
        $license->user_id = Auth::id();
        $license->save();

        return redirect()->route('veterinary.settings.index', '#licenses')
            ->with('success', 'Document uploaded successfully. It will be reviewed by our team.');
    }

    /**
     * Delete a license or certification document.
     */
    public function deleteLicense(VeterinaryLicense $license)
    {
        // Check if the license belongs to the authenticated user
        if ($license->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the file from storage
        if ($license->document_path && Storage::disk('public')->exists($license->document_path)) {
            Storage::disk('public')->delete($license->document_path);
        }

        // Delete the record
        $license->delete();

        return redirect()->route('veterinary.settings.index', '#licenses')
            ->with('success', 'Document deleted successfully.');
    }
}
