@props([
    'label' => null,
    'error' => null,
    'name' => null,
])

@php
    $classes = 'block w-full rounded-lg border-0 bg-white py-2 pl-3 pr-10 text-sm text-slate-900 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset placeholder:text-slate-400 sm:leading-6 '.
        ($error ? 'ring-red-300 focus:ring-red-500' : 'ring-slate-300 focus:ring-indigo-600');
@endphp

@if ($label)
    <label @if ($name) for="{{ $name }}" @endif class="mb-1.5 block text-sm font-medium text-slate-700">
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
    <p class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
@endif
