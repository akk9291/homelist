<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\State;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /** @test */
    public function it_can_perform_eloquent_fallback_search_without_keyword()
    {
        // Get some properties from the DB
        $property = Property::where('published', true)
            ->whereNotNull('city_id')
            ->whereNotNull('property_type_id')
            ->first();

        $this->assertNotNull($property, "No properties found for fallback search test.");

        // Query by state, city, and type
        $response = $this->get(route('properties.search', [
            'state_id' => $property->state_id,
            'city_id' => $property->city_id,
            'type_id' => $property->property_type_id,
        ]));

        $response->assertStatus(200)
            ->assertViewIs('properties.index')
            ->assertViewHas('properties');
            
        $properties = $response->viewData('properties');
        $this->assertGreaterThan(0, $properties->count());
        $this->assertEquals($property->city_id, $properties->first()->city_id);
    }

    /** @test */
    public function it_can_perform_scout_search_with_keyword()
    {
        // Perform search using keyword
        $response = $this->get(route('properties.search', [
            'keyword' => 'duplex'
        ]));

        $response->assertStatus(200)
            ->assertViewIs('properties.index')
            ->assertViewHas('properties');
    }
}
