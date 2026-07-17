<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\State;
use App\Models\User;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /** @test */
    public function it_can_fetch_properties_list_via_api()
    {
        $response = $this->getJson('/api/v1/properties');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'price',
                        'bedrooms',
                        'bathrooms',
                        'area',
                        'address',
                        'type',
                        'status',
                        'city',
                        'state',
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_can_fetch_single_property_by_slug_via_api()
    {
        $property = Property::where('published', true)->first();
        $this->assertNotNull($property, "No active properties in database for API testing.");

        $response = $this->getJson("/api/v1/properties/{$property->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', $property->slug)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'slug',
                    'description',
                    'price',
                    'bedrooms',
                    'bathrooms',
                    'area',
                    'facing',
                    'ownership',
                    'furnishing',
                    'address',
                    'latitude',
                    'longitude',
                    'images',
                    'type',
                    'status',
                    'city',
                    'state',
                    'agent',
                    'amenities',
                    'floor_plans',
                ]
            ]);
    }

    /** @test */
    public function it_can_search_properties_via_api()
    {
        $response = $this->getJson('/api/v1/search?keyword=duplex');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'price',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_fetch_locations_via_api()
    {
        $response = $this->getJson('/api/v1/states');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                    ]
                ]
            ]);

        $response = $this->getJson('/api/v1/cities');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'state',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_fetch_property_types_via_api()
    {
        $response = $this->getJson('/api/v1/property-types');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_fetch_agents_via_api()
    {
        $response = $this->getJson('/api/v1/agents');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'photo',
                        'about',
                    ]
                ]
            ]);
    }
}
