<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'company',
        'location',
        'source',
        'stage_id',
        'assigned_to',
        'created_by',
        'budget',
        'notes',
        'next_followup',
        'converted_client_id',
        'quotation_status',
        'quotation_notes',
        'quotation_valid_until',
        'quotation_discount',
        'quotation_tax_percent',
        'quotation_approved_by',
        'quotation_approved_at',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'next_followup' => 'date',
        'quotation_valid_until' => 'date',
        'quotation_approved_at' => 'datetime',
        'quotation_discount' => 'decimal:2',
        'quotation_tax_percent' => 'decimal:2',
    ];

    public function stage()
    {
        return $this->belongsTo(LeadStage::class, 'stage_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function convertedClient()
    {
        return $this->belongsTo(Client::class, 'converted_client_id');
    }

    public function requirements()
    {
        return $this->hasMany(LeadRequirement::class);
    }

    public function quotationApprovedBy()
    {
        return $this->belongsTo(User::class, 'quotation_approved_by');
    }

    public function timelines()
    {
        return $this->hasMany(LeadTimeline::class);
    }
}
