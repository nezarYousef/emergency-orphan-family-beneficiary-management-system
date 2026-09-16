<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessAndWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_style_login_reaches_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_viewer_cannot_open_write_forms_or_audit_logs(): void
    {
        $viewer = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($viewer);

        $this->get('/families/create')->assertForbidden();
        $this->get('/beneficiaries/create')->assertForbidden();
        $this->get('/orphans/create')->assertForbidden();
        $this->get('/aid-distributions/create')->assertForbidden();
        $this->get('/audit-logs')->assertForbidden();
    }

    public function test_admin_can_open_protected_operational_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get('/beneficiaries')->assertOk();
        $this->get('/orphans')->assertOk();
        $this->get('/aid-distributions')->assertOk();
        $this->get('/audit-logs')->assertOk();
        $this->get('/reports')->assertOk();
    }

    public function test_inactive_users_cannot_sign_in(): void
    {
        $user = User::factory()->create(['email' => 'inactive@example.com', 'is_active' => false]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_viewers_can_export_records_without_write_access(): void
    {
        $viewer = User::factory()->create(['role' => 'viewer']);

        $this->actingAs($viewer)->get('/export/families')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_manage_users_but_viewer_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/users')->assertOk();

        $viewer = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($viewer)->get('/users')->assertForbidden();
    }
}
