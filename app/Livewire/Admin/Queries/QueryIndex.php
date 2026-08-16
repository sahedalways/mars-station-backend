<?php

namespace App\Livewire\Admin\Queries;

use App\Enums\MessageSenderType;
use App\Enums\QueryStatus;
use App\Models\Query;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class QueryIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?string $status = null;

    public bool $showReplyModal = false;

    public ?int $selectedQueryId = null;

    public string $replyBody = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function openQuery(Query $query): void
    {
        $query->markRead();
    }

    public function openReply(Query $query): void
    {
        $this->selectedQueryId = $query->id;
        $this->replyBody = '';
        $this->showReplyModal = true;
        $query->markRead();
    }

    public function sendReply(ActivityLogService $logs): void
    {
        $this->validate([
            'replyBody' => ['required', 'string'],
        ]);

        $query = Query::findOrFail($this->selectedQueryId);

        $query->messages()->create([
            'sender_type' => MessageSenderType::Admin,
            'admin_id' => auth('admin')->id(),
            'body' => $this->replyBody,
        ]);

        if ($query->status === QueryStatus::New) {
            $query->update(['status' => QueryStatus::Open]);
        }

        $logs->record('query.replied', $query, [
            'name' => $query->full_name,
        ], auth('admin')->user());

        $this->showReplyModal = false;
        $this->dispatch('toast', message: 'Reply sent to client.', type: 'success');
    }

    public function setStatus(Query $query, string $status, ActivityLogService $logs): void
    {
        $query->update(['status' => QueryStatus::from($status)]);

        $logs->record('query.status_changed', $query, [
            'name' => $query->full_name,
            'to' => $status,
        ], auth('admin')->user());
    }

    public function render()
    {
        return view('livewire.admin.queries.query-index', [
            'queries' => $this->buildQuery(),
        ])->title('Queries');
    }

    private function buildQuery()
    {
        return Query::query()
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
