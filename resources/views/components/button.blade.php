@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-500 focus-visible:ring-indigo-600',
        'secondary' => 'bg-slate-800 text-slate-200 ring-1 ring-inset ring-slate-600 hover:bg-slate-700 hover:text-white focus-visible:ring-slate-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-500 focus-visible:ring-red-600',
        'ghost' => 'text-slate-400 hover:bg-slate-800 hover:text-white focus-visible:ring-slate-400',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-500 focus-visible:ring-emerald-600',
    ];

    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-2.5 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $isSubmit = $type === 'submit';

    $base = 'inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg font-semibold shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-60 ';

    $style = $isSubmit
        ? 'bg-indigo-600 text-white hover:bg-indigo-500 focus-visible:ring-indigo-600'
        : ($variants[$variant] ?? $variants['primary']);

    $sizeClass = $sizes[$size] ?? $sizes['md'];

    $loadingTarget = $attributes->get('wire:target');
@endphp

<button
    {{ $attributes->merge([
        'type' => $type,
        'class' => $base.$style.' '.$sizeClass,
    ]) }}
>
    @if($loadingTarget)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $loadingTarget }}" class="inline-flex items-center gap-2" aria-live="polite">
            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="sr-only">Loading</span>
        </span>
    @else
        {{ $slot }}
    @endif
</button>
