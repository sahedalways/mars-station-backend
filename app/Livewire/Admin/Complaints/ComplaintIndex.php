<?php

namespace App\Livewire\Admin\Complaints;

use App\Enums\ComplaintStatus;
use App\Enums\MessageSenderType;
use App\Models\Complaint;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ComplaintIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?string $status = null;

    public bool $showReplyModal = false;

    public ?int $selectedComplaintId = null;

    public string $replyBody = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function openComplaint(Complaint $complaint): void
    {
        $complaint->markRead();
    }

    public function openReply(Complaint $complaint): void
    {
        $this->selectedComplaintId = $complaint->id;
        $this->replyBody = '';
        $this->showReplyModal = true;
        $complaint->markRead();
    }

    public function sendReply(ActivityLogService $logs): void
    {
        $this->validate([
            'replyBody' => ['required', 'string'],
        ]);

        $complaint = Complaint::findOrFail($this->selectedComplaintId);

        $complaint->messages()->create([
            'sender_type' => MessageSenderType::Admin,
            'admin_id' => auth('admin')->id(),
            'body' => $this->replyBody,
        ]);

        if ($complaint->status === ComplaintStatus::New) {
            $complaint->update(['status' => ComplaintStatus::Open]);
        }

        $logs->record('complaint.replied', $complaint, [
            'name' => $complaint->full_name,
        ], auth('admin')->user());

        $this->showReplyModal = false;
        $this->dispatch('toast', message: 'Reply sent to client.', type: 'success');
    }

    public function setStatus(Complaint $complaint, string $status, ActivityLogService $logs): void
    {
        $complaint->update(['status' => ComplaintStatus::from($status)]);

        $logs->record('complaint.status_changed', $complaint, [
            'name' => $complaint->full_name,
            'to' => $status,
        ], auth('admin')->user());
    }

    public function render()
    {
        return view('livewire.admin.complaints.complaint-index', [
            'complaints' => $this->complaints(),
        ])->title('Complaints');
    }

    private function complaints()
    {
        return Complaint::query()
            ->withCount('messages')
            ->when($this->search !== '', fn (Builder $q) => $q->where(function (Builder $q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(15);
    }
}
