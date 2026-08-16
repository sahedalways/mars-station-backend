<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Reviews</h1>
            <p class="mt-1 text-sm text-slate-500">Client reviews shown on the public website.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-select wire:model.live="status">
                <option value="">All Statuses</option>
                @foreach (\App\Enums\ReviewStatus::cases() as $s)
                    <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option>
                @endforeach
            </x-select>
            <x-button wire:click="openCreate">+ Add Review</x-button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($reviews as $review)
            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full bg-indigo-100 text-lg font-semibold text-indigo-600">
                        @if ($review->dp_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($review->dp_path))
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('local')->url($review->dp_path) }}" alt="{{ $review->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="flex h-full w-full items-center justify-center">{{ strtoupper(substr($review->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-900">{{ $review->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $review->position }}</p>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.175 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.061 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.288-3.958z"/>
                        </svg>
                    @endfor
                </div>

                <p class="mt-3 flex-1 text-sm text-slate-600">“{{ $review->description }}”</p>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                    <x-badge :color="match($review->status->value) { 'approved' => 'green', 'rejected' => 'red', default => 'amber' }">
                        {{ ucfirst($review->status->value) }}
                    </x-badge>
                    <div class="flex items-center gap-2">
                        @if ($review->status->value !== 'approved')
                            <x-button size="sm" variant="secondary" wire:click="setStatus({{ $review->id }}, 'approved')">Approve</x-button>
                        @endif
                        @if ($review->status->value !== 'rejected')
                            <x-button size="sm" variant="secondary" wire:click="setStatus({{ $review->id }}, 'rejected')">Reject</x-button>
                        @endif
                        <x-button size="sm" variant="danger" wire:click="delete({{ $review->id }})" wire:confirm="Delete this review?">Delete</x-button>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-16 shadow-sm">
                    <x-empty-state title="No reviews yet" description="Reviews submitted from the website will appear here."/>
                </div>
            </div>
        @endforelse
    </div>

    @if ($reviews->hasPages())
        <div class="px-4 py-3">
            {{ $reviews->links() }}
        </div>
    @endif

    <x-modal :show="'showCreateModal'" max-width="xl">
        <x-slot:title>Add Review</x-slot:title>
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-input label="Name" wire:model="name" :error="$errors->first('name')"/>
                <x-input label="Position" wire:model="position" :error="$errors->first('position')"/>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Rating</label>
                <select wire:model="rating" class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} / 5</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                <textarea wire:model="description" rows="4"
                          class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                @error('description')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Photo (optional)</label>
                <input type="file" wire:model="photo" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                @error('photo')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showCreateModal = false">Cancel</x-button>
            <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save">Save</x-button>
        </x-slot:footer>
    </x-modal>
</div>
