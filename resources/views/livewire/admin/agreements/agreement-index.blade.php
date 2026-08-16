<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Agreements</h1>
            <p class="mt-1 text-sm text-slate-500">Create, manage and track client agreements.</p>
        </div>
    </div>

    <div class="border-b border-slate-200">
        <nav class="-mb-px flex gap-6 overflow-x-auto" aria-label="Agreement tabs">
            <button type="button" wire:click="setTab('all')" wire:key="tab-all"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition', 'border-indigo-600 text-indigo-600' => $tab === 'all', 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => $tab !== 'all'])>
                All Agreements
            </button>
            <button type="button" wire:click="setTab('create')" wire:key="tab-create"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition', 'border-indigo-600 text-indigo-600' => $tab === 'create', 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => $tab !== 'create'])>
                Create Agreement
            </button>
            <button type="button" wire:click="setTab('payments')" wire:key="tab-payments"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition', 'border-indigo-600 text-indigo-600' => $tab === 'payments', 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => $tab !== 'payments'])>
                Payment History
            </button>
            <button type="button" wire:click="setTab('archived')" wire:key="tab-archived"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition', 'border-indigo-600 text-indigo-600' => $tab === 'archived', 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => $tab !== 'archived'])>
                Archived Agreements
            </button>
        </nav>
    </div>

    @if ($tab === 'create')
        <livewire:admin.agreements.create-agreement/>
    @elseif ($tab === 'payments')
        <livewire:admin.payments.payment-history :embedded="true"/>
    @else
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-4">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="md:col-span-1">
                        <x-input type="search" wire:model.live.debounce.300ms="search" placeholder="Search number, client, email, mobile…"/>
                    </div>
                    <div>
                        <x-select wire:model.live="status">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <x-select wire:model.live="payment">
                            <option value="">All Payments</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                            <option value="none">None</option>
                        </x-select>
                    </div>
                    <div>
                        <x-select wire:model.live="dateRange">
                            <option value="">All Dates</option>
                            <option value="7d">Last 7 Days</option>
                            <option value="30d">Last 30 Days</option>
                            <option value="6m">Last 6 Months</option>
                            <option value="1y">Last Year</option>
                        </x-select>
                    </div>
                </div>
            </div>

            @if ($agreements->isEmpty())
                <div class="p-6">
                    <x-empty-state
                        title="{{ $tab === 'archived' ? 'No archived agreements' : 'No agreements found' }}"
                        :description="$tab === 'archived' ? 'Archived agreements will appear here.' : 'Create your first agreement to get started.'"/>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <button type="button" wire:click="sortBy('agreement_number')" class="hover:text-slate-700">Agreement</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <button type="button" wire:click="sortBy('client_name')" class="hover:text-slate-700">Client</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <button type="button" wire:click="sortBy('created_at')" class="hover:text-slate-700">Created</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <button type="button" wire:click="sortBy('updated_at')" class="hover:text-slate-700">Updated</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <button type="button" wire:click="sortBy('status')" class="hover:text-slate-700">Status</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Payment</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($agreements as $agreement)
                                @php
                                    $paidAmount = $agreement->payments->where('status', 'succeeded')->sum('amount_pence');
                                    $hasSucceeded = $agreement->payments->contains('status', 'succeeded');
                                    $paymentStatus = $agreement->payment_type->value === 'none'
                                        ? 'none'
                                        : ($hasSucceeded ? 'paid' : ($agreement->payments->contains('status', 'failed') ? 'failed' : 'unpaid'));
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <a href="{{ route('admin.agreements.show', $agreement) }}" class="font-medium text-indigo-600 hover:text-indigo-500">{{ $agreement->agreement_number }}</a>
                                        <p class="max-w-[200px] truncate text-xs text-slate-500">{{ $agreement->title }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-slate-900">{{ $agreement->client_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $agreement->client_email }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-500">{{ $agreement->created_at->format('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-500">{{ $agreement->updated_at->diffForHumans() }}</td>
                                    <td class="px-4 py-3">
                                        <x-badge :color="match($agreement->status->value) { 'completed', 'subscribed' => 'green', 'expired', 'terminated' => 'red', 'signed', 'in_progress' => 'blue', 'pending' => 'amber', default => 'gray' }">
                                            {{ $agreement->status->label() }}
                                        </x-badge>
                                    </td>
                                    <td class="px-4 py-3">
                                        <x-badge :color="match($paymentStatus) { 'paid' => 'green', 'failed', 'refunded' => 'red', 'unpaid' => 'amber', default => 'gray' }">
                                            {{ ucfirst($paymentStatus) }}
                                        </x-badge>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-slate-900">
                                        {{ \App\Support\Money::format($paidAmount) }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <x-dropdown>
                                            <x-slot:trigger>
                                                <button type="button" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Actions">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>
                                            <div class="py-1">
                                                <a href="{{ route('admin.agreements.show', $agreement) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">View</a>
                                                @if ($tab !== 'archived')
                                                    <a href="{{ route('admin.agreements.edit', $agreement) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Edit</a>
                                                    <button type="button" wire:click="openStatusModal({{ $agreement->id }})" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Status</button>
                                                    <button type="button" wire:click="openArchiveModal({{ $agreement->id }})" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Archive</button>
                                                @else
                                                    <button type="button" wire:click="restoreAgreement({{ $agreement->id }})" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Restore</button>
                                                    <button type="button" wire:click="deletePermanently({{ $agreement->id }})" wire:confirm="Permanently delete this agreement? This cannot be undone." class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Delete Permanently</button>
                                                @endif
                                            </div>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-4 py-3">
                    {{ $agreements->links() }}
                </div>
            @endif
        </div>
    @endif

    <x-modal :show="'showStatusModal'" max-width="sm">
        <x-slot:title>Change Status</x-slot:title>
        <div class="space-y-4">
            <p class="text-sm text-slate-500">Update the status of this agreement. This action is logged.</p>
            <x-select wire:model="newStatus">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </x-select>
            @error('newStatus')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showStatusModal = false">Cancel</x-button>
            <x-button type="button" wire:click="saveStatus" wire:loading.attr="disabled" wire:target="saveStatus">Confirm</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showArchiveModal'" max-width="sm">
        <x-slot:title>Archive Agreement</x-slot:title>
        <p class="text-sm text-slate-500">
            Archiving disables the client link and removes the agreement from the active list. History, payments and versions are preserved.
        </p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showArchiveModal = false">Cancel</x-button>
            <x-button type="button" variant="danger" wire:click="archiveAgreement" wire:loading.attr="disabled" wire:target="archiveAgreement">Archive Agreement</x-button>
        </x-slot:footer>
    </x-modal>
</div>
