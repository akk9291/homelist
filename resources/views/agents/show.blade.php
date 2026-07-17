@extends('layouts.app')

@section('title', $agent->name . ' - Expert Real Estate Agent | Homelist')

@section('content')
<!-- Agent Header / Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-primary-950 text-white py-16 px-4">
    <div class="absolute top-1/4 -right-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <!-- Agent Avatar -->
            <div class="relative group">
                <div class="absolute -inset-1.5 bg-gradient-to-tr from-primary-500 to-teal-400 rounded-full blur opacity-75 group-hover:opacity-100 transition duration-300"></div>
                <div class="relative w-36 h-36 md:w-44 md:h-44 bg-slate-800 rounded-full overflow-hidden border-4 border-slate-900">
                    @if($agent->agent && $agent->agent->photo)
                        <img src="{{ $agent->agent->photo }}" alt="{{ $agent->name }}" class="w-full h-full object-cover">
                    @else
                        <!-- Default avatar with initials -->
                        <div class="w-full h-full bg-gradient-to-tr from-primary-600 to-teal-500 flex items-center justify-center text-white text-5xl font-bold">
                            {{ substr($agent->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Agent Bio Info -->
            <div class="text-center md:text-left flex-grow">
                <span class="bg-primary-500/20 text-primary-400 font-semibold px-4 py-1.5 rounded-full text-xs border border-primary-500/30 uppercase tracking-wider mb-3 inline-block">Partner Agent</span>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-3">{{ $agent->name }}</h1>
                <p class="text-slate-300 text-lg max-w-2xl font-light mb-6">
                    Professional Real Estate Consultant at Homelist Real Estate Portal. Helping you find your perfect space.
                </p>

                <!-- Stats Badges -->
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                    <span class="bg-white/10 backdrop-blur-md border border-white/10 rounded-xl px-4 py-2 text-sm text-slate-200">
                        <strong class="text-white">{{ $properties->total() }}</strong> Properties Listed
                    </span>
                    <span class="bg-white/10 backdrop-blur-md border border-white/10 rounded-xl px-4 py-2 text-sm text-slate-200">
                        Active in <strong class="text-white">Indore & Nagpur</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Profile Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-xl font-bold text-darkslate border-b border-slate-100 pb-4">Contact Details</h3>
                
                <div class="space-y-4">
                    <!-- Phone -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                            <span class="text-slate-800 font-semibold text-base">{{ $agent->agent->phone ?? '+91 98265 23333' }}</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
                            <a href="mailto:{{ $agent->email }}" class="text-primary-600 hover:text-primary-700 font-semibold text-base">{{ $agent->email }}</a>
                        </div>
                    </div>

                    <!-- Agency -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Office Agency</span>
                            <span class="text-slate-800 font-semibold text-base">Homelist HQ, Indore</span>
                        </div>
                    </div>
                </div>

                <!-- Inquiry Form -->
                <div class="border-t border-slate-100 pt-6">
                    <h4 class="font-bold text-darkslate mb-4">Send a Direct Message</h4>
                    <form action="{{ route('properties.inquiry') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                        
                        <input type="text" name="name" required placeholder="Your Name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <input type="email" name="email" required placeholder="Email Address" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <input type="text" name="phone" placeholder="Phone Number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        <textarea name="message" required rows="4" placeholder="How can I help you?" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors"></textarea>
                        
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition-all shadow-md shadow-primary-600/10">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bio & Listings -->
        <div class="lg:col-span-2 space-y-12">
            <!-- About Section -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h2 class="text-2xl font-bold text-darkslate">About {{ $agent->name }}</h2>
                <div class="text-slate-600 leading-relaxed space-y-4 text-base font-light">
                    @if($agent->agent && $agent->agent->about)
                        <p>{!! nl2br(e($agent->agent->about)) !!}</p>
                    @else
                        <p>This agent is a fully verified premium listing consultant at Homelist. They specialize in high-yield residential duplexes, prime plot lands, and modern commercial options in major growing areas.</p>
                        <p>With years of experience in local markets, they ensure seamless transactions and end-to-end guidance for all buyers and lease seekers.</p>
                    @endif
                </div>
            </div>

            <!-- Listings Section -->
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-darkslate">Properties Listed By {{ $agent->name }}</h3>

                @if($properties->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($properties as $property)
                            @include('properties._card', ['property' => $property])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="bg-slate-50 rounded-2xl p-12 text-center border border-dashed border-slate-200">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <h4 class="text-lg font-semibold text-slate-700">No Listings Found</h4>
                        <p class="text-slate-500 mt-2">This agent doesn't have any active property listings at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
