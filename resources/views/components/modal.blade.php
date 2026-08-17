@props([
    'name' => 'modal',
    'maxWidth' => 'lg',
    'show' => 'show',
    'title' => '',
])

@php
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        'full' => 'max-w-full',
    ];
@endphp

<div
    x-data="{ open: @entangle($show) }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false"
    class="relative z-50"
    role="dialog"
    aria-modal="true"
>
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <div x-show="open" x-transition class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="open"
                x-transition
                class="relative w-full overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 {{ $widths[$maxWidth] ?? $widths['lg'] }}"
            >
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
                    <button type="button" @click="open = false" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Close">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5">
                    {{ $slot }}
                </div>
                @if (isset($footer))
                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
