<?php

namespace App\Livewire\Admin\Payments;

use App\Jobs\ExportPaymentsJob;
use App\Models\Payment;
use App\Support\Money;
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
    public string $paymentMethod = '';

    #[Url(history: true)]
    public string $paymentType = '';

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    public bool $showExportModal = false;

    public bool $showRefundModal = false;

    public int $refundTargetId = 0;

    public int $refundAmountPence = 0;

    public string $refundReason = '';

    public string $refundAmountFormatted = '';

    public function updatedRefundAmountFormatted(string $value): void
    {
        $value = str_replace([',', ' '], '', $value);
        $pence = (int) round((float) $value * 100);
        $this->refundAmountPence = max(1, min($pence, $this->refundTargetId ? \App\Models\Payment::find($this->refundTargetId)?->refundableAmountPence() ?? 0 : 0));
        $this->refundAmountFormatted = \App\Support\Money::format($this->refundAmountPence);
    }

    public function updatedRefundAmountPence(int $value): void
    {
        if ($value > 0) {
            $this->refundAmountFormatted = \App\Support\Money::format($value);
        }
    }

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

    public function updatedPaymentMethod(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentType(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('dateRange', 'customFrom', 'customTo', 'status', 'paymentMethod', 'paymentType', 'search');
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

    public function openRefundModal(int $paymentId): void
    {
        $payment = Payment::with('agreement')->findOrFail($paymentId);
        $this->refundTargetId = $paymentId;
        $this->refundAmountPence = $payment->refundableAmountPence();
        $this->refundAmountFormatted = \App\Support\Money::format($payment->refundableAmountPence());
        $this->refundReason = '';
        $this->showRefundModal = true;
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->refundTargetId = 0;
        $this->refundAmountPence = 0;
        $this->refundAmountFormatted = '';
        $this->refundReason = '';
        $this->resetErrorBag();
    }

    public function closeModal(string $property): void
    {
        $this->$property = false;
    }

    public function processRefund(\App\Services\StripeService $stripe, \App\Services\EmailService $email): void
    {
        $this->validate([
            'refundAmountPence' => 'required|integer|min:1',
            'refundReason' => 'required|string|max:500',
        ]);

        $payment = Payment::with('agreement')->findOrFail($this->refundTargetId);

        if ($this->refundAmountPence > $payment->refundableAmountPence()) {
            $this->addError('refundAmountPence', 'Refund amount exceeds refundable amount.');

            return;
        }

        if (! $payment->stripe_payment_intent_id) {
            $this->addError('refundAmountPence', 'Cannot refund: No Stripe payment intent found.');

            return;
        }

        try {
            $refundParams = [
                'payment_intent' => $payment->stripe_payment_intent_id,
                'amount' => $this->refundAmountPence,
                'metadata' => [
                    'admin_id' => auth('admin')->id(),
                    'reason' => $this->refundReason,
                ],
            ];

            if ($this->refundReason !== '') {
                $refundParams['reason'] = 'requested_by_customer';
            }

            $stripeRefund = $stripe->createRefund($refundParams);

            $refund = $payment->refunds()->create([
                'payment_id' => $payment->id,
                'stripe_refund_id' => $stripeRefund->id,
                'amount_pence' => $this->refundAmountPence,
                'currency' => $payment->currency,
                'status' => $stripeRefund->status,
                'reason' => $this->refundReason,
                'admin_id' => auth('admin')->id(),
                'processed_at' => now(),
            ]);

            $payment->increment('refunded_amount_pence', $this->refundAmountPence);

            if ($payment->refunded_amount_pence >= $payment->amount_pence) {
                $payment->status = \App\Enums\PaymentStatus::Refunded;
            } else {
                $payment->status = \App\Enums\PaymentStatus::PartiallyRefunded;
            }
            $payment->save();

            // Send refund email to client
            $link = $payment->agreement->links()->where('is_active', true)->latest('id')->first();
            if ($link) {
                $email->send(
                    new \App\Mail\PaymentRefundedMail($payment->agreement, $refund, $link),
                    $payment->agreement->client_email,
                    'payment.refunded',
                    $payment->agreement,
                    auth('admin')->user()
                );
            }

            $this->closeRefundModal();

            $this->dispatch('toast', message: 'Refund processed successfully via Stripe. Client notified.', type: 'success');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe refund failed', ['error' => $e->getMessage(), 'payment_id' => $payment->id]);
            $this->addError('refundAmountPence', 'Stripe refund failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.payments.payment-history', [
            'payments' => $this->payments(),
            'dashboardStats' => $this->dashboardStats(),
            'paymentMethods' => Payment::query()
                ->whereNotNull('payment_method')
                ->distinct()
                ->orderBy('payment_method')
                ->pluck('payment_method'),
        ])->title('Payment History');
    }

    #[Computed]
    public function payments()
    {
        $query = Payment::query()
            ->with(['agreement' => fn ($q) => $q->select('id', 'agreement_number', 'title', 'client_name', 'client_email', 'payment_type')])
            ->with(['refunds' => fn ($q) => $q->where('status', 'succeeded')->latest('processed_at')])
            ->withCount('refunds')
            ->when($this->dateRange === '7d', fn (Builder $q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($this->dateRange === '30d', fn (Builder $q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($this->dateRange === '6m', fn (Builder $q) => $q->where('created_at', '>=', now()->subMonths(6)))
            ->when($this->dateRange === '1y', fn (Builder $q) => $q->where('created_at', '>=', now()->subYear()))
            ->when($this->dateRange === 'custom' && $this->customFrom, fn (Builder $q) => $q->whereDate('created_at', '>=', $this->customFrom))
            ->when($this->dateRange === 'custom' && $this->customTo, fn (Builder $q) => $q->whereDate('created_at', '<=', $this->customTo))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->when($this->paymentMethod !== '', fn (Builder $q) => $q->where('payment_method', $this->paymentMethod))
            ->when($this->paymentType !== '', fn (Builder $q) => $q->where('type', $this->paymentType))
            ->when($this->search !== '', fn (Builder $q) => $q->whereHas('agreement', function (Builder $q) {
                $q->where('agreement_number', 'like', "%{$this->search}%")
                    ->orWhere('client_name', 'like', "%{$this->search}%")
                    ->orWhere('client_email', 'like', "%{$this->search}%");
            }));

        $column = in_array($this->sortField, ['created_at', 'amount_pence', 'status'], true)
            ? $this->sortField
            : 'created_at';

        return $query->orderBy($column, $this->sortDirection)->paginate(10);
    }

    #[Computed]
    public function dashboardStats(): array
    {
        $successful = Payment::query()->where('status', 'succeeded');
        $refunds = Payment::query()->sum('refunded_amount_pence');
        $received = (clone $successful)->sum('amount_pence');

        $periods = [
            ['label' => 'Last 7 Days', 'days' => 7],
            ['label' => 'Last 30 Days', 'days' => 30],
            ['label' => 'Last 6 Months', 'months' => 6],
            ['label' => 'Last Year', 'months' => 12],
        ];

        $kpis = collect($periods)->map(function (array $period) {
            $from = isset($period['days']) ? now()->subDays($period['days']) : now()->subMonths($period['months']);
            $previousFrom = isset($period['days']) ? $from->copy()->subDays($period['days']) : $from->copy()->subMonths($period['months']);
            $current = Payment::query()->where('status', 'succeeded')->whereBetween('paid_at', [$from, now()])->sum('amount_pence');
            $previous = Payment::query()->where('status', 'succeeded')->whereBetween('paid_at', [$previousFrom, $from])->sum('amount_pence');

            return [
                'label' => $period['label'],
                'value' => Money::format($current),
                'trend' => $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : null,
                'trendText' => 'vs previous '.$period['label'],
            ];
        })->all();

        $chartStart = now()->startOfDay()->subDays(6);
        $dailyTotals = Payment::query()
            ->where('status', 'succeeded')
            ->where('paid_at', '>=', $chartStart)
            ->get(['paid_at', 'amount_pence'])
            ->groupBy(fn (Payment $payment) => $payment->paid_at->toDateString())
            ->map(fn ($payments) => $payments->sum('amount_pence'));
        $chart = collect(range(0, 6))->map(function (int $offset) use ($chartStart, $dailyTotals) {
            $date = $chartStart->copy()->addDays($offset);

            return [
                'label' => $date->format('M j'),
                'amount' => $dailyTotals->get($date->toDateString(), 0),
            ];
        })->all();

        return [
            'kpis' => $kpis,
            'received' => $received,
            'refunds' => $refunds,
            'transactions' => (clone $successful)->count(),
            'average' => (clone $successful)->avg('amount_pence') ?? 0,
            'chart' => $chart,
        ];
    }
}
