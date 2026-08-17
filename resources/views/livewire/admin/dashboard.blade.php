<div class="space-y-6">

    {{-- ===== HEADER ROW ===== --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">
                Welcome <span class="text-purple-400">Back!</span>
            </h1>
            <p class="mt-1 text-sm text-slate-400">Here's what's happening with Mars Station today.</p>
        </div>

        {{-- Date Badge --}}
        <div class="inline-flex items-center gap-3 rounded-xl border border-purple-500/20 bg-white/[0.03] px-4 py-2.5 text-sm backdrop-blur-sm">
            <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            <span class="font-medium text-slate-200">{{ now()->format('l, d M Y') }}</span>
            <span class="h-4 w-px bg-purple-500/30"></span>
            <span class="font-medium text-slate-300">{{ now()->format('H:i') }} <span class="text-xs text-slate-500">{{ now()->format('a') }}</span></span>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    @php
        $trend = fn (string $key) => ($this->trends[$key]['pct'] >= 0 ? '+' : '').$this->trends[$key]['pct'].'%';
        $trendUp = fn (string $key) => $this->trends[$key]['up'];

        $cards = [
            [
                'label' => 'Total Agreements',
                'value' => number_format($this->stats['totalAgreements']),
                'trend' => $trend('totalAgreements'),
                'trendUp' => $trendUp('totalAgreements'),
                'gradient' => 'from-purple-600/20 to-purple-900/5',
                'iconBg' => 'from-purple-500/30 to-purple-700/20 text-purple-300',
                'ring' => 'ring-purple-500/30',
                'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            ],
            [
                'label' => 'Active Subscriptions',
                'value' => number_format($this->stats['activeSubscriptions']),
                'trend' => $trend('activeSubscriptions'),
                'trendUp' => $trendUp('activeSubscriptions'),
                'gradient' => 'from-emerald-600/20 to-emerald-900/5',
                'iconBg' => 'from-emerald-500/30 to-emerald-700/20 text-emerald-300',
                'ring' => 'ring-emerald-500/30',
                'icon' => 'M2.25 12l8.954-8.955a1.5 1.5 0 012.121 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
            ],
            [
                'label' => 'Total Payments',
                'value' => $this->stats['totalPayments'],
                'trend' => $trend('totalPayments'),
                'trendUp' => $trendUp('totalPayments'),
                'gradient' => 'from-blue-600/20 to-blue-900/5',
                'iconBg' => 'from-blue-500/30 to-blue-700/20 text-blue-300',
                'ring' => 'ring-blue-500/30',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
            ],
            [
                'label' => 'Pending Signatures',
                'value' => number_format($this->stats['pendingSignatures']),
                'trend' => $trend('pendingSignatures'),
                'trendUp' => $trendUp('pendingSignatures'),
                'gradient' => 'from-amber-600/20 to-amber-900/5',
                'iconBg' => 'from-amber-500/30 to-amber-700/20 text-amber-300',
                'ring' => 'ring-amber-500/30',
                'icon' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10',
            ],
            [
                'label' => 'Pending Get Services',
                'value' => number_format($this->stats['pendingGetServices']),
                'trend' => $trend('pendingGetServices'),
                'trendUp' => $trendUp('pendingGetServices'),
                'gradient' => 'from-cyan-600/20 to-cyan-900/5',
                'iconBg' => 'from-cyan-500/30 to-cyan-700/20 text-cyan-300',
                'ring' => 'ring-cyan-500/30',
                'icon' => 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5',
            ],
            [
                'label' => 'Pending Reviews',
                'value' => number_format($this->stats['pendingReviews']),
                'trend' => $trend('pendingReviews'),
                'trendUp' => $trendUp('pendingReviews'),
                'gradient' => 'from-pink-600/20 to-pink-900/5',
                'iconBg' => 'from-pink-500/30 to-pink-700/20 text-pink-300',
                'ring' => 'ring-pink-500/30',
                'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
            ],
            [
                'label' => 'Pending Complaints',
                'value' => number_format($this->stats['pendingComplaints']),
                'trend' => $trend('pendingComplaints'),
                'trendUp' => $trendUp('pendingComplaints'),
                'gradient' => 'from-red-600/20 to-red-900/5',
                'iconBg' => 'from-red-500/30 to-red-700/20 text-red-300',
                'ring' => 'ring-red-500/30',
                'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            ],
            [
                'label' => 'Pending Queries',
                'value' => number_format($this->stats['pendingQueries']),
                'trend' => $trend('pendingQueries'),
                'trendUp' => $trendUp('pendingQueries'),
                'gradient' => 'from-yellow-600/20 to-yellow-900/5',
                'iconBg' => 'from-yellow-500/30 to-yellow-700/20 text-yellow-300',
                'ring' => 'ring-yellow-500/30',
                'icon' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <div class="group relative overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br {{ $card['gradient'] }} p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm transition hover:border-purple-500/30 hover:shadow-purple-900/40">
                {{-- Glow effect --}}
                <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5 blur-2xl transition group-hover:bg-white/10"></div>

                <div class="relative flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-300">{{ $card['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-white">{{ $card['value'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $card['iconBg'] }} ring-1 ring-inset {{ $card['ring'] }} shadow-lg">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>

                {{-- Trend --}}
                <div class="relative mt-3 flex items-center gap-1.5 text-xs">
                    @if ($card['trendUp'])
                        <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/>
                        </svg>
                        <span class="font-semibold text-emerald-400">{{ $card['trend'] }}</span>
                    @else
                        <svg class="h-3.5 w-3.5 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                        </svg>
                        <span class="font-semibold text-red-400">{{ $card['trend'] }}</span>
                    @endif
                    <span class="text-slate-500">from last 30 days</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== DATA TABLES ROW ===== --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- Recent Agreements --}}
        <x-panel title="Recent Agreements" icon="agreement" href="{{ route('admin.agreements.index') ?? '#' }}">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/5 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="pb-3 pt-1 font-medium">Agreement</th>
                        <th class="pb-3 pt-1 font-medium">Client</th>
                        <th class="pb-3 pt-1 font-medium">Status</th>
                        <th class="pb-3 pt-1 text-right font-medium">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($recentAgreements as $agreement)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="py-3 pr-2">
                                <div class="font-semibold text-slate-100">{{ $agreement->agreement_number ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ Str::limit($agreement->title, 22) }}</div>
                            </td>
                            <td class="py-3 pr-2">
                                <div class="font-medium text-slate-200">{{ $agreement->client_name }}</div>
                                <div class="truncate text-xs text-slate-500">{{ $agreement->client_email ?? '' }}</div>
                            </td>
                            <td class="py-3 pr-2">
                                <x-status-pill :status="$agreement->status->value ?? 'pending'"/>
                            </td>
                            <td class="py-3 text-right">
                                <div class="text-xs font-medium text-slate-300">{{ $agreement->created_at->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $agreement->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-slate-500">No agreements yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-panel>

        {{-- Recent Payments --}}
        <x-panel title="Recent Payments" icon="payment" href="{{ route('admin.payments.index') ?? '#' }}">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/5 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="pb-3 pt-1 font-medium">Payment ID</th>
                        <th class="pb-3 pt-1 font-medium">Agreement</th>
                        <th class="pb-3 pt-1 font-medium">Amount</th>
                        <th class="pb-3 pt-1 font-medium">Status</th>
                        <th class="pb-3 pt-1 text-right font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($recentPayments as $payment)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="py-3 pr-2 font-mono text-xs text-slate-300">{{ Str::limit($payment->stripe_id ?? 'pay_', 10, '...') }}</td>
                            <td class="py-3 pr-2">
                                <div class="font-semibold text-slate-100">{{ $payment->agreement?->agreement_number ?? 'N/A' }}</div>
                                <div class="truncate text-xs text-slate-500">{{ Str::limit($payment->agreement?->title, 15) }}</div>
                            </td>
                            <td class="py-3 pr-2 font-semibold text-slate-100">£{{ number_format(($payment->amount_pence ?? 0) / 100, 2) }}</td>
                            <td class="py-3 pr-2">
                                <x-status-pill :status="$payment->status->value ?? 'pending'"/>
                            </td>
                            <td class="py-3 text-right">
                                <div class="text-xs font-medium text-slate-300">{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-slate-500">No payments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-panel>

        {{-- Recent Get Services --}}
        <x-panel title="Recent Get Services" icon="service" href="{{ route('admin.get-services.index') ?? '#' }}">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-white/5 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="pb-3 pt-1 font-medium">Client</th>
                        <th class="pb-3 pt-1 font-medium">Service</th>
                        <th class="pb-3 pt-1 font-medium">Status</th>
                        <th class="pb-3 pt-1 text-right font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($recentGetServices as $request)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="py-3 pr-2">
                                <div class="font-medium text-slate-100">{{ $request->full_name }}</div>
                                <div class="truncate text-xs text-slate-500">{{ Str::limit($request->email, 20) }}</div>
                            </td>
                            <td class="py-3 pr-2 text-slate-300">{{ Str::limit($request->service ?? 'General', 18) }}</td>
                            <td class="py-3 pr-2">
                                <x-status-pill :status="$request->is_read ? 'read' : 'unread'"/>
                            </td>
                            <td class="py-3 text-right">
                                <div class="text-xs font-medium text-slate-300">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $request->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-slate-500">No requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-panel>
    </div>
</div
