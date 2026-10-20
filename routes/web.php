<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    ClientController,
    UserController,
    ServiceController,
    RequisitionController,
    InvoiceController,
    ExpenseController,
    LeadController,
    MeetingController,
    SettingsController,
    TaskController,
    NotificationController,
    ReminderController,
    FollowupController,
    ContentPostController,
    CampaignController,
    EmployeeKpiController,
    ReportController,
    TargetController,
    WorklogController,
    WorkloadController
};

// ── Guest / Auth Routes ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated Routes with Feature Controls ──
Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications Center
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Meetings & Schedule Management
    Route::middleware('feature:meetings')->group(function () {
        Route::resource('meetings', MeetingController::class);
        Route::post('/meetings/{meeting}/status', [MeetingController::class, 'updateStatus'])->name('meetings.update-status');
    });

    // CRM Pipeline Management
    Route::middleware('feature:crm')->group(function () {
        Route::get('/crm/export/csv', [LeadController::class, 'exportLeads'])->name('crm.export');
        Route::resource('crm', LeadController::class)->parameters(['crm' => 'lead']);
        Route::post('/crm/{lead}/stage', [LeadController::class, 'updateStage'])->name('crm.update-stage');
        Route::post('/crm/{lead}/timeline', [LeadController::class, 'addTimelineNote'])->name('crm.add-timeline');
        Route::post('/crm/{lead}/convert', [LeadController::class, 'convertToRequisition'])->name('crm.convert');
        Route::get('/crm/{lead}/quotation', [LeadController::class, 'quotation'])->name('crm.quotation');
        Route::post('/crm/{lead}/quotation', [LeadController::class, 'updateQuotation'])->name('crm.quotation.update');
        Route::post('/crm/{lead}/quotation/request-approval', [LeadController::class, 'requestQuotationApproval'])->name('crm.quotation.request-approval');
        Route::post('/crm/{lead}/quotation/approve', [LeadController::class, 'approveQuotation'])->name('crm.quotation.approve');
        Route::post('/crm/{lead}/quotation/reject', [LeadController::class, 'rejectQuotation'])->name('crm.quotation.reject');
    });

    // Client Operations
    Route::middleware('feature:clients')->group(function () {
        Route::get('/clients/export/csv', [ClientController::class, 'exportClients'])->name('clients.export');
        Route::resource('clients', ClientController::class);
    });

    // Team & Role Feature Control Management & Employee Info / KPI
    Route::middleware('feature:team')->group(function () {
        Route::get('/team/kpi', [EmployeeKpiController::class, 'index'])->name('team.kpi');
        Route::get('/team/{user}/kpi', [EmployeeKpiController::class, 'show'])->name('team.kpi.show');
        Route::post('/team/attendance', [EmployeeKpiController::class, 'storeAttendance'])->name('team.attendance.store');
        Route::post('/team/salary', [EmployeeKpiController::class, 'storeSalary'])->name('team.salary.store');
        Route::put('/team/{user}/employee-info', [EmployeeKpiController::class, 'updateEmployeeInfo'])->name('team.employee-info.update');
        Route::post('/team/permissions', [UserController::class, 'updatePermissions'])->name('team.update-permissions');
        Route::resource('team', UserController::class)->parameters(['team' => 'user']);
    });

    // Agency Master Services Catalog
    Route::middleware('feature:services')->group(function () {
        Route::resource('services', ServiceController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // My Assigned Tasks
    Route::middleware('feature:my_tasks')->group(function () {
        Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my-tasks');
    });

    // Task Board & Deliverables Operations
    Route::middleware('feature:tasks')->group(function () {
        Route::resource('tasks', TaskController::class);
        Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::post('/tasks/{task}/progress', [TaskController::class, 'addProgress'])->name('tasks.add-progress');
        Route::post('/tasks/{task}/checklist', [TaskController::class, 'updateChecklist'])->name('tasks.update-checklist');
        Route::post('/tasks/{task}/attachments', [TaskController::class, 'uploadAttachment'])->name('tasks.upload-attachment');
        Route::delete('/tasks/{task}/attachments/{index}', [TaskController::class, 'deleteAttachment'])->name('tasks.delete-attachment');
    });

    // Content Operations & Editorial Calendar
    Route::middleware('feature:calendar')->group(function () {
        Route::get('/calendar', [ContentPostController::class, 'index'])->name('calendar.index');
        Route::post('/calendar', [ContentPostController::class, 'store'])->name('calendar.store');
        Route::put('/calendar/{post}', [ContentPostController::class, 'update'])->name('calendar.update');
        Route::post('/calendar/{post}/stage', [ContentPostController::class, 'updateStage'])->name('calendar.update-stage');
        Route::delete('/calendar/{post}', [ContentPostController::class, 'destroy'])->name('calendar.destroy');
    });

    // Performance Marketing & Ad Campaigns
    Route::middleware('feature:campaigns')->group(function () {
        Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
        Route::post('/campaigns/{campaign}/metrics', [CampaignController::class, 'updateMetrics'])->name('campaigns.metrics');
        Route::post('/campaigns/{campaign}/status', [CampaignController::class, 'updateStatus'])->name('campaigns.status');
        Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    });

    // Requisition Approval Hub
    Route::middleware('feature:requisitions')->group(function () {
        Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
        Route::get('/requisitions/{requisition}', [RequisitionController::class, 'show'])->name('requisitions.show');
        Route::post('/requisitions/{requisition}/approve', [RequisitionController::class, 'approve'])->name('requisitions.approve');
        Route::post('/requisitions/{requisition}/reject', [RequisitionController::class, 'reject'])->name('requisitions.reject');
    });

    // Follow-ups Management Hub
    Route::middleware('feature:followups')->group(function () {
        Route::get('/followups', [FollowupController::class, 'index'])->name('followups.index');
        Route::post('/followups', [FollowupController::class, 'store'])->name('followups.store');
        Route::put('/followups/{followup}', [FollowupController::class, 'update'])->name('followups.update');
        Route::post('/followups/{followup}/complete', [FollowupController::class, 'complete'])->name('followups.complete');
        Route::delete('/followups/{followup}', [FollowupController::class, 'destroy'])->name('followups.destroy');
    });

    // Financial Oversight (Invoices, Expenses & Payment Reminders)
    Route::middleware('feature:invoices')->group(function () {
        Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    });

    Route::middleware('feature:reminders')->group(function () {
        Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
        Route::post('/reminders/send', [ReminderController::class, 'send'])->name('reminders.send');
        Route::post('/reminders/preview', [ReminderController::class, 'preview'])->name('reminders.preview');
        Route::post('/reminders/templates', [ReminderController::class, 'storeTemplate'])->name('reminders.store-template');
        Route::put('/reminders/templates/{template}', [ReminderController::class, 'updateTemplate'])->name('reminders.update-template');
        Route::delete('/reminders/templates/{template}', [ReminderController::class, 'destroyTemplate'])->name('reminders.destroy-template');
    });

    Route::middleware('feature:expenses')->group(function () {
        Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);
    });

    // Monthly Client Performance Reports (REQ-REP-01)
    Route::middleware('feature:reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/client/{client}', [ReportController::class, 'clientReport'])->name('reports.client');
        Route::get('/reports/client/{client}/pdf', [ReportController::class, 'clientPdf'])->name('reports.client.pdf');
    });

    // Sales Targets & Monthly Quotas (REQ-TGT-01)
    Route::middleware('feature:targets')->group(function () {
        Route::get('/targets', [TargetController::class, 'index'])->name('targets.index');
        Route::post('/targets', [TargetController::class, 'store'])->name('targets.store');
        Route::post('/targets/{target}/deals', [TargetController::class, 'addDeal'])->name('targets.deals.store');
        Route::put('/targets/{target}', [TargetController::class, 'update'])->name('targets.update');
        Route::delete('/targets/{target}', [TargetController::class, 'destroy'])->name('targets.destroy');
    });

    // Daily Worklogs & SMM QA Review (REQ-LOG-01)
    Route::middleware('feature:worklogs')->group(function () {
        Route::get('/worklogs', [WorklogController::class, 'index'])->name('worklogs.index');
        Route::post('/worklogs', [WorklogController::class, 'store'])->name('worklogs.store');
        Route::post('/worklogs/{worklog}/review', [WorklogController::class, 'review'])->name('worklogs.review');
        Route::delete('/worklogs/{worklog}', [WorklogController::class, 'destroy'])->name('worklogs.destroy');
    });

    // Team Workload Capacity & Day Planner (REQ-WLD-01)
    Route::middleware('feature:workload')->group(function () {
        Route::get('/workload', [WorkloadController::class, 'index'])->name('workload.index');
        Route::post('/workload/items', [WorkloadController::class, 'storeWorkItem'])->name('workload.items.store');
        Route::patch('/workload/items/{item}/status', [WorkloadController::class, 'updateWorkItemStatus'])->name('workload.items.update-status');
        Route::delete('/workload/items/{item}', [WorkloadController::class, 'destroyWorkItem'])->name('workload.items.destroy');
    });

    // System & Agency Settings
    Route::middleware('feature:settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/agency', [SettingsController::class, 'updateAgency'])->name('settings.update-agency');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    });
});
