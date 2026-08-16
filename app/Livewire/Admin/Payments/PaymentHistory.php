<?php

namespace App\Livewire\Admin\Payments;

use App\Jobs\ExportPaymentsJob;
use App\Models\Payment;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PaymentHistory extends Component
{
    use WithPagination;

    public bool $embedded = false;

    #[Url(history: true)]
    public ?string $dateRange = null;

    public string $customFrom = '';

    public string $customTo = '';

    #[Url(history: true)]
    public ?string $status = null;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    public bool $showExportModal = false;

    public function updatedDateRange(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
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

    public function requestExport(): void
    {
        $this->showExportModal = true;
    }

    public function exportCsv(): void
    {
        $this->showExportModal = false;

        ExportPaymentsJob::dispatch(
            auth('admin')->id(),
            $this->dateRange,
            $this->customFrom,
            $this->customTo,
            $this->status,
            $this->search
        );

        $this->dispatch('toast', message: 'Export is being generated. You will receive an email when ready.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.payments.payment-history', [
            'payments' => $this->payments(),
        ])->title('Payment History');
    }

    #[Computed]
    public function payments()
    {
        $query = Payment::query()
            ->with(['agreement' => fn ($q) => $q->select('id', 'agreement_number', 'title', 'client_name', 'client_email')])
            ->withCount('refunds')
            ->when($this->dateRange === '7d', fn (Builder $q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($this->dateRange === '30d', fn (Builder $q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($this->dateRange === '6m', fn (Builder $q) => $q->where('created_at', '>=', now()->subMonths(6)))
            ->when($this->dateRange === '1y', fn (Builder $q) => $q->where('created_at', '>=', now()->subYear()))
            ->when($this->dateRange === 'custom' && $this->customFrom, fn (Builder $q) => $q->whereDate('created_at', '>=', $this->customFrom))
            ->when($this->dateRange === 'custom' && $this->customTo, fn (Builder $q) => $q->whereDate('created_at', '<=', $this->customTo))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->when($this->search !== '', fn (Builder $q) => $q->whereHas('agreement', function (Builder $q) {
                $q->where('agreement_number', 'like', "%{$this->search}%")
                    ->orWhere('client_name', 'like', "%{$this->search}%")
                    ->orWhere('client_email', 'like', "%{$this->search}%");
            }));

        $column = in_array($this->sortField, ['created_at', 'amount_pence', 'status'], true)
            ? $this->sortField
            : 'created_at';

        return $query->orderBy($column, $this->sortDirection)->paginate(15);
    }
}
