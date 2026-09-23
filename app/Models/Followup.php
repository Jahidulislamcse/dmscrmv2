<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'priority',
        'scheduled_at',
        'status',
        'outcome',
        'lead_id',
        'client_id',
        'meeting_id',
        'assigned_to',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->scheduled_at && $this->scheduled_at->isPast();
    }

    public function isToday(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isToday();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', now()->toDateString());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')->where('scheduled_at', '<', now());
    }
}
