<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create Agreement</h1>
            <p class="mt-1 text-sm text-slate-500">Send a new agreement to a client for review and signature.</p>
        </div>
        <a href="{{ route('admin.agreements.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Back to Agreements</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Agreement Details</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input label="Agreement Title" name="title" wire:model="title" :error="$errors->first('title')" placeholder="Law Farm Web"/>
                </div>

                <x-input label="Client Full Name" name="client_name" wire:model="client_name" :error="$errors->first('client_name')" placeholder="John Smith"/>

                <x-input label="Client Email" name="client_email" type="email" wire:model="client_email" :error="$errors->first('client_email')" placeholder="client@example.com"/>

                <x-input label="Client Mobile" name="client_mobile" wire:model="client_mobile" :error="$errors->first('client_mobile')" placeholder="+44 7700 900123"/>

                <x-input label="Validity Date" name="validity_date" type="date" wire:model="validity_date" :error="$errors->first('validity_date')"/>

                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Agreement Content</label>
                    <textarea
                        wire:model="content"
                        rows="10"
                        class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:leading-6"
                        placeholder="Terms, scope of work, deliverables, payment schedule, legal terms..."></textarea>
                    @error('content')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Attachment <span class="font-normal text-slate-400">(optional)</span></label>
                    <input type="file" wire:model="attachment" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                           class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"/>
                    <div wire:loading wire:target="attachment" class="mt-2 text-xs text-slate-400">Uploading…</div>
                    @error('attachment')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if ($attachment)
                        <p class="mt-2 text-xs text-slate-500">Selected: {{ $attachment->getClientOriginalName() }} ({{ number_format($attachment->getSize() / 1024, 1) }} KB)</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Payment</h2>
            <div class="mt-4">
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Payment Type</label>
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    @foreach ([
                        'none' => 'No Payment Required',
                        'full' => 'Full Payment',
                        'milestone' => 'Milestone Payment',
                        'subscription' => 'Subscription Payment',
                    ] as $value => $label)
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="payment_type" value="{{ $value }}" class="peer sr-only">
                            <span class="block rounded-lg border border-slate-300 px-4 py-3 text-center text-sm font-medium text-slate-700 transition peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('payment_type')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($payment_type === 'full')
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-input label="Payment Title" name="full_title" wire:model="full_title" :error="$errors->first('full_title')" placeholder="Law Farm Web"/>
                    <x-input label="Amount (£)" name="full_amount" type="number" step="0.01" min="0" wire:model="full_amount" :error="$errors->first('full_amount')" placeholder="380.99"/>
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
                                <button type="button" wire:click="removeMilestone({{ $index }})" class="text-sm text-red-600 hover:text-red-500" @if (count($milestones) === 1) disabled @endif>
                                    Remove
                                </button>
                            </div>
                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <x-input label="Title" wire:model="milestones.{{ $index }}.title" :error="$errors->first('milestones.'.$index.'.title')" placeholder="Deposit"/>
                                <x-input label="Amount (£)" type="number" step="0.01" min="0" wire:model="milestones.{{ $index }}.amount" :error="$errors->first('milestones.'.$index.'.amount')" placeholder="100.00"/>
                                <div class="sm:col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                                    <textarea wire:model="milestones.{{ $index }}.description" rows="2"
                                              class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @error('milestones')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            @if ($payment_type === 'subscription')
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <x-input label="Subscription Title" name="subscription_title" wire:model="subscription_title" :error="$errors->first('subscription_title')" placeholder="Hosting & Support"/>
                    <x-input label="Amount (£)" name="subscription_amount" type="number" step="0.01" min="0" wire:model="subscription_amount" :error="$errors->first('subscription_amount')" placeholder="49.99"/>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Frequency</label>
                        <x-select name="subscription_frequency" wire:model="subscription_frequency" :error="$errors->first('subscription_frequency')">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </x-select>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.agreements.index') }}">
                <x-button type="button" variant="secondary">Cancel</x-button>
            </a>
            <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Create &amp; Send Agreement</span>
                <span wire:loading wire:target="save">Creating&hellip;</span>
            </x-button>
        </div>
    </form>
</div>
