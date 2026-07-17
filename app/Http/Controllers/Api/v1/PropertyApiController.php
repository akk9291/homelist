<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyApiResource;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PropertyApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $properties = Property::where('published', true)
            ->with(['type', 'status', 'city', 'state', 'agent.agent', 'amenities', 'floorPlans'])
            ->latest('published_at')
            ->paginate($request->input('per_page', 15));

        return PropertyApiResource::collection($properties);
    }

    public function show(string $slug): PropertyApiResource
    {
        $property = Property::where('slug', $slug)
            ->where('published', true)
            ->with(['type', 'status', 'city', 'state', 'agent.agent', 'amenities', 'floorPlans'])
            ->firstOrFail();

        // Increment views count safely
        $property->increment('views');

        return new PropertyApiResource($property);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $keyword = $request->input('keyword');
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $typeId = $request->input('type_id');
        $statusId = $request->input('status_id');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $bedrooms = $request->input('bedrooms');
        $bathrooms = $request->input('bathrooms');
        $amenities = $request->input('amenities'); // array

        // If there's a keyword search, use Laravel Scout
        if (!empty($keyword)) {
            $scoutQuery = Property::search($keyword);
            
            // Perform Scout filters (where/whereIn supported by Meilisearch)
            if ($typeId) $scoutQuery->where('property_type_id', (int) $typeId);
            if ($statusId) $scoutQuery->where('property_status_id', (int) $statusId);
            if ($cityId) $scoutQuery->where('city_id', (int) $cityId);
            if ($stateId) $scoutQuery->where('state_id', (int) $stateId);
            
            $properties = $scoutQuery->query(function ($builder) use ($priceMin, $priceMax, $bedrooms, $bathrooms, $amenities, $request) {
                // Apply remaining advanced filters on the database query side of Scout results
                $builder->where('published', true)
                    ->with(['type', 'status', 'city', 'state', 'agent.agent', 'amenities', 'floorPlans'])
                    ->withCustomFieldFilters($request->input('custom_fields', []));

                if ($priceMin) $builder->where('price', '>=', $priceMin);
                if ($priceMax) $builder->where('price', '<=', $priceMax);
                if ($bedrooms) $builder->where('bedrooms', $bedrooms);
                if ($bathrooms) $builder->where('bathrooms', $bathrooms);
                if (!empty($amenities) && is_array($amenities)) {
                    foreach ($amenities as $amenityId) {
                        $builder->whereHas('amenities', function ($q) use ($amenityId) {
                            $q->where('amenities.id', $amenityId);
                        });
                    }
                }
            })->paginate($request->input('per_page', 15));
        } else {
            // Standard Eloquent search
            $builder = Property::where('published', true)
                ->with(['type', 'status', 'city', 'state', 'agent.agent', 'amenities', 'floorPlans'])
                ->withCustomFieldFilters($request->input('custom_fields', []));

            if ($stateId) $builder->where('state_id', $stateId);
            if ($cityId) $builder->where('city_id', $cityId);
            if ($typeId) $builder->where('property_type_id', $typeId);
            if ($statusId) $builder->where('property_status_id', $statusId);
            
            if ($priceMin) $builder->where('price', '>=', $priceMin);
            if ($priceMax) $builder->where('price', '<=', $priceMax);
            if ($bedrooms) $builder->where('bedrooms', $bedrooms);
            if ($bathrooms) $builder->where('bathrooms', $bathrooms);

            if (!empty($amenities) && is_array($amenities)) {
                foreach ($amenities as $amenityId) {
                    $builder->whereHas('amenities', function ($q) use ($amenityId) {
                        $q->where('amenities.id', $amenityId);
                    });
                }
            }

            $properties = $builder->latest('published_at')->paginate($request->input('per_page', 15));
        }

        return PropertyApiResource::collection($properties);
    }
}
