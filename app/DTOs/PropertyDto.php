<?php

namespace App\DTOs;

class PropertyDto extends BaseDto
{
    public function __construct(
        public string $title,
        public ?string $slug,
        public ?string $description,
        public string $type, // sale, rent
        public string $property_type_id,
        public string $property_status_id,
        public float $price,
        public ?float $sub_price,
        public int $bedrooms,
        public int $bathrooms,
        public float $area,
        public ?string $address,
        public ?string $city_id,
        public ?string $state_id,
        public ?string $zip_code,
        public ?string $latitude,
        public ?string $longitude,
        public ?string $video_url,
        public ?string $virtual_tour_360,
        public ?string $floor_plans,
        public ?bool $is_featured,
        public ?bool $is_slider,
        public ?string $status, // published, pending, draft, rejected, sold, rented
        public ?string $agent_id,
        public array $amenities = []
    ) {}

    /**
     * Create DTO from request array or array payload.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            type: $data['type'] ?? 'sale',
            property_type_id: $data['property_type_id'],
            property_status_id: $data['property_status_id'],
            price: (float) $data['price'],
            sub_price: isset($data['sub_price']) ? (float) $data['sub_price'] : null,
            bedrooms: (int) ($data['bedrooms'] ?? 0),
            bathrooms: (int) ($data['bathrooms'] ?? 0),
            area: (float) ($data['area'] ?? 0.00),
            address: $data['address'] ?? null,
            city_id: $data['city_id'] ?? null,
            state_id: $data['state_id'] ?? null,
            zip_code: $data['zip_code'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            video_url: $data['video_url'] ?? null,
            virtual_tour_360: $data['virtual_tour_360'] ?? null,
            floor_plans: $data['floor_plans'] ?? null,
            is_featured: (bool) ($data['is_featured'] ?? false),
            is_slider: (bool) ($data['is_slider'] ?? false),
            status: $data['status'] ?? 'pending',
            agent_id: $data['agent_id'] ?? null,
            amenities: $data['amenities'] ?? []
        );
    }
}
