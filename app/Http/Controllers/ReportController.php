<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\{Client, Campaign, ContentPost, Task, Invoice, ClientService, User};
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Reports Hub: Agency-wide monthly performance dashboard across all active clients.
     */
    public function index(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        try {
            $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
        } catch (\Throwable $e) {
            $selectedMonth = now()->format('Y-m');
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        $clients = Client::with(['assignedSmm', 'assignedSales', 'clientServices'])
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        // Month-wide Agency Totals
        $agencyPostsTotal = ContentPost::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $agencyPostsPublished = ContentPost::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'published')
            ->count();

        $agencyCampaigns = Campaign::where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
              ->orWhere(function ($sub) use ($startDate, $endDate) {
                  $sub->where('status', 'active')
                      ->where('created_at', '<=', $endDate);
              });
        })->get();

        $totalAdSpend = $agencyCampaigns->sum('spend');
        $totalAdLeads = $agencyCampaigns->sum('leads_count');
        $totalAdRevenue = $agencyCampaigns->sum('revenue');
        $agencyRoas = $totalAdSpend > 0 ? round($totalAdRevenue / $totalAdSpend, 2) : 0.0;

        $agencyTasksCompleted = Task::whereNull('parent_task_id')
            ->where('status', 'done')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        $agencyInvoicesBilled = Invoice::whereBetween('issued_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('total');

        $agencyInvoicesCollected = Invoice::whereBetween('issued_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'paid')
            ->sum('total');

        // Compile metrics for each client
        $clientMetrics = $clients->map(function ($client) use ($startDate, $endDate) {
            // Content Posts
            $posts = ContentPost::where('client_id', $client->id)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get();
            $postsTotal = $posts->count();
            $postsPublished = $posts->where('status', 'published')->count();
            $postsInReview = $posts->whereIn('status', ['draft', 'internal_review', 'client_approval', 'revision'])->count();

            // Campaigns
            $campaigns = Campaign::where('client_id', $client->id)
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhere('status', 'active');
                })->get();
            $adSpend = $campaigns->sum('spend');
            $adLeads = $campaigns->sum('leads_count');
            $adRevenue = $campaigns->sum('revenue');
            $roas = $adSpend > 0 ? round($adRevenue / $adSpend, 2) : 0.0;

            // Tasks
            $tasks = Task::where('client_id', $client->id)
                ->whereNull('parent_task_id')
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('deadline', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhereBetween('updated_at', [$startDate, $endDate]);
                })->get();
            $tasksTotal = $tasks->count();
            $tasksCompleted = $tasks->where('status', 'done')->count();

            // Invoices
            $invoices = Invoice::where('client_id', $client->id)
                ->whereBetween('issued_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get();
            $billedAmount = $invoices->sum('total');
            $balanceDue = $invoices->sum('balance');

            // Retainer monthly estimate
            $monthlyRetainer = $client->total_monthly_value;

            // Health Score calculation (0 - 100)
            $score = 70; // baseline
            if ($postsTotal > 0) {
                $publishRate = ($postsPublished / $postsTotal) * 100;
                $score += ($publishRate >= 80 ? 10 : ($publishRate < 50 ? -10 : 0));
            }
            if ($adSpend > 0) {
                $score += ($roas >= 3.0 ? 10 : ($roas < 1.5 ? -10 : 5));
            }
            if ($tasksTotal > 0) {
                $taskRate = ($tasksCompleted / $tasksTotal) * 100;
                $score += ($taskRate >= 75 ? 10 : -5);
            }
            if ($balanceDue > 0) {
                $score -= 5;
            }
            $score = max(30, min(100, $score));

            return [
                'client'           => $client,
                'monthlyRetainer'  => $monthlyRetainer,
                'postsTotal'       => $postsTotal,
                'postsPublished'   => $postsPublished,
                'postsInReview'    => $postsInReview,
                'campaignsCount'   => $campaigns->count(),
                'adSpend'          => $adSpend,
                'adLeads'          => $adLeads,
                'adRevenue'        => $adRevenue,
                'roas'             => $roas,
                'tasksTotal'       => $tasksTotal,
                'tasksCompleted'   => $tasksCompleted,
                'billedAmount'     => $billedAmount,
                'balanceDue'       => $balanceDue,
                'healthScore'      => $score,
            ];
        });

        // Available Months list for dropdown (last 12 months)
        $monthOptions = [];
        for ($i = 0; $i < 12; $i++) {
            $m = now()->subMonths($i);
            $monthOptions[$m->format('Y-m')] = $m->format('F Y');
        }

        return view('reports.index', compact(
            'selectedMonth',
            'startDate',
            'endDate',
            'clients',
            'clientMetrics',
            'agencyPostsTotal',
            'agencyPostsPublished',
            'totalAdSpend',
            'totalAdLeads',
            'totalAdRevenue',
            'agencyRoas',
            'agencyTasksCompleted',
            'agencyInvoicesBilled',
            'agencyInvoicesCollected',
            'monthOptions'
        ));
    }

    /**
     * Dedicated Client Monthly Performance Report Dossier.
     */
    public function clientReport(Client $client, Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        try {
            $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
        } catch (\Throwable $e) {
            $selectedMonth = now()->format('Y-m');
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        $reportData = $this->compileClientReportData($client, $startDate, $endDate, $selectedMonth);

        // Available Months for switcher
        $monthOptions = [];
        for ($i = 0; $i < 12; $i++) {
            $m = now()->subMonths($i);
            $monthOptions[$m->format('Y-m')] = $m->format('F Y');
        }

        // All other clients for quick switch
        $allClients = Client::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('reports.client_monthly', array_merge($reportData, [
            'monthOptions' => $monthOptions,
            'allClients'   => $allClients,
        ]));
    }

    /**
     * Download or stream client report in high-fidelity PDF format via DomPDF.
     */
    public function clientPdf(Client $client, Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        try {
            $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
        } catch (\Throwable $e) {
            $selectedMonth = now()->format('Y-m');
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        $reportData = $this->compileClientReportData($client, $startDate, $endDate, $selectedMonth);

        $agencySettings = SettingsController::getAgencySettings();

        $pdf = Pdf::loadView('reports.client_pdf', array_merge($reportData, [
            'agencySettings' => $agencySettings,
        ]))
        ->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('defaultFont', 'Kalpurush')
        ->setOption('enable_font_subsetting', true);

        $filename = 'Client_Report_' . Str::slug($client->name) . '_' . $selectedMonth . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Helper to assemble and calculate comprehensive monthly data for a client.
     */
    private function compileClientReportData(Client $client, Carbon $startDate, Carbon $endDate, string $selectedMonth): array
    {
        $client->load(['assignedSmm', 'assignedSales', 'clientServices.service']);

        // 1. Content Calendar Deliverables
        $posts = ContentPost::where('client_id', $client->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'asc')
            ->get();

        $postsCount = $posts->count();
        $postsPublished = $posts->where('status', 'published')->count();
        $postsApproved = $posts->where('status', 'approved')->count();
        $postsInReview = $posts->whereIn('status', ['draft', 'internal_review', 'client_approval', 'revision'])->count();
        $postsRevision = $posts->where('status', 'revision')->count();

        // Group by Format
        $postsByFormat = [
            'design'   => $posts->where('post_type', 'design')->count(),
            'reel'     => $posts->where('post_type', 'reel')->count(),
            'video'    => $posts->where('post_type', 'video')->count(),
            'carousel' => $posts->where('post_type', 'carousel')->count(),
            'copy'     => $posts->where('post_type', 'copy')->count(),
        ];

        // Group by Platform
        $postsByPlatform = [];
        foreach (ContentPost::PLATFORMS as $key => $meta) {
            $count = $posts->where('platform', $key)->count();
            if ($count > 0) {
                $postsByPlatform[$key] = [
                    'label' => $meta['label'],
                    'count' => $count,
                    'color' => $meta['color'],
                    'icon'  => $meta['icon'],
                ];
            }
        }

        // 2. Performance Marketing & Ad Campaigns
        $campaigns = Campaign::where('client_id', $client->id)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                  ->orWhere(function ($sub) use ($startDate, $endDate) {
                      $sub->where('status', 'active')
                          ->where('created_at', '<=', $endDate);
                  });
            })
            ->orderBy('start_date', 'asc')
            ->get();

        $totalBudget = $campaigns->sum('budget');
        $totalSpend = $campaigns->sum('spend');
        $totalImpressions = $campaigns->sum('impressions');
        $totalClicks = $campaigns->sum('clicks');
        $totalLeads = $campaigns->sum('leads_count');
        $totalRevenue = $campaigns->sum('revenue');

        $overallCtr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0.0;
        $overallCpl = $totalLeads > 0 ? round($totalSpend / $totalLeads, 2) : 0.0;
        $overallCpc = $totalClicks > 0 ? round($totalSpend / $totalClicks, 2) : 0.0;
        $overallRoas = $totalSpend > 0 ? round($totalRevenue / $totalSpend, 2) : 0.0;

        // Platform breakdown
        $campaignsByPlatform = [];
        foreach ($campaigns as $camp) {
            $p = $camp->platform;
            if (!isset($campaignsByPlatform[$p])) {
                $campaignsByPlatform[$p] = [
                    'label'       => Campaign::PLATFORMS[$p]['label'] ?? ucfirst($p),
                    'color'       => Campaign::PLATFORMS[$p]['color'] ?? '#64748B',
                    'spend'       => 0,
                    'impressions' => 0,
                    'clicks'      => 0,
                    'leads'       => 0,
                    'revenue'     => 0,
                ];
            }
            $campaignsByPlatform[$p]['spend'] += $camp->spend;
            $campaignsByPlatform[$p]['impressions'] += $camp->impressions;
            $campaignsByPlatform[$p]['clicks'] += $camp->clicks;
            $campaignsByPlatform[$p]['leads'] += $camp->leads_count;
            $campaignsByPlatform[$p]['revenue'] += $camp->revenue;
        }

        // 3. Creative Tasks & Milestones
        $tasks = Task::with('assignedTo')
            ->where('client_id', $client->id)
            ->whereNull('parent_task_id')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('deadline', [$startDate->toDateString(), $endDate->toDateString()])
                  ->orWhereBetween('updated_at', [$startDate, $endDate]);
            })
            ->orderBy('deadline', 'asc')
            ->get();

        $tasksTotal = $tasks->count();
        $tasksCompleted = $tasks->where('status', 'done')->count();
        $tasksInProgress = $tasks->whereIn('status', ['in_progress', 'review'])->count();
        $tasksTodo = $tasks->where('status', 'todo')->count();
        $tasksOverdue = $tasks->filter(fn($t) => $t->deadline && $t->deadline < today() && $t->status !== 'done')->count();

        // Checklist items
        $totalChecklistItems = 0;
        $completedChecklistItems = 0;
        foreach ($tasks as $task) {
            if (is_array($task->checklist)) {
                $totalChecklistItems += count($task->checklist);
                foreach ($task->checklist as $item) {
                    if (!empty($item['completed'])) $completedChecklistItems++;
                }
            }
        }

        // 4. Invoices & Billing
        $invoices = Invoice::where('client_id', $client->id)
            ->whereBetween('issued_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('issued_date', 'asc')
            ->get();

        $totalInvoiced = $invoices->sum('total');
        $totalAdvancePaid = $invoices->sum('advance_paid');
        $totalBalance = $invoices->sum('balance');
        $monthlyRetainerValue = $client->total_monthly_value;

        // 5. Client Overall Performance Score (0 - 100)
        $score = 75; // baseline
        if ($postsCount > 0) {
            $deliveryRatio = $postsPublished / $postsCount;
            $score += ($deliveryRatio >= 0.8 ? 10 : ($deliveryRatio < 0.5 ? -10 : 0));
        }
        if ($totalSpend > 0) {
            $score += ($overallRoas >= 3.0 ? 10 : ($overallRoas < 1.5 ? -10 : 5));
        }
        if ($tasksTotal > 0) {
            $taskRatio = $tasksCompleted / $tasksTotal;
            $score += ($taskRatio >= 0.8 ? 8 : -4);
        }
        if ($totalBalance > 0) {
            $score -= 3;
        }
        $performanceScore = max(40, min(100, $score));

        // Rating Grade
        $grade = 'A';
        $gradeColor = 'text-emerald-500';
        $gradeBadge = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        if ($performanceScore >= 90) {
            $grade = 'A+';
            $gradeColor = 'text-emerald-600';
            $gradeBadge = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        } elseif ($performanceScore >= 80) {
            $grade = 'A';
            $gradeColor = 'text-emerald-500';
            $gradeBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($performanceScore >= 70) {
            $grade = 'B+';
            $gradeColor = 'text-blue-500';
            $gradeBadge = 'bg-blue-100 text-blue-800 border-blue-300';
        } elseif ($performanceScore >= 60) {
            $grade = 'B';
            $gradeColor = 'text-amber-500';
            $gradeBadge = 'bg-amber-100 text-amber-800 border-amber-300';
        } else {
            $grade = 'C';
            $gradeColor = 'text-rose-500';
            $gradeBadge = 'bg-rose-100 text-rose-800 border-rose-300';
        }

        return [
            'client'                  => $client,
            'selectedMonth'           => $selectedMonth,
            'startDate'               => $startDate,
            'endDate'                 => $endDate,
            'posts'                   => $posts,
            'postsCount'              => $postsCount,
            'postsPublished'          => $postsPublished,
            'postsApproved'           => $postsApproved,
            'postsInReview'           => $postsInReview,
            'postsRevision'           => $postsRevision,
            'postsByFormat'           => $postsByFormat,
            'postsByPlatform'         => $postsByPlatform,
            'campaigns'               => $campaigns,
            'totalBudget'             => $totalBudget,
            'totalSpend'              => $totalSpend,
            'totalImpressions'        => $totalImpressions,
            'totalClicks'             => $totalClicks,
            'totalLeads'              => $totalLeads,
            'totalRevenue'            => $totalRevenue,
            'overallCtr'              => $overallCtr,
            'overallCpl'              => $overallCpl,
            'overallCpc'              => $overallCpc,
            'overallRoas'             => $overallRoas,
            'campaignsByPlatform'     => $campaignsByPlatform,
            'tasks'                   => $tasks,
            'tasksTotal'              => $tasksTotal,
            'tasksCompleted'          => $tasksCompleted,
            'tasksInProgress'         => $tasksInProgress,
            'tasksTodo'               => $tasksTodo,
            'tasksOverdue'            => $tasksOverdue,
            'totalChecklistItems'     => $totalChecklistItems,
            'completedChecklistItems' => $completedChecklistItems,
            'invoices'                => $invoices,
            'totalInvoiced'           => $totalInvoiced,
            'totalAdvancePaid'        => $totalAdvancePaid,
            'totalBalance'            => $totalBalance,
            'monthlyRetainerValue'    => $monthlyRetainerValue,
            'performanceScore'        => $performanceScore,
            'grade'                   => $grade,
            'gradeColor'              => $gradeColor,
            'gradeBadge'              => $gradeBadge,
        ];
    }
}
