<?php

namespace Tests\Feature;

use App\Enums\AgreementStatus;
use App\Models\Admin;
use App\Models\Agreement;
use App\Models\AgreementMilestone;
use App\Models\AgreementVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.agreements.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_view_all_admin_pages(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $agreement = Agreement::factory()->create();
        AgreementVersion::factory()->create([
            'agreement_id' => $agreement->id,
            'version' => 1,
        ]);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.agreements.index'))->assertOk();
        $this->get(route('admin.agreements.show', $agreement))->assertOk();
        $this->get(route('admin.agreements.edit', $agreement))->assertOk();
        $this->get(route('admin.payments.index'))->assertOk();
        $this->get(route('admin.get-services.index'))->assertOk();
        $this->get(route('admin.services.index'))->assertOk();
        $this->get(route('admin.reviews.index'))->assertOk();
        $this->get(route('admin.complaints.index'))->assertOk();
        $this->get(route('admin.queries.index'))->assertOk();
    }

    public function test_agreement_index_filters_by_status(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        Agreement::factory()->create([
            'status' => AgreementStatus::Pending,
            'client_name' => 'Pending Client',
        ]);
        Agreement::factory()->create([
            'status' => AgreementStatus::Completed,
            'client_name' => 'Done Client',
        ]);

        $this->get(route('admin.agreements.index', ['status' => 'completed']))
            ->assertOk()
            ->assertSee('Done Client')
            ->assertDontSee('Pending Client');
    }

    public function test_agreement_show_displays_milestones(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $agreement = Agreement::factory()->create(['payment_type' => 'milestone']);
        AgreementVersion::factory()->create([
            'agreement_id' => $agreement->id,
            'version' => 1,
            'payment_config' => [
                'milestones' => [
                    ['title' => 'Design', 'description' => 'UI/UX', 'amount_pence' => 25000, 'order_index' => 1],
                    ['title' => 'Build', 'description' => null, 'amount_pence' => 25000, 'order_index' => 2],
                ],
            ],
        ]);
        AgreementMilestone::factory()->create([
            'agreement_id' => $agreement->id,
            'title' => 'Design',
            'amount_pence' => 25000,
            'order_index' => 1,
        ]);
        AgreementMilestone::factory()->create([
            'agreement_id' => $agreement->id,
            'title' => 'Build',
            'amount_pence' => 25000,
            'order_index' => 2,
        ]);

        $this->get(route('admin.agreements.show', $agreement))
            ->assertOk()
            ->assertSee('Design')
            ->assertSee('Build');
    }

    public function test_admin_guard_required_for_protected_routes(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'web');

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }
}
