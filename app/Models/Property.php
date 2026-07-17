<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Property extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Searchable, InteractsWithMedia;

    protected $fillable = [
        'wp_property_id',
        'agent_id',
        'city_id',
        'state_id',
        'property_type_id',
        'property_status_id',
        'title',
        'slug',
        'description',
        'price',
        'bedrooms',
        'bathrooms',
        'area',
        'facing',
        'ownership',
        'furnishing',
        'address',
        'latitude',
        'longitude',
        'featured_image',
        'views',
        'featured',
        'published',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'area' => 'integer',
        'views' => 'integer',
        'featured' => 'boolean',
        'published' => 'boolean',
        'published_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Spatie Media Library conversions
    public function registerMediaConversions(Media $media = null): void
    {
        if (app()->runningInConsole() && str_contains(implode(' ', $_SERVER['argv'] ?? []), 'import:')) {
            return;
        }

        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('gallery', 'featured');

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(800)
            ->performOnCollections('gallery', 'featured');
    }

    // Searchable array for Laravel Scout / Meilisearch
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => (float) $this->price,
            'bedrooms' => (int) $this->bedrooms,
            'bathrooms' => (int) $this->bathrooms,
            'area' => (int) $this->area,
            'facing' => $this->facing,
            'ownership' => $this->ownership,
            'furnishing' => $this->furnishing,
            'address' => $this->address,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'featured' => (bool) $this->featured,
            'published' => (bool) $this->published,
            'state' => $this->state?->name,
            'city' => $this->city?->name,
            'type' => $this->type?->name,
            'status' => $this->status?->name,
            'amenities' => $this->amenities->pluck('name')->toArray(),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->published;
    }

    // Relationships
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function status()
    {
        return $this->belongsTo(PropertyStatus::class, 'property_status_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

    public function floorPlans()
    {
        return $this->hasMany(PropertyFloorPlan::class, 'property_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function inquiries()
    {
        return $this->hasMany(PropertyInquiry::class, 'property_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'property_id');
    }

    public function customFieldValues()
    {
        return $this->hasMany(PropertyCustomFieldValue::class, 'property_id');
    }

    public function getCustomFieldValue(string $slug)
    {
        $valueRecord = $this->customFieldValues()
            ->whereHas('customField', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first();

        return $valueRecord ? $valueRecord->value : null;
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Scope a query to filter by custom field values.
     */
    public function scopeWithCustomFieldFilters($query, array $filters)
    {
        if (empty($filters)) {
            return $query;
        }

        foreach ($filters as $slug => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $query->whereHas('customFieldValues', function ($q) use ($slug, $value) {
                $q->whereHas('customField', function ($sq) use ($slug) {
                    $sq->where('slug', $slug);
                });

                if (is_array($value)) {
                    $q->where(function ($sub) use ($value) {
                        foreach ($value as $val) {
                            $sub->orWhere('value', $val)
                                ->orWhere('value', 'like', '%' . json_encode($val) . '%');
                        }
                    });
                } else {
                    $q->where(function ($sub) use ($value) {
                        $sub->where('value', $value)
                            ->orWhere('value', 'like', '%' . json_encode($value) . '%');
                    });
                }
            });
        }

        return $query;
    }
}
