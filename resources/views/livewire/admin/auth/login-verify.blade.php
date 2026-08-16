<div>
    <h1 class="text-xl font-semibold text-slate-900">Enter your code</h1>
    <p class="mt-1 text-sm text-slate-500">
        We sent a one-time code to <strong>{{ $email }}</strong>.
    </p>

    <form wire:submit="verifyOtp" class="mt-6 space-y-4">
        <div>
            <label for="otp" class="mb-1.5 block text-sm font-medium text-slate-700">Login code</label>
            <x-input
                id="otp"
                type="text"
                inputmode="numeric"
                wire:model="otp"
                maxlength="6"
                autocomplete="one-time-code"
                required
                autofocus
                placeholder="••••••"
                class="text-center text-2xl tracking-[0.5em]"
            />
            @error('otp')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <x-button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="verifyOtp">
            <span wire:loading.remove wire:target="verifyOtp">Verify &amp; Sign In</span>
            <span wire:loading wire:target="verifyOtp">Verifying&hellip;</span>
        </x-button>
    </form>

    <div class="mt-4 text-center">
        <button type="button" wire:click="resendOtp" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
            Request a new code
        </button>
    </div>
</div>
