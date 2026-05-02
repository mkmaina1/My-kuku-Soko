<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'contact_name',
        'phone',
        'street',
        'city',
        'county',
        'postal_code',
        'landmark',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full formatted address.
     */
    public function getFullAddressAttribute()
    {
        $parts = [
            $this->street,
            $this->city,
            $this->county . ' County',
            $this->postal_code,
            'Kenya'
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Get the contact information.
     */
    public function getContactInfoAttribute()
    {
        $contact = $this->contact_name ?: $this->user->name;
        return $contact . ' | ' . $this->phone;
    }

    /**
     * Scope a query to only include default addresses.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Set this address as default for the user.
     */
    public function setAsDefault()
    {
        // Remove default status from other addresses
        $this->user->addresses()->update(['is_default' => false]);

        // Set this address as default
        $this->update(['is_default' => true]);

        return $this;
    }
}
