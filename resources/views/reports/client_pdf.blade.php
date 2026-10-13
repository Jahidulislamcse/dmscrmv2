<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Monthly Client Performance Report — {{ $client->name }}</title>
    <style>
        @page {
            margin: 28px 32px 35px 32px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .agency-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .agency-sub {
            font-size: 9px;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        .meta-box {
            text-align: right;
        }
        .report-badge {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
        }
        .client-banner {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin: 14px 0;
        }
        .client-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }
        .section-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 4px;
            margin: 14px 0 8px 0;
        }
        .kpi-table {
            margin: 8px 0;
        }
        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }
        .kpi-label {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .kpi-val {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        .kpi-sub {
            font-size: 8px;
            color: #10b981;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            margin-top: 6px;
            border: 1px solid #e2e8f0;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 6px;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .data-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-info {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        .badge-gray {
            background-color: #f1f5f9;
            color: #475569;
        }
        .strategy-box {
            background-color: #0f172a;
            color: #ffffff;
            border-radius: 6px;
            padding: 10px 12px;
            margin-top: 14px;
        }
        .strategy-title {
            font-size: 10px;
            font-weight: bold;
            color: #f59e0b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .sign-table {
            margin-top: 25px;
        }
        .sign-line {
            border-bottom: 1px solid #94a3b8;
            width: 160px;
            height: 20px;
            margin-top: 25px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Header / Letterhead -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="agency-title">{{ $agencySettings['agency_name'] ?? 'DMS Agency' }}</div>
                <div class="agency-sub">Digital Marketing & Creative Advertising Solutions</div>
                <div style="font-size: 8px; color: #64748b; margin-top: 3px;">
                    {{ $agencySettings['agency_address'] ?? 'Dhaka, Bangladesh' }} · {{ $agencySettings['agency_phone'] ?? '' }}<br>
                    Tax BIN: {{ $agencySettings['tax_id'] ?? 'BIN-992019481' }} · {{ $agencySettings['agency_email'] ?? 'info@dmsagency.com' }}
                </div>
            </td>
            <td style="width: 40%;" class="meta-box">
                <span class="report-badge">Monthly Performance Report</span>
                <div style="font-size: 12px; font-weight: bold; margin-top: 4px;">{{ $startDate->format('F Y') }}</div>
                <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
                    Ref: <strong>DMS-REP-{{ $startDate->format('Ym') }}-{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</strong><br>
                    Date: {{ now()->format('d M Y') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Client Dossier Banner -->
    <div class="client-banner">
        <table style="width: 100%;">
            <tr>
                <td style="width: 65%;">
                    <div class="client-name">{{ $client->name }}</div>
                    <div style="font-size: 9px; color: #475569;">{{ $client->company ?? 'Commercial Client' }} · {{ $client->location ?? 'Bangladesh' }}</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
                        Email: {{ $client->email ?? 'N/A' }} | Phone: {{ $client->phone ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 35%; text-align: right; vertical-align: top;">
                    <div style="font-size: 8px; font-weight: bold; color: #64748b; text-transform: uppercase;">Performance Rating</div>
                    <div style="font-size: 14px; font-weight: bold; color: #047857; margin-top: 2px;">Grade {{ $grade }} ({{ $performanceScore }}/100)</div>
                    <div style="font-size: 8px; color: #64748b;">SMM: {{ $client->assignedSmm->name ?? 'Assigned Team' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Executive KPI Grid (4 Cards) -->
    <table class="kpi-table" style="width: 100%;">
        <tr>
            <td style="width: 25%; padding-right: 4px;">
                <div class="kpi-card">
                    <div class="kpi-label">Social Deliverables</div>
                    <div class="kpi-val">{{ $postsPublished }} / {{ $postsCount }}</div>
                    <div class="kpi-sub">{{ $postsCount > 0 ? round(($postsPublished / $postsCount) * 100) : 0 }}% Delivered</div>
                </div>
            </td>
            <td style="width: 25%; padding-right: 4px; padding-left: 4px;">
                <div class="kpi-card">
                    <div class="kpi-label">Ad Spend</div>
                    <div class="kpi-val">{{ $agencySettings['agency_currency'] ?? '৳' }}{{ number_format($totalSpend, 0) }}</div>
                    <div class="kpi-sub">{{ $totalLeads }} Leads Generated</div>
                </div>
            </td>
            <td style="width: 25%; padding-right: 4px; padding-left: 4px;">
                <div class="kpi-card">
                    <div class="kpi-label">Return on Ad Spend</div>
                    <div class="kpi-val" style="color: #059669;">{{ $overallRoas }}x</div>
                    <div class="kpi-sub" style="color: #475569;">{{ $agencySettings['agency_currency'] ?? '৳' }}{{ number_format($totalRevenue, 0) }} Gross</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 4px;">
                <div class="kpi-card">
                    <div class="kpi-label">Tasks / Milestones</div>
                    <div class="kpi-val">{{ $tasksCompleted }} / {{ $tasksTotal }}</div>
                    <div class="kpi-sub">{{ $tasksTotal > 0 ? round(($tasksCompleted / $tasksTotal) * 100) : 0 }}% Finished</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Section 1: Social Media Deliverables -->
    <div class="section-header">1. Social Media Editorial Deliverables</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 45%;">Post Title & Deliverable</th>
                <th style="width: 15%;">Format</th>
                <th style="width: 12%;">Platform</th>
                <th style="width: 13%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            @php 
                $fmt = App\Models\ContentPost::FORMATS[$post->post_type] ?? ['label' => ucfirst($post->post_type)];
            @endphp
            <tr>
                <td style="font-family: monospace;">{{ $post->date ? $post->date->format('d M Y') : 'TBD' }}</td>
                <td>
                    <strong>{{ $post->title }}</strong>
                    @if($post->client_feedback)
                        <div style="font-size: 8px; color: #b45309;">Feedback: {{ $post->client_feedback }}</div>
                    @endif
                </td>
                <td>{{ $fmt['label'] }}</td>
                <td>{{ ucfirst($post->platform) }}</td>
                <td class="text-center">
                    <span class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-gray' }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="color: #94a3b8;">No posts scheduled for this month.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section 2: Paid Ad Performance -->
    <div class="section-header">2. Performance Marketing & Paid Advertising</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 14%;">Platform</th>
                <th style="width: 32%;">Campaign Name</th>
                <th style="width: 14%;" class="text-right">Spend</th>
                <th style="width: 10%;" class="text-right">Clicks</th>
                <th style="width: 10%;" class="text-right">Leads</th>
                <th style="width: 10%;" class="text-right">CPL</th>
                <th style="width: 10%;" class="text-right">ROAS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaigns as $camp)
            <tr>
                <td><strong>{{ ucfirst($camp->platform) }}</strong></td>
                <td>{{ $camp->name }}</td>
                <td class="text-right" style="font-family: monospace;">{{ $agencySettings['agency_currency'] ?? '৳' }}{{ number_format($camp->spend, 0) }}</td>
                <td class="text-right">{{ number_format($camp->clicks) }}</td>
                <td class="text-right" style="font-weight: bold; color: #047857;">{{ $camp->leads_count }}</td>
                <td class="text-right">{{ $agencySettings['agency_currency'] ?? '৳' }}{{ $camp->cpl }}</td>
                <td class="text-right" style="font-weight: bold; color: #059669;">{{ $camp->roas }}x</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="color: #94a3b8;">No active campaigns recorded for this month.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section 3: Project Tasks & Financial Retainer -->
    <div class="section-header">3. Retainer Retrospective & Invoicing</div>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 6px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Active Retainer Scope</th>
                            <th class="text-right">Monthly Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->clientServices as $cs)
                        <tr>
                            <td>{{ $cs->custom_name ?: ($cs->service->name ?? 'Retainer Package') }}</td>
                            <td class="text-right">{{ $agencySettings['agency_currency'] ?? '৳' }}{{ number_format($cs->price * $cs->qty, 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center" style="color: #94a3b8;">No active services.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 6px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice Ref</th>
                            <th class="text-right">Billed</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                        <tr>
                            <td style="font-family: monospace;">{{ $inv->invoice_number }}</td>
                            <td class="text-right">{{ $agencySettings['agency_currency'] ?? '৳' }}{{ number_format($inv->total, 0) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $inv->status === 'paid' ? 'badge-success' : 'badge-gray' }}">
                                    {{ ucfirst($inv->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center" style="color: #94a3b8;">No invoices issued this month.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Strategic Summary Box -->
    <div class="strategy-box">
        <div class="strategy-title">Agency Strategy & Next Steps</div>
        <div style="font-size: 8.5px; line-height: 1.4; color: #cbd5e1;">
            Deliverables in {{ $startDate->format('F Y') }} achieved <strong>{{ $totalLeads }} customer leads</strong> at an average cost per acquisition of <strong>{{ $agencySettings['agency_currency'] ?? '৳' }}{{ $overallCpl }}</strong>, maintaining a <strong>{{ $overallRoas }}x ROAS</strong> across advertising channels. Recommendation for next month: Double down on video reels and reallocate Meta audience testing sets.
        </div>
    </div>

    <!-- Signatures -->
    <table class="sign-table" style="width: 100%;">
        <tr>
            <td style="width: 50%;">
                <div class="sign-line"></div>
                <div style="font-size: 9px; font-weight: bold; margin-top: 4px;">{{ auth()->user()->name }}</div>
                <div style="font-size: 8px; color: #64748b;">Account Executive · {{ $agencySettings['agency_name'] ?? 'Agency' }}</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="sign-line" style="margin-left: auto;"></div>
                <div style="font-size: 9px; font-weight: bold; margin-top: 4px;">{{ $client->name }}</div>
                <div style="font-size: 8px; color: #64748b;">Client Commercial Representative</div>
            </td>
        </tr>
    </table>

</body>
</html>
