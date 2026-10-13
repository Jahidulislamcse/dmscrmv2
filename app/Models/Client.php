<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company',
        'phone',
        'email',
        'location',
        'assigned_smm',
        'assigned_sales',
        'status',
        'billing_cycle',
        'advance',
        'notes',
        'satisfaction_score',
        'onboarded_at',
    ];

    protected $casts = [
        'advance' => 'decimal:2',
        'onboarded_at' => 'date',
    ];

    public function assignedSmm()
    {
        return $this->belongsTo(User::class, 'assigned_smm');
    }

    public function assignedSales()
    {
        return $this->belongsTo(User::class, 'assigned_sales');
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'client_services')
                    ->withPivot(['id', 'price', 'qty', 'custom_name'])
                    ->withTimestamps();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function contentPosts()
    {
        return $this->hasMany(ContentPost::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function getTotalMonthlyValueAttribute()
    {
        return $this->clientServices->sum(fn($cs) => $cs->price * $cs->qty);
    }
}
