<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit {{ $agreement->agreement_number }}</h1>
            <p class="mt-1 text-sm text-slate-500">
                @if ($isSigned)
                    This agreement is signed. Editing will create a new version; the signed version stays immutable.
                @else
                    Editing the current pending version in place.
                @endif
            </p>
        </div>
        <a href="{{ route('admin.agreements.show', $agreement) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Back to Agreement</a>
    </div>

    @if ($isSigned)
        <x-alert type="warning">
            A new version will be created and a fresh secure link generated. The client will receive the updated agreement by email.
        </x-alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Agreement Details</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input label="Agreement Title" wire:model="title" :error="$errors->first('title')"/>
                </div>
                <x-input label="Client Full Name" wire:model="client_name" :error="$errors->first('client_name')"/>
                <x-input label="Client Email" type="email" wire:model="client_email" :error="$errors->first('client_email')"/>
                <x-input label="Client Mobile" wire:model="client_mobile" :error="$errors->first('client_mobile')"/>
                <x-input label="Validity Date" type="date" wire:model="validity_date" :error="$errors->first('validity_date')"/>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Agreement Content</label>
                    <textarea wire:model="content" rows="10"
                              class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                    @error('content')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Payment</h2>
            <p class="mt-1 text-sm text-slate-500">Payment type: {{ $agreement->payment_type->label() }}</p>

            @if ($payment_type === 'full')
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-input label="Payment Title" wire:model="full_title" :error="$errors->first('full_title')"/>
                    <x-input label="Amount (£)" type="number" step="0.01" min="0" wire:model="full_amount" :error="$errors->first('full_amount')"/>
                </div>
            @endif

            @if ($payment_type === 'milestone')
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-slate-700">Milestones</h3>
                        <x-button type="button" size="sm" variant="secondary" wire:click="addMilestone">+ Add Milestone</x-button>
                    </div>
                    @foreach ($milestones as $index => $milestone)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Milestone {{ $index + 1 }}</span>
                                <button type="button" wire:click="removeMilestone({{ $index }})" class="text-sm text-red-600 hover:text-red-500" @if (count($milestones) === 1) disabled @endif>Remove</button>
                            </div>
                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <x-input label="Title" wire:model="milestones.{{ $index }}.title" :error="$errors->first('milestones.'.$index.'.title')"/>
                                <x-input label="Amount (£)" type="number" step="0.01" min="0" wire:model="milestones.{{ $index }}.amount" :error="$errors->first('milestones.'.$index.'.amount')"/>
                                <div class="sm:col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                                    <textarea wire:model="milestones.{{ $index }}.description" rows="2"
                                              class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($payment_type === 'subscription')
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <x-input label="Subscription Title" wire:model="subscription_title" :error="$errors->first('subscription_title')"/>
                    <x-input label="Amount (£)" type="number" step="0.01" min="0" wire:model="subscription_amount" :error="$errors->first('subscription_amount')"/>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Frequency</label>
                        <x-select wire:model="subscription_frequency">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </x-select>
                    </div>
                </div>
            @endif

            @if ($payment_type === 'none')
                <p class="mt-2 text-sm text-slate-400">No payment is required for this agreement.</p>
            @endif
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.agreements.show', $agreement) }}">
                <x-button type="button" variant="secondary">Cancel</x-button>
            </a>
            <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $isSigned ? 'Save as New Version' : 'Save Changes' }}</span>
                <span wire:loading wire:target="save">Saving&hellip;</span>
            </x-button>
        </div>
    </form>
</div>
