<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyTypeApiResource;
use App\Models\PropertyType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PropertyTypeApiController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PropertyTypeApiResource::collection(PropertyType::all());
    }
}
