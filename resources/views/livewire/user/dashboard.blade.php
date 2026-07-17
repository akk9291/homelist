<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black text-slate-900 mb-8">My Account Dashboard</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 h-fit space-y-2">
            <button wire:click="$set('activeTab', 'profile')" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'profile' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profile Details
            </button>

            @if(auth()->user()->role === 'agent')
                <button wire:click="$set('activeTab', 'kyc')" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'kyc' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    KYC & Profile Verification
                </button>
            @endif

            <button wire:click="$set('activeTab', 'wishlist')" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'wishlist' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                My Wishlist
            </button>

            <button wire:click="$set('activeTab', 'bookings')" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'bookings' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Booking Inquiries
            </button>

            <button wire:click="$set('activeTab', 'payments')" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'payments' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Purchase History
            </button>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
            <!-- Profile Details Tab -->
            @if($activeTab === 'profile')
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Profile Settings</h2>
                    @if (session()->has('success'))
                        <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 mb-6">{{ session('success') }}</div>
                    @endif
                    <form wire:submit.prevent="updateProfile" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Email address</label>
                                <input type="email" wire:model="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-6">
                            <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Change Password</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">New Password</label>
                                    <input type="password" wire:model="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm New Password</label>
                                    <input type="password" wire:model="password_confirmation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- KYC Verification Tab -->
            @if($activeTab === 'kyc' && auth()->user()->role === 'agent')
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-2">Agent KYC & Profile Verification</h2>
                    <p class="text-slate-500 text-sm mb-6">Verify your credentials to receive a verified agent badge on your profile and properties.</p>

                    @if (session()->has('success'))
                        <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 mb-6">{{ session('success') }}</div>
                    @endif

                    <!-- Verification Status Bar -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Verification Badge Status</span>
                            <span class="text-sm font-bold text-slate-900 flex items-center gap-2 mt-1">
                                @if($agent?->kyc_status === 'verified')
                                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span> Verified Profile
                                @elseif($agent?->kyc_status === 'pending')
                                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full"></span> Verification Pending (Admin Review)
                                @else
                                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> Unverified
                                @endif
                            </span>
                        </div>
                        @if($agent?->kyc_status === 'verified')
                            <span class="bg-green-100 text-green-800 text-xs font-black px-3 py-1.5 rounded-lg flex items-center gap-1">✓ Active Badge</span>
                        @endif
                    </div>

                    <form wire:submit.prevent="uploadKyc" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Public Agent Phone</label>
                                <input type="text" wire:model="phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">KYC Document (PDF/ID Photo)</label>
                                <input type="file" wire:model="kycFile" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm cursor-pointer file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-800 file:cursor-pointer">
                                @error('kycFile') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">About Me (Bio)</label>
                            <textarea wire:model="about" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors"></textarea>
                            @error('about') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors">
                                Submit KYC Documents
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Wishlist Tab -->
            @if($activeTab === 'wishlist')
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-6">My Wishlist Properties</h2>
                    
                    @if (session()->has('success'))
                        <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 mb-6">{{ session('success') }}</div>
                    @endif

                    @if($wishlist->isEmpty())
                        <p class="text-slate-400 text-sm">No properties added to your wishlist yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($wishlist as $property)
                                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm flex flex-col justify-between">
                                    <div class="relative aspect-[4/3] bg-slate-100">
                                        <img src="{{ $property->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80' }}" class="w-full h-full object-cover">
                                        <button wire:click="toggleWishlist({{ $property->id }})" class="absolute top-3 right-3 bg-white/95 text-red-500 p-2 rounded-full hover:scale-105 transition-all shadow-md">
                                            ✕
                                        </button>
                                    </div>
                                    <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-lg leading-tight line-clamp-1">{{ $property->title }}</h3>
                                            <p class="text-slate-400 text-xs mt-1">{{ $property->address }}</p>
                                        </div>
                                        <div class="flex justify-between items-center border-t border-slate-50 pt-3">
                                            <span class="font-black text-amber-500 text-lg">₹{{ number_format($property->price) }}</span>
                                            <a href="{{ route('properties.show', $property->slug) }}" class="text-xs font-bold text-slate-700 hover:text-amber-500 transition-colors">View Details →</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Booking Inquiries Tab -->
            @if($activeTab === 'bookings')
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Booking & Property Inquiries</h2>
                    
                    @if($inquiries->isEmpty())
                        <p class="text-slate-400 text-sm">You haven't submitted any property booking inquiries yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($inquiries as $inquiry)
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="font-bold text-slate-900">
                                                @if($inquiry->property)
                                                    <a href="{{ route('properties.show', $inquiry->property->slug) }}" class="hover:text-amber-500 transition-colors">{{ $inquiry->property->title }}</a>
                                                @else
                                                    Archived Property
                                                @endif
                                            </h3>
                                            <span class="text-slate-400 text-xs">Submitted on {{ $inquiry->created_at->format('d M Y, h:i A') }}</span>
                                        </div>
                                        @if($inquiry->property)
                                            <span class="bg-amber-100 text-amber-800 text-xs font-black px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $inquiry->property->type?->name }}</span>
                                        @endif
                                    </div>
                                    <p class="text-slate-600 text-sm italic">"{{ $inquiry->message }}"</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Payments / Subscriptions Tab -->
            @if($activeTab === 'payments')
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Subscription & Billing History</h2>

                    @if(auth()->user()->activeSubscription)
                        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 mb-8 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider block">Current Active Plan</span>
                                <span class="text-2xl font-black text-slate-900 mt-1">{{ auth()->user()->activeSubscription->subscriptionPlan->name }}</span>
                                <span class="text-slate-500 text-xs block mt-1">Active until: {{ auth()->user()->activeSubscription->ends_at ? auth()->user()->activeSubscription->ends_at->format('d M Y') : 'Lifetime access' }}</span>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs font-black px-3 py-1.5 rounded-lg flex items-center gap-1">● Active</span>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 mb-8 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-slate-900">No active subscription plan</span>
                                <span class="text-slate-400 text-xs block mt-1">Purchase a plan to unlock premium postings and verification.</span>
                            </div>
                            <a href="{{ route('subscription.checkout') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-colors shadow-md">Upgrade Plan</a>
                        </div>
                    @endif

                    <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Billing History</h3>

                    @if($payments->isEmpty())
                        <p class="text-slate-400 text-sm">No transaction records found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <th class="pb-3">Invoice No</th>
                                        <th class="pb-3">Plan</th>
                                        <th class="pb-3">Gateway</th>
                                        <th class="pb-3">Amount</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @foreach($payments as $payment)
                                        <tr class="text-slate-700">
                                            <td class="py-4 font-semibold text-slate-900">{{ $payment->invoice_number ?: 'N/A' }}</td>
                                            <td class="py-4">{{ $payment->subscriptionPlan?->name ?: 'N/A' }}</td>
                                            <td class="py-4 capitalize">{{ $payment->payment_gateway }}</td>
                                            <td class="py-4 font-bold text-slate-900">₹{{ number_format($payment->total_amount, 2) }}</td>
                                            <td class="py-4">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize {{ $payment->payment_status === 'completed' ? 'bg-green-50 text-green-700' : ($payment->payment_status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                                    {{ $payment->payment_status }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-right text-xs text-slate-400">{{ $payment->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
