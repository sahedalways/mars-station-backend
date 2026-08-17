<div class="space-y-6">
      {{-- ========== PAGE HEADER ========== --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Agreements</h1>
            <p class="mt-1 text-sm text-slate-400">Manage, track and create agreements for your clients.</p>
        </div>
        @if ($tab !== 'all')
            <a href="{{ route('admin.agreements.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-purple-400 transition hover:text-purple-300">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Agreements
            </a>
        @endif
    </div>



    <div class="border-b border-slate-800">
        <nav class="-mb-px flex gap-6 overflow-x-auto scrollbar-none" aria-label="Agreement tabs">
            <button type="button" wire:click="setTab('all')" wire:key="tab-all"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-xs font-medium transition cursor-pointer', 'border-purple-400 text-purple-300' => $tab === 'all', 'border-transparent text-slate-400 hover:border-slate-600 hover:text-slate-200' => $tab !== 'all'])>
                All Agreements
            </button>
            <button type="button" wire:click="setTab('create')" wire:key="tab-create"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-xs font-medium transition cursor-pointer', 'border-purple-400 text-purple-300' => $tab === 'create', 'border-transparent text-slate-400 hover:border-slate-600 hover:text-slate-200' => $tab !== 'create'])>
                Create Agreement
            </button>
            <button type="button" wire:click="setTab('payments')" wire:key="tab-payments"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-xs font-medium transition cursor-pointer', 'border-purple-400 text-purple-300' => $tab === 'payments', 'border-transparent text-slate-400 hover:border-slate-600 hover:text-slate-200' => $tab !== 'payments'])>
                Payment History
            </button>
            <button type="button" wire:click="setTab('archived')" wire:key="tab-archived"
                    @class(['whitespace-nowrap border-b-2 px-1 py-3 text-xs font-medium transition cursor-pointer', 'border-purple-400 text-purple-300' => $tab === 'archived', 'border-transparent text-slate-400 hover:border-slate-600 hover:text-slate-200' => $tab !== 'archived'])>
                Archived Agreements
            </button>
        </nav>
    </div>

    @if ($tab === 'create')
        <livewire:admin.agreements.create-agreement/>
    @elseif ($tab === 'payments')
        <livewire:admin.payments.payment-history :embedded="true"/>
    @else
        <div class="rounded-2xl border border-purple-500/15 bg-white/[0.03] shadow-lg shadow-purple-950/30 backdrop-blur-sm">
            <div class="border-b border-slate-800 p-4">
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
                    <table class="min-w-full divide-y divide-slate-800">
                        <thead class="bg-slate-950/50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    <button type="button" wire:click="sortBy('agreement_number')" class="hover:text-slate-200">Agreement</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    <button type="button" wire:click="sortBy('client_name')" class="hover:text-slate-200">Client</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    <button type="button" wire:click="sortBy('created_at')" class="hover:text-slate-200">Created</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    <button type="button" wire:click="sortBy('updated_at')" class="hover:text-slate-200">Updated</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    <button type="button" wire:click="sortBy('status')" class="hover:text-slate-200">Status</button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">Payment</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">Amount</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($agreements as $agreement)
                                @php
                                    $paymentStatus = $agreement->payment_type->value === 'none'
                                        ? 'none'
                                        : ($agreement->isPaid() ? 'paid' : ($agreement->payments->contains('status', 'failed') ? 'failed' : 'unpaid'));
                                @endphp
                                <tr class="transition hover:bg-white/[0.03]">
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <a href="{{ route('admin.agreements.show', $agreement) }}" class="font-medium text-purple-400 transition hover:text-purple-300">{{ $agreement->agreement_number }}</a>
                                        <p class="max-w-[200px] truncate text-xs text-slate-500">{{ $agreement->title }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-slate-100">{{ $agreement->client_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $agreement->client_email }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-400">{{ $agreement->created_at->format('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-400">{{ $agreement->updated_at->diffForHumans() }}</td>
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
                                    <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-slate-100">
                                        {{ $agreement->formatted_amount }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <x-dropdown>
                                            <x-slot:trigger>
                                                <button type="button" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-800 hover:text-slate-200 cursor-pointer" aria-label="Actions">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>
                                            <div class="py-1.5">
                                                <a href="{{ route('admin.agreements.show', $agreement) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 transition hover:bg-purple-500/10 hover:text-white">
                                                    <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    View Details
                                                </a>
                                                @if ($tab !== 'archived')
                                                    <a href="{{ route('admin.agreements.edit', $agreement) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 transition hover:bg-purple-500/10 hover:text-white">
                                                        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                        Edit Agreement
                                                    </a>
                                                    <div class="mx-3 my-1 border-t border-purple-500/10"></div>
                                                    <button type="button" wire:click="openStatusModal({{ $agreement->id }})" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-slate-300 transition hover:bg-purple-500/10 hover:text-white">
                                                        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                                        Change Status
                                                    </button>
                                                    <button type="button" wire:click="openArchiveModal({{ $agreement->id }})" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-red-400 transition hover:bg-red-500/10 hover:text-red-300">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                                        Archive
                                                    </button>
                                                @else
                                                    <button type="button" wire:click="restoreAgreement({{ $agreement->id }})" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-emerald-400 transition hover:bg-emerald-500/10 hover:text-emerald-300">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                                        Restore
                                                    </button>
                                                    <div class="mx-3 my-1 border-t border-purple-500/10"></div>
                                                    <button type="button" wire:click="deletePermanently({{ $agreement->id }})" wire:confirm="Permanently delete this agreement? This cannot be undone." class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-red-400 transition hover:bg-red-500/10 hover:text-red-300">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                        Delete Permanently
                                                    </button>
                                                @endif
                                            </div>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                <div class="border-t border-slate-800 px-4 py-3">
                    {{ $agreements->links() }}
                </div>
            @endif
        </div>
    @endif

    <x-modal :show="'showStatusModal'" max-width="sm">
        <x-slot:title>Change Status</x-slot:title>
        <div class="space-y-4">
            <p class="text-sm text-slate-400">Update the status of this agreement. This action is logged.</p>
            <x-select wire:model="newStatus">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </x-select>
            @error('newStatus')
                <p class="text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showStatusModal')">Cancel</x-button>
            <x-button type="button" wire:click="saveStatus" wire:loading.attr="disabled" wire:target="saveStatus">Confirm</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showArchiveModal'" max-width="sm">
        <x-slot:title>Archive Agreement</x-slot:title>
        <p class="text-sm text-slate-400">
            Archiving disables the client link and removes the agreement from the active list. History, payments and versions are preserved.
        </p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="closeModal('showArchiveModal')">Cancel</x-button>
            <x-button type="button" variant="danger" wire:click="archiveAgreement" wire:loading.attr="disabled" wire:target="archiveAgreement">Archive Agreement</x-button>
        </x-slot:footer>
    </x-modal>
</div>
