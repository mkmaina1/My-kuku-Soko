<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farmer_id',
        'veterinarian_id',
        'consultation_number',
        'consultation_type',
        'poultry_type',
        'flock_size',
        'age_weeks',
        'consultation_status',
        'priority',
        'symptoms',
        'observations',
        'mortality_rate',
        'feed_intake',
        'water_intake',
        'diagnosis',
        'differential_diagnosis',
        'treatment_plan',
        'medications',
        'vaccinations',
        'biosecurity_measures',
        'feeding_recommendations',
        'management_recommendations',
        'follow_up_instructions',
        'appointment_date',
        'consultation_date',
        'follow_up_date',
        'location',
        'farm_name',
        'attachments',
        'prescription_issued',
        'prescription_notes',
        'consultation_fee',
        'payment_status',
        'rating',
        'farmer_feedback',
        'veterinarian_notes'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'consultation_date' => 'datetime',
        'follow_up_date' => 'datetime',
        'attachments' => 'array',
        'prescription_issued' => 'boolean',
        'consultation_fee' => 'decimal:2'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($consultation) {
            if (empty($consultation->consultation_number)) {
                $consultation->consultation_number = 'CONS-' . date('Ymd') . '-' . str_pad(Consultation::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the farmer who requested the consultation.
     */
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Get the veterinarian handling the consultation.
     */
    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    /**
     * Scope a query to only include pending consultations.
     */
    public function scopePending($query)
    {
        return $query->where('consultation_status', 'pending');
    }

    /**
     * Scope a query to only include completed consultations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('consultation_status', 'completed');
    }

    /**
     * Scope a query to only include telemedicine consultations.
     */
    public function scopeTelemedicine($query)
    {
        return $query->where('consultation_type', 'telemedicine');
    }

    /**
     * Scope a query to only include follow-up consultations.
     */
    public function scopeFollowUps($query)
    {
        return $query->where('consultation_type', 'follow_up');
    }

    /**
     * Scope a query to only include emergency consultations.
     */
    public function scopeEmergency($query)
    {
        return $query->where('priority', 'emergency');
    }

    /**
     * Get the consultation status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->consultation_status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the priority badge color.
     */
    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low' => 'secondary',
            'normal' => 'primary',
            'high' => 'warning',
            'emergency' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the poultry type display name.
     */
    public function getPoultryTypeNameAttribute(): string
    {
        return match($this->poultry_type) {
            'broilers' => 'Broilers',
            'layers' => 'Layers',
            'kienyeji' => 'Kienyeji',
            'breeding' => 'Breeding Stock',
            'other' => 'Other',
            default => ucfirst($this->poultry_type),
        };
    }

    /**
     * Check if consultation is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->appointment_date && $this->consultation_status === 'pending') {
            return $this->appointment_date->isPast();
        }
        return false;
    }

    /**
     * Get consultation duration in minutes.
     */
    public function getDurationAttribute(): ?int
    {
        if ($this->consultation_date && $this->follow_up_date) {
            return $this->consultation_date->diffInMinutes($this->follow_up_date);
        }
        return null;
    }
    
}
