<div class="space-y-5">

    {{-- ============================================================
         TOP HEADER BAR
    ============================================================ --}}
    <div class="relative z-40 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-purple-500/15 text-purple-300 ring-1 ring-inset ring-purple-500/30">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>

            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-xl font-bold tracking-tight text-white">{{ $agreement->title }}</h1>
                    <div class="inline-flex items-center gap-1.5 rounded-md bg-slate-800/70 px-2.5 py-1 text-xs text-slate-300 ring-1 ring-inset ring-slate-700">
                        <span class="text-slate-500">Agreement No:</span>
                        <span class="font-semibold text-slate-200">{{ $agreement->agreement_number }}</span>
                        <span x-data="{ copied: false }" class="relative inline-flex">
                            <button type="button" title="Copy agreement number"
                                    @click="navigator.clipboard.writeText('{{ $agreement->agreement_number }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                    class="inline-flex h-5 w-5 items-center justify-center rounded text-slate-400 transition hover:bg-slate-700 hover:text-purple-300">
                                <svg x-show="!copied" x-cloak class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876"/>
                                </svg>
                                <svg x-show="copied" x-cloak class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </button>
                            <span x-show="copied" x-cloak x-transition
                                  class="absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-emerald-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg">
                                Copied!
                            </span>
                        </span>
                    </div>
                </div>

                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                    @php
                        $statusVal = $agreement->status->value;
                        $sm = match($statusVal) {
                            'completed', 'subscribed' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30'],
                            'expired', 'terminated'   => ['bg' => 'bg-red-500/15',     'text' => 'text-red-300',     'ring' => 'ring-red-500/30'],
                            'signed'                  => ['bg' => 'bg-blue-500/15',     'text' => 'text-blue-300',    'ring' => 'ring-blue-500/30'],
                            'in_progress'             => ['bg' => 'bg-indigo-500/15',   'text' => 'text-indigo-300',  'ring' => 'ring-indigo-500/30'],
                            'pending'                 => ['bg' => 'bg-amber-500/15',    'text' => 'text-amber-300',   'ring' => 'ring-amber-500/30'],
                            'unsubscribed'            => ['bg' => 'bg-slate-500/15',    'text' => 'text-slate-300',   'ring' => 'ring-slate-500/30'],
                            default                   => ['bg' => 'bg-slate-500/15',    'text' => 'text-slate-300',   'ring' => 'ring-slate-500/30'],
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 font-semibold ring-1 ring-inset {{ $sm['bg'] }} {{ $sm['text'] }} {{ $sm['ring'] }}">
                        {{ $agreement->status->label() }}
                    </span>

                    <span class="inline-flex items-center rounded-md bg-purple-500/15 px-2 py-0.5 font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30">
                        {{ $agreement->payment_type->label() }}
                    </span>

                    @if ($agreement->currentVersion)
                        <span class="inline-flex items-center rounded-md bg-blue-500/15 px-2 py-0.5 font-semibold text-blue-300 ring-1 ring-inset ring-blue-500/30">
                            Current Version: V{{ $agreement->currentVersion->version }}
                        </span>
                    @endif

                    @if ($agreement->amountTotalPence() > 0)
                        <span class="inline-flex items-center rounded-md bg-emerald-500/15 px-2 py-0.5 font-semibold text-emerald-300 ring-1 ring-inset ring-emerald-500/30">
                            {{ $agreement->formatted_amount }}
                        </span>
                    @endif

                    <span class="h-3 w-px bg-slate-700"></span>

                    <span class="inline-flex items-center gap-1 text-slate-400">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75"/>
                        </svg>
                        Created: {{ $agreement->created_at->format('M d, Y h:i A') }}
                    </span>

                    <span class="h-3 w-px bg-slate-700"></span>

                    <span class="inline-flex items-center gap-1 text-slate-400">
                        Last Updated: {{ $agreement->updated_at->format('M d, Y h:i A') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Right side: Edit + dropdown --}}
        <div class="flex items-center gap-3" x-data="{ open: false }">
            <a href="{{ route('admin.agreements.edit', $agreement) }}"
               class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-900/40 transition-all hover:from-purple-500 hover:to-indigo-500 hover:shadow-purple-800/60 hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-4 w-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
                Edit Agreement
            </a>

            <div class="relative">
                <button type="button" @click="open = !open"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-700/60 bg-slate-800/80 px-4 py-2.5 text-sm font-semibold text-slate-200 shadow-lg shadow-slate-950/30 backdrop-blur-sm transition-all hover:border-slate-600 hover:bg-slate-700/80 hover:text-white active:scale-[0.98]">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <span class="hidden sm:inline">Actions</span>
                    <svg class="h-3.5 w-3.5 text-slate-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div x-show="open" @click.away="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 top-full z-50 mt-2 w-64 overflow-hidden rounded-2xl border border-purple-500/15 bg-slate-900/95 shadow-2xl shadow-purple-950/50 backdrop-blur-2xl">

                    {{-- Section label --}}
                    <div class="border-b border-slate-700/50 px-4 pt-3.5 pb-2">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Agreement Actions</p>
                    </div>

                    <div class="p-1.5">
                        {{-- Edit Agreement --}}
                        <a href="{{ route('admin.agreements.edit', $agreement) }}" @click="open = false"
                           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 transition hover:bg-purple-500/10 hover:text-white group/item">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-500/15 text-indigo-400 ring-1 ring-inset ring-indigo-500/20 transition group-hover/item:bg-indigo-500/25 group-hover/item:text-indigo-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Edit Agreement</p>
                                <p class="text-[11px] text-slate-500">Modify content & details</p>
                            </div>
                        </a>

                        {{-- Change Status --}}
                        <button type="button" wire:click="openStatusModal" @click="open = false"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-slate-200 transition hover:bg-purple-500/10 hover:text-white group/item">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/15 text-amber-400 ring-1 ring-inset ring-amber-500/20 transition group-hover/item:bg-amber-500/25 group-hover/item:text-amber-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Change Status</p>
                                <p class="text-[11px] text-slate-500">Update agreement status</p>
                            </div>
                        </button>

                        {{-- Resend to Client --}}
                        <button type="button" wire:click="showResendModal = true" @click="open = false"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-slate-200 transition hover:bg-purple-500/10 hover:text-white group/item">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-500/15 text-purple-400 ring-1 ring-inset ring-purple-500/20 transition group-hover/item:bg-purple-500/25 group-hover/item:text-purple-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Resend to Client</p>
                                <p class="text-[11px] text-slate-500">Send agreement email again</p>
                            </div>
                        </button>

                        {{-- Send Payment Reminder --}}
                        <button type="button" wire:click="showPaymentReminderModal = true" @click="open = false"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-slate-200 transition hover:bg-purple-500/10 hover:text-white group/item">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-500/15 text-orange-400 ring-1 ring-inset ring-orange-500/20 transition group-hover/item:bg-orange-500/25 group-hover/item:text-orange-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Payment Reminder</p>
                                <p class="text-[11px] text-slate-500">Nudge client for payment</p>
                            </div>
                        </button>

                        {{-- Archive Agreement --}}
                        <button type="button" wire:click="showArchiveModal = true" @click="open = false"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-slate-200 transition hover:bg-purple-500/10 hover:text-white group/item">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-500/15 text-slate-400 ring-1 ring-inset ring-slate-500/20 transition group-hover/item:bg-slate-500/25 group-hover/item:text-slate-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Archive Agreement</p>
                                <p class="text-[11px] text-slate-500">Hide from active list</p>
                            </div>
                        </button>
                    </div>

                    {{-- Danger zone --}}
                    <div class="border-t border-slate-700/50 p-1.5">
                        <button type="button" wire:click="showTerminateModal = true" @click="open = false"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-red-300 transition hover:bg-red-500/10 hover:text-red-200 group/item">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-500/15 text-red-400 ring-1 ring-inset ring-red-500/20 transition group-hover/item:bg-red-500/25 group-hover/item:text-red-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Terminate Agreement</p>
                                <p class="text-[11px] text-red-400/60">Disable & stop all activity</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MAIN GRID
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

        {{-- ============ LEFT COLUMN (Agreement Details + tables) ============ --}}
        <div class="space-y-5 xl:col-span-4">

            {{-- Agreement Details --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <h2 class="mb-4 text-base font-bold text-white">Agreement Details</h2>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Client Info --}}
                    <div>
                        <div class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-purple-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            Client Information
                        </div>
                        <dl class="space-y-2 text-sm">
                            <div class="flex gap-2">
                                <dt class="w-20 text-slate-500">Full Name:</dt>
                                <dd class="font-medium text-slate-100">{{ $agreement->client_name }}</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="w-20 text-slate-500">Email:</dt>
                                <dd class="min-w-0 flex-1 font-medium text-slate-100">
                                    <span class="truncate block">{{ $agreement->client_email }}</span>
                                </dd>
                                <span x-data="{ copied: false }" class="relative shrink-0 inline-flex">
                                    <button type="button" title="Copy email"
                                            @click="navigator.clipboard.writeText('{{ $agreement->client_email }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                            class="inline-flex h-5 w-5 items-center justify-center rounded text-slate-400 transition hover:bg-slate-700 hover:text-purple-300">
                                        <svg x-show="!copied" x-cloak class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876"/></svg>
                                        <svg x-show="copied" x-cloak class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </button>
                                    <span x-show="copied" x-cloak x-transition
                                          class="absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-emerald-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg">
                                        Copied!
                                    </span>
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <dt class="w-20 text-slate-500">Mobile:</dt>
                                <dd class="font-medium text-slate-100">{{ $agreement->client_mobile ?? '—' }}</dd>
                            </div>
                            @if ($agreement->creator)
                                <div class="flex gap-2">
                                    <dt class="w-20 text-slate-500">Created By:</dt>
                                    <dd class="font-medium text-slate-100">{{ $agreement->creator->name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Agreement Info --}}
                    <div>
                        <div class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-purple-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664"/>
                            </svg>
                            Agreement Information
                        </div>
                        <dl class="space-y-2 text-sm">
                            <div class="flex gap-2">
                                <dt class="w-24 text-slate-500">Validity Date:</dt>
                                <dd class="font-medium text-slate-100">{{ $agreement->validity_date?->format('M d, Y') ?? '—' }}</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="w-24 text-slate-500">Payment Type:</dt>
                                <dd class="font-medium text-slate-100">{{ $agreement->payment_type->label() }}</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="w-24 text-slate-500">Total Amount:</dt>
                                <dd class="font-semibold text-emerald-400">{{ $agreement->formatted_amount }}</dd>
                            </div>
                            @if ($agreement->validity_date)
                                <div class="flex gap-2">
                                    <dt class="w-24 text-slate-500">Expired:</dt>
                                    <dd class="font-medium {{ $agreement->isExpired() ? 'text-red-400' : 'text-slate-100' }}">
                                        {{ $agreement->isExpired() ? 'Yes' : 'No' }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Agreement Content preview --}}
                <div class="mt-5 border-t border-purple-500/10 pt-4" x-data="{ expanded: false }">
                    <div class="mb-2 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-purple-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25"/>
                            </svg>
                            Agreement Content
                        </div>
                        <button type="button"
                                x-show="{{ $agreement->currentVersion?->content ? 'true' : 'false' }}"
                                @click="expanded = !expanded"
                                class="text-[11px] font-medium text-purple-400 transition hover:text-purple-300">
                            <span x-text="expanded ? 'Show less' : 'View more'"></span>
                        </button>
                    </div>
                    @if ($agreement->currentVersion?->content)
                        <div class="prose-content rounded-xl bg-slate-900/60 p-4 ring-1 ring-inset ring-purple-500/10">
                            <div :class="expanded ? '' : 'line-clamp-6'">
                                {!! $agreement->currentVersion->content !!}
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-slate-500">No content available.</p>
                    @endif
                </div>

                {{-- Attachments --}}
                @if ($agreement->attachments->isNotEmpty())
                    <div class="mt-4 border-t border-purple-500/10 pt-4">
                        <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-purple-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                            Attachments
                        </div>
                        @foreach ($agreement->attachments as $att)
                            <div class="flex items-center justify-between rounded-lg bg-slate-900/60 p-3 ring-1 ring-inset ring-purple-500/15 {{ !$loop->first ? 'mt-2' : '' }}">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-500/15 text-red-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-slate-100">{{ $att->original_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $att->mime_type ?? 'file' }} · {{ number_format($att->size_bytes / 1024, 0) }} KB</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.attachments.download', $att) }}" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white/5 hover:text-purple-300" title="Download">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Version History --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-base font-bold text-white">Version History</h2>
                </div>

                <div id="version-history-scroll" class="overflow-x-auto overflow-y-auto" style="max-height: 270px;">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-white/5 text-[11px] uppercase tracking-wider text-slate-500">
                                <th class="pb-3 pt-1 font-medium">Version</th>
                                <th class="pb-3 pt-1 font-medium">Changed Date / Time</th>
                                <th class="pb-3 pt-1 font-medium">Changed By</th>
                                <th class="pb-3 pt-1 font-medium">Status</th>
                                <th class="pb-3 pt-1 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($agreement->versions as $version)
                                <tr class="transition hover:bg-white/[0.02]">
                                    <td class="py-3 font-semibold {{ $loop->first ? 'text-purple-300' : 'text-slate-300' }}">
                                        V{{ $version->version }}{{ $loop->first ? ' (Current)' : '' }}
                                    </td>
                                    <td class="py-3 text-xs text-slate-300">{{ $version->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="py-3 text-xs text-slate-300">{{ $version->admin?->name ?? 'System' }}</td>
                                    <td class="py-3">
                                        @if ($loop->first)
                                            <span class="inline-flex items-center rounded-md bg-emerald-500/15 px-2 py-0.5 text-[10px] font-semibold text-emerald-300 ring-1 ring-inset ring-emerald-500/30">Current</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-slate-500/15 px-2 py-0.5 text-[10px] font-semibold text-slate-400 ring-1 ring-inset ring-slate-500/30">Archived</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <button type="button" wire:click="previewVersion({{ $version->id }})"
                                                class="inline-flex items-center justify-center rounded-lg p-1.5 text-slate-400 transition hover:bg-white/5 hover:text-purple-300"
                                                title="Preview version V{{ $version->version }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.75-.75 1.982-.827 3.09-.198l5.504 5.504c1.108 1.108.17 2.927-.874 2.927H8.75a.75.75 0 010-1.5h3.642l-5.504-5.504c-.42-.42-.66-.975-.66-1.554V12z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-sm text-slate-500">No versions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-start gap-2 rounded-lg border border-blue-500/20 bg-blue-500/5 p-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <p class="text-xs text-slate-400">New edits create a new version. Previous versions remain unchanged.</p>
                </div>
            </div>

            {{-- Activity Timeline --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
                    </svg>
                    <h2 class="text-base font-bold text-white">Activity Timeline</h2>
                </div>

                <ol id="activity-timeline-scroll" class="relative space-y-4 overflow-y-auto border-l border-purple-500/20 pl-5" style="max-height: 270px;">
                    @forelse ($agreement->accessLogs as $log)
                        <li class="relative">
                            <span class="absolute -left-[27px] top-1.5 h-2.5 w-2.5 rounded-full {{ $log->status === 'verified' ? 'bg-emerald-500 ring-4 ring-emerald-500/20' : 'bg-purple-500 ring-4 ring-purple-500/20' }}"></span>
                            <p class="text-xs font-medium text-purple-300">{{ $log->created_at->format('M d, Y h:i A') }}</p>
                            <p class="mt-0.5 text-sm text-slate-200">{{ ucfirst(str_replace('_', ' ', $log->status)) }}{{ $log->email ? ' — ' . $log->email : '' }}</p>
                        </li>
                    @empty
                        <li class="relative">
                            <span class="absolute -left-[27px] top-1.5 h-2.5 w-2.5 rounded-full bg-slate-600 ring-4 ring-slate-600/20"></span>
                            <p class="text-sm text-slate-500">No activity yet.</p>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>

        {{-- ============ MIDDLE COLUMN (PDF Preview + Payment History) ============ --}}
        <div class="space-y-5 xl:col-span-5">

            {{-- Agreement Preview --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h2 class="text-base font-bold text-white">Agreement Preview</h2>
                        <span class="text-xs text-slate-500">
                            {{ $agreement->currentVersion ? '(V' . $agreement->currentVersion->version . ')' : '(No version)' }}
                        </span>
                    </div>
                    <button type="button" wire:click="downloadPdf"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-purple-500/30 bg-purple-500/10 px-3 py-1.5 text-xs font-semibold text-purple-300 transition hover:bg-purple-500/20">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Download PDF
                    </button>
                </div>

                {{-- Preview content --}}
                <div class="rounded-xl border border-purple-500/15 bg-white" id="agreement-preview-wrap" style="max-height: 520px; overflow-y: auto;">
                    <style>
                        #agreement-preview-wrap .ap-doc-wrapper {
                            background: #ffffff; border-radius: 0; overflow: hidden;
                            border: none; position: relative;
                            border-top: 4px solid #7c3aed;
                        }
                        #agreement-preview-wrap .ap-doc-wrapper::before {
                            content: ''; position: absolute; top: 18%; right: 2%; transform: none;
                            width: 420px; height: 420px;
                            background: url('{{ asset("watermark-logo.png") }}') no-repeat center center;
                            background-size: contain; opacity: 0.04; pointer-events: none; z-index: 1;
                        }
                        #agreement-preview-wrap .ap-doc-wrapper > * { position: relative; z-index: 2; }
                        #agreement-preview-wrap .ap-header {
                            padding: 28px 40px 24px; border-bottom: 3px solid #4c1d95;
                            display: flex; align-items: center; justify-content: space-between; gap: 24px;
                            background: linear-gradient(180deg, #f9f7ff 0%, #ffffff 100%);
                        }
                        #agreement-preview-wrap .ap-brand img { display: block; max-width: 300px; height: auto; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.05)); }
                        #agreement-preview-wrap .ap-meta {
                            display: grid; grid-template-columns: 1fr 1fr; gap: 10px 22px;
                            min-width: 320px; max-width: 420px;
                        }
                        #agreement-preview-wrap .ap-meta-row { display: flex; gap: 10px; align-items: flex-start; }
                        #agreement-preview-wrap .ap-meta-icon {
                            width: 30px; height: 30px; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 8px;
                            display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #4c1d95;
                            box-shadow: 0 1px 3px rgba(76,29,149,0.1);
                        }
                        #agreement-preview-wrap .ap-meta-icon svg { width: 14px; height: 14px; }
                        #agreement-preview-wrap .ap-meta-label { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
                        #agreement-preview-wrap .ap-meta-value { font-size: 12px; color: #111827; font-weight: 600; }
                        #agreement-preview-wrap .ap-status {
                            display: inline-flex; align-items: center; gap: 4px; padding: 3px 12px; border-radius: 999px;
                            font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
                        }
                        #agreement-preview-wrap .ap-status-signed { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; border: 1px solid #86efac; box-shadow: 0 1px 3px rgba(22,101,52,0.1); }
                        #agreement-preview-wrap .ap-status-pending { background: linear-gradient(135deg, #fef9c3, #fde68a); color: #854d0e; border: 1px solid #fcd34d; box-shadow: 0 1px 3px rgba(133,77,14,0.1); }
                        #agreement-preview-wrap .ap-status-expired { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fca5a5; box-shadow: 0 1px 3px rgba(153,27,27,0.1); }
                        #agreement-preview-wrap .ap-status-default { background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 1px solid #d1d5db; }
                        #agreement-preview-wrap .ap-body { padding: 36px 40px 0; }
                        #agreement-preview-wrap .ap-section-label {
                            font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: #4c1d95;
                            font-weight: 800; border-left: 4px solid #4c1d95; padding-left: 12px;
                            margin: 32px 0 14px; font-family: 'Inter', sans-serif;
                        }
                        #agreement-preview-wrap .ap-intro { font-size: 13px; line-height: 1.75; color: #374151; margin: 0 0 8px; }
                        #agreement-preview-wrap .ap-sub-intro { font-size: 12px; color: #6b7280; font-style: italic; margin: 0 0 20px; }
                        #agreement-preview-wrap .ap-block { margin-bottom: 22px; }
                        #agreement-preview-wrap .ap-block h3 { font-size: 12px; color: #4c1d95; font-weight: 800; margin: 0 0 8px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.3px; }
                        #agreement-preview-wrap .ap-block h3 .num {
                            display: inline-flex; align-items: center; justify-content: center;
                            width: 26px; height: 26px; background: linear-gradient(135deg, #7c3aed, #4c1d95); color: #fff;
                            border-radius: 50%; font-size: 10px; font-weight: 800;
                            box-shadow: 0 2px 6px rgba(76,29,149,0.25);
                        }
                        #agreement-preview-wrap .ap-block p { font-size: 12.5px; line-height: 1.7; color: #374151; margin: 0 0 6px; }
                        #agreement-preview-wrap .ap-content-area { font-size: 12.5px; line-height: 1.7; color: #000000; }
                        #agreement-preview-wrap .ap-content-area * { color: #000000 !important; }
                        #agreement-preview-wrap .ap-content-area h1,
                        #agreement-preview-wrap .ap-content-area h2,
                        #agreement-preview-wrap .ap-content-area h3,
                        #agreement-preview-wrap .ap-content-area h4 { color: #000000 !important; margin: 14px 0 8px; font-weight: 700; }
                        #agreement-preview-wrap .ap-content-area strong,
                        #agreement-preview-wrap .ap-content-area b { font-weight: 700; }
                        #agreement-preview-wrap .ap-content-area ul,
                        #agreement-preview-wrap .ap-content-area ol { padding-left: 20px; margin: 8px 0; }
                        #agreement-preview-wrap .ap-content-area li { margin-bottom: 4px; }
                        #agreement-preview-wrap .ap-content-area p { margin: 0 0 6px; }
                        #agreement-preview-wrap .ap-content-area td,
                        #agreement-preview-wrap .ap-content-area th,
                        #agreement-preview-wrap .ap-content-area span,
                        #agreement-preview-wrap .ap-content-area a,
                        #agreement-preview-wrap .ap-content-area em,
                        #agreement-preview-wrap .ap-content-area i { color: #000000 !important; }
                        #agreement-preview-wrap .ap-acceptance {
                            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #f5f3ff 100%);
                            border: 2px solid #c4b5fd; border-radius: 14px; padding: 28px 32px;
                            margin: 28px 40px 0; display: flex; gap: 24px; align-items: flex-start;
                            box-shadow: 0 4px 16px rgba(76,29,149,0.08), inset 0 2px 12px rgba(76,29,149,0.04);
                            position: relative; overflow: hidden;
                        }
                        #agreement-preview-wrap .ap-acceptance::before {
                            content: ''; position: absolute; top: 50%; right: 20px;
                            transform: translateY(-50%); width: 180px; height: 180px;
                            background: url('{{ asset("watermark-logo.png") }}') no-repeat center center;
                            background-size: contain; opacity: 0.07; pointer-events: none;
                        }
                        #agreement-preview-wrap .ap-acceptance > * { position: relative; z-index: 1; }
                        #agreement-preview-wrap .ap-acceptance-icon {
                            width: 56px; height: 56px; background: linear-gradient(135deg, #7c3aed, #4c1d95);
                            border: none; border-radius: 14px; display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0; color: #fff; box-shadow: 0 6px 20px rgba(76,29,149,0.3);
                        }
                        #agreement-preview-wrap .ap-acceptance-icon svg { width: 26px; height: 26px; }
                        #agreement-preview-wrap .ap-acceptance h4 { font-size: 15px; color: #1e1b4b; margin: 0 0 14px; font-weight: 800; letter-spacing: 0.8px; }
                        #agreement-preview-wrap .ap-sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 28px; }
                        #agreement-preview-wrap .ap-sig-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 600; margin-bottom: 2px; }
                        #agreement-preview-wrap .ap-sig-value { font-size: 12px; color: #111827; font-weight: 600; }
                        #agreement-preview-wrap .ap-sig-note {
                            margin-top: 14px; padding-top: 12px; border-top: 1px dashed #c4b5fd;
                            font-size: 10.5px; color: #4c1d95; font-weight: 600;
                            display: flex; align-items: center; gap: 6px;
                        }
                        #agreement-preview-wrap .ap-footer {
                            background: linear-gradient(180deg, #1e1b4b 0%, #0f0524 100%); color: #fff;
                            padding: 0; margin-top: 32px; position: relative; overflow: hidden;
                        }
                        #agreement-preview-wrap .ap-footer::before {
                            content: ''; position: absolute; top: -30px; right: -30px;
                            width: 180px; height: 180px;
                            background: url('{{ asset("watermark-logo.png") }}') no-repeat center center;
                            background-size: contain; opacity: 0.04; pointer-events: none;
                        }
                        #agreement-preview-wrap .ap-footer > * { position: relative; z-index: 1; }
                        #agreement-preview-wrap .ap-footer-inner { padding: 32px 40px 0; }
                        #agreement-preview-wrap .ap-footer-logo { text-align: center; margin-bottom: 20px; width: 100%; }
                        #agreement-preview-wrap .ap-footer-logo img { display: block; margin: 0 auto; height: 42px; width: auto; filter: brightness(0) invert(1); opacity: 0.85; }
                        #agreement-preview-wrap .ap-footer-divider {
                            height: 1px; margin: 0 0 18px;
                            background: linear-gradient(90deg, transparent, rgba(167,139,250,0.25), transparent);
                        }
                        #agreement-preview-wrap .ap-footer-strip {
                            display: flex; justify-content: center; gap: 28px; flex-wrap: wrap;
                            font-size: 10.5px; color: #c4b5fd;
                        }
                        #agreement-preview-wrap .ap-footer-strip strong { color: #e9d5ff; font-weight: 700; }
                        #agreement-preview-wrap .ap-footer-disclaimer {
                            margin-top: 18px; padding: 14px 20px;
                            background: rgba(255,255,255,0.03); border-radius: 8px;
                            font-size: 9px; color: #8b7fc7; line-height: 1.65; text-align: center;
                        }
                        #agreement-preview-wrap .ap-footer-bottom {
                            margin-top: 18px; padding: 14px 40px;
                            background: rgba(0,0,0,0.25);
                            display: flex; justify-content: space-between; align-items: center;
                            font-size: 9px; color: #7c6daa;
                        }
                        #agreement-preview-wrap .ap-footer-bottom a { color: #a78bfa; text-decoration: none; transition: color 0.2s; }
                        #agreement-preview-wrap .ap-footer-bottom a:hover { color: #c4b5fd; }
                        #agreement-preview-wrap .ap-footer-links { display: flex; gap: 18px; align-items: center; }
                        #agreement-preview-wrap .ap-footer-social { display: flex; gap: 10px; align-items: center; }
                        #agreement-preview-wrap .ap-footer-social a {
                            display: inline-flex; align-items: center; justify-content: center;
                            width: 24px; height: 24px; border-radius: 50%;
                            background: rgba(167,139,250,0.12); color: #a78bfa;
                        }
                        #agreement-preview-wrap .ap-footer-social a svg { width: 12px; height: 12px; }
                        #agreement-preview-wrap .ap-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #e9e5f5; }
                        #agreement-preview-wrap .ap-table { width: 100%; border-collapse: collapse; font-size: 12px; }
                        #agreement-preview-wrap .ap-table thead { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
                        #agreement-preview-wrap .ap-table th { padding: 10px 16px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #4c1d95; border-bottom: 2px solid #e9e5f5; }
                        #agreement-preview-wrap .ap-table td { padding: 12px 16px; color: #374151; border-bottom: 1px solid #f3f4f6; }
                        #agreement-preview-wrap .ap-table tbody tr:last-child td { border-bottom: none; }

                        @media (max-width: 640px) {
                            #agreement-preview-wrap .ap-header {
                                flex-direction: column; align-items: flex-start; gap: 16px; padding: 20px 20px 18px;
                            }
                            #agreement-preview-wrap .ap-brand img { max-width: 180px; }
                            #agreement-preview-wrap .ap-meta { grid-template-columns: 1fr; min-width: 0; max-width: 100%; gap: 8px; }
                            #agreement-preview-wrap .ap-body { padding: 24px 20px 0; }
                            #agreement-preview-wrap .ap-acceptance { flex-direction: column; align-items: center; text-align: center; padding: 20px 18px; margin: 20px 20px 0; gap: 16px; }
                            #agreement-preview-wrap .ap-acceptance-icon { width: 44px; height: 44px; }
                            #agreement-preview-wrap .ap-acceptance-icon svg { width: 20px; height: 20px; }
                            #agreement-preview-wrap .ap-sig-grid { grid-template-columns: 1fr; gap: 8px; }
                            #agreement-preview-wrap .ap-footer-inner { padding: 24px 20px 0; }
                            #agreement-preview-wrap .ap-footer-strip { flex-direction: column; gap: 8px; text-align: center; }
                            #agreement-preview-wrap .ap-footer-bottom { flex-direction: column; gap: 10px; text-align: center; padding: 12px 20px; }
                            #agreement-preview-wrap .ap-footer-links { flex-wrap: wrap; justify-content: center; gap: 12px; }
                            #agreement-preview-wrap .ap-footer-social { justify-content: center; }
                        }
                    </style>

                    <style>
                        #agreement-preview-wrap::-webkit-scrollbar { width: 6px; }
                        #agreement-preview-wrap::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
                        #agreement-preview-wrap::-webkit-scrollbar-thumb { background: #c4b5fd; border-radius: 10px; }
                        #agreement-preview-wrap::-webkit-scrollbar-thumb:hover { background: #a78bfa; }

                        #version-history-scroll::-webkit-scrollbar,
                        #activity-timeline-scroll::-webkit-scrollbar,
                        #access-history-scroll::-webkit-scrollbar { width: 6px; }
                        #version-history-scroll::-webkit-scrollbar-track,
                        #activity-timeline-scroll::-webkit-scrollbar-track,
                        #access-history-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px; }
                        #version-history-scroll::-webkit-scrollbar-thumb,
                        #activity-timeline-scroll::-webkit-scrollbar-thumb,
                        #access-history-scroll::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 10px; }
                        #version-history-scroll::-webkit-scrollbar-thumb:hover,
                        #activity-timeline-scroll::-webkit-scrollbar-thumb:hover,
                        #access-history-scroll::-webkit-scrollbar-thumb:hover { background: #a78bfa; }
                    </style>

                    @if ($agreement->currentVersion)
                        @include('partials._agreement-document', ['version' => $agreement->currentVersion, 'pdfMode' => false])
                    @else
                        <div class="py-12 text-slate-400">
                            <svg class="mx-auto h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <p class="text-sm">No version available for preview.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Payment History --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        <h2 class="text-base font-bold text-white">Payment History</h2>
                        <span class="inline-flex items-center rounded-md bg-slate-800/70 px-2 py-0.5 text-[10px] font-semibold text-slate-300 ring-1 ring-inset ring-slate-700">
                            {{ $agreement->payment_type->label() }}
                        </span>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-purple-400 transition hover:text-purple-300">View All</a>
                </div>

                @if ($agreement->payment_type->value === 'none')
                    <div class="py-8 text-center">
                        <svg class="mx-auto h-10 w-10 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        <p class="text-sm text-slate-500">No payment required for this agreement.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-white/5 text-[11px] uppercase tracking-wider text-slate-500">
                                    <th class="pb-3 pt-1 font-medium">
                                        {{ match($agreement->payment_type->value) {
                                            'milestone' => 'Milestone',
                                            'subscription' => 'Period',
                                            default => 'Type',
                                        } }}
                                    </th>
                                    <th class="pb-3 pt-1 font-medium">Amount</th>
                                    <th class="pb-3 pt-1 font-medium">Status</th>
                                    <th class="pb-3 pt-1 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @if ($agreement->payment_type->value === 'milestone')
                                    @forelse ($agreement->milestones as $milestone)
                                        @php
                                            $payment = $agreement->payments->firstWhere('milestone_id', $milestone->id);
                                            $pStatus = $payment?->status->value ?? 'pending';
                                            $pill = match($pStatus) {
                                                'succeeded' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Paid'],
                                                'processing' => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-300', 'ring' => 'ring-blue-500/30', 'label' => 'Processing'],
                                                'failed' => ['bg' => 'bg-red-500/15', 'text' => 'text-red-300', 'ring' => 'ring-red-500/30', 'label' => 'Failed'],
                                                default => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-300', 'ring' => 'ring-amber-500/30', 'label' => 'Pending'],
                                            };
                                        @endphp
                                        <tr class="transition hover:bg-white/[0.02]">
                                            <td class="py-3 text-sm font-medium text-slate-100">{{ $milestone->title ?? 'Milestone ' . $milestone->order_index }}</td>
                                            <td class="py-3 text-sm text-slate-200">{{ $milestone->formattedAmount() }}</td>
                                            <td class="py-3">
                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset {{ $pill['bg'] }} {{ $pill['text'] }} {{ $pill['ring'] }}">{{ $pill['label'] }}</span>
                                            </td>
                                            <td class="py-3 text-xs text-slate-400">{{ $payment?->paid_at?->format('M d, Y') ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-4 text-sm text-slate-500">No milestones defined.</td></tr>
                                    @endforelse

                                @elseif ($agreement->payment_type->value === 'subscription')
                                    @forelse ($agreement->subscriptions as $sub)
                                        @php
                                            $subPill = match($sub->status->value) {
                                                'active' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30'],
                                                'past_due' => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-300', 'ring' => 'ring-amber-500/30'],
                                                'canceled' => ['bg' => 'bg-red-500/15', 'text' => 'text-red-300', 'ring' => 'ring-red-500/30'],
                                                default => ['bg' => 'bg-slate-500/15', 'text' => 'text-slate-300', 'ring' => 'ring-slate-500/30'],
                                            };
                                        @endphp
                                        <tr class="transition hover:bg-white/[0.02]">
                                            <td class="py-3 text-sm font-medium text-slate-100">{{ $sub->title ?? 'Subscription' }}</td>
                                            <td class="py-3 text-sm text-slate-200">{{ $sub->formattedAmount() ?? '£' . number_format($sub->amount_pence / 100, 2) }}/{{ $sub->frequency ?? 'mo' }}</td>
                                            <td class="py-3">
                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset {{ $subPill['bg'] }} {{ $subPill['text'] }} {{ $subPill['ring'] }}">{{ ucfirst($sub->status->value) }}</span>
                                            </td>
                                            <td class="py-3 text-xs text-slate-400">{{ $sub->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-4 text-sm text-slate-500">No subscriptions.</td></tr>
                                    @endforelse

                                @else
                                    {{-- Full payment --}}
                                    @forelse ($agreement->payments as $payment)
                                        @php
                                            $fpPill = match($payment->status->value) {
                                                'succeeded' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'ring' => 'ring-emerald-500/30', 'label' => 'Paid'],
                                                'processing' => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-300', 'ring' => 'ring-blue-500/30', 'label' => 'Processing'],
                                                'failed' => ['bg' => 'bg-red-500/15', 'text' => 'text-red-300', 'ring' => 'ring-red-500/30', 'label' => 'Failed'],
                                                'refunded' => ['bg' => 'bg-slate-500/15', 'text' => 'text-slate-300', 'ring' => 'ring-slate-500/30', 'label' => 'Refunded'],
                                                default => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-300', 'ring' => 'ring-amber-500/30', 'label' => ucfirst($payment->status->value)],
                                            };
                                        @endphp
                                        <tr class="transition hover:bg-white/[0.02]">
                                            <td class="py-3 text-sm font-medium text-slate-100">{{ ucfirst($payment->type->value) }} Payment</td>
                                            <td class="py-3 text-sm text-slate-200">{{ $payment->formattedAmount() }}</td>
                                            <td class="py-3">
                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset {{ $fpPill['bg'] }} {{ $fpPill['text'] }} {{ $fpPill['ring'] }}">{{ $fpPill['label'] }}</span>
                                            </td>
                                            <td class="py-3 text-xs text-slate-400">{{ $payment->paid_at?->format('M d, Y') ?? $payment->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-4 text-sm text-slate-500">No payments recorded.</td></tr>
                                    @endforelse
                                @endif
                            </tbody>
                            <tfoot>
                                @php
                                    $totalPaid = $agreement->payments->where('status.value', 'succeeded')->sum('amount_pence');
                                    $totalRefunded = $agreement->payments->sum('refunded_amount_pence');
                                    $totalExpected = $agreement->amountTotalPence();
                                @endphp
                                <tr class="border-t border-purple-500/15">
                                    <td class="pt-4 text-sm font-bold text-white">Total Paid</td>
                                    <td class="pt-4 text-sm font-bold text-emerald-400" colspan="2">
                                        £{{ number_format($totalPaid / 100, 2) }}
                                        @if ($totalExpected > 0)
                                            <span class="text-slate-500 font-normal"> of £{{ number_format($totalExpected / 100, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="pt-4 text-right text-xs text-slate-500">
                                        @if ($totalRefunded > 0)
                                            <span class="text-amber-400">£{{ number_format($totalRefunded / 100, 2) }} refunded</span>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============ RIGHT COLUMN (Link Control + Access + Actions) ============ --}}
        <div class="space-y-5 xl:col-span-3">

            {{-- Agreement Link Control --}}
            @php $activeLink = $agreement->links->where('is_active', true)->first(); @endphp
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
                    </svg>
                    <h2 class="text-base font-bold text-white">Agreement Link</h2>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Link Status</span>
                        <span class="inline-flex items-center rounded-md {{ $activeLink ? 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30' : 'bg-red-500/15 text-red-300 ring-red-500/30' }} px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset">
                            {{ $activeLink ? 'Active' : 'Disabled' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Email OTP</span>
                        <span class="inline-flex items-center rounded-md {{ $activeLink?->otp_enabled ? 'bg-purple-500/15 text-purple-300 ring-purple-500/30' : 'bg-slate-500/15 text-slate-400 ring-slate-500/30' }} px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset">
                            {{ $activeLink?->otp_enabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>

                    @if ($activeLink)
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Protected Email</p>
                            <div class="flex items-center justify-between rounded-lg bg-slate-900/60 px-3 py-2 ring-1 ring-inset ring-purple-500/15">
                                <span class="truncate text-sm text-slate-200">{{ $agreement->client_email }}</span>
                                <span x-data="{ copied: false }" class="relative shrink-0 inline-flex ml-2">
                                    <button type="button" title="Copy email"
                                            @click="navigator.clipboard.writeText('{{ $agreement->client_email }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded text-slate-400 transition hover:bg-slate-700 hover:text-purple-300">
                                        <svg x-show="!copied" x-cloak class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876"/></svg>
                                        <svg x-show="copied" x-cloak class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </button>
                                    <span x-show="copied" x-cloak x-transition
                                          class="absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-emerald-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg">
                                        Copied!
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 mb-1">Link</p>
                            <div class="flex items-center justify-between rounded-lg bg-slate-900/60 px-3 py-2 ring-1 ring-inset ring-purple-500/15">
                                <span class="truncate text-xs text-slate-300">{{ $activeLink->publicUrl() }}</span>
                                <span x-data="{ copied: false }" class="relative shrink-0 inline-flex ml-2">
                                    <button type="button" title="Copy link"
                                            @click="navigator.clipboard.writeText('{{ $activeLink->publicUrl() }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded text-slate-400 transition hover:bg-slate-700 hover:text-purple-300">
                                        <svg x-show="!copied" x-cloak class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876"/></svg>
                                        <svg x-show="copied" x-cloak class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </button>
                                    <span x-show="copied" x-cloak x-transition
                                          class="absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-emerald-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg">
                                        Copied!
                                    </span>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="rounded-lg bg-slate-900/60 px-3 py-3 text-center ring-1 ring-inset ring-red-500/15">
                            <p class="text-xs text-slate-400">No active link. Regenerate to create one.</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <button type="button" wire:click="showArchiveModal = true"
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-300 transition hover:bg-red-500/20">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Disable Link
                        </button>
                        <button type="button" wire:click="showLinkRegenModal = true"
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-xs font-semibold text-emerald-300 transition hover:bg-emerald-500/20">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                            Regenerate
                        </button>
                    </div>

                    <button type="button" wire:click="showOtpToggleModal = true"
                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-purple-500/30 bg-purple-500/10 px-3 py-2 text-xs font-semibold text-purple-300 transition hover:bg-purple-500/20">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        {{ $activeLink?->otp_enabled ? 'Disable OTP' : 'Enable OTP' }}
                    </button>

                    <p class="text-[11px] text-slate-500">When enabled, client must verify email via OTP code every 24 hours to access the link.</p>
                </div>
            </div>

            {{-- Access History --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h2 class="text-base font-bold text-white">Access History</h2>
                        <span class="inline-flex items-center rounded-md bg-slate-800/70 px-2 py-0.5 text-[10px] font-semibold text-slate-300 ring-1 ring-inset ring-slate-700">
                            {{ $agreement->accessLogs->count() }}
                        </span>
                    </div>
                </div>

                <ul id="access-history-scroll" class="space-y-3 overflow-y-auto" style="max-height: 270px;">
                    @forelse ($agreement->accessLogs->take(5) as $log)
                        <li class="flex items-start gap-3 border-b border-white/5 pb-3 last:border-0 last:pb-0">
                            <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $log->status === 'verified' ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30' : 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30' }}">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <div class="min-w-0 flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-slate-200">{{ $log->created_at->format('M d, Y h:i A') }}</span>
                                    <span class="inline-flex items-center rounded-md {{ $log->status === 'verified' ? 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30' : 'bg-amber-500/15 text-amber-300 ring-amber-500/30' }} px-1.5 py-0.5 text-[9px] font-semibold ring-1 ring-inset">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </div>
                                @if ($log->email)
                                    <p class="mt-0.5 text-[11px] text-slate-400">{{ $log->email }}</p>
                                @endif
                                <div class="mt-1 text-[11px] text-slate-500">
                                    {{ $log->ip_address ?? '—' }}
                                    @if ($log->ip_address)
                                        · {{ $log->created_at->addDay()->format('M d, Y h:i A') }} valid
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-2 text-sm text-slate-500">No access recorded.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Quick Actions --}}
            <div class="rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] p-5 shadow-lg shadow-purple-950/30 backdrop-blur-sm">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                    </svg>
                    <h2 class="text-base font-bold text-white">Quick Actions</h2>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" wire:click="showResendModal = true"
                            class="group flex flex-col items-center gap-2 rounded-xl border border-purple-500/15 bg-slate-900/40 p-4 text-center transition hover:border-purple-500/40 hover:bg-slate-900/60">
                        <div class="relative flex h-9 w-9 items-center justify-center rounded-lg bg-purple-500/15 text-purple-300 ring-1 ring-inset ring-purple-500/30 transition group-hover:bg-purple-500/25 group-hover:text-purple-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-200 group-hover:text-white">Resend<br>Agreement</div>
                    </button>

                    <button type="button" wire:click="showPaymentReminderModal = true"
                            class="group flex flex-col items-center gap-2 rounded-xl border border-purple-500/15 bg-slate-900/40 p-4 text-center transition hover:border-purple-500/40 hover:bg-slate-900/60">
                        <div class="relative flex h-9 w-9 items-center justify-center rounded-lg bg-orange-500/15 text-orange-300 ring-1 ring-inset ring-orange-500/30 transition group-hover:bg-orange-500/25 group-hover:text-orange-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-200 group-hover:text-white">Send Payment<br>Reminder</div>
                    </button>

                    <button type="button" wire:click="downloadPdf"
                            class="group flex flex-col items-center gap-2 rounded-xl border border-purple-500/15 bg-slate-900/40 p-4 text-center transition hover:border-purple-500/40 hover:bg-slate-900/60">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30 transition group-hover:bg-emerald-500/25 group-hover:text-emerald-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-200 group-hover:text-white">Export<br>Agreement PDF</div>
                    </button>

                    <a href="{{ route('admin.agreements.index') }}"
                       class="group flex flex-col items-center gap-2 rounded-xl border border-purple-500/15 bg-slate-900/40 p-4 text-center transition hover:border-purple-500/40 hover:bg-slate-900/60">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500/15 text-blue-300 ring-1 ring-inset ring-blue-500/30 transition group-hover:bg-blue-500/25 group-hover:text-blue-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-200 group-hover:text-white">View in<br>All Agreements</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODALS
    ============================================================ --}}
    <x-modal :show="'showStatusModal'" max-width="sm">
        <x-slot:title>Change Status</x-slot:title>
        <div class="space-y-4">
            <p class="text-sm text-slate-400">This action is logged.</p>
            <x-select wire:model="newStatus">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </x-select>
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showStatusModal')">Cancel</x-button>
            <x-button type="button" wire:click="saveStatus" wire:loading.attr="disabled" wire:target="saveStatus">Confirm</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showArchiveModal'" max-width="sm">
        <x-slot:title>Archive Agreement</x-slot:title>
        <p class="text-sm text-slate-400">This disables the client link. History and payments are preserved.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showArchiveModal')">Cancel</x-button>
            <x-button type="button" variant="danger" wire:click="archive" wire:loading.attr="disabled" wire:target="archive">Archive</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showTerminateModal'" max-width="sm">
        <x-slot:title>Terminate Agreement</x-slot:title>
        <div class="space-y-3">
            <p class="text-sm text-slate-400">This will change the status to <span class="font-semibold text-red-400">Terminated</span> and disable the client link immediately.</p>
            <p class="text-sm text-slate-400">This action cannot be easily reversed.</p>
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showTerminateModal')">Cancel</x-button>
            <x-button type="button" variant="danger" wire:click="terminateAgreement" wire:loading.attr="disabled" wire:target="terminateAgreement">Terminate</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showLinkRegenModal'" max-width="sm">
        <x-slot:title>Regenerate Link</x-slot:title>
        <p class="text-sm text-slate-400">A new secure link will be generated and the current link will become invalid.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showLinkRegenModal')">Cancel</x-button>
            <x-button type="button" wire:click="regenerateLink" wire:loading.attr="disabled" wire:target="regenerateLink">Regenerate</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showOtpToggleModal'" max-width="sm">
        <x-slot:title>{{ $activeLink?->otp_enabled ? 'Disable' : 'Enable' }} Email OTP</x-slot:title>
        <p class="text-sm text-slate-400">
            @if ($activeLink?->otp_enabled)
                Client access will no longer require an email verification code.
            @else
                Client access to the agreement link will require an email verification code.
            @endif
        </p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showOtpToggleModal')">Cancel</x-button>
            <x-button type="button" wire:click="toggleOtpProtection" wire:loading.attr="disabled" wire:target="toggleOtpProtection">Confirm</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showResendModal'" max-width="sm">
        <x-slot:title>Resend to Client</x-slot:title>
        <p class="text-sm text-slate-400">Send the agreement link to <span class="font-medium text-slate-200">{{ $agreement->client_email }}</span> again.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showResendModal')">Cancel</x-button>
            <x-button type="button" wire:click="resendAgreement" wire:loading.attr="disabled" wire:target="resendAgreement">Resend Email</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal wire:key="payment-reminder-modal" :show="'showPaymentReminderModal'" max-width="sm">
        <x-slot:title>Send Payment Reminder</x-slot:title>
        <p class="text-sm text-slate-400">Send a payment reminder to <span class="font-medium text-slate-200">{{ $agreement->client_email }}</span>.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" @click="open = false" wire:click="closeModal('showPaymentReminderModal')">Cancel</x-button>
            <x-button type="button" wire:click="sendPaymentReminder" wire:loading.attr="disabled" wire:target="sendPaymentReminder">Send Reminder</x-button>
        </x-slot:footer>
    </x-modal>

    {{-- Version Preview Modal --}}
    <x-modal :show="'showVersionPreviewModal'" max-width="4xl" class="p-0" style="z-index: 9999;">
        <div id="agreement-preview-wrap" class="ap-scope" x-data="{ expanded: false }"
             style="overflow-y: auto; max-height: 90vh; background: #fff;">
            <style>
                .ap-scope { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #111827; line-height: 1.6; }
                .ap-scope .ap-doc-wrapper { max-width: 900px; margin: 0 auto; background: #fff; box-shadow: 0 0 0 1px #e5e7eb; }
                .ap-scope .ap-header { display: flex; flex-wrap: wrap; gap: 32px; padding: 28px 40px 20px; background: linear-gradient(135deg, #fafafa 0%, #f3f4f6 100%); border-bottom: 1px solid #e5e7eb; position: relative; }
                .ap-scope .ap-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #7c3aed, #a855f7, #7c3aed); }
                .ap-scope .ap-brand { flex: 0 0 220px; }
                .ap-scope .ap-brand img { max-width: 100%; height: auto; filter: drop-shadow(0 2px 8px rgba(76,29,149,0.15)); }
                .ap-scope .ap-meta { flex: 1; min-width: 0; max-width: 100%; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px 32px; }
                .ap-scope .ap-meta-row { display: flex; gap: 10px; align-items: flex-start; }
                .ap-scope .ap-meta-icon { width: 30px; height: 30px; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #4c1d95; box-shadow: 0 1px 3px rgba(76,29,149,0.1); }
                .ap-scope .ap-meta-icon svg { width: 14px; height: 14px; }
                .ap-scope .ap-meta-label { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
                .ap-scope .ap-meta-value { font-size: 12px; color: #111827; font-weight: 600; }
                .ap-scope .ap-status { display: inline-flex; align-items: center; gap: 4px; padding: 3px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px; }
                .ap-scope .ap-status-signed { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; border: 1px solid #86efac; box-shadow: 0 1px 3px rgba(22,101,52,0.1); }
                .ap-scope .ap-status-pending { background: linear-gradient(135deg, #fef9c3, #fde68a); color: #854d0e; border: 1px solid #fcd34d; box-shadow: 0 1px 3px rgba(133,77,14,0.1); }
                .ap-scope .ap-status-expired { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fca5a5; box-shadow: 0 1px 3px rgba(153,27,27,0.1); }
                .ap-scope .ap-status-default { background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 1px solid #d1d5db; }
                .ap-scope .ap-body { padding: 36px 40px 0; }
                .ap-scope .ap-section-label { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: #4c1d95; font-weight: 800; border-left: 4px solid #4c1d95; padding-left: 12px; margin: 32px 0 14px; font-family: 'Inter', sans-serif; }
                .ap-scope .ap-intro { font-size: 13px; line-height: 1.75; color: #374151; margin: 0 0 8px; }
                .ap-scope .ap-sub-intro { font-size: 12px; color: #6b7280; font-style: italic; margin: 0 0 20px; }
                .ap-scope .ap-block { margin-bottom: 22px; }
                .ap-scope .ap-block h3 { font-size: 12px; color: #4c1d95; font-weight: 800; margin: 0 0 8px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.3px; }
                .ap-scope .ap-block h3 .num { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; background: linear-gradient(135deg, #7c3aed, #4c1d95); color: #fff; border-radius: 6px; font-size: 11px; font-weight: 800; flex-shrink: 0; }
                .ap-scope .ap-block p { font-size: 13px; line-height: 1.75; color: #374151; margin: 0; }
                .ap-scope .ap-content-area { font-size: 13px; line-height: 1.75; color: #111827; }
                .ap-scope .ap-content-area h1, .ap-scope .ap-content-area h2, .ap-scope .ap-content-area h3 { color: #111827; }
                .ap-scope .ap-content-area p { margin: 0 0 6px; }
                .ap-scope .ap-content-area td, .ap-scope .ap-content-area th, .ap-scope .ap-content-area span, .ap-scope .ap-content-area a, .ap-scope .ap-content-area em, .ap-scope .ap-content-area i { color: #000000 !important; }
                .ap-scope .ap-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #e9e5f5; }
                .ap-scope .ap-table { width: 100%; border-collapse: collapse; font-size: 12px; }
                .ap-scope .ap-table thead { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
                .ap-scope .ap-table th { padding: 10px 16px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #4c1d95; border-bottom: 2px solid #e9e5f5; }
                .ap-scope .ap-table td { padding: 12px 16px; color: #374151; border-bottom: 1px solid #f3f4f6; }
                .ap-scope .ap-table tbody tr:last-child td { border-bottom: none; }
                .ap-scope .ap-acceptance { background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #f5f3ff 100%); border: 2px solid #c4b5fd; border-radius: 14px; padding: 28px 32px; margin: 28px 40px 0; display: flex; gap: 24px; align-items: flex-start; box-shadow: 0 4px 16px rgba(76,29,149,0.08), inset 0 2px 12px rgba(76,29,149,0.04); position: relative; overflow: hidden; }
                .ap-scope .ap-acceptance::before { content: ''; position: absolute; top: 50%; right: 20px; transform: translateY(-50%); width: 180px; height: 180px; background: url('{{ asset("watermark-logo.png") }}') no-repeat center center; background-size: contain; opacity: 0.07; pointer-events: none; }
                .ap-scope .ap-acceptance > * { position: relative; z-index: 1; }
                .ap-scope .ap-acceptance-icon { width: 56px; height: 56px; background: linear-gradient(135deg, #7c3aed, #4c1d95); border: none; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #fff; box-shadow: 0 6px 20px rgba(76,29,149,0.3); }
                .ap-scope .ap-acceptance-icon svg { width: 26px; height: 26px; }
                .ap-scope .ap-acceptance h4 { font-size: 15px; color: #1e1b4b; margin: 0 0 14px; font-weight: 800; letter-spacing: 0.8px; }
                .ap-scope .ap-sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 28px; }
                .ap-scope .ap-sig-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 600; margin-bottom: 2px; }
                .ap-scope .ap-sig-value { font-size: 12px; color: #111827; font-weight: 600; }
                .ap-scope .ap-sig-note { margin-top: 14px; padding-top: 12px; border-top: 1px dashed #c4b5fd; font-size: 10.5px; color: #4c1d95; font-weight: 600; display: flex; align-items: center; gap: 6px; }
                .ap-scope .ap-footer { background: linear-gradient(180deg, #1e1b4b 0%, #0f0524 100%); color: #fff; padding: 0; margin-top: 32px; position: relative; overflow: hidden; }
                .ap-scope .ap-footer::before { content: ''; position: absolute; top: -30px; right: -30px; width: 180px; height: 180px; background: url('{{ asset("watermark-logo.png") }}') no-repeat center center; background-size: contain; opacity: 0.04; pointer-events: none; }
                .ap-scope .ap-footer > * { position: relative; z-index: 1; }
                .ap-scope .ap-footer-inner { padding: 32px 40px 0; }
                .ap-scope .ap-footer-logo { text-align: center; margin-bottom: 20px; width: 100%; }
                .ap-scope .ap-footer-logo img { display: block; margin: 0 auto; height: 42px; width: auto; filter: brightness(0) invert(1); opacity: 0.85; }
                .ap-scope .ap-footer-divider { height: 1px; margin: 0 0 18px; background: linear-gradient(90deg, transparent, rgba(167,139,250,0.25), transparent); }
                .ap-scope .ap-footer-strip { display: flex; justify-content: center; gap: 28px; flex-wrap: wrap; font-size: 10.5px; color: #c4b5fd; }
                .ap-scope .ap-footer-strip strong { color: #e9d5ff; font-weight: 700; }
                .ap-scope .ap-footer-disclaimer { margin-top: 18px; padding: 14px 20px; background: rgba(255,255,255,0.03); border-radius: 8px; font-size: 9px; color: #8b7fc7; line-height: 1.65; text-align: center; }
                .ap-scope .ap-footer-bottom { margin-top: 18px; padding: 14px 40px; background: rgba(0,0,0,0.25); display: flex; justify-content: space-between; align-items: center; font-size: 9px; color: #7c6daa; }
                .ap-scope .ap-footer-bottom a { color: #a78bfa; text-decoration: none; transition: color 0.2s; }
                .ap-scope .ap-footer-bottom a:hover { color: #c4b5fd; }
                .ap-scope .ap-footer-links { display: flex; gap: 18px; align-items: center; }
                .ap-scope .ap-footer-social { display: flex; gap: 10px; align-items: center; }
                .ap-scope .ap-footer-social a { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); border-radius: 8px; transition: background 0.2s; }
                .ap-scope .ap-footer-social a:hover { background: rgba(167,139,250,0.25); }
                .ap-scope .ap-footer-social a svg { width: 14px; height: 14px; }
                @media (max-width: 640px) {
                    .ap-scope .ap-header { flex-direction: column; align-items: flex-start; gap: 16px; padding: 20px 20px 18px; }
                    .ap-scope .ap-brand img { max-width: 180px; }
                    .ap-scope .ap-meta { grid-template-columns: 1fr; min-width: 0; max-width: 100%; gap: 8px; }
                    .ap-scope .ap-body { padding: 24px 20px 0; }
                    .ap-scope .ap-acceptance { flex-direction: column; align-items: center; text-align: center; padding: 20px 18px; margin: 20px 20px 0; gap: 16px; }
                    .ap-scope .ap-acceptance-icon { width: 44px; height: 44px; }
                    .ap-scope .ap-acceptance-icon svg { width: 20px; height: 20px; }
                    .ap-scope .ap-sig-grid { grid-template-columns: 1fr; gap: 8px; }
                    .ap-scope .ap-footer-inner { padding: 24px 20px 0; }
                    .ap-scope .ap-footer-strip { flex-direction: column; gap: 8px; text-align: center; }
                    .ap-scope .ap-footer-bottom { flex-direction: column; gap: 10px; text-align: center; padding: 12px 20px; }
                    .ap-scope .ap-footer-links { flex-wrap: wrap; justify-content: center; gap: 12px; }
                    .ap-scope .ap-footer-social { justify-content: center; }
                }
            </style>

            @if ($previewVersion)
            {{-- Header with "Old Version" badge --}}
            <div class="ap-scope" style="padding: 12px 24px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-bottom: 1px solid #fcd34d; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-700 border border-amber-300 flex-shrink-0">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        OLD VERSION — V{{ $previewVersion->version }}
                    </span>
                    <span class="text-sm text-amber-800 truncate">This is an archived version ({{ $previewVersion->created_at->format('M d, Y') }}). Current version is V{{ $agreement->currentVersion->version }}.</span>
                </div>
                <button type="button" wire:click="downloadVersion({{ $previewVersion->id }})"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-purple-500/50 bg-purple-500/15 px-3 py-1.5 text-xs font-semibold text-black transition hover:bg-purple-600 hover:border-purple-600 hover:text-white flex-shrink-0"
                        title="Download PDF">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Download PDF
                </button>
            </div>
                @include('partials._agreement-document', ['version' => $previewVersion, 'pdfMode' => false])
            @else
                <div class="py-12 text-center text-slate-500">
                    <svg class="mx-auto h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    <p class="text-sm">Version not found.</p>
                </div>
            @endif
        </div>
    </x-modal>
</div>
