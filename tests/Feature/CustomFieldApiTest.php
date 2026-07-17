<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\City;
use App\Models\State;
use App\Models\CustomField;
use App\Models\CustomFieldGroup;
use App\Models\PropertyCustomFieldValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomFieldApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $agentUser;
    protected PropertyType $propertyType;
    protected City $city;
    protected State $state;
    protected Property $property;
    protected CustomFieldGroup $group;
    protected CustomField $field;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        
        $this->agentUser = User::factory()->create(['role' => 'agent']);
        $this->agentUser->assignRole('agent');

        $this->state = State::create(['name' => 'MP', 'slug' => 'mp']);
        $this->city = City::create(['state_id' => $this->state->id, 'name' => 'Indore', 'slug' => 'indore']);
        $this->propertyType = PropertyType::create(['name' => 'Villa', 'slug' => 'villa']);
        
        $status = \App\Models\PropertyStatus::create(['name' => 'For Sale', 'slug' => 'for-sale']);

        $this->property = Property::create([
            'agent_id' => $this->agentUser->id,
            'title' => 'Indore Villa',
            'slug' => 'indore-villa',
            'description' => 'Beautiful villa',
            'price' => 12000000,
            'bedrooms' => 3,
            'bathrooms' => 3,
            'area' => 2400,
            'address' => 'Palasia',
            'city_id' => $this->city->id,
            'state_id' => $this->state->id,
            'property_type_id' => $this->propertyType->id,
            'property_status_id' => $status->id,
            'published' => true,
        ]);

        $this->group = CustomFieldGroup::create(['name' => 'Specs', 'slug' => 'specs']);
        $this->field = CustomField::create([
            'group_id' => $this->group->id,
            'name' => 'Flooring Type',
            'slug' => 'flooring-type',
            'field_type' => 'text',
        ]);
    }

    /** @test */
    public function it_can_fetch_custom_fields_via_api()
    {
        $response = $this->getJson('/api/v1/custom-fields');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'field_type',
                ]
            ]
        ]);
        $response->assertJsonFragment(['slug' => 'flooring-type']);
    }

    /** @test */
    public function it_can_fetch_property_custom_fields_schema()
    {
        $response = $this->getJson('/api/v1/property-custom-fields?property_id=' . $this->property->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['slug' => 'flooring-type']);
    }

    /** @test */
    public function it_can_fetch_and_save_custom_field_values_via_api()
    {
        // 1. Authenticated save
        Sanctum::actingAs($this->agentUser);

        $saveResponse = $this->postJson('/api/v1/custom-field-values', [
            'property_id' => $this->property->id,
            'values' => [
                'flooring-type' => 'Italian Marble',
            ]
        ]);

        $saveResponse->assertStatus(200);
        $saveResponse->assertJsonFragment(['message' => 'Custom field values saved successfully.']);

        $this->assertDatabaseHas('property_custom_field_values', [
            'property_id' => $this->property->id,
            'value' => 'Italian Marble',
        ]);

        // 2. Read values
        $getResponse = $this->getJson('/api/v1/custom-field-values?property_id=' . $this->property->id);
        
        $getResponse->assertStatus(200);
        $getResponse->assertJsonFragment([
            'flooring-type' => 'Italian Marble'
        ]);
    }

    /** @test */
    public function guest_cannot_save_custom_field_values_via_api()
    {
        $response = $this->postJson('/api/v1/custom-field-values', [
            'property_id' => $this->property->id,
            'values' => [
                'flooring-type' => 'Italian Marble',
            ]
        ]);

        // Guests should be rejected with 401 Unauthorized
        $response->assertStatus(401);
    }
}
