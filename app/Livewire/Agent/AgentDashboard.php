<?php

namespace App\Livewire\Agent;

use App\Models\User;
use App\Models\Agent;
use App\Models\Agency;
use App\Models\AgencyAgent;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use App\Models\City;
use App\Models\State;
use App\Models\Amenity;
use App\Models\PropertyBooking;
use App\Models\PropertyInquiry;
use App\Models\Review;
use App\Models\Payment;
use App\Services\CustomFieldService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AgentDashboard extends Component
{
    use WithFileUploads;

    public string $activeTab = 'overview';

    // Profile Details
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $photo;
    public ?string $avatarUrl = '';
    public ?string $phone = '';
    public ?string $about = '';

    // Agency Details
    public ?int $agencyId = null;
    public string $agencyName = '';
    public string $agencyEmail = '';
    public string $agencyPhone = '';
    public string $agencyWebsite = '';
    public string $agencyAddress = '';
    public string $agencyAbout = '';

    // Property Form Fields (Create/Edit)
    public ?int $propertyId = null;
    public string $propTitle = '';
    public string $propDescription = '';
    public float $propPrice = 0;
    public int $propBedrooms = 1;
    public int $propBathrooms = 1;
    public int $propArea = 0;
    public string $propFacing = 'East';
    public string $propOwnership = 'Freehold';
    public string $propFurnishing = 'Unfurnished';
    public string $propAddress = '';
    public float $propLatitude = 22.7196;
    public float $propLongitude = 75.8577;
    public ?int $propCityId = null;
    public ?int $propStateId = null;
    public ?int $propPropertyTypeId = null;
    public ?int $propPropertyStatusId = null;
    public $propFeaturedImageFile;
    public ?string $propFeaturedImageUrl = '';
    public $propGalleryFiles = [];
    public ?array $propGalleryUrls = [];
    public string $propVideoUrl = '';
    public string $propVirtualTourUrl = '';
    public array $propSelectedAmenities = [];
    public bool $propPublished = true;

    // Dynamic Custom Fields State
    public array $customFieldValues = [];
    public $dynamicFields = []; // Loaded assigned fields

    // Review Replies
    public ?int $selectedReviewId = null;
    public string $reviewReplyText = '';

    // KYC Details
    public string $kycDocType = 'aadhaar';
    public string $kycDocNumber = '';
    public $kycFile;

    protected $queryString = ['activeTab'];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !($user->hasRole('Agent') || $user->role === 'agent')) {
            return redirect()->route('login');
        }

        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatarUrl = $user->avatar_url;

        $agent = $user->agent;
        if ($agent) {
            $this->phone = $agent->phone;
            $this->about = $agent->about;
        }

        // Load Agency details
        $agencyAgent = AgencyAgent::where('user_id', $user->id)->first();
        $agency = $agencyAgent ? $agencyAgent->agency : Agency::where('owner_id', $user->id)->first();
        if ($agency) {
            $this->agencyId = $agency->id;
            $this->agencyName = $agency->name;
            $this->agencyEmail = $agency->email;
            $this->agencyPhone = $agency->phone;
            $this->agencyWebsite = $agency->website;
            $this->agencyAddress = $agency->address;
            $this->agencyAbout = $agency->about;
        }
    }

    /**
     * Load dynamic custom fields based on current property parameters.
     */
    public function loadDynamicFields()
    {
        $service = app(CustomFieldService::class);
        
        // Query assigned custom fields matching current selections
        $fields = $service->getAssignedFieldsFlat(
            $this->propPropertyTypeId,
            null, // Category
            $this->propCityId,
            $this->propStateId
        );

        $this->dynamicFields = $fields->toArray();

        // Populate values
        foreach ($fields as $field) {
            if (!isset($this->customFieldValues[$field->slug])) {
                if ($this->propertyId) {
                    $prop = Property::find($this->propertyId);
                    $val = $prop->getCustomFieldValue($field->slug);
                    
                    // Decode array-type values
                    if (in_array($field->field_type, ['multiselect', 'checkbox', 'multiple_image_upload', 'multiple_file_upload']) && !empty($val)) {
                        $decoded = json_decode($val, true);
                        $val = is_array($decoded) ? $decoded : [$val];
                    }
                    $this->customFieldValues[$field->slug] = $val;
                } else {
                    $this->customFieldValues[$field->slug] = $field->default_value;
                }
            }
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['propPropertyTypeId', 'propCityId', 'propStateId'])) {
            $this->loadDynamicFields();
        }
    }

    // --- Profile & Agent Settings ---
    public function updateProfile()
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'about' => 'nullable|string',
        ];

        if ($this->photo) {
            $rules['photo'] = 'image|max:1024'; // 1MB Max
        }

        if (!empty($this->password)) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        if ($this->photo) {
            $path = $this->photo->store('avatars', 'public');
            $user->avatar_url = '/storage/' . $path;
            $this->avatarUrl = $user->avatar_url;
        }

        $user->save();

        if ($user->agent) {
            $user->agent->update([
                'phone' => $this->phone,
                'about' => $this->about,
            ]);
        }

        session()->flash('success', 'Profile & Agent details updated successfully!');
    }

    // --- Agency Details ---
    public function updateAgency()
    {
        $this->validate([
            'agencyName' => 'required|string|max:255',
            'agencyEmail' => 'required|email',
            'agencyPhone' => 'required|string|max:20',
            'agencyWebsite' => 'nullable|url',
            'agencyAddress' => 'required|string',
            'agencyAbout' => 'nullable|string',
        ]);

        if ($this->agencyId) {
            $agency = Agency::findOrFail($this->agencyId);
            $agency->update([
                'name' => $this->agencyName,
                'email' => $this->agencyEmail,
                'phone' => $this->agencyPhone,
                'website' => $this->agencyWebsite,
                'address' => $this->agencyAddress,
                'about' => $this->agencyAbout,
            ]);
        } else {
            $agency = Agency::create([
                'name' => $this->agencyName,
                'email' => $this->agencyEmail,
                'phone' => $this->agencyPhone,
                'website' => $this->agencyWebsite,
                'address' => $this->agencyAddress,
                'about' => $this->agencyAbout,
                'slug' => Str::slug($this->agencyName),
                'owner_id' => Auth::id(),
            ]);

            AgencyAgent::create([
                'agency_id' => $agency->id,
                'user_id' => Auth::id(),
                'role' => 'owner',
                'status' => 'active',
            ]);

            $this->agencyId = $agency->id;
        }

        session()->flash('success', 'Agency details saved successfully!');
    }

    // --- Properties Management (CRUD) ---
    public function initAddProperty()
    {
        $this->propertyId = null;
        $this->propTitle = '';
        $this->propDescription = '';
        $this->propPrice = 0;
        $this->propBedrooms = 2;
        $this->propBathrooms = 2;
        $this->propArea = 1200;
        $this->propFacing = 'East';
        $this->propOwnership = 'Freehold';
        $this->propFurnishing = 'Unfurnished';
        $this->propAddress = '';
        $this->propLatitude = 22.7196;
        $this->propLongitude = 75.8577;
        $this->propCityId = City::first()?->id;
        $this->propStateId = State::first()?->id;
        $this->propPropertyTypeId = PropertyType::first()?->id;
        $this->propPropertyStatusId = PropertyStatus::first()?->id;
        $this->propFeaturedImageUrl = '';
        $this->propFeaturedImageFile = null;
        $this->propGalleryFiles = [];
        $this->propGalleryUrls = [];
        $this->propVideoUrl = '';
        $this->propVirtualTourUrl = '';
        $this->propSelectedAmenities = [];
        $this->propPublished = true;
        
        $this->customFieldValues = [];
        $this->loadDynamicFields();

        $this->activeTab = 'add_property';
    }

    public function loadEditProperty(int $id)
    {
        $property = Property::findOrFail($id);
        
        $this->authorize('update', $property);

        $this->propertyId = $property->id;
        $this->propTitle = $property->title;
        $this->propDescription = $property->description ?: '';
        $this->propPrice = $property->price;
        $this->propBedrooms = $property->bedrooms ?: 1;
        $this->propBathrooms = $property->bathrooms ?: 1;
        $this->propArea = $property->area ?: 0;
        $this->propFacing = $property->facing ?: 'East';
        $this->propOwnership = $property->ownership ?: 'Freehold';
        $this->propFurnishing = $property->furnishing ?: 'Unfurnished';
        $this->propAddress = $property->address ?: '';
        $this->propLatitude = $property->latitude ?: 22.7196;
        $this->propLongitude = $property->longitude ?: 75.8577;
        $this->propCityId = $property->city_id;
        $this->propStateId = $property->state_id;
        $this->propPropertyTypeId = $property->property_type_id;
        $this->propPropertyStatusId = $property->property_status_id;
        $this->propFeaturedImageUrl = $property->featured_image ?: '';
        $this->propFeaturedImageFile = null;
        $this->propGalleryFiles = [];
        $this->propGalleryUrls = $property->images->pluck('image_path')->toArray();
        $this->propVideoUrl = '';
        $this->propVirtualTourUrl = '';
        $this->propSelectedAmenities = $property->amenities->pluck('id')->toArray();
        $this->propPublished = $property->published;

        $this->customFieldValues = [];
        $this->loadDynamicFields();

        $this->activeTab = 'add_property';
    }

    public function saveProperty()
    {
        $rules = [
            'propTitle' => 'required|string|max:255',
            'propDescription' => 'required|string',
            'propPrice' => 'required|numeric|min:0',
            'propBedrooms' => 'required|integer|min:0',
            'propBathrooms' => 'required|integer|min:0',
            'propArea' => 'required|integer|min:0',
            'propAddress' => 'required|string',
            'propCityId' => 'required|exists:cities,id',
            'propStateId' => 'required|exists:states,id',
            'propPropertyTypeId' => 'required|exists:property_types,id',
            'propPropertyStatusId' => 'required|exists:property_statuses,id',
        ];

        if ($this->propFeaturedImageFile) {
            $rules['propFeaturedImageFile'] = 'image|max:2048';
        }

        // Fetch dynamic fields collection to compile validation
        $service = app(CustomFieldService::class);
        $fieldsCollection = $service->getAssignedFieldsFlat(
            $this->propPropertyTypeId,
            null,
            $this->propCityId,
            $this->propStateId
        );

        // Compile custom field validations
        $dynamicRulesObj = $service->compileValidationRules($fieldsCollection);
        $mergedRules = array_merge($rules, $dynamicRulesObj['rules']);

        $this->validate($mergedRules, [], $dynamicRulesObj['attributes']);

        // Handle dynamic files / images upload
        foreach ($fieldsCollection as $field) {
            $slug = $field->slug;
            if (in_array($field->field_type, ['file', 'image']) && isset($this->customFieldValues[$slug])) {
                $file = $this->customFieldValues[$slug];
                if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $path = $file->store('properties/custom', 'public');
                    $this->customFieldValues[$slug] = '/storage/' . $path;
                }
            } elseif (in_array($field->field_type, ['multiple_file', 'multiple_image']) && isset($this->customFieldValues[$slug])) {
                $files = $this->customFieldValues[$slug];
                if (is_array($files)) {
                    $storedPaths = [];
                    foreach ($files as $file) {
                        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                            $path = $file->store('properties/custom', 'public');
                            $storedPaths[] = '/storage/' . $path;
                        } else {
                            $storedPaths[] = $file;
                        }
                    }
                    $this->customFieldValues[$slug] = $storedPaths;
                }
            }
        }

        $featuredImagePath = $this->propFeaturedImageUrl;
        if ($this->propFeaturedImageFile) {
            $path = $this->propFeaturedImageFile->store('properties/featured', 'public');
            $featuredImagePath = '/storage/' . $path;
        }

        $data = [
            'agent_id' => Auth::id(),
            'title' => $this->propTitle,
            'slug' => $this->propertyId ? Property::find($this->propertyId)->slug : Str::slug($this->propTitle) . '-' . rand(100,999),
            'description' => $this->propDescription,
            'price' => $this->propPrice,
            'bedrooms' => $this->propBedrooms,
            'bathrooms' => $this->propBathrooms,
            'area' => $this->propArea,
            'facing' => $this->propFacing,
            'ownership' => $this->propOwnership,
            'furnishing' => $this->propFurnishing,
            'address' => $this->propAddress,
            'latitude' => $this->propLatitude,
            'longitude' => $this->propLongitude,
            'city_id' => $this->propCityId,
            'state_id' => $this->propStateId,
            'property_type_id' => $this->propPropertyTypeId,
            'property_status_id' => $this->propPropertyStatusId,
            'featured_image' => $featuredImagePath,
            'published' => $this->propPublished,
            'published_at' => $this->propPublished ? now() : null,
        ];

        if ($this->propertyId) {
            $property = Property::findOrFail($this->propertyId);
            $this->authorize('update', $property);
            $property->update($data);
            session()->flash('success', 'Property updated successfully!');
        } else {
            $property = Property::create($data);
            session()->flash('success', 'Property created successfully!');
        }

        // Sync Amenities
        $property->amenities()->sync($this->propSelectedAmenities);

        // Upload Gallery Images if any
        if (!empty($this->propGalleryFiles)) {
            foreach ($this->propGalleryFiles as $galleryFile) {
                $gPath = $galleryFile->store('properties/gallery', 'public');
                $property->images()->create([
                    'image_path' => '/storage/' . $gPath
                ]);
            }
        }

        // Save dynamic custom fields values
        $service->saveValues($property->id, $this->customFieldValues);

        $this->activeTab = 'properties';
    }

    public function deleteProperty(int $id)
    {
        $property = Property::findOrFail($id);
        $this->authorize('delete', $property);
        $property->delete();

        session()->flash('success', 'Property deleted successfully!');
    }

    // --- Bookings ---
    public function updateBookingStatus(int $id, string $status)
    {
        $booking = PropertyBooking::findOrFail($id);
        if ($booking->property->agent_id !== Auth::id()) {
            abort(403);
        }

        $booking->update(['status' => $status]);
        session()->flash('success', 'Booking status updated to: ' . $status);
    }

    // --- Review Replies ---
    public function selectReview(int $id)
    {
        $this->selectedReviewId = $id;
        $this->reviewReplyText = '';
    }

    public function replyToReview()
    {
        $this->validate([
            'reviewReplyText' => 'required|string',
        ]);

        $review = Review::findOrFail($this->selectedReviewId);
        $review->update(['reply' => $this->reviewReplyText]);
        $this->selectedReviewId = null;
        $this->reviewReplyText = '';

        session()->flash('success', 'Reply submitted successfully.');
    }

    // --- KYC Documents Upload ---
    public function uploadKyc()
    {
        $this->validate([
            'kycFile' => 'required|file|mimes:jpeg,png,pdf|max:5120',
            'kycDocType' => 'required|string',
            'kycDocNumber' => 'required|string|max:50',
        ]);

        $filePath = $this->kycFile->store('kyc', 'public');

        Auth::user()->kycDocuments()->create([
            'document_type' => $this->kycDocType,
            'document_number' => $this->kycDocNumber,
            'file_path' => '/storage/' . $filePath,
            'status' => 'pending',
        ]);

        if (Auth::user()->agent) {
            Auth::user()->agent->update([
                'kyc_status' => 'pending',
                'kyc_document_path' => '/storage/' . $filePath,
            ]);
        }

        $this->kycDocNumber = '';
        $this->kycFile = null;

        session()->flash('success', 'KYC Document submitted successfully. Admin will review and verify.');
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Overview Statistics
        $propertiesCount = Property::where('agent_id', $user->id)->count();
        $publishedCount = Property::where('agent_id', $user->id)->where('published', true)->count();
        $draftCount = Property::where('agent_id', $user->id)->where('published', false)->count();
        
        $hasSubscription = $user->hasActiveSubscription();
        $inquiriesCount = $hasSubscription 
            ? PropertyInquiry::whereIn('property_id', Property::where('agent_id', $user->id)->pluck('id'))->count()
            : 0;
            
        $bookingsCount = PropertyBooking::whereIn('property_id', Property::where('agent_id', $user->id)->pluck('id'))->count();
        $walletBalance = $user->wallet?->balance ?? 0.00;

        // 2. Properties List
        $properties = Property::where('agent_id', $user->id)->with(['type', 'status', 'city'])->latest()->get();

        // 3. Bookings
        $bookings = PropertyBooking::whereIn('property_id', Property::where('agent_id', $user->id)->pluck('id'))
            ->with(['property', 'user'])
            ->latest()
            ->get();

        // 4. Inquiries / Leads (Only visible to agent if subscription is active)
        $inquiries = $hasSubscription 
            ? PropertyInquiry::whereIn('property_id', Property::where('agent_id', $user->id)->pluck('id'))
                ->with('property')
                ->latest()
                ->get()
            : collect();

        // 5. Reviews
        $reviews = Review::whereIn('property_id', Property::where('agent_id', $user->id)->pluck('id'))
            ->with(['property', 'user'])
            ->latest()
            ->get();

        // 6. KYC History
        $kycDocs = $user->kycDocuments()->latest()->get();

        // 7. Form Constants
        $cities = City::all();
        $states = State::all();
        $propertyTypes = PropertyType::all();
        $propertyStatuses = PropertyStatus::all();
        $allAmenities = Amenity::all();

        // 8. Custom Field Service grouped fields for UI rendering
        $customFieldService = app(CustomFieldService::class);
        $groupedCustomFields = $customFieldService->getAssignedFieldsGrouped(
            $this->propPropertyTypeId,
            null,
            $this->propCityId,
            $this->propStateId
        );

        return view('livewire.agent.agent-dashboard', [
            'propertiesCount' => $propertiesCount,
            'publishedCount' => $publishedCount,
            'draftCount' => $draftCount,
            'inquiriesCount' => $inquiriesCount,
            'bookingsCount' => $bookingsCount,
            'walletBalance' => $walletBalance,
            'properties' => $properties,
            'bookings' => $bookings,
            'inquiries' => $inquiries,
            'reviews' => $reviews,
            'kycDocs' => $kycDocs,
            'cities' => $cities,
            'states' => $states,
            'propertyTypes' => $propertyTypes,
            'propertyStatuses' => $propertyStatuses,
            'allAmenities' => $allAmenities,
            'groupedCustomFields' => $groupedCustomFields,
            'agent' => $user->agent,
        ])->layout('layouts.app');
    }
}
