@extends('layouts.app')

@section('title', 'Payment Reminders Hub')
@section('header_title', 'Client Payment Reminders & Collections')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: 'invoices', 
    openSendModal: false, 
    openTemplateModal: false,
    openEditTemplateModal: false,
    activeInvoice: {}, 
    activeTemplate: {},
    selectedTemplateId: '',
    selectedChannel: 'whatsapp',
    messageBody: '',
    parsedMessage: '',
    clientPhone: '',
    clientEmail: '',
    updatePreview() {
        if (!this.activeInvoice.id) return;
        fetch(`{{ route('reminders.preview') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                invoice_id: this.activeInvoice.id,
                template_id: this.selectedTemplateId,
                body: this.messageBody
            })
        })
        .then(res => res.json())
        .then(data => {
            this.parsedMessage = data.parsed_message;
            this.clientPhone = data.client_phone;
            this.clientEmail = data.client_email;
        });
    }
}">

    <!-- Top Action Bar & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Payment Reminders & Collections Hub</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Automate and dispatch payment reminders via WhatsApp, Email, and SMS for due and overdue client invoices.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Tab Navigation Switcher -->
            <div class="bg-slate-200/80 p-1 rounded-xl flex items-center gap-1 border border-slate-300/60 shadow-inner">
                <button @click="activeTab = 'invoices'"
                        :class="activeTab === 'invoices' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-3.5 py-1.5 rounded-lg text-xs flex items-center gap-2 transition-all">
                    <i class="fa fa-bell text-amber-500"></i>
                    <span>Invoices Needing Reminder</span>
                </button>
                <button @click="activeTab = 'history'"
                        :class="activeTab === 'history' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-3.5 py-1.5 rounded-lg text-xs flex items-center gap-2 transition-all">
                    <i class="fa fa-history text-slate-500"></i>
                    <span>Sent Reminders Log</span>
                </button>
                <button @click="activeTab = 'templates'"
                        :class="activeTab === 'templates' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-3.5 py-1.5 rounded-lg text-xs flex items-center gap-2 transition-all">
                    <i class="fa fa-file-code text-slate-500"></i>
                    <span>Message Templates</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Overview Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-paper-plane"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reminders Sent</span>
                <h4 class="text-lg font-extrabold text-slate-900">{{ $totalSent }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-file-invoice-dollar"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Unpaid / Partial Invoices</span>
                <h4 class="text-lg font-extrabold text-blue-600">{{ $unpaidInvoices->count() }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl {{ $overdueInvoices->count() > 0 ? 'bg-rose-500/10 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-bold text-lg">
                <i class="fa fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Overdue Alerts</span>
                <h4 class="text-lg font-extrabold {{ $overdueInvoices->count() > 0 ? 'text-rose-600' : 'text-slate-700' }}">{{ $overdueInvoices->count() }}</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold text-lg">
                <i class="fa fa-vault"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Outstanding</span>
                <h4 class="text-lg font-extrabold text-emerald-700">৳{{ number_format($totalOutstanding, 2) }}</h4>
            </div>
        </div>
    </div>

    <!-- TAB 1: INVOICES NEEDING REMINDER -->
    <div x-show="activeTab === 'invoices'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa fa-clock text-amber-500"></i> Unpaid & Due Client Invoices
                </h3>
                <span class="text-xs text-slate-500 font-medium">Select any invoice below to compose & dispatch a payment reminder.</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">Invoice #</th>
                            <th class="py-3.5 px-5">Client / Contact</th>
                            <th class="py-3.5 px-5">Due Date</th>
                            <th class="py-3.5 px-5">Total Amount</th>
                            <th class="py-3.5 px-5">Balance Due</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Dispatch Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @forelse($unpaidInvoices as $inv)
                        @php
                            $isOverdue = $inv->due_date && $inv->due_date->isPast();
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="py-3.5 px-5 font-bold font-mono text-slate-900">
                                <a href="{{ route('invoices.show', $inv->id) }}" class="text-amber-600 hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900">{{ $inv->client->name ?? 'Client' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $inv->client->company ?? '' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $inv->client->phone ?? '' }} | {{ $inv->client->email ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-5 font-mono">
                                <span class="{{ $isOverdue ? 'text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-200' : 'text-slate-700' }}">
                                    {{ $inv->due_date ? $inv->due_date->format('M d, Y') : 'Immediate' }}
                                    @if($isOverdue)
                                        <i class="fa fa-triangle-exclamation ml-1"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-900">
                                ৳{{ number_format($inv->total, 2) }}
                            </td>
                            <td class="py-3.5 px-5 font-mono font-extrabold text-rose-600">
                                ৳{{ number_format($inv->balance, 2) }}
                            </td>
                            <td class="py-3.5 px-5">
                                @if($isOverdue)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">Overdue</span>
                                @elseif($inv->status === 'partial')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">Partial</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300">Unpaid</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <button type="button" 
                                        @click="
                                            activeInvoice = @js($inv); 
                                            selectedTemplateId = '{{ $templates->first()->id ?? '' }}';
                                            messageBody = '{{ addslashes($templates->first()->body ?? '') }}';
                                            openSendModal = true;
                                            $nextTick(() => updatePreview());
                                        "
                                        class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs shadow-sm transition-all inline-flex items-center gap-1.5">
                                    <i class="fa fa-paper-plane"></i> Send Reminder
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa fa-circle-check text-3xl mb-2 text-emerald-500 block"></i>
                                <span class="font-bold text-slate-700">All invoices paid up! No pending reminders needed.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: SENT REMINDERS LOG HISTORY -->
    <div x-show="activeTab === 'history'" x-cloak class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa fa-history text-slate-500"></i> Dispatch & Communication History Log
                </h3>

                <form action="{{ route('reminders.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="history">
                    <select name="channel" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold">
                        <option value="">All Channels</option>
                        <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="manual" {{ request('channel') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">Invoice #</th>
                            <th class="py-3.5 px-5">Client Name</th>
                            <th class="py-3.5 px-5">Channel</th>
                            <th class="py-3.5 px-5">Message Body Preview</th>
                            <th class="py-3.5 px-5">Dispatched By</th>
                            <th class="py-3.5 px-5">Sent Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @forelse($sentReminders as $rem)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="py-3.5 px-5 font-bold font-mono text-slate-900">
                                <a href="{{ route('invoices.show', $rem->invoice_id) }}" class="text-amber-600 hover:underline">
                                    {{ $rem->invoice->invoice_number ?? 'Invoice' }}
                                </a>
                            </td>
                            <td class="py-3.5 px-5 font-bold text-slate-900">
                                {{ $rem->invoice->client->name ?? 'Client' }}
                            </td>
                            <td class="py-3.5 px-5">
                                @if($rem->channel === 'whatsapp')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
                                        <i class="fa-brands fa-whatsapp text-emerald-600"></i> WhatsApp
                                    </span>
                                @elseif($rem->channel === 'email')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300 inline-flex items-center gap-1">
                                        <i class="fa fa-envelope text-blue-600"></i> Email
                                    </span>
                                @elseif($rem->channel === 'sms')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-300 inline-flex items-center gap-1">
                                        <i class="fa fa-comment-sms text-purple-600"></i> SMS
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-300">Manual</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 max-w-xs truncate text-slate-600" title="{{ $rem->message }}">
                                {{ $rem->message }}
                            </td>
                            <td class="py-3.5 px-5 font-bold text-slate-800">
                                {{ $rem->sentBy->name ?? 'Staff' }}
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 font-mono">
                                {{ $rem->sent_at ? $rem膜.sent_at->format('M d, Y h:i A') : $rem->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No reminders recorded in history yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sentReminders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $sentReminders->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 3: MESSAGE TEMPLATES MANAGER -->
    <div x-show="activeTab === 'templates'" x-cloak class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Customizable Reminder Templates</h3>
            <button @click="openTemplateModal = true" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all flex items-center gap-2">
                <i class="fa fa-plus"></i> Create Template
            </button>
        </div>

        <!-- Available Tags Callout -->
        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 space-y-1">
            <h4 class="font-bold text-amber-950 flex items-center gap-1.5">
                <i class="fa fa-tags text-amber-600"></i> Dynamic Template Tags Supported:
            </h4>
            <div class="flex flex-wrap gap-2 pt-1 font-mono text-[11px]">
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{client_name}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{company_name}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{invoice_number}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{due_date}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{total_amount}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{balance_due}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{payment_link}</span>
                <span class="px-2 py-0.5 bg-white border border-amber-300 rounded text-slate-800">{agency_name}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($templates as $tmpl)
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3 flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-sm">{{ $tmpl->name }}</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase 
                            {{ $tmpl->type === 'whatsapp' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($tmpl->type === 'email' ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-purple-100 text-purple-800 border border-purple-300') }}">
                            {{ strtoupper($tmpl->type) }}
                        </span>
                    </div>
                    <pre class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs font-sans whitespace-pre-wrap text-slate-700 leading-relaxed max-h-36 overflow-y-auto">{{ $tmpl->body }}</pre>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-medium">Trigger: {{ $tmpl->days_before }} days before due</span>
                    <form action="{{ route('reminders.destroy-template', $tmpl->id) }}" method="POST" onsubmit="return confirm('Delete template?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold"><i class="fa fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- SEND PAYMENT REMINDER MODAL -->
    <div x-show="openSendModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="openSendModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Compose Payment Reminder</h3>
                    <p class="text-xs text-slate-500">Invoice #<span class="font-mono font-bold text-slate-800" x-text="activeInvoice.invoice_number"></span> — <span class="font-bold text-amber-600" x-text="activeInvoice.client ? activeInvoice.client.name : 'Client'"></span></p>
                </div>
                <button @click="openSendModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('reminders.send') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="invoice_id" :value="activeInvoice.id">

                <!-- Channel Selector -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Channel *</label>
                    <div class="grid grid-cols-4 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="channel" value="whatsapp" x-model="selectedChannel" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl border border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-slate-700 peer-checked:text-emerald-800 font-bold text-xs transition-all">
                                <i class="fa-brands fa-whatsapp text-emerald-600 block text-base mb-1"></i> WhatsApp
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="channel" value="email" x-model="selectedChannel" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl border border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-slate-700 peer-checked:text-blue-800 font-bold text-xs transition-all">
                                <i class="fa fa-envelope text-blue-600 block text-base mb-1"></i> Email
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="channel" value="sms" x-model="selectedChannel" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl border border-slate-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 text-slate-700 peer-checked:text-purple-800 font-bold text-xs transition-all">
                                <i class="fa fa-comment-sms text-purple-600 block text-base mb-1"></i> SMS
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="channel" value="manual" x-model="selectedChannel" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl border border-slate-200 peer-checked:border-slate-800 peer-checked:bg-slate-100 text-slate-700 peer-checked:text-slate-900 font-bold text-xs transition-all">
                                <i class="fa fa-file-lines text-slate-600 block text-base mb-1"></i> Log Note
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Template Selector -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Load Template</label>
                    <select name="template_id" x-model="selectedTemplateId" @change="
                        let t = @js($templates).find(x => x.id == selectedTemplateId);
                        if(t) { messageBody = t.body; updatePreview(); }
                    " class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                        <option value="">— Select Template (Optional) —</option>
                        @foreach($templates as $tmpl)
                            <option value="{{ $tmpl->id }}">{{ $tmpl->name }} ({{ strtoupper($tmpl->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Editable Message Template Body -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Message Body (With Tags) *</label>
                    <textarea name="message" x-model="messageBody" @input="updatePreview()" rows="3" required
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500"></textarea>
                </div>

                <!-- Live Message Preview Box -->
                <div class="bg-amber-50/60 p-3.5 rounded-xl border border-amber-200/80 space-y-1 text-xs">
                    <span class="block text-[10px] font-black text-amber-900 uppercase tracking-wider">Live Output Preview (What Client Receives):</span>
                    <div class="text-slate-800 whitespace-pre-line leading-relaxed font-sans bg-white p-2.5 rounded-lg border border-amber-200" x-text="parsedMessage"></div>
                </div>

                <!-- WhatsApp Option flag -->
                <template x-if="selectedChannel === 'whatsapp'">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="open_whatsapp" value="1" checked id="open_wa" class="rounded text-emerald-600">
                        <label for="open_wa" class="text-xs font-bold text-emerald-800">Launch WhatsApp Web immediately upon sending</label>
                    </div>
                </template>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openSendModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-xs rounded-xl shadow-md">
                        Dispatch & Record Reminder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CREATE TEMPLATE MODAL -->
    <div x-show="openTemplateModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4" @click.outside="openTemplateModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Create Reminder Template</h3>
                <button @click="openTemplateModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa fa-times"></i></button>
            </div>

            <form action="{{ route('reminders.store-template') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Template Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Overdue Final Demand Notice"
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Channel Type *</label>
                        <select name="type" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                            <option value="whatsapp">WhatsApp</option>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Trigger (Days Before Due)</label>
                        <input type="number" name="days_before" value="0"
                               class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Template Message Body *</label>
                    <textarea name="body" rows="4" required placeholder="Dear {client_name}, your invoice #{invoice_number} is due..."
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-amber-500"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openTemplateModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md">Create Template</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
