<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPost extends Model
{
    public const STAGES = [
        'draft'           => ['label' => 'Draft', 'color' => 'slate', 'icon' => 'fa-pen'],
        'internal_review' => ['label' => 'Internal Review', 'color' => 'indigo', 'icon' => 'fa-magnifying-glass'],
        'client_approval' => ['label' => 'Client Approval', 'color' => 'blue', 'icon' => 'fa-paper-plane'],
        'revision'        => ['label' => 'Revision Needed', 'color' => 'amber', 'icon' => 'fa-rotate-right'],
        'approved'        => ['label' => 'Approved', 'color' => 'emerald', 'icon' => 'fa-circle-check'],
        'published'       => ['label' => 'Published', 'color' => 'teal', 'icon' => 'fa-globe'],
    ];

    public const FORMATS = [
        'design'   => ['label' => 'Graphic Design', 'icon' => 'fa-palette', 'badge' => 'bg-purple-100 text-purple-700 border-purple-200'],
        'reel'     => ['label' => 'Reel / Short', 'icon' => 'fa-film', 'badge' => 'bg-pink-100 text-pink-700 border-pink-200'],
        'video'    => ['label' => 'Full Video', 'icon' => 'fa-video', 'badge' => 'bg-rose-100 text-rose-700 border-rose-200'],
        'copy'     => ['label' => 'Copywriting', 'icon' => 'fa-file-lines', 'badge' => 'bg-sky-100 text-sky-700 border-sky-200'],
        'carousel' => ['label' => 'Carousel', 'icon' => 'fa-images', 'badge' => 'bg-amber-100 text-amber-700 border-amber-200'],
    ];

    public const PLATFORMS = [
        'facebook'  => ['label' => 'Facebook', 'icon' => 'fa-brands fa-facebook', 'color' => '#1877F2'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram', 'color' => '#E4405F'],
        'linkedin'  => ['label' => 'LinkedIn', 'icon' => 'fa-brands fa-linkedin', 'color' => '#0A66C2'],
        'tiktok'    => ['label' => 'TikTok', 'icon' => 'fa-brands fa-tiktok', 'color' => '#000000'],
        'youtube'   => ['label' => 'YouTube', 'icon' => 'fa-brands fa-youtube', 'color' => '#FF0000'],
        'twitter'   => ['label' => 'X / Twitter', 'icon' => 'fa-brands fa-x-twitter', 'color' => '#000000'],
        'other'     => ['label' => 'Other Channel', 'icon' => 'fa-share-nodes', 'color' => '#64748B'],
    ];

    protected $fillable = [
        'title',
        'client_id',
        'assigned_to',
        'created_by',
        'platform',
        'post_type',
        'date',
        'publish_time',
        'status',
        'notes',
        'brief',
        'caption',
        'creative_url',
        'internal_feedback',
        'client_feedback',
    ];

    protected $casts = [
        'date' => 'date',
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

    public function getStageInfoAttribute()
    {
        return self::STAGES[$this->status] ?? [
            'label' => ucfirst(str_replace('_', ' ', $this->status)),
            'color' => 'slate',
            'icon'  => 'fa-circle'
        ];
    }

    public function getFormatInfoAttribute()
    {
        return self::FORMATS[$this->post_type] ?? [
            'label' => ucfirst($this->post_type),
            'icon'  => 'fa-file',
            'badge' => 'bg-slate-100 text-slate-700 border-slate-200'
        ];
    }

    public function getPlatformInfoAttribute()
    {
        return self::PLATFORMS[$this->platform] ?? [
            'label' => ucfirst($this->platform),
            'icon'  => 'fa-share-nodes',
            'color' => '#64748B'
        ];
    }
}
