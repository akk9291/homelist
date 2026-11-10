<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyInquiry;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\State;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::where('published', true)
            ->with(['type', 'status', 'city', 'state'])
            ->latest('published_at');

        // Optional quick filter
        if ($request->filled('type')) {
            $query->whereHas('type', fn ($q) => $q->where('slug', $request->type));
        }
        if ($request->filled('status')) {
            $query->whereHas('status', fn ($q) => $q->where('slug', $request->status));
        }

        $properties = $query->paginate(12)->withQueryString();

        $types = PropertyType::all();
        $statuses = PropertyStatus::all();
        $states = State::all();
        $cities = City::all();
        $amenities = Amenity::all();

        return view('properties.index', compact('properties', 'types', 'statuses', 'states', 'cities', 'amenities'));
    }

    public function show(string $slug)
    {
        $property = Property::where('slug', $slug)
            ->where('published', true)
            ->with(['type', 'status', 'city', 'state', 'agent.agent', 'amenities', 'floorPlans', 'seo'])
            ->firstOrFail();

        $property->increment('views');

        // Get similar properties
        $similarProperties = Property::where('published', true)
            ->where('property_type_id', $property->property_type_id)
            ->where('id', '!=', $property->id)
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    public function brochure(string $slug)
    {
        $property = Property::where('slug', $slug)
            ->where('published', true)
            ->with(['type', 'status', 'city', 'state', 'agent.agent', 'amenities', 'floorPlans'])
            ->firstOrFail();

        return view('properties.brochure', compact('property'));
    }

    public function search(Request $request)
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
        $selectedAmenities = $request->input('amenities', []);

        if (!empty($keyword)) {
            // Scout Search
            $scoutQuery = Property::search($keyword);

            if ($typeId) $scoutQuery->where('property_type_id', (int) $typeId);
            if ($statusId) $scoutQuery->where('property_status_id', (int) $statusId);
            if ($cityId) $scoutQuery->where('city_id', (int) $cityId);
            if ($stateId) $scoutQuery->where('state_id', (int) $stateId);

            $properties = $scoutQuery->query(function ($builder) use ($priceMin, $priceMax, $bedrooms, $bathrooms, $selectedAmenities, $request) {
                $builder->where('published', true)
                    ->with(['type', 'status', 'city', 'state'])
                    ->withCustomFieldFilters($request->input('custom_fields', []));

                if ($priceMin) $builder->where('price', '>=', $priceMin);
                if ($priceMax) $builder->where('price', '<=', $priceMax);
                if ($bedrooms) $builder->where('bedrooms', $bedrooms);
                if ($bathrooms) $builder->where('bathrooms', $bathrooms);
                
                if (!empty($selectedAmenities)) {
                    foreach ($selectedAmenities as $amId) {
                        $builder->whereHas('amenities', fn ($q) => $q->where('amenities.id', $amId));
                    }
                }
            })->paginate(12)->withQueryString();
        } else {
            // Regular Eloquent Search
            $builder = Property::where('published', true)
                ->with(['type', 'status', 'city', 'state'])
                ->withCustomFieldFilters($request->input('custom_fields', []));

            if ($stateId) $builder->where('state_id', $stateId);
            if ($cityId) $builder->where('city_id', $cityId);
            if ($typeId) $builder->where('property_type_id', $typeId);
            if ($statusId) $builder->where('property_status_id', $statusId);
            if ($priceMin) $builder->where('price', '>=', $priceMin);
            if ($priceMax) $builder->where('price', '<=', $priceMax);
            if ($bedrooms) $builder->where('bedrooms', $bedrooms);
            if ($bathrooms) $builder->where('bathrooms', $bathrooms);

            if (!empty($selectedAmenities)) {
                foreach ($selectedAmenities as $amId) {
                    $builder->whereHas('amenities', fn ($q) => $q->where('amenities.id', $amId));
                }
            }

            $properties = $builder->latest('published_at')->paginate(12)->withQueryString();
        }

        $types = PropertyType::all();
        $statuses = PropertyStatus::all();
        $states = State::all();
        $cities = City::all();
        $amenities = Amenity::all();

        return view('properties.index', compact('properties', 'types', 'statuses', 'states', 'cities', 'amenities'));
    }

    public function inquiry(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $inquiry = PropertyInquiry::create($validated);
        $property = Property::with('agent')->find($validated['property_id']);

        // Dispatch Email / Alerts via NotificationService
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $siteName = config('app.name', 'Homelist');

            // 1. Notify Assigned Agent (If subscription is active)
            if ($property && $property->agent && $property->agent->hasActiveSubscription()) {
                $agentEmail = $property->agent->email;
                $subject = "New Inquiry for {$property->title} - {$siteName}";
                $body = "<h3>You have received a new property inquiry!</h3>" .
                        "<p><strong>Property:</strong> {$property->title}</p>" .
                        "<p><strong>Buyer Name:</strong> {$validated['name']}</p>" .
                        "<p><strong>Phone:</strong> {$validated['phone']}</p>" .
                        "<p><strong>Email:</strong> {$validated['email']}</p>" .
                        "<p><strong>Message:</strong> {$validated['message']}</p>" .
                        "<p><a href='" . url('/agent/dashboard') . "'>View in Agent Dashboard</a></p>";
                $notificationService->sendEmail($agentEmail, $subject, $body);
            }

            // 2. Buyer Confirmation Email
            $buyerSubject = "Inquiry Received: {$property->title} - {$siteName}";
            $buyerBody = "<h3>Thank you for your interest in {$property->title}!</h3>" .
                         "<p>Hello {$validated['name']},</p>" .
                         "<p>We have received your query. An authorized representative will get in touch with you shortly.</p>" .
                         "<p>Property Link: <a href='" . url('/properties/' . $property->slug) . "'>{$property->title}</a></p>" .
                         "<br><p>Best regards,<br>The {$siteName} Team</p>";
            $notificationService->sendEmail($validated['email'], $buyerSubject, $buyerBody);
        } catch (\Throwable $e) {
            // Silently log and don't interrupt customer flow
            \Illuminate\Support\Facades\Log::warning("Inquiry email notification error: " . $e->getMessage());
        }

        return back()->with('success', 'Your inquiry has been successfully submitted! The agent will contact you shortly.');
    }
}
