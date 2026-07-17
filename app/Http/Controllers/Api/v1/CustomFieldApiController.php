<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\CustomField;
use App\Services\CustomFieldService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomFieldApiController extends Controller
{
    protected CustomFieldService $service;

    public function __construct(CustomFieldService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/v1/custom-fields
     * List all available or filtered custom field schemas.
     */
    public function index(Request $request): JsonResponse
    {
        $fields = $this->service->getAssignedFieldsFlat(
            $request->integer('property_type_id') ?: null,
            $request->integer('category_id') ?: null,
            $request->integer('city_id') ?: null,
            $request->integer('state_id') ?: null,
            $request->integer('subscription_plan_id') ?: null
        );

        return response()->json([
            'status' => 'success',
            'data' => $fields
        ]);
    }

    /**
     * GET /api/v1/property-custom-fields
     * Retrieve custom fields schema assigned to a specific property.
     */
    public function propertyFields(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::findOrFail($request->integer('property_id'));

        $fields = $this->service->getAssignedFieldsFlat(
            $property->property_type_id,
            null,
            $property->city_id,
            $property->state_id
        );

        return response()->json([
            'status' => 'success',
            'data' => $fields
        ]);
    }

    /**
     * GET/POST /api/v1/custom-field-values
     * Get or Save property custom field values.
     */
    public function values(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            return $this->saveValues($request);
        }

        return $this->getValues($request);
    }

    protected function getValues(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::findOrFail($request->integer('property_id'));
        $valuesList = $property->customFieldValues()->with('customField')->get();
        $formatted = $this->service->parseValuesForFrontend($valuesList);

        return response()->json([
            'status' => 'success',
            'property_id' => $property->id,
            'values' => $formatted
        ]);
    }

    protected function saveValues(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'values' => 'required|array',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        // Authenticated check
        $user = $request->user();
        if ($user && $property->agent_id !== $user->id && !$user->hasAnyRole(['admin', 'Super Admin'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $this->service->saveValues($property->id, $validated['values']);

        return response()->json([
            'status' => 'success',
            'message' => 'Custom field values saved successfully.'
        ]);
    }
}
