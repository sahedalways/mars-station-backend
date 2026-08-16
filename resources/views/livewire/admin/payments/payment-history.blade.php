<div class="space-y-6">
    @unless ($embedded)
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Payments</h1>
                <p class="mt-1 text-sm text-slate-500">Track all payments across agreements.</p>
            </div>
            <div class="flex items-center gap-2">
                <x-button size="sm" variant="secondary" wire:click="requestExport">Export CSV</x-button>
            </div>
        </div>
    @else
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-lg font-bold tracking-tight text-slate-900">Payment History</h2>
            <div class="flex items-center gap-2">
                <x-button size="sm" variant="secondary" wire:click="requestExport">Export CSV</x-button>
            </div>
        </div>
    @endunless

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[220px]">
                    <x-input type="text" placeholder="Search by agreement, name or email&hellip;" wire:model.live.debounce.300ms="search"/>
                </div>
                <x-select wire:model.live="status">
                    <option value="">All Statuses</option>
                    @foreach (\App\Enums\PaymentStatus::cases() as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                    @endforeach
                </x-select>
                <x-select wire:model.live="dateRange">
                    <option value="">All Time</option>
                    <option value="7d">Last 7 days</option>
                    <option value="30d">Last 30 days</option>
                    <option value="6m">Last 6 months</option>
                    <option value="1y">Last year</option>
                    <option value="custom">Custom range&hellip;</option>
                </x-select>
                @if ($dateRange === 'custom')
                    <x-input type="date" wire:model.live="customFrom"/>
                    <span class="text-sm text-slate-400">to</span>
                    <x-input type="date" wire:model.live="customTo"/>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        @foreach ([['label' => 'Payment', 'field' => 'created_at'], ['label' => 'Agreement', 'field' => null], ['label' => 'Type', 'field' => null], ['label' => 'Status', 'field' => 'status'], ['label' => 'Amount', 'field' => 'amount_pence']] as $col)
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                @if ($col['field'])
                                    <button wire:click="sortBy('{{ $col['field'] }}')" class="inline-flex items-center gap-1 hover:text-slate-700">
                                        {{ $col['label'] }}
                                        @if ($sortField === $col['field'])
                                            <span class="text-indigo-600">{{ $sortDirection === 'desc' ? '↓' : '↑' }}</span>
                                        @endif
                                    </button>
                                @else
                                    {{ $col['label'] }}
                                @endif
                            </th>
                        @endforeach
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-slate-500">Refunds</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3">
                                <p class="text-sm font-medium text-slate-900">#{{ $payment->id }}</p>
                                <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y H:i') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-slate-900">{{ $payment->agreement?->agreement_number }}</p>
                                <p class="text-xs text-slate-500">{{ $payment->agreement?->client_name ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-slate-700">{{ ucfirst($payment->type->value) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <x-badge :color="match($payment->status->value) { 'succeeded' => 'green', 'failed' => 'red', 'refunded' => 'gray', 'partially_refunded' => 'amber', default => 'amber' }">
                                    {{ $payment->status->label() }}
                                </x-badge>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-slate-900">{{ $payment->formattedAmount() }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                @if ($payment->refunds_count > 0)
                                    <span class="text-sm text-slate-600">{{ $payment->refunds_count }}×</span>
                                @else
                                    <span class="text-sm text-slate-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12">
                                <x-empty-state title="No payments found" description="Payments will appear here once clients complete a payment."/>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <x-modal :show="'showExportModal'">
        <x-slot:title>Export Payments CSV</x-slot:title>
        <p class="text-sm text-slate-500">
            This export is generated in the background and emailed to you. Current filters: {{ $search ?: 'all agreements' }}, {{ $status ?: 'all statuses' }}, {{ $dateRange ?: 'all time' }}.
        </p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showExportModal = false">Cancel</x-button>
            <x-button type="button" wire:click="exportCsv" wire:loading.attr="disabled" wire:target="exportCsv">Generate Export</x-button>
        </x-slot:footer>
    </x-modal>
</div>
