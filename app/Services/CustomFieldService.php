<?php

namespace App\Services;

use App\Models\CustomField;
use App\Models\CustomFieldGroup;
use App\Models\CustomFieldAssignment;
use App\Repositories\Contracts\CustomFieldRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomFieldService
{
    public function __construct(
        protected CustomFieldRepositoryInterface $repository
    ) {}

    /**
     * Get assigned custom fields grouped by their parent layout group.
     */
    public function getAssignedFieldsGrouped(
        ?int $propertyTypeId,
        ?int $categoryId = null,
        ?int $cityId = null,
        ?int $stateId = null,
        ?int $subscriptionPlanId = null
    ): Collection {
        $fields = $this->repository->getAssignedFields(
            $propertyTypeId,
            $categoryId,
            $cityId,
            $stateId,
            $subscriptionPlanId
        );

        // Group by Custom Field Group
        return $fields->groupBy(function ($field) {
            return $field->group ? $field->group->slug : 'general';
        });
    }

    /**
     * Get all assigned custom fields in a flat list.
     */
    public function getAssignedFieldsFlat(
        ?int $propertyTypeId,
        ?int $categoryId = null,
        ?int $cityId = null,
        ?int $stateId = null,
        ?int $subscriptionPlanId = null
    ): Collection {
        return $this->repository->getAssignedFields(
            $propertyTypeId,
            $categoryId,
            $cityId,
            $stateId,
            $subscriptionPlanId
        );
    }

    /**
     * Compile dynamic validation rules for dynamic custom fields.
     */
    public function compileValidationRules(Collection $fields): array
    {
        $rules = [];
        $attributes = [];

        foreach ($fields as $field) {
            $fieldRules = [];

            if ($field->required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Map field types to basic validation helpers
            switch ($field->field_type) {
                case 'number':
                case 'currency':
                case 'area_unit':
                    $fieldRules[] = 'numeric';
                    break;
                case 'decimal':
                    $fieldRules[] = 'numeric';
                    break;
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'url':
                    $fieldRules[] = 'url';
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
            }

            // Append custom validation rule strings if specified
            if (!empty($field->validation_rules)) {
                $customRules = explode('|', $field->validation_rules);
                $fieldRules = array_merge($fieldRules, $customRules);
            }

            $rules['customFieldValues.' . $field->slug] = $fieldRules;
            $attributes['customFieldValues.' . $field->slug] = $field->name;
        }

        return [
            'rules' => $rules,
            'attributes' => $attributes,
        ];
    }

    /**
     * Parse raw property custom values from database. Decodes arrays like multiselect/checkbox.
     */
    public function parseValuesForFrontend(Collection $valueRecords): array
    {
        $values = [];

        foreach ($valueRecords as $record) {
            $field = $record->customField;
            if (!$field) {
                continue;
            }

            $val = $record->value;

            // Check if field is multi-valued and needs JSON decoding
            if (in_array($field->field_type, ['multiselect', 'checkbox', 'multiple_image_upload', 'multiple_file_upload']) && !empty($val)) {
                $decoded = json_decode($val, true);
                $val = is_array($decoded) ? $decoded : [$val];
            }

            $values[$field->slug] = $val;
        }

        return $values;
    }

    /**
     * Save property custom values.
     */
    public function saveValues(int $propertyId, array $values): void
    {
        $this->repository->savePropertyValues($propertyId, $values);
    }

    // --- JSON Import/Export ---

    /**
     * Export all custom fields and groups to a JSON string.
     */
    public function exportToJson(): string
    {
        $groups = CustomFieldGroup::with('customFields.assignments')->get();
        return $groups->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Import custom fields and groups from a JSON string.
     */
    public function importFromJson(string $json): int
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new \InvalidArgumentException("Invalid JSON schema.");
        }

        $importedCount = 0;

        foreach ($data as $groupData) {
            $group = CustomFieldGroup::updateOrCreate(
                ['slug' => $groupData['slug']],
                [
                    'name' => $groupData['name'],
                    'description' => $groupData['description'] ?? null,
                    'sort_order' => $groupData['sort_order'] ?? 0,
                    'status' => $groupData['status'] ?? 'active',
                ]
            );

            if (isset($groupData['custom_fields']) && is_array($groupData['custom_fields'])) {
                foreach ($groupData['custom_fields'] as $fieldData) {
                    $field = CustomField::updateOrCreate(
                        ['slug' => $fieldData['slug']],
                        [
                            'group_id' => $group->id,
                            'name' => $fieldData['name'],
                            'field_type' => $fieldData['field_type'],
                            'placeholder' => $fieldData['placeholder'] ?? null,
                            'default_value' => $fieldData['default_value'] ?? null,
                            'validation_rules' => $fieldData['validation_rules'] ?? null,
                            'options_json' => $fieldData['options_json'] ?? null,
                            'conditional_logic' => $fieldData['conditional_logic'] ?? null,
                            'width' => $fieldData['width'] ?? 100,
                            'required' => $fieldData['required'] ?? false,
                            'searchable' => $fieldData['searchable'] ?? false,
                            'sortable' => $fieldData['sortable'] ?? false,
                            'filterable' => $fieldData['filterable'] ?? false,
                            'exportable' => $fieldData['exportable'] ?? true,
                            'is_repeatable' => $fieldData['is_repeatable'] ?? false,
                            'sort_order' => $fieldData['sort_order'] ?? 0,
                            'status' => $fieldData['status'] ?? 'active',
                        ]
                    );

                    // Sync assignments if present
                    if (isset($fieldData['assignments']) && is_array($fieldData['assignments'])) {
                        $field->assignments()->delete();
                        foreach ($fieldData['assignments'] as $assignData) {
                            CustomFieldAssignment::create([
                                'custom_field_id' => $field->id,
                                'property_type_id' => $assignData['property_type_id'] ?? null,
                                'category_id' => $assignData['category_id'] ?? null,
                                'city_id' => $assignData['city_id'] ?? null,
                                'state_id' => $assignData['state_id'] ?? null,
                                'subscription_plan_id' => $assignData['subscription_plan_id'] ?? null,
                            ]);
                        }
                    }

                    $importedCount++;
                }
            }
        }

        return $importedCount;
    }

    // --- CSV Import/Export ---

    /**
     * Export all custom fields to CSV string.
     */
    public function exportToCsv(): string
    {
        $fields = CustomField::with('group')->get();
        $output = fopen('php://temp', 'r+');

        // CSV Header
        fputcsv($output, [
            'group_slug', 'group_name', 'field_name', 'field_slug', 'field_type', 
            'placeholder', 'default_value', 'validation_rules', 'options_json', 
            'width', 'required', 'searchable', 'sortable', 'filterable', 'is_repeatable'
        ]);

        foreach ($fields as $field) {
            fputcsv($output, [
                $field->group?->slug ?? 'general',
                $field->group?->name ?? 'General',
                $field->name,
                $field->slug,
                $field->field_type,
                $field->placeholder,
                $field->default_value,
                $field->validation_rules,
                $field->options_json ? json_encode($field->options_json) : null,
                $field->width,
                $field->required ? 1 : 0,
                $field->searchable ? 1 : 0,
                $field->sortable ? 1 : 0,
                $field->filterable ? 1 : 0,
                $field->is_repeatable ? 1 : 0,
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Import custom fields and groups from CSV content.
     */
    public function importFromCsv(string $csvContent): int
    {
        $lines = explode("\n", str_replace("\r", "", $csvContent));
        $importedCount = 0;
        $header = null;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $row = str_getcsv($line);
            if (!$header) {
                $header = $row;
                continue;
            }

            $data = array_combine($header, $row);

            // 1. Create or Find Group
            $groupSlug = Str::slug($data['group_name'] ?? 'general');
            $group = CustomFieldGroup::updateOrCreate(
                ['slug' => $groupSlug],
                [
                    'name' => $data['group_name'] ?? 'General',
                    'status' => 'active',
                ]
            );

            // 2. Parse Options
            $options = null;
            if (!empty($data['options_json'])) {
                $options = json_decode($data['options_json'], true);
            }

            // 3. Create Custom Field
            CustomField::updateOrCreate(
                ['slug' => $data['field_slug']],
                [
                    'group_id' => $group->id,
                    'name' => $data['field_name'],
                    'field_type' => $data['field_type'],
                    'placeholder' => $data['placeholder'] ?: null,
                    'default_value' => $data['default_value'] ?: null,
                    'validation_rules' => $data['validation_rules'] ?: null,
                    'options_json' => $options,
                    'width' => (int) ($data['width'] ?? 100),
                    'required' => (bool) ($data['required'] ?? false),
                    'searchable' => (bool) ($data['searchable'] ?? false),
                    'sortable' => (bool) ($data['sortable'] ?? false),
                    'filterable' => (bool) ($data['filterable'] ?? false),
                    'is_repeatable' => (bool) ($data['is_repeatable'] ?? false),
                    'status' => 'active',
                ]
            );

            $importedCount++;
        }

        return $importedCount;
    }
}
