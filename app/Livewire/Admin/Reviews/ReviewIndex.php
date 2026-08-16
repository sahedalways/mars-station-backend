<?php

namespace App\Livewire\Admin\Reviews;

use App\Enums\ReviewStatus;
use App\Models\Review;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReviewIndex extends Component
{
    use WithFileUploads, WithPagination;

    #[Url(history: true)]
    public ?string $status = null;

    public bool $showCreateModal = false;

    public string $name = '';

    public string $position = '';

    public int $rating = 5;

    public string $description = '';

    public $photo;

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'position', 'rating', 'description', 'photo']);
        $this->rating = 5;
        $this->showCreateModal = true;
    }

    public function save(ActivityLogService $logs): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $dpPath = null;

        if ($this->photo) {
            $dpPath = $this->photo->store('reviews', 'local');
        }

        $review = Review::create([
            'dp_path' => $dpPath,
            'name' => $this->name,
            'position' => $this->position,
            'rating' => $this->rating,
            'description' => $this->description,
            'status' => ReviewStatus::Approved,
        ]);

        $logs->record('review.created', $review, [
            'name' => $review->name,
        ], auth('admin')->user());

        $this->showCreateModal = false;
        $this->dispatch('toast', message: 'Review added.', type: 'success');
    }

    public function setStatus(Review $review, string $status, ActivityLogService $logs): void
    {
        $review->update(['status' => ReviewStatus::from($status)]);

        $logs->record('review.status_changed', $review, [
            'name' => $review->name,
            'to' => $status,
        ], auth('admin')->user());
    }

    public function delete(Review $review, ActivityLogService $logs): void
    {
        $review->delete();

        $logs->record('review.deleted', $review, [
            'name' => $review->name,
        ], auth('admin')->user());

        $this->dispatch('toast', message: 'Review deleted.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.reviews.review-index', [
            'reviews' => Review::query()
                ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
                ->latest()
                ->paginate(15),
        ])->title('Reviews');
    }
}
