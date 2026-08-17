<div class="space-y-5">

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Reviews Management</h1>
            <p class="mt-1 text-sm text-slate-400">Add, approve, edit and delete customer reviews. Only approved reviews are displayed on the website.</p>
        </div>

        <button type="button" wire:click="openCreate"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-900/50 transition hover:from-purple-500 hover:to-purple-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add New Review
        </button>
    </div>

    {{-- ============================================================
         KPI CARDS
    ============================================================ --}}
    @php
        $kpis = [
            [
                'label'    => 'Total Reviews',
                'value'    => $counts['total'],
                'sub'      => 'All time',
                'valColor' => 'text-purple-300',
                'iconBg'   => 'from-purple-500/20 to-purple-700/10 text-purple-300 ring-purple-500/30',
                'icon'     => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            ],
            [
                'label'    => 'Approved',
                'value'    => $counts['approved'],
                'sub'      => 'Visible on website',
                'valColor' => 'text-emerald-300',
                'iconBg'   => 'from-emerald-500/20 to-emerald-700/10 text-emerald-300 ring-emerald-500/30',
                'icon'     => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'label'    => 'Pending Approval',
                'value'    => $counts['pending'],
                'sub'      => 'Waiting for approval',
                'valColor' => 'text-amber-300',
                'iconBg'   => 'from-amber-500/20 to-amber-700/10 text-amber-300 ring-amber-500/30',
                'icon'     => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'label'    => 'Rejected',
                'value'    => $counts['rejected'],
                'sub'      => 'Not approved',
                'valColor' => 'text-red-300',
                'iconBg'   => 'from-red-500/20 to-red-700/10 text-red-300 ring-red-500/30',
                'icon'     => 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($kpis as $kpi)
            <div class="group relative overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm transition hover:border-purple-500/30">
                <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/5 blur-2xl"></div>
                <div class="relative flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $kpi['iconBg'] }} ring-1 ring-inset">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $kpi['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">{{ $kpi['label'] }}</p>
                        <p class="mt-1 text-2xl font-bold tracking-tight {{ $kpi['valColor'] }}">{{ $kpi['value'] }}</p>
                        <p class="mt-0.5 text-[11px] text-slate-500">{{ $kpi['sub'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================================
         TABLE CARD
    ============================================================ --}}
    <div class="overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] shadow-lg shadow-purple-950/30 backdrop-blur-sm">

        {{-- ============ FILTER ROW ============ --}}
        <div class="border-b border-purple-500/10 p-4">
            <div class="flex flex-wrap items-end gap-3">
                {{-- Search --}}
                <div class="min-w-[260px] flex-1">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, position or description..."
                               class="block w-full rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-10 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                </div>

                {{-- Status filter --}}
                <div>
                    <div class="relative">
                        <select wire:model.live="status"
                                class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                            <option value="" class="bg-slate-900">All Status</option>
                            @foreach (\App\Enums\ReviewStatus::cases() as $s)
                                <option value="{{ $s->value }}" class="bg-slate-900">{{ ucfirst($s->value) }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                {{-- Sort By --}}
                <div class="ml-auto">
                    <label class="mb-1 block text-[11px] font-medium text-slate-500">Sort By</label>
                    <div class="relative">
                        <select wire:model.live="sort"
                                class="w-44 appearance-none rounded-lg border-0 bg-slate-900/60 py-2.5 pl-3 pr-9 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                            <option value="newest" class="bg-slate-900">Newest First</option>
                            <option value="oldest" class="bg-slate-900">Oldest First</option>
                            <option value="highest" class="bg-slate-900">Highest Rated</option>
                            <option value="lowest" class="bg-slate-900">Lowest Rated</option>
                        </select>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                {{-- Reset Filters --}}
                <button type="button" wire:click="$set('search',''); $set('status',''); $set('sort','newest')"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-purple-500/20 bg-slate-900/60 px-3 py-2.5 text-sm font-medium text-slate-300 transition hover:border-purple-500/40 hover:text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>

        {{-- ============ TABLE ============ --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead>
                    <tr class="border-b border-purple-500/10 text-[11px] uppercase tracking-wider text-slate-400">
                        <th class="px-4 py-4 font-medium">DP</th>
                        <th class="px-4 py-4 font-medium">Name</th>
                        <th class="px-4 py-4 font-medium">Position</th>
                        <th class="px-4 py-4 font-medium">Stars</th>
                        <th class="px-4 py-4 font-medium">Description</th>
                        <th class="px-4 py-4 font-medium">Status</th>
                        <th class="px-4 py-4 font-medium">Date</th>
                        <th class="px-4 py-4 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($reviews as $review)
                        @php
                            $statusVal = $review->status->value ?? 'pending';
                            $pill = match($statusVal) {
                                'approved' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Approved'],
                                'rejected' => ['bg' => 'bg-red-500/15',     'text' => 'text-red-300',     'ring' => 'ring-red-500/30',     'label' => 'Rejected'],
                                default    => ['bg' => 'bg-amber-500/15',   'text' => 'text-amber-300',   'ring' => 'ring-amber-500/30',   'label' => 'Pending'],
                            };
                        @endphp
                        <tr class="transition hover:bg-white/[0.02]">
                            {{-- DP avatar --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="h-10 w-10 overflow-hidden rounded-full bg-gradient-to-br from-purple-500/40 to-purple-800/40 ring-1 ring-purple-500/30">
                                    @if ($review->dp_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($review->dp_path))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($review->dp_path) }}"
                                             alt="{{ $review->name }}" class="h-full w-full object-cover"/>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm font-bold text-purple-200">
                                            {{ strtoupper(substr($review->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Name --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="text-sm font-semibold text-slate-100">{{ $review->name }}</div>
                            </td>

                            {{-- Position --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="text-sm text-slate-300">{{ $review->position }}</div>
                            </td>

                            {{-- Stars --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-700' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.175 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.061 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.288-3.958z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>

                            {{-- Description --}}
                            <td class="px-4 py-3">
                                <p class="max-w-[280px] text-xs leading-relaxed text-slate-300">
                                    {{ \Illuminate\Support\Str::limit($review->description, 100) }}
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
                                <div class="text-xs font-medium text-slate-300">{{ $review->created_at->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $review->created_at->format('h:i A') }}</div>
                            </td>

                            {{-- Action --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    @if ($review->status?->value !== 'approved')
                                        <button type="button" wire:click="setStatus({{ $review->id }}, 'approved')"
                                                class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-1.5 text-emerald-300 transition hover:bg-emerald-500/20"
                                                title="Approve">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                    @if ($review->status?->value !== 'rejected')
                                        <button type="button" wire:click="setStatus({{ $review->id }}, 'rejected')"
                                                class="rounded-lg border border-amber-500/30 bg-amber-500/10 p-1.5 text-amber-300 transition hover:bg-amber-500/20"
                                                title="Reject">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    @endif
                                    <button type="button" wire:click="openEdit({{ $review->id }})"
                                            class="rounded-lg border border-purple-500/30 bg-purple-500/10 p-1.5 text-purple-300 transition hover:bg-purple-500/20"
                                            title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </button>

                                    <button type="button" wire:click="confirmDelete({{ $review->id }})"
                                            class="rounded-lg border border-red-500/30 bg-red-500/10 p-1.5 text-red-300 transition hover:bg-red-500/20"
                                            title="Delete">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10 ring-1 ring-inset ring-purple-500/30">
                                        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-200">No reviews yet</p>
                                    <p class="mt-1 text-xs text-slate-500">Reviews submitted from the website will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ============ PAGINATION FOOTER ============ --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-purple-500/10 px-4 py-3">
            <div class="text-xs text-slate-500">
                Showing {{ $reviews->firstItem() ?? 1 }} to {{ $reviews->lastItem() ?? $reviews->count() }} of {{ $reviews->total() ?? $reviews->count() }} reviews
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

                {{-- Numbered pagination --}}
                @if (method_exists($reviews, 'hasPages') && $reviews->hasPages())
                    <div class="flex items-center gap-1">
                        {{-- First --}}
                        <button type="button" wire:click="gotoPage(1)"
                                @if ($reviews->onFirstPage()) disabled @endif
                                class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 transition hover:text-white disabled:opacity-40">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/>
                            </svg>
                        </button>

                        {{-- Previous --}}
                        <button type="button" wire:click="previousPage"
                                @if ($reviews->onFirstPage()) disabled @endif
                                class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 transition hover:text-white disabled:opacity-40">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>

                        @php
                            $current = $reviews->currentPage();
                            $last    = $reviews->lastPage();
                            $window  = 2;
                            $start   = max(1, $current - $window);
                            $end     = min($last, $current + $window);
                        @endphp

                        @if ($start > 1)
                            <button wire:click="gotoPage(1)" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-900/60 text-xs font-semibold text-slate-300 ring-1 ring-inset ring-purple-500/20 hover:bg-slate-800">1</button>
                            @if ($start > 2) <span class="px-1 text-slate-500">…</span> @endif
                        @endif

                        @for ($p = $start; $p <= $end; $p++)
                            <button type="button" wire:click="gotoPage({{ $p }})"
                                    @class([
                                        'flex h-7 w-7 items-center justify-center rounded-lg text-xs font-semibold transition',
                                        'bg-purple-600 text-white shadow-lg shadow-purple-900/40' => $current === $p,
                                        'bg-slate-900/60 text-slate-300 ring-1 ring-inset ring-purple-500/20 hover:bg-slate-800' => $current !== $p,
                                    ])>
                                {{ $p }}
                            </button>
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1) <span class="px-1 text-slate-500">…</span> @endif
                            <button wire:click="gotoPage({{ $last }})" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-900/60 text-xs font-semibold text-slate-300 ring-1 ring-inset ring-purple-500/20 hover:bg-slate-800">{{ $last }}</button>
                        @endif

                        {{-- Next --}}
                        <button type="button" wire:click="nextPage"
                                @if (! $reviews->hasMorePages()) disabled @endif
                                class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 transition hover:text-white disabled:opacity-40">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>

                        {{-- Last --}}
                        <button type="button" wire:click="gotoPage({{ $last }})"
                                @if (! $reviews->hasMorePages()) disabled @endif
                                class="rounded-lg bg-slate-900/60 p-1.5 text-slate-400 ring-1 ring-inset ring-purple-500/20 transition hover:text-white disabled:opacity-40">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 4.5l7.5 7.5-7.5 7.5m6-15l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================
         CREATE / EDIT MODAL
    ============================================================ --}}
    @if ($showCreateModal)
        <x-modal :show="'showCreateModal'" max-width="xl">
            <x-slot:title>{{ $editReviewId ? 'Edit Review' : 'Add Review' }}</x-slot:title>
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">Name <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="name"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                        @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">Position</label>
                        <input type="text" wire:model="position"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                        @error('position') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Rating <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <select wire:model="rating"
                                class="block w-full appearance-none rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 pr-10 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" class="bg-slate-900">{{ $i }} / 5</option>
                            @endfor
                        </select>
                        <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Description <span class="text-red-400">*</span></label>
                    <textarea wire:model="description" rows="4"
                              class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"></textarea>
                    @error('description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Photo <span class="font-normal text-slate-500">(optional)</span></label>
                    <label class="flex cursor-pointer items-center justify-center gap-3 rounded-lg border border-dashed border-purple-500/30 bg-slate-900/40 px-4 py-3 transition hover:border-purple-500/60 hover:bg-slate-900/60">
                        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l3 3m0 0l3-3m-3 3V2.25"/>
                        </svg>
                        <div class="text-sm">
                            <span class="font-semibold text-slate-200">Click to upload</span>
                            <span class="text-slate-400">or drag and drop</span>
                        </div>
                        <input type="file" wire:model="photo" accept="image/*" class="sr-only"/>
                    </label>
                    @if ($photo)
                        <p class="mt-2 text-xs text-emerald-400">New photo selected: {{ $photo->getClientOriginalName() }}</p>
                    @endif
                    @error('photo') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
            <x-slot:footer>
                <x-button type="button" variant="secondary" wire:click="closeModal('showCreateModal')">Cancel</x-button>
                <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save">{{ $editReviewId ? 'Update' : 'Save' }}</x-button>
            </x-slot:footer>
        </x-modal>
    @endif

    {{-- ============================================================
         DELETE CONFIRMATION MODAL
    ============================================================ --}}
    @if ($showDeleteModal)
        <x-modal :show="'showDeleteModal'" max-width="sm">
            <x-slot:title>Delete Review?</x-slot:title>
            <p class="text-sm text-slate-400">This action cannot be undone. The review will be permanently removed.</p>
            <x-slot:footer>
                <x-button type="button" variant="secondary" wire:click="closeModal('showDeleteModal')">Cancel</x-button>
                <x-button type="button" variant="danger" wire:click="deleteReview">Delete</x-button>
            </x-slot:footer>
        </x-modal>
    @endif
</div>
