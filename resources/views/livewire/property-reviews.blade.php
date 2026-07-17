<div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <h3 class="text-2xl font-black text-slate-900">Reviews & Ratings</h3>
            <p class="text-slate-400 text-sm mt-1">Read feedback from other users or post your own experience.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-4xl font-black text-slate-900">{{ number_format($averageRating, 1) }}</span>
            <div>
                <!-- Stars -->
                <div class="flex text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.9 1.51-.9 1.82 0l1.317 3.987a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.376 2.458a1 1 0 00-.364 1.118l1.318 3.987c.3.9-.755 1.688-1.54 1.118l-3.376-2.458a1 1 0 00-1.175 0l-3.376 2.458c-.784.57-1.838-.219-1.54-1.118l1.318-3.987a1 1 0 00-.364-1.118L2.05 9.414c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.318-3.987z"></path>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs text-slate-400 font-bold block mt-1">Based on {{ $reviews->count() }} reviews</span>
            </div>
        </div>
    </div>

    <!-- Add Review Form -->
    <div>
        <h4 class="text-lg font-bold text-slate-900 mb-4">Post a Review</h4>
        @if(auth()->check())
            @if (session()->has('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 mb-4">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="submitReview" class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-slate-700">Your Rating:</span>
                    <div class="flex gap-1">
                        @for($r = 1; $r <= 5; $r++)
                            <button type="button" wire:click="$set('rating', {{ $r }})" class="text-amber-400 hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 {{ $r <= $rating ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.9 1.51-.9 1.82 0l1.317 3.987a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.376 2.458a1 1 0 00-.364 1.118l1.318 3.987c.3.9-.755 1.688-1.54 1.118l-3.376-2.458a1 1 0 00-1.175 0l-3.376 2.458c-.784.57-1.838-.219-1.54-1.118l1.318-3.987a1 1 0 00-.364-1.118L2.05 9.414c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.318-3.987z"></path>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>

                <div>
                    <textarea wire:model="comment" rows="4" placeholder="Share your experience, location feedback, or booking convenience..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors @error('comment') border-red-500 @enderror"></textarea>
                    @error('comment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors">
                    Submit Review
                </button>
            </form>
        @else
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-center">
                <p class="text-slate-500 text-sm">Please log in to leave a review and rating for this property.</p>
                <a href="{{ route('login') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-md mt-4 transition-colors">Log In / Sign Up</a>
            </div>
        @endif
    </div>

    <!-- Reviews List -->
    <div class="space-y-6 pt-6 border-t border-slate-100">
        @forelse($reviews as $rev)
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-700 flex-shrink-0">
                    {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $rev->user->name }}</span>
                        <!-- Stars -->
                        <div class="flex text-amber-400">
                            @for($s = 1; $s <= 5; $s++)
                                <svg class="w-3.5 h-3.5 {{ $s <= $rev->rating ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.9 1.51-.9 1.82 0l1.317 3.987a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.376 2.458a1 1 0 00-.364 1.118l1.318 3.987c.3.9-.755 1.688-1.54 1.118l-3.376-2.458a1 1 0 00-1.175 0l-3.376 2.458c-.784.57-1.838-.219-1.54-1.118l1.318-3.987a1 1 0 00-.364-1.118L2.05 9.414c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.318-3.987z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <span class="text-xs text-slate-400 block">{{ $rev->created_at->diffForHumans() }}</span>
                    <p class="text-slate-600 text-sm pt-1 leading-relaxed">{{ $rev->comment }}</p>
                </div>
            </div>
        @empty
            <p class="text-slate-400 text-sm">No reviews posted yet. Be the first to write a review!</p>
        @endforelse
    </div>
</div>
