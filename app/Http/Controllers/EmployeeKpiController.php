<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Attendance, EmployeeSalary, Task, Worklog, ContentPost, RolePermission};

class EmployeeKpiController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $month = $request->get('month', now()->format('Y-m'));
        $roleFilter = $request->get('role');

        $query = User::query()->where('active', true);
        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        $users = $query->orderBy('name')->get();

        // Compute KPIs for all users
        $teamKpis = [];
        $totalSalary = 0;
        $totalBonus = 0;
        $totalTasksCompleted = 0;
        $totalTasksOverdue = 0;
        $totalTasksCount = 0;
        $totalPresent = 0;
        $totalAbsent = 0;

        foreach ($users as $user) {
            $summary = $user->kpiSummary($month);
            $teamKpis[] = [
                'user'    => $user,
                'summary' => $summary,
            ];

            $totalSalary += $summary['net_salary'];
            $totalBonus += $summary['bonus'];
            $totalTasksCompleted += $summary['tasks_completed'];
            $totalTasksOverdue += $summary['tasks_overdue'];
            $totalTasksCount += $summary['tasks_total'];
            $totalPresent += $summary['present_days'];
            $totalAbsent += $summary['absent_days'];
        }

        $avgCompletionRate = $totalTasksCount > 0 ? round(($totalTasksCompleted / $totalTasksCount) * 100, 1) : 0;
        $roles = RolePermission::allRoles();

        return view('team.kpi', compact(
            'teamKpis',
            'month',
            'roleFilter',
            'roles',
            'totalSalary',
            'totalBonus',
            'totalTasksCompleted',
            'totalTasksOverdue',
            'avgCompletionRate',
            'totalPresent',
            'totalAbsent'
        ));
    }

    public function show(User $user, Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team') && auth()->id() !== $user->id) {
            abort(403, 'Access denied.');
        }

        $month = $request->get('month', now()->format('Y-m'));
        $kpi = $user->kpiSummary($month);

        $recentTasks = $user->tasks()->latest()->take(10)->get();
        $recentWorklogs = $user->worklogs()->latest('date')->take(10)->get();
        $recentPosts = $user->contentPosts()->latest('date')->take(10)->get();
        $attendances = $user->attendances()->where('date', 'like', "{$month}%")->orderBy('date', 'desc')->get();
        $salaryHistory = $user->salaries()->orderBy('month', 'desc')->take(6)->get();

        return view('team.employee_profile', compact(
            'user',
            'month',
            'kpi',
            'recentTasks',
            'recentWorklogs',
            'recentPosts',
            'attendances',
            'salaryHistory'
        ));
    }

    public function storeAttendance(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'date'      => 'required|date',
            'status'    => 'required|in:present,absent,late,half_day,leave',
            'check_in'  => 'nullable|string',
            'check_out' => 'nullable|string',
            'notes'     => 'nullable|string|max:255',
        ]);

        Attendance::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'date'    => $validated['date'],
            ],
            [
                'status'    => $validated['status'],
                'check_in'  => $validated['check_in'] ?? null,
                'check_out' => $validated['check_out'] ?? null,
                'notes'     => $validated['notes'] ?? null,
            ]
        );

        return back()->with('success', 'Attendance record logged successfully.');
    }

    public function storeSalary(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'month'          => 'required|string|regex:/^\d{4}-\d{2}$/',
            'base_salary'    => 'required|numeric|min:0',
            'bonus'          => 'nullable|numeric|min:0',
            'deductions'     => 'nullable|numeric|min:0',
            'status'         => 'required|in:paid,pending',
            'payment_method' => 'nullable|string|max:50',
            'paid_at'        => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $base = (float) $validated['base_salary'];
        $bonus = (float) ($validated['bonus'] ?? 0);
        $deductions = (float) ($validated['deductions'] ?? 0);
        $net = max(0, $base + $bonus - $deductions);

        EmployeeSalary::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'month'   => $validated['month'],
            ],
            [
                'base_salary'    => $base,
                'bonus'          => $bonus,
                'deductions'     => $deductions,
                'net_salary'     => $net,
                'status'         => $validated['status'],
                'payment_method' => $validated['payment_method'] ?? null,
                'paid_at'        => $validated['status'] === 'paid' ? ($validated['paid_at'] ?? now()->toDateString()) : null,
                'notes'          => $validated['notes'] ?? null,
            ]
        );

        // Also update user's base salary master record
        User::where('id', $validated['user_id'])->update(['base_salary' => $base]);

        return back()->with('success', 'Employee salary and bonus record updated successfully.');
    }

    public function updateEmployeeInfo(Request $request, User $user)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->canAccess('team')) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'phone'             => 'nullable|string|max:30',
            'designation'       => 'nullable|string|max:100',
            'department'        => 'nullable|string|max:100',
            'joining_date'      => 'nullable|date',
            'base_salary'       => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return back()->with('success', "Employee profile for {$user->name} updated successfully.");
    }
}
