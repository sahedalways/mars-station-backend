<div>
    {{-- Lock Icon --}}
    <div class="mb-4 flex justify-center">
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-purple-500/15 ring-1 ring-purple-500/30">
            <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
    </div>

    <h1 class="text-center text-2xl font-bold text-white">Backend Login</h1>
    <p class="mt-1 text-center text-sm text-slate-400">Secure access to Mars Station backend</p>

    {{-- Stepper --}}
    <div class="mt-8 flex items-center justify-center gap-2 px-2">
        {{-- Step 1 --}}
        <div class="flex flex-col items-center">
            <div @class([
                'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold transition',
                'bg-purple-600 text-white shadow-lg shadow-purple-900/50' => $step === 1,
                'bg-slate-800 text-slate-500 ring-1 ring-slate-700' => $step !== 1,
            ])>
                @if ($step === 2)
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                @else
                    1
                @endif
            </div>
            <div @class([
                'mt-1.5 text-xs font-medium',
                'text-slate-300' => $step === 1,
                'text-slate-500' => $step !== 1,
            ])>
                Enter Email
            </div>
        </div>

        <div class="mb-5 h-px w-12 bg-gradient-to-r from-purple-500 to-slate-700"></div>

        {{-- Step 2 --}}
        <div class="flex flex-col items-center">
            <div @class([
                'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold transition',
                'bg-purple-600 text-white shadow-lg shadow-purple-900/50' => $step === 2,
                'bg-slate-800 text-slate-500 ring-1 ring-slate-700' => $step !== 2,
            ])>
                2
            </div>
            <div @class([
                'mt-1.5 text-xs font-medium',
                'text-slate-300' => $step === 2,
                'text-slate-500' => $step !== 2,
            ])>
                Verify OTP
            </div>
        </div>

        <div class="mb-5 h-px w-12 bg-slate-700"></div>

        {{-- Step 3 --}}
        <div class="flex flex-col items-center">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-sm font-semibold text-slate-500 ring-1 ring-slate-700">
                3
            </div>
            <div class="mt-1.5 text-xs font-medium text-slate-500">Access Dashboard</div>
        </div>
    </div>

    <div class="my-6 h-px bg-purple-500/10"></div>

    {{-- Step 1: Email --}}
    @if ($step === 1)
        <div wire:key="step-email" wire:transition="transition-opacity duration-300">
            <h2 class="text-base font-semibold text-white">Step 1: Enter Your Email</h2>

            <form wire:submit="requestOtp" class="mt-4 space-y-4">
                <x-input
                    label="Email Address"
                    name="email"
                    type="email"
                    wire:model.live="email"
                    autocomplete="email"
                    required
                    autofocus
                    placeholder="you@example.com"
                    :error="$errors->first('email')"
                    :icon="'<svg class=\'h-4 w-4\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75\'/></svg>'"
                />

                <p class="text-xs text-slate-500">
                    We'll send a 6-digit OTP to your registered email address.
                </p>

                <x-button
                    type="submit"
                    variant="primary"
                    size="md"
                    class="w-full"
                    wire:loading.attr="disabled"
                    wire:target="requestOtp"
                >
                    <span wire:loading.remove wire:target="requestOtp">Send OTP</span>
                    <span wire:loading wire:target="requestOtp" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        Sending&hellip;
                    </span>
                </x-button>
            </form>
        </div>
    @endif

    {{-- Step 2: OTP --}}
    @if ($step === 2)
        <div wire:key="step-otp" wire:transition="transition-opacity duration-300">
            <h2 class="text-base font-semibold text-white">Step 2: Verify Your Code</h2>
            <p class="mt-1 text-sm text-slate-400">
                We sent a one-time code to <strong class="text-slate-200">{{ $email }}</strong>.
            </p>

            <form wire:submit="verifyOtp" class="mt-4 space-y-4">
                <div x-data="{ otpLength: 0 }">
                    <x-input
                        id="otp"
                        label="Verification Code"
                        type="text"
                        inputmode="numeric"
                        wire:model.live="otp"
                        maxlength="6"
                        autocomplete="one-time-code"
                        required
                        autofocus
                        placeholder="••••••"
                        class="text-center text-2xl tracking-[0.5em]"
                        :error="$errors->first('otp')"
                        x-on:input="otpLength = $event.target.value.length"
                        x-on:keydown.enter.prevent=""
                    />

                    <div class="mt-1.5 flex items-center justify-between">
                        <p class="text-xs text-slate-500">6-digit code</p>

                    </div>
                </div>

                <x-button
                    type="submit"
                    variant="primary"
                    size="md"
                    class="w-full"
                >
                    Verify &amp; Sign In
                </x-button>
            </form>

            <div class="mt-4 flex items-center justify-between text-sm">
                <button type="button" wire:click="backToEmail" class="cursor-pointer font-medium text-slate-400 transition hover:text-slate-200">
                    &larr; Change Email
                </button>
                <button type="button" wire:click="backToEmail" class="cursor-pointer font-medium text-indigo-400 transition hover:text-indigo-300">
                    Request a new code
                </button>
            </div>
        </div>
    @endif

    {{-- Trust badge --}}
    <div class="mt-6 flex items-center justify-center gap-2 rounded-lg border border-purple-500/10 bg-white/[0.02] py-2.5 text-xs text-slate-400">
        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
        </svg>
        <span>Secure</span>
        <span class="text-purple-500">•</span>
        <span>Encrypted</span>
        <span class="text-purple-500">•</span>
        <span>Protected</span>
    </div>

    {{-- Global feedback modal --}}
    <x-modal name="login-feedback" :show="'showModal'" :title="$modalTitle" maxWidth="sm">
        <div class="flex items-start gap-3">
            <div @class([
                'flex h-9 w-9 shrink-0 items-center justify-center rounded-full',
                'bg-emerald-100 text-emerald-600' => $modalType === 'success',
                'bg-red-100 text-red-600' => $modalType === 'error',
                'bg-blue-100 text-blue-600' => $modalType !== 'success' && $modalType !== 'error',
            ])>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    @if ($modalType === 'success')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @elseif ($modalType === 'error')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    @endif
                </svg>
            </div>
            <p class="text-sm leading-relaxed text-slate-600">{{ $modalMessage }}</p>
        </div>

        <x-slot name="footer">
            <x-button type="button" variant="primary" size="sm" @click="open = false">
                Continue
            </x-button>
        </x-slot>
    </x-modal>
</div>
