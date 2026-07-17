<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    public function __construct(Property $model)
    {
        parent::__construct($model);
    }

    /**
     * Get featured properties with Cache.
     */
    public function getFeatured(int $limit = 5): Collection
    {
        return Cache::remember("properties.featured.limit.{$limit}", 3600, function () use ($limit) {
            return $this->model
                ->where('is_featured', true)
                ->where('status', 'published')
                ->with(['type', 'status', 'city', 'state'])
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Search properties based on filters.
     */
    public function searchProperties(array $filters): Collection
    {
        $query = $this->model->newQuery()->where('status', 'published');

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['property_type_id'])) {
            $query->where('property_type_id', $filters['property_type_id']);
        }

        if (isset($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['bedrooms'])) {
            $query->where('bedrooms', $filters['bedrooms']);
        }

        return $query->with(['type', 'status', 'city', 'state'])->get();
    }
}
