<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'name',
        'target_type',
        'target_value',
        'current_value',
        'period',
        'start_date',
        'end_date',
        'status',
        'description',
        'metrics'
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'metrics' => 'array'
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_value == 0) {
            return 0;
        }

        return min(100, ($this->current_value / $this->target_value) * 100);
    }

    public function getProgressColorAttribute()
    {
        $percentage = $this->progress_percentage;

        if ($percentage >= 100) {
            return 'success';
        } elseif ($percentage >= 70) {
            return 'info';
        } elseif ($percentage >= 40) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    public function getDaysRemainingAttribute()
    {
        $end = \Carbon\Carbon::parse($this->end_date);
        $now = \Carbon\Carbon::now();

        return max(0, $now->diffInDays($end, false));
    }
}
