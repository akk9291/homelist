<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateApiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'cities' => $this->cities->map(fn ($city) => [
                'id' => $city->id,
                'name' => $city->name,
                'slug' => $city->slug,
            ]),
            'properties_count' => $this->properties()->count(),
        ];
    }
}
