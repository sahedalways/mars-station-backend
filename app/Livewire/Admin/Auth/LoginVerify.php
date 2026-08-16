<?php

namespace App\Livewire\Admin\Auth;

use App\Models\AdminOtpRequest;
use App\Services\ActivityLogService;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class LoginVerify extends Component
{
    public string $otp = '';

    public ?string $email = null;

    public function mount(): void
    {
        $this->email = session()->get('admin_otp_pending_email');

        if (! $this->email) {
            $this->redirect(route('admin.login'), navigate: true);
        }
    }

    public function rules(): array
    {
        return [
            'otp' => ['required', 'string', 'size:'.config('mars.otp.length')],
        ];
    }

    public function verifyOtp(OtpService $otp, ActivityLogService $logs): void
    {
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

    public function resendOtp(): void
    {
        session()->put('admin_otp_pending_email', $this->email);

        $this->redirect(route('admin.login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.auth.login-verify')
            ->layout('layouts.auth');
    }

    private function fingerprint(): string
    {
        return substr(hash('sha256', request()->ip().'|'.request()->userAgent()), 0, 24);
    }
}
