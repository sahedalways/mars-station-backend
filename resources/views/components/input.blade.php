@props([
    'label' => null,
    'error' => null,
    'name' => null,
    'icon' => null,
])

@php
    $classes = 'block w-full rounded-lg border-0 py-3 text-sm text-slate-100 shadow-sm ring-1 ring-inset bg-slate-900/60 focus:ring-2 focus:ring-inset placeholder:text-slate-500 sm:leading-6 '.
        ($icon ? 'pl-10 pr-3 ' : 'px-3.5 ').
        ($error ? 'ring-red-500/50 focus:ring-red-500' : 'ring-purple-500/20 focus:ring-purple-500');
@endphp

@if ($label)
    <label @if ($name) for="{{ $name }}" @endif class="mb-1.5 block text-sm font-medium text-slate-300">
        {{ $label }}
    </label>
@endif

<div class="relative">
    @if ($icon)
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
            {!! $icon !!}
        </div>
    @endif

    <input
        @if ($name) name="{{ $name }}" id="{{ $name }}" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    />
</div>

@if ($error)
    <p class="mt-1.5 text-sm text-red-400">{{ $error }}</p>
@endif
