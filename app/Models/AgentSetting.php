<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'business_name',
        'business_registration_number',
        'tax_identification_number',
        'business_address',
        'business_phone',
        'business_email',
        'business_website',
        'business_description',
        'email_notifications',
        'sms_notifications',
        'order_updates',
        'commission_alerts',
        'target_alerts',
        'marketplace_updates',
        'working_days',
        'working_hours_start',
        'working_hours_end',
        'commission_rate',
        'commission_type',
        'preferences'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'order_updates' => 'boolean',
        'commission_alerts' => 'boolean',
        'target_alerts' => 'boolean',
        'marketplace_updates' => 'boolean',
        'working_hours_start' => 'datetime:H:i',
        'working_hours_end' => 'datetime:H:i',
        'commission_rate' => 'decimal:2',
        'preferences' => 'array'
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function getWorkingHoursAttribute()
    {
        if ($this->working_hours_start && $this->working_hours_end) {
            return $this->working_hours_start->format('h:i A') . ' - ' .
                $this->working_hours_end->format('h:i A');
        }
        return 'Not Set';
    }

    public function getNotificationSettingsAttribute()
    {
        return [
            'email' => $this->email_notifications,
            'sms' => $this->sms_notifications,
            'order_updates' => $this->order_updates,
            'commission_alerts' => $this->commission_alerts,
            'target_alerts' => $this->target_alerts,
            'marketplace_updates' => $this->marketplace_updates,
        ];
    }
}
