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
use App\Services\CustomFieldService;
use App\Livewire\Agent\AgentDashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomFieldBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $agentUser;
    protected PropertyType $propertyType;
    protected City $city;
    protected State $state;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->adminUser->assignRole('admin');

        $this->agentUser = User::factory()->create(['role' => 'agent']);
        $this->agentUser->assignRole('agent');

        $this->state = State::create(['name' => 'Madhya Pradesh', 'slug' => 'madhya-pradesh']);
        $this->city = City::create(['state_id' => $this->state->id, 'name' => 'Indore', 'slug' => 'indore']);
        $this->propertyType = PropertyType::create(['name' => 'Residential Villa', 'slug' => 'residential-villa']);
    }

    /** @test */
    public function admins_can_create_custom_field_groups_and_fields()
    {
        $group = CustomFieldGroup::create([
            'name' => 'Construction details',
            'slug' => 'construction-details',
        ]);

        $field = CustomField::create([
            'group_id' => $group->id,
            'name' => 'Ceiling Height',
            'slug' => 'ceiling-height',
            'field_type' => 'number',
            'required' => true,
        ]);

        $this->assertDatabaseHas('custom_field_groups', ['slug' => 'construction-details']);
        $this->assertDatabaseHas('custom_fields', ['slug' => 'ceiling-height', 'group_id' => $group->id]);
    }

    /** @test */
    public function service_resolves_assignments_and_returns_assigned_fields_only()
    {
        $group = CustomFieldGroup::create([
            'name' => 'Specifications',
            'slug' => 'specifications',
        ]);

        // 1. Global field (no assignments)
        $globalField = CustomField::create([
            'group_id' => $group->id,
            'name' => 'Global Note',
            'slug' => 'global-note',
            'field_type' => 'text',
        ]);

        // 2. Assigned field (only for Residential Villa)
        $assignedField = CustomField::create([
            'group_id' => $group->id,
            'name' => 'Modular Kitchen Brand',
            'slug' => 'modular-kitchen-brand',
            'field_type' => 'text',
        ]);

        $assignedField->assignments()->create([
            'property_type_id' => $this->propertyType->id,
        ]);

        $service = app(CustomFieldService::class);

        // Fetching fields for our property type
        $fieldsForType = $service->getAssignedFieldsFlat($this->propertyType->id);
        $this->assertCount(2, $fieldsForType);

        // Fetching fields for another property type
        $otherType = PropertyType::create(['name' => 'Agricultural Land', 'slug' => 'agricultural-land']);
        $fieldsForOther = $service->getAssignedFieldsFlat($otherType->id);
        
        // Should only return global field, not the residential villa specific one
        $this->assertCount(1, $fieldsForOther);
        $this->assertEquals('global-note', $fieldsForOther->first()->slug);
    }

    /** @test */
    public function service_exports_and_imports_json_schemas_successfully()
    {
        $group = CustomFieldGroup::create([
            'name' => 'Pricing details',
            'slug' => 'pricing-details',
        ]);

        CustomField::create([
            'group_id' => $group->id,
            'name' => 'Maintenance Charges',
            'slug' => 'maintenance-charges',
            'field_type' => 'number',
        ]);

        $service = app(CustomFieldService::class);
        $json = $service->exportToJson();

        $this->assertStringContainsString('pricing-details', $json);
        $this->assertStringContainsString('maintenance-charges', $json);

        // Clear tables
        PropertyCustomFieldValue::query()->delete();
        CustomField::query()->delete();
        CustomFieldGroup::query()->delete();

        // Import back
        $count = $service->importFromJson($json);
        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('custom_field_groups', ['slug' => 'pricing-details']);
        $this->assertDatabaseHas('custom_fields', ['slug' => 'maintenance-charges']);
    }

    /** @test */
    public function dynamic_form_renders_and_validates_custom_fields_in_agent_dashboard()
    {
        $group = CustomFieldGroup::create(['name' => 'Details', 'slug' => 'details']);
        
        // Required custom field
        CustomField::create([
            'group_id' => $group->id,
            'name' => 'Year Built',
            'slug' => 'year-built',
            'field_type' => 'number',
            'required' => true,
        ]);

        $status = \App\Models\PropertyStatus::create(['name' => 'For Sale', 'slug' => 'for-sale']);

        Livewire::actingAs($this->agentUser)
            ->test(AgentDashboard::class)
            ->call('initAddProperty')
            ->set('propTitle', 'Luxury Villa in Indore')
            ->set('propDescription', 'Amazing villa with premium spacing.')
            ->set('propPrice', 15000000)
            ->set('propBedrooms', 4)
            ->set('propBathrooms', 4)
            ->set('propArea', 4500)
            ->set('propAddress', 'Vijay Nagar, Indore')
            ->set('propCityId', $this->city->id)
            ->set('propStateId', $this->state->id)
            ->set('propPropertyTypeId', $this->propertyType->id)
            ->set('propPropertyStatusId', $status->id)
            // Leave year-built empty (it is required)
            ->call('saveProperty')
            ->assertHasErrors(['customFieldValues.year-built' => 'required']);
    }

    /** @test */
    public function dynamic_custom_fields_can_be_saved_and_searched()
    {
        $group = CustomFieldGroup::create(['name' => 'Details', 'slug' => 'details']);
        $field = CustomField::create([
            'group_id' => $group->id,
            'name' => 'Carpet Area',
            'slug' => 'carpet-area',
            'field_type' => 'number',
            'searchable' => true,
        ]);

        $status = \App\Models\PropertyStatus::create(['name' => 'For Sale', 'slug' => 'for-sale']);

        $property = Property::create([
            'agent_id' => $this->agentUser->id,
            'title' => 'Indore Penthouse',
            'slug' => 'indore-penthouse',
            'description' => 'Beautiful penthouse',
            'price' => 20000000,
            'bedrooms' => 3,
            'bathrooms' => 3,
            'area' => 3000,
            'address' => 'Palasia, Indore',
            'city_id' => $this->city->id,
            'state_id' => $this->state->id,
            'property_type_id' => $this->propertyType->id,
            'property_status_id' => $status->id,
            'published' => true,
            'published_at' => now(),
        ]);

        // Save value
        $service = app(CustomFieldService::class);
        $service->saveValues($property->id, ['carpet-area' => '2600']);

        $this->assertDatabaseHas('property_custom_field_values', [
            'property_id' => $property->id,
            'custom_field_id' => $field->id,
            'value' => '2600',
        ]);

        // Test searching via controller with custom field filter
        $response = $this->get('/search?custom_fields[carpet-area]=2600');
        $response->assertStatus(200);
        $response->assertSee('Indore Penthouse');

        // Test searching with non-matching custom field filter
        $responseNonMatching = $this->get('/search?custom_fields[carpet-area]=9999');
        $responseNonMatching->assertStatus(200);
        $responseNonMatching->assertDontSee('Indore Penthouse');
    }
}
