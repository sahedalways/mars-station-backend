@props([
    'title' => '',
    'icon' => null,
    'href' => '#',
])

@php
    $icons = [
        'agreement' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'payment'   => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
        'service'   => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z',
    ];
    $iconPath = $icons[$icon] ?? $icons['agreement'];
@endphp

<div class="overflow-hidden rounded-2xl border border-purple-500/15 bg-gradient-to-br from-white/[0.04] to-white/[0.01] shadow-lg shadow-purple-950/30 backdrop-blur-sm">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-purple-500/10 px-5 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-500/15 text-purple-300 ring-1 ring-inset ring-purple-500/30">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                </svg>
            </div>
            <h2 class="text-base font-bold text-white">{{ $title }}</h2>
        </div>

        <a href="{{ $href }}" class="group inline-flex items-center gap-1 text-xs font-semibold text-purple-400 transition hover:text-purple-300">
            View All
            <svg class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
            </svg>
        </a>
    </div>

    {{-- Body --}}
    <div class="overflow-x-auto px-5 pb-4">
        {{ $slot }}
    </div>
</div>
