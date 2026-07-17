<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Agent;
use App\Models\AgentWallet;
use App\Models\LoginActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Content Manager']);
        Role::firstOrCreate(['name' => 'Agent']);
        Role::firstOrCreate(['name' => 'Customer']);
    }

    /** @test */
    public function guests_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function guests_can_view_registration_page()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test */
    public function users_can_register_as_customer()
    {
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('password', 'secret123')
            ->set('password_confirmation', 'secret123')
            ->set('role', 'customer')
            ->call('register')
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'customer',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->hasRole('Customer'));
    }

    /** @test */
    public function users_can_register_as_agent()
    {
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'Jane Agent')
            ->set('email', 'jane@example.com')
            ->set('password', 'secret123')
            ->set('password_confirmation', 'secret123')
            ->set('role', 'agent')
            ->call('register')
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role' => 'agent',
        ]);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertTrue($user->hasRole('Agent'));
        $this->assertNotNull($user->agent);
        $this->assertNotNull($user->wallet);
        $this->assertEquals(0.00, $user->wallet->balance);
    }

    /** @test */
    public function users_can_login_and_activities_are_logged()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);
        $user->assignRole('Customer');

        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('customer.dashboard'));

        $this->assertDatabaseHas('login_activities', [
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
        ]);
    }

    /** @test */
    public function logins_are_rate_limited()
    {
        $user = User::create([
            'name' => 'Rate User',
            'email' => 'rate@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        // Attempt login with incorrect credentials multiple times
        $loginComponent = Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', 'rate@example.com')
            ->set('password', 'wrong-pass');

        for ($i = 0; $i < 5; $i++) {
            $loginComponent->call('login');
        }

        // The 6th time should trigger rate limiter error
        $loginComponent->call('login')
            ->assertHasErrors(['email']);
    }
}
