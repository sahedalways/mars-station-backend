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
    }

    public function test_admin_dashboard_renders(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    public function test_admin_services_renders(): void
    {
        $response = $this->get('/admin/services');
        $response->assertStatus(200);
    }

    public function test_admin_reviews_renders(): void
    {
        $response = $this->get('/admin/reviews');
        $response->assertStatus(200);
    }

    public function test_admin_complaints_renders(): void
    {
        $response = $this->get('/admin/complaints');
        $response->assertStatus(200);
    }

    public function test_admin_queries_renders(): void
    {
        $response = $this->get('/admin/queries');
        $response->assertStatus(200);
    }

    public function test_admin_get_services_renders(): void
    {
        $response = $this->get('/admin/get-services');
        $response->assertStatus(200);
    }

    public function test_admin_payments_renders(): void
    {
        $response = $this->get('/admin/payments');
        $response->assertStatus(200);
    }

    public function test_admin_agreements_renders(): void
    {
        $response = $this->get('/admin/agreements');
        $response->assertStatus(200);
    }

    public function test_admin_login_renders(): void
    {
        auth('admin')->logout();
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_agreement_portal_with_valid_token(): void
    {
        auth('admin')->logout();
        $link = \App\Models\AgreementLink::where('token', '!=', null)->first();
        if ($link) {
            $response = $this->get('/agreement/' . $link->token);
            $response->assertStatus(200);
        }
    }

    public function test_complaint_export_renders(): void
    {
        $response = $this->get('/admin/complaints/export');
        $response->assertStatus(200);
    }

    public function test_query_export_renders(): void
    {
        $response = $this->get('/admin/queries/export');
        $response->assertStatus(200);
    }

    public function test_get_service_export_renders(): void
    {
        $response = $this->get('/admin/get-services/export');
        $response->assertStatus(200);
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
