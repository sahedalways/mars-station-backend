<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Get Service Requests</h1>
            <p class="mt-1 text-sm text-slate-500">Requests submitted from the public website.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-64">
                <x-input type="text" placeholder="Search name, email, company&hellip;" wire:model.live.debounce.300ms="search"/>
            </div>
            <x-select wire:model.live="status">
                <option value="">All Statuses</option>
                @foreach (\App\Enums\GetServiceStatus::cases() as $s)
                    <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option>
                @endforeach
            </x-select>
            <x-select wire:model.live="sort">
                <option value="latest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="unread">Unread first</option>
            </x-select>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <ul class="divide-y divide-slate-100">
            @forelse ($requests as $request)
                <li class="flex flex-wrap items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50">
                    <div class="flex min-w-0 flex-1 items-center gap-4">
                        @if (!$request->is_read)
                            <span class="h-2 w-2 flex-shrink-0 rounded-full bg-indigo-500"></span>
                        @endif
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $request->full_name }}</p>
                                <span class="text-sm text-slate-400">·</span>
                                <p class="text-sm text-slate-500">{{ $request->email }}</p>
                            </div>
                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $request->company ?? '—' }}
                                @if ($request->selected_services)
                                    · {{ implode(', ', $request->selected_services) }}
                                @endif
                            </p>
                            <p class="mt-0.5 text-xs text-slate-400">
                                {{ $request->created_at->format('d M Y H:i') }} · {{ $request->attachments_count }} attachment(s)
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge :color="match($request->status->value) { 'new' => 'blue', 'processing' => 'amber', 'flagged' => 'red', 'signed' => 'indigo', 'completed' => 'green', default => 'gray' }">
                            {{ ucfirst($request->status->value) }}
                        </x-badge>
                        <x-button size="sm" variant="secondary" wire:click="openStatusModal({{ $request->id }})">Status</x-button>
                    </div>
                </li>
            @empty
                <li class="px-5 py-16">
                    <x-empty-state title="No requests found" description="Get service requests will appear here."/>
                </li>
            @endforelse
        </ul>

        @if ($requests->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <x-modal :show="'showStatusModal'" max-width="sm">
        <x-slot:title>Change Status</x-slot:title>
        <x-select wire:model="selectedStatus">
            @foreach (\App\Enums\GetServiceStatus::cases() as $s)
                <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option>
            @endforeach
        </x-select>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showStatusModal = false">Cancel</x-button>
            <x-button type="button" wire:click="saveStatus">Save</x-button>
        </x-slot:footer>
    </x-modal>
</div>
