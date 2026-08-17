<div class="space-y-6">

    {{-- ========== HEADER ========== --}}
    @unless ($embedded)
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Payment History</h1>
                <p class="mt-1 text-sm text-slate-400">View all successful payments and refunds.</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Date range indicator --}}
                <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-purple-500/20 bg-white/[0.03] px-3.5 py-2 text-sm text-slate-200 backdrop-blur-sm transition hover:bg-white/[0.06]">
                    <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span class="font-medium">{{ now()->subDays(30)->format('M d, Y') }} - {{ now()->format('M d, Y') }}</span>
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                <button type="button" wire:click="requestExport"
                        class="inline-flex items-center gap-2 rounded-lg border border-purple-500/30 bg-purple-500/10 px-3.5 py-2 text-sm font-semibold text-purple-300 backdrop-blur-sm transition hover:bg-purple-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    Export Payment Report
                </button>
            </div>
        </div>
    @else
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-lg font-bold tracking-tight text-white">Payment History</h2>
            <button type="button" wire:click="requestExport"
                    class="inline-flex items-center gap-2 rounded-lg border border-purple-500/30 bg-purple-500/10 px-3 py-1.5 text-xs font-semibold text-purple-300 transition hover:bg-purple-500/20">
                Export CSV
            </button>
        </div>
    @endunless

    {{-- ========== KPI CARDS ========== --}}
    @php
        $kpis = [
            [
                ...$dashboardStats['kpis'][0],
                'iconBg' => 'from-emerald-500/20 to-emerald-700/10 text-emerald-300 ring-emerald-500/30',
                'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75',
            ],
            [
                ...$dashboardStats['kpis'][1],
                'iconBg' => 'from-blue-500/20 to-blue-700/10 text-blue-300 ring-blue-500/30',
                'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
            ],
            [
                ...$dashboardStats['kpis'][2],
                'iconBg' => 'from-orange-500/20 to-orange-700/10 text-orange-300 ring-orange-500/30',
                'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
            ],
            [
                ...$dashboardStats['kpis'][3],
                'iconBg' => 'from-purple-500/20 to-purple-700/10 text-purple-300 ring-purple-500/30',
                'icon' => 'M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941',
            ],
            [
                'label' => 'Custom Range',
                'value' => \App\Support\Money::format($dashboardStats['received'] - $dashboardStats['refunds']),
                'trend' => null,
                'trendText' => 'Select custom date range',
                'trendUp' => true,
                'iconBg' => 'from-pink-500/20 to-pink-700/10 text-pink-300 ring-pink-500/30',
                'icon' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @foreach ($kpis as $kpi)
            <div class="group relative overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm transition hover:border-purple-500/30">
                <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/5 blur-2xl"></div>

                <div class="relative flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $kpi['iconBg'] }} ring-1 ring-inset">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $kpi['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-slate-400">{{ $kpi['label'] }}</p>
                        <p class="mt-1 text-xl font-bold tracking-tight text-white">{{ $kpi['value'] }}</p>
                        <p class="mt-1.5 text-[11px] {{ ($kpi['trend'] ?? null) !== null ? ($kpi['trend'] >= 0 ? 'text-emerald-400' : 'text-red-400') : 'text-slate-500' }}">
                            @if (($kpi['trend'] ?? null) !== null)<span class="font-semibold">{{ $kpi['trend'] >= 0 ? '↑' : '↓' }} {{ abs($kpi['trend']) }}%</span> @endif
                            <span class="text-slate-500">{{ $kpi['trendText'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ========== CHART + SUMMARY ========== --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- Payment Overview Chart --}}
        <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-bold text-white">Payment Overview</h3>
                <div class="relative">
                    <select class="appearance-none rounded-lg border-0 bg-slate-900/60 py-1.5 pl-3 pr-8 text-xs text-slate-200 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                        <option class="bg-slate-900">Daily</option>
                        <option class="bg-slate-900">Weekly</option>
                        <option class="bg-slate-900">Monthly</option>
                    </select>
                    <svg class="pointer-events-none absolute inset-y-0 right-2 my-auto h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </div>
            </div>

            @php $maxChartAmount = max(1, ...array_column($dashboardStats['chart'], 'amount')); @endphp
            <div class="flex h-64 items-end gap-3 border-b border-slate-800 px-2 pt-4">
                @foreach ($dashboardStats['chart'] as $point)
                    <div class="group relative flex h-full flex-1 items-end">
                        <div class="w-full rounded-t-md bg-gradient-to-t from-purple-600/50 to-purple-400 transition group-hover:from-purple-500 group-hover:to-purple-300"
                             style="height: {{ $point['amount'] > 0 ? max(3, ($point['amount'] / $maxChartAmount) * 100) : 1 }}%"
                             title="{{ $point['label'] }}: {{ \App\Support\Money::format($point['amount']) }}"></div>
                    </div>
                @endforeach
            </div>

            <div class="mt-2 flex justify-between px-2 text-[11px] text-slate-500">
                @foreach ($dashboardStats['chart'] as $point)
                    <span>{{ $point['label'] }}</span>
                @endforeach
            </div>
        </div>

        {{-- Payment Summary --}}
        <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
            <h3 class="mb-4 text-base font-bold text-white">Payment Summary</h3>
            <div class="space-y-4 text-sm">
                @php
                    $summary = [
                        ['label' => 'Total Payments',        'value' => \App\Support\Money::format($dashboardStats['received']), 'color' => 'text-emerald-400'],
                        ['label' => 'Total Refunds',         'value' => \App\Support\Money::format($dashboardStats['refunds']), 'color' => 'text-red-400'],
                        ['label' => 'Net Received',          'value' => \App\Support\Money::format($dashboardStats['received'] - $dashboardStats['refunds']), 'color' => 'text-amber-400'],
                        ['label' => 'Total Transactions',    'value' => $dashboardStats['transactions'], 'color' => 'text-blue-400'],
                        ['label' => 'Average Transaction Value', 'value' => \App\Support\Money::format((int) round($dashboardStats['average'])), 'color' => 'text-purple-400'],
                    ];
                @endphp
                @foreach ($summary as $row)
                    <div class="flex items-center justify-between border-b border-white/5 pb-3 last:border-0 last:pb-0">
                        <span class="text-slate-400">{{ $row['label'] }}</span>
                        <span class="font-semibold {{ $row['color'] }}">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========== TABLE CARD ========== --}}
    <div class="overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] shadow-lg shadow-purple-950/30 backdrop-blur-sm">

        {{-- Filter row --}}
        <div class="border-b border-purple-500/10 p-4">
            <div class="flex flex-wrap items-end gap-3">
                {{-- Search --}}
                <div class="min-w-[240px] flex-1">
                    <div class="relative">
                        <svg class="pointer-events-none absolute inset-y-0 left-3 my-auto h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by Agreement No., Client Name, Payment ID…"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 py-2.5 pl-10 pr-3 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-slate-500">Payment Method</label>
                    <div class="relative">
                        <select wire:model.live="paymentMethod" class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                            <option value="" class="bg-slate-900">All Methods</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method }}" class="bg-slate-900">{{ ucfirst($method) }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                {{-- Payment Status --}}
                <div>
                    <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-slate-500">Payment Status</label>
                    <div class="relative">
                        <select wire:model.live="status" class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                            <option value="" class="bg-slate-900">All Status</option>
                            @foreach (\App\Enums\PaymentStatus::cases() as $s)
                                <option value="{{ $s->value }}" class="bg-slate-900">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                {{-- Transaction Type --}}
                <div>
                    <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-slate-500">Transaction Type</label>
                    <div class="relative">
                        <select wire:model.live="paymentType" class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                            <option value="" class="bg-slate-900">All Types</option>
                            @foreach (\App\Enums\PaymentType::cases() as $type)
                                <option value="{{ $type->value }}" class="bg-slate-900">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                {{-- Reset Filters --}}
                <button type="button" wire:click="resetFilters"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-purple-500/20 bg-slate-900/60 px-3 py-2.5 text-sm font-medium text-slate-300 transition hover:border-purple-500/40 hover:text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Reset Filters
                </button>

                {{-- Custom Range Dates --}}
                @if ($dateRange === 'custom')
                    <div class="flex items-center gap-2">
                        <input type="date" wire:model.live="customFrom"
                               class="rounded-lg border-0 bg-slate-900/60 px-3 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500 [color-scheme:dark]"/>
                        <span class="text-sm text-slate-500">to</span>
                        <input type="date" wire:model.live="customTo"
                               class="rounded-lg border-0 bg-slate-900/60 px-3 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500 [color-scheme:dark]"/>
                    </div>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-purple-500/10 text-[11px] uppercase tracking-wider text-slate-500">
                        @foreach ([
                            ['label' => 'Agreement',      'field' => null],
                            ['label' => 'Client',         'field' => null],
                            ['label' => 'Payment ID',     'field' => null],
                            ['label' => 'Date / Time',    'field' => 'created_at'],
                            ['label' => 'Amount',         'field' => 'amount_pence'],
                            ['label' => 'Payment Method', 'field' => null],
                            ['label' => 'Refund Amount',  'field' => null],
                            ['label' => 'Refund Date',    'field' => null],
                            ['label' => '',               'field' => null], // Type badge column
                            ['label' => 'Action',         'field' => null],
                        ] as $col)
                            <th scope="col" class="px-4 py-3 text-left font-medium">
                                @if ($col['field'])
                                    <button wire:click="sortBy('{{ $col['field'] }}')" class="inline-flex items-center gap-1 transition hover:text-slate-200">
                                        {{ $col['label'] }}
                                        @if ($sortField === $col['field'])
                                            <span class="text-purple-400">{{ $sortDirection === 'desc' ? '↓' : '↑' }}</span>
                                        @endif
                                    </button>
                                @else
                                    {{ $col['label'] }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($payments as $payment)
                        <tr class="transition hover:bg-white/[0.02]">
                            {{-- Agreement --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="text-sm font-medium text-slate-100">{{ $payment->agreement?->title ?? '—' }}</div>
                                <div class="text-xs font-semibold text-purple-400">{{ $payment->agreement?->agreement_number }}</div>
                            </td>

                            {{-- Client --}}
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-slate-100">{{ $payment->agreement?->client_name ?? '—' }}</div>
                                <div class="text-xs text-slate-500">{{ $payment->agreement?->client_email ?? '' }}</div>
                            </td>

                            {{-- Payment ID --}}
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs">
                                <div class="text-slate-200">{{ $payment->stripe_payment_intent_id ?? 'Payment #' . $payment->id }}</div>
                                @if ($payment->stripe_invoice_id)
                                    <div class="text-slate-500">{{ $payment->stripe_invoice_id }}</div>
                                @endif
                            </td>

                            {{-- Date / Time --}}
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                <div class="text-slate-200">{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>

                            {{-- Amount + Status pill under --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="text-sm font-bold text-slate-100">{{ $payment->formattedAmount() }}</div>
                                @php
                                    $statusVal = $payment->status->value;
                                    $pill = match($statusVal) {
                                        'succeeded' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Paid'],
                                        'failed'    => ['bg' => 'bg-red-500/15',     'text' => 'text-red-300',     'ring' => 'ring-red-500/30',     'label' => 'Failed'],
                                        'refunded'  => ['bg' => 'bg-red-500/15',     'text' => 'text-red-300',     'ring' => 'ring-red-500/30',     'label' => 'Refunded'],
                                        'partially_refunded' => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-300', 'ring' => 'ring-amber-500/30', 'label' => 'Partial'],
                                        default     => ['bg' => 'bg-amber-500/15',  'text' => 'text-amber-300',  'ring' => 'ring-amber-500/30',  'label' => $payment->status->label()],
                                    };
                                @endphp
                                <span class="mt-1 inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset {{ $pill['bg'] }} {{ $pill['text'] }} {{ $pill['ring'] }}">
                                    {{ $pill['label'] }}
                                </span>
                            </td>

                            {{-- Payment Method --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="text-sm text-slate-200">{{ $payment->payment_method_brand ? ucfirst($payment->payment_method_brand) : ucfirst($payment->payment_method ?? '—') }}{{ $payment->payment_method_last4 ? ' •••• ' . $payment->payment_method_last4 : '' }}</div>
                                @if ($payment->payment_method)
                                    <span class="mt-1 inline-flex items-center gap-1 rounded-md bg-slate-800 px-2 py-0.5 text-[10px] font-semibold text-slate-300 ring-1 ring-inset ring-slate-700">{{ ucfirst($payment->payment_method) }}</span>
                                @endif
                            </td>

                            {{-- Refund Amount --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                @if ($payment->refunded_amount_pence > 0)
                                    <div class="text-sm font-semibold text-red-400">{{ $payment->formattedRefundedAmount() }}</div>
                                @else
                                    <span class="text-slate-600">—</span>
                                @endif
                            </td>

                            {{-- Refund Date --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                @if ($payment->refunds->first()?->processed_at)
                                    <div class="text-sm text-slate-200">{{ $payment->refunds->first()->processed_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $payment->refunds->first()->processed_at->format('h:i A') }}</div>
                                @else
                                    <span class="text-slate-600">—</span>
                                @endif
                            </td>

                            {{-- Type badge --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                @php
                                    $typeVal = $payment->type->value ?? 'full';
                                    $typePill = match($typeVal) {
                                        'full'         => ['bg' => 'bg-purple-500/15', 'text' => 'text-purple-300', 'ring' => 'ring-purple-500/30', 'label' => 'Full Payment'],
                                        'milestone'    => ['bg' => 'bg-orange-500/15', 'text' => 'text-orange-300', 'ring' => 'ring-orange-500/30', 'label' => 'Milestone'],
                                        'subscription' => ['bg' => 'bg-blue-500/15',   'text' => 'text-blue-300',   'ring' => 'ring-blue-500/30',   'label' => 'Subscription'],
                                        default        => ['bg' => 'bg-slate-500/15',  'text' => 'text-slate-300',  'ring' => 'ring-slate-500/30',  'label' => ucfirst($typeVal)],
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $typePill['bg'] }} {{ $typePill['text'] }} {{ $typePill['ring'] }}">
                                    {{ $typePill['label'] }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button type="button" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white/5 hover:text-purple-300" title="View">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white/5 hover:text-slate-200" title="More">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10 ring-1 ring-inset ring-purple-500/30">
                                        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-200">No payments found</p>
                                    <p class="mt-1 text-xs text-slate-500">Payments will appear here once clients complete a payment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-purple-500/10 px-4 py-3">
            <div class="text-xs text-slate-500">
                Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() ?? 0 }} payments
            </div>

            <div class="flex items-center gap-3">
                {{-- Per Page --}}
                <div class="relative">
                    <select class="appearance-none rounded-lg border-0 bg-slate-900/60 py-1.5 pl-3 pr-8 text-xs text-slate-200 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                        <option class="bg-slate-900">10 per page</option>
                        <option class="bg-slate-900">25 per page</option>
                        <option class="bg-slate-900">50 per page</option>
                    </select>
                    <svg class="pointer-events-none absolute inset-y-0 right-2 my-auto h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </div>

                @if ($payments->hasPages())
                    <div class="[&_button]:!bg-slate-900/60 [&_button]:!ring-purple-500/20 [&_button]:!text-slate-300 [&_span[aria-current]]:!bg-purple-600 [&_span[aria-current]]:!text-white">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Export Modal (unchanged) --}}
    <x-modal :show="'showExportModal'">
        <x-slot:title>Export Payments CSV</x-slot:title>
        <p class="text-sm text-slate-400">
            This export is generated in the background and emailed to you. Current filters:
            {{ $search ?: 'all agreements' }}, {{ $status ?: 'all statuses' }}, {{ $dateRange ?: 'all time' }}.
        </p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="$set('showExportModal', false)">Cancel</x-button>
            <x-button type="button" wire:click="exportCsv" wire:loading.attr="disabled" wire:target="exportCsv">Generate Export</x-button>
        </x-slot:footer>
    </x-modal>
</div>
