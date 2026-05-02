<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPPLIER = 'supplier';
    const ROLE_FARMER = 'farmer';
    const ROLE_AGENT = 'agent';
    const ROLE_VETERINARY = 'veterinary';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
        'verification_status',
        'is_verified',
        'verified_at',
        'verified_by',
        'last_login_at',
        'has_active_subscription',
        'subscription_plan',
        'subscription_expires_at',
        'subscription_features',
        'has_professional_info',
        'license_number',
        'license_expiry',
        'years_of_experience',
        'consultation_fee',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'has_active_subscription' => 'boolean',
            'subscription_expires_at' => 'datetime',
            'subscription_features' => 'array',
            'has_professional_info' => 'boolean',
            'license_expiry' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Automatically mark email as verified when creating a new user
        static::creating(function ($user) {
            if (empty($user->email_verified_at)) {
                $user->email_verified_at = now();
            }
        });
    }

    // =================== NOTIFICATION METHODS ===================

    /**
     * Override Laravel's notifications() method for custom table
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Override unreadNotifications() for custom table
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('read', false);
    }

    /**
     * Override readNotifications() for custom table
     */
    public function readNotifications()
    {
        return $this->notifications()->where('read', true);
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    // =================== RELATIONSHIPS ===================

    /**
     * Get the verification requests for the user.
     */
    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the latest verification request for the user.
     */
    public function latestVerificationRequest()
    {
        return $this->hasOne(VerificationRequest::class)->latest();
    }

    /**
     * Get the verification request (alias for latestVerificationRequest).
     */
    public function verificationRequest()
    {
        return $this->hasOne(VerificationRequest::class)->latest();
    }

    /**
     * Get the admin who verified this user.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the agent orders for the user.
     */
    public function agentOrders()
    {
        return $this->hasMany(Order::class, 'agent_id');
    }

    /**
     * Get the commissions for the user.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'agent_id');
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the products listed by this user (if supplier).
     */
    public function products()
    {
        return $this->hasMany(Marketplace::class, 'supplier_id');
    }

    /**
     * Get supplier products.
     */
    public function supplierProducts()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }

    /**
     * Get supplier categories.
     */
    public function supplierCategories()
    {
        return $this->hasMany(Category::class, 'supplier_id');
    }

    /**
     * Get the veterinary subscriptions for this user.
     */
    public function veterinarySubscriptions()
    {
        return $this->hasMany(VeterinarySubscription::class);
    }

    /**
     * Get the active subscription for this user.
     */
    public function activeSubscription()
    {
        return $this->hasOne(VeterinarySubscription::class)->where('status', 'active')->latest();
    }

    /**
     * Get the consultations where this user is the veterinarian.
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'veterinarian_id');
    }

    /**
     * Get pending poultry consultations for veterinarian.
     */
    public function pendingPoultryConsultations()
    {
        return $this->hasMany(Consultation::class, 'veterinarian_id')
                    ->where('consultation_status', 'pending')
                    ->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get all poultry consultations for veterinarian.
     */
    public function poultryConsultations()
    {
        return $this->hasMany(Consultation::class, 'veterinarian_id')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get emergency poultry consultations.
     */
    public function emergencyPoultryConsultations()
    {
        return $this->hasMany(Consultation::class, 'veterinarian_id')
                    ->where('priority', 'emergency')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get farm visits for this veterinarian.
     */
    public function farmVisits()
    {
        return $this->hasMany(FarmVisit::class, 'veterinarian_id');
    }

    /**
     * Get farm visits where user is the farmer.
     */
    public function farmerFarmVisits()
    {
        return $this->hasMany(FarmVisit::class, 'farmer_id')
                    ->orderBy('scheduled_date', 'desc');
    }

    /**
     * Get upcoming farm visits for veterinarian.
     */
    public function upcomingFarmVisits()
    {
        return $this->farmVisits()
                    ->where('visit_status', 'scheduled')
                    ->where('scheduled_date', '>=', now())
                    ->orderBy('scheduled_date', 'asc');
    }

    /**
     * Get completed farm visits for veterinarian.
     */
    public function completedFarmVisits()
    {
        return $this->farmVisits()
                    ->where('visit_status', 'completed')
                    ->orderBy('actual_end_time', 'desc');
    }

    /**
     * Get emergency farm visits for veterinarian.
     */
    public function emergencyFarmVisits()
    {
        return $this->farmVisits()
                    ->where('is_emergency', true)
                    ->orderBy('scheduled_date', 'desc');
    }

    /**
     * Get farm visits in progress for veterinarian.
     */
    public function inProgressFarmVisits()
    {
        return $this->farmVisits()
                    ->where('visit_status', 'in_progress')
                    ->orderBy('actual_start_time', 'desc');
    }

    /**
     * Get the veterinary licenses for the user.
     */
    public function veterinaryLicenses()
    {
        return $this->hasMany(VeterinaryLicense::class);
    }

    /**
     * Get the marketplace products for the user (if they are supplier/agent).
     */
    public function marketplaceProducts()
    {
        return $this->hasMany(Marketplace::class, 'supplier_id');
    }

    /**
     * Get M-Pesa payments for this user.
     */
    public function mpesaPayments()
    {
        return $this->hasMany(MpesaPayment::class);
    }

    /**
     * Get transport mortality cases for this user (if agent).
     */
    public function transportMortalityCases()
    {
        return $this->hasMany(TransportMortality::class, 'agent_id');
    }

    /**
     * Get mortality reports for this user.
     */
    public function mortalityReports()
    {
        return $this->hasMany(MortalityReport::class, 'user_id');
    }

    /**
     * Get assigned mortality reports for this user.
     */
    public function assignedMortalityReports()
    {
        return $this->hasMany(MortalityReport::class, 'assigned_to');
    }

    // =================== VERIFICATION METHODS ===================

    /**
     * Check if user is verified.
     */
    public function isVerified(): bool
    {
        return $this->is_verified === true && $this->verification_status === 'approved';
    }

    /**
     * Check if user has a pending verification request.
     */
    public function hasPendingVerificationRequest(): bool
    {
        return $this->verificationRequests()
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if user's verification was rejected.
     */
    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Check if user can apply for verification.
     */
    public function canApplyVerification(): bool
    {
        // User cannot apply if already verified
        if ($this->isVerified()) {
            return false;
        }

        // User cannot apply if they have a pending request
        if ($this->hasPendingVerificationRequest()) {
            return false;
        }

        return true;
    }

    /**
     * Check if user has recently rejected request (within 7 days)
     */
    public function hasRecentRejectedRequest($days = 7): bool
    {
        return $this->verificationRequests()
            ->where('status', 'rejected')
            ->where('created_at', '>=', now()->subDays($days))
            ->exists();
    }

    /**
     * Get verification request status.
     */
    public function getVerificationRequestStatusAttribute(): ?string
    {
        if ($this->isVerified()) {
            return 'approved';
        }

        $latestRequest = $this->verificationRequests()->latest()->first();

        if ($latestRequest) {
            return $latestRequest->status; // pending, approved, rejected
        }

        return null; // never applied
    }

    /**
     * Get verification badge information.
     */
    public function getVerificationBadgeAttribute(): array
    {
        if ($this->isVerified()) {
            return [
                'text' => 'Verified',
                'color' => 'success',
                'icon' => 'fa-check-circle',
            ];
        }

        if ($this->hasPendingVerificationRequest()) {
            return [
                'text' => 'Pending Verification',
                'color' => 'warning',
                'icon' => 'fa-clock',
            ];
        }

        if ($this->isRejected()) {
            return [
                'text' => 'Verification Rejected',
                'color' => 'danger',
                'icon' => 'fa-times-circle',
            ];
        }

        return [
            'text' => 'Not Verified',
            'color' => 'secondary',
            'icon' => 'fa-user',
        ];
    }

    /**
     * Get verification status for display.
     */
    public function getVerificationStatusTextAttribute(): string
    {
        return match($this->verification_status) {
            'approved' => 'Verified',
            'pending' => 'Pending Verification',
            'rejected' => 'Verification Rejected',
            default => 'Not Verified',
        };
    }

    /**
     * Get verification status color.
     */
    public function getVerificationStatusColorAttribute(): string
    {
        return match($this->verification_status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get verification status icon.
     */
    public function getVerificationStatusIconAttribute(): string
    {
        return match($this->verification_status) {
            'approved' => 'fa-check-circle',
            'pending' => 'fa-clock',
            'rejected' => 'fa-times-circle',
            default => 'fa-user',
        };
    }

    /**
     * Get verification message for display.
     */
    public function getVerificationMessageAttribute(): string
    {
        if ($this->isVerified()) {
            return 'Verified Account';
        }

        if ($this->hasPendingVerificationRequest()) {
            return 'Verification Pending';
        }

        if ($this->isRejected()) {
            return 'Verification Rejected';
        }

        return 'Not Verified';
    }

    // =================== ROLE METHODS ===================

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is an independent supplier
     */
    public function isSupplier(): bool
    {
        return $this->role === self::ROLE_SUPPLIER;
    }

    /**
     * Check if user is a farmer
     */
    public function isFarmer(): bool
    {
        return $this->role === self::ROLE_FARMER;
    }

    /**
     * Check if user is an agent
     */
    public function isAgent(): bool
    {
        return $this->role === self::ROLE_AGENT;
    }

    /**
     * Check if user is a veterinary
     */
    public function isVeterinary(): bool
    {
        return $this->role === self::ROLE_VETERINARY;
    }

    /**
     * Check if user has selected a role
     */
    public function hasRole(): bool
    {
        return !empty($this->role);
    }

    /**
     * Get the user's role in readable format
     */
    public function getRoleNameAttribute(): string
    {
        if (!$this->role) {
            return 'No Role Selected';
        }

        return match($this->role) {
            'admin' => 'Admin',
            'supplier' => 'Independent Supplier',
            'farmer' => 'Farmer',
            'agent' => 'Agent',
            'veterinary' => 'Veterinary',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get the role badge color based on role
     */
    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role) {
            'admin' => 'warning',
            'supplier' => 'success',
            'farmer' => 'primary',
            'agent' => 'info',
            'veterinary' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the role icon based on role
     */
    public function getRoleIconAttribute(): string
    {
        return match($this->role) {
            'admin' => 'crown',
            'supplier' => 'warehouse',
            'farmer' => 'tractor',
            'agent' => 'user-tie',
            'veterinary' => 'user-md',
            default => 'user',
        };
    }

    // =================== ACCOUNT STATUS METHODS ===================

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if user account is inactive
     */
    public function isInactive(): bool
    {
        return $this->is_active === false;
    }

    /**
     * Activate the user account
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Deactivate the user account
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Check if user email is verified
     */
    public function hasVerifiedEmail(): bool
    {
        return !empty($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     */
    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();

        return $this;
    }

    // =================== VETERINARY LICENSE METHODS ===================

    /**
     * Check if the user has a valid veterinary license.
     */
    public function hasValidLicense(): bool
    {
        return $this->veterinaryLicenses()
            ->where('document_type', 'veterinary_license')
            ->where('is_verified', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->exists();
    }

    /**
     * Check if the user has complete professional information.
     */
    public function hasCompleteProfessionalInfo(): bool
    {
        return $this->has_professional_info &&
            $this->license_number &&
            $this->license_expiry &&
            $this->years_of_experience &&
            $this->consultation_fee;
    }

    // =================== VERIFICATION REQUESTER METHODS ===================

    /**
     * Get the verification requester name
     */
    public function getVerificationRequesterNameAttribute(): string
    {
        return $this->name . ' (' . $this->getRoleNameAttribute() . ')';
    }

    /**
     * Get the verification requester email
     */
    public function getVerificationRequesterEmailAttribute(): string
    {
        return $this->email;
    }

    /**
     * Get the verification requester phone
     */
    public function getVerificationRequesterPhoneAttribute(): ?string
    {
        return $this->phone;
    }

    /**
     * Get the verification requester address
     */
    public function getVerificationRequesterAddressAttribute(): ?string
    {
        return $this->address;
    }

    /**
 * Get role badge color
 */
public function getRoleBadgeColor()
{
    return $this->role_badge_color;
}

/**
 * Get role name
 */
public function getRoleName()
{
    return $this->role_name;
}

/**
 * Get role icon
 */
public function getRoleIcon()
{
    return $this->role_icon;
}
}
