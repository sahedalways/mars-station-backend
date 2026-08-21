<?php

namespace App\Livewire\Admin\Agreements;

use App\Enums\AgreementStatus;
use App\Models\Agreement;
use App\Services\AgreementService;
use App\Services\PdfService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AgreementShow extends Component
{
    public Agreement $agreement;

    public bool $showStatusModal = false;

    public string $newStatus = '';

    public bool $showArchiveModal = false;

    public bool $showLinkRegenModal = false;

    public bool $showResendModal = false;

    public bool $showOtpToggleModal = false;

    public bool $showTerminateModal = false;

    public bool $showPaymentReminderModal = false;

    public bool $showVersionPreviewModal = false;

    public ?int $previewVersionId = null;

    public function mount(Agreement $agreement): void
    {
        abort_unless($agreement->exists, 404);

        $this->showPaymentReminderModal = false;

        $this->agreement = $agreement->load([
            'versions' => fn ($q) => $q->latest('version'),
            'currentVersion',
            'links' => fn ($q) => $q->latest('id'),
            'attachments',
            'milestones' => fn ($q) => $q->orderBy('order_index'),
            'subscriptions',
            'payments' => fn ($q) => $q->with('refunds')->latest('id'),
            'creator',
            'accessLogs' => fn ($q) => $q->latest('id')->limit(20),
        ]);

        $this->newStatus = $agreement->status->value;

        if (request()->query('send_reminder') === '1' && $agreement->payment_type->value !== 'none') {
            $this->showPaymentReminderModal = true;
        }
    }

    public function closeModal(string $property): void
    {
        $this->$property = false;
    }

    public function previewVersion(int $versionId): void
    {
        $version = $this->agreement->versions()->where('id', $versionId)->first();

        if ($version) {
            $this->previewVersionId = $version->id;
            $this->showVersionPreviewModal = true;
        }
    }

    public function closeVersionPreviewModal(): void
    {
        $this->showVersionPreviewModal = false;
        $this->previewVersionId = null;
    }

    public function openStatusModal(): void
    {
        $this->newStatus = $this->agreement->status->value;
        $this->showStatusModal = true;
    }

    public function saveStatus(AgreementService $service): void
    {
        $status = AgreementStatus::tryFrom($this->newStatus);

        if (! $status) {
            $this->addError('newStatus', 'Invalid status.');

            return;
        }

        $service->changeStatus($this->agreement, $status, auth('admin')->user());
        $this->showStatusModal = false;

        $this->dispatch('toast', message: "Status updated to {$status->label()}.", type: 'success');
    }

    public function archive(AgreementService $service): void
    {
        $service->archive($this->agreement, auth('admin')->user());
        $this->showArchiveModal = false;

        $this->dispatch('toast', message: 'Agreement archived.', type: 'success');
    }

    public function terminateAgreement(AgreementService $service): void
    {
        $service->changeStatus($this->agreement, AgreementStatus::Terminated, auth('admin')->user());

        $this->agreement->links()->where('is_active', true)->get()->each->disable();
        $this->agreement->refresh();

        $this->showTerminateModal = false;

        $this->dispatch('toast', message: 'Agreement terminated. Client link disabled.', type: 'success');
    }

    public function regenerateLink(AgreementService $service): void
    {
        $activeLink = $this->agreement->links()->where('is_active', true)->first();

        if ($activeLink) {
            $service->regenerateLink($activeLink, auth('admin')->user());
        } else {
            $service->createLink($this->agreement, $this->agreement->currentVersion, auth('admin')->user());
        }

        $this->showLinkRegenModal = false;
        $this->agreement->refresh();

        $this->dispatch('toast', message: 'A new secure link was generated. The old link is now invalid.', type: 'success');
    }

    public function toggleOtpProtection(AgreementService $service): void
    {
        $link = $this->agreement->links()->where('is_active', true)->first();

        if ($link) {
            $link->update(['otp_enabled' => ! $link->otp_enabled]);
        } else {
            $link = $service->createLink($this->agreement, $this->agreement->currentVersion, auth('admin')->user(), true);
        }

        $this->showOtpToggleModal = false;
        $this->agreement->refresh();

        $this->dispatch('toast', message: $link->otp_enabled ? 'Email OTP protection enabled.' : 'Email OTP protection disabled.', type: 'success');
    }

    public function resendAgreement(AgreementService $service): void
    {
        $link = $this->agreement->links()->where('is_active', true)->latest('id')->first();

        if (! $link) {
            $link = $service->createLink($this->agreement, $this->agreement->currentVersion, auth('admin')->user());
        }

        $version = $this->agreement->currentVersion;

        $service->sendAgreement($this->agreement, $link, $version, auth('admin')->user());

        $this->showResendModal = false;

        $this->dispatch('toast', message: 'Agreement email resent.', type: 'success');
    }

    public function sendPaymentReminder(AgreementService $service): void
    {
        $link = $this->agreement->links()->where('is_active', true)->latest('id')->first();

        if (! $link) {
            $link = $service->createLink($this->agreement, $this->agreement->currentVersion, auth('admin')->user());
        }

        $version = $this->agreement->currentVersion;

        $service->sendPaymentReminder($this->agreement, $link, $version, auth('admin')->user());

        $this->showPaymentReminderModal = false;

        $this->dispatch('toast', message: 'Payment reminder sent to ' . $this->agreement->client_email . '.', type: 'success');
    }

    public function downloadPdf(PdfService $pdf)
    {
        $version = $this->agreement->currentVersion;

        if (! $version) {
            $this->dispatch('toast', message: 'No agreement version found.', type: 'error');

            return null;
        }

        return $pdf->downloadAgreementPdf($this->agreement, $version);
    }

    public function downloadVersion(PdfService $pdf, int $versionId)
    {
        $version = $this->agreement->versions()->where('id', $versionId)->first();

        if (! $version) {
            $this->dispatch('toast', message: 'Version not found.', type: 'error');

            return;
        }

        return $pdf->downloadAgreementPdf($this->agreement, $version);
    }

    public function render()
    {
        return view('livewire.admin.agreements.agreement-show', [
            'statuses' => AgreementStatus::cases(),
            'previewVersion' => $this->previewVersionId
                ? $this->agreement->versions()->where('id', $this->previewVersionId)->first()
                : null,
        ])->title($this->agreement->agreement_number);
    }
}
