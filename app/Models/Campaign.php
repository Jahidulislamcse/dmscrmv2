<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public const PLATFORMS = [
        'meta'     => ['label' => 'Meta Ads (FB/IG)', 'icon' => 'fa-brands fa-meta', 'color' => '#0668E1', 'badge' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'google'   => ['label' => 'Google Ads', 'icon' => 'fa-brands fa-google', 'color' => '#EA4335', 'badge' => 'bg-rose-100 text-rose-800 border-rose-200'],
        'tiktok'   => ['label' => 'TikTok Ads', 'icon' => 'fa-brands fa-tiktok', 'color' => '#000000', 'badge' => 'bg-slate-100 text-slate-800 border-slate-200'],
        'linkedin' => ['label' => 'LinkedIn Ads', 'icon' => 'fa-brands fa-linkedin', 'color' => '#0A66C2', 'badge' => 'bg-sky-100 text-sky-800 border-sky-200'],
        'youtube'  => ['label' => 'YouTube Ads', 'icon' => 'fa-brands fa-youtube', 'color' => '#FF0000', 'badge' => 'bg-red-100 text-red-800 border-red-200'],
        'other'    => ['label' => 'Other Channel', 'icon' => 'fa-bullseye', 'color' => '#64748B', 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'],
    ];

    public const OBJECTIVES = [
        'leads'      => ['label' => 'Lead Generation', 'icon' => 'fa-user-plus'],
        'sales'      => ['label' => 'Conversions / Sales', 'icon' => 'fa-cart-shopping'],
        'traffic'    => ['label' => 'Website Traffic', 'icon' => 'fa-arrow-pointer'],
        'awareness'  => ['label' => 'Brand Awareness', 'icon' => 'fa-eye'],
        'engagement' => ['label' => 'Engagement / Messages', 'icon' => 'fa-comments'],
    ];

    public const STATUSES = [
        'active'    => ['label' => 'Active', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'dot' => 'bg-emerald-500'],
        'paused'    => ['label' => 'Paused', 'badge' => 'bg-amber-100 text-amber-800 border-amber-300', 'dot' => 'bg-amber-500'],
        'completed' => ['label' => 'Completed', 'badge' => 'bg-slate-100 text-slate-700 border-slate-300', 'dot' => 'bg-slate-500'],
        'draft'     => ['label' => 'Draft', 'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300', 'dot' => 'bg-indigo-500'],
    ];

    protected $fillable = [
        'name',
        'client_id',
        'platform',
        'objective',
        'status',
        'budget',
        'spend',
        'revenue',
        'impressions',
        'clicks',
        'leads_count',
        'start_date',
        'end_date',
        'target_audience',
        'notes',
        'ad_account_id',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'budget'      => 'decimal:2',
        'spend'       => 'decimal:2',
        'revenue'     => 'decimal:2',
        'impressions' => 'integer',
        'clicks'      => 'integer',
        'leads_count' => 'integer',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Calculated metrics
    public function getCplAttribute(): float
    {
        return $this->leads_count > 0 ? round($this->spend / $this->leads_count, 2) : 0.0;
    }

    public function getCtrAttribute(): float
    {
        return $this->impressions > 0 ? round(($this->clicks / $this->impressions) * 100, 2) : 0.0;
    }

    public function getCpcAttribute(): float
    {
        return $this->clicks > 0 ? round($this->spend / $this->clicks, 2) : 0.0;
    }

    public function getRoasAttribute(): float
    {
        return $this->spend > 0 ? round($this->revenue / $this->spend, 2) : 0.0;
    }

    public function getBudgetUtilizationAttribute(): float
    {
        return $this->budget > 0 ? min(round(($this->spend / $this->budget) * 100, 1), 100.0) : 0.0;
    }

    public function getPlatformInfoAttribute(): array
    {
        return self::PLATFORMS[$this->platform] ?? [
            'label' => ucfirst($this->platform),
            'icon'  => 'fa-bullseye',
            'color' => '#64748B',
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200'
        ];
    }

    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? [
            'label' => ucfirst($this->status),
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
            'dot'   => 'bg-slate-400'
        ];
    }

    public function getObjectiveInfoAttribute(): array
    {
        return self::OBJECTIVES[$this->objective] ?? [
            'label' => ucfirst($this->objective),
            'icon'  => 'fa-bullseye'
        ];
    }
}
