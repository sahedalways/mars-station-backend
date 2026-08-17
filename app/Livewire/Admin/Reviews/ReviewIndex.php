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

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sort = 'newest';

    public bool $showCreateModal = false;

    public bool $showDeleteModal = false;

    public ?int $deleteReviewId = null;

    public ?int $editReviewId = null;

    public string $name = '';

    public string $position = '';

    public int $rating = 5;

    public string $description = '';

    public $photo;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'position', 'rating', 'description', 'photo', 'editReviewId']);
        $this->rating = 5;
        $this->showCreateModal = true;
    }

    public function openEdit(int $id): void
    {
        $review = Review::findOrFail($id);
        $this->editReviewId = $id;
        $this->name = $review->name;
        $this->position = $review->position ?? '';
        $this->rating = $review->rating;
        $this->description = $review->description;
        $this->photo = null;
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
            $dpPath = $this->photo->store('reviews', 'public');
        }

        if ($this->editReviewId) {
            $review = Review::findOrFail($this->editReviewId);

            $data = [
                'name' => $this->name,
                'position' => $this->position,
                'rating' => $this->rating,
                'description' => $this->description,
            ];

            if ($dpPath) {
                $data['dp_path'] = $dpPath;
            }

            $review->update($data);

            $logs->record('review.updated', $review, [
                'name' => $review->name,
            ], auth('admin')->user());

            $message = 'Review updated.';
        } else {
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

            $message = 'Review added.';
        }

        $this->showCreateModal = false;
        $this->editReviewId = null;
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteReviewId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteReview(ActivityLogService $logs): void
    {
        $review = Review::findOrFail($this->deleteReviewId);
        $name = $review->name;
        $review->delete();

        $logs->record('review.deleted', $review, [
            'name' => $name,
        ], auth('admin')->user());

        $this->showDeleteModal = false;
        $this->deleteReviewId = null;
        $this->dispatch('toast', message: 'Review deleted.', type: 'success');
    }

    public function setStatus(int $id, string $status, ActivityLogService $logs): void
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => ReviewStatus::from($status)]);

        $logs->record('review.status_changed', $review, [
            'name' => $review->name,
            'to' => $status,
        ], auth('admin')->user());

        $this->dispatch('toast', message: 'Review status updated.', type: 'success');
    }

    public function closeModal(string $property): void
    {
        $this->$property = false;
        if ($property === 'showDeleteModal') {
            $this->deleteReviewId = null;
        }
        if ($property === 'showCreateModal') {
            $this->editReviewId = null;
        }
    }

    public function render()
    {
        $reviews = Review::query()
            ->when($this->search !== '', fn (Builder $q) => $q->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('position', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->when($this->sort === 'oldest', fn (Builder $q) => $q->oldest())
            ->when($this->sort === 'highest', fn (Builder $q) => $q->orderByDesc('rating'))
            ->when($this->sort === 'lowest', fn (Builder $q) => $q->orderBy('rating'))
            ->latest()
            ->paginate(10);

        $approved = Review::where('status', ReviewStatus::Approved)->count();
        $pending = Review::where('status', ReviewStatus::Pending)->count();
        $rejected = Review::where('status', ReviewStatus::Rejected)->count();

        return view('livewire.admin.reviews.review-index', [
            'reviews' => $reviews,
            'counts' => [
                'total' => Review::count(),
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
            ],
        ])->title('Reviews');
    }
}
