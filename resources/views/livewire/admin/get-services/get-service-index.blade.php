<div class="space-y-4">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="shrink-0">
            <h1 class="text-2xl font-bold text-white">Get Services Management</h1>
            <p class="mt-1 text-sm text-slate-300">Manage all service requests submitted through the website.</p>
        </div>
        <div class="flex max-w-full divide-x divide-purple-500/10 overflow-x-auto rounded-xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] scrollbar-none">
            @php
                $stats = [
                    'all' => ['Total Requests', 'text-white', 'bg-purple-500/15', '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />'],
                    'unread' => ['Unread', 'text-red-400', 'bg-red-500/15', '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />'],
                    'processing' => ['In Processing', 'text-blue-400', 'bg-blue-500/15', '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />'],
                    'completed' => ['Completed', 'text-emerald-400', 'bg-emerald-500/15', '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'signed' => ['Signed', 'text-purple-400', 'bg-purple-500/15', '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />'],
                ];
            @endphp
            @foreach($stats as $key => [$label, $color, $iconBg, $icon])
                <div class="flex min-w-36 items-center gap-3 px-5 py-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $iconBg }}">
                        <svg class="h-4 w-4 {{ $color }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">{!! $icon !!}</svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">{{ $label }}</p>
                        <p class="text-lg font-bold {{ $color }}">{{ $counts[$key] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] shadow-lg shadow-purple-950/30">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-purple-500/10 px-4 py-5">
            <div class="flex flex-wrap gap-2">
                @foreach(['all' => 'All Requests', 'unread' => 'Unread', 'processing' => 'In Processing', 'signed' => 'Signed', 'completed' => 'Completed'] as $key => $label)
                    <button wire:click="setFilter('{{ $key }}')"
                        @class([
                            'rounded-full border px-4 py-2 text-xs font-medium transition',
                            'border-purple-500/50 bg-purple-500/20 text-purple-200 shadow-lg shadow-purple-500/10' => $filter === $key,
                            'border-purple-500/10 bg-slate-900/40 text-slate-400 hover:border-purple-500/30 hover:text-slate-200' => $filter !== $key,
                        ])>
                        {{ $label }}
                        <span class="ml-1 rounded-full bg-white/10 px-1.5 py-0.5 text-[10px]">{{ $counts[$key] }}</span>
                    </button>
                @endforeach
            </div>
            <div class="flex gap-2">
                <input wire:model.live.debounce.300ms="search" placeholder="Search requests..."
                    class="w-44 rounded-lg border border-purple-500/15 bg-slate-900/60 px-3 py-2 text-xs text-white placeholder-slate-500 ring-1 ring-inset ring-purple-500/10 focus:border-purple-500/40 focus:outline-none">
                    <a href="{{ route('admin.get-services.export') }}" class="rounded-lg border border-purple-500/30 bg-purple-500/10 px-3 py-2 text-xs font-medium text-purple-300 transition hover:bg-purple-500/20">
                    ⇩ Export
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-purple-500/10 bg-purple-500/[0.05]">
                    <tr>
                        @foreach(['Full Name', 'Email', 'Phone', 'Company', 'Contact', 'Notes', 'Status', 'Date', 'Action'] as $head)
                            <th class="px-4 py-4 font-medium text-purple-300">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-500/10">
                    @forelse($requests as $request)
                        @php
                            $styles = [
                                'new' => 'border-blue-500/40 bg-blue-500/15 text-blue-300',
                                'processing' => 'border-amber-500/40 bg-amber-500/15 text-amber-300',
                                'signed' => 'border-purple-500/40 bg-purple-500/15 text-purple-300',
                                'completed' => 'border-emerald-500/40 bg-emerald-500/15 text-emerald-300',
                                'flagged' => 'border-red-500/40 bg-red-500/15 text-red-300',
                            ];
                        @endphp
                        <tr class="cursor-pointer transition hover:bg-purple-500/[.03]"
                            wire:click="selectRequest({{ $request->id }})">
                            <td class="px-4 py-4 font-medium text-slate-100">
                                @if(!$request->is_read)
                                    <span class="mr-2 inline-block h-2 w-2 rounded-full bg-red-500"></span>
                                @endif
                                {{ $request->full_name }}
                                @if(!$request->is_read)
                                    <span class="ml-2 rounded bg-purple-500/25 px-1.5 py-1 text-[10px] text-purple-200">New</span>
                                @endif
                            </td>
                            <td class="px-4 text-slate-300">{{ $request->email }}</td>
                            <td class="px-4 text-slate-300">{{ $request->phone ?: '—' }}</td>
                            <td class="px-4 text-slate-300">{{ $request->company ?: '—' }}</td>
                            <td class="px-4 text-slate-300">{{ ucfirst($request->preferred_contact) }}</td>
                            <td class="max-w-44 truncate px-4 text-slate-400">{{ $request->additional_notes ?: '—' }}</td>
                            <td class="px-4">
                                <span class="rounded border px-2 py-1 {{ $styles[$request->status->value] ?? '' }}">
                                    {{ ucfirst($request->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 text-slate-300">
                                {{ $request->created_at->format('M d, Y') }}
                                <br><span class="text-slate-500">{{ $request->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-4">
                                <button class="rounded-lg border border-purple-500/30 bg-purple-500/10 px-2 py-1 text-[11px] font-medium text-purple-300 transition hover:bg-purple-500/20">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500/15 text-purple-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-400">No service requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-purple-500/10 px-4 py-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="text-xs text-slate-500">
                    Showing {{ $requests->firstItem() ?? 1 }} to {{ $requests->lastItem() ?? $requests->count() }} of {{ $requests->total() ?? $requests->count() }} requests
                </div>

                <div class="flex items-center gap-3">
                    @if (method_exists($requests, 'hasPages') && $requests->hasPages())
                        <div class="flex items-center gap-1">
                            <button wire:click="gotoPage(1)" @if ($requests->onFirstPage()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/></svg>
                            </button>
                            <button wire:click="previousPage" @if ($requests->onFirstPage()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>

                            @php
                                $current = $requests->currentPage();
                                $last = $requests->lastPage();
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

                            <button wire:click="nextPage" @if (! $requests->hasMorePages()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                            <button wire:click="gotoPage({{ $last }})" @if (! $requests->hasMorePages()) disabled @endif
                                    class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 disabled:opacity-40">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 4.5l7.5 7.5-7.5 7.5m6-15l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============ DETAIL MODAL ============ --}}
    <x-modal :show="'showDetailModal'" max-width="lg">
        <x-slot:title>
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/15 text-purple-300 ring-1 ring-inset ring-purple-500/30">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-white">Service Request Details</h2>
                    @if($selectedRequest)
                        <p class="text-xs text-purple-400">#GS-{{ str_pad($selectedRequest->id, 6, '0', STR_PAD_LEFT) }}</p>
                    @endif
                </div>
            </div>
        </x-slot:title>

        @if($selectedRequest)
            <div class="space-y-5">
                @php
                    $statusStyles = [
                        'new' => 'border-blue-500/40 bg-blue-500/15 text-blue-300',
                        'processing' => 'border-amber-500/40 bg-amber-500/15 text-amber-300',
                        'signed' => 'border-purple-500/40 bg-purple-500/15 text-purple-300',
                        'completed' => 'border-emerald-500/40 bg-emerald-500/15 text-emerald-300',
                        'flagged' => 'border-red-500/40 bg-red-500/15 text-red-300',
                    ];
                @endphp

                <div class="flex items-center justify-between rounded-xl bg-slate-900/60 p-3 ring-1 ring-inset ring-purple-500/10">
                    <span class="text-xs text-slate-400">Status</span>
                    <span class="rounded border px-2.5 py-1 text-xs font-semibold {{ $statusStyles[$selectedRequest->status->value] ?? '' }}">
                        {{ ucfirst($selectedRequest->status->value) }}
                    </span>
                </div>

                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-inset ring-purple-500/10">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-500/15 text-purple-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-purple-300">Client Information</span>
                    </div>
                    <dl class="space-y-2.5 text-xs">
                        <div class="grid grid-cols-3 gap-2">
                            <dt class="text-slate-500">Full Name</dt>
                            <dd class="col-span-2 font-medium text-slate-100">{{ $selectedRequest->full_name }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="col-span-2 font-medium text-slate-100">{{ $selectedRequest->email }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="col-span-2 font-medium text-slate-100">{{ $selectedRequest->phone ?: '—' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <dt class="text-slate-500">Company</dt>
                            <dd class="col-span-2 font-medium text-slate-100">{{ $selectedRequest->company ?: '—' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <dt class="text-slate-500">Contact Via</dt>
                            <dd class="col-span-2 font-medium text-slate-100">{{ ucfirst($selectedRequest->preferred_contact) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-inset ring-purple-500/10">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-300">Selected Services</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedRequest->selected_services ?? [] as $service)
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-3 py-1.5 text-xs font-medium text-emerald-300">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                {{ $service }}
                            </span>
                        @endforeach
                        @if(empty($selectedRequest->selected_services))
                            <span class="text-xs text-slate-500">No services selected</span>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-inset ring-purple-500/10">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/15 text-amber-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-300">Additional Notes</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-300">{{ $selectedRequest->additional_notes ?: 'No notes provided.' }}</p>
                </div>

                <div class="flex items-center gap-2 rounded-xl bg-slate-900/60 p-3 ring-1 ring-inset ring-purple-500/10">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span class="text-xs text-slate-400">Submitted {{ $selectedRequest->created_at->format('M d, Y \a\t h:i A') }}</span>
                </div>

                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-inset ring-purple-500/10">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-500/15 text-purple-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-purple-300">Update Status</span>
                    </div>
                    <select wire:change="updateSelectedStatus($event.target.value)"
                        class="w-full rounded-lg border border-purple-500/15 bg-slate-900/60 px-3 py-2.5 text-xs text-white ring-1 ring-inset ring-purple-500/10 focus:border-purple-500/40 focus:outline-none">
                        @foreach(\App\Enums\GetServiceStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected($selectedRequest->status === $s)>{{ ucfirst($s->value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button type="button" variant="secondary" wire:click="closeModal('showDetailModal')">Close</x-button>
            </div>
        </x-slot:footer>
    </x-modal>
</div>
