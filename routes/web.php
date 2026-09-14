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
    SettingsController
};

// ── Guest / Auth Routes ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated Super Admin Routes ──
Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Meetings & Schedule Management
    Route::resource('meetings', MeetingController::class);
    Route::post('/meetings/{meeting}/status', [MeetingController::class, 'updateStatus'])->name('meetings.update-status');

    // CRM Pipeline Management
    Route::resource('crm', LeadController::class);
    Route::post('/crm/{lead}/stage', [LeadController::class, 'updateStage'])->name('crm.update-stage');
    Route::post('/crm/{lead}/timeline', [LeadController::class, 'addTimelineNote'])->name('crm.add-timeline');
    Route::post('/crm/{lead}/convert', [LeadController::class, 'convertToRequisition'])->name('crm.convert');

    // Super Admin Client Management
    Route::resource('clients', ClientController::class);

    // Super Admin Team & User Management
    Route::resource('team', UserController::class)->parameters(['team' => 'user']);

    // Agency Master Services Catalog
    Route::resource('services', ServiceController::class)->only(['index', 'store', 'update', 'destroy']);

    // Requisition Approval Hub
    Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('/requisitions/{requisition}', [RequisitionController::class, 'show'])->name('requisitions.show');
    Route::post('/requisitions/{requisition}/approve', [RequisitionController::class, 'approve'])->name('requisitions.approve');
    Route::post('/requisitions/{requisition}/reject', [RequisitionController::class, 'reject'])->name('requisitions.reject');

    // Financial Oversight (Invoices & Expenses)
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);

    // System & Agency Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/agency', [SettingsController::class, 'updateAgency'])->name('settings.update-agency');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::get('/settings/export', [SettingsController::class, 'exportBackup'])->name('settings.export-backup');
});
