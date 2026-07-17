@extends('layouts.app')

@section('title', 'Contact Us - Homelist Real Estate Portal')

@section('content')
<!-- Header Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-primary-950 text-white py-16 px-4">
    <div class="absolute top-1/4 -right-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-primary-500/20 text-primary-400 font-semibold px-4 py-1.5 rounded-full text-xs border border-primary-500/30 uppercase tracking-wider mb-3 inline-block">Get In Touch</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">Contact Homelist</h1>
        <p class="text-slate-300 text-base sm:text-lg max-w-xl mx-auto font-light">
            Have questions about a listing, search filters, or want to list your properties? Reach out to us.
        </p>
    </div>
</section>

<!-- Form & Info Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
        <!-- Contact Information Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-8">
                <h3 class="text-xl font-bold text-darkslate border-b border-slate-100 pb-4">Our Offices</h3>
                
                <div class="space-y-6">
                    <!-- Office 1 -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-primary-600 uppercase tracking-wider block">Indore Office (HQ)</span>
                        <p class="text-slate-700 font-medium text-sm">
                            {{ $settings->contact_address }}
                        </p>
                        <p class="text-slate-500 text-xs font-light">Open Mon - Sat (10:00 AM - 7:00 PM)</p>
                    </div>

                    <!-- Office 2 -->
                    @if($settings->contact_address_nagpur)
                    <div class="space-y-2 border-t border-slate-100 pt-6">
                        <span class="text-xs font-bold text-primary-600 uppercase tracking-wider block">Nagpur Branch</span>
                        <p class="text-slate-700 font-medium text-sm">
                            {{ $settings->contact_address_nagpur }}
                        </p>
                        <p class="text-slate-500 text-xs font-light">Open Mon - Sat (10:00 AM - 6:30 PM)</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Direct Contact Numbers -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-xl font-bold text-darkslate border-b border-slate-100 pb-4">Direct Support</h3>

                <div class="space-y-4">
                    <!-- Call -->
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Phone support</span>
                            <span class="text-slate-800 font-semibold text-sm">{{ $settings->contact_phone }}</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Email support</span>
                            <a href="mailto:{{ $settings->contact_email }}" class="text-primary-600 hover:text-primary-700 font-semibold text-sm">{{ $settings->contact_email }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form Card -->
        <div class="lg:col-span-2">
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                <div>
                    <h3 class="text-2xl font-bold text-darkslate">Send Us a Message</h3>
                    <p class="text-slate-500 font-light mt-1.5 text-sm">We will get back to you within 24 business hours.</p>
                </div>

                <form action="{{ route('properties.inquiry') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Your Name</label>
                            <input type="text" name="name" required placeholder="John Doe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" required placeholder="john@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Phone Number</label>
                            <input type="text" name="phone" placeholder="+91 98765 43210" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <!-- Subject -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Subject</label>
                            <select name="subject" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                                <option value="Listing Inquiry">Listing Inquiry</option>
                                <option value="Agent Support">Agent Registration</option>
                                <option value="General Question">General Question</option>
                                <option value="Technical Support">Feedback / Bug Report</option>
                            </select>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Your Message</label>
                        <textarea name="message" required rows="6" placeholder="Describe how we can help you..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 transition-colors"></textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg shadow-primary-500/10 hover:shadow-primary-500/20 hover:-translate-y-0.5 transition-all duration-200 text-center">
                        Submit Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
@if($settings->map_embed_code)
<div class="w-full h-[450px] bg-slate-100 relative mt-12 border-t border-slate-100 overflow-hidden shadow-inner">
    {!! $settings->map_embed_code !!}
</div>
@endif
@endsection
