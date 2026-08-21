<div class="space-y-5">

    {{-- ============================================================
         SUB-TABS
    ============================================================ --}}
    <div class="flex items-center gap-6 border-b border-purple-500/15 text-sm">
        <a href="{{ route('admin.complaints.index') }}" class="pb-3 font-medium text-slate-400 transition hover:text-slate-200">
            Complaints
        </a>
        <span class="relative -mb-px border-b-2 border-purple-500 px-1 pb-3 font-semibold text-white">
            Queries
        </span>
    </div>

    {{-- ============================================================
         HEADER — Title + KPIs Inline
    ============================================================ --}}
    <div class="flex flex-wrap items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Queries Management</h1>
            <p class="mt-1 text-sm text-slate-400">Manage all customer queries and responses.</p>
        </div>

        @php
            $total     = $queries->total() ?? $queries->count();
            $unread    = \App\Models\Query::where('is_read', false)->count();
            $inProgress = \App\Models\Query::whereIn('status', ['open', 'new'])->count();
            $responded = \App\Models\Query::where('status', 'responded')->count();
            $flagged   = \App\Models\Query::where('status', 'flagged')->count();

            $kpis = [
                ['label' => 'Total Queries',  'value' => $total,      'color' => 'text-purple-300'],
                ['label' => 'Unread',         'value' => $unread,     'color' => 'text-red-400'],
                ['label' => 'In Progress',    'value' => $inProgress, 'color' => 'text-blue-400'],
                ['label' => 'Responded',      'value' => $responded,  'color' => 'text-emerald-400'],
                ['label' => 'Flagged',        'value' => $flagged,    'color' => 'text-amber-400'],
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
                    <div class="min-w-[260px] flex-1">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email or query..."
                                   class="block w-full rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-10 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                            <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <div class="relative">
                            <select wire:model.live="status"
                                    class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                                <option value="" class="bg-slate-900">All Status</option>
                                @foreach (\App\Enums\QueryStatus::cases() as $s)
                                    <option value="{{ $s->value }}" class="bg-slate-900">{{ ucfirst(str_replace('_', ' ', $s->value)) }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </div>
                    </div>

                    @if ($search !== '' || $status !== null)
                        <button type="button" wire:click="clearFilters"
                                class="ml-auto inline-flex items-center gap-1.5 rounded-lg border border-red-500/20 bg-slate-900/60 px-3.5 py-2.5 text-sm font-medium text-red-300 transition hover:border-red-500/40 hover:text-red-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear Filters
                        </button>
                    @endif

                    <a href="{{ route('admin.queries.export') }}"
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
                            <th class="px-4 py-4 font-medium">Query</th>
                            <th class="px-4 py-4 font-medium">Status</th>
                            <th class="px-4 py-4 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($queries as $query)
                            @php
                                $statusVal = $query->status->value;
                                $pill = match($statusVal) {
                                    'new'       => ['bg' => 'bg-blue-500/15',    'text' => 'text-blue-300',    'ring' => 'ring-blue-500/30',    'label' => 'New'],
                                    'open'      => ['bg' => 'bg-blue-500/15',    'text' => 'text-blue-300',    'ring' => 'ring-blue-500/30',    'label' => 'In Progress'],
                                    'flagged'   => ['bg' => 'bg-amber-500/15',   'text' => 'text-amber-300',   'ring' => 'ring-amber-500/30',   'label' => 'Flagged'],
                                    'responded' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Responded'],
                                    default     => ['bg' => 'bg-slate-500/15',   'text' => 'text-slate-300',   'ring' => 'ring-slate-500/30',   'label' => ucfirst($statusVal)],
                                };
                            @endphp
                            <tr class="cursor-pointer transition hover:bg-white/[0.02]"
                                wire:click="openReply({{ $query->id }})">
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if (! $query->is_read)
                                            <span class="h-2 w-2 shrink-0 rounded-full bg-red-500"></span>
                                        @endif
                                        <span class="text-sm font-medium text-slate-100">{{ $query->full_name }}</span>
                                        @if (! $query->is_read)
                                            <span class="inline-flex items-center rounded-md bg-purple-500/15 px-1.5 py-0.5 text-[9px] font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30">Unread</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="text-sm text-slate-300">{{ $query->email }}</span>
                                </td>

                                <td class="px-4 py-3">
                                    <p class="max-w-[300px] truncate text-sm text-slate-300">
                                        {{ \Illuminate\Support\Str::limit($query->query, 60) }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $pill['bg'] }} {{ $pill['text'] }} {{ $pill['ring'] }}">
                                        {{ $pill['label'] }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="text-xs font-medium text-slate-300">{{ $query->created_at->format('M d, Y') }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $query->created_at->format('h:i A') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10 ring-1 ring-inset ring-purple-500/30">
                                            <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-200">No queries yet</p>
                                        <p class="mt-1 text-xs text-slate-500">Queries submitted from the website will appear here.</p>
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
                    Showing {{ $queries->firstItem() ?? 1 }} to {{ $queries->lastItem() ?? $queries->count() }} of {{ $queries->total() ?? $queries->count() }} queries
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

                    @if (method_exists($queries, 'hasPages') && $queries->hasPages())
                        <div class="flex items-center gap-1">
                            <button wire:click="gotoPage(1)" @if ($queries->onFirstPage()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/></svg>
                            </button>
                            <button wire:click="previousPage" @if ($queries->onFirstPage()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>

                            @php
                                $current = $queries->currentPage();
                                $last = $queries->lastPage();
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

                            <button wire:click="nextPage" @if (! $queries->hasMorePages()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                            <button wire:click="gotoPage({{ $last }})" @if (! $queries->hasMorePages()) disabled @endif
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
    @if ($showReplyModal && $selectedQuery)
        @php $q = $selectedQuery; @endphp

        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 p-4 pt-[5vh] backdrop-blur-sm"
             x-data="{ contactOpen: false }" @click.self="$wire.closeModal()">

            <div class="w-full max-w-5xl rounded-2xl border border-purple-500/20 bg-gradient-to-br from-slate-900 via-slate-900 to-purple-950/40 shadow-2xl shadow-purple-950/50">

                <div class="flex items-center justify-between border-b border-purple-500/15 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/15 ring-1 ring-inset ring-purple-500/30">
                            <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Query #QRY-{{ str_pad($q->id, 6, '0', STR_PAD_LEFT) }}</h2>
                            <p class="text-xs text-slate-400">{{ $q->full_name }} &middot; {{ $q->email }}</p>
                        </div>
                        @if (! $q->is_read)
                            <span class="inline-flex items-center rounded-md bg-red-500/15 px-2 py-0.5 text-[10px] font-semibold text-red-300 ring-1 ring-inset ring-red-500/30">Unread</span>
                        @endif
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset
                            {{ match($q->status->value) {
                                'new'       => 'bg-blue-500/15 text-blue-300 ring-blue-500/30',
                                'open'      => 'bg-blue-500/15 text-blue-300 ring-blue-500/30',
                                'flagged'   => 'bg-amber-500/15 text-amber-300 ring-amber-500/30',
                                'responded' => 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30',
                                default     => 'bg-slate-500/15 text-slate-300 ring-slate-500/30',
                            } }}">{{ ucfirst(str_replace('_', ' ', $q->status->value)) }}</span>
                    </div>
                    <button type="button" wire:click="closeModal"
                            class="rounded-lg p-2 text-slate-400 transition hover:bg-white/5 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-0 lg:grid-cols-5">

                    <div class="max-h-[75vh] space-y-5 overflow-y-auto border-r border-purple-500/10 p-6 lg:col-span-3">

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Full Name</p>
                                <p class="mt-1 text-sm font-semibold text-slate-100">{{ $q->full_name }}</p>
                            </div>
                            <div class="cursor-pointer rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10 transition hover:bg-slate-800/80 hover:ring-purple-500/30"
                                 title="Click to view contact details"
                                 @click="contactOpen = true">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Email</p>
                                <p class="mt-1 truncate text-sm font-semibold text-slate-100">{{ $q->email }}</p>
                            </div>
                            <div class="cursor-pointer rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10 transition hover:bg-slate-800/80 hover:ring-purple-500/30"
                                 title="Click to view contact details"
                                 @click="contactOpen = true">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Phone</p>
                                <p class="mt-1 truncate text-sm font-semibold text-slate-100">{{ $q->phone ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Preferred Contact</p>
                                <p class="mt-1 text-sm font-semibold text-slate-100">{{ ucfirst($q->preferred_contact ?? '—') }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Submitted</p>
                                <p class="mt-1 text-sm font-semibold text-slate-100">{{ $q->created_at->format('M d, Y') }}</p>
                                <p class="text-[10px] text-slate-500">{{ $q->created_at->format('h:i A') }}</p>
                            </div>
                        </div>

                        @if (! empty($q->selected_services) && count($q->selected_services) > 0)
                            <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                                <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-emerald-300">Selected Services</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($q->selected_services as $service)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-3 py-1.5 text-xs font-medium text-emerald-300">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            {{ $service }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-purple-300">Query Description</h3>
                            <p class="text-sm leading-relaxed text-slate-300">{{ $q->query }}</p>
                        </div>

                        @if (($q->attachments ?? collect())->count() > 0)
                            <div>
                                <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-purple-300">Attachments ({{ $q->attachments->count() }})</h3>
                                <div class="space-y-2">
                                    @foreach ($q->attachments as $att)
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

                        <div>
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Conversation</h3>
                            <div class="max-h-64 space-y-3 overflow-y-auto pr-1 scrollbar-thin">
                                <div class="flex items-start gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-600/40 to-slate-800/40 text-[10px] font-bold text-white ring-1 ring-purple-500/30">
                                        {{ strtoupper(substr($q->full_name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 rounded-xl bg-slate-800/40 p-3 ring-1 ring-inset ring-purple-500/10">
                                        <div class="mb-1 flex items-center gap-2">
                                            <span class="text-xs font-semibold text-slate-200">{{ $q->full_name }} (Customer)</span>
                                            <span class="ml-auto text-[10px] text-slate-500">{{ $q->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        <p class="text-sm text-slate-300">{{ $q->query }}</p>
                                    </div>
                                </div>

                                @foreach ($q->messages ?? [] as $msg)
                                    @php $isAdmin = $msg->from_admin ?? false; @endphp
                                    <div class="flex items-start gap-2.5">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br {{ $isAdmin ? 'from-purple-500/40 to-purple-800/40' : 'from-slate-600/40 to-slate-800/40' }} text-[10px] font-bold text-white ring-1 ring-purple-500/30">
                                            {{ strtoupper(substr($isAdmin ? 'Mars Station' : $q->full_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 rounded-xl bg-slate-800/40 p-3 ring-1 ring-inset ring-purple-500/10">
                                            <div class="mb-1 flex items-center gap-2">
                                                <span class="text-xs font-semibold text-slate-200">{{ $isAdmin ? 'Mars Station (Admin)' : $q->full_name . ' (Customer)' }}</span>
                                                <span class="ml-auto text-[10px] text-slate-500">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                                            </div>
                                            <p class="text-sm text-slate-300">{{ $msg->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[75vh] space-y-5 overflow-y-auto p-6 lg:col-span-2">

                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Status</h3>
                            <select wire:change="setStatus({{ $q->id }}, $event.target.value)"
                                    class="w-full appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                                @foreach (\App\Enums\QueryStatus::cases() as $s)
                                    <option value="{{ $s->value }}" @selected($q->status->value === $s->value) class="bg-slate-900 text-slate-100">
                                        {{ ucfirst(str_replace('_', ' ', $s->value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Quick Actions</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" wire:click="setStatus({{ $q->id }}, 'responded')"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-3 py-2.5 text-xs font-semibold text-emerald-300 transition hover:bg-emerald-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Responded
                                </button>
                                <button type="button" wire:click="setStatus({{ $q->id }}, 'flagged')"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-2.5 text-xs font-semibold text-amber-300 transition hover:bg-amber-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"/></svg>
                                    Flag
                                </button>
                                <button type="button" wire:click="markUnread({{ $q->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-500/30 bg-blue-500/10 px-3 py-2.5 text-xs font-semibold text-blue-300 transition hover:bg-blue-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75"/></svg>
                                    Mark Unread
                                </button>
                                <button type="button" wire:click="delete({{ $q->id }})" wire:confirm="Delete this query?"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2.5 text-xs font-semibold text-red-300 transition hover:bg-red-500/20">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M4.772 5.79c.34-.059.68-.114 1.022-.165"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-800/30 p-4 ring-1 ring-inset ring-purple-500/10">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-purple-300">Timeline</h3>
                            <div class="space-y-2 text-xs text-slate-400">
                                <div class="flex items-center justify-between"><span>Created</span><span class="font-medium text-slate-200">{{ $q->created_at->format('M d, Y h:i A') }}</span></div>
                                <div class="flex items-center justify-between"><span>Updated</span><span class="font-medium text-slate-200">{{ $q->updated_at->format('M d, Y h:i A') }}</span></div>
                                @if ($q->read_at)
                                    <div class="flex items-center justify-between"><span>First Read</span><span class="font-medium text-slate-200">{{ $q->read_at->format('M d, Y h:i A') }}</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- CONTACT INFO MODAL --}}
            <div x-show="contactOpen" x-cloak
                 class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                 x-transition
                 @click.self="contactOpen = false"
                 @keydown.escape.window="contactOpen = false">

                <div class="w-full max-w-sm rounded-2xl border border-purple-500/20 bg-gradient-to-br from-slate-900 via-slate-900 to-purple-950/40 shadow-2xl shadow-purple-950/50">

                    {{-- Header --}}
                    <div class="flex items-center justify-between border-b border-purple-500/15 px-5 py-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-purple-300">Contact Info</h3>
                        <button type="button" @click="contactOpen = false"
                                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white/5 hover:text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Email + Phone --}}
                    <div class="space-y-3 p-5">

                        {{-- Email --}}
                        <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Email</p>
                                <span x-data="{ copied: false }">
                                    <button type="button" title="Copy email"
                                            @click="navigator.clipboard.writeText('{{ $q->email }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                            class="inline-flex items-center gap-1.5 rounded-md bg-purple-500/10 px-2.5 py-1 text-[10px] font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30 transition hover:bg-purple-500/20">
                                        <svg x-show="!copied" x-cloak class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                        <svg x-show="copied" x-cloak class="h-3 w-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                    </button>
                                </span>
                            </div>
                            <p class="mt-1.5 break-all text-sm font-semibold text-slate-100">{{ $q->email }}</p>
                        </div>

                        {{-- Phone --}}
                        <div class="rounded-xl bg-slate-800/50 p-3 ring-1 ring-inset ring-purple-500/10">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">Phone</p>
                                @if ($q->phone)
                                    <span x-data="{ copied: false }">
                                        <button type="button" title="Copy phone"
                                                @click="navigator.clipboard.writeText('{{ $q->phone }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-purple-500/10 px-2.5 py-1 text-[10px] font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30 transition hover:bg-purple-500/20">
                                            <svg x-show="!copied" x-cloak class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                            <svg x-show="copied" x-cloak class="h-3 w-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                        </button>
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1.5 break-all text-sm font-semibold text-slate-100">{{ $q->phone ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
