<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'color',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
        'password' => 'hashed',
    ];

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isSales(): bool
    {
        return $this->role === 'sales';
    }

    public function isSMM(): bool
    {
        return $this->role === 'smm';
    }

    public function isDesigner(): bool
    {
        return in_array($this->role, ['designer', 'motion', 'video', 'seo', 'developer', 'mediabuyer']);
    }

    public function canAccess(string $module): bool
    {
        if ($this->isOwner()) {
            return true;
        }

        $allPermissions = \App\Models\RolePermission::getPermissions();
        $allowedModules = $allPermissions[$this->role] ?? ['dashboard'];

        return in_array($module, $allowedModules);
    }

    public function assignedClients()
    {
        return $this->hasMany(Client::class, 'assigned_smm');
    }

    public function salesClients()
    {
        return $this->hasMany(Client::class, 'assigned_sales');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function worklogs()
    {
        return $this->hasMany(Worklog::class, 'user_id');
    }
}
