@props([
    'align' => 'right',
    'width' => 'w-56',
])

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    class="relative inline-block"
>
    <div @click="open = !open" @keydown.escape="open = false">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition
        x-cloak
        @class([
            'absolute z-[100] mt-2 rounded-xl border border-purple-500/20 bg-slate-900/95 py-1.5 shadow-lg shadow-purple-950/40 backdrop-blur-xl scrollbar-none',
            $width,
            'right-0' => $align === 'right',
            'left-0' => $align === 'left',
        ])
        @click="open = false"
    >
        {{ $slot }}
    </div>
</div>
