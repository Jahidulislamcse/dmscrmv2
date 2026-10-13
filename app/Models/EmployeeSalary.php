<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'base_salary',
        'bonus',
        'deductions',
        'net_salary',
        'status',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'bonus'       => 'decimal:2',
        'deductions'  => 'decimal:2',
        'net_salary'  => 'decimal:2',
        'paid_at'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
