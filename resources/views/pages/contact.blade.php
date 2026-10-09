@extends('layouts.app')

@section('title', 'Contact & Global Newsroom Inquiries — ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- Header Breadcrumb & Title -->
    <div class="max-w-3xl mb-12">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-red-500 mb-3">
            <span>Corporate</span>
            <span>&bull;</span>
            <span>Global Inquiries</span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight mb-4">
            Contact {{ \App\Models\Setting::get('short_brand_name', 'AQ NEWSWIRE') }} Newsroom
        </h1>
        <p class="text-slate-400 text-base leading-relaxed">
            Reach our executive leadership team, investigative news desk, and editorial support bureaus around the world.
        </p>
    </div>

    <!-- Contact Bureaus Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        
        <!-- General Inquiries -->
        <div class="bg-[#0D1322] border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-red-600/10 border border-red-500/20 text-red-400 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">General & Corporate</h3>
                <p class="text-xs text-slate-400 mb-4 leading-relaxed">
                    General business correspondence, executive communications, and media partnerships.
                </p>
            </div>
            <div>
                <a href="mailto:{{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }}" class="text-sm font-semibold text-red-400 hover:text-red-300 transition">
                    {{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }}
                </a>
            </div>
        </div>

        <!-- Editorial Desk -->
        <div class="bg-[#0D1322] border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-600/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Editorial Newsroom</h3>
                <p class="text-xs text-slate-400 mb-4 leading-relaxed">
                    Confidential whistleblower leaks, editorial pitches, market tips, and corrections.
                </p>
            </div>
            <div>
                <a href="mailto:{{ \App\Models\Setting::get('editorial_email', 'editorial@aqnewswire.com') }}" class="text-sm font-semibold text-blue-400 hover:text-blue-300 transition">
                    {{ \App\Models\Setting::get('editorial_email', 'editorial@aqnewswire.com') }}
                </a>
            </div>
        </div>

        <!-- Reader & Technical Support -->
        <div class="bg-[#0D1322] border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-emerald-600/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Subscriber Inquiries</h3>
                <p class="text-xs text-slate-400 mb-4 leading-relaxed">
                    Executive subscription billing, membership access, and digital edition support.
                </p>
            </div>
            <div>
                <a href="mailto:{{ \App\Models\Setting::get('support_email', 'support@aqnewswire.com') }}" class="text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition">
                    {{ \App\Models\Setting::get('support_email', 'support@aqnewswire.com') }}
                </a>
            </div>
        </div>

    </div>

    <!-- Office & Operational Details -->
    <div class="bg-[#0B0F19] border border-slate-800 rounded-3xl p-8 lg:p-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl font-bold text-white mb-4">Global Headquarters</h2>
                <div class="space-y-4 text-xs text-slate-300">
                    <div class="flex items-start gap-3">
                        <span class="text-slate-500 font-bold uppercase w-20 shrink-0">Address:</span>
                        <span class="leading-relaxed">{{ \App\Models\Setting::get('office_address', '100 Financial District, New York, NY 10005') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-slate-500 font-bold uppercase w-20 shrink-0">Telephone:</span>
                        <span>{{ \App\Models\Setting::get('contact_phone', '+1 (555) 019-2834') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-slate-500 font-bold uppercase w-20 shrink-0">Timezone:</span>
                        <span>{{ \App\Models\Setting::get('timezone', 'UTC') }}</span>
                    </div>
                </div>
            </div>
            <div class="border-t lg:border-t-0 lg:border-l border-slate-800 lg:pl-10">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Connect On Verified Channels</h3>
                <div class="flex items-center gap-3 flex-wrap">
                    @if(\App\Models\Setting::get('social_twitter'))
                        <a href="{{ \App\Models\Setting::get('social_twitter') }}" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs text-slate-200 transition">
                            X / Twitter
                        </a>
                    @endif
                    @if(\App\Models\Setting::get('social_linkedin'))
                        <a href="{{ \App\Models\Setting::get('social_linkedin') }}" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs text-slate-200 transition">
                            LinkedIn
                        </a>
                    @endif
                    @if(\App\Models\Setting::get('social_youtube'))
                        <a href="{{ \App\Models\Setting::get('social_youtube') }}" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs text-slate-200 transition">
                            YouTube
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
