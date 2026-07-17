<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Compare Properties</h1>
            <p class="text-slate-500 text-sm mt-1">Review and compare specifications of your selected properties side-by-side.</p>
        </div>
        @if(count($propertyIds) > 0)
            <button wire:click="clearCompare" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold px-4 py-2.5 rounded-xl text-sm transition-colors border border-red-200">
                Clear All
            </button>
        @endif
    </div>

    @if(count($propertyIds) === 0)
        <div class="text-center py-20 bg-white border border-slate-100 rounded-3xl shadow-sm">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <h3 class="font-bold text-slate-700 text-lg">No properties selected for comparison</h3>
            <p class="text-slate-400 text-sm mt-1">Browse properties and click "Add to Compare" to see them here.</p>
            <a href="{{ route('properties.index') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors mt-6">Browse Properties</a>
        </div>
    @else
        <div class="bg-white border border-slate-100 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-150">
                            <th class="p-6 font-bold text-slate-800 text-base min-w-[200px]">Specification</th>
                            @foreach($properties as $property)
                                <th class="p-6 min-w-[280px] border-l border-slate-150">
                                    <div class="space-y-4">
                                        <div class="aspect-[4/3] bg-slate-100 rounded-2xl overflow-hidden relative">
                                            <img src="{{ $property->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=400&q=80' }}" class="w-full h-full object-cover">
                                            <button wire:click="removeProperty({{ $property->id }})" class="absolute top-2 right-2 bg-white/95 text-slate-700 p-1.5 rounded-full hover:scale-105 transition-all shadow-md text-xs font-black">
                                                ✕
                                            </button>
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-slate-900 leading-snug line-clamp-1">{{ $property->title }}</h3>
                                            <span class="text-xs text-slate-400 mt-1 block">{{ $property->address }}</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-2">
                                            <span class="font-black text-amber-500 text-lg">₹{{ number_format($property->price) }}</span>
                                            <a href="{{ route('properties.show', $property->slug) }}" class="text-xs font-bold text-slate-700 hover:text-amber-500 transition-colors">Details →</a>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Posting Type</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100 capitalize">{{ $property->type?->name }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Property Status</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100 capitalize">{{ $property->status?->name }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Covered Area</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100">{{ number_format($property->area) }} Sq Ft</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Bedrooms / Baths</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100">{{ $property->bedrooms ?: '0' }} BHK / {{ $property->bathrooms ?: '0' }} Baths</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Facing</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100 capitalize">{{ $property->facing ?: 'N/A' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Furnishing</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100 capitalize">{{ $property->furnishing ?: 'N/A' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Ownership</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100 capitalize">{{ $property->ownership ?: 'N/A' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">City / State</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100">{{ $property->city?->name }}, {{ $property->state?->name }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-6 font-bold text-slate-900">Amenities</td>
                            @foreach($properties as $property)
                                <td class="p-6 border-l border-slate-100">
                                    <div class="flex flex-wrap gap-1.5 max-w-[240px]">
                                        @foreach($property->amenities as $amenity)
                                            <span class="bg-slate-100 text-slate-800 text-[10px] font-semibold px-2 py-1 rounded">{{ $amenity->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
