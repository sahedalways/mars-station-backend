<?php

namespace App\Livewire\Agreement;

use App\Enums\MilestoneStatus;
use App\Models\AgreementLink;
use App\Models\AgreementOtpSession;
use App\Services\AgreementService;
use App\Services\EmailService;
use App\Services\OtpService;
use App\Services\PdfService;
use App\Services\StripeService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.agreement')]
class AgreementPortal extends Component
{
    public ?AgreementLink $link = null;

    public bool $isArchived = false;

    public string $step = 'view';

    // OTP flow
    public string $otpEmail = '';

    public string $otp = '';

    // Signature
    public string $signName = '';

    public string $signEmail = '';

    public string $message = '';

    public string $error = '';

    public bool $showPaymentModal = false;

    public bool $paymentPending = false;

    public string $paymentType = '';

    public string $completionMessage = '';

    public int $pendingMilestoneId = 0;

    public int $pendingMilestoneIndex = 0;

    public int $totalMilestoneCount = 0;

    public function mount(string $token): void
    {
        $this->link = AgreementLink::query()
            ->where('token', $token)
            ->where('is_active', true)
            ->with(['agreement', 'version'])
            ->first();

        abort_unless($this->link, 404, 'This agreement link is invalid or has expired.');

        $agreement = $this->link->agreement;

        if ($agreement->is_archived) {
            $this->isArchived = true;

            return;
        }

        $this->signName = $agreement->client_name;
        $this->signEmail = $agreement->client_email;
        $this->otpEmail = $agreement->client_email;

        $this->recordAccess('viewed');

        if ($this->link->otp_enabled) {
            $sessionId = session("agreement_otp_{$this->link->id}");
            $session = is_int($sessionId) ? AgreementOtpSession::find($sessionId) : null;

            if ($session && $session->grantsAccess()) {
                $this->step = 'view';
            } else {
                session()->forget("agreement_otp_{$this->link->id}");
                $this->step = 'otp';
                $this->requestOtp();
            }
        }

        $statusParam = request()->query('status');

        if ($statusParam === 'success') {
            $agreement->refresh();
            $this->paymentType = $agreement->payment_type->value;

            if ($agreement->isPaid()) {
                $this->completionMessage = $this->buildCompletionMessage($agreement);
                $this->step = 'complete';
            } else {
                $this->paymentPending = true;
                $this->step = 'view';

                if ($this->paymentType === 'milestone') {
                    $allMs = $agreement->milestones()->orderBy('order_index')->get();
                    $this->totalMilestoneCount = $allMs->count();

                    // Find the milestone that was just paid (webhook may have already processed).
                    $justPaid = $agreement->milestones()
                        ->where('status', MilestoneStatus::Paid)
                        ->orderByDesc('paid_at')
                        ->first();

                    // Fall back to the next pending milestone if webhook hasn't arrived yet.
                    $trackMs = $justPaid ?? $agreement->nextMilestone();

                    if ($trackMs) {
                        $this->pendingMilestoneId = $trackMs->id;
                        $this->pendingMilestoneIndex = (int) $allMs->search(fn ($m) => $m->id === $trackMs->id) + 1;
                    }
                }
            }
        } elseif ($statusParam === 'cancelled') {
            $this->paymentType = $agreement->payment_type->value;
            $this->error = match ($this->paymentType) {
                'subscription' => 'Subscription payment was cancelled. Your subscription has not been activated. You can try again when ready.',
                'milestone' => 'Milestone payment was cancelled. This milestone is still unpaid. You can try again when ready.',
                default => 'Full payment was cancelled. No payment was completed. You can try again when ready.',
            };
        }
    }

    public function requestOtp(): void
    {
        if (! $this->link->otp_enabled) {
            return;
        }

        $key = 'agreement-otp:'.Str::lower($this->otpEmail).'|'.$this->link->id.'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->error = 'Too many attempts. Please try again later.';

            return;
        }

        RateLimiter::hit($key, 60 * config('mars.agreement_otp.expiry_minutes', 5));

        $agreement = $this->link->agreement;

        $this->link->agreement()->first()->otpSessions()
            ->where('email', $this->otpEmail)
            ->where('consumed_at', null)
            ->get()
            ->each(fn ($s) => $s->update(['consumed_at' => now()]));

        $otpService = app(OtpService::class);
        $code = $otpService->generate();

        $session = AgreementOtpSession::create([
            'agreement_id' => $agreement->id,
            'link_id' => $this->link->id,
            'email' => $this->otpEmail,
            'otp_hash' => $otpService->hash($code),
            'expires_at' => now()->addMinutes(config('mars.agreement_otp.expiry_minutes', 5)),
            'session_expires_at' => now()->addMinutes(config('mars.agreement_otp.session_minutes', 30)),
            'attempts' => 0,
            'max_attempts' => config('mars.agreement_otp.max_attempts', 5),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        app(EmailService::class)->send(
            new \App\Mail\AgreementOtpMail($agreement, $this->link, $code),
            $this->otpEmail,
            'agreement.otp',
            $agreement
        );

        session()->put("agreement_otp_session_{$this->link->id}", $session->id);
        $this->error = '';
        $this->message = 'A verification code has been sent to your email.';
    }

    public function verifyOtp(): void
    {
        $sessionId = session()->get("agreement_otp_session_{$this->link->id}");

        $session = AgreementOtpSession::find($sessionId);

        abort_unless($session && $session->link_id === $this->link->id, 403);

        if (! $session->isValid()) {
            $this->error = 'This code has expired. Request a new one.';

            return;
        }

        if (! app(OtpService::class)->verify($this->otp, $session->otp_hash)) {
            $session->increment('attempts');

            $this->error = 'Invalid code. Please try again.';

            return;
        }

        $session->update([
            'verified_at' => now(),
            'consumed_at' => now(),
        ]);

        session()->put("agreement_otp_{$this->link->id}", $session->id);
        $this->step = 'view';
        $this->otp = '';
        $this->error = '';
        $this->recordAccess('otp_verified');
    }

    public function sign(\App\Services\ActivityLogService $logs): void
    {
        $this->validate([
            'signName' => ['required', 'string', 'max:255'],
            'signEmail' => ['required', 'email', 'max:255'],
        ]);

        $agreement = $this->link->agreement;
        $version = $this->link->version;

        if ($agreement->is_archived) {
            $this->error = 'This agreement has been archived.';

            return;
        }

        if ($version->isSigned()) {
            $this->error = 'This version has already been signed.';

            return;
        }

        if ($this->link->otp_enabled) {
            $signSessionId = session("agreement_otp_{$this->link->id}");
            $signSession = is_int($signSessionId) ? AgreementOtpSession::find($signSessionId) : null;

            if (! $signSession || ! $signSession->grantsAccess()) {
                $this->error = 'Agreement access expired. Please reload the page.';

                return;
            }
        }

        $pdfService = app(PdfService::class);

        $signedPdfPath = $pdfService->storeSignedPdf($agreement, $version);

        $version->update([
            'status' => 'signed',
            'signed_name' => $this->signName,
            'signed_email' => $this->signEmail,
            'signed_at' => now(),
            'signed_ip_address' => request()->ip(),
            'signed_user_agent' => request()->userAgent(),
            'signed_pdf_path' => $signedPdfPath,
        ]);

        app(AgreementService::class)->markSigned($agreement, $version);

        $this->recordAccess('signed');

        $logs->record('agreement.client_signed', $agreement, [
            'agreement_number' => $agreement->agreement_number,
            'version' => $version->version,
            'signed_email' => $this->signEmail,
        ]);

        $this->step = 'view';
        $this->showPaymentModal = true;
        $this->message = 'Thank you for signing. Your agreement is now valid.';

        $this->dispatch('toast', message: 'Agreement signed successfully!', type: 'success');
    }

    public function payNow(StripeService $stripe): mixed
    {
        $agreement = $this->link->agreement;
        $version = $this->link->version;

        if ($agreement->payment_type->value === 'none' || $agreement->isPaid()) {
            return $this->complete();
        }

        if (! $stripe->isConfigured()) {
            $this->error = 'Payments are temporarily unavailable. Please try again later.';

            return null;
        }

        try {
            $checkout = match ($agreement->payment_type->value) {
                'full' => $this->createFullCheckout($stripe, $agreement, $version),
                'milestone' => $this->createMilestoneCheckout($stripe, $agreement, $version),
                'subscription' => $this->createSubscriptionCheckout($stripe, $agreement, $version),
                default => null,
            };
        } catch (\Throwable $e) {
            report($e);
            $this->error = 'Payment session could not be created. Please try again.';

            return null;
        }

        if (! $checkout) {
            $this->error = 'Payment session could not be created. Please try again.';

            return null;
        }

        return redirect()->away($checkout->url);
    }

    public function complete(): void
    {
        $this->step = 'complete';
    }

    public function goToSign(): void
    {
        $this->step = 'sign';
    }

    public function goToView(): void
    {
        $this->step = 'view';
    }

    public function goToPayment(): void
    {
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    public function downloadPdf()
    {
        return app(PdfService::class)->downloadAgreementPdf($this->link->agreement, $this->link->version);
    }

    private function createFullCheckout(StripeService $stripe, $agreement, $version)
    {
        $config = $version->payment_config ?? [];

        $session = $stripe->createCheckoutSession([
            'mode' => 'payment',
            'customer_email' => $agreement->client_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $stripe->currency(),
                    'unit_amount' => (int) ($config['amount_pence'] ?? 0),
                    'product_data' => ['name' => $config['title'] ?? $agreement->title],
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('agreement.view', ['token' => $this->link->token]).'?status=success',
            'cancel_url' => route('agreement.view', ['token' => $this->link->token]).'?status=cancelled',
            'client_reference_id' => (string) $agreement->id,
            'metadata' => [
                'agreement_id' => (string) $agreement->id,
                'payment_type' => 'full',
                'version_id' => (string) $version->id,
            ],
        ]);

        return $session;
    }

    private function createMilestoneCheckout(StripeService $stripe, $agreement, $version)
    {
        $milestone = $agreement->nextMilestone();

        if (! $milestone) {
            return null;
        }

        $session = $stripe->createCheckoutSession([
            'mode' => 'payment',
            'customer_email' => $agreement->client_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $stripe->currency(),
                    'unit_amount' => (int) $milestone->amount_pence,
                    'product_data' => ['name' => $agreement->agreement_number.' — '.$milestone->title],
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('agreement.view', ['token' => $this->link->token]).'?status=success',
            'cancel_url' => route('agreement.view', ['token' => $this->link->token]).'?status=cancelled',
            'client_reference_id' => (string) $agreement->id,
            'metadata' => [
                'agreement_id' => (string) $agreement->id,
                'payment_type' => 'milestone',
                'milestone_id' => (string) $milestone->id,
                'version_id' => (string) $version->id,
            ],
        ]);

        return $session;
    }

    private function createSubscriptionCheckout(StripeService $stripe, $agreement, $version)
    {
        $config = $version->payment_config ?? [];

        $session = $stripe->createCheckoutSession([
            'mode' => 'subscription',
            'customer_email' => $agreement->client_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $stripe->currency(),
                    'unit_amount' => (int) ($config['amount_pence'] ?? 0),
                    'recurring' => ['interval' => ($config['frequency'] ?? 'monthly') === 'yearly' ? 'year' : 'month'],
                    'product_data' => ['name' => $config['title'] ?? $agreement->title],
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('agreement.view', ['token' => $this->link->token]).'?status=success',
            'cancel_url' => route('agreement.view', ['token' => $this->link->token]).'?status=cancelled',
            'client_reference_id' => (string) $agreement->id,
            'metadata' => [
                'agreement_id' => (string) $agreement->id,
                'payment_type' => 'subscription',
                'version_id' => (string) $version->id,
            ],
        ]);

        return $session;
    }

    public function checkPaymentStatus(): void
    {
        if (! $this->paymentPending) {
            return;
        }

        $agreement = $this->link->agreement->fresh();

        if ($this->paymentType === 'milestone' && $this->pendingMilestoneId) {
            $milestone = $agreement->milestones()->find($this->pendingMilestoneId);

            if ($milestone && $milestone->status->value === 'paid') {
                $this->paymentPending = false;

                $allMs = $agreement->milestones()->orderBy('order_index')->get();
                $totalMs = $allMs->count();
                $paidMs = $allMs->where('status', 'paid')->count();
                $remaining = $totalMs - $paidMs;

                if ($remaining > 0) {
                    $this->message = "Milestone {$this->pendingMilestoneIndex} of {$totalMs} paid successfully. {$remaining} milestone".($remaining !== 1 ? 's' : '').' remaining.';
                    $this->showPaymentModal = true;
                    $this->error = '';
                } else {
                    $this->completionMessage = 'Final milestone paid successfully. Your agreement is now complete.';
                    $this->step = 'complete';
                    $this->message = '';
                    $this->error = '';
                }
            }

            return;
        }

        if ($agreement->isPaid()) {
            $this->paymentPending = false;
            $this->completionMessage = $this->buildCompletionMessage($agreement);
            $this->step = 'complete';
            $this->message = '';
            $this->error = '';
        }
    }

    private function buildCompletionMessage(\App\Models\Agreement $agreement): string
    {
        $type = $agreement->payment_type->value;

        return match ($type) {
            'subscription' => $this->buildSubscriptionCompletionMessage($agreement),
            'milestone' => 'Final milestone paid successfully. Your agreement is now complete.',
            'full' => 'Payment received in full. Your agreement is now complete.',
            default => 'Your agreement with Mars Station is now complete. You can download a copy of the signed agreement below.',
        };
    }

    private function buildSubscriptionCompletionMessage(\App\Models\Agreement $agreement): string
    {
        $subscription = $agreement->subscriptions()
            ->whereIn('status', ['active', 'trialing', 'past_due'])
            ->latest()
            ->first();

        if ($subscription && $subscription->current_period_end) {
            $nextDate = $subscription->current_period_end->format('F j, Y');

            return "Subscription activated successfully. Your next billing date is {$nextDate}.";
        }

        return 'Subscription activated successfully. Your subscription is now active.';
    }

    private function recordAccess(string $status): void
    {
        $this->link->agreement()->first()->accessLogs()->create([
            'link_id' => $this->link->id,
            'type' => $this->link->otp_enabled ? 'otp' : 'link',
            'email' => $this->otpEmail ?: $this->link->agreement->client_email,
            'status' => $status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        if ($this->isArchived) {
            return view('livewire.agreement.archived')
                ->title('Agreement Archived');
        }

        return view('livewire.agreement.agreement-portal', [
            'agreement' => $this->link->agreement,
            'version' => $this->link->version,
        ])->title('Agreement '.$this->link->agreement->agreement_number);
    }
}
