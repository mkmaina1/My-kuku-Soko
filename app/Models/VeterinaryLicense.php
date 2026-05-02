<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeterinaryLicense extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'document_path',
        'notes',
        'is_verified',
        'is_pending',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
        'is_pending' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the license.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the license has expired.
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if the license is expiring soon (within 30 days).
     */
    public function isExpiringSoon()
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isFuture() &&
               $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Get the badge color for the document type.
     */
    public function getTypeBadge()
    {
        $badges = [
            'veterinary_license' => 'danger',
            'practice_license' => 'warning',
            'certification' => 'info',
            'degree_certificate' => 'primary',
            'other' => 'secondary',
        ];

        return $badges[$this->document_type] ?? 'secondary';
    }
}
