@php use Illuminate\Support\Facades\Storage; @endphp

<div class="space-y-5">

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Our Services Management</h1>
            <p class="mt-1 text-sm text-slate-400">Manage all services displayed on the website.</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" wire:click="openCreateModal"
                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-900/50 transition hover:from-purple-500 hover:to-purple-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add New Service
            </button>

            <button type="button" wire:click="openReorderModal"
                    class="inline-flex items-center gap-2 rounded-lg border border-purple-500/30 bg-white/[0.03] px-4 py-2.5 text-sm font-semibold text-slate-200 backdrop-blur-sm transition hover:bg-white/[0.06]">
                <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                </svg>
                Reorder Services
            </button>
        </div>
    </div>

    {{-- ============================================================
         KPI CARDS
    ============================================================ --}}
    @php
        $kpis = [
            [
                'label'    => 'Total Services',
                'value'    => ($counts['total'] ?? 9) . ' / ' . ($counts['max'] ?? 12),
                'sub'      => 'Maximum ' . ($counts['max'] ?? 12) . ' services',
                'valColor' => 'text-purple-300',
                'iconBg'   => 'from-purple-500/20 to-purple-700/10 text-purple-300 ring-purple-500/30',
                'icon'     => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6h1.5m-1.5 3h1.5m-1.5 3h1.5M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
            ],
            [
                'label'    => 'Active Services',
                'value'    => $counts['active'] ?? 9,
                'sub'      => 'Currently published',
                'valColor' => 'text-emerald-300',
                'iconBg'   => 'from-emerald-500/20 to-emerald-700/10 text-emerald-300 ring-emerald-500/30',
                'icon'     => 'M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z',
            ],
            [
                'label'    => 'Total Projects',
                'value'    => $counts['projects'] ?? 23,
                'sub'      => 'Across all services',
                'valColor' => 'text-blue-300',
                'iconBg'   => 'from-blue-500/20 to-blue-700/10 text-blue-300 ring-blue-500/30',
                'icon'     => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25A2.25 2.25 0 0113.5 8.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
            ],
            [
                'label'    => 'Remaining Slots',
                'value'    => $counts['remaining'] ?? 3,
                'sub'      => 'Services can be added',
                'valColor' => 'text-amber-300',
                'iconBg'   => 'from-amber-500/20 to-amber-700/10 text-amber-300 ring-amber-500/30',
                'icon'     => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
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
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead>
                    <tr class="border-b border-purple-500/10 text-[11px] uppercase tracking-wider text-slate-400">
                        <th class="w-10 px-3 py-4"></th>
                        <th class="px-3 py-4 font-medium">Order</th>
                        <th class="px-3 py-4 font-medium">Icon</th>
                        <th class="px-3 py-4 font-medium">Title</th>
                        <th class="px-3 py-4 font-medium">Type</th>
                        <th class="px-3 py-4 font-medium">Description</th>
                        <th class="px-3 py-4 font-medium">Bullet Points</th>
                        <th class="px-3 py-4 font-medium">Recent Work</th>
                        <th class="px-3 py-4 font-medium">Status</th>
                        <th class="px-3 py-4 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @php
                        // Fallback demo icons + type colors
                        $iconMap = [
                            'wordpress'  => ['bg' => 'bg-blue-500/15 text-blue-300 ring-blue-500/30',    'svg' => 'M6.75 7.5l3 2.25-3 2.25m4.5 0h3'],
                            'ecommerce'  => ['bg' => 'bg-pink-500/15 text-pink-300 ring-pink-500/30',    'svg' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z'],
                            'android'    => ['bg' => 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30', 'svg' => 'M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3'],
                            'ios'        => ['bg' => 'bg-orange-500/15 text-orange-300 ring-orange-500/30', 'svg' => 'M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3'],
                            'fullstack'  => ['bg' => 'bg-indigo-500/15 text-indigo-300 ring-indigo-500/30', 'svg' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5'],
                            'marketing'  => ['bg' => 'bg-yellow-500/15 text-yellow-300 ring-yellow-500/30', 'svg' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
                            'devops'     => ['bg' => 'bg-cyan-500/15 text-cyan-300 ring-cyan-500/30',    'svg' => 'M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z'],
                            'ai'         => ['bg' => 'bg-purple-500/15 text-purple-300 ring-purple-500/30', 'svg' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.091 3.091zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z'],
                            'support'    => ['bg' => 'bg-teal-500/15 text-teal-300 ring-teal-500/30',    'svg' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75'],
                        ];
                        $typePillMap = [
                            'wordpress'  => 'bg-blue-500/15 text-blue-300 ring-blue-500/30',
                            'ecommerce'  => 'bg-pink-500/15 text-pink-300 ring-pink-500/30',
                            'android'    => 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30',
                            'ios'        => 'bg-orange-500/15 text-orange-300 ring-orange-500/30',
                            'fullstack'  => 'bg-indigo-500/15 text-indigo-300 ring-indigo-500/30',
                            'marketing'  => 'bg-yellow-500/15 text-yellow-300 ring-yellow-500/30',
                            'devops'     => 'bg-cyan-500/15 text-cyan-300 ring-cyan-500/30',
                            'ai'         => 'bg-purple-500/15 text-purple-300 ring-purple-500/30',
                            'support'    => 'bg-teal-500/15 text-teal-300 ring-teal-500/30',
                        ];
                    @endphp

                    @forelse ($services as $service)
                        @php
                            $typeKey = strtolower(str_replace(['/', ' ', '-'], '', $service->type ?? 'wordpress'));
                            $iconData = $iconMap[$typeKey] ?? $iconMap['wordpress'];
                            $typePill = $typePillMap[$typeKey] ?? 'bg-slate-500/15 text-slate-300 ring-slate-500/30';
                            $bullets = $service->bulletPoints->pluck('text')->values()->all();
                            $projects = $service->projects ?? collect();
                        @endphp
                        <tr class="group transition hover:bg-white/[0.02]">
                            {{-- Drag handle --}}
                            <td class="px-3 py-4">
                                <button type="button" class="cursor-grab text-slate-500 transition hover:text-purple-300 active:cursor-grabbing" title="Drag to reorder">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/>
                                    </svg>
                                </button>
                            </td>

                            {{-- Order --}}
                            <td class="px-3 py-4 text-sm font-semibold text-slate-300">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Icon --}}
                            <td class="px-3 py-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl ring-1 ring-inset {{ $iconData['bg'] }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconData['svg'] }}"/>
                                    </svg>
                                </div>
                            </td>

                            {{-- Title --}}
                            <td class="whitespace-nowrap px-3 py-4">
                                <div class="text-sm font-semibold text-slate-100">{{ $service->title }}</div>
                                <div class="text-[11px] text-slate-500">ID: SRV{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}</div>
                            </td>

                            {{-- Type --}}
                            <td class="whitespace-nowrap px-3 py-4">
                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $typePill }}">
                                    {{ $service->type ?? 'WordPress' }}
                                </span>
                            </td>

                            {{-- Description --}}
                            <td class="px-3 py-4">
                                <p class="max-w-[220px] text-xs leading-relaxed text-slate-300">
                                    {{ \Illuminate\Support\Str::limit($service->description, 70) }}
                                </p>
                            </td>

                            {{-- Bullet Points --}}
                            <td class="px-3 py-4">
                                <ul class="space-y-0.5 text-[11px] text-slate-400">
                                    @foreach (array_slice($bullets, 0, 3) as $bp)
                                        <li class="flex items-start gap-1">
                                            <span class="mt-1 h-1 w-1 shrink-0 rounded-full bg-purple-400"></span>
                                            <span>{{ $bp }}</span>
                                        </li>
                                    @endforeach
                                    @if (count($bullets) > 3)
                                        <li class="text-[10px] text-purple-400">+{{ count($bullets) - 3 }} more</li>
                                    @endif
                                </ul>
                            </td>

                            {{-- Recent Work (Thumbnails) --}}
                            <td class="px-3 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex -space-x-2">
                                        @if ($projects->count() > 0)
                                            @for ($i = 0; $i < min(3, $projects->count()); $i++)
                                                <div class="h-10 w-14 shrink-0 overflow-hidden rounded-md border-2 border-slate-900 bg-gradient-to-br from-slate-700 to-slate-900 ring-1 ring-purple-500/20">
                                                    @if ($projects[$i]->picture_path)
                                                        <img src="{{ $projects[$i]->thumbnail }}" alt="{{ $projects[$i]->title }}" class="h-full w-full object-cover"/>
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-purple-900/40 to-slate-950">
                                                            <svg class="h-4 w-4 text-purple-500/40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endfor
                                        @else
                                            <div class="h-10 w-14 shrink-0 overflow-hidden rounded-md border-2 border-dashed border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 ring-1 ring-purple-500/10">
                                                <div class="flex h-full w-full items-center justify-center">
                                                    <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center rounded-md bg-purple-500/15 px-2 py-0.5 text-[10px] font-semibold text-purple-300 ring-1 ring-inset ring-purple-500/30">
                                        {{ $projects->count() }} {{ Str::plural('Project', $projects->count()) }}
                                    </span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="whitespace-nowrap px-3 py-4">
                                @if ($service->is_active ?? true)
                                    <span class="inline-flex items-center rounded-md bg-emerald-500/15 px-2.5 py-1 text-[11px] font-semibold text-emerald-300 ring-1 ring-inset ring-emerald-500/30">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-500/15 px-2.5 py-1 text-[11px] font-semibold text-slate-400 ring-1 ring-inset ring-slate-500/30">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="whitespace-nowrap px-3 py-4">
                                <div class="flex items-center gap-1.5">
                                    <button type="button" wire:click="editService({{ $service->id }})"
                                            class="rounded-lg border border-purple-500/30 bg-purple-500/10 p-1.5 text-purple-300 transition hover:bg-purple-500/20"
                                            title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="confirmDelete({{ $service->id }})"
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
                            <td colspan="10" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10 ring-1 ring-inset ring-purple-500/30">
                                        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-200">No services yet</p>
                                    <p class="mt-1 text-xs text-slate-500">Click "Add New Service" to create your first one.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ============ PAGINATION FOOTER ============ --}}
        @if ($services->hasPages())
            <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; border-top:1px solid rgba(139,92,246,0.1); padding:12px 16px;">
                <div style="font-size:12px; color:#64748b;">
                    Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} services
                </div>

                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="display:flex; align-items:center; gap:4px;">
                        <button type="button" @if ($services->onFirstPage()) disabled @endif
                                wire:click="previousPage"
                                style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; border:1px solid rgba(139,92,246,0.2); background:rgba(15,23,42,0.6); color:#94a3b8; transition:all 0.15s ease;" disabled-style="opacity:0.4; cursor:not-allowed;">
                            <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>

                        @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                            <button type="button" wire:click="gotoPage({{ $page }})"
                                    style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; font-size:12px; font-weight:600; transition:all 0.15s ease; border:1px solid {{ $services->currentPage() === $page ? 'rgba(139,92,246,0.5)' : 'rgba(139,92,246,0.2)' }}; background:{{ $services->currentPage() === $page ? '#7c3aed' : 'rgba(15,23,42,0.6)' }}; color:{{ $services->currentPage() === $page ? '#fff' : '#cbd5e1' }}; {{ $services->currentPage() === $page ? 'box-shadow:0 4px 12px rgba(124,58,237,0.4);' : '' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        <button type="button" @if (! $services->hasMorePages()) disabled @endif
                                wire:click="nextPage"
                                style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; border:1px solid rgba(139,92,246,0.2); background:rgba(15,23,42,0.6); color:#94a3b8; transition:all 0.15s ease;" disabled-style="opacity:0.4; cursor:not-allowed;">
                            <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================================
         CREATE / EDIT MODAL
    ============================================================ --}}
    @if ($showCreateModal)
        <x-modal :show="'showCreateModal'" max-width="lg">
            <x-slot:title>{{ $editServiceId ? 'Edit Service' : 'Add New Service' }}</x-slot:title>
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">Service Title <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="title" placeholder="e.g. Web Development"
                               class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                        @error('title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-300">Type <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <select wire:model="type"
                                    class="block w-full appearance-none rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 pr-10 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 focus:ring-2 focus:ring-purple-500">
                                <option value="" class="bg-slate-900">Select type...</option>
                                @foreach (['WordPress', 'eCommerce', 'Android', 'iOS', 'Full Stack', 'Marketing', 'DevOps', 'AI', 'Support'] as $t)
                                    <option value="{{ $t }}" class="bg-slate-900">{{ $t }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </div>
                        @error('type') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Description <span class="text-red-400">*</span></label>
                    <textarea wire:model="description" rows="3" placeholder="Brief description of the service..."
                              class="block w-full rounded-lg border-0 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"></textarea>
                    @error('description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Bullet Points</label>
                    <div class="space-y-2">
                        @if (count($bulletPoints) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($bulletPoints as $i => $bp)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-purple-500/25 bg-purple-500/10 px-3 py-1 text-xs font-medium text-purple-300">
                                        {{ $bp }}
                                        <button type="button" wire:click="removeBulletPoint({{ $i }})" class="ml-0.5 rounded-full p-0.5 text-purple-400 transition hover:bg-purple-500/20 hover:text-purple-200">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model.live="newBulletPoint" wire:keydown.enter.prevent="addBulletPoint" placeholder="Type and press Enter to add..."
                                   class="block flex-1 rounded-lg border-0 bg-slate-900/60 px-3.5 py-2 text-sm text-slate-100 ring-1 ring-inset ring-purple-500/20 placeholder:text-slate-500 focus:ring-2 focus:ring-purple-500"/>
                            <button type="button" wire:click="addBulletPoint" class="shrink-0 rounded-lg border border-purple-500/30 bg-purple-500/10 px-3 py-2 text-sm font-medium text-purple-300 transition hover:bg-purple-500/20">Add</button>
                        </div>
                    </div>
                </div>

              <div>
    <label class="mb-1.5 block text-sm font-medium text-slate-300">
        Recent Projects
    </label>

    <div class="space-y-3">

        {{-- Empty state when no images --}}
        @if (count($projects) === 0)
            <div class="flex items-center gap-3 rounded-lg border border-dashed border-slate-700 bg-slate-900/30 p-3">
                <div class="flex h-16 w-20 shrink-0 items-center justify-center rounded-lg bg-slate-800/60 ring-1 ring-inset ring-slate-700/50">
                    <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400">No project images yet</p>
                    <p class="text-[11px] text-slate-500">Upload images to showcase recent work for this service.</p>
                </div>
            </div>
        @endif

        {{-- Uploaded Projects Grid --}}
        @if (count($projects) > 0)
            <div class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                @foreach ($projects as $i => $proj)
                    <div class="group relative overflow-hidden rounded-lg border border-emerald-500/20 bg-emerald-500/5">
                        @if (!empty($proj['picture_path']))
                            <img
                                src="{{ Storage::disk('public')->url($proj['picture_path']) }}"
                                alt="{{ $proj['title'] ?? 'Project' }}"
                                class="h-20 w-full object-cover"
                            />
                        @else
                            <div class="flex h-20 w-full items-center justify-center bg-slate-800/60">
                                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex items-center justify-between px-2 py-1.5">
                            <span class="max-w-[80px] truncate text-[11px] font-medium text-emerald-300">
                                {{ $proj['title'] ?? 'Image' }}
                            </span>
                            <button
                                type="button"
                                wire:click="removeProject({{ $i }})"
                                class="rounded p-0.5 text-slate-500 transition hover:bg-red-500/20 hover:text-red-300"
                                title="Remove"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Upload --}}
        <div class="space-y-2" x-data>
            <label
                class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-emerald-500/30 bg-slate-900/40 px-4 py-3 transition hover:border-emerald-500/60 hover:bg-slate-900/60"
            >
                <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span class="text-xs font-medium text-slate-300">
                    {{ count($projects) > 0 ? 'Add More Images' : 'Upload Project Images' }}
                </span>
                <input
                    type="file"
                    x-ref="projectFileInput"
                    accept="image/*"
                    multiple
                    class="sr-only"
                    @change="
                        $wire.uploadMultiple('newProjectImage', $event.target.files)
                            .then(() => {
                                $wire.processUploadedImages();
                                $refs.projectFileInput.value = '';
                            })
                    "
                />
            </label>
            @error('newProjectImage') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Uploading --}}
        <div wire:loading wire:target="processUploadedImages" class="flex items-center gap-2 text-xs text-emerald-400">
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8"/>
            </svg>
            Uploading project images...
        </div>
    </div>
</div>

                <div class="flex items-center gap-3">
                    <label class="relative inline-flex cursor-pointer items-center gap-2">
                        <input type="checkbox" wire:model="isActive" class="peer sr-only"/>
                        <div class="peer h-5 w-9 rounded-full bg-slate-700 after:absolute after:left-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-slate-400 after:transition-all peer-checked:bg-purple-600 peer-checked:after:translate-x-full peer-checked:after:bg-white"></div>
                    </label>
                    <span class="text-sm text-slate-300">Active (published on website)</span>
                </div>
            </div>
            <x-slot:footer>
                <x-button type="button" variant="secondary" wire:click="closeModal('showCreateModal')">Cancel</x-button>
                <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save">{{ $editServiceId ? 'Update' : 'Save' }}</x-button>
            </x-slot:footer>
        </x-modal>
    @endif

    @if ($showDeleteModal)
        <x-modal :show="'showDeleteModal'" max-width="sm">
            <x-slot:title>Delete Service?</x-slot:title>
            <p class="text-sm text-slate-400">This action cannot be undone.</p>
            <x-slot:footer>
                <x-button type="button" variant="secondary" wire:click="closeModal('showDeleteModal')">Cancel</x-button>
                <x-button type="button" variant="danger" wire:click="deleteService">Delete</x-button>
            </x-slot:footer>
        </x-modal>
    @endif

    @if ($showReorderModal)
        <x-modal :show="'showReorderModal'" max-width="lg">
            <x-slot:title>Reorder Services</x-slot:title>
            <p class="mb-4 text-sm text-slate-400">Use the arrows to change the display order of services.</p>
            <div class="space-y-2">
                @foreach (\App\Models\Service::with('bulletPoints')->orderBy('order_index')->get() as $s)
                    <div class="flex items-center gap-3 rounded-lg border border-purple-500/15 bg-white/[0.02] px-4 py-3">
                        <span class="text-sm font-semibold text-purple-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex-1 text-sm text-slate-200">{{ $s->title }}</span>
                        <span class="text-xs text-slate-500">{{ ucfirst($s->type) }}</span>
                        <div class="flex gap-1">
                            <button type="button" wire:click="move({{ $s->id }}, -1)" {{ $loop->first ? 'disabled' : '' }} class="rounded border border-purple-500/20 bg-purple-500/10 p-1 text-purple-300 transition hover:bg-purple-500/20 disabled:opacity-30">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                            </button>
                            <button type="button" wire:click="move({{ $s->id }}, 1)" {{ $loop->last ? 'disabled' : '' }} class="rounded border border-purple-500/20 bg-purple-500/10 p-1 text-purple-300 transition hover:bg-purple-500/20 disabled:opacity-30">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-slot:footer>
                <x-button type="button" variant="secondary" wire:click="closeModal('showReorderModal')">Close</x-button>
            </x-slot:footer>
        </x-modal>
    @endif
</div>
