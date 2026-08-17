<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ $title ?? 'Backend Login' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="cosmic-bg min-h-screen font-sans text-slate-100 antialiased">
    <div class="relative z-10 flex min-h-screen items-center px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
        <div class="mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-8 lg:grid-cols-2 lg:gap-12 xl:gap-20">

            {{-- ============ LEFT SIDE ============ --}}
            <div class="flex flex-col">
                {{-- Logo --}}
                <div class="mb-6 flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Mars Station" style="height: 6rem; width: auto; margin-left: -0.75rem;">
                </div>

                <div class="mb-5 h-px w-24 bg-purple-500/40"></div>

                <h2 class="mb-3 text-xl font-semibold text-purple-300 sm:text-2xl">
                    Welcome to Mars Station Backend
                </h2>
                <p class="mb-6 max-w-md text-sm leading-relaxed text-slate-400">
                    Manage your digital solutions, track performance, and grow your business — all from one powerful dashboard.
                </p>

                {{-- Feature cards --}}
                <div class="max-w-md space-y-4 rounded-2xl border border-purple-500/10 bg-white/[0.02] p-5 backdrop-blur-sm">
                    <x-feature-item
                        title="Secure Access"
                        description="Email OTP authentication for maximum security"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                        </svg>
                    </x-feature-item>

                    <x-feature-item
                        title="24-Hour Session"
                        description="Automatic logout after 24 hours of inactivity"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </x-feature-item>

                    <x-feature-item
                        title="Email Verification"
                        description="One-time password sent to your registered email"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </x-feature-item>
                </div>

                {{-- Copyright (visible only on lg+) --}}
                <p class="mt-8 hidden text-xs text-slate-500 lg:block">
                    &copy; {{ date('Y') }} Mars Station. All rights reserved.
                </p>
            </div>

            {{-- ============ RIGHT SIDE ============ --}}
            <div class="flex flex-col">
                <div class="rounded-2xl border border-purple-500/20 bg-slate-950/60 p-6 shadow-2xl shadow-purple-950/50 backdrop-blur-xl sm:p-8 lg:p-10">
                    @if (session('error'))
                        <x-alert type="error" :message="session('error')" class="mb-4"/>
                    @endif

                    @if (session('success'))
                        <x-alert type="success" :message="session('success')" class="mb-4"/>
                    @endif

                    {{ $slot }}
                </div>

                {{-- Session Security Info --}}
                <div class="mt-5 flex items-start gap-3 rounded-2xl border border-purple-500/15 bg-white/[0.02] p-4 backdrop-blur-sm">
                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-purple-500/10 text-purple-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                    </div>
                    <div class="text-sm">
                        <div class="font-semibold text-purple-300">Session Security</div>
                        <p class="mt-0.5 text-xs leading-relaxed text-slate-400">
                            You will be automatically logged out after 24 hours of inactivity.
                            Please login again using a new OTP.
                        </p>
                    </div>
                </div>

                {{-- Copyright (visible only on small screens) --}}
                <p class="mt-6 text-center text-xs text-slate-500 lg:hidden">
                    &copy; {{ date('Y') }} Mars Station. All rights reserved.
                </p>
            </div>

        </div>
    </div>
    @livewireScripts
</body>
</html>
