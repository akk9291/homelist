<?php

namespace App\Repositories\Eloquent;

use App\Models\CustomField;
use App\Models\CustomFieldGroup;
use App\Models\PropertyCustomFieldValue;
use App\Repositories\Contracts\CustomFieldRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomFieldRepository implements CustomFieldRepositoryInterface
{
    /**
     * Get all active custom fields assigned to a specific combination of criteria.
     */
    public function getAssignedFields(
        ?int $propertyTypeId,
        ?int $categoryId = null,
        ?int $cityId = null,
        ?int $stateId = null,
        ?int $subscriptionPlanId = null
    ): Collection {
        return CustomField::where('status', 'active')
            ->with('group')
            ->where(function ($query) use ($propertyTypeId, $categoryId, $cityId, $stateId, $subscriptionPlanId) {
                // If a field has no assignments, it is considered global
                $query->whereDoesntHave('assignments')
                    // Or if it matches any of the filter criteria
                    ->orWhereHas('assignments', function ($subQuery) use ($propertyTypeId, $categoryId, $cityId, $stateId, $subscriptionPlanId) {
                        $subQuery->where(function ($q) use ($propertyTypeId, $categoryId, $cityId, $stateId, $subscriptionPlanId) {
                            $q->whereNull('property_type_id');
                            if ($propertyTypeId) {
                                $q->orWhere('property_type_id', $propertyTypeId);
                            }
                            if ($categoryId) {
                                $q->orWhere('category_id', $categoryId);
                            }
                            if ($cityId) {
                                $q->orWhere('city_id', $cityId);
                            }
                            if ($stateId) {
                                $q->orWhere('state_id', $stateId);
                            }
                            if ($subscriptionPlanId) {
                                $q->orWhere('subscription_plan_id', $subscriptionPlanId);
                            }
                        });
                    });
            })
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all values for a property.
     */
    public function getPropertyValues(int $propertyId): Collection
    {
        return PropertyCustomFieldValue::where('property_id', $propertyId)
            ->with('customField')
            ->get();
    }

    /**
     * Save/update property custom field values.
     */
    public function savePropertyValues(int $propertyId, array $values): void
    {
        DB::transaction(function () use ($propertyId, $values) {
            foreach ($values as $fieldIdentifier => $value) {
                $field = null;
                if (is_numeric($fieldIdentifier)) {
                    $field = CustomField::find((int) $fieldIdentifier);
                } else {
                    $field = CustomField::where('slug', $fieldIdentifier)->first();
                }

                if (!$field) {
                    continue;
                }

                // If the value is null, delete the record or update to null
                if ($value === null || $value === '') {
                    PropertyCustomFieldValue::where('property_id', $propertyId)
                        ->where('custom_field_id', $field->id)
                        ->delete();
                    continue;
                }

                // Format arrays (multi-select / checkboxes / file lists) as JSON
                $formattedValue = is_array($value) ? json_encode($value) : (string) $value;

                PropertyCustomFieldValue::updateOrCreate(
                    [
                        'property_id' => $propertyId,
                        'custom_field_id' => $field->id,
                    ],
                    [
                        'value' => $formattedValue,
                    ]
                );
            }
        });
    }

    /**
     * Find a custom field by slug.
     */
    public function findFieldBySlug(string $slug): ?CustomField
    {
        return CustomField::where('slug', $slug)->first();
    }

    /**
     * Get all custom field groups.
     */
    public function getAllGroups(): Collection
    {
        return CustomFieldGroup::orderBy('sort_order')->get();
    }
}
