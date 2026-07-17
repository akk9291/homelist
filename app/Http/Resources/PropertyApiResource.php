<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyApiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'wp_property_id' => $this->wp_property_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area' => $this->area,
            'facing' => $this->facing,
            'ownership' => $this->ownership,
            'furnishing' => $this->furnishing,
            'address' => $this->address,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'views' => (int) $this->views,
            'featured' => (bool) $this->featured,
            'published_at' => $this->published_at?->toIso8601String(),
            
            // Relationships
            'type' => $this->type ? [
                'id' => $this->type->id,
                'name' => $this->type->name,
                'slug' => $this->type->slug,
            ] : null,
            
            'status' => $this->status ? [
                'id' => $this->status->id,
                'name' => $this->status->name,
                'slug' => $this->status->slug,
            ] : null,

            'state' => $this->state ? [
                'id' => $this->state->id,
                'name' => $this->state->name,
                'slug' => $this->state->slug,
            ] : null,

            'city' => $this->city ? [
                'id' => $this->city->id,
                'name' => $this->city->name,
                'slug' => $this->city->slug,
            ] : null,

            'agent' => $this->agent ? [
                'id' => $this->agent->id,
                'name' => $this->agent->name,
                'email' => $this->agent->email,
                'phone' => $this->agent->agent?->phone,
                'photo' => $this->agent->agent?->photo ? asset('storage/' . $this->agent->agent->photo) : null,
                'about' => $this->agent->agent?->about,
            ] : null,

            'amenities' => $this->amenities->map(fn ($amenity) => [
                'id' => $amenity->id,
                'name' => $amenity->name,
                'slug' => $amenity->slug,
            ]),

            'floor_plans' => $this->floorPlans->map(fn ($plan) => [
                'name' => $plan->name,
                'price' => $plan->price ? (float) $plan->price : null,
                'size' => $plan->size,
                'bedrooms' => $plan->bedrooms,
                'bathrooms' => $plan->bathrooms,
                'image' => $plan->image ? asset('storage/' . $plan->image) : null,
                'description' => $plan->description,
            ]),

            'images' => [
                'featured' => $this->getFirstMediaUrl('featured') ?: $this->featured_image,
                'featured_thumb' => $this->getFirstMediaUrl('featured', 'thumb') ?: $this->featured_image,
                'gallery' => $this->getMedia('gallery')->map(fn ($media) => [
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'large' => $media->getUrl('large'),
                ]),
            ],

            'seo' => $this->seo ? [
                'meta_title' => $this->seo->meta_title,
                'meta_description' => $this->seo->meta_description,
                'keywords' => $this->seo->keywords,
            ] : null,
        ];
    }
}
