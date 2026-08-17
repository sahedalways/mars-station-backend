@props([
    'color' => 'gray',
    'dot' => false,
])

@php
    $colors = [
        'gray' => 'bg-slate-500/15 text-slate-300 ring-slate-400/30',
        'green' => 'bg-emerald-500/15 text-emerald-300 ring-emerald-400/30',
        'red' => 'bg-red-500/15 text-red-300 ring-red-400/30',
        'amber' => 'bg-amber-500/15 text-amber-300 ring-amber-400/30',
        'blue' => 'bg-blue-500/15 text-blue-300 ring-blue-400/30',
        'indigo' => 'bg-indigo-500/15 text-indigo-300 ring-indigo-400/30',
        'purple' => 'bg-purple-500/15 text-purple-300 ring-purple-400/30',
        'slate' => 'bg-slate-500/10 text-slate-400 ring-slate-400/20',
    ];

    $dots = [
        'gray' => 'bg-slate-500',
        'green' => 'bg-emerald-500',
        'red' => 'bg-red-500',
        'amber' => 'bg-amber-500',
        'blue' => 'bg-blue-500',
        'indigo' => 'bg-indigo-500',
        'purple' => 'bg-purple-500',
        'slate' => 'bg-slate-400',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset '.($colors[$color] ?? $colors['gray'])]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dots[$color] ?? $dots['gray'] }}"></span>
    @endif
    {{ $slot }}
</span>
