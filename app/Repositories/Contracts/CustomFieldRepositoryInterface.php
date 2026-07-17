<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\CustomField;

interface CustomFieldRepositoryInterface
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
    ): Collection;

    /**
     * Get all values for a property.
     */
    public function getPropertyValues(int $propertyId): Collection;

    /**
     * Save/update property custom field values.
     */
    public function savePropertyValues(int $propertyId, array $values): void;

    /**
     * Find a custom field by slug.
     */
    public function findFieldBySlug(string $slug): ?CustomField;

    /**
     * Get all custom field groups.
     */
    public function getAllGroups(): Collection;
}
