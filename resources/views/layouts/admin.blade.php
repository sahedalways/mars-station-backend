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
<body class="cosmic-bg min-h-screen font-sans text-slate-100 antialiased">
    <header class="fixed inset-x-0 top-0 z-40 border-b border-purple-500/20 bg-slate-950/80 backdrop-blur-xl">
        <div class="flex h-16 items-center justify-between px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg transition hover:opacity-90" title="Dashboard">
                    <img src="{{ asset('logo.png') }}" alt="Mars Station" style="height: 3.5rem; width: auto; filter: brightness(2) drop-shadow(0 0 6px rgba(168,85,247,0.6));">
                </a>
            </div>

<nav class="hidden items-center gap-1 lg:flex" aria-label="Primary">
    @php
        $nav = [
            [
                'label' => 'Dashboard',
                'route' => 'admin.dashboard',
                'active' => request()->routeIs('admin.dashboard'),
                'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75',
            ],
            [
                'label' => 'Agreements',
                'route' => 'admin.agreements.index',
                'active' => request()->routeIs('admin.agreements.*'),
                'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z',
            ],
            [
                'label' => 'Get Services',
                'route' => 'admin.get-services.index',
                'active' => request()->routeIs('admin.get-services.index'),
                'icon' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0',
            ],
            [
                'label' => 'Our Services',
                'route' => 'admin.services.index',
                'active' => request()->routeIs('admin.services.index'),
                'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            ],
            [
                'label' => 'Reviews',
                'route' => 'admin.reviews.index',
                'active' => request()->routeIs('admin.reviews.index'),
                'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
            ],
            [
                'label' => 'Complaints & Queries',
                'route' => 'admin.complaints.index',
                'active' => request()->routeIs('admin.complaints.*') || request()->routeIs('admin.queries.*'),
                'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            ],
        ];
    @endphp

    @foreach ($nav as $item)
        <a href="{{ route($item['route']) }}"
           @class([
               'flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium transition',
               'text-purple-300 hover:text-white' => $item['active'],
               'text-slate-400 hover:bg-slate-800 hover:text-white' => ! $item['active'],
           ])>
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="{{ $item['icon'] }}"/>
            </svg>
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
            <div class="flex overflow-x-auto scrollbar-none px-4 py-2">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'whitespace-nowrap rounded-lg px-3 py-2 text-xs font-medium transition',
                           'text-purple-300' => $item['active'],
                           'text-slate-400 hover:text-white' => ! $item['active'],
                       ])>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    <main class="mx-auto w-full px-4 pb-16 pt-24 sm:px-6">
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
