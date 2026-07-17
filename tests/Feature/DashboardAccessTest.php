<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $agent;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
        $agentRole = Role::firstOrCreate(['name' => 'Agent']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Create Customer
        $this->customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);
        $this->customer->assignRole($customerRole);
        $this->customer->markEmailAsVerified();

        // Create Agent
        $this->agent = User::create([
            'name' => 'Agent User',
            'email' => 'agent@example.com',
            'password' => bcrypt('password123'),
            'role' => 'agent',
        ]);
        $this->agent->assignRole($agentRole);
        $this->agent->markEmailAsVerified();

        // Create Admin
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
        $this->admin->assignRole($adminRole);
        $this->admin->markEmailAsVerified();
    }

    /** @test */
    public function guest_cannot_access_customer_dashboard()
    {
        $response = $this->get('/account/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function guest_cannot_access_agent_dashboard()
    {
        $response = $this->get('/agent/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function customer_can_access_customer_dashboard()
    {
        $response = $this->actingAs($this->customer)->get('/account/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_cannot_access_agent_dashboard()
    {
        $response = $this->actingAs($this->customer)->get('/agent/dashboard');
        $response->assertRedirect(route('customer.dashboard'));
    }

    /** @test */
    public function agent_can_access_agent_dashboard()
    {
        $response = $this->actingAs($this->agent)->get('/agent/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function agent_cannot_access_customer_dashboard()
    {
        $response = $this->actingAs($this->agent)->get('/account/dashboard');
        $response->assertRedirect(route('agent.dashboard'));
    }

    /** @test */
    public function customer_and_agent_cannot_access_filament_admin_panel()
    {
        // Filament admin login URL is typically /admin/login or check panel access
        // Directly testing the User model implementation of canAccessPanel
        $adminPanel = \Filament\Facades\Filament::getPanel('admin');
        
        $this->assertFalse($this->customer->canAccessPanel($adminPanel));
        $this->assertFalse($this->agent->canAccessPanel($adminPanel));
        $this->assertTrue($this->admin->canAccessPanel($adminPanel));
    }

    /** @test */
    public function authenticated_users_are_redirected_to_their_dashboards_from_generic_dashboard_route()
    {
        $response = $this->actingAs($this->customer)->get('/dashboard');
        $response->assertRedirect(route('customer.dashboard'));

        $response = $this->actingAs($this->agent)->get('/dashboard');
        $response->assertRedirect(route('agent.dashboard'));

        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertRedirect('/admin');
    }
}
