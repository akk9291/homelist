<div class="min-h-screen bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Customer Portal</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your properties, wishlist, compare options, bookings, and support tickets.</p>
            </div>
            <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm w-fit">
                <img src="{{ $avatarUrl ?: 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(auth()->user()->email))) . '?d=mp' }}" class="w-10 h-10 rounded-full object-cover border border-amber-500">
                <div>
                    <span class="font-bold text-slate-900 dark:text-white block text-sm">{{ auth()->user()->name }}</span>
                    <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-400 text-[10px] font-black uppercase px-2 py-0.5 rounded-md tracking-wider">Premium Client</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-5 h-fit space-y-1.5 transition-all duration-300">
                <nav class="space-y-1">
                    <!-- Overview -->
                    <button wire:click="$set('activeTab', 'overview')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'overview' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        Dashboard
                    </button>

                    <!-- Profile -->
                    <button wire:click="$set('activeTab', 'profile')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'profile' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profile Settings
                    </button>

                    <!-- Wishlist -->
                    <button wire:click="$set('activeTab', 'wishlist')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'wishlist' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Wishlist
                    </button>

                    <!-- Compare -->
                    <button wire:click="$set('activeTab', 'compare')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'compare' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                        Property Compare
                    </button>

                    <!-- Saved Searches -->
                    <button wire:click="$set('activeTab', 'saved_searches')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'saved_searches' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Saved Searches
                    </button>

                    <!-- Bookings -->
                    <button wire:click="$set('activeTab', 'bookings')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'bookings' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Bookings History
                    </button>

                    <!-- Support Tickets -->
                    <button wire:click="$set('activeTab', 'tickets')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'tickets' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Support Tickets
                    </button>

                    <!-- Subscriptions -->
                    <button wire:click="$set('activeTab', 'subscriptions')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'subscriptions' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Billing & Subscriptions
                    </button>

                    <!-- Calculators -->
                    <button wire:click="$set('activeTab', 'calculators')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'calculators' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        EMI Calculator
                    </button>

                    <!-- KYC Upload -->
                    <button wire:click="$set('activeTab', 'kyc')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'kyc' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        KYC Documents
                    </button>

                    <!-- Settings -->
                    <button wire:click="$set('activeTab', 'settings')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'settings' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Settings
                    </button>

                    <!-- Logout -->
                    <a href="{{ route('logout') }}" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-all duration-300">
                @if (session()->has('success'))
                    <div class="bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 p-4 rounded-2xl text-sm border border-green-100 dark:border-green-800/50 mb-6 font-semibold flex items-center gap-2">
                        <span>✓</span> {{ session('success') }}
                    </div>
                @endif

                <!-- OVERVIEW TAB -->
                @if($activeTab === 'overview')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Dashboard Overview</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Wishlisted</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $wishlist->count() }}</span>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">My Bookings</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $bookings->count() }}</span>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Support Tickets</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $tickets->count() }}</span>
                            </div>
                            <div class="bg-green-50 dark:bg-green-950/20 border border-green-100 dark:border-green-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Saved Searches</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $savedSearches->count() }}</span>
                            </div>
                        </div>

                        <!-- Recently Viewed properties -->
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Recently Viewed Properties</h3>
                        @if($recentlyViewed->isEmpty())
                            <div class="text-center py-8 bg-slate-50 dark:bg-slate-700/20 rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-sm">
                                You haven't viewed any properties recently.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($recentlyViewed as $prop)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-4 flex gap-4 items-center">
                                        <img src="{{ $prop->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=300&q=80' }}" class="w-16 h-16 rounded-xl object-cover">
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-slate-900 dark:text-white text-sm line-clamp-1">{{ $prop->title }}</h4>
                                            <span class="text-amber-500 font-bold text-xs">₹{{ number_format($prop->price) }}</span>
                                        </div>
                                        <a href="{{ route('properties.show', $prop->slug) }}" class="text-xs font-bold text-slate-500 hover:text-amber-500 transition-colors">Details</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- PROFILE SETTINGS TAB -->
                @if($activeTab === 'profile')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Profile Settings</h2>
                        <form wire:submit.prevent="updateProfile" class="space-y-6">
                            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-700">
                                <div class="relative">
                                    <img src="{{ $photo ? $photo->temporaryUrl() : ($avatarUrl ?: 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(auth()->user()->email))) . '?d=mp') }}" class="w-24 h-24 rounded-full object-cover border border-slate-200 dark:border-slate-700">
                                    <label class="absolute bottom-0 right-0 bg-amber-500 hover:bg-amber-600 text-white rounded-full p-2 cursor-pointer shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <input type="file" wire:model="photo" class="hidden">
                                    </label>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-lg">Your Profile Photo</h4>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Upload a high resolution JPEG or PNG profile image.</p>
                                    @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                                    <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors text-slate-900 dark:text-white">
                                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                                    <input type="email" wire:model="email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors text-slate-900 dark:text-white">
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-wider">Change Password</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">New Password</label>
                                        <input type="password" wire:model="password" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors text-slate-900 dark:text-white">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
                                        <input type="password" wire:model="password_confirmation" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors text-slate-900 dark:text-white">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors">
                                    Save Profile Changes
                                </button>
                            </div>
                        </form>

                        <div class="border-t border-red-100 dark:border-red-950/30 pt-8 mt-12">
                            <h3 class="text-lg font-black text-red-500 mb-2">Delete Account</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Once you delete your account, all of your data will be permanently removed. This action is irreversible.</p>
                            <button onclick="confirm('Are you absolutely sure you want to delete your account? This cannot be undone.') || event.stopImmediatePropagation()" wire:click="deleteAccount" class="bg-red-50 hover:bg-red-100 dark:bg-red-950/20 text-red-500 font-bold px-6 py-3 rounded-xl border border-red-200 dark:border-red-900/50 transition-colors">
                                Delete My Account
                            </button>
                        </div>
                    </div>
                @endif

                <!-- WISHLIST TAB -->
                @if($activeTab === 'wishlist')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">My Wishlist</h2>
                        @if($wishlist->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                You haven't added any properties to your wishlist yet.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($wishlist as $prop)
                                    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between group">
                                        <div class="relative aspect-[16/10] bg-slate-100">
                                            <img src="{{ $prop->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <button wire:click="removeFromWishlist({{ $prop->id }})" class="absolute top-3 right-3 bg-white/95 dark:bg-slate-800/95 text-red-500 p-2 rounded-full hover:scale-105 transition-all shadow-md">
                                                ✕
                                            </button>
                                        </div>
                                        <div class="p-5 flex-grow flex flex-col justify-between">
                                            <div>
                                                <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight line-clamp-1">{{ $prop->title }}</h3>
                                                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">{{ $prop->address }}</p>
                                            </div>
                                            <div class="flex justify-between items-center border-t border-slate-50 dark:border-slate-700 pt-4 mt-4">
                                                <span class="font-black text-amber-500 text-lg">₹{{ number_format($prop->price) }}</span>
                                                <a href="{{ route('properties.show', $prop->slug) }}" class="text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-amber-500 dark:hover:text-amber-400 transition-colors">View →</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- PROPERTY COMPARE TAB -->
                @if($activeTab === 'compare')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Compare Properties</h2>
                        @if($compareProperties->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                No properties in compare list. Browse properties and click "Add to Compare".
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[600px]">
                                    <thead>
                                        <tr class="border-b border-slate-100 dark:border-slate-700">
                                            <th class="py-4 font-bold text-slate-400 text-sm">Feature</th>
                                            @foreach($compareProperties as $prop)
                                                <th class="py-4 px-4">
                                                    <div class="flex flex-col">
                                                        <img src="{{ $prop->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=300&q=80' }}" class="w-24 h-16 rounded-lg object-cover mb-2">
                                                        <span class="font-bold text-slate-900 dark:text-white text-sm line-clamp-1">{{ $prop->title }}</span>
                                                        <button wire:click="removeFromCompare({{ $prop->id }})" class="text-left text-xs font-semibold text-red-500 hover:text-red-600 mt-1">Remove</button>
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                                        <tr>
                                            <td class="py-4 font-bold text-slate-400">Price</td>
                                            @foreach($compareProperties as $prop)
                                                <td class="py-4 px-4 font-black text-amber-500">₹{{ number_format($prop->price) }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="py-4 font-bold text-slate-400">Area</td>
                                            @foreach($compareProperties as $prop)
                                                <td class="py-4 px-4 font-bold">{{ $prop->area }} Sq. Ft.</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="py-4 font-bold text-slate-400">Beds / Baths</td>
                                            @foreach($compareProperties as $prop)
                                                <td class="py-4 px-4 font-bold">{{ $prop->bedrooms }} Beds / {{ $prop->bathrooms }} Baths</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="py-4 font-bold text-slate-400">Location</td>
                                            @foreach($compareProperties as $prop)
                                                <td class="py-4 px-4 text-xs font-semibold text-slate-500">{{ $prop->city?->name }}, {{ $prop->state?->name }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="py-4 font-bold text-slate-400">Amenities</td>
                                            @foreach($compareProperties as $prop)
                                                <td class="py-4 px-4">
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($prop->amenities as $amenity)
                                                            <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] px-2 py-0.5 rounded">{{ $amenity->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- SAVED SEARCHES TAB -->
                @if($activeTab === 'saved_searches')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Saved Searches</h2>
                        @if($savedSearches->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                You haven't saved any searches yet.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($savedSearches as $search)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-white text-base">{{ $search->name }}</h4>
                                            <p class="text-xs text-slate-400 mt-1">Filters: 
                                                @foreach($search->query_params as $key => $val)
                                                    <span class="font-semibold text-slate-500">{{ $key }}:</span> {{ is_array($val) ? implode(', ', $val) : $val }}; 
                                                @endforeach
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button wire:click="toggleSearchNotifications({{ $search->id }})" class="px-4 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 font-semibold flex items-center gap-1.5 {{ $search->notifications_enabled ? 'bg-amber-50 dark:bg-amber-950/20 border-amber-300 text-amber-700 dark:text-amber-400' : 'bg-white dark:bg-slate-800 text-slate-500' }}">
                                                🔔 {{ $search->notifications_enabled ? 'Alerts Enabled' : 'Alerts Disabled' }}
                                            </button>
                                            <button onclick="confirm('Delete saved search?') || event.stopImmediatePropagation()" wire:click="deleteSavedSearch({{ $search->id }})" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition-colors">
                                                🗑️
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- BOOKINGS TAB -->
                @if($activeTab === 'bookings')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Booking Queries & Site Visits</h2>
                        @if($bookings->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                You haven't booked any site visits yet.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($bookings as $booking)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        <div class="flex gap-4">
                                            <img src="{{ $booking->property->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=150&q=80' }}" class="w-16 h-16 rounded-xl object-cover">
                                            <div>
                                                <h4 class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $booking->property->title }}</h4>
                                                <div class="flex gap-3 text-xs text-slate-400 dark:text-slate-500 mt-1">
                                                    <span>📅 {{ $booking->booking_date->format('d M Y') }}</span>
                                                    <span>⏰ {{ $booking->time_slot }}</span>
                                                </div>
                                                @if($booking->notes)
                                                    <p class="text-xs text-slate-500 mt-2 italic">Notes: "{{ $booking->notes }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-start">
                                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-950/30 dark:text-green-400' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-950/30 dark:text-red-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/30 dark:text-amber-400') }}">
                                                {{ $booking->status }}
                                            </span>
                                            @if($booking->status === 'pending')
                                                <button onclick="confirm('Cancel this visit booking?') || event.stopImmediatePropagation()" wire:click="cancelBooking({{ $booking->id }})" class="text-xs font-bold text-red-500 hover:underline">Cancel Booking</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- SUPPORT TICKETS TAB -->
                @if($activeTab === 'tickets')
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Support Tickets</h2>
                            @if($selectedTicket)
                                <button wire:click="$set('selectedTicket', null)" class="text-xs font-bold text-slate-500 hover:text-amber-500 transition-colors">← Back to List</button>
                            @endif
                        </div>

                        @if(!$selectedTicket)
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Create Ticket Form -->
                                <div class="lg:col-span-1 bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 h-fit">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4">Open New Ticket</h3>
                                    <form wire:submit.prevent="createTicket" class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-1">Subject</label>
                                            <input type="text" wire:model="ticketSubject" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                            @error('ticketSubject') <span class="text-red-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-1">Category</label>
                                            <select wire:model="ticketCategory" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                                <option value="general">General Inquiry</option>
                                                <option value="billing">Billing & Invoice</option>
                                                <option value="technical">Technical Issue</option>
                                                <option value="sales">Sales & Upgrades</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-1">Priority</label>
                                            <select wire:model="ticketPriority" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-1">Message Description</label>
                                            <textarea wire:model="ticketDescription" rows="4" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                                            @error('ticketDescription') <span class="text-red-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                                        </div>
                                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl shadow-md transition-colors text-sm">
                                            Submit Ticket
                                        </button>
                                    </form>
                                </div>

                                <!-- Tickets List -->
                                <div class="lg:col-span-2 space-y-4">
                                    @if($tickets->isEmpty())
                                        <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-sm">
                                            No support tickets submitted.
                                        </div>
                                    @else
                                        @foreach($tickets as $ticket)
                                            <div wire:click="selectTicket({{ $ticket->id }})" class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 hover:border-amber-500 dark:hover:border-amber-500 transition-colors cursor-pointer flex justify-between items-start">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1.5">
                                                        <span class="font-black text-slate-900 dark:text-white text-sm">{{ $ticket->ticket_number }}</span>
                                                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : ($ticket->priority === 'medium' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">{{ $ticket->priority }}</span>
                                                    </div>
                                                    <h4 class="font-bold text-slate-750 dark:text-slate-350 text-base leading-tight mb-2">{{ $ticket->subject }}</h4>
                                                    <span class="text-slate-400 text-xs">Category: <span class="capitalize font-semibold text-slate-500">{{ $ticket->category }}</span> • Opened on {{ $ticket->created_at->format('d M Y') }}</span>
                                                </div>
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $ticket->status === 'closed' ? 'bg-slate-200 text-slate-700' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $ticket->status }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Ticket Detailed view & Conversation -->
                            <div class="space-y-6">
                                <div class="bg-slate-50 dark:bg-slate-900/20 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 flex flex-col sm:flex-row justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-lg font-black text-slate-900 dark:text-white">{{ $selectedTicket->ticket_number }}</h3>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider {{ $selectedTicket->status === 'closed' ? 'bg-slate-200' : 'bg-amber-100 text-amber-800' }}">{{ $selectedTicket->status }}</span>
                                        </div>
                                        <h4 class="font-bold text-slate-700 dark:text-slate-350 text-xl leading-tight mb-3">{{ $selectedTicket->subject }}</h4>
                                        <p class="text-slate-600 dark:text-slate-400 text-sm whitespace-pre-line bg-white dark:bg-slate-850 p-4 rounded-xl border border-slate-100 dark:border-slate-750 shadow-inner">
                                            {{ $selectedTicket->description }}
                                        </p>
                                    </div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500 font-semibold space-y-1.5 min-w-[150px]">
                                        <div>Category: <span class="capitalize text-slate-600 dark:text-slate-400">{{ $selectedTicket->category }}</span></div>
                                        <div>Priority: <span class="capitalize text-slate-600 dark:text-slate-400">{{ $selectedTicket->priority }}</span></div>
                                        <div>Opened: <span class="text-slate-600 dark:text-slate-400">{{ $selectedTicket->created_at->format('d M Y, h:i A') }}</span></div>
                                    </div>
                                </div>

                                <!-- Replies Chat style -->
                                <div class="space-y-4">
                                    <h4 class="font-black text-slate-900 dark:text-white text-base border-b border-slate-100 dark:border-slate-700 pb-2">Ticket Activity</h4>
                                    
                                    @if($selectedTicket->replies->isEmpty())
                                        <p class="text-slate-400 text-sm text-center py-6">No updates on this ticket yet. Admin will reply soon.</p>
                                    @else
                                        @foreach($selectedTicket->replies as $reply)
                                            <div class="flex gap-4 p-4 rounded-2xl border {{ $reply->user_id === auth()->id() ? 'bg-amber-50/50 dark:bg-amber-950/10 border-amber-100 dark:border-amber-900/50' : 'bg-slate-50 dark:bg-slate-900/10 border-slate-100 dark:border-slate-800' }}">
                                                <img src="{{ $reply->user->avatar_url ?: 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($reply->user->email))) . '?d=mp' }}" class="w-10 h-10 rounded-full object-cover">
                                                <div class="flex-grow">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $reply->user->name }}</span>
                                                        <span class="text-slate-400 text-[10px]">{{ $reply->created_at->format('d M Y, h:i A') }}</span>
                                                    </div>
                                                    <p class="text-slate-700 dark:text-slate-350 text-sm whitespace-pre-line">{{ $reply->message }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Submit Reply -->
                                <form wire:submit.prevent="replyToTicket" class="space-y-3 bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Reply message</label>
                                    <textarea wire:model="replyMessage" rows="3" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-750 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                                    @error('replyMessage') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition-colors text-sm">
                                        Send Message
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- BILLING & SUBSCRIPTIONS TAB -->
                @if($activeTab === 'subscriptions')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Subscriptions & Payments</h2>

                        @if(auth()->user()->activeSubscription)
                            <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 rounded-2xl p-6 mb-8 flex flex-col justify-between gap-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <span class="text-xs font-semibold text-amber-800 dark:text-amber-400 uppercase tracking-wider block">Current Active Plan</span>
                                        <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ auth()->user()->activeSubscription->subscriptionPlan->name }}</span>
                                        <span class="text-slate-500 dark:text-slate-400 text-xs block mt-1">Expiry Date: {{ auth()->user()->activeSubscription->ends_at ? auth()->user()->activeSubscription->ends_at->format('d M Y') : 'Lifetime Access' }}</span>
                                    </div>
                                    <span class="bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-400 text-xs font-black px-3 py-1.5 rounded-lg flex items-center gap-1 w-fit">● Active Subscription</span>
                                </div>
                                
                                <div class="border-t border-amber-200/50 dark:border-amber-900/50 pt-4">
                                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-3">Included Features & Limits</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="bg-white dark:bg-slate-800/55 p-3 rounded-xl border border-amber-100/50 dark:border-amber-900/20">
                                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Property Limit</span>
                                            <span class="text-sm font-black text-slate-800 dark:text-white mt-0.5 block">{{ auth()->user()->activeSubscription->subscriptionPlan->property_limit }} Listings</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800/55 p-3 rounded-xl border border-amber-100/50 dark:border-amber-900/20">
                                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Featured Limit</span>
                                            <span class="text-sm font-black text-slate-800 dark:text-white mt-0.5 block">{{ auth()->user()->activeSubscription->subscriptionPlan->featured_limit }} Featured</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800/55 p-3 rounded-xl border border-amber-100/50 dark:border-amber-900/20">
                                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Image Limit</span>
                                            <span class="text-sm font-black text-slate-800 dark:text-white mt-0.5 block">Max {{ auth()->user()->activeSubscription->subscriptionPlan->image_limit }} / Prop</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800/55 p-3 rounded-xl border border-amber-100/50 dark:border-amber-900/20">
                                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Video Limit</span>
                                            <span class="text-sm font-black text-slate-800 dark:text-white mt-0.5 block">Max {{ auth()->user()->activeSubscription->subscriptionPlan->video_limit }} / Prop</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block text-lg">No Active Subscription Plan</span>
                                    <span class="text-slate-400 text-xs block mt-1">Upgrade your account to access advanced analytics and premium property bookings.</span>
                                </div>
                                <a href="{{ route('subscription.checkout') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-md">Browse Plans</a>
                            </div>
                        @endif

                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-wider">Invoice History</h3>
                        @if($payments->isEmpty())
                            <div class="text-center py-8 bg-slate-50 dark:bg-slate-700/20 rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-sm">
                                No invoice payments found.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-100 dark:border-slate-700 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                            <th class="pb-3">Invoice ID</th>
                                            <th class="pb-3">Upgrade Plan</th>
                                            <th class="pb-3">Payment Gateway</th>
                                            <th class="pb-3">Total Amount</th>
                                            <th class="pb-3">Status</th>
                                            <th class="pb-3 text-right">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                                        @foreach($payments as $payment)
                                            <tr class="text-slate-700 dark:text-slate-300">
                                                <td class="py-4 font-semibold text-slate-900 dark:text-white">{{ $payment->invoice_number ?: 'N/A' }}</td>
                                                <td class="py-4">{{ $payment->subscriptionPlan?->name ?: 'N/A' }}</td>
                                                <td class="py-4 capitalize">{{ $payment->payment_gateway }}</td>
                                                <td class="py-4 font-bold text-slate-900 dark:text-white">₹{{ number_format($payment->total_amount, 2) }}</td>
                                                <td class="py-4">
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize {{ $payment->payment_status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400' }}">
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

                <!-- EMI CALCULATORS TAB -->
                @if($activeTab === 'calculators')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Home Loan EMI Calculator</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Sliders -->
                            <div class="lg:col-span-2 space-y-6 bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-6 border border-slate-100 dark:border-slate-800">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Loan Amount</label>
                                        <span class="font-bold text-slate-950 dark:text-white">₹{{ number_format($loanAmount) }}</span>
                                    </div>
                                    <input type="range" wire:model.live="loanAmount" min="100000" max="100000000" step="50000" class="w-full accent-amber-500">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Interest Rate (% p.a.)</label>
                                        <span class="font-bold text-slate-950 dark:text-white">{{ $interestRate }}%</span>
                                    </div>
                                    <input type="range" wire:model.live="interestRate" min="1" max="20" step="0.1" class="w-full accent-amber-500">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Loan Term (Years)</label>
                                        <span class="font-bold text-slate-950 dark:text-white">{{ $loanTerm }} Years</span>
                                    </div>
                                    <input type="range" wire:model.live="loanTerm" min="1" max="30" step="1" class="w-full accent-amber-500">
                                </div>
                            </div>

                            <!-- Calculations breakdown -->
                            <div class="lg:col-span-1 bg-amber-500 text-white rounded-2xl p-6 shadow-lg flex flex-col justify-between">
                                <div>
                                    <span class="text-xs uppercase tracking-wider font-bold block opacity-75">Monthly EMI</span>
                                    <span class="text-3xl font-black block mt-1">₹{{ number_format($emi) }}</span>
                                </div>
                                <div class="border-t border-white/20 pt-4 mt-6 space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="opacity-80">Principal Loan</span>
                                        <span class="font-bold">₹{{ number_format($loanAmount) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="opacity-80">Total Interest Payable</span>
                                        <span class="font-bold">₹{{ number_format($totalInterest) }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-white/20 pt-2 font-bold text-base">
                                        <span>Total Payment</span>
                                        <span>₹{{ number_format($totalPayment) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- KYC UPLOAD TAB -->
                @if($activeTab === 'kyc')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">KYC Profile Verification</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Upload Form -->
                            <div class="lg:col-span-1 bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 h-fit">
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4 font-black">Upload KYC document</h3>
                                <form wire:submit.prevent="uploadKyc" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Document Type</label>
                                        <select wire:model="kycDocType" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                            <option value="aadhaar">Aadhaar Card (India)</option>
                                            <option value="pan">PAN Card (India)</option>
                                            <option value="passport">Passport</option>
                                            <option value="driving_license">Driving License</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Document Number / ID</label>
                                        <input type="text" wire:model="kycDocNumber" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                        @error('kycDocNumber') <span class="text-red-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Upload File (PDF/JPEG/PNG)</label>
                                        <input type="file" wire:model="kycFile" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none text-slate-900 dark:text-white cursor-pointer file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-amber-100 file:text-amber-800">
                                        @error('kycFile') <span class="text-red-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl shadow-md transition-colors text-sm">
                                        Upload Document
                                    </button>
                                </form>
                            </div>

                            <!-- List -->
                            <div class="lg:col-span-2 space-y-4">
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4">KYC History</h3>
                                @if($kycDocs->isEmpty())
                                    <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-sm">
                                        No KYC documents uploaded yet.
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach($kycDocs as $doc)
                                            <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 flex justify-between items-center">
                                                <div>
                                                    <span class="font-bold text-slate-900 dark:text-white text-sm capitalize">{{ $doc->document_type }}</span>
                                                    <span class="text-slate-500 dark:text-slate-400 text-xs block mt-1">ID: {{ $doc->document_number }} • Uploaded: {{ $doc->created_at->format('d M Y') }}</span>
                                                    @if($doc->rejection_reason)
                                                        <span class="text-red-500 text-xs block mt-1">Reason: "{{ $doc->rejection_reason }}"</span>
                                                    @endif
                                                </div>
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $doc->status === 'approved' ? 'bg-green-100 text-green-800' : ($doc->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                    {{ $doc->status }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- SETTINGS TAB -->
                @if($activeTab === 'settings')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Account Settings</h2>
                        
                        <div class="space-y-6 bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-6 border border-slate-100 dark:border-slate-800">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">Notification Preferences</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model="emailNotifications" class="rounded border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500 h-4.5 w-4.5">
                                        <div>
                                            <span class="font-bold text-slate-950 dark:text-white text-sm block">Email Alerts</span>
                                            <span class="text-xs text-slate-450 block">Receive instant emails on saved searches and ticket replies.</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model="smsNotifications" class="rounded border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500 h-4.5 w-4.5">
                                        <div>
                                            <span class="font-bold text-slate-950 dark:text-white text-sm block">SMS Alerts</span>
                                            <span class="text-xs text-slate-450 block">Receive phone notifications for site visit bookings.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">Privacy & Visibility</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model="profilePublic" class="rounded border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500 h-4.5 w-4.5">
                                        <div>
                                            <span class="font-bold text-slate-950 dark:text-white text-sm block">Public Profile</span>
                                            <span class="text-xs text-slate-450 block">Allow other registered users to see my name and rating on property reviews.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
