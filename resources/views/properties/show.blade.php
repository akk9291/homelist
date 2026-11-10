@extends('layouts.app')

@section('title', $property->seo?->meta_title ?: $property->title . ' - Homelist')
@section('meta_description', $property->seo?->meta_description ?: Str::limit(strip_tags($property->description), 150))
@section('meta_keywords', $property->seo?->keywords ?: 'property, home, buy, rent')

@section('content')
<!-- Schema.org JSON-LD Real Estate Markup -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "RealEstateAgent",
  "name": "Homelist Portal",
  "image": "{{ $property->getFirstMediaUrl('featured') ?: $property->featured_image }}",
  "telephone": "+91 98265 23333",
  "url": "{{ url('/') }}",
  "priceRange": "INR",
  "address": {
    "@@type": "PostalAddress",
    "streetAddress": "{{ $property->address }}",
    "addressLocality": "{{ $property->city?->name }}",
    "addressRegion": "{{ $property->state?->name }}",
    "addressCountry": "IN"
  }
}
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Title & Price -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <span class="bg-primary-100 text-primary-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $property->type?->name }}</span>
                <span class="bg-slate-100 text-slate-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $property->status?->name }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-darkslate mb-2">{{ $property->title }}</h1>
            <p class="text-slate-500 flex items-center gap-1">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $property->address }}
            </p>
        </div>
        <div class="text-left md:text-right flex flex-col items-start md:items-end gap-3">
            <div>
                <span class="text-slate-400 text-sm font-semibold uppercase tracking-wider block mb-1">Asking Price</span>
                <span class="text-3xl font-black text-primary-600">₹{{ number_format($property->price) }}</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('properties.brochure', $property->slug) }}" target="_blank" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-3.5 py-1.5 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Download Brochure</span>
                </a>
                <a href="{{ route('wishlist.toggle', $property->id) }}" class="border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1 transition-colors">
                    @if(auth()->check() && auth()->user()->favoriteProperties->contains($property->id))
                        ❤️ Wishlisted
                    @else
                        🤍 Wishlist
                    @endif
                </a>
                <a href="{{ route('compare.add', $property->id) }}" class="border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1 transition-colors">
                    ➕ Compare
                </a>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12 rounded-3xl overflow-hidden shadow-sm">
        <!-- Main Featured Image -->
        <div class="md:col-span-2 aspect-[4/3] relative group bg-slate-100">
            <img src="{{ $property->getFirstMediaUrl('featured', 'large') ?: ($property->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=80') }}" 
                 alt="Featured Image" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500">
        </div>
        
        <!-- Gallery Sidebar Grid -->
        <div class="grid grid-cols-2 md:grid-cols-1 gap-4 h-full">
            @php $gallery = $property->getMedia('gallery')->take(2); @endphp
            @foreach($gallery as $media)
                <div class="aspect-[4/3] bg-slate-100 overflow-hidden relative group">
                    <img src="{{ $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl() }}" alt="Gallery Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            @endforeach
            
            <!-- Fallback placeholders if gallery is empty -->
            @if(count($gallery) == 0)
                <div class="aspect-[4/3] bg-slate-100 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1513584684374-8bab748fbf90?auto=format&fit=crop&w=600&q=80" alt="Placeholder 1" class="w-full h-full object-cover">
                </div>
                <div class="aspect-[4/3] bg-slate-100 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1502005229762-fc1b2b812ca5?auto=format&fit=crop&w=600&q=80" alt="Placeholder 2" class="w-full h-full object-cover">
                </div>
            @endif
        </div>
    </div>

    <!-- Layout Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-12">
            <!-- Quick Summary -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm grid grid-cols-3 gap-6 text-center">
                <div>
                    <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Bedrooms</span>
                    <span class="text-xl font-bold text-darkslate">{{ $property->bedrooms ?: '0' }}</span>
                </div>
                <div class="border-l border-r border-slate-100">
                    <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Bathrooms</span>
                    <span class="text-xl font-bold text-darkslate">{{ $property->bathrooms ?: '0' }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Area</span>
                    <span class="text-xl font-bold text-darkslate">{{ number_format($property->area ?: 0) }} Sq Ft</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-xl font-bold text-darkslate mb-4">Property Description</h3>
                <div class="prose max-w-none text-slate-600 leading-relaxed">
                    {!! $property->description ?: 'No description provided.' !!}
                </div>
            </div>

            <!-- Details List -->
            <div>
                <h3 class="text-xl font-bold text-darkslate mb-4">Property Specifications</h3>
                <div class="grid grid-cols-2 gap-4 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">Facing:</span>
                        <span class="font-semibold text-darkslate">{{ $property->facing ?: 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">Furnishing:</span>
                        <span class="font-semibold text-darkslate">{{ $property->furnishing ?: 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">Ownership:</span>
                        <span class="font-semibold text-darkslate">{{ $property->ownership ?: 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">Views:</span>
                        <span class="font-semibold text-darkslate">{{ $property->views }}</span>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div>
                <h3 class="text-xl font-bold text-darkslate mb-4">Amenities & Features</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                    @forelse($property->amenities as $amenity)
                        <div class="flex items-center gap-2.5 text-slate-700">
                            <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-xs">✓</span>
                            <span class="text-sm font-medium">{{ $amenity->name }}</span>
                        </div>
                    @empty
                        <div class="text-slate-400 col-span-full">No amenities listed.</div>
                    @endforelse
                </div>
            </div>

            <!-- Floor Plans -->
            @if($property->floorPlans->count() > 0)
                <div>
                    <h3 class="text-xl font-bold text-darkslate mb-4">Floor Plans</h3>
                    <div class="space-y-4">
                        @foreach($property->floorPlans as $plan)
                            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6" x-data="{ open: false }">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-darkslate text-lg">{{ $plan->name }}</h4>
                                    <div class="flex flex-wrap gap-4 text-sm text-slate-500 font-medium">
                                        <span>Size: {{ $plan->size ? $plan->size . ' Sq Ft' : 'N/A' }}</span>
                                        <span>Beds: {{ $plan->bedrooms ?: '0' }}</span>
                                        <span>Baths: {{ $plan->bathrooms ?: '0' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($plan->price)
                                        <span class="font-extrabold text-primary-600 text-lg">₹{{ number_format($plan->price) }}</span>
                                    @endif
                                    <button @click="open = !open" class="text-slate-500 hover:text-primary-600 font-semibold text-sm flex items-center gap-1">
                                        View Details
                                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                </div>
                                <div class="w-full border-t border-slate-100 pt-4 mt-4 text-slate-600 text-sm col-span-full" x-show="open" x-collapse>
                                    <p class="mb-4">{{ $plan->description }}</p>
                                    @if($plan->image)
                                        <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->name }}" class="max-w-md w-full rounded-2xl border border-slate-200">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Loan & EMI Calculator -->
            <div class="mt-12">
                <livewire:loan-calculator :price="$property->price" />
            </div>

            <!-- Reviews & Ratings -->
            <div class="mt-12">
                <livewire:property-reviews :property-id="$property->id" />
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-8">
            <!-- Agent Card -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm text-center">
                <h3 class="text-lg font-bold text-darkslate mb-6">Listing Agent</h3>
                @if($property->agent)
                    <div class="mb-4">
                        <img src="{{ $property->agent->agent?->photo ? asset('storage/' . $property->agent->agent->photo) : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=200&q=80' }}" 
                             alt="{{ $property->agent->name }}" class="w-24 h-24 rounded-full object-cover mx-auto shadow-md">
                    </div>
                    <h4 class="font-bold text-lg text-darkslate mb-1 flex items-center justify-center gap-1.5">
                        <a href="{{ route('agents.show', $property->agent->id) }}" class="hover:text-primary-600 transition-colors">{{ $property->agent->name }}</a>
                        @if($property->agent->agent?->is_verified || $property->agent->agent?->kyc_status === 'verified')
                            <span class="inline-flex items-center text-blue-500" title="Verified RERA Agent">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </span>
                        @endif
                    </h4>
                    <p class="text-slate-400 text-sm mb-4">
                        @if($property->agent->agent?->is_verified || $property->agent->agent?->kyc_status === 'verified')
                            <span class="text-blue-600 font-semibold text-xs bg-blue-50 px-2 py-0.5 rounded-md">✔ Verified Partner Agent</span>
                        @else
                            Licensed Real Estate Agent
                        @endif
                    </p>
                    <p class="text-slate-500 text-xs italic line-clamp-3 mb-6">"{{ $property->agent->agent?->about ?: 'Ready to assist you in finding your perfect property matching your requirements.' }}"</p>
                    
                    @php
                        $rawPhone = $property->agent->agent?->phone ?: ($settings->contact_whatsapp ?: $settings->contact_phone);
                        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                        $waText = "Hello! I am interested in property: " . $property->title . " (₹" . number_format($property->price) . "). " . url()->current();
                    @endphp

                    <div class="space-y-2">
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-sm transition-all">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>WhatsApp Agent</span>
                            </a>
                        @endif
                        <a href="{{ route('agents.show', $property->agent->id) }}" class="inline-block text-primary-600 hover:text-primary-700 font-semibold text-xs py-1">View All Listings →</a>
                    </div>
                @else
                    <p class="text-slate-400">Independent Owner/Agent</p>
                @endif
            </div>

            <!-- Inquiry Form -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-darkslate mb-6">Inquire About Property</h3>
                <form action="{{ route('properties.inquiry') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    
                    <div>
                        <input type="text" name="name" placeholder="Your Name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Your Email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <input type="tel" name="phone" placeholder="Your Phone Number" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <textarea name="message" placeholder="I am interested in this property and would like more information..." required rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3.5 rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2">
                        Submit Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
