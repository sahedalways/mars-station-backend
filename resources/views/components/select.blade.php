@props([
    'label' => null,
    'error' => null,
    'name' => null,
])

@php
    $classes = 'block w-full rounded-lg border-0 bg-slate-900/60 py-2 pl-3 pr-10 text-sm text-slate-100 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset placeholder:text-slate-500 sm:leading-6 '.
        ($error ? 'ring-red-500/50 focus:ring-red-500' : 'ring-purple-500/20 focus:ring-purple-500');
@endphp

@if ($label)
    <label @if ($name) for="{{ $name }}" @endif class="mb-1.5 block text-sm font-medium text-slate-300">
        {{ $label }}
    </label>
@endif

<select
    @if ($name) name="{{ $name }}" id="{{ $name }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</select>

@if ($error)
    <p class="mt-1.5 text-sm text-red-400">{{ $error }}</p>
@endif
