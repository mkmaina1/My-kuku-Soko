<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeterinarySubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'plan_name',
        'amount_paid',
        'payment_method',
        'mpesa_receipt',
        'checkout_request_id',
        'status',
        'starts_at',
        'expires_at',
        'cancelled_at',
        'payment_verified', // ADD THIS
        'verified_at',      // ADD THIS
        'verified_by'       // ADD THIS
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'payment_verified' => 'boolean', // ADD THIS
        'verified_at' => 'datetime'      // ADD THIS
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at && $this->expires_at->isFuture();
    }

    // Scope for unverified payments
    public function scopeUnverified($query)
    {
        return $query->where('payment_verified', false)
            ->where('status', 'pending');
    }
}
