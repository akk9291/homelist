<div class="min-h-screen bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Agent Workspace</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your properties, lead inquiries, customer bookings, reviews, and agency representation.</p>
            </div>
            <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm w-fit">
                <img src="{{ $avatarUrl ?: 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(auth()->user()->email))) . '?d=mp' }}" class="w-10 h-10 rounded-full object-cover border border-amber-500">
                <div>
                    <span class="font-bold text-slate-900 dark:text-white block text-sm">{{ auth()->user()->name }}</span>
                    <span class="flex items-center gap-1.5 mt-0.5">
                        @if($agent?->kyc_status === 'verified')
                            <span class="bg-green-100 dark:bg-green-950/50 text-green-800 dark:text-green-400 text-[10px] font-black uppercase px-2 py-0.5 rounded-md tracking-wider">Verified Agent</span>
                        @elseif($agent?->kyc_status === 'pending')
                            <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-400 text-[10px] font-black uppercase px-2 py-0.5 rounded-md tracking-wider">KYC Pending</span>
                        @else
                            <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase px-2 py-0.5 rounded-md tracking-wider">Unverified</span>
                        @endif
                    </span>
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

                    <!-- Properties -->
                    <button wire:click="$set('activeTab', 'properties')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'properties' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        My Properties
                    </button>

                    <!-- Add Property -->
                    <button wire:click="initAddProperty" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'add_property' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Add New Property
                    </button>

                    <!-- Bookings -->
                    <button wire:click="$set('activeTab', 'bookings')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'bookings' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Booking Calendar
                    </button>

                    <!-- Inquiries -->
                    <button wire:click="$set('activeTab', 'inquiries')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'inquiries' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Leads & Inquiries
                    </button>

                    <!-- Reviews -->
                    <button wire:click="$set('activeTab', 'reviews')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'reviews' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.243.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.77-.57-.372-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Reviews & Replies
                    </button>

                    <!-- Agency -->
                    <button wire:click="$set('activeTab', 'agency')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'agency' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Agency Information
                    </button>

                    <!-- KYC -->
                    <button wire:click="$set('activeTab', 'kyc')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'kyc' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        KYC Upload
                    </button>

                    <!-- Profile Settings -->
                    <button wire:click="$set('activeTab', 'profile')" class="w-full text-left px-4 py-3 rounded-2xl font-bold transition-all text-sm flex items-center gap-3 {{ $activeTab === 'profile' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Agent Profile Settings
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
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Properties</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $propertiesCount }}</span>
                                <span class="text-slate-400 text-[10px] mt-1 block font-medium">{{ $publishedCount }} Active • {{ $draftCount }} Draft</span>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Leads / Inquiries</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $inquiriesCount }}</span>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Visit Bookings</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">{{ $bookingsCount }}</span>
                            </div>
                            <div class="bg-green-50 dark:bg-green-950/20 border border-green-100 dark:border-green-900/50 rounded-2xl p-5">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Wallet Balance</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white mt-1 block">₹{{ number_format($walletBalance, 2) }}</span>
                            </div>
                        </div>

                        <!-- Inquiry Quick overview -->
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Latest Inquiries</h3>
                        @if($inquiries->isEmpty())
                            <div class="text-center py-8 bg-slate-50 dark:bg-slate-700/20 rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-sm">
                                No inquiries submitted yet.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($inquiries->take(3) as $inq)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-xl p-4 flex justify-between items-center text-sm">
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white">{{ $inq->name }}</span>
                                            <span class="text-slate-400 text-xs ml-2">on {{ $inq->property?->title ?: 'Archived Property' }}</span>
                                            <p class="text-xs text-slate-500 mt-1">"{{ $inq->message }}"</p>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-400">{{ $inq->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- PROPERTIES TAB -->
                @if($activeTab === 'properties')
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">My Properties</h2>
                            <button wire:click="initAddProperty" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-md transition-colors">+ Add Property</button>
                        </div>

                        @if($properties->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                You haven't listed any properties yet.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($properties as $prop)
                                    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-750 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between group transition-all">
                                        <div class="relative aspect-[16/10] bg-slate-100">
                                            <img src="{{ $prop->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80' }}" class="w-full h-full object-cover">
                                            <span class="absolute top-3 left-3 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shadow {{ $prop->published ? 'bg-green-500 text-white' : 'bg-slate-500 text-white' }}">
                                                {{ $prop->published ? 'Published' : 'Draft' }}
                                            </span>
                                        </div>
                                        <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                                            <div>
                                                <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight line-clamp-1">{{ $prop->title }}</h3>
                                                <p class="text-slate-400 text-xs mt-1">{{ $prop->address }}, {{ $prop->city?->name }}</p>
                                                <span class="text-amber-500 font-black text-base mt-2 block">₹{{ number_format($prop->price) }}</span>
                                            </div>
                                            <div class="flex justify-between items-center border-t border-slate-50 dark:border-slate-700 pt-4 mt-2">
                                                <button wire:click="loadEditProperty({{ $prop->id }})" class="text-xs font-bold text-slate-600 dark:text-slate-350 hover:text-amber-500 transition-colors">✏️ Edit Details</button>
                                                <button onclick="confirm('Delete this property listing permanently?') || event.stopImmediatePropagation()" wire:click="deleteProperty({{ $prop->id }})" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors">🗑️ Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- ADD/EDIT PROPERTY FORM TAB -->
                @if($activeTab === 'add_property')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">{{ $propertyId ? 'Edit Property Listing' : 'Create New Property Listing' }}</h2>
                        <form wire:submit.prevent="saveProperty" class="space-y-6">
                            <!-- Basic details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Property Title</label>
                                    <input type="text" wire:model="propTitle" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('propTitle') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Price (INR ₹)</label>
                                    <input type="number" wire:model="propPrice" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('propPrice') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Description</label>
                                <textarea wire:model="propDescription" rows="5" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                                @error('propDescription') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Specs -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Bedrooms</label>
                                    <input type="number" wire:model="propBedrooms" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Bathrooms</label>
                                    <input type="number" wire:model="propBathrooms" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Area (Sq. Ft.)</label>
                                    <input type="number" wire:model="propArea" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Facing</label>
                                    <select wire:model="propFacing" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white">
                                        <option value="East">East</option>
                                        <option value="West">West</option>
                                        <option value="North">North</option>
                                        <option value="South">South</option>
                                        <option value="North-East">North-East</option>
                                        <option value="North-West">North-West</option>
                                        <option value="South-East">South-East</option>
                                        <option value="South-West">South-West</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Status, Category, Location -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Property Type</label>
                                    <select wire:model="propPropertyTypeId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white">
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Property Status</label>
                                    <select wire:model="propPropertyStatusId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white">
                                        @foreach($propertyStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Furnishing</label>
                                    <select wire:model="propFurnishing" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white">
                                        <option value="Unfurnished">Unfurnished</option>
                                        <option value="Semi-Furnished">Semi-Furnished</option>
                                        <option value="Fully Furnished">Fully Furnished</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">State</label>
                                    <select wire:model="propStateId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white">
                                        @foreach($states as $st)
                                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">City</label>
                                    <select wire:model="propCityId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white">
                                        @foreach($cities as $ct)
                                            <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Street Address</label>
                                    <input type="text" wire:model="propAddress" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white">
                                    @error('propAddress') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Media Uploads -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100 dark:border-slate-700">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Featured Image</label>
                                    @if($propFeaturedImageUrl && !$propFeaturedImageFile)
                                        <div class="mb-2 relative w-32 h-20 bg-slate-100 rounded-lg overflow-hidden border">
                                            <img src="{{ $propFeaturedImageUrl }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <input type="file" wire:model="propFeaturedImageFile" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs cursor-pointer text-slate-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Upload Gallery Images</label>
                                    <input type="file" wire:model="propGalleryFiles" multiple class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs cursor-pointer text-slate-900 dark:text-white">
                                </div>
                            </div>

                            <!-- Dynamic Custom Fields Groups -->
                            @if(isset($groupedCustomFields) && $groupedCustomFields->isNotEmpty())
                                <div class="border-t border-slate-100 dark:border-slate-700 pt-6 space-y-6">
                                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Custom Property Details</h3>
                                    
                                    @foreach($groupedCustomFields as $groupSlug => $fields)
                                        @php
                                            $group = $fields->first()->group;
                                            $groupName = $group ? $group->name : 'General Specifications';
                                        @endphp
                                        <div x-data="{ open: true }" class="border border-slate-100 dark:border-slate-750 rounded-2xl overflow-hidden bg-slate-50/30 dark:bg-slate-900/10">
                                            <button type="button" @click="open = !open" class="w-full flex justify-between items-center px-5 py-4 bg-slate-100/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-755 font-bold text-slate-900 dark:text-white text-sm">
                                                <span>{{ $groupName }}</span>
                                                <span x-show="open">▲</span>
                                                <span x-show="!open">▼</span>
                                            </button>
                                            
                                            <div x-show="open" class="p-5 grid grid-cols-1 md:grid-cols-4 gap-6">
                                                @foreach($fields as $field)
                                                    @php
                                                        // Evaluate Conditional Visibility Rules in PHP
                                                        $showField = true;
                                                        if ($field->conditional_logic && is_array($field->conditional_logic)) {
                                                            foreach ($field->conditional_logic as $cond) {
                                                                $dependSlug = $cond['depend_on_field_slug'] ?? '';
                                                                $operator = $cond['operator'] ?? 'equals';
                                                                $expectedVal = $cond['value'] ?? '';
                                                                
                                                                // Fetch current value of dependency
                                                                $currentVal = null;
                                                                if ($dependSlug === 'property_type_id') {
                                                                    $currentVal = $propPropertyTypeId;
                                                                } else {
                                                                    $currentVal = $customFieldValues[$dependSlug] ?? null;
                                                                }

                                                                $conditionMet = false;
                                                                switch ($operator) {
                                                                    case 'equals':
                                                                        $conditionMet = ((string) $currentVal === (string) $expectedVal);
                                                                        break;
                                                                    case 'not_equals':
                                                                        $conditionMet = ((string) $currentVal !== (string) $expectedVal);
                                                                        break;
                                                                    case 'contains':
                                                                        $conditionMet = (str_contains((string) $currentVal, (string) $expectedVal));
                                                                        break;
                                                                    case 'not_contains':
                                                                        $conditionMet = (!str_contains((string) $currentVal, (string) $expectedVal));
                                                                        break;
                                                                    case 'empty':
                                                                        $conditionMet = empty($currentVal);
                                                                        break;
                                                                    case 'not_empty':
                                                                        $conditionMet = !empty($currentVal);
                                                                        break;
                                                                }

                                                                if (!$conditionMet) {
                                                                    $showField = false;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    @if($showField)
                                                        @php
                                                            $colSpan = match($field->width) {
                                                                25 => 'col-span-1 md:col-span-1',
                                                                50 => 'col-span-1 md:col-span-2',
                                                                75 => 'col-span-1 md:col-span-3',
                                                                default => 'col-span-1 md:col-span-4',
                                                            };
                                                        @endphp
                                                        <div class="{{ $colSpan }}">
                                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                                                {{ $field->name }}
                                                                @if($field->required)
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </label>
                                                            
                                                            @if(in_array($field->field_type, ['text', 'url', 'email', 'phone', 'currency', 'area_unit']))
                                                                <input type="text" wire:model="customFieldValues.{{ $field->slug }}" placeholder="{{ $field->placeholder }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                            @elseif($field->field_type === 'number')
                                                                <input type="number" wire:model="customFieldValues.{{ $field->slug }}" placeholder="{{ $field->placeholder }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                            @elseif($field->field_type === 'decimal')
                                                                <input type="number" step="0.01" wire:model="customFieldValues.{{ $field->slug }}" placeholder="{{ $field->placeholder }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                            @elseif($field->field_type === 'textarea')
                                                                <textarea wire:model="customFieldValues.{{ $field->slug }}" placeholder="{{ $field->placeholder }}" rows="3" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500"></textarea>
                                                            @elseif($field->field_type === 'richtext')
                                                                <textarea wire:model="customFieldValues.{{ $field->slug }}" placeholder="{{ $field->placeholder }}" rows="4" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500"></textarea>
                                                            @elseif($field->field_type === 'date')
                                                                <input type="date" wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                            @elseif($field->field_type === 'datetime')
                                                                <input type="datetime-local" wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                            @elseif($field->field_type === 'time')
                                                                <input type="time" wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                            @elseif($field->field_type === 'color')
                                                                <input type="color" wire:model="customFieldValues.{{ $field->slug }}" class="w-12 h-10 bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl p-1 text-sm text-slate-900 dark:text-white focus:outline-none cursor-pointer">
                                                            @elseif($field->field_type === 'toggle')
                                                                <label class="flex items-center gap-2 cursor-pointer mt-2">
                                                                    <input type="checkbox" wire:model="customFieldValues.{{ $field->slug }}" value="1" class="rounded text-amber-500 focus:ring-amber-500">
                                                                    <span class="text-xs text-slate-500 dark:text-slate-400">Enabled</span>
                                                                </label>
                                                            @elseif(in_array($field->field_type, ['select', 'radio']))
                                                                <select wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                                                                    <option value="">Select option</option>
                                                                    @if($field->options_json)
                                                                        @foreach($field->options_json as $opt)
                                                                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            @elseif($field->field_type === 'multiselect')
                                                                <select multiple wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 min-h-[80px]">
                                                                    @if($field->options_json)
                                                                        @foreach($field->options_json as $opt)
                                                                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            @elseif($field->field_type === 'checkbox')
                                                                <div class="grid grid-cols-2 gap-2 mt-1">
                                                                    @if($field->options_json)
                                                                        @foreach($field->options_json as $opt)
                                                                            <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                                                                <input type="checkbox" wire:model="customFieldValues.{{ $field->slug }}" value="{{ $opt['value'] }}" class="rounded text-amber-500 focus:ring-amber-500">
                                                                                <span>{{ $opt['label'] }}</span>
                                                                            </label>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            @elseif(in_array($field->field_type, ['file', 'image']))
                                                                @if(isset($customFieldValues[$field->slug]) && is_string($customFieldValues[$field->slug]))
                                                                    <div class="mb-2 text-xs font-semibold text-amber-600">
                                                                        Attached File: <a href="{{ $customFieldValues[$field->slug] }}" target="_blank" class="underline">View Attached File</a>
                                                                    </div>
                                                                @endif
                                                                <input type="file" wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs cursor-pointer text-slate-900 dark:text-white">
                                                            @elseif(in_array($field->field_type, ['multiple_file', 'multiple_image']))
                                                                @if(isset($customFieldValues[$field->slug]) && is_array($customFieldValues[$field->slug]))
                                                                    <div class="mb-2 space-y-1">
                                                                        @foreach($customFieldValues[$field->slug] as $fpath)
                                                                            @if(is_string($fpath))
                                                                                <div class="text-xs font-semibold text-amber-600">
                                                                                    Attached File: <a href="{{ $fpath }}" target="_blank" class="underline">{{ basename($fpath) }}</a>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                                <input type="file" multiple wire:model="customFieldValues.{{ $field->slug }}" class="w-full bg-white dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs cursor-pointer text-slate-900 dark:text-white">
                                                            @endif
                                                            
                                                            @error('customFieldValues.' . $field->slug)
                                                                <span class="text-red-500 text-[10px] mt-0.5 block">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Amenities Checkbox Grid -->
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-wider">Amenities</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($allAmenities as $amenity)
                                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                                            <input type="checkbox" wire:model="propSelectedAmenities" value="{{ $amenity->id }}" class="rounded text-amber-500 focus:ring-amber-500">
                                            <span>{{ $amenity->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Publish toggle -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" wire:model="propPublished" id="propPublished" class="rounded text-amber-500 focus:ring-amber-500 h-5 w-5">
                                <label for="propPublished" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Publish listing immediately (make it public)</label>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors">
                                    Save Property Listing
                                </button>
                                <button type="button" wire:click="$set('activeTab', 'properties')" class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-250 font-semibold px-6 py-3 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-650 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- BOOKING CALENDAR TAB -->
                @if($activeTab === 'bookings')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Booking Calendar & Site Visits</h2>
                        @if($bookings->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                No site visit bookings scheduled by customers yet.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($bookings as $booking)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $booking->property->title }}</h4>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                    {{ $booking->status }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Booked by: {{ $booking->user->name }} ({{ $booking->user->email }})</p>
                                            <div class="flex gap-4 text-xs text-slate-400 mt-2 font-medium">
                                                <span>📅 {{ $booking->booking_date->format('d M Y') }}</span>
                                                <span>⏰ {{ $booking->time_slot }}</span>
                                            </div>
                                            @if($booking->notes)
                                                <p class="text-xs text-slate-500 italic mt-2">Notes: "{{ $booking->notes }}"</p>
                                            @endif
                                        </div>
                                        
                                        @if($booking->status === 'pending')
                                            <div class="flex gap-2 w-full md:w-auto">
                                                <button wire:click="updateBookingStatus({{ $booking->id }}, 'confirmed')" class="flex-grow md:flex-grow-0 bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-colors">Approve</button>
                                                <button wire:click="updateBookingStatus({{ $booking->id }}, 'cancelled')" class="flex-grow md:flex-grow-0 bg-red-500 hover:bg-red-600 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-colors">Decline</button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- LEADS / INQUIRIES TAB -->
                @if($activeTab === 'inquiries')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Property Leads & inquiries</h2>
                        @if($inquiries->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                No inquiries submitted on your properties yet.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($inquiries as $inq)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5">
                                        <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                                            <div>
                                                <h4 class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $inq->name }}</h4>
                                                <p class="text-xs text-slate-400 mt-0.5">Email: <span class="font-semibold text-slate-500">{{ $inq->email }}</span> | Phone: <span class="font-semibold text-slate-500">{{ $inq->phone ?: 'N/A' }}</span></p>
                                            </div>
                                            <span class="text-slate-400 text-xs font-semibold">{{ $inq->created_at->format('d M Y, h:i A') }}</span>
                                        </div>
                                        <p class="text-slate-700 dark:text-slate-350 text-sm italic bg-white dark:bg-slate-850 p-3 rounded-xl border border-slate-100 dark:border-slate-750 mt-3">"{{ $inq->message }}"</p>
                                        <span class="text-[10px] font-black uppercase text-amber-500 mt-3 block">Property: {{ $inq->property?->title ?: 'Archived Listing' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- REVIEWS & REPLIES TAB -->
                @if($activeTab === 'reviews')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Customer Reviews & Replies</h2>
                        @if($reviews->isEmpty())
                            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/20 rounded-3xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500">
                                No reviews left on your properties yet.
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($reviews as $rev)
                                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
                                        <div class="flex justify-between items-start flex-wrap gap-2">
                                            <div>
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $rev->user->name }}</span>
                                                <span class="text-slate-400 text-xs block">Property: <span class="font-semibold text-slate-500">{{ $rev->property->title }}</span></span>
                                            </div>
                                            <div class="flex items-center gap-1.5 bg-amber-100 dark:bg-amber-950/20 text-amber-800 dark:text-amber-400 text-xs font-black px-2 py-0.5 rounded">
                                                ★ {{ $rev->rating }} / 5
                                            </div>
                                        </div>
                                        <p class="text-slate-700 dark:text-slate-350 text-sm whitespace-pre-line bg-white dark:bg-slate-850 p-4 rounded-xl border border-slate-100 dark:border-slate-750">
                                            {{ $rev->comment }}
                                        </p>

                                        <!-- Reply displaying -->
                                        @if($rev->reply)
                                            <div class="bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/30 p-4 rounded-xl">
                                                <span class="font-bold text-amber-850 dark:text-amber-400 text-xs block">Your Reply:</span>
                                                <p class="text-slate-650 dark:text-slate-350 text-xs mt-1">{{ $rev->reply }}</p>
                                            </div>
                                        @endif

                                        <!-- Reply form toggling -->
                                        @if($selectedReviewId === $rev->id)
                                            <form wire:submit.prevent="replyToReview" class="space-y-2 mt-3">
                                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Write reply</label>
                                                <textarea wire:model="reviewReplyText" rows="2" class="w-full bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-750 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                                                @error('reviewReplyText') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                                                <div class="flex gap-2">
                                                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-1.5 rounded-lg text-xs shadow">Submit Reply</button>
                                                    <button type="button" wire:click="$set('selectedReviewId', null)" class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold px-4 py-1.5 rounded-lg text-xs">Cancel</button>
                                                </div>
                                            </form>
                                        @elseif(!$rev->reply)
                                            <button wire:click="selectReview({{ $rev->id }})" class="text-xs font-bold text-amber-500 hover:underline">Submit Reply</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- AGENCY INFORMATION TAB -->
                @if($activeTab === 'agency')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Agency Information</h2>
                        <form wire:submit.prevent="updateAgency" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Agency Name</label>
                                    <input type="text" wire:model="agencyName" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('agencyName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Agency Email</label>
                                    <input type="email" wire:model="agencyEmail" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('agencyEmail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Agency Phone</label>
                                    <input type="text" wire:model="agencyPhone" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('agencyPhone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Agency Website</label>
                                    <input type="text" wire:model="agencyWebsite" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('agencyWebsite') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Agency Address</label>
                                <textarea wire:model="agencyAddress" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                                @error('agencyAddress') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">About Agency</label>
                                <textarea wire:model="agencyAbout" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                            </div>

                            <div>
                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-colors">
                                    Save Agency Information
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- KYC UPLOAD TAB -->
                @if($activeTab === 'kyc')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">KYC Verification Status</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Form -->
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

                            <!-- KYC Logs -->
                            <div class="lg:col-span-2 space-y-4">
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4 font-black">KYC Verification Status</h3>
                                <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 mb-6">
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Verification Badge Status</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2 mt-1">
                                        @if($agent?->kyc_status === 'verified')
                                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span> Verified Agent Badge is Active
                                        @elseif($agent?->kyc_status === 'pending')
                                            <span class="w-2.5 h-2.5 bg-amber-500 rounded-full"></span> Verification Pending (Admin Review)
                                        @else
                                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> Unverified Profile
                                        @endif
                                    </span>
                                </div>

                                <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-3">KYC Document Logs</h4>
                                @if($kycDocs->isEmpty())
                                    <div class="text-center py-8 bg-slate-50 dark:bg-slate-700/20 rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-sm">
                                        No documents submitted.
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach($kycDocs as $doc)
                                            <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 flex justify-between items-center text-sm">
                                                <div>
                                                    <span class="font-bold text-slate-900 dark:text-white capitalize">{{ $doc->document_type }}</span>
                                                    <span class="text-slate-500 text-xs block mt-0.5">ID: {{ $doc->document_number }} • Uploaded: {{ $doc->created_at->format('d M Y') }}</span>
                                                    @if($doc->rejection_reason)
                                                        <span class="text-red-500 text-xs block mt-1">Reason: "{{ $doc->rejection_reason }}"</span>
                                                    @endif
                                                </div>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $doc->status === 'approved' ? 'bg-green-100 text-green-800' : ($doc->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
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

                <!-- AGENT PROFILE SETTINGS TAB -->
                @if($activeTab === 'profile')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Agent Profile Settings</h2>
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
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Upload a high resolution profile picture for public listing pages.</p>
                                    @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                                    <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                                    <input type="email" wire:model="email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Public Phone Number</label>
                                    <input type="text" wire:model="phone" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                    @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">About Me (Bio)</label>
                                    <textarea wire:model="about" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white"></textarea>
                                    @error('about') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-wider">Change Password</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">New Password</label>
                                        <input type="password" wire:model="password" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
                                        <input type="password" wire:model="password_confirmation" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 text-slate-900 dark:text-white">
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

                <!-- SETTINGS TAB -->
                @if($activeTab === 'settings')
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Settings & Security</h2>
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 space-y-6">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2">Notification Settings</h3>
                                <p class="text-xs text-slate-400 mb-4">Toggle notifications for new enquiries and property bookings.</p>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" checked class="rounded border-slate-350 text-amber-500 focus:ring-amber-500 h-4.5 w-4.5">
                                    <span class="text-sm font-semibold">Enable email alerts for new leads & inquiries</span>
                                </label>
                            </div>
                            <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
                                <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2">Two-Factor Authentication</h3>
                                <p class="text-xs text-slate-400 mb-4">Add an extra layer of security to your agent workspace.</p>
                                <button class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow">Enable 2FA</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
