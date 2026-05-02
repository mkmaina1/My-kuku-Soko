<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'duration', 'features', 'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function subscriptions()
    {
        return $this->hasMany(VeterinarySubscription::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'KES ' . number_format($this->price, 2);
    }

    public function getFeaturesListAttribute()
    {
        return is_array($this->features) ? $this->features : json_decode($this->features, true) ?? [];
    }
}
