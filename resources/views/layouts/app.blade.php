<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ $settings->site_favicon ? asset('storage/' . $settings->site_favicon) : asset('favicon.ico') }}" type="image/x-icon">
    
    <title>@yield('title', ($settings->site_title ?? 'Homelist Real Estate Portal'))</title>
    <meta name="description" content="@yield('meta_description', ($settings->site_description ?? 'Find your dream house, commercial properties, and lands with Homelist.'))">
    <meta name="keywords" content="@yield('meta_keywords', 'real estate, properties, agents, flats, houses')">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('title', 'Homelist Real Estate Portal')">
    <meta property="og:description" content="@yield('meta_description', 'Find your dream house, commercial properties, and lands with Homelist.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fbf7',
                            100: '#dcf7ec',
                            200: '#bdf0db',
                            300: '#8ee4c2',
                            400: '#58d0a2',
                            500: '#32b483',
                            600: '#23936a',
                            700: '#1e7557',
                            800: '#1b5d47',
                            900: '#174d3c',
                            950: '#0c2b22',
                        },
                        darkslate: '#0f172a',
                    }
                }
            }
        }
    </script>
    
    <!-- AlpineJS for animations and toggle states -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Top Bar Info -->
    <div class="bg-slate-950 text-slate-400 text-xs py-2.5 px-4 border-b border-slate-900 hidden md:block">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                @if($settings->contact_address)
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ $settings->contact_address }}</span>
                </div>
                @endif
                @if($settings->contact_phone)
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>{{ $settings->contact_phone }}</span>
                </div>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <span>Mon to Sat  -  09:00 to 21:00</span>
                <span class="text-slate-800">|</span>
                <a href="/admin/login" class="text-slate-300 hover:text-amber-500 transition-colors font-medium">Agent Login</a>
            </div>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 w-full transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-amber-400 font-bold text-xl shadow-lg shadow-amber-500/10 group-hover:scale-105 transition-all">{{ substr($settings->site_name ?? 'Homelist', 0, 1) }}</span>
                <span class="text-2xl font-black tracking-tight text-slate-900">{{ $settings->site_name ?? 'Homelist' }}</span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 font-bold text-xs tracking-wider">
                <a href="{{ route('home') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase {{ request()->routeIs('home') ? 'text-amber-500 font-extrabold' : '' }}">Home</a>
                <a href="{{ route('properties.index') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase {{ request()->routeIs('properties.*') ? 'text-amber-500 font-extrabold' : '' }}">Properties</a>
                <a href="{{ route('pages.about') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase {{ request()->routeIs('pages.about') ? 'text-amber-500 font-extrabold' : '' }}">About</a>
                <a href="{{ route('pages.blog') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase {{ request()->routeIs('pages.blog') ? 'text-amber-500 font-extrabold' : '' }}">Blog</a>
                <a href="{{ route('pages.contact') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase {{ request()->routeIs('pages.contact') ? 'text-amber-500 font-extrabold' : '' }}">Contact</a>
            </nav>

            <!-- CTA Actions -->
            <div class="flex items-center gap-4 text-xs tracking-wider font-bold">
                <a href="{{ route('compare') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase mr-2 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                    Compare
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase">My Dashboard</a>
                    <a href="{{ route('logout') }}" class="text-red-500 hover:text-red-600 transition-colors uppercase">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase">Login</a>
                    <a href="{{ route('register') }}" class="text-slate-700 hover:text-amber-500 transition-colors uppercase">Register</a>
                @endauth

                <a href="{{ route('properties.index') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-5 rounded-xl shadow-sm transition-all duration-200 uppercase">Browse Listings</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6" x-data="{ show: true }" x-show="show" x-transition>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            </div>
        @endif

        @yield('content')
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-16 mt-20 border-t border-slate-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
            <!-- Brand & Bio -->
            <div class="space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-yellow-400 rounded-xl flex items-center justify-center text-slate-950 font-bold text-xl shadow-lg shadow-amber-500/10">{{ substr($settings->site_name ?? 'Homelist', 0, 1) }}</span>
                    <span class="text-2xl font-black text-white tracking-tight">{{ $settings->site_name ?? 'Homelist' }}</span>
                </a>
                <p class="text-slate-400 leading-relaxed text-sm">
                    {{ $settings->footer_text ?? ($settings->site_description ?? 'The leading real estate marketplace to buy, rent, or lease premium properties. Serving Madhya Pradesh, Maharashtra, and beyond.') }}
                </p>
                @if(($settings->social_facebook || $settings->social_twitter || $settings->social_instagram || $settings->social_youtube || $settings->social_linkedin))
                    <div class="flex gap-3 pt-2">
                        @if($settings->social_facebook)
                            <a href="{{ $settings->social_facebook }}" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-300 rounded-xl flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1V12h3v3h-3v6.8c4.56-.93 8-4.96 8-9.8z"/></svg>
                            </a>
                        @endif
                        @if($settings->social_twitter)
                            <a href="{{ $settings->social_twitter }}" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-300 rounded-xl flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        @endif
                        @if($settings->social_instagram)
                            <a href="{{ $settings->social_instagram }}" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-300 rounded-xl flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                        @endif
                        @if($settings->social_linkedin)
                            <a href="{{ $settings->social_linkedin }}" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-300 rounded-xl flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold text-sm mb-6 uppercase tracking-wider">Useful Links</h4>
                <ul class="space-y-3.5 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
                    <li><a href="{{ route('properties.index') }}" class="hover:text-amber-500 transition-colors">Properties</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-amber-500 transition-colors">About Us</a></li>
                    <li><a href="{{ route('pages.blog') }}" class="hover:text-amber-500 transition-colors">Blog & News</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-amber-500 transition-colors">Contact Support</a></li>
                </ul>
            </div>

            <!-- Popular Cities -->
            <div>
                <h4 class="text-white font-bold text-sm mb-6 uppercase tracking-wider">Popular Cities</h4>
                <ul class="space-y-3.5 text-sm">
                    <li><a href="/search?city_id=2" class="hover:text-amber-500 transition-colors">Nagpur Properties</a></li>
                    <li><a href="/search?city_id=1" class="hover:text-amber-500 transition-colors">Indore Properties</a></li>
                    <li><a href="/search?city_id=3" class="hover:text-amber-500 transition-colors">Bhopal Properties</a></li>
                    <li><a href="/search?city_id=4" class="hover:text-amber-500 transition-colors">Pune Properties</a></li>
                </ul>
            </div>

            <!-- Get in Touch -->
            <div>
                <h4 class="text-white font-bold text-sm mb-6 uppercase tracking-wider">Get In Touch</h4>
                <ul class="space-y-4 text-sm">
                    @if($settings->contact_address)
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $settings->contact_address }}</span>
                    </li>
                    @endif
                    @if($settings->contact_phone)
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $settings->contact_phone }}</span>
                    </li>
                    @endif
                    @if($settings->contact_email)
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span><a href="mailto:{{ $settings->contact_email }}" class="hover:text-amber-500 transition-colors">{{ $settings->contact_email }}</a></span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? 'Homelist' }} Real Estate Portal. All rights reserved.</p>
            <div class="inline-flex items-center gap-2 bg-slate-900/90 text-slate-400 border border-slate-800 px-3.5 py-1.5 rounded-full text-xs font-medium">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>Total Website Views: <strong class="text-white font-bold">{{ number_format($settings->website_views ?? 0) }}</strong></span>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    @if($settings->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->contact_whatsapp) }}?text=Hello,%20I%20am%20interested%20in%20listings%20on%20{{ urlencode($settings->site_name ?? 'Homelist') }}" 
       target="_blank" 
       rel="noopener"
       class="fixed bottom-6 right-6 bg-[#25D366] hover:bg-[#20ba5a] text-white p-4 rounded-full shadow-2xl z-50 hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center group animate-bounce" 
       style="animation-duration: 2s;"
       aria-label="Chat on WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.428 2.008 13.99 1.01 11.993 1.01c-5.452 0-9.88 4.379-9.884 9.822-.001 1.778.476 3.51 1.38 5.02L2.485 20.87l4.162-1.716zM17.47 14.8c-.29-.145-1.712-.845-1.977-.94-.266-.096-.46-.145-.653.145-.193.29-.748.94-.917 1.133-.169.193-.338.217-.628.072-1.488-.743-2.434-1.343-3.411-3.02-.256-.44-.067-.678.078-.82.13-.129.29-.338.435-.507.145-.169.193-.29.29-.483.097-.193.048-.361-.024-.506-.072-.145-.653-1.571-.895-2.152-.236-.569-.475-.492-.653-.501-.17-.008-.364-.01-.559-.01-.195 0-.51.072-.776.361-.266.29-1.016.992-1.016 2.417s1.036 2.798 1.18 2.99c.145.193 2.036 3.111 4.933 4.363.688.298 1.227.476 1.646.609.693.22 1.324.19 1.823.11.556-.09 1.712-.699 1.953-1.376.242-.678.242-1.258.17-1.376-.073-.118-.266-.19-.556-.34z"/>
        </svg>
    </a>
    @endif

    @yield('scripts')
</body>
</html>
