<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('user:reset-password {username} {password}', function (string $username, string $password) {
    if (strlen($password) < 6) {
        $this->error('Password must be at least 6 characters.');

        return self::FAILURE;
    }

    $user = User::where('username', strtolower($username))->first();

    if (! $user) {
        $this->error("User [{$username}] was not found.");

        return self::FAILURE;
    }

    $user->forceFill([
        'password' => $password,
        'active' => true,
    ])->save();

    $this->info("Password reset for [{$user->username}].");

    return self::SUCCESS;
})->purpose('Reset a user password and reactivate the account');

Artisan::command('user:check-password {username} {password}', function (string $username, string $password) {
    $user = User::where('username', strtolower($username))->first();

    if (! $user || ! \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        $this->error('Password check failed.');

        return self::FAILURE;
    }

    $this->info('Password check passed.');

    return self::SUCCESS;
})->purpose('Check a user password against the stored hash');

// Automated CRM Notification & Alerts Dispatcher (REQ-NOT-01)
Artisan::command('crm:check-alerts', function () {
    $this->info('Scanning CRM for overdue invoices, tasks, pending SMM worklogs, and contract milestones...');
    $count = \App\Http\Controllers\NotificationController::generateAutomatedAlerts();
    $this->info("CRM Automated Alerts check complete! Generated {$count} new notification(s).");
    return self::SUCCESS;
})->purpose('Check and generate automated notifications for overdue invoices, tasks, and contract renewals')->dailyAt('09:00');
