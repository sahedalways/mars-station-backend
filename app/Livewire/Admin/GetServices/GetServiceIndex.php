<?php

namespace App\Livewire\Admin\GetServices;

use App\Enums\GetServiceStatus;
use App\Mail\GetServiceStatusMail;
use App\Models\GetServiceRequest;
use App\Services\ActivityLogService;
use App\Services\EmailService;
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

    public string $filter = 'all';

    public ?int $selectedRequestId = null;

    public bool $showDetailModal = false;

    public bool $showStatusModal = false;

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

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function selectRequest(int $id): void
    {
        $request = GetServiceRequest::findOrFail($id);
        $request->markRead();
        $this->selectedRequestId = $id;
        $this->showDetailModal = true;
    }

    public function closeModal(string $property): void
    {
        $this->$property = false;
        if ($property === 'showDetailModal') {
            $this->selectedRequestId = null;
        }
    }

    public function updateSelectedStatus(string $status): void
    {
        $request = GetServiceRequest::findOrFail($this->selectedRequestId);
        $request->update(['status' => GetServiceStatus::from($status)]);

        app(EmailService::class)->send(
            new GetServiceStatusMail($request),
            $request->email,
            'get_service.status_changed',
            $request
        );

        $this->dispatch('toast', message: 'Request status updated.', type: 'success');
    }

    public function openStatusModal(GetServiceRequest $request): void
    {
        $this->selectedRequestId = $request->id;
        $this->selectedStatus = $request->status->value;
        $this->showStatusModal = true;
    }

    public function saveStatus(ActivityLogService $logs): void
    {
        $request = GetServiceRequest::findOrFail($this->selectedRequestId);

        $request->update(['status' => GetServiceStatus::from($this->selectedStatus)]);

        $logs->record('get_service.status_changed', $request, [
            'name' => $request->full_name,
            'to' => $this->selectedStatus,
        ], auth('admin')->user());

        app(EmailService::class)->send(
            new GetServiceStatusMail($request),
            $request->email,
            'get_service.status_changed',
            $request
        );

        $this->showStatusModal = false;
        $this->dispatch('toast', message: 'Status updated.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.get-services.get-service-index', [
            'requests' => $this->requests(),
            'counts' => [
                'all' => GetServiceRequest::count(),
                'unread' => GetServiceRequest::where('is_read', false)->count(),
                'processing' => GetServiceRequest::where('status', GetServiceStatus::Processing)->count(),
                'completed' => GetServiceRequest::where('status', GetServiceStatus::Completed)->count(),
                'signed' => GetServiceRequest::where('status', GetServiceStatus::Signed)->count(),
            ],
            'selectedRequest' => $this->selectedRequestId ? GetServiceRequest::with('attachments')->find($this->selectedRequestId) : null,
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

        $query->when($this->filter === 'unread', fn (Builder $q) => $q->where('is_read', false))
            ->when(in_array($this->filter, ['processing', 'completed', 'signed'], true), fn (Builder $q) => $q->where('status', $this->filter));

        if ($this->sort === 'oldest') {
            $query->oldest();
        } elseif ($this->sort === 'unread') {
            $query->orderBy('is_read')->latest('id');
        } else {
            $query->latest();
        }

        return $query->withCount('attachments')->paginate(10);
    }
}
