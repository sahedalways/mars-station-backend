<?php

namespace App\Livewire\Admin\Auth;

use App\Mail\AdminOtpMail;
use App\Models\Admin;
use App\Models\AdminOtpRequest;
use App\Services\ActivityLogService;
use App\Services\EmailService;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class AdminLogin extends Component
{
    public int $step = 1;

    public string $email = '';

    public string $otp = '';

    public bool $showModal = false;

    public string $modalType = 'info';

    public string $modalTitle = '';

    public string $modalMessage = '';

    public function mount(): void
    {
        if (session()->has('admin_otp_pending_email') && session()->has('admin_otp_request_id')) {
            $this->email = (string) session()->get('admin_otp_pending_email');
            $this->step = 2;
        }
    }

    public function rules(): array
    {
        return $this->step === 2
            ? ['otp' => ['required', 'string', 'size:'.config('mars.otp.length')]]
            : ['email' => ['required', 'email', 'max:255']];
    }

    public function updatedEmail(): void
    {
        $this->resetValidation('email');
    }

    public function updatedOtp(): void
    {
        $this->resetValidation('otp');
    }

    public function requestOtp(OtpService $otp, EmailService $email, ActivityLogService $logs): void
    {
        $this->step = 1;
        $this->validate();

        $key = 'admin-otp-request:'.Str::lower($this->email).'|'.$this->fingerprint();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->openModal('error', 'Too Many Attempts', 'Please wait '.RateLimiter::availableIn($key).' seconds before trying again.');

            return;
        }

        RateLimiter::hit($key, 60);

        $admin = Admin::query()
            ->where('email', $this->email)
            ->where('is_active', true)
            ->first();

        // Generic response regardless of whether the admin exists.
        session()->put('admin_otp_pending_email', $this->email);

        if (! $admin) {
            $logs->record('admin.login.email_unknown', null, ['email' => $this->email], null, 'system');

            $this->openModal(
                'info',
                'Check Your Inbox',
                'If an account exists with this email, a verification code has been sent.'
            );

            return;
        }

        $code = $otp->generate(config('mars.otp.length'));
        $expiresAt = now()->addMinutes(config('mars.otp.expires_minutes'));

        $request = AdminOtpRequest::create([
            'admin_id' => $admin->id,
            'otp_hash' => $otp->hash($code),
            'expires_at' => $expiresAt,
            'max_attempts' => config('mars.otp.max_attempts'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->put('admin_otp_request_id', $request->id);

        $email->send(
            new AdminOtpMail($code, config('mars.otp.expires_minutes')),
            $admin->email,
            'admin.login.otp',
            null,
            $admin
        );

        $logs->record('admin.login.otp_sent', $request, ['email' => $admin->email], $admin);

        $this->otp = '';
        $this->step = 2;
        $this->openModal('success', 'Code Sent', 'A 6-digit verification code has been sent to '.$this->email.'.');
    }

    public function verifyOtp(OtpService $otp, ActivityLogService $logs): void
    {
        $this->step = 2;
        $this->validate();

        $requestId = session()->get('admin_otp_request_id');

        if (! $requestId) {
            $this->addError('otp', 'No active login request. Please request a new code.');

            return;
        }

        $otpRequest = AdminOtpRequest::find($requestId);

        if (! $otpRequest) {
            $this->addError('otp', 'This login request is no longer valid.');

            return;
        }

        $key = 'admin-otp-verify:'.$requestId.'|'.$this->fingerprint();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('otp', 'Too many attempts. Please wait '.RateLimiter::availableIn($key).' seconds.');

            return;
        }

        RateLimiter::hit($key, 60);

        if (! $otpRequest->isValid()) {
            $logs->record('admin.login.otp_invalid_expired', $otpRequest, ['email' => $otpRequest->admin->email], null, 'system');
            $this->addError('otp', $otpRequest->isExpired() ? 'This code has expired. Please request a new one.' : 'This code is no longer valid.');

            return;
        }

        $otpRequest->increment('attempts');

        if (! $otp->verify($this->otp, $otpRequest->otp_hash)) {
            $logs->record('admin.login.otp_incorrect', $otpRequest, ['email' => $otpRequest->admin->email], null, 'system');
            $this->addError('otp', 'The code you entered is incorrect.');

            return;
        }

        $otpRequest->update([
            'consumed_at' => now(),
        ]);

        $admin = $otpRequest->admin;

        if (! $admin->isAuthorized()) {
            $this->addError('otp', 'Your account has been deactivated.');

            return;
        }

        Auth::guard('admin')->login($admin, false);
        session()->regenerate(true);
        session()->forget(['admin_otp_request_id', 'admin_otp_pending_email']);

        $admin->update(['last_login_at' => now()]);

        $logs->record('admin.login.succeeded', $admin, ['email' => $admin->email], $admin);

        $this->redirect(route('admin.dashboard'), navigate: true);
    }

    public function backToEmail(): void
    {
        $this->otp = '';
        $this->step = 1;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.auth.login')
            ->layout('layouts.auth');
    }

    private function openModal(string $type, string $title, string $message): void
    {
        $this->modalType = $type;
        $this->modalTitle = $title;
        $this->modalMessage = $message;
        $this->showModal = true;
    }

    private function fingerprint(): string
    {
        return substr(hash('sha256', request()->ip().'|'.request()->userAgent()), 0, 24);
    }
}