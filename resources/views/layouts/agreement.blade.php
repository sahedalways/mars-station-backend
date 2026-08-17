<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ $title ?? 'Agreement' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased">
    <div class="min-h-screen px-4 py-8">
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
</body>
</html>
