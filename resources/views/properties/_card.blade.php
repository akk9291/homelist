<div class="bg-white rounded-[32px] shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-slate-100/80 overflow-hidden flex flex-col group w-full">
    <!-- Image Header -->
    <div class="relative overflow-hidden aspect-[4/3] bg-slate-100">
        <img src="{{ $property->getFirstMediaUrl('featured', 'thumb') ?: ($property->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80') }}" 
             alt="{{ $property->title }}" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
        
        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
            @if($property->status)
                <span class="bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-sm">
                    {{ $property->status->name }}
                </span>
            @endif
            @if($property->type)
                <span class="bg-amber-500/90 backdrop-blur-md text-slate-950 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-sm">
                    {{ $property->type->name }}
                </span>
            @endif
        </div>

        @if($property->featured)
            <div class="absolute top-4 right-4">
                <span class="bg-yellow-400 text-slate-950 text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current text-slate-950" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    FEATURED
                </span>
            </div>
        @endif
    </div>

    <!-- Card Body -->
    <div class="p-6 flex-grow flex flex-col justify-between">
        <div>
            <!-- Location -->
            <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2.5">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>{{ $property->city?->name }}, {{ $property->state?->name }}</span>
            </div>
            
            <!-- Title -->
            <h3 class="text-base font-extrabold text-slate-900 mb-3.5 line-clamp-1 group-hover:text-amber-500 transition-colors uppercase tracking-tight">
                <a href="{{ route('properties.show', $property->slug) }}">{{ $property->title }}</a>
            </h3>

            <!-- Price -->
            <div class="text-lg font-black text-slate-900 mb-4 bg-slate-50 px-4 py-2 rounded-2xl inline-block border border-slate-100">
                ₹{{ number_format($property->price) }}
            </div>
        </div>

        <!-- Specifications -->
        <div class="border-t border-slate-100 pt-4 flex items-center justify-between text-slate-500 text-xs font-semibold">
            <div class="flex items-center gap-1.5">
                <span class="font-extrabold text-slate-900 text-sm">{{ $property->bedrooms ?: 0 }}</span> Beds
            </div>
            <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
            <div class="flex items-center gap-1.5">
                <span class="font-extrabold text-slate-900 text-sm">{{ $property->bathrooms ?: 0 }}</span> Baths
            </div>
            <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
            <div class="flex items-center gap-1.5">
                <span class="font-extrabold text-slate-900 text-sm">{{ number_format($property->area ?: 0) }}</span> Sq Ft
            </div>
        </div>
    </div>
</div>
