<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use App\Models\ServiceBulletPoint;
use App\Models\ServiceProject;
use App\Services\ActivityLogService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ServiceIndex extends Component
{
    use WithPagination, WithFileUploads;

    public const MAX_PROJECTS = 3;

    public bool $showCreateModal = false;

    public bool $showReorderModal = false;

    public bool $showDeleteModal = false;

    public ?int $editServiceId = null;

    public ?int $deleteServiceId = null;

    public string $title = '';

    public string $type = '';

    public string $description = '';

    public array $bulletPoints = [];

    public string $newBulletPoint = '';

    public array $projects = [];

    /** @var TemporaryUploadedFile|TemporaryUploadedFile[]|null */
    public $newProjectImage = null;

    public bool $isActive = true;

    /** Paths loaded from DB when editing — used to detect orphaned uploads on cancel. */
    private array $committedPaths = [];

    public function openCreateModal(): void
    {
        $this->title = '';
        $this->type = '';
        $this->description = '';
        $this->bulletPoints = [];
        $this->newBulletPoint = '';
        $this->projects = [];
        $this->committedPaths = [];
        $this->newProjectImage = null;
        $this->editServiceId = null;
        $this->isActive = true;
        $this->showCreateModal = true;
    }

    public function openReorderModal(): void
    {
        $this->showReorderModal = true;
    }

    public function editService(int $id): void
    {
        $service = Service::with('bulletPoints', 'projects')->findOrFail($id);

        $this->editServiceId = $service->id;
        $this->title = $service->title;
        $this->type = $service->type;
        $this->description = $service->description;
        $this->isActive = $service->is_active;
        $this->bulletPoints = $service->bulletPoints->pluck('text')->values()->all();
        $this->projects = $service->projects
            ->map(fn ($p) => [
                'title'      => $p->title,
                'type'       => $p->type,
                'view_link'  => $p->view_link,
                'picture_path' => $p->picture_path,
            ])
            ->values()
            ->all();
        $this->committedPaths = $service->projects->pluck('picture_path')->filter()->values()->all();
        $this->showCreateModal = true;
    }

    public function addBulletPoint(): void
    {
        $text = trim($this->newBulletPoint);
        if ($text !== '') {
            $this->bulletPoints[] = $text;
            $this->newBulletPoint = '';
        }
    }

    public function removeBulletPoint(int $index): void
    {
        unset($this->bulletPoints[$index]);
        $this->bulletPoints = array_values($this->bulletPoints);
    }

    public function updatedNewProjectImage(): void
    {
        $this->processUploadedImages();
    }

    public function processUploadedImages(): void
    {
        if (! $this->newProjectImage) {
            return;
        }

        $files = Arr::wrap($this->newProjectImage);

        foreach ($files as $file) {
            if (count($this->projects) >= self::MAX_PROJECTS) {
                $this->dispatch('toast', message: 'Maximum ' . self::MAX_PROJECTS . ' projects per service.', type: 'error');
                break;
            }

            if (! $file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                continue;
            }

            $path = $file->store('services/projects', 'public');

            if (empty($path)) {
                $this->dispatch('toast', message: 'Failed to store: ' . $file->getClientOriginalName(), type: 'error');
                continue;
            }

            $this->projects[] = [
                'title'        => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'type'         => '',
                'view_link'    => '',
                'picture_path' => $path,
            ];
        }

        $this->newProjectImage = null;
    }

    public function removeProject(int $index): void
    {
        if (! isset($this->projects[$index])) {
            return;
        }

        $path = $this->projects[$index]['picture_path'] ?? null;

        unset($this->projects[$index]);
        $this->projects = array_values($this->projects);

        // Only delete file from disk if it was a new upload (not in DB).
        // For existing DB records, save() handles deletion after successful DB write.
        if ($path && ! in_array($path, $this->committedPaths, true)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteServiceId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteService(ActivityLogService $logs): void
    {
        $service = Service::findOrFail($this->deleteServiceId);
        $title = $service->title;

        foreach ($service->projects as $project) {
            if ($project->picture_path) {
                Storage::disk('public')->delete($project->picture_path);
            }
        }

        $service->projects()->delete();
        $service->bulletPoints()->delete();
        $service->delete();

        $logs->record('service.deleted', $service, [
            'title' => $title,
        ], auth('admin')->user());

        $this->showDeleteModal = false;
        $this->deleteServiceId = null;
        $this->dispatch('toast', message: 'Service deleted.', type: 'success');
    }

    public function save(ActivityLogService $logs): void
    {
        $this->validate([
            'title'                => ['required', 'string', 'max:255'],
            'type'                 => ['required', 'string', 'max:255'],
            'description'          => ['required', 'string'],
            'projects'             => ['array', 'max:' . self::MAX_PROJECTS],
            'projects.*.title'     => ['required', 'string', 'max:255'],
            'projects.*.type'      => ['nullable', 'string', 'max:255'],
            'projects.*.view_link' => ['nullable', 'string', 'max:2048'],
        ]);

        // Ensure every project entry has a picture_path set by processUploadedImages.
        foreach ($this->projects as $i => $project) {
            if (empty($project['picture_path'])) {
                $this->dispatch('toast', message: 'Each project image must have a valid file.', type: 'error');
                return;
            }
        }

        $message = 'Service created.';
        $filesStoredDuringSave = [];

        try {
            DB::transaction(function () use ($logs, &$message, &$filesStoredDuringSave) {
                if ($this->editServiceId) {
                    $service = Service::findOrFail($this->editServiceId);

                    $service->update([
                        'title' => $this->title,
                        'type' => $this->type,
                        'description' => $this->description,
                        'is_active' => $this->isActive,
                    ]);

                    $service->bulletPoints()->delete();
                    foreach ($this->bulletPoints as $i => $text) {
                        ServiceBulletPoint::create([
                            'service_id' => $service->id,
                            'text' => $text,
                            'order_index' => $i + 1,
                        ]);
                    }

                    // Collect old paths before deleting DB records.
                    $existingPaths = $service->projects->pluck('picture_path')->filter()->all();
                    $service->projects()->delete();

                    // Delete files that are no longer referenced.
                    $newPaths = array_column($this->projects, 'picture_path');
                    foreach ($existingPaths as $path) {
                        if (! in_array($path, $newPaths, true)) {
                            Storage::disk('public')->delete($path);
                        }
                    }

                    foreach ($this->projects as $i => $project) {
                        ServiceProject::create([
                            'service_id'   => $service->id,
                            'title'        => $project['title'],
                            'type'         => $project['type'] ?? null,
                            'view_link'    => $project['view_link'] ?? null,
                            'picture_path' => $project['picture_path'],
                            'order_index'  => $i + 1,
                        ]);
                    }

                    $logs->record('service.updated', $service, [
                        'title' => $service->title,
                    ], auth('admin')->user());

                    $message = 'Service updated.';
                } else {
                    $count = Service::count();

                    abort_if($count >= (int) config('mars.services.max', 12), 422, 'Maximum number of services reached.');

                    $service = Service::create([
                        'title' => $this->title,
                        'type' => $this->type,
                        'description' => $this->description,
                        'order_index' => $count + 1,
                        'is_active' => $this->isActive,
                    ]);

                    foreach ($this->bulletPoints as $i => $text) {
                        ServiceBulletPoint::create([
                            'service_id' => $service->id,
                            'text' => $text,
                            'order_index' => $i + 1,
                        ]);
                    }

                    foreach ($this->projects as $i => $project) {
                        ServiceProject::create([
                            'service_id'   => $service->id,
                            'title'        => $project['title'],
                            'type'         => $project['type'] ?? null,
                            'view_link'    => $project['view_link'] ?? null,
                            'picture_path' => $project['picture_path'],
                            'order_index'  => $i + 1,
                        ]);
                    }

                    $logs->record('service.created', $service, [
                        'title' => $service->title,
                    ], auth('admin')->user());

                    $message = 'Service created.';
                }
            });
        } catch (\Exception $e) {
            // If DB transaction failed, clean up any files stored during this attempt.
            foreach ($filesStoredDuringSave as $path) {
                Storage::disk('public')->delete($path);
            }

            $this->dispatch('toast', message: 'Save failed: ' . $e->getMessage(), type: 'error');
            return;
        }

        $this->showCreateModal = false;
        $this->editServiceId = null;
        $this->committedPaths = [];
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function closeModal(string $property): void
    {
        if ($property === 'showCreateModal') {
            // Clean up files stored to disk but not yet committed to DB.
            $currentPaths = array_column($this->projects, 'picture_path');
            foreach ($currentPaths as $path) {
                if (! empty($path) && ! in_array($path, $this->committedPaths, true)) {
                    Storage::disk('public')->delete($path);
                }
            }
            $this->projects = [];
            $this->editServiceId = null;
            $this->committedPaths = [];
        }

        if ($property === 'showDeleteModal') {
            $this->deleteServiceId = null;
        }

        $this->$property = false;
    }

    public function move(int $id, int $direction, ActivityLogService $logs): void
    {
        $service = Service::findOrFail($id);

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

    public function render()
    {
        $services = Service::with(['bulletPoints', 'projects'])->orderBy('order_index')->paginate(10);
        $max = (int) config('mars.services.max', 12);

        return view('livewire.admin.services.service-index', [
            'services' => $services,
            'counts' => [
                'total' => Service::count(),
                'active' => Service::where('is_active', true)->count(),
                'projects' => ServiceProject::count(),
                'max' => $max,
                'remaining' => max(0, $max - Service::count()),
            ],
        ])->title('Our Services');
    }
}
