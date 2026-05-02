<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'type',
        'title',
        'message',
        'read',
        'data',
        'link',
        'icon',
        'color',
        'created_by',
    ];

    protected $casts = [
        'read' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Add these for automatic timestamps
    public $timestamps = true;

    /**
     * Relationship with the user who receives the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the user who created the notification
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Scope for notifications of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for notifications for a specific user type
     */
    public function scopeForUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    /**
     * Scope for recent notifications (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['read' => true]);
        return $this;
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['read' => false]);
        return $this;
    }

    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return $this->read === true;
    }

    /**
     * Check if notification is unread
     */
    public function isUnread()
    {
        return $this->read === false;
    }

    /**
     * Get icon for notification type
     */
    public function getIconAttribute()
    {
        // Use custom icon if set, otherwise fallback to type-based icon
        if (!empty($this->attributes['icon'])) {
            return $this->attributes['icon'];
        }

        switch ($this->type) {
            case 'email':
                return 'fas fa-envelope';
            case 'sms':
                return 'fas fa-sms';
            case 'order':
                return 'fas fa-shopping-cart';
            case 'commission':
                return 'fas fa-money-bill-wave';
            case 'target':
                return 'fas fa-bullseye';
            case 'marketplace':
                return 'fas fa-store';
            case 'system':
                return 'fas fa-cog';
            case 'verification_request':
                return 'fas fa-shield-alt';
            case 'verification_status':
                return 'fas fa-user-check';
            case 'new_user':
                return 'fas fa-user-plus';
            case 'order_placed':
                return 'fas fa-shopping-bag';
            case 'order_completed':
                return 'fas fa-check-circle';
            case 'payment_received':
                return 'fas fa-credit-card';
            case 'message':
                return 'fas fa-comment';
            case 'alert':
                return 'fas fa-exclamation-triangle';
            default:
                return 'fas fa-bell';
        }
    }

    /**
     * Get badge color for notification type
     */
    public function getBadgeColorAttribute()
    {
        // Use custom color if set, otherwise fallback to type-based color
        if (!empty($this->attributes['color'])) {
            return $this->attributes['color'];
        }

        switch ($this->type) {
            case 'email':
                return 'primary';
            case 'sms':
                return 'success';
            case 'order':
                return 'info';
            case 'commission':
                return 'warning';
            case 'target':
                return 'danger';
            case 'marketplace':
                return 'secondary';
            case 'system':
                return 'dark';
            case 'verification_request':
                return 'warning';
            case 'verification_status':
                return 'success';
            case 'new_user':
                return 'primary';
            case 'order_placed':
                return 'info';
            case 'order_completed':
                return 'success';
            case 'payment_received':
                return 'success';
            case 'message':
                return 'primary';
            case 'alert':
                return 'danger';
            default:
                return 'muted';
        }
    }

    /**
     * Get the notification data as array
     */
    public function getDataAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Set the notification data
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get human-readable time
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if notification has a link
     */
    public function hasLink()
    {
        return !empty($this->link);
    }

    /**
     * Get the notification link
     */
    public function getLinkAttribute()
    {
        return $this->attributes['link'] ?? '#';
    }

    /**
     * Create a notification for a user
     */
    public static function createForUser($userId, array $data)
    {
        return self::create(array_merge($data, [
            'user_id' => $userId,
            'read' => false,
        ]));
    }

    /**
     * Create a notification for multiple users
     */
    public static function createForUsers(array $userIds, array $data)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = self::create(array_merge($data, [
                'user_id' => $userId,
                'read' => false,
            ]));
        }
        return $notifications;
    }

    /**
     * Create a verification request notification
     */
    public static function createVerificationRequest($userId, $userName, $verificationId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'verification_request',
            'title' => 'New Verification Request',
            'message' => "{$userName} has submitted a verification request.",
            'link' => "/admin/verifications/{$verificationId}",
            'icon' => 'fas fa-shield-alt',
            'color' => 'warning',
            'data' => [
                'user_id' => $userId,
                'user_name' => $userName,
                'verification_id' => $verificationId,
                'action_url' => "/admin/verifications/{$verificationId}"
            ]
        ]);
    }

    /**
     * Create a verification status notification
     */
    public static function createVerificationStatus($userId, $status, $adminNotes = null)
    {
        $statusMessages = [
            'approved' => 'Your verification request has been approved!',
            'rejected' => 'Your verification request has been rejected.',
            'pending' => 'Your verification request is under review.',
        ];

        $icons = [
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            'pending' => 'fas fa-clock',
        ];

        $colors = [
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
        ];

        return self::create([
            'user_id' => $userId,
            'type' => 'verification_status',
            'title' => 'Verification ' . ucfirst($status),
            'message' => $statusMessages[$status] ?? 'Your verification status has been updated.',
            'link' => '/profile#verification',
            'icon' => $icons[$status] ?? 'fas fa-shield-alt',
            'color' => $colors[$status] ?? 'info',
            'data' => [
                'status' => $status,
                'admin_notes' => $adminNotes,
                'action_url' => '/profile#verification'
            ]
        ]);
    }
}
