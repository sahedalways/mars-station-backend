<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased">
    <header class="fixed inset-x-0 top-0 z-40 border-b border-slate-800 bg-slate-900">
        <div class="mx-auto flex h-16 max-w-[1600px] items-center justify-between px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg transition hover:opacity-90" title="Dashboard">
                    <x-logo class="h-9 w-9"/>
                    <span class="text-lg font-bold tracking-tight text-white">Mars Station</span>
                </a>
            </div>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Primary">
                @php
                    $nav = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard')],
                        ['label' => 'Agreements', 'route' => 'admin.agreements.index', 'active' => request()->routeIs('admin.agreements.*')],
                        ['label' => 'Get Services', 'route' => 'admin.get-services.index', 'active' => request()->routeIs('admin.get-services.index')],
                        ['label' => 'Our Services', 'route' => 'admin.services.index', 'active' => request()->routeIs('admin.services.index')],
                        ['label' => 'Reviews', 'route' => 'admin.reviews.index', 'active' => request()->routeIs('admin.reviews.index')],
                        ['label' => 'Complaints', 'route' => 'admin.complaints.index', 'active' => request()->routeIs('admin.complaints.index')],
                        ['label' => 'Queries', 'route' => 'admin.queries.index', 'active' => request()->routeIs('admin.queries.index')],
                    ];
                @endphp
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'rounded-lg px-3 py-2 text-sm font-medium transition',
                           'bg-indigo-600 text-white' => $item['active'],
                           'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $item['active'],
                       ])>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <div class="hidden items-center gap-2 rounded-lg bg-slate-800 px-3 py-1.5 sm:flex">
                    <span class="text-sm font-medium text-slate-200">{{ auth('admin')->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-button type="submit" size="sm">Logout</x-button>
                </form>
            </div>
        </div>

        <nav class="border-t border-slate-800 lg:hidden" aria-label="Mobile">
            <div class="flex overflow-x-auto px-4 py-2">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition',
                           'bg-indigo-600 text-white' => $item['active'],
                           'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $item['active'],
                       ])>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    <main class="mx-auto w-full max-w-[1600px] px-4 pb-16 pt-24 sm:px-6">
        <div x-data="{}">
            {{ $slot }}
        </div>
    </main>

    <x-toast/>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.added', ({ el }) => Alpine.initTree(el));
        });
    </script>
</body>
</html>
