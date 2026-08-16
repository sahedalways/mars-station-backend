<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Our Services</h1>
            <p class="mt-1 text-sm text-slate-500">Manage the services shown on the public website. Max {{ config('mars.services.max', 12) }} services.</p>
        </div>
        <x-button wire:click="openCreate">+ Add Service</x-button>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        @forelse ($services as $service)
            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-lg">
                            {{ $service->icon }}
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">{{ $service->title }}</h3>
                            <p class="text-xs text-slate-500">{{ $service->type }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="move({{ $service->id }}, -1)" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                        </button>
                        <button type="button" wire:click="move({{ $service->id }}, 1)" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                    </div>
                </div>

                <p class="mt-3 text-sm text-slate-600">{{ $service->description }}</p>

                @if ($service->bulletPoints->isNotEmpty())
                    <ul class="mt-3 space-y-1">
                        @foreach ($service->bulletPoints as $bullet)
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                {{ $bullet->text }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($service->projects->isNotEmpty())
                    <div class="mt-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Projects</p>
                        <div class="mt-1 flex flex-wrap gap-1.5">
                            @foreach ($service->projects as $project)
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600">{{ $project->title }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                    <x-badge :color="$service->is_active ? 'green' : 'gray'" :dot="true">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                    <div class="flex items-center gap-2">
                        <x-button size="sm" variant="secondary" wire:click="toggleActive({{ $service->id }})">
                            {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                        </x-button>
                        <x-button size="sm" variant="secondary" wire:click="openEdit({{ $service->id }})">Edit</x-button>
                        <x-button size="sm" variant="danger" wire:click="delete({{ $service->id }})" wire:confirm="Delete this service?">Delete</x-button>
                    </div>
                </div>
            </div>
        @empty
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-16 shadow-sm">
                    <x-empty-state title="No services yet" description="Create your first service to display on the website."/>
                </div>
            </div>
        @endforelse
    </div>

    <x-modal :show="'showCreateModal'" max-width="2xl">
        <x-slot:title>{{ $editServiceId ? 'Edit Service' : 'Add Service' }}</x-slot:title>
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <x-input label="Title" wire:model="title" :error="$errors->first('title')"/>
                </div>
                <x-input label="Type" wire:model="type" :error="$errors->first('type')"/>
            </div>
            <x-input label="Icon (emoji)" wire:model="icon"/>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                <textarea wire:model="description" rows="3"
                          class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                @error('description')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Bullet Points (one per line)</label>
                    <textarea wire:model="bulletPointsInput" rows="4"
                              class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Projects (one per line)</label>
                    <textarea wire:model="projectsInput" rows="4"
                              class="block w-full rounded-lg border-0 py-2 pl-3 pr-3 text-sm text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"></textarea>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" wire:model="isActive" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                Active
            </label>
        </div>
        <x-slot:footer>
            <x-button type="button" variant="secondary" wire:click="showCreateModal = false">Cancel</x-button>
            <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save">Save</x-button>
        </x-slot:footer>
    </x-modal>
</div>
