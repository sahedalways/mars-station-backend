@props([
    'icon' => 'M3.75 12h16.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'title' => 'Nothing here yet',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-2xl border border-dashed border-purple-500/20 bg-white/[0.02] px-6 py-16 text-center']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-800/80">
        <svg class="h-7 w-7 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
        </svg>
    </div>
    <h3 class="mt-4 text-sm font-semibold text-slate-200">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @endif
    @if (isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
