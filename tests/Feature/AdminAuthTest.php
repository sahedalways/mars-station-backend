<?php

namespace Tests\Feature;

use App\Livewire\Admin\Auth\AdminLogin;
use App\Mail\AdminOtpMail;
use App\Models\Admin;
use App\Models\AdminOtpRequest;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Backend Login');
    }

    public function test_admin_can_request_otp(): void
    {
        Mail::fake();

        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'is_active' => true,
        ]);

        Livewire::test(AdminLogin::class)
            ->set('email', 'admin@example.com')
            ->call('requestOtp')
            ->assertSet('step', 2)
            ->assertSet('showModal', true)
            ->assertSet('modalType', 'success');

        $this->assertDatabaseHas('admin_otp_requests', [
            'admin_id' => $admin->id,
        ]);

        Mail::assertSent(AdminOtpMail::class, function (AdminOtpMail $mail) {
            return is_string($mail->code) && strlen($mail->code) === 6;
        });

        $this->assertDatabaseHas('email_logs', [
            'to_email' => 'admin@example.com',
            'event_type' => 'admin.login.otp',
            'status' => 'sent',
        ]);
    }

    public function test_request_otp_is_generic_for_unknown_email(): void
    {
        Livewire::test(AdminLogin::class)
            ->set('email', 'nobody@example.com')
            ->call('requestOtp')
            ->assertSet('step', 1)
            ->assertSet('showModal', true)
            ->assertSet('modalType', 'info');

        $this->assertDatabaseCount('admin_otp_requests', 0);
        $this->assertSame('nobody@example.com', session('admin_otp_pending_email'));
    }

    public function test_email_validation_rules_are_applied(): void
    {
        Livewire::test(AdminLogin::class)
            ->set('email', 'not-an-email')
            ->call('requestOtp')
            ->assertHasErrors('email');
    }

    public function test_admin_can_login_with_valid_otp(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'is_active' => true,
        ]);

        $otp = app(OtpService::class);
        $code = '123456';

        $request = AdminOtpRequest::create([
            'admin_id' => $admin->id,
            'otp_hash' => $otp->hash($code),
            'expires_at' => now()->addMinutes(5),
            'max_attempts' => 5,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test-agent',
        ]);

        session([
            'admin_otp_pending_email' => $admin->email,
            'admin_otp_request_id' => $request->id,
        ]);

        Livewire::test(AdminLogin::class)
            ->set('step', 2)
            ->set('email', $admin->email)
            ->set('otp', $code)
            ->call('verifyOtp')
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated('admin');

        $this->assertNotNull($request->fresh()->consumed_at);
        $this->assertNotNull($admin->fresh()->last_login_at);
    }

    public function test_admin_login_rejects_wrong_otp(): void
    {
        $admin = Admin::factory()->create(['email' => 'admin@example.com']);

        $otp = app(OtpService::class);

        $request = AdminOtpRequest::create([
            'admin_id' => $admin->id,
            'otp_hash' => $otp->hash('123456'),
            'expires_at' => now()->addMinutes(5),
            'max_attempts' => 5,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test-agent',
        ]);

        session([
            'admin_otp_pending_email' => $admin->email,
            'admin_otp_request_id' => $request->id,
        ]);

        Livewire::test(AdminLogin::class)
            ->set('step', 2)
            ->set('email', $admin->email)
            ->set('otp', '000000')
            ->call('verifyOtp')
            ->assertHasErrors('otp');

        $this->assertGuest('admin');
        $this->assertEquals(1, $request->fresh()->attempts);
    }

    public function test_admin_login_rejects_expired_otp(): void
    {
        $admin = Admin::factory()->create(['email' => 'admin@example.com']);

        $otp = app(OtpService::class);

        $request = AdminOtpRequest::create([
            'admin_id' => $admin->id,
            'otp_hash' => $otp->hash('123456'),
            'expires_at' => now()->subMinute(),
            'max_attempts' => 5,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test-agent',
        ]);

        session([
            'admin_otp_pending_email' => $admin->email,
            'admin_otp_request_id' => $request->id,
        ]);

        Livewire::test(AdminLogin::class)
            ->set('step', 2)
            ->set('email', $admin->email)
            ->set('otp', '123456')
            ->call('verifyOtp')
            ->assertHasErrors('otp');

        $this->assertGuest('admin');
    }

    public function test_inactive_admin_cannot_login(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'is_active' => false,
        ]);

        $otp = app(OtpService::class);

        $request = AdminOtpRequest::create([
            'admin_id' => $admin->id,
            'otp_hash' => $otp->hash('123456'),
            'expires_at' => now()->addMinutes(5),
            'max_attempts' => 5,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test-agent',
        ]);

        session([
            'admin_otp_pending_email' => $admin->email,
            'admin_otp_request_id' => $request->id,
        ]);

        Livewire::test(AdminLogin::class)
            ->set('step', 2)
            ->set('email', $admin->email)
            ->set('otp', '123456')
            ->call('verifyOtp')
            ->assertHasErrors('otp');

        $this->assertGuest('admin');
    }
}