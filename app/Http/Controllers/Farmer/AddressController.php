<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the addresses.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('farmer.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        $counties = $this->getKenyaCounties();
        return view('farmer.addresses.create', compact('counties'));
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'landmark' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();

        // If setting as default, remove default status from other addresses
        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create($request->all());

        return redirect()->route('farmer.addresses.index')
            ->with('success', 'Address added successfully!');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $counties = $this->getKenyaCounties();
        return view('farmer.addresses.edit', compact('address', 'counties'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'landmark' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        // If setting as default, remove default status from other addresses
        if ($request->is_default) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->all());

        return redirect()->route('farmer.addresses.index')
            ->with('success', 'Address updated successfully!');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // If deleting default address, set another address as default
        if ($address->is_default) {
            $newDefault = Auth::user()->addresses()
                ->where('id', '!=', $address->id)
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $address->delete();

        return redirect()->route('farmer.addresses.index')
            ->with('success', 'Address deleted successfully!');
    }

    /**
     * Set address as default.
     */
    public function setDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->setAsDefault();

        return redirect()->route('farmer.addresses.index')
            ->with('success', 'Default address updated!');
    }

    /**
     * Get Kenya counties list.
     */
    private function getKenyaCounties()
    {
        return [
            'Mombasa', 'Kwale', 'Kilifi', 'Tana River', 'Lamu',
            'Taita Taveta', 'Garissa', 'Wajir', 'Mandera', 'Marsabit',
            'Isiolo', 'Meru', 'Tharaka Nithi', 'Embu', 'Kitui',
            'Machakos', 'Makueni', 'Nyandarua', 'Nyeri', 'Kirinyaga',
            'Murang\'a', 'Kiambu', 'Turkana', 'West Pokot', 'Samburu',
            'Trans Nzoia', 'Uasin Gishu', 'Elgeyo Marakwet', 'Nandi',
            'Baringo', 'Laikipia', 'Nakuru', 'Narok', 'Kajiado',
            'Kericho', 'Bomet', 'Kakamega', 'Vihiga', 'Bungoma',
            'Busia', 'Siaya', 'Kisumu', 'Homa Bay', 'Migori',
            'Kisii', 'Nyamira', 'Nairobi'
        ];
    }
}
