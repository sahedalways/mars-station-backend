@props([
    'title' => '',
    'description' => '',
])

<div class="flex items-start gap-3">
    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-500/10 text-purple-400 ring-1 ring-purple-500/20">
        {{ $slot }}
    </div>
    <div>
        <div class="text-sm font-semibold text-slate-100">{{ $title }}</div>
        <div class="text-xs leading-relaxed text-slate-400">{{ $description }}</div>
    </div>
</div>
