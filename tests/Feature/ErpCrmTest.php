<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErpCrmTest extends TestCase
{
    /** @test */
    public function it_can_register_and_login_a_user_via_api()
    {
        $email = 'testuser_' . time() . '@example.com';

        // 1. Test registration
        $regResponse = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => $email,
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'customer',
        ]);

        $regResponse->assertStatus(201)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => ['id', 'name', 'email']
            ]);

        // 2. Test login
        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => $email,
            'password' => 'Password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => ['id', 'name', 'email']
            ]);
    }

    /** @test */
    public function it_can_access_authenticated_profile_endpoint()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/user/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email']
            ]);
    }

    /** @test */
    public function it_can_subscribe_to_a_plan_via_api()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $plan = SubscriptionPlan::first() ?: SubscriptionPlan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan',
            'price' => 500.00,
            'billing_period' => 'monthly',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/subscriptions/subscribe', [
            'subscription_plan_id' => $plan->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'subscription' => ['id', 'user_id', 'subscription_plan_id', 'status']
            ]);
    }

    /** @test */
    public function it_can_toggle_property_wishlist()
    {
        $user = User::factory()->create();
        $property = \App\Models\Property::first() ?: \App\Models\Property::factory()->create();

        $response = $this->actingAs($user)->get("/wishlist/toggle/{$property->id}");

        $response->assertRedirect();
        $this->assertTrue($user->favoriteProperties()->where('property_id', $property->id)->exists());

        // Toggle again to remove
        $response2 = $this->actingAs($user)->get("/wishlist/toggle/{$property->id}");
        $response2->assertRedirect();
        $this->assertFalse($user->favoriteProperties()->where('property_id', $property->id)->exists());
    }

    /** @test */
    public function it_can_add_property_to_compare_session()
    {
        $property = \App\Models\Property::first() ?: \App\Models\Property::factory()->create();

        $response = $this->get("/compare/add/{$property->id}");

        $response->assertRedirect(route('compare'));
        $response->assertSessionHas('compare_list', [$property->id]);
    }
}
