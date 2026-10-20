<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Notification, Invoice, Task, Worklog, Client, User};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([]);
        }

        $notifs = Notification::where(function($q) use($user) {
            $q->where('user_id', $user->id)->orWhereNull('user_id');
        })->when(!$user->isAdmin(), fn($q) => $q->where('is_admin_only', false))
          ->orderByDesc('created_at')->take(20)->get();

        return response()->json($notifs);
    }

    public function markAllRead()
    {
        $user = Auth::user();
        if ($user) {
            Notification::where(function($q) use($user) {
                $q->where('user_id', $user->id)->orWhereNull('user_id');
            })->update(['is_read' => true]);
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read!');
    }

    /**
     * Trigger manual alert synchronization from UI
     */
    public function syncAlerts()
    {
        $generated = self::generateAutomatedAlerts();
        return redirect()->back()->with('success', "Alert synchronization complete! Generated {$generated} new alerts.");
    }

    /**
     * Core Automated Alert & Notification Generator Engine
     * Triggered via cron / schedule ('crm:check-alerts') and manual UI sync
     */
    public static function generateAutomatedAlerts(): int
    {
        $generated = 0;
        $today = now()->toDateString();

        // 1. Overdue Invoices Alert (Admin Only)
        $overdueInvoices = Invoice::with('client')
            ->whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', $today)
            ->get();

        foreach ($overdueInvoices as $inv) {
            $daysOverdue = (int) ceil(Carbon::parse($inv->due_date)->diffInDays(now()));
            $clientName = $inv->client->name ?? 'Client';
            $needle = "Invoice: {$inv->invoice_number}";

            // Check if already notified in the last 24 hours
            $exists = Notification::where('message', 'like', "%{$needle}%")
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => null, // Broadcast to all admins
                    'icon' => 'triangle-exclamation',
                    'bg_color' => 'bg-rose-500',
                    'message' => "🚨 Overdue Invoice: {$inv->invoice_number} for {$clientName} (" . number_format($inv->balance) . " BDT) is {$daysOverdue} days overdue!",
                    'is_admin_only' => true,
                    'is_read' => false,
                ]);
                $generated++;
            }
        }

        // 2. Overdue Tasks Alert (To Assigned User & Admin)
        $overdueTasks = Task::with(['client'])
            ->whereNotIn('status', ['done', 'completed'])
            ->where('deadline', '<', $today)
            ->get();

        foreach ($overdueTasks as $task) {
            if (!$task->assigned_to) continue;

            $clientName = $task->client->name ?? 'Internal';
            $needle = "Overdue Task: '{$task->title}'";

            $exists = Notification::where('user_id', $task->assigned_to)
                ->where('message', 'like', "%{$needle}%")
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => $task->assigned_to,
                    'icon' => 'clock',
                    'bg_color' => 'bg-amber-500',
                    'message' => "⏰ Overdue Task: '{$task->title}' for {$clientName} passed its deadline. Please deliver or update status.",
                    'is_admin_only' => false,
                    'is_read' => false,
                ]);
                $generated++;
            }
        }

        // 3. Pending SMM Worklog Reviews Alert (To SMM & Admin)
        $pendingWorklogs = Worklog::where('status', 'pending')->count();
        if ($pendingWorklogs > 0) {
            $needle = "QA Review Queue:";
            $exists = Notification::where('message', 'like', "%{$needle}%")
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => null,
                    'icon' => 'clipboard-check',
                    'bg_color' => 'bg-indigo-500',
                    'message' => "📋 QA Review Queue: {$pendingWorklogs} daily creative deliverables awaiting SMM review & quality ratings.",
                    'is_admin_only' => true,
                    'is_read' => false,
                ]);
                $generated++;
            }
        }

        // 4. Contract Retainer Renewal Check-in
        $activeClients = Client::where('status', 'active')
            ->where('billing_cycle', 'monthly')
            ->get();

        if ($activeClients->count() > 0) {
            $needle = "Monthly Retainer Review:";
            $exists = Notification::where('message', 'like', "%{$needle}%")
                ->where('created_at', '>=', now()->startOfMonth())
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => null,
                    'icon' => 'file-contract',
                    'bg_color' => 'bg-blue-500',
                    'message' => "📑 Monthly Retainer Review: " . $activeClients->count() . " active client retainers due for deliverable quota and invoice audits.",
                    'is_admin_only' => true,
                    'is_read' => false,
                ]);
                $generated++;
            }
        }

        return $generated;
    }
}
