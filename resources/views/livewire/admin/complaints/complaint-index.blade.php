<div class="space-y-5">

    {{-- ============================================================
         SUB-TABS
    ============================================================ --}}
    <div class="flex items-center gap-6 border-b border-purple-500/15 text-sm">
        <span class="relative -mb-px border-b-2 border-purple-500 px-1 pb-3 font-semibold text-white">
            Complaints
        </span>
        <a href="{{ route('admin.queries.index') }}" class="pb-3 font-medium text-slate-400 transition hover:text-slate-200">Queries</a>
    </div>

    {{-- ============================================================
         HEADER — Title + KPIs Inline
    ============================================================ --}}
    <div class="flex flex-wrap items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Complaints Management</h1>
            <p class="mt-1 text-sm text-slate-400">Manage all customer complaints and responses.</p>
        </div>

        {{-- Inline KPI grid --}}
        @php
            $total     = $complaints->total() ?? $complaints->count();
            $unread    = \App\Models\Complaint::where('is_read', false)->count();
            $inProgress = \App\Models\Complaint::whereIn('status', ['open', 'in_progress'])->count();
            $resolved  = \App\Models\Complaint::where('status', 'resolved')->count();
            $flagged   = \App\Models\Complaint::where('status', 'flagged')->count();

            $kpis = [
                ['label' => 'Total Complaints', 'value' => $total,     'color' => 'text-purple-300'],
                ['label' => 'Unread',           'value' => $unread,    'color' => 'text-red-400'],
                ['label' => 'In Progress',      'value' => $inProgress,'color' => 'text-blue-400'],
                ['label' => 'Resolved',         'value' => $resolved,  'color' => 'text-emerald-400'],
                ['label' => 'Flagged',          'value' => $flagged,   'color' => 'text-amber-400'],
            ];
        @endphp

        <div class="flex flex-wrap items-center gap-2">
            @foreach ($kpis as $kpi)
                <div class="rounded-xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] px-4 py-2.5 text-center shadow shadow-purple-950/20 backdrop-blur-sm">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400">{{ $kpi['label'] }}</p>
                    <p class="mt-0.5 text-xl font-bold {{ $kpi['color'] }}">{{ $kpi['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================================
         TABLE CARD
    ============================================================ --}}
    <div class="overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] shadow-lg shadow-purple-950/30 backdrop-blur-sm">

            {{-- Filter row --}}
            <div class="border-b border-purple-500/10 p-4">
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <div class="min-w-[260px] flex-1">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email or complaint..."
                                   class="block w-full rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-10 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                            <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <div class="relative">
                            <select wire:model.live="status"
                                    class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                                <option value="" class="bg-slate-900">All Status</option>
                                @foreach (\App\Enums\ComplaintStatus::cases() as $s)
                                    <option value="{{ $s->value }}" class="bg-slate-900">{{ ucfirst(str_replace('_', ' ', $s->value)) }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Filter button --}}
                    @if ($search !== '' || $status !== null)
                        <button type="button" wire:click="clearFilters"
                                class="ml-auto inline-flex items-center gap-1.5 rounded-lg border border-red-500/20 bg-slate-900/60 px-3.5 py-2.5 text-sm font-medium text-red-300 transition hover:border-red-500/40 hover:text-red-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear Filters
                        </button>
                    @endif

                    {{-- Export --}}
                    <a href="{{ route('admin.complaints.export') }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-purple-500/30 bg-purple-500/10 px-3.5 py-2.5 text-sm font-semibold text-purple-300 transition hover:bg-purple-500/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        Export Report
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-purple-500/10 text-[11px] uppercase tracking-wider text-slate-400">
                            <th class="px-4 py-4 font-medium">Full Name</th>
                            <th class="px-4 py-4 font-medium">Email</th>
                            <th class="px-4 py-4 font-medium">Complaint Description</th>
                            <th class="px-4 py-4 font-medium">Status</th>
                            <th class="px-4 py-4 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($complaints as $complaint)
                            @php
                                $statusVal = $complaint->status->value;
                                $pill = match($statusVal) {
                                    'new'         => ['bg' => 'bg-blue-500/15',    'text' => 'text-blue-300',    'ring' => 'ring-blue-500/30',    'label' => 'New'],
                                    'open',
                                    'in_progress' => ['bg' => 'bg-blue-500/15',    'text' => 'text-blue-300',    'ring' => 'ring-blue-500/30',    'label' => 'In Progress'],
                                    'flagged'     => ['bg' => 'bg-amber-500/15',   'text' => 'text-amber-300',   'ring' => 'ring-amber-500/30',   'label' => 'Flagged'],
                                    'resolved'    => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Resolved'],
                                    default       => ['bg' => 'bg-slate-500/15',   'text' => 'text-slate-300',   'ring' => 'ring-slate-500/30',   'label' => ucfirst($statusVal)],
                                };
                            @endphp
                            <tr class="cursor-pointer transition hover:bg-white/[0.02]"
                                wire:click="openReply({{ $complaint->id }})">
                                {{-- Full Name + Unread dot/badge --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if (! $complaint->is_read)
                                            <span class="h-2 w-2 shrink-0 rounded-full bg-red-500"></span>
                                        @endif
                                        <span class="text-sm font-medium text-slate-100">{{ $complaint->full_name }}</span>
                                        @if (! $complaint->is_read)
                                            <span class="inline-flex items-center rounded-md bg-purple-500/15 px-1.5 py-0.5 text-[9px] font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30">Unread</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="text-sm text-slate-300">{{ $complaint->email }}</span>
                                </td>

                                {{-- Description --}}
                                <td class="px-4 py-3">
                                    <p class="max-w-[300px] truncate text-sm text-slate-300">
                                        {{ \Illuminate\Support\Str::limit($complaint->description, 60) }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $pill['bg'] }} {{ $pill['text'] }} {{ $pill['ring'] }}">
                                        {{ $pill['label'] }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="text-xs font-medium text-slate-300">{{ $complaint->created_at->format('M d, Y') }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $complaint->created_at->format('h:i A') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10 ring-1 ring-inset ring-purple-500/30">
                                            <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-200">No complaints yet</p>
                                        <p class="mt-1 text-xs text-slate-500">Complaints submitted from the website will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-purple-500/10 px-4 py-3">
                <div class="text-xs text-slate-500">
                    Showing {{ $complaints->firstItem() ?? 1 }} to {{ $complaints->lastItem() ?? $complaints->count() }} of {{ $complaints->total() ?? $complaints->count() }} complaints
                </div>

                <div class="flex items-center gap-3">
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

                    @if (method_exists($complaints, 'hasPages') && $complaints->hasPages())
                        <div class="flex items-center gap-1">
                            <button wire:click="gotoPage(1)" @if ($complaints->onFirstPage()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/></svg>
                            </button>
                            <button wire:click="previousPage" @if ($complaints->onFirstPage()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>

                            @php
                                $current = $complaints->currentPage();
                                $last = $complaints->lastPage();
                                $start = max(1, $current - 2);
                                $end = min($last, $current + 2);
                            @endphp

                            @for ($p = $start; $p <= $end; $p++)
                                <button wire:click="gotoPage({{ $p }})"
                                        @class([
                                            'flex h-7 w-7 items-center justify-center rounded-lg text-xs font-semibold',
                                            'bg-purple-600 text-white shadow-lg shadow-purple-900/40' => $current === $p,
                                            'bg-slate-900/60 text-slate-300 ring-1 ring-inset ring-purple-500/20 hover:bg-slate-800' => $current !== $p,
                                        ])>{{ $p }}</button>
                            @endfor

                            @if ($end < $last)
                                <span class="px-1 text-slate-500">…</span>
                                <button wire:click="gotoPage({{ $last }})" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-900/60 text-xs font-semibold text-slate-300 ring-1 ring-inset ring-purple-500/20 hover:bg-slate-800">{{ $last }}</button>
                            @endif

                            <button wire:click="nextPage" @if (! $complaints->hasMorePages()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                            <button wire:click="gotoPage({{ $last }})" @if (! $complaints->hasMorePages()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 4.5l7.5 7.5-7.5 7.5m6-15l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
    </div>

    {{-- ============================================================
         FULL-WIDTH MODAL OVERLAY
    ============================================================ --}}
    @if ($showReplyModal && $selectedComplaint)
        @php $c = $selectedComplaint; @endphp

        {{-- Backdrop --}}
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 p-4 pt-[5vh] backdrop-blur-sm"
             x-data @click.self="$wire.closeModal()">

            {{-- Modal Panel --}}
            <div class="w-full max-w-5xl rounded-2xl border border-purple-500/20 bg-gradient-to-br from-slate-900 via-slate-900 to-purple-950/40 shadow-2xl shadow-purple-950/50">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-purple-500/15 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/15 ring-1 ring-inset ring-purple-500/30">
                            <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Complaint #CMP-{{ str_pad($c->id, 6, '0', STR_PAD_LEFT) }}</h2>
                            <p class="text-xs text-slate-400">{{ $c->full_name }} &middot; {{ $c->email }}</p>
                        </div>
                        @if (! $c->is_read)
                            <span class="inline-flex items-center rounded-md bg-red-500/15 px-2 py-0.5 text-[10px] font-semibold text-red-300 ring-1 ring-inset ring-red-500/30">Unread</span>
                        @endif
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset
                            {{ match($c->status->value) {
                                'new'      => 'bg-blue-500/15 text-blue-300 ring-blue-500/30',
                                'open'     => 'bg-blue-500/15 text-blue-300 ring-blue-500/30',
                                'flagged'  => 'bg-amber-500/15 text-amber-300 ring-amber-500/30',
                                'resolved' => 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30',
                                default    => 'bg-slate-500/15 text-slate-300 ring-slate-500/30',
                            } }}">{{ ucfirst(str_replace('_', ' ', $c->status->value)) }}</span>
                    </div>
                    <button type="button" wire:click="closeModal"
                            class="rounded-lg p-2 text-slate-400 transition hover:bg-white/5 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body: 2-column --}}
                <div class="grid grid-cols-1 gap-0 lg:grid-cols-5">

                    {{-- LEFT: Details + Conversation --}}
                    <div class="max-h-[75vh] space-y-5 overflow-y-auto border-r border-purple-500/10 p-6 lg:col-span-3">

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Full Name</p>
                                <p class="mt-1 text-sm font-semibold text-slate-100">{{ $c->full_name }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Email</p>
                                <p class="mt-1 truncate text-sm font-semibold text-slate-100">{{ $c->email }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Phone</p>
                                <p class="mt-1 text-sm font-semibold text-slate-100">{{ $c->phone ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Submitted</p>
                                <p class="mt-1 text-sm font-semibold text-slate-100">{{ $c->created_at->format('M d, Y') }}</p>
                                <p class="text-[10px] text-slate-500">{{ $c->created_at->format('h:i A') }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-purple-300">Description</h3>
                            <p class="text-sm leading-relaxed text-slate-300">{{ $c->description }}</p>
                        </div>

                        {{-- Attachments --}}
                        @if ($c->attachments && $c->attachments->count() > 0)
                            <div>
                                <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-purple-300">Attachments ({{ $c->attachments->count() }})</h3>
                                <div class="space-y-2">
                                    @foreach ($c->attachments as $att)
                                        @php
                                            $ext = strtolower(pathinfo($att->original_name, PATHINFO_EXTENSION));
                                            $isPdf = $ext === 'pdf';
                                            $isImg = in_array($ext, ['png', 'jpg', 'jpeg', 'webp']);
                                        @endphp
                                        <div class="flex items-center justify-between rounded-lg bg-slate-800/40 p-3 ring-1 ring-inset ring-purple-500/10">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $isPdf ? 'bg-red-500/15 text-red-300' : ($isImg ? 'bg-blue-500/15 text-blue-300' : 'bg-purple-500/15 text-purple-300') }}">
                                                    <span class="text-[10px] font-bold">{{ strtoupper($ext) }}</span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-xs font-medium text-slate-100">{{ $att->original_name }}</p>
                                                    <p class="text-[10px] text-slate-500">{{ number_format($att->size_bytes / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- RIGHT: Reply + Actions --}}
                    <div class="max-h-[75vh] space-y-5 overflow-y-auto p-6 lg:col-span-2">

                        {{-- Status Control --}}
                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Status</h3>
                            <select wire:change="setStatus({{ $c->id }}, $event.target.value)"
                                    class="w-full appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                                @foreach (\App\Enums\ComplaintStatus::cases() as $s)
                                    <option value="{{ $s->value }}" @selected($c->status->value === $s->value) class="bg-slate-900 text-slate-100">
                                        {{ ucfirst(str_replace('_', ' ', $s->value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Quick Actions</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" wire:click="setStatus({{ $c->id }}, 'resolved')"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-3 py-2.5 text-xs font-semibold text-emerald-300 transition hover:bg-emerald-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Resolved
                                </button>
                                <button type="button" wire:click="setStatus({{ $c->id }}, 'flagged')"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-2.5 text-xs font-semibold text-amber-300 transition hover:bg-amber-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"/></svg>
                                    Flag
                                </button>
                                <button type="button" wire:click="markUnread({{ $c->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-500/30 bg-blue-500/10 px-3 py-2.5 text-xs font-semibold text-blue-300 transition hover:bg-blue-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75"/></svg>
                                    Mark Unread
                                </button>
                                <button type="button" wire:click="delete({{ $c->id }})" wire:confirm="Delete this complaint?"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2.5 text-xs font-semibold text-red-300 transition hover:bg-red-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M4.772 5.79c.34-.059.68-.114 1.022-.165"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>

                        {{-- Timestamps --}}
                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Timeline</h3>
                            <div class="space-y-2 text-xs text-slate-400">
                                <div class="flex items-center justify-between"><span>Created</span><span class="font-medium text-slate-200">{{ $c->created_at->format('M d, Y h:i A') }}</span></div>
                                <div class="flex items-center justify-between"><span>Updated</span><span class="font-medium text-slate-200">{{ $c->updated_at->format('M d, Y h:i A') }}</span></div>
                                @if ($c->read_at)
                                    <div class="flex items-center justify-between"><span>First Read</span><span class="font-medium text-slate-200">{{ $c->read_at->format('M d, Y h:i A') }}</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
