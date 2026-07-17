<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityApiResource;
use App\Http\Resources\StateApiResource;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocationApiController extends Controller
{
    public function states(): AnonymousResourceCollection
    {
        return StateApiResource::collection(State::with('cities')->get());
    }

    public function cities(Request $request): AnonymousResourceCollection
    {
        $query = City::with('state');

        if ($request->has('state_id')) {
            $query->where('state_id', $request->input('state_id'));
        }

        return CityApiResource::collection($query->get());
    }
}
