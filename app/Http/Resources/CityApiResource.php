<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityApiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'state' => [
                'id' => $this->state?->id,
                'name' => $this->state?->name,
                'slug' => $this->state?->slug,
            ],
            'properties_count' => $this->properties()->count(),
        ];
    }
}
