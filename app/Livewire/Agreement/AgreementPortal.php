<?php

namespace App\Livewire\Agreement;

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

        abort_if($agreement->is_archived, 410, 'This agreement has been archived.');
        abort_if($version->isSigned(), 409, 'This version has already been signed.');

        if ($this->link->otp_enabled) {
            $signSessionId = session("agreement_otp_{$this->link->id}");
            $signSession = is_int($signSessionId) ? AgreementOtpSession::find($signSessionId) : null;
            abort_unless($signSession && $signSession->grantsAccess(), 403, 'Agreement access expired. Please reload the page.');
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

        $this->step = 'payment';
        $this->message = 'Thank you for signing. Your agreement is now valid.';
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

        $checkout = match ($agreement->payment_type->value) {
            'full' => $this->createFullCheckout($stripe, $agreement, $version),
            'milestone' => $this->createMilestoneCheckout($stripe, $agreement, $version),
            'subscription' => $this->createSubscriptionCheckout($stripe, $agreement, $version),
            default => null,
        };

        return $checkout ? redirect()->away($checkout->url) : $this->complete();
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
        $this->step = 'payment';
    }

    public function downloadPdf()
    {
        $version = $this->link->version;

        if ($version->signed_pdf_path) {
            return app(PdfService::class)->streamSignedPdf($this->link->agreement, $version);
        }

        return app(PdfService::class)->downloadAgreementPdf($this->link->agreement, $version);
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
            'cancel_url' => route('agreement.view', ['token' => $this->link->token]),
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
            'cancel_url' => route('agreement.view', ['token' => $this->link->token]),
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
            'cancel_url' => route('agreement.view', ['token' => $this->link->token]),
            'client_reference_id' => (string) $agreement->id,
            'metadata' => [
                'agreement_id' => (string) $agreement->id,
                'payment_type' => 'subscription',
                'version_id' => (string) $version->id,
            ],
        ]);

        return $session;
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
