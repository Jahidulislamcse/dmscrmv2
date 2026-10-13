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
        'phone',
        'password',
        'role',
        'designation',
        'department',
        'joining_date',
        'base_salary',
        'emergency_contact',
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
        'joining_date' => 'date',
        'base_salary' => 'decimal:2',
    ];

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['owner', 'admin']);
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

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) $this->active;
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

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'user_id');
    }

    public function contentPosts()
    {
        return $this->hasMany(ContentPost::class, 'assigned_to');
    }

    public function kpiSummary(?string $month = null): array
    {
        $month = $month ?: now()->format('Y-m');

        // Task metrics
        $tasksTotal = $this->tasks()->count();
        $tasksCompleted = $this->tasks()->where('status', 'done')->count();
        $tasksPending = $this->tasks()->whereIn('status', ['pending', 'in_progress', 'done_pending_review'])->count();
        $tasksOverdue = $this->tasks()
            ->where('status', '!=', 'done')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now()->toDateString())
            ->count();
        
        $taskCompletionRate = $tasksTotal > 0 ? round(($tasksCompleted / $tasksTotal) * 100, 1) : 0;
        $workloadHours = (float) $this->tasks()
            ->whereIn('status', ['pending', 'in_progress', 'done_pending_review'])
            ->sum('estimated_hours');

        // Worklog quality metrics
        $worklogsCount = $this->worklogs()->count();
        $avgRating = (float) ($this->worklogs()->avg('quality_rating') ?: 0);
        $avgRatingFormatted = round($avgRating, 1);

        // Content Posts metrics
        $postsTotal = $this->contentPosts()->count();
        $postsCompleted = $this->contentPosts()->whereIn('status', ['approved', 'published'])->count();

        // Attendance metrics for selected month
        $attQuery = $this->attendances()->where('date', 'like', "{$month}%");
        $presentDays = (clone $attQuery)->where('status', 'present')->count();
        $absentDays = (clone $attQuery)->where('status', 'absent')->count();
        $lateDays = (clone $attQuery)->where('status', 'late')->count();
        $leaveDays = (clone $attQuery)->where('status', 'leave')->count();
        $totalTrackedDays = $presentDays + $absentDays + $lateDays + $leaveDays;

        // Salary & Bonus for month
        $salaryRecord = $this->salaries()->where('month', $month)->first();
        $baseSalary = $salaryRecord ? $salaryRecord->base_salary : (float) ($this->base_salary ?: 0);
        $bonus = $salaryRecord ? $salaryRecord->bonus : 0.00;
        $netSalary = $salaryRecord ? $salaryRecord->net_salary : $baseSalary;
        $salaryStatus = $salaryRecord ? $salaryRecord->status : 'pending';

        // Role-Based Composite KPI Score calculation (0 - 100)
        $attendanceScore = $totalTrackedDays > 0 ? (($presentDays + ($lateDays * 0.5)) / $totalTrackedDays) * 30 : 28;

        if ($this->isAdmin()) {
            // Executive Leadership: full marks on operational stability + attendance
            $taskScore = 38;
            $ratingScore = 28;
        } elseif ($this->isSales()) {
            // Sales Reps: evaluated on Target quota achievement & won leads
            $target = \App\Models\Target::where('user_id', $this->id)->where('month', $month)->first();
            if ($target && $target->total > 0) {
                $achievedPct = min(100, ($target->achieved / $target->total) * 100);
                $taskScore = ($achievedPct / 100) * 40;
            } else {
                $taskScore = 32;
            }
            $completedFollowups = \App\Models\Followup::where('assigned_to', $this->id)->where('status', 'completed')->count();
            $ratingScore = min(30, max(22, 20 + ($completedFollowups * 5)));
        } elseif ($this->isSMM()) {
            // SMM: evaluated on editorial content progress & calendar completion
            $postsTotal = \App\Models\ContentPost::count();
            $postsReady = \App\Models\ContentPost::whereIn('status', ['approved', 'published'])->count();
            $smmPct = $postsTotal > 0 ? ($postsReady / $postsTotal) * 100 : 75;
            $taskScore = ($smmPct / 100) * 40;
            $ratingScore = 28;
        } else {
            // Creative, Video, Motion, SEO, Media Buyer, Employee:
            // Tasks Completion Rate (40%) + Worklog Quality Rating (30%) + Attendance (30%)
            $taskScore = $tasksTotal > 0 ? ($taskCompletionRate / 100) * 40 : 30;
            $ratingScore = $worklogsCount > 0 ? ($avgRating / 5) * 30 : 22;
        }
        
        $totalScore = round(min(100, max(0, $taskScore + $ratingScore + $attendanceScore)), 1);

        if ($totalScore >= 90) {
            $grade = 'A+';
            $gradeLabel = 'Outstanding';
            $gradeBadge = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        } elseif ($totalScore >= 80) {
            $grade = 'A';
            $gradeLabel = 'Excellent';
            $gradeBadge = 'bg-blue-100 text-blue-800 border-blue-300';
        } elseif ($totalScore >= 70) {
            $grade = 'B';
            $gradeLabel = 'Good';
            $gradeBadge = 'bg-indigo-100 text-indigo-800 border-indigo-300';
        } elseif ($totalScore >= 60) {
            $grade = 'C';
            $gradeLabel = 'Average';
            $gradeBadge = 'bg-amber-100 text-amber-800 border-amber-300';
        } else {
            $grade = 'D';
            $gradeLabel = 'Needs Improvement';
            $gradeBadge = 'bg-rose-100 text-rose-800 border-rose-300';
        }

        return [
            'month'               => $month,
            'tasks_total'         => $tasksTotal,
            'tasks_completed'     => $tasksCompleted,
            'tasks_pending'       => $tasksPending,
            'tasks_overdue'       => $tasksOverdue,
            'completion_rate'     => $taskCompletionRate,
            'workload_hours'      => $workloadHours,
            'worklogs_count'      => $worklogsCount,
            'avg_rating'          => $avgRatingFormatted,
            'posts_total'         => $postsTotal,
            'posts_completed'     => $postsCompleted,
            'present_days'        => $presentDays,
            'absent_days'         => $absentDays,
            'late_days'           => $lateDays,
            'leave_days'          => $leaveDays,
            'tracked_days'        => $totalTrackedDays,
            'base_salary'         => $baseSalary,
            'bonus'               => $bonus,
            'net_salary'          => $netSalary,
            'salary_status'       => $salaryStatus,
            'kpi_score'           => $totalScore,
            'kpi_grade'           => $grade,
            'kpi_label'           => $gradeLabel,
            'kpi_badge'           => $gradeBadge,
        ];
    }
}
