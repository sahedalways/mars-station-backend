<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use App\Models\ServiceBulletPoint;
use App\Models\ServiceProject;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ServiceIndex extends Component
{
    public bool $showCreateModal = false;

    public ?int $editServiceId = null;

    public string $title = '';

    public string $type = '';

    public string $icon = '';

    public string $description = '';

    public bool $isActive = true;

    public string $bulletPointsInput = '';

    public string $projectsInput = '';

    public string $editingTitle = '';

    public string $editingType = '';

    public string $editingIcon = '';

    public string $editingDescription = '';

    public bool $editingIsActive = true;

    public function openCreate(): void
    {
        $this->reset(['title', 'type', 'icon', 'description', 'bulletPointsInput', 'projectsInput', 'editServiceId']);
        $this->isActive = true;
        $this->showCreateModal = true;
    }

    public function openEdit(Service $service): void
    {
        $this->editServiceId = $service->id;
        $this->editingTitle = $service->title;
        $this->editingType = $service->type;
        $this->editingIcon = $service->icon;
        $this->editingDescription = $service->description;
        $this->editingIsActive = $service->is_active;
        $this->bulletPointsInput = $service->bulletPoints->pluck('text')->implode("\n");
        $this->projectsInput = $service->projects->pluck('title')->implode("\n");
        $this->showCreateModal = true;
    }

    public function save(ActivityLogService $logs): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($logs) {
            $bullets = collect(preg_split('/\r\n|\r|\n/', $this->bulletPointsInput))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values();

            $projects = collect(preg_split('/\r\n|\r|\n/', $this->projectsInput))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values();

            if ($this->editServiceId) {
                $service = Service::findOrFail($this->editServiceId);

                $service->update([
                    'title' => $this->title,
                    'type' => $this->type,
                    'icon' => $this->icon,
                    'description' => $this->description,
                    'is_active' => $this->isActive,
                ]);

                $service->bulletPoints()->delete();
                $bullets->each(fn ($text, $i) => ServiceBulletPoint::create([
                    'service_id' => $service->id,
                    'text' => $text,
                    'order_index' => $i + 1,
                ]));

                $service->projects()->delete();
                $projects->each(fn ($title, $i) => ServiceProject::create([
                    'service_id' => $service->id,
                    'title' => $title,
                    'order_index' => $i + 1,
                ]));

                $logs->record('service.updated', $service, [
                    'title' => $service->title,
                ], auth('admin')->user());

                $message = 'Service updated.';
            } else {
                $count = Service::count();

                abort_if($count >= (int) config('mars.services.max', 12), 422, 'Maximum number of services reached.');

                $service = Service::create([
                    'icon' => $this->icon,
                    'title' => $this->title,
                    'type' => $this->type,
                    'description' => $this->description,
                    'order_index' => $count + 1,
                    'is_active' => $this->isActive,
                ]);

                $bullets->each(fn ($text, $i) => ServiceBulletPoint::create([
                    'service_id' => $service->id,
                    'text' => $text,
                    'order_index' => $i + 1,
                ]));

                $projects->each(fn ($title, $i) => ServiceProject::create([
                    'service_id' => $service->id,
                    'title' => $title,
                    'order_index' => $i + 1,
                ]));

                $logs->record('service.created', $service, [
                    'title' => $service->title,
                ], auth('admin')->user());

                $message = 'Service created.';
            }
        });

        $this->showCreateModal = false;
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function toggleActive(Service $service, ActivityLogService $logs): void
    {
        $service->update(['is_active' => ! $service->is_active]);

        $logs->record($service->is_active ? 'service.activated' : 'service.deactivated', $service, [
            'title' => $service->title,
        ], auth('admin')->user());
    }

    public function move(Service $service, int $direction, ActivityLogService $logs): void
    {
        $siblings = Service::query()->orderBy('order_index')->get();

        $keys = $siblings->keys()->all();
        $current = $siblings->search(fn ($s) => $s->id === $service->id);

        $target = $current + $direction;

        if (! in_array($target, $keys)) {
            return;
        }

        DB::transaction(function () use ($siblings, $current, $target) {
            $a = $siblings[$current];
            $b = $siblings[$target];
            $tmp = $a->order_index;
            $a->update(['order_index' => $b->order_index]);
            $b->update(['order_index' => $tmp]);
        });

        $logs->record('service.reordered', $service, [
            'title' => $service->title,
        ], auth('admin')->user());
    }

    public function delete(Service $service, ActivityLogService $logs): void
    {
        $service->delete();

        $logs->record('service.deleted', $service, [
            'title' => $service->title,
        ], auth('admin')->user());

        $this->dispatch('toast', message: 'Service deleted.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.services.service-index', [
            'services' => Service::with(['bulletPoints', 'projects'])->orderBy('order_index')->get(),
        ])->title('Our Services');
    }
}
