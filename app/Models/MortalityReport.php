<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MortalityReport extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'agent_id',
        'report_type',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'resolved_at',
        'resolution_notes'
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
