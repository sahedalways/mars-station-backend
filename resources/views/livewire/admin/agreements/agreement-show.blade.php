<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.agreements.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">← Agreements</a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $agreement->agreement_number }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $agreement->title }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <x-badge :color="match($agreement->status->value) { 'completed', 'subscribed' => 'green', 'expired', 'terminated' => 'red', 'signed', 'in_progress' => 'blue', 'pending' => 'amber', default => 'gray' }">
                {{ $agreement->status->label() }}
            </x-badge>
            <x-badge color="slate">{{ $agreement->payment_type->label() }}</x-badge>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.agreements.edit', $agreement) }}">
            <x-button size="sm">Edit Agreement</x-button>
        </a>
        <x-button size="sm" variant="secondary" wire:click="openStatusModal">Status</x-button>
        <x-button size="sm" variant="secondary" wire:click="showResendModal = true">Resend</x-button>
        <x-button size="sm" variant="secondary" wire:click="downloadPdf" wire:loading.attr="disabled" wire:target="downloadPdf">
            <span wire:loading.remove wire:target="downloadPdf">Export PDF</span>
            <span wire:loading wire:target="downloadPdf">Generating…</span>
        </x-button>
        <x-button size="sm" variant="secondary" wire:click="showLinkRegenModal = true">Regenerate Link</x-button>
        <x-button size="sm" variant="secondary" wire:click="showOtpToggleModal = true">
            {{ $agreement->links->where('is_active', true)->first()?->otp_enabled ? 'Disable OTP' : 'Enable OTP' }}
        </x-button>
        <x-button size="sm" variant="danger" wire:click="showArchiveModal = true">Archive</x-button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Client Information</h2>
                </div>
                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Full Name</p>
                        <p class="mt-1 text-sm text-slate-900">{{ $agreement->client_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Email</p>
                        <p class="mt-1 text-sm text-slate-900">{{ $agreement->client_email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Mobile</p>
                        <p class="mt-1 text-sm text-slate-900">{{ $agreement->client_mobile ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Validity Date</p>
                        <p class="mt-1 text-sm text-slate-900">{{ $agreement->validity_date?->format('d M Y') ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Current Version</h2>
                </div>
                <div class="p-5">
                    @if ($agreement->currentVersion)
                        <div class="mb-3 flex items-center justify-between">
                            <x-badge color="indigo">V{{ $agreement->currentVersion->version }}</x-badge>
                            <span class="text-xs text-slate-400">Created {{ $agreement->currentVersion->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="prose prose-sm max-w-none whitespace-pre-wrap text-sm text-slate-700">
                            {{ $agreement->currentVersion->content }}
                        </div>
                    @else
                        <p class="text-sm text-slate-400">No version available.</p>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Milestones</h2>
                </div>
                <div class="p-5">
                    @if ($agreement->milestones->isEmpty())
                        <p class="text-sm text-slate-400">No milestones configured.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach ($agreement->milestones as $milestone)
                                <li class="flex items-center justify-between rounded-lg border border-slate-200 p-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold {{ $milestone->isPaid() ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $milestone->order_index }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $milestone->title }}</p>
                                            @if ($milestone->description)
                                                <p class="text-xs text-slate-500">{{ $milestone->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-slate-900">{{ $milestone->formattedAmount() }}</p>
                                        <x-badge :color="$milestone->isPaid() ? 'green' : 'amber'">
                                            {{ $milestone->isPaid() ? 'Paid' : 'Pending' }}
                                        </x-badge>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Version History</h2>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($agreement->versions as $version)
                        <li class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-3">
                                <x-badge color="indigo">V{{ $version->version }}</x-badge>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $version->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $version->created_at->format('d M Y H:i') }} · by {{ $version->admin?->name ?? 'System' }}</p>
                                </div>
                            </div>
                            <x-badge :color="$version->isSigned() ? 'green' : 'amber'">
                                {{ $version->isSigned() ? 'Signed' : 'Pending' }}
                            </x-badge>
                        </li>
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">No versions.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Attachments</h2>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($agreement->attachments as $attachment)
                        <li class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $attachment->original_name }}</p>
                                    <p class="text-xs text-slate-500">{{ strtoupper(pathinfo($attachment->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($attachment->size_bytes / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.attachments.download', $attachment) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                        </li>
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">No attachments.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Agreement Link</h2>
                </div>
                <div class="space-y-3 p-5">
                    @php $activeLink = $agreement->links->where('is_active', true)->first(); @endphp
                    @if ($activeLink)
                        <div class="flex items-center gap-2">
                            <x-badge color="green" :dot="true">Active</x-badge>
                            @if ($activeLink->otp_enabled)
                                <x-badge color="purple">OTP Protected</x-badge>
                            @endif
                        </div>
                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="break-all text-xs text-slate-600">{{ $activeLink->publicUrl() }}</p>
                        </div>
                        <div x-data="{ copied: false }" class="flex gap-2">
                            <x-button size="sm" variant="secondary" type="button"
                                      @click="navigator.clipboard.writeText('{{ $activeLink->publicUrl() }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                      x-text="copied ? 'Copied!' : 'Copy Link'">
                            </x-button>
                        </div>
                        <p class="text-xs text-slate-400">Created {{ $activeLink->created_at->diffForHumans() }}</p>
                    @else
                        <x-badge color="red">Disabled</x-badge>
                        <p class="text-xs text-slate-400">No active client link.</p>
                    @endif

                    @if ($agreement->links->count() > 1)
                        <details class="mt-2">
                            <summary class="cursor-pointer text-xs font-medium text-slate-500 hover:text-slate-700">Previous links ({{ $agreement->links->count() - 1 }})</summary>
                            <ul class="mt-2 space-y-1">
                                @foreach ($agreement->links->skip(1) as $link)
                                    <li class="flex items-center justify-between rounded bg-slate-50 px-2 py-1">
                                        <span class="truncate text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($link->token, 16) }}…</span>
                                        <x-badge color="gray">Invalid</x-badge>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Payment History</h2>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($agreement->payments as $payment)
                        <li class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $payment->formattedAmount() }}</p>
                                <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }} · {{ ucfirst($payment->type->value) }}</p>
                            </div>
                            <x-badge :color="match($payment->status->value) { 'succeeded' => 'green', 'failed' => 'red', 'refunded' => 'gray', 'partially_refunded' => 'amber', default => 'amber' }">
                                {{ $payment->status->label() }}
                            </x-badge>
                        </li>
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">No payments yet.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Subscriptions</h2>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($agreement->subscriptions as $subscription)
                        <li class="px-5 py-3">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-900">{{ $subscription->title }}</p>
                                <x-badge :color="$subscription->isActive() ? 'green' : 'gray'">{{ $subscription->status->label() }}</x-badge>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $subscription->formattedAmount() }} / {{ $subscription->frequency->label() }}
                            </p>
                            @if ($subscription->stripe_subscription_id)
                                <p class="mt-1 truncate text-xs text-slate-400">{{ $subscription->stripe_subscription_id }}</p>
                            @endif
                        </li>
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">No subscriptions.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Access History</h2>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($agreement->accessLogs as $log)
                        <li class="px-5 py-2.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $log->status)) }}</span>
                                <span class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-500">{{ $log->email ?? '—' }} · {{ $log->ip_address ?? '—' }}</p>
                        </li>
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">No access recorded.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <x-modal :show="'showStatusModal'" max-width="sm">
        <x-slot:title>Change Status</x-slot:title>
        <div class="space-y-4">
            <p class="text-sm text-slate-500">This action is logged.</p>
            <x-select wire:model="newStatus">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </x-select>
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showStatusModal = false">Cancel</x-button>
            <x-button type="button" wire:click="saveStatus" wire:loading.attr="disabled" wire:target="saveStatus">Confirm</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showArchiveModal'" max-width="sm">
        <x-slot:title>Archive Agreement</x-slot:title>
        <p class="text-sm text-slate-500">This disables the client link. History and payments are preserved.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showArchiveModal = false">Cancel</x-button>
            <x-button type="button" variant="danger" wire:click="archive" wire:loading.attr="disabled" wire:target="archive">Archive</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showLinkRegenModal'" max-width="sm">
        <x-slot:title>Regenerate Link</x-slot:title>
        <p class="text-sm text-slate-500">A new secure link will be generated and the current link will become invalid.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showLinkRegenModal = false">Cancel</x-button>
            <x-button type="button" wire:click="regenerateLink" wire:loading.attr="disabled" wire:target="regenerateLink">Regenerate</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showOtpToggleModal'" max-width="sm">
        <x-slot:title>Toggle Email OTP</x-slot:title>
        <p class="text-sm text-slate-500">Client access to the agreement link will require an email verification code.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showOtpToggleModal = false">Cancel</x-button>
            <x-button type="button" wire:click="toggleOtpProtection" wire:loading.attr="disabled" wire:target="toggleOtpProtection">Save</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal :show="'showResendModal'" max-width="sm">
        <x-slot:title>Resend to Client</x-slot:title>
        <p class="text-sm text-slate-500">Send the agreement link to {{ $agreement->client_email }} again.</p>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showResendModal = false">Cancel</x-button>
            <x-button type="button" wire:click="resendAgreement" wire:loading.attr="disabled" wire:target="resendAgreement">Resend Email</x-button>
        </x-slot:footer>
    </x-modal>
</div>
