@props([
    'color' => 'gray',
    'dot' => false,
])

@php
    $colors = [
        'gray' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/20',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        'purple' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
        'slate' => 'bg-slate-50 text-slate-600 ring-slate-500/10',
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
