<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ $title ?? 'Agreement' }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='40' fill='%23111'/%3E%3Ccircle cx='50' cy='50' r='28' fill='%236366f1'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased">
    <div class="min-h-screen px-4 py-8">
        <div class="mx-auto w-full max-w-3xl">
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-logo class="h-8 w-8"/>
                    <div>
                        <div class="text-lg font-bold tracking-tight text-slate-900">Mars Station</div>
                        <div class="text-sm text-slate-500">Client Portal</div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. This is a secure document portal.
            </p>
        </div>
    </div>
</body>
</html>
