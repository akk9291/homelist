@extends('layouts.app')

@section('title', 'Real Estate Guide & Blog - Homelist')

@section('content')
<!-- Blog Header -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-primary-950 text-white py-16 px-4">
    <div class="absolute top-1/4 -right-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-primary-500/20 text-primary-400 font-semibold px-4 py-1.5 rounded-full text-xs border border-primary-500/30 uppercase tracking-wider mb-3 inline-block">News & Guides</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">Homelist Insights</h1>
        <p class="text-slate-300 text-base sm:text-lg max-w-xl mx-auto font-light">
            Stay up to date with the latest real estate trends, first-time homebuyer guides, and market valuations.
        </p>
    </div>
</section>

<!-- Blog List Roll -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($blogs as $blog)
            <article class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <!-- Blog Image -->
                <div class="h-56 w-full overflow-hidden relative">
                    <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-4 left-4 bg-darkslate/75 backdrop-blur-md text-white text-xs font-semibold px-3 py-1.5 rounded-xl">
                        {{ $blog['date'] }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8 flex-grow flex flex-col justify-between space-y-6">
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-primary-600 uppercase tracking-wider block">Real Estate Guide</span>
                        <h3 class="text-xl font-extrabold text-darkslate leading-snug group-hover:text-primary-600 transition-colors">
                            {{ $blog['title'] }}
                        </h3>
                        <p class="text-slate-500 text-sm font-light leading-relaxed">
                            {{ $blog['excerpt'] }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-50 pt-4 text-xs">
                        <span class="text-slate-400">By <strong class="text-slate-600 font-semibold">{{ $blog['author'] }}</strong></span>
                        <span class="text-primary-600 hover:text-primary-700 font-bold flex items-center gap-1 cursor-pointer">
                            Read Article
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection
