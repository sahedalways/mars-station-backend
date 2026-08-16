<?php

namespace App\Livewire\Admin\Auth;

use App\Mail\AdminOtpMail;
use App\Models\Admin;
use App\Models\AdminOtpRequest;
use App\Services\ActivityLogService;
use App\Services\EmailService;
use App\Services\OtpService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class LoginEmail extends Component
{
    public string $email = '';

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function requestOtp(OtpService $otp, EmailService $email, ActivityLogService $logs): void
    {
        $this->validate();

        $key = 'admin-otp-request:'.Str::lower($this->email).'|'.$this->fingerprint();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Too many attempts. Please wait '.RateLimiter::availableIn($key).' seconds.');

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

        $this->redirect(route('admin.login.verify'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.auth.login-email')
            ->layout('layouts.auth');
    }

    private function fingerprint(): string
    {
        return substr(hash('sha256', request()->ip().'|'.request()->userAgent()), 0, 24);
    }
}
