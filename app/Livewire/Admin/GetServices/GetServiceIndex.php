<?php

namespace App\Livewire\Admin\GetServices;

use App\Enums\GetServiceStatus;
use App\Models\GetServiceRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class GetServiceIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?string $status = null;

    #[Url(history: true)]
    public string $sort = 'latest';

    public bool $showStatusModal = false;

    public ?int $selectedRequestId = null;

    public string $selectedStatus = '';

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

    public function openStatusModal(GetServiceRequest $request): void
    {
        $this->selectedRequestId = $request->id;
        $this->selectedStatus = $request->status->value;
        $this->showStatusModal = true;
    }

    public function saveStatus(\App\Services\ActivityLogService $logs): void
    {
        $request = GetServiceRequest::findOrFail($this->selectedRequestId);

        $request->update(['status' => GetServiceStatus::from($this->selectedStatus)]);

        $logs->record('get_service.status_changed', $request, [
            'name' => $request->full_name,
            'to' => $this->selectedStatus,
        ], auth('admin')->user());

        $this->showStatusModal = false;
        $this->dispatch('toast', message: 'Status updated.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.get-services.get-service-index', [
            'requests' => $this->requests(),
        ])->title('Get Service Requests');
    }

    private function requests()
    {
        $query = GetServiceRequest::query()
            ->when($this->search !== '', fn (Builder $q) => $q->where(function (Builder $q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('company', 'like', "%{$this->search}%");
            }))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status));

        if ($this->sort === 'oldest') {
            $query->oldest();
        } elseif ($this->sort === 'unread') {
            $query->orderBy('is_read')->latest('id');
        } else {
            $query->latest();
        }

        return $query->withCount('attachments')->paginate(15);
    }
}
