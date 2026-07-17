@extends('layouts.app')

@section('title', 'Homelist Real Estate Portal - Premium Properties')

@section('content')
<!-- Hero Section (Veedoo Style) -->
<section class="relative bg-slate-950 text-white min-h-[650px] flex items-center justify-center py-20 px-4 overflow-hidden" 
         style="background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
    
    <!-- Decorative Glow Elements -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-yellow-500/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto w-full text-center relative z-10 flex flex-col items-center">
        <span class="bg-amber-500/10 text-amber-400 font-bold px-4 py-1.5 rounded-full text-xs border border-amber-500/20 uppercase tracking-widest mb-6 inline-block">
            Modern Real Estate Marketplace
        </span>
        <h1 class="text-4xl sm:text-6xl font-black tracking-tight mb-6 leading-tight max-w-4xl mx-auto uppercase">
            Find Your Perfect <br>
            <span class="bg-gradient-to-r from-amber-400 to-yellow-300 bg-clip-text text-transparent">Place to Live</span>
        </h1>
        <p class="text-slate-300 text-base sm:text-lg max-w-xl mx-auto mb-10 font-normal leading-relaxed">
            Search thousands of verified listings across prime locations. Your dream home is just a click away.
        </p>

        <!-- Search Bar Widget (Tabbed Veedoo Style) -->
        <div class="w-full max-w-5xl bg-slate-900/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-2xl border border-slate-800 text-left" 
             x-data="{ activeStatus: 'for-sale' }">
            
            <!-- Tab Headers -->
            <div class="flex gap-2 mb-6 border-b border-slate-800 pb-4">
                @foreach($statuses->take(3) as $status)
                    <button type="button" 
                            @click="activeStatus = '{{ $status->slug }}'"
                            :class="activeStatus === '{{ $status->slug }}' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-200">
                        {{ $status->name }}
                    </button>
                @endforeach
            </div>

            <!-- Form -->
            <form action="{{ route('properties.search') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Status Hidden Input -->
                @foreach($statuses->take(3) as $status)
                    <input type="hidden" name="status_id" value="{{ $status->id }}" x-show="activeStatus === '{{ $status->slug }}'" :disabled="activeStatus !== '{{ $status->slug }}'">
                @endforeach

                <!-- Keyword -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keyword</label>
                    <input type="text" name="keyword" placeholder="Duplex, Villa, Land..." 
                           class="w-full bg-slate-800/80 border border-slate-700 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                </div>

                <!-- State -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">State</label>
                    <select name="state_id" class="w-full bg-slate-800/80 border border-slate-700 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                        <option value="">All States</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">City</label>
                    <select name="city_id" class="w-full bg-slate-800/80 border border-slate-700 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Property Type -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Property Type</label>
                    <select name="type_id" class="w-full bg-slate-800/80 border border-slate-700 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3.5 rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Dynamic Stats Ribbon -->
        <div class="mt-8 flex flex-wrap justify-center items-center gap-6 sm:gap-12 text-slate-400 text-sm">
            <div class="flex items-center gap-2">
                <span class="text-white font-extrabold text-lg">{{ \App\Models\Property::where('published', true)->count() }}+</span>
                <span>Properties Available</span>
            </div>
            <div class="w-1.5 h-1.5 bg-slate-800 rounded-full hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <span class="text-white font-extrabold text-lg">{{ \App\Models\Agent::count() }}+</span>
                <span>Expert Agents</span>
            </div>
            <div class="w-1.5 h-1.5 bg-slate-800 rounded-full hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <div class="flex gap-0.5">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-4 h-4 fill-amber-500 text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                <span class="text-white font-extrabold">4.8 Rating</span>
            </div>
        </div>
    </div>
</section>

<!-- Category Showcase Grid -->
@php
$typeImages = [
    'residential' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=500&q=80',
    'commercial' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=500&q=80',
    'shop' => 'https://images.unsplash.com/photo-1555421689-491a97ff2040?auto=format&fit=crop&w=500&q=80',
    'farm-house' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=500&q=80',
    'hotel' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=500&q=80',
    'office' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=500&q=80',
    'hostel-building' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=500&q=80',
    'semi-commercial' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=500&q=80',
    'industrial-land' => 'https://images.unsplash.com/photo-1530089711124-9ca31fb9e863?auto=format&fit=crop&w=500&q=80',
    'other' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=500&q=80',
];
@endphp
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-amber-500 font-bold uppercase tracking-widest text-xs block mb-3">Top Rated Categories</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 uppercase">Explore by Category</h2>
            <div class="w-12 h-1 bg-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($types->take(6) as $type)
                <a href="{{ route('properties.index') }}?type={{ $type->slug }}" 
                   class="group relative h-60 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-end p-5 bg-slate-900 border border-slate-100">
                    <!-- Background Cover Image -->
                    <div class="absolute inset-0 bg-slate-950/40 z-10 transition-colors group-hover:bg-slate-950/60"></div>
                    <img src="{{ $typeImages[$type->slug] ?? $typeImages['other'] }}" 
                         alt="{{ $type->name }}" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <!-- Content -->
                    <div class="relative z-20">
                        <span class="bg-amber-500 text-slate-950 font-bold text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block mb-2 shadow">
                            {{ $type->properties_count }} Listings
                        </span>
                        <h3 class="text-white font-extrabold text-base tracking-tight uppercase group-hover:text-amber-400 transition-colors">
                            {{ $type->name }}
                        </h3>
                        <span class="text-slate-300 text-[10px] font-semibold flex items-center gap-1 mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View details
                            <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Comfort Living Grid (Tabbed Properties Grid) -->
@php
$allTabProperties = $featuredProperties->merge($recentProperties)->unique('id')->take(6);
@endphp
<section class="py-24 bg-slate-50 border-t border-slate-100" x-data="{ activeTab: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-amber-500 font-bold uppercase tracking-widest text-xs block mb-3">Our Choice</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 uppercase">Comfort Living Solution</h2>
            <div class="w-12 h-1 bg-amber-500 mx-auto mt-4 rounded-full"></div>
            
            <!-- Filters Tabs -->
            <div class="flex flex-wrap justify-center gap-3 mt-10">
                <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'bg-slate-900 text-white font-bold shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
                        class="px-6 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider transition-all duration-200">
                    All Properties
                </button>
                @foreach($statuses->take(2) as $status)
                    <button @click="activeTab = '{{ $status->slug }}'" 
                            :class="activeTab === '{{ $status->slug }}' ? 'bg-slate-900 text-white font-bold shadow' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
                            class="px-6 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider transition-all duration-200">
                        {{ $status->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($allTabProperties as $property)
                <div x-show="activeTab === 'all' || activeTab === '{{ $property->status?->slug }}'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex">
                    @include('properties._card', ['property' => $property])
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-500">
                    No properties listed yet.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- City Destination Showcase ("Uncover Dream Country Home") -->
@php
$cityImages = [
    'indore' => 'https://images.unsplash.com/photo-1627483262112-039e9a0a0f16?auto=format&fit=crop&w=600&q=80',
    'nagpur' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=600&q=80',
    'bhopal' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=600&q=80',
    'pune' => 'https://images.unsplash.com/photo-1601931145329-a5e2f754779f?auto=format&fit=crop&w=600&q=80',
    'default' => 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=600&q=80'
];
@endphp
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-amber-500 font-bold uppercase tracking-widest text-xs block mb-3">Popular Locations</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 uppercase">Uncover Dream Country Home</h2>
            <div class="w-12 h-1 bg-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($popularCities->take(4) as $city)
                <div class="group relative h-96 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 bg-slate-900 flex flex-col justify-end p-6">
                    <!-- Image -->
                    <div class="absolute inset-0 bg-slate-950/30 z-10 transition-colors group-hover:bg-slate-950/60"></div>
                    <img src="{{ $cityImages[$city->slug] ?? $cityImages['default'] }}" 
                         alt="{{ $city->name }}" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    
                    <!-- Content -->
                    <div class="relative z-20 text-white">
                        <span class="bg-amber-500 text-slate-950 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider shadow inline-block mb-3">
                            {{ $city->properties_count }} properties
                        </span>
                        <h3 class="text-2xl font-black tracking-tight uppercase mb-4">{{ $city->name }}</h3>
                        <a href="{{ route('properties.search') }}?city_id={{ $city->id }}" 
                           class="w-full bg-white/10 hover:bg-white text-white hover:text-slate-950 text-xs font-bold py-3 px-6 rounded-xl transition-all duration-200 inline-block text-center backdrop-blur-sm shadow uppercase tracking-wider">
                            View Property
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Real Estate Team Showcase -->
<section class="py-24 bg-slate-50 border-t border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-amber-500 font-bold uppercase tracking-widest text-xs block mb-3">Professional Brokers</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 uppercase">Meet Our Real Estate Team</h2>
            <div class="w-12 h-1 bg-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-8">
            @foreach($agents as $agent)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 text-center flex flex-col items-center group">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-4 border-2 border-slate-100 group-hover:border-amber-500 transition-colors shadow">
                        <img src="https://i.pravatar.cc/150?img={{ ($agent->id % 70) + 1 }}" 
                             alt="{{ $agent->user?->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-slate-900 font-extrabold text-base tracking-tight mb-1 line-clamp-1 uppercase">{{ $agent->user?->name }}</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 block">Expert Agent</span>
                    <span class="text-amber-600 bg-amber-50 font-bold text-[9px] px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm mb-4">
                        {{ $agent->properties_count }} Listings
                    </span>
                    <a href="{{ route('agents.show', $agent->user_id) }}" 
                       class="text-xs font-bold text-slate-900 hover:text-amber-500 transition-colors uppercase tracking-wider flex items-center gap-1 group/btn">
                        View Profile
                        <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Happy Homeowners Testimonial Section -->
<section class="py-24 bg-white" x-data="{ activeIndex: 0, testimonialsCount: 3 }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="text-amber-500 font-bold uppercase tracking-widest text-xs block mb-3">Happy Clients</span>
        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 uppercase mb-12">Our Happy Homeowners!</h2>
        
        <!-- Testimonial Slider Container -->
        <div class="relative h-64 sm:h-52 overflow-hidden mb-8">
            <!-- Testimonial 1 -->
            <div x-show="activeIndex === 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="absolute inset-0 flex flex-col items-center justify-center">
                <p class="text-slate-600 text-base sm:text-lg italic leading-relaxed max-w-2xl mb-6">
                    "Finding a commercial property that checked all our boxes seemed impossible until we used this portal. The dynamic search filter let us drill down to specific locations and facing preferences instantly. Highly recommended!"
                </p>
                <h4 class="text-slate-950 font-extrabold text-base uppercase tracking-wider">Manoj Deshmukh</h4>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Bhopal Homeowner</span>
            </div>

            <!-- Testimonial 2 -->
            <div x-show="activeIndex === 1" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="absolute inset-0 flex flex-col items-center justify-center">
                <p class="text-slate-600 text-base sm:text-lg italic leading-relaxed max-w-2xl mb-6">
                    "The agent details and direct contact functionality made our search extremely transparent. We closed on a premium duplex in Indore within just three weeks of browsing. The gallery images were fully loaded and accurate."
                </p>
                <h4 class="text-slate-950 font-extrabold text-base uppercase tracking-wider">Archana Sharma</h4>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Indore Homeowner</span>
            </div>

            <!-- Testimonial 3 -->
            <div x-show="activeIndex === 2" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="absolute inset-0 flex flex-col items-center justify-center">
                <p class="text-slate-600 text-base sm:text-lg italic leading-relaxed max-w-2xl mb-6">
                    "Amazing experience. The location details and city listings counts are accurate. The WhatsApp dynamic chat feature allowed us to clear doubts on listings instantly with active realtors."
                </p>
                <h4 class="text-slate-950 font-extrabold text-base uppercase tracking-wider">Sandeep Joshi</h4>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Nagpur Homeowner</span>
            </div>
        </div>

        <!-- Slider Navigation Dots -->
        <div class="flex justify-center gap-2">
            <template x-for="i in Array.from({length: testimonialsCount})">
                <button type="button" 
                        @click="activeIndex = Array.from({length: testimonialsCount}).indexOf(i)"
                        :class="activeIndex === Array.from({length: testimonialsCount}).indexOf(i) ? 'bg-amber-500 w-8' : 'bg-slate-200 w-2.5'"
                        class="h-2.5 rounded-full transition-all duration-300" 
                        aria-label="Slide Indicator"></button>
            </template>
        </div>
    </div>
</section>

<!-- Call to Action Section (Premium Dark Banner) -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-tr from-slate-950 to-slate-900 rounded-[40px] p-8 sm:p-16 text-center text-white relative overflow-hidden shadow-2xl border border-slate-900">
            <div class="absolute -right-32 -top-32 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center">
                <span class="text-amber-400 font-bold uppercase tracking-widest text-xs block mb-4">Join Our Platform</span>
                <h2 class="text-3xl sm:text-5xl font-black uppercase mb-6 leading-tight">Your Dream Home Awaits - Explore Now!</h2>
                <p class="text-slate-400 text-base mb-10 font-normal leading-relaxed">
                    Connect directly with real estate agents, list properties, manage floor plans, and view customer inquiries inside a robust administrator panel.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('properties.index') }}" 
                       class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-4 px-8 rounded-2xl shadow-lg transition-all duration-200 inline-block hover:-translate-y-0.5 uppercase tracking-wider text-xs">
                        Explore properties
                    </a>
                    <a href="/admin/login" 
                       class="bg-slate-800 hover:bg-slate-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-200 inline-block hover:-translate-y-0.5 border border-slate-700 uppercase tracking-wider text-xs">
                        Agent Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
