<?php

namespace Tests\Feature;

use App\Livewire\Admin\Services\ServiceIndex;
use App\Models\Admin;
use App\Models\Service;
use App\Models\ServiceBulletPoint;
use App\Models\ServiceProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceProjectImageTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'admin');
    }

    private function fakeImage(string $name = 'test.jpg'): UploadedFile
    {
        return UploadedFile::fake()->image($name, 100, 100)->size(100);
    }

    // ─── processUploadedImages ───────────────────────────────────────

    public function test_process_uploaded_images_stores_file_and_adds_to_projects(): void
    {
        $file = $this->fakeImage('portfolio.jpg');

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('newProjectImage', $file)
            ->call('processUploadedImages')
            ->assertSet('projects', function ($projects) {
                return count($projects) === 1
                    && str_ends_with($projects[0]['picture_path'], '.jpg')
                    && str_starts_with($projects[0]['picture_path'], 'services/projects/')
                    && $projects[0]['title'] === 'portfolio';
            });
    }

    public function test_process_uploaded_images_clears_new_project_image_after_processing(): void
    {
        $file = $this->fakeImage();

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('newProjectImage', $file)
            ->call('processUploadedImages')
            ->assertSet('newProjectImage', null);
    }

    public function test_process_uploaded_images_does_nothing_when_no_file(): void
    {
        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->assertSet('projects', [])
            ->call('processUploadedImages')
            ->assertSet('projects', []);
    }

    public function test_process_uploaded_images_handles_multiple_files(): void
    {
        $files = [
            $this->fakeImage('one.jpg'),
            $this->fakeImage('two.jpg'),
            $this->fakeImage('three.jpg'),
        ];

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('newProjectImage', $files)
            ->call('processUploadedImages')
            ->assertSet('projects', function ($projects) {
                return count($projects) === 3;
            });
    }

    // ─── removeProject ───────────────────────────────────────────────

    public function test_remove_project_removes_from_array(): void
    {
        $file = $this->fakeImage();

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('newProjectImage', $file)
            ->call('processUploadedImages')
            ->assertSet('projects', fn ($p) => count($p) === 1)
            ->call('removeProject', 0)
            ->assertSet('projects', []);
    }

    public function test_remove_new_upload_deletes_from_disk(): void
    {
        $file = $this->fakeImage();

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('newProjectImage', $file)
            ->call('processUploadedImages')
            ->assertSet('projects', fn ($p) => count($p) === 1)
            ->call('removeProject', 0)
            ->assertSet('projects', []);
    }

    // ─── closeModal ──────────────────────────────────────────────────

    public function test_close_modal_on_create_cleans_up_new_uploads(): void
    {
        $file = $this->fakeImage();

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('newProjectImage', $file)
            ->call('processUploadedImages')
            ->assertSet('projects', fn ($p) => count($p) === 1)
            ->call('closeModal', 'showCreateModal')
            ->assertSet('projects', [])
            ->assertSet('editServiceId', null);
    }

    // ─── save (create) ───────────────────────────────────────────────

    public function test_save_creates_service_with_projects(): void
    {
        $file = $this->fakeImage();

        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('title', 'Web Development')
            ->set('type', 'web')
            ->set('description', 'Full stack web development')
            ->set('newProjectImage', $file)
            ->call('processUploadedImages')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('services', ['title' => 'Web Development']);
        $this->assertDatabaseCount('service_projects', 1);

        $project = ServiceProject::first();
        $this->assertNotNull($project->picture_path);
    }

    public function test_save_does_not_create_service_when_project_has_null_path(): void
    {
        Livewire::test(ServiceIndex::class)
            ->call('openCreateModal')
            ->set('title', 'Web Development')
            ->set('type', 'web')
            ->set('description', 'Full stack web development')
            ->set('projects', [['title' => 'Project', 'picture_path' => null]])
            ->call('save')
            ->assertSet('showCreateModal', true);

        $this->assertDatabaseCount('services', 0);
        $this->assertDatabaseCount('service_projects', 0);
    }

    // ─── save (edit) ─────────────────────────────────────────────────

    public function test_save_edit_replaces_projects(): void
    {
        $service = Service::factory()->create();
        ServiceProject::factory()->create([
            'service_id' => $service->id,
            'picture_path' => 'services/projects/old-file.jpg',
        ]);

        $newFile = $this->fakeImage('new-file.jpg');

        Livewire::test(ServiceIndex::class)
            ->call('editService', $service->id)
            ->assertSet('projects', fn ($p) => count($p) === 1)
            ->set('projects', []) // remove the old project
            ->set('newProjectImage', $newFile)
            ->call('processUploadedImages')
            ->assertSet('projects', fn ($p) => count($p) === 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('service_projects', 1);
    }

    // ─── deleteService ───────────────────────────────────────────────

    public function test_delete_service_soft_deletes_and_removes_files(): void
    {
        $service = Service::factory()->create();
        ServiceProject::factory()->create([
            'service_id' => $service->id,
            'picture_path' => 'services/projects/to-be-deleted.jpg',
        ]);

        Livewire::test(ServiceIndex::class)
            ->call('confirmDelete', $service->id)
            ->call('deleteService');

        $this->assertSoftDeleted('services', ['id' => $service->id]);
        $this->assertDatabaseCount('service_projects', 0);
    }

    // ─── Orphan cleanup command ──────────────────────────────────────

    public function test_orphan_cleanup_dry_run_does_not_delete(): void
    {
        Storage::disk('public')->put('services/projects/orphan-test.jpg', 'fake-content');

        $this->artisan('services:cleanup-orphaned-project-images --dry-run')
            ->expectsOutputToContain('Orphaned files: 1')
            ->expectsOutputToContain('Dry run');

        Storage::disk('public')->assertExists('services/projects/orphan-test.jpg');
    }

    public function test_orphan_cleanup_delete_removes_unreferenced_files(): void
    {
        Storage::disk('public')->put('services/projects/orphan-delete.jpg', 'fake-content');

        $this->artisan('services:cleanup-orphaned-project-images --delete')
            ->expectsConfirmation('Delete 1 orphaned file(s)?', 'yes')
            ->expectsOutputToContain('Deleted 1 orphaned file(s).');

        Storage::disk('public')->assertMissing('services/projects/orphan-delete.jpg');
    }

    public function test_orphan_cleanup_preserves_referenced_files(): void
    {
        $service = Service::factory()->create();
        ServiceProject::factory()->create([
            'service_id' => $service->id,
            'picture_path' => 'services/projects/valid-file.jpg',
        ]);

        // Create the file on disk so it exists
        Storage::disk('public')->put('services/projects/valid-file.jpg', 'valid-content');
        Storage::disk('public')->put('services/projects/orphan-delete.jpg', 'fake-content');

        $this->artisan('services:cleanup-orphaned-project-images --delete')
            ->expectsConfirmation('Delete 1 orphaned file(s)?', 'yes');

        Storage::disk('public')->assertExists('services/projects/valid-file.jpg');
        Storage::disk('public')->assertMissing('services/projects/orphan-delete.jpg');
    }

    public function test_orphan_cleanup_reports_zero_when_no_orphans(): void
    {
        $service = Service::factory()->create();
        ServiceProject::factory()->create([
            'service_id' => $service->id,
            'picture_path' => 'services/projects/only-file.jpg',
        ]);

        Storage::disk('public')->put('services/projects/only-file.jpg', 'valid-content');

        $this->artisan('services:cleanup-orphaned-project-images --dry-run')
            ->expectsOutputToContain('Orphaned files: 0')
            ->expectsOutputToContain('No orphaned files found.');
    }

    // ─── picture_path NOT NULL constraint ────────────────────────────

    public function test_picture_path_is_not_null_in_schema(): void
    {
        $columns = Schema::getColumns('service_projects');
        $picturePath = collect($columns)->firstWhere('name', 'picture_path');

        $this->assertFalse($picturePath['nullable'], 'picture_path should NOT be nullable');
    }

    public function test_cannot_insert_record_with_null_picture_path(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::table('service_projects')->insert([
            'service_id' => Service::factory()->create()->id,
            'title' => 'Test',
            'picture_path' => null,
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
