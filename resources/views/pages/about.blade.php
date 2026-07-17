@extends('layouts.app')

@section('title', 'About Us - Homelist Real Estate Portal')

@section('content')
<!-- About Header -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-primary-950 text-white py-20 px-4">
    <div class="absolute top-1/4 -right-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-primary-500/20 text-primary-400 font-semibold px-4 py-1.5 rounded-full text-xs border border-primary-500/30 uppercase tracking-wider mb-6 inline-block">Our Story</span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
            Redefining the <span class="bg-gradient-to-r from-primary-400 to-teal-300 bg-clip-text text-transparent">Real Estate Search</span>
        </h1>
        <p class="text-slate-300 text-lg sm:text-xl max-w-2xl mx-auto font-light">
            Providing a premium, high-speed, data-driven property marketplace across Central India.
        </p>
    </div>
</section>

<!-- Company Vision & Details -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Visual Badge Grid -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-white to-slate-50 p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary-500/5 rounded-full blur-xl"></div>
                <span class="text-primary-600 text-sm font-bold uppercase tracking-wider block mb-2">Our Mission</span>
                <h3 class="text-2xl font-extrabold text-darkslate mb-4">Precision, Speed & Scalability</h3>
                <p class="text-slate-600 leading-relaxed font-light">
                    Homelist is engineered to handle over 50,000+ properties with sub-second search times. By migrating from WordPress to a robust Laravel 12 architecture, we have eliminated performance bottlenecks and brought premium tools directly to our buyers and agents.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="text-2xl font-extrabold text-primary-600 block mb-1">Instant search</span>
                    <p class="text-slate-500 text-sm font-light">Powered by Laravel Scout and Meilisearch for immediate filtered results.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="text-2xl font-extrabold text-primary-600 block mb-1">Verified Agents</span>
                    <p class="text-slate-500 text-sm font-light">Direct communication with licensed and verified local real estate consultants.</p>
                </div>
            </div>
        </div>

        <!-- Text details -->
        <div class="space-y-6">
            <span class="text-primary-600 font-bold uppercase tracking-wider text-xs block">Who We Are</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-darkslate leading-tight">
                Central India's Most Advanced Real Estate Portal
            </h2>
            <p class="text-slate-600 leading-relaxed font-light">
                Originally founded as a WordPress listing catalog, Homelist grew rapidly, serving thousands of daily buyers looking for duplex houses, agricultural land, commercial shops, and rental flats.
            </p>
            <p class="text-slate-600 leading-relaxed font-light">
                To serve our users better, we migrated the entire directory containing **6,374 listings**, **122 verified agents**, and over **35,000 gallery images** into this highly optimized, custom Laravel 12 backend. Utilizing responsive caching, high-resolution media generation, and location mapping, Homelist now represents the state of the art in prop-tech web applications.
            </p>
        </div>
    </div>
</div>

<!-- Values Section -->
<section class="bg-slate-50 py-20 border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-16">
        <div>
            <span class="text-primary-600 font-bold uppercase tracking-wider text-xs block mb-2">Our Core Pillars</span>
            <h2 class="text-3xl font-extrabold text-darkslate">How We Deliver Value</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-darkslate">100% Verified Listings</h3>
                <p class="text-slate-500 text-sm font-light leading-relaxed">
                    Every property is passed through our agent verification network. We inspect locations, configurations, ownership titles, and photos.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-darkslate">High Performance</h3>
                <p class="text-slate-500 text-sm font-light leading-relaxed">
                    No slow databases. Thanks to memory caching, Redis queues, and advanced indexing, page load speeds are kept below 150ms.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-darkslate">Agent Relationships</h3>
                <p class="text-slate-500 text-sm font-light leading-relaxed">
                    We empower independent agents and agencies with Filament-based property managers, real-time analytics, and customer lead notifications.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
