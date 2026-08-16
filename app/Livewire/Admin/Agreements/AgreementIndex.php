<?php

namespace App\Livewire\Admin\Agreements;

use App\Enums\AgreementPaymentType;
use App\Enums\AgreementStatus;
use App\Models\Agreement;
use App\Services\AgreementService;
use App\Support\Money;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AgreementIndex extends Component
{
    use WithPagination;

    public string $tab = 'all';

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?string $status = null;

    #[Url(history: true)]
    public ?string $payment = null;

    #[Url(history: true)]
    public ?string $dateRange = null;

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    public int $statusTargetId = 0;

    public string $newStatus = '';

    public bool $showStatusModal = false;

    public bool $showArchiveModal = false;

    public int $archiveTargetId = 0;

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPayment(): void
    {
        $this->resetPage();
    }

    public function updatedDateRange(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function openStatusModal(int $agreementId): void
    {
        $agreement = Agreement::findOrFail($agreementId);
        $this->statusTargetId = $agreementId;
        $this->newStatus = $agreement->status->value;
        $this->showStatusModal = true;
    }

    public function saveStatus(AgreementService $service): void
    {
        $agreement = Agreement::findOrFail($this->statusTargetId);
        $status = AgreementStatus::tryFrom($this->newStatus);

        if (! $status) {
            $this->addError('newStatus', 'Invalid status.');

            return;
        }

        $service->changeStatus($agreement, $status, auth('admin')->user());

        $this->showStatusModal = false;

        $this->dispatch('toast', message: "Status updated to {$status->label()}.", type: 'success');
    }

    public function openArchiveModal(int $agreementId): void
    {
        $this->archiveTargetId = $agreementId;
        $this->showArchiveModal = true;
    }

    public function archiveAgreement(AgreementService $service): void
    {
        $agreement = Agreement::findOrFail($this->archiveTargetId);
        $service->archive($agreement, auth('admin')->user());

        $this->showArchiveModal = false;

        $this->dispatch('toast', message: 'Agreement archived.', type: 'success');
    }

    public function restoreAgreement(int $agreementId, AgreementService $service): void
    {
        $agreement = Agreement::findOrFail($agreementId);
        $service->restore($agreement, auth('admin')->user());

        $this->dispatch('toast', message: 'Agreement restored.', type: 'success');
    }

    public function deletePermanently(int $agreementId, AgreementService $service): void
    {
        $agreement = Agreement::withTrashed()->findOrFail($agreementId);
        $service->deletePermanently($agreement, auth('admin')->user());

        $this->dispatch('toast', message: 'Agreement permanently deleted.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.agreements.agreement-index', [
            'agreements' => $this->agreements(),
            'statuses' => AgreementStatus::cases(),
        ])->title('Agreements');
    }

    #[Computed]
    public function agreements()
    {
        $query = Agreement::query()
            ->with([
                'currentVersion',
                'payments' => fn ($q) => $q->select('id', 'agreement_id', 'amount_pence', 'status', 'type', 'refunded_amount_pence', 'created_at'),
            ])
            ->when($this->tab === 'archived', fn (Builder $q) => $q->where('is_archived', true))
            ->when($this->tab !== 'archived', fn (Builder $q) => $q->where('is_archived', false))
            ->when($this->search !== '', fn (Builder $q) => $q->where(function (Builder $q) {
                $q->where('agreement_number', 'like', "%{$this->search}%")
                    ->orWhere('client_name', 'like', "%{$this->search}%")
                    ->orWhere('client_email', 'like', "%{$this->search}%")
                    ->orWhere('client_mobile', 'like', "%{$this->search}%")
                    ->orWhere('title', 'like', "%{$this->search}%");
            }))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->when($this->payment === 'paid', fn (Builder $q) => $q->whereHas('payments', fn ($p) => $p->where('status', 'succeeded')))
            ->when($this->payment === 'unpaid', fn (Builder $q) => $q->whereDoesntHave('payments', fn ($p) => $p->where('status', 'succeeded')))
            ->when($this->payment === 'failed', fn (Builder $q) => $q->whereHas('payments', fn ($p) => $p->where('status', 'failed')))
            ->when($this->payment === 'refunded', fn (Builder $q) => $q->whereHas('payments', fn ($p) => $p->where('status', 'refunded')))
            ->when($this->payment === 'none', fn (Builder $q) => $q->where('payment_type', AgreementPaymentType::None->value))
            ->when($this->dateRange === '7d', fn (Builder $q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($this->dateRange === '30d', fn (Builder $q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($this->dateRange === '6m', fn (Builder $q) => $q->where('created_at', '>=', now()->subMonths(6)))
            ->when($this->dateRange === '1y', fn (Builder $q) => $q->where('created_at', '>=', now()->subYear()));

        $column = in_array($this->sortField, ['created_at', 'updated_at', 'client_name', 'agreement_number', 'status'], true)
            ? $this->sortField
            : 'created_at';

        return $query->orderBy($column, $this->sortDirection)->paginate(10);
    }
}
