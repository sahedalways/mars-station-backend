<?php

namespace App\Jobs;

use App\Mail\ExportReadyMail;
use App\Models\Admin;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ExportPaymentsJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 600;

    public function __construct(
        public int $adminId,
        public ?string $dateRange,
        public string $customFrom,
        public string $customTo,
        public ?string $status,
        public string $search,
    ) {}

    public function handle(): void
    {
        $query = Payment::query()
            ->with(['agreement' => fn ($q) => $q->select('id', 'agreement_number', 'title', 'client_name', 'client_email')])
            ->when($this->dateRange === '7d', fn ($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($this->dateRange === '30d', fn ($q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($this->dateRange === '6m', fn ($q) => $q->where('created_at', '>=', now()->subMonths(6)))
            ->when($this->dateRange === '1y', fn ($q) => $q->where('created_at', '>=', now()->subYear()))
            ->when($this->dateRange === 'custom' && $this->customFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->customFrom))
            ->when($this->dateRange === 'custom' && $this->customTo, fn ($q) => $q->whereDate('created_at', '<=', $this->customTo))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->search !== '', fn ($q) => $q->whereHas('agreement', function ($q) {
                $q->where('agreement_number', 'like', "%{$this->search}%")
                    ->orWhere('client_name', 'like', "%{$this->search}%")
                    ->orWhere('client_email', 'like', "%{$this->search}%");
            }))
            ->orderBy('created_at', 'desc');

        $filename = 'payment-exports/payments-'.now()->format('Y-m-d-Hi').'-'.str()->random(6).'.csv';
        $stream = fopen('php://temp', 'w');

        fputcsv($stream, [
            'Payment ID', 'Stripe ID', 'Agreement', 'Client', 'Email', 'Type', 'Status',
            'Amount', 'Currency', 'Milestone', 'Created At', 'Paid At', 'Refunds',
        ]);

        $query->chunk(500, function ($payments) use ($stream) {
            foreach ($payments as $payment) {
                fputcsv($stream, [
                    $payment->id,
                    $payment->stripe_payment_intent_id,
                    $payment->agreement?->agreement_number,
                    $payment->agreement?->client_name,
                    $payment->agreement?->client_email,
                    $payment->type->value,
                    $payment->status->value,
                    $payment->amount_pence,
                    $payment->currency,
                    $payment->milestone_id,
                    $payment->created_at?->toDateTimeString(),
                    $payment->paid_at?->toDateTimeString(),
                    $payment->refunds_count,
                ]);
            }
        });

        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        Storage::disk('local')->put($filename, $csv);

        $admin = Admin::find($this->adminId);

        if ($admin) {
            Mail::to($admin->email)->send(new ExportReadyMail($filename));
        }
    }
}
