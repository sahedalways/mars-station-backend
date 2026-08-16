<?php

namespace Tests\Feature;

use App\Livewire\Agreement\AgreementPortal;
use App\Mail\AgreementSentMail;
use App\Models\Admin;
use App\Models\Agreement;
use App\Models\AgreementOtpSession;
use App\Services\AgreementService;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AgreementWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createAgreement(Admin $admin, array $overrides = []): Agreement
    {
        return app(AgreementService::class)->create(array_merge([
            'title' => 'Website Development',
            'client_name' => 'Alice Client',
            'client_email' => 'alice@example.com',
            'client_mobile' => '+44000000000',
            'content' => 'Mars Station will build a website for Alice Client.',
            'validity_date' => now()->addMonths(2)->format('Y-m-d'),
            'payment_type' => 'full',
            'full_title' => 'Website Build',
            'full_amount_pence' => 50000,
        ], $overrides), $admin);
    }

    public function test_service_creates_agreement_with_version_link_and_email(): void
    {
        Mail::fake();

        $admin = Admin::factory()->create();

        $agreement = $this->createAgreement($admin);

        $this->assertNotNull($agreement->agreement_number);
        $this->assertCount(1, $agreement->versions);
        $this->assertCount(1, $agreement->links);
        $this->assertDatabaseHas('agreements', ['id' => $agreement->id, 'status' => 'pending']);
        $this->assertDatabaseHas('agreement_versions', [
            'agreement_id' => $agreement->id,
            'version' => 1,
        ]);
        $this->assertDatabaseHas('agreement_links', [
            'agreement_id' => $agreement->id,
            'is_active' => true,
        ]);

        Mail::assertSent(AgreementSentMail::class);
        $this->assertDatabaseHas('email_logs', [
            'to_email' => 'alice@example.com',
            'event_type' => 'agreement.sent',
        ]);
    }

    public function test_client_can_view_agreement_portal(): void
    {
        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();

        $this->get(route('agreement.view', $link->token))
            ->assertOk()
            ->assertSee($agreement->agreement_number)
            ->assertSee('Alice Client');

        $this->assertDatabaseHas('agreement_access_logs', [
            'agreement_id' => $agreement->id,
            'status' => 'viewed',
        ]);
    }

    public function test_invalid_or_disabled_link_returns_404(): void
    {
        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();
        $link->disable();

        $this->get(route('agreement.view', $link->token))->assertNotFound();
        $this->get(route('agreement.view', 'invalid-token'))->assertNotFound();
    }

    public function test_client_can_sign_agreement(): void
    {
        Storage::fake('local');

        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();
        $version = $agreement->currentVersion;

        Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('step', 'sign')
            ->set('signName', 'Alice Client')
            ->set('signEmail', 'alice@example.com')
            ->call('sign');

        $version->refresh();

        $this->assertTrue($version->isSigned());
        $this->assertNotNull($version->signed_at);
        $this->assertNotNull($version->signed_pdf_path);
        $this->assertNotNull($version->signed_ip_address);

        $this->assertDatabaseHas('agreements', [
            'id' => $agreement->id,
            'status' => 'signed',
        ]);

        Storage::disk('local')->assertExists($version->signed_pdf_path);
    }

    public function test_client_can_download_unsigned_pdf(): void
    {
        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();

        $response = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->call('downloadPdf');

        $response->assertFileDownloaded($agreement->agreement_number.'-V1.pdf');
    }

    public function test_otp_protected_link_requires_verification(): void
    {
        Storage::fake('local');
        Mail::fake();

        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();
        $link->update(['otp_enabled' => true]);

        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token]);

        // OTP flow kicks in because otp_enabled and no prior session.
        $component->assertSee('Verify your email');
        $this->assertDatabaseHas('agreement_otp_sessions', [
            'link_id' => $link->id,
        ]);

        $session = AgreementOtpSession::where('link_id', $link->id)->firstOrFail();

        $this->assertDatabaseHas('email_logs', [
            'event_type' => 'agreement.otp',
            'status' => 'sent',
        ]);

        // Wrong code fails.
        $component->set('otp', '000000')->call('verifyOtp');
        $this->assertEquals(1, $session->fresh()->attempts);

        // Right code works if we can derive it; use OtpService to check.
        // We cannot read the plaintext, so simulate a fresh verified session:
        $code = '654321';
        $session->update(['otp_hash' => app(OtpService::class)->hash($code), 'attempts' => 0]);

        $component->set('otp', $code)->call('verifyOtp');

        $this->assertNotNull($session->fresh()->verified_at);
    }

    public function test_signed_agreement_moves_to_payment_step(): void
    {
        Storage::fake('local');

        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();

        $component = Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('step', 'sign')
            ->set('signName', 'Alice Client')
            ->set('signEmail', 'alice@example.com')
            ->call('sign');

        $this->assertDatabaseHas('agreements', [
            'id' => $agreement->id,
            'status' => 'signed',
        ]);

        $component->assertSet('step', 'payment');
    }

    public function test_new_version_created_after_signing(): void
    {
        Storage::fake('local');

        $admin = Admin::factory()->create();
        $agreement = $this->createAgreement($admin);
        $link = $agreement->links->first();

        Livewire::test(AgreementPortal::class, ['token' => $link->token])
            ->set('step', 'sign')
            ->set('signName', 'Alice Client')
            ->set('signEmail', 'alice@example.com')
            ->call('sign');

        $service = app(AgreementService::class);

        $version = $service->createNewVersion($agreement, [
            'title' => 'Website Development v2',
            'client_name' => 'Alice Client',
            'client_email' => 'alice@example.com',
            'client_mobile' => null,
            'content' => 'Updated scope.',
            'validity_date' => now()->addMonths(2)->format('Y-m-d'),
            'payment_type' => 'full',
            'full_title' => 'Website Build',
            'full_amount_pence' => 60000,
        ], $admin);

        $this->assertEquals(2, $version->version);

        $signedVersion = $agreement->versions()->where('version', 1)->first();
        $this->assertTrue($signedVersion->isSigned());
        $this->assertDatabaseHas('agreement_versions', [
            'agreement_id' => $agreement->id,
            'version' => 2,
            'status' => 'pending',
        ]);
    }
}
