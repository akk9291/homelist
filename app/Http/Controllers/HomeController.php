<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Property;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\Agent;
use App\Models\State;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredProperties = Property::where('published', true)
            ->where('featured', true)
            ->with(['type', 'status', 'city', 'state'])
            ->take(6)
            ->get();

        $recentProperties = Property::where('published', true)
            ->with(['type', 'status', 'city', 'state'])
            ->latest('published_at')
            ->take(6)
            ->get();

        $types = PropertyType::withCount(['properties' => function ($query) {
            $query->where('published', true);
        }])->get();

        $statuses = PropertyStatus::all();
        $states = State::all();
        
        $cities = City::all();

        $popularCities = City::withCount(['properties' => function ($query) {
            $query->where('published', true);
        }])->orderBy('properties_count', 'desc')
            ->take(6)
            ->get();

        $agents = Agent::with('user')
            ->withCount(['properties' => function ($query) {
                $query->where('published', true);
            }])
            ->has('user')
            ->take(6)
            ->get();

        return view('home', compact(
            'featuredProperties',
            'recentProperties',
            'types',
            'statuses',
            'states',
            'cities',
            'popularCities',
            'agents'
        ));
    }
}
