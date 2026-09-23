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
    FollowupController
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
        Route::resource('crm', LeadController::class)->parameters(['crm' => 'lead']);
        Route::post('/crm/{lead}/stage', [LeadController::class, 'updateStage'])->name('crm.update-stage');
        Route::post('/crm/{lead}/timeline', [LeadController::class, 'addTimelineNote'])->name('crm.add-timeline');
        Route::post('/crm/{lead}/convert', [LeadController::class, 'convertToRequisition'])->name('crm.convert');
    });

    // Client Operations
    Route::middleware('feature:clients')->group(function () {
        Route::resource('clients', ClientController::class);
    });

    // Team & Role Feature Control Management
    Route::middleware('feature:team')->group(function () {
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

    // System & Agency Settings
    Route::middleware('feature:settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/agency', [SettingsController::class, 'updateAgency'])->name('settings.update-agency');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    });
});
