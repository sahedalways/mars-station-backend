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
            'absolute z-30 mt-2 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg',
            $width,
            'right-0' => $align === 'right',
            'left-0' => $align === 'left',
        ])
        @click="open = false"
    >
        {{ $slot }}
    </div>
</div>
