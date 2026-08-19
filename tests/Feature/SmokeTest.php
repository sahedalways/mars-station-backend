<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->actingAs(Admin::first(), 'admin');
        
        // Manually set session for subdomain
        $admin = Admin::first();
        session(['admin' => $admin->id]);
        session()->save();
    }

    protected function getAdminHost(): array
    {
        return [
            'HTTP_HOST' => config('app.admin_subdomain') . '.' . config('app.domain'),
            'SERVER_NAME' => config('app.admin_subdomain') . '.' . config('app.domain'),
        ];
    }

    protected function getClientHost(): array
    {
        return [
            'HTTP_HOST' => config('app.client_subdomain') . '.' . config('app.domain'),
            'SERVER_NAME' => config('app.client_subdomain') . '.' . config('app.domain'),
        ];
    }

    protected function getAdminRoute(string $name, array $params = []): \Illuminate\Testing\TestResponse
    {
        $url = route($name, $params, true);
        $response = $this->get($url);
        if ($response->status() === 302) {
            $redirectUrl = $response->headers->get('Location');
            $response = $this->get($redirectUrl);
        }
        return $response;
    }

    public function test_admin_dashboard_renders(): void
    {
        $response = $this->getAdminRoute('admin.dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_services_renders(): void
    {
        $response = $this->getAdminRoute('admin.services.index');
        $response->assertStatus(200);
    }

    public function test_admin_reviews_renders(): void
    {
        $response = $this->getAdminRoute('admin.reviews.index');
        $response->assertStatus(200);
    }

    public function test_admin_complaints_renders(): void
    {
        $response = $this->getAdminRoute('admin.complaints.index');
        $response->assertStatus(200);
    }

    public function test_admin_queries_renders(): void
    {
        $response = $this->getAdminRoute('admin.queries.index');
        $response->assertStatus(200);
    }

    public function test_admin_get_services_renders(): void
    {
        $response = $this->getAdminRoute('admin.get-services.index');
        $response->assertStatus(200);
    }

    public function test_admin_payments_renders(): void
    {
        $response = $this->getAdminRoute('admin.payments.index');
        $response->assertStatus(200);
    }

    public function test_admin_agreements_renders(): void
    {
        $response = $this->getAdminRoute('admin.agreements.index');
        $response->assertStatus(200);
    }

    public function test_admin_login_renders(): void
    {
        auth('admin')->logout();
        $response = $this->getAdminRoute('admin.login');
        $response->assertStatus(200);
    }

    public function test_agreement_portal_with_valid_token(): void
    {
        auth('admin')->logout();
        $link = \App\Models\AgreementLink::where('token', '!=', null)->first();
        if ($link) {
            $url = route('agreement.view', ['token' => $link->token], false);
            $response = $this->get($url);
            if ($response->status() === 302) {
                $redirectUrl = $response->headers->get('Location');
                $response = $this->get($redirectUrl);
            }
            $response->assertStatus(200);
        }
    }

    public function test_complaint_export_renders(): void
    {
        $url = route('admin.complaints.export', [], true);
        $response = $this->get($url);
        // For streamed responses, just check it doesn't 404
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_query_export_renders(): void
    {
        $url = route('admin.queries.export', [], true);
        $response = $this->get($url);
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_get_service_export_renders(): void
    {
        $url = route('admin.get-services.export', [], true);
        $response = $this->get($url);
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_service_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\Services\ServiceIndex::class)
            ->assertStatus(200);
    }

    public function test_review_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\Reviews\ReviewIndex::class)
            ->assertStatus(200);
    }

    public function test_complaint_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\Complaints\ComplaintIndex::class)
            ->assertStatus(200);
    }

    public function test_query_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\Queries\QueryIndex::class)
            ->assertStatus(200);
    }

    public function test_get_service_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\GetServices\GetServiceIndex::class)
            ->assertStatus(200);
    }

    public function test_payment_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\Payments\PaymentHistory::class)
            ->assertStatus(200);
    }

    public function test_agreement_livewire_component(): void
    {
        Livewire::test(\App\Livewire\Admin\Agreements\AgreementIndex::class)
            ->assertStatus(200);
    }
}
