<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderTemplate extends Model
{
    protected $fillable = [
        'name',
        'title',
        'type',
        'body',
        'days_before',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'days_before' => 'integer',
    ];
}
