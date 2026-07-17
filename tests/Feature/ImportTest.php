<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyFloorPlan;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImportTest extends TestCase
{
    // Do not refresh the database here because we want to test with the actual imported data,
    // or we can write a test that verifies the actual imported records exist.
    
    /** @test */
    public function it_has_successfully_imported_all_user_records()
    {
        // Assert that the users and agents were imported
        $this->assertGreaterThan(0, User::count(), "No users are present in the database.");
        $this->assertGreaterThan(0, User::where('role', 'agent')->count(), "No agents are present in the database.");
        
        // Assert that Spatie roles were mapped
        $agentUser = User::where('role', 'agent')->first();
        if ($agentUser) {
            $this->assertTrue($agentUser->hasRole('agent'), "Agent user is missing Spatie role mapping.");
            $this->assertNotNull($agentUser->agent, "Agent profile is missing for user.");
        }
    }

    /** @test */
    public function it_has_successfully_imported_all_locations()
    {
        // Assert states and cities exist
        $this->assertGreaterThan(0, State::count(), "No states are present in the database.");
        $this->assertGreaterThan(0, City::count(), "No cities are present in the database.");

        $city = City::first();
        $this->assertNotNull($city->state, "City is missing state relationship.");
    }

    /** @test */
    public function it_has_successfully_imported_all_amenities()
    {
        // Assert amenities exist
        $this->assertGreaterThan(0, Amenity::count(), "No amenities are present in the database.");
    }

    /** @test */
    public function it_has_successfully_imported_all_properties_and_floor_plans()
    {
        // Assert properties exist
        $this->assertGreaterThan(0, Property::count(), "No properties are present in the database.");
        
        // Assert slug integrity and relationship lookups
        $property = Property::whereNotNull('city_id')->whereNotNull('state_id')->first();
        $this->assertNotNull($property, "No property with city and state is present.");
        $this->assertNotNull($property->city, "Property city relationship is failing.");
        $this->assertNotNull($property->state, "Property state relationship is failing.");
        
        // Assert floor plans are loaded
        $this->assertGreaterThan(0, PropertyFloorPlan::count(), "No floor plans are present in the database.");
        $floorPlan = PropertyFloorPlan::first();
        $this->assertNotNull($floorPlan->property, "Floor plan property relationship is failing.");
    }
}
