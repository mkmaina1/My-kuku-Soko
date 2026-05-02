<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'agent_id',
        'order_number',
        'shipping_address',
        'payment_method',
        'notes',
        'subtotal',
        'shipping',
        'tax',
        'total',
        'status',
        'tracking_number',
        'delivered_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the agent who helped with the order.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the items in the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the commission for this order.
     */
    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include processing orders.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope a query to only include shipped orders.
     */
    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    /**
     * Scope a query to only include delivered orders.
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope a query to only include cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if order is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if order is processing.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if order is shipped.
     */
    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    /**
     * Check if order is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Mark order as processing.
     */
    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
        return $this;
    }

    /**
     * Mark order as shipped.
     */
    public function markAsShipped($trackingNumber = null)
    {
        $this->update([
            'status' => 'shipped',
            'tracking_number' => $trackingNumber,
        ]);
        return $this;
    }

    /**
     * Mark order as delivered.
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
        return $this;
    }

    /**
     * Mark order as cancelled.
     */
    public function markAsCancelled()
    {
        $this->update(['status' => 'cancelled']);
        return $this;
    }

    /**
     * Get the order status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the order status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Get the payment method icon.
     */
    public function getPaymentMethodIconAttribute(): string
    {
        return match($this->payment_method) {
            'mpesa' => 'fas fa-mobile-alt',
            'card' => 'fas fa-credit-card',
            'bank' => 'fas fa-university',
            'cash' => 'fas fa-money-bill-wave',
            'agent' => 'fas fa-user-tie',
            default => 'fas fa-money-bill',
        };
    }

    /**
     * Get the payment method text.
     */
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'mpesa' => 'M-Pesa',
            'card' => 'Credit Card',
            'bank' => 'Bank Transfer',
            'cash' => 'Cash on Delivery',
            'agent' => 'Agent Assisted',
            default => ucfirst($this->payment_method),
        };
    }

    // In app/Models/Order.php
public function transportMortality()
{
    return $this->hasOne(TransportMortality::class);
}

public function mortalityReports()
{
    return $this->hasMany(MortalityReport::class);
}
public function shippingAddress()
{
    // If you have a separate Address model
    return $this->belongsTo(Address::class, 'shipping_address_id');

    // OR if shipping address is just a text field in orders table
    // You don't need a relationship, just access the field directly
}
public function mpesaPayments()
{
    return $this->hasMany(MpesaPayment::class);
}

}
