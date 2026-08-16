<div>
    <h1 class="text-xl font-semibold text-slate-900">Sign in to Admin</h1>
    <p class="mt-1 text-sm text-slate-500">Enter your admin email to receive a one-time login code.</p>

    <form wire:submit="requestOtp" class="mt-6 space-y-4">
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email address</label>
            <x-input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                required
                autofocus
                placeholder="you@example.com"
            />
            @error('email')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <x-button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="requestOtp">
            <span wire:loading.remove wire:target="requestOtp">Send Login Code</span>
            <span wire:loading wire:target="requestOtp">Sending&hellip;</span>
        </x-button>
    </form>

    <p class="mt-4 text-center text-xs text-slate-400">
        Only authorized admin accounts receive a code.
    </p>
</div>
