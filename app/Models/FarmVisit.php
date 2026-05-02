<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'veterinarian_id',
        'farmer_id',
        'visit_number',
        'visit_type',
        'priority',
        'visit_status',
        'farm_name',
        'location',
        'county',
        'sub_county',
        'ward',
        'farm_address',
        'gps_latitude',
        'gps_longitude',
        'poultry_type',
        'total_flock_size',
        'affected_flock_size',
        'age_weeks',
        'housing_type',
        'visit_purpose',
        'specific_issues',
        'scheduled_date',
        'actual_start_time',
        'actual_end_time',
        'duration_minutes',
        'distance_km',
        'transport_cost',
        'consultation_fee',
        'observations',
        'issues_found',
        'mortality_rate',
        'feed_intake',
        'water_intake',
        'egg_production',
        'feed_conversion_ratio',
        'diagnosis',
        'recommendations',
        'treatment_administered',
        'vaccinations_administered',
        'biosecurity_assessment',
        'management_advice',
        'follow_up_plan',
        'is_emergency',
        'emergency_details',
        'emergency_contact',
        'emergency_phone',
        'visit_summary',
        'photos',
        'documents',
        'report_generated',
        'report_generated_at',
        'follow_up_date',
        'follow_up_notes',
        'payment_status',
        'total_amount',
        'amount_paid',
        'balance',
        'farmer_rating',
        'farmer_feedback',
        'veterinarian_notes'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'follow_up_date' => 'datetime',
        'report_generated_at' => 'datetime',
        'photos' => 'array',
        'documents' => 'array',
        'is_emergency' => 'boolean',
        'report_generated' => 'boolean',
        'gps_latitude' => 'decimal:8',
        'gps_longitude' => 'decimal:8',
        'mortality_rate' => 'decimal:2',
        'feed_intake' => 'decimal:2',
        'water_intake' => 'decimal:2',
        'egg_production' => 'decimal:2',
        'feed_conversion_ratio' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'transport_cost' => 'decimal:2',
        'consultation_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($farmVisit) {
            if (empty($farmVisit->visit_number)) {
                $farmVisit->visit_number = 'FV-' . date('Ymd') . '-' . str_pad(FarmVisit::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the veterinarian for the farm visit.
     */
    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    /**
     * Get the farmer for the farm visit.
     */
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Scope a query to only include upcoming visits.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('visit_status', 'scheduled')
                     ->where('scheduled_date', '>=', now());
    }

    /**
     * Scope a query to only include completed visits.
     */
    public function scopeCompleted($query)
    {
        return $query->where('visit_status', 'completed');
    }

    /**
     * Scope a query to only include emergency visits.
     */
    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    /**
     * Scope a query to only include visits in progress.
     */
    public function scopeInProgress($query)
    {
        return $query->where('visit_status', 'in_progress');
    }

    /**
     * Scope a query to only include cancelled visits.
     */
    public function scopeCancelled($query)
    {
        return $query->where('visit_status', 'cancelled');
    }

    /**
     * Get the visit status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->visit_status) {
            'scheduled' => 'primary',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'rescheduled' => 'warning',
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
     * Get the visit type display name.
     */
    public function getVisitTypeNameAttribute(): string
    {
        return match($this->visit_type) {
            'routine' => 'Routine Checkup',
            'emergency' => 'Emergency Visit',
            'follow_up' => 'Follow-up Visit',
            'consultation' => 'Consultation',
            'vaccination' => 'Vaccination',
            'inspection' => 'Farm Inspection',
            default => ucfirst(str_replace('_', ' ', $this->visit_type)),
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
            'mixed' => 'Mixed Flock',
            'other' => 'Other',
            default => ucfirst($this->poultry_type),
        };
    }

    /**
     * Check if visit is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->visit_status === 'scheduled' &&
               $this->scheduled_date &&
               $this->scheduled_date->isPast();
    }

    /**
     * Calculate visit duration.
     */
    public function calculateDuration()
    {
        if ($this->actual_start_time && $this->actual_end_time) {
            return $this->actual_start_time->diffInMinutes($this->actual_end_time);
        }
        return null;
    }

    /**
     * Get formatted scheduled date.
     */
    public function getFormattedScheduledDateAttribute()
    {
        return $this->scheduled_date ? $this->scheduled_date->format('F d, Y h:i A') : 'Not scheduled';
    }

    /**
     * Check if visit requires follow-up.
     */
    public function getRequiresFollowUpAttribute(): bool
    {
        return !empty($this->follow_up_date) &&
               $this->follow_up_date->isFuture() &&
               $this->visit_status === 'completed';
    }
}
