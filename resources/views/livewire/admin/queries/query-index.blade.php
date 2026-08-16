<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Queries</h1>
            <p class="mt-1 text-sm text-slate-500">Queries submitted from the public website.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-64">
                <x-input type="text" placeholder="Search name, email&hellip;" wire:model.live.debounce.300ms="search"/>
            </div>
            <x-select wire:model.live="status">
                <option value="">All Statuses</option>
                @foreach (\App\Enums\QueryStatus::cases() as $s)
                    <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option>
                @endforeach
            </x-select>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <ul class="divide-y divide-slate-100">
            @forelse ($queries as $query)
                <li class="flex flex-wrap items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50">
                    <div class="flex min-w-0 flex-1 items-center gap-4">
                        @if (!$query->is_read)
                            <span class="h-2 w-2 flex-shrink-0 rounded-full bg-indigo-500"></span>
                        @endif
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $query->full_name }}</p>
                                <span class="text-sm text-slate-400">·</span>
                                <p class="text-sm text-slate-500">{{ $query->email }}</p>
                                <span class="text-xs text-slate-400">· {{ $query->messages_count }} message(s)</span>
                            </div>
                            <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $query->query }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $query->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge :color="match($query->status->value) { 'new' => 'blue', 'open' => 'amber', 'flagged' => 'red', 'responded' => 'green', default => 'gray' }">
                            {{ ucfirst($query->status->value) }}
                        </x-badge>
                        <x-button size="sm" variant="secondary" wire:click="openReply({{ $query->id }})">Reply</x-button>
                        <x-button size="sm" variant="secondary" wire:click="setStatus({{ $query->id }}, 'responded')">Mark Responded</x-button>
                    </div>
                </li>
            @empty
                <li class="px-5 py-16">
                    <x-empty-state title="No queries" description="Queries submitted from the website will appear here."/>
                </li>
            @endforelse
        </ul>

        @if ($queries->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $queries->links() }}
            </div>
        @endif
    </div>

    <x-modal :show="'showReplyModal'" max-width="xl">
        <x-slot:title>Reply to Query</x-slot:title>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Message</label>
            <textarea wire:model="replyBody" rows="5"
                      class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
            @error('replyBody')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showReplyModal = false">Cancel</x-button>
            <x-button type="button" wire:click="sendReply" wire:loading.attr="disabled" wire:target="sendReply">Send Reply</x-button>
        </x-slot:footer>
    </x-modal>
</div>
