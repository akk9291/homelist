@extends('layouts.app')

@section('title', 'Properties Listing - Homelist Real Estate Portal')

@section('content')
<section class="py-12 bg-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-darkslate">Find Properties</h1>
        <p class="text-slate-500 mt-2">Browse through our extensive catalog of properties.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter Form -->
        <aside class="col-span-1 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm h-fit sticky top-24">
            <h3 class="text-lg font-bold text-darkslate mb-6">Filter Properties</h3>
            
            <form action="{{ route('properties.search') }}" method="GET" class="space-y-6">
                <!-- Keyword -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Keyword</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="e.g. Duplex, Villa" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <!-- State -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">State</label>
                    <select name="state_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <option value="">All States</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">City</label>
                    <select name="city_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Property Type</label>
                    <select name="type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Property Status</label>
                    <select name="status_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Price Range</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min Price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max Price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                </div>

                <!-- Bedrooms & Bathrooms -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Beds</label>
                        <input type="number" name="bedrooms" value="{{ request('bedrooms') }}" placeholder="Beds" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Baths</label>
                        <input type="number" name="bathrooms" value="{{ request('bathrooms') }}" placeholder="Baths" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                </div>

                <!-- Amenities Checklist -->
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Amenities</label>
                    <div class="space-y-2.5 max-h-48 overflow-y-auto pr-2">
                        @foreach($amenities as $amenity)
                            <label class="flex items-center gap-2 text-sm text-slate-600 hover:text-darkslate cursor-pointer">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, (array) request('amenities', [])) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                {{ $amenity->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Filter Button -->
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2">
                    Apply Filters
                </button>
            </form>
        </aside>

        <!-- Main Property Grid -->
        <section class="col-span-1 lg:col-span-3 space-y-12">
            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($properties as $property)
                        @include('properties._card', ['property' => $property])
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-12">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 text-slate-500 shadow-sm">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-bold text-darkslate mb-2">No Properties Found</h3>
                    <p>We couldn't find any properties matching your search criteria. Try modifying your filters.</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
