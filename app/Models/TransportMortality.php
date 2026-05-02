<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportMortality extends Model
{
     // Add this line to specify the table name
    protected $table = 'transport_mortality';
    
    protected $fillable = [
        'order_id',
        'agent_id',
        'transport_type',
        'quantity',
        'cause',
        'notes',
        'reported_by',
        'status',
        'resolved_at'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'resolved_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
