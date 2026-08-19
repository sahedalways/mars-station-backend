<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ $title ?? 'Agreement' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased">
    <div class="min-h-screen px-4 pt-24 pb-8 sm:px-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="mb-10 text-center">
                <img src="{{ asset('logo.png') }}" alt="Mars Station" class="mx-auto h-12 w-auto" style="filter: brightness(2) drop-shadow(0 0 8px rgba(168,85,247,0.6));">
                <div class="mt-3 text-xs font-medium tracking-widest text-slate-500 uppercase mb-5">Client Portal</div>
            </div>

            <div>
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. This is a secure document portal.
            </p>
        </div>
    </div>

    <div x-data="agreementToast()" x-show="visible" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="fixed bottom-6 right-6 z-[60]" role="status">
        <div x-show="visible" x-cloak class="flex max-w-sm items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-lg">
            <div class="flex-1" x-text="message"></div>
            <button type="button" @click="visible = false" class="shrink-0 text-emerald-400 hover:text-emerald-600" aria-label="Dismiss">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    <script>
        function agreementToast() {
            return {
                visible: false,
                message: '',
                timer: null,
                init() {
                    Livewire.on('toast', (payload) => {
                        this.message = payload[0]?.message ?? 'Done';
                        this.visible = true;
                        clearTimeout(this.timer);
                        this.timer = setTimeout(() => { this.visible = false; }, 4000);
                    });
                },
            };
        }
    </script>
    @livewireScripts
</body>
</html>
