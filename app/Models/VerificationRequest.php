<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_front',
        'document_back',
        'additional_info',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['color' => 'warning', 'icon' => 'fa-clock'],
            'approved' => ['color' => 'success', 'icon' => 'fa-check-circle'],
            'rejected' => ['color' => 'danger', 'icon' => 'fa-times-circle'],
        ];

        return $badges[$this->status] ?? ['color' => 'secondary', 'icon' => 'fa-question'];
    }

    public function approve($reviewerId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'admin_notes' => $notes,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now()
        ]);

        // Update user's verification status
        $this->user->update([
            'is_verified' => true,
            'verification_status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $reviewerId
        ]);

        return $this;
    }

    public function reject($reviewerId, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now()
        ]);

        // Update user's verification status
        $this->user->update([
            'is_verified' => false,
            'verification_status' => 'rejected'
        ]);

        return $this;
    }
}
