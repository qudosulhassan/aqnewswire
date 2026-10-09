@extends('layouts.admin')

@section('title', 'General Settings 2.0 — AQ NEWSWIRE Editorial CMS')

@section('content')
<div class="max-w-[1400px] mx-auto pb-16 space-y-6">

    <!-- Top Sticky Control Header -->
    <div class="sticky top-0 z-30 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-3.5 shadow-md">
        <div class="max-w-[1400px] mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Left: Title, Badge & Last Saved -->
            <div>
                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                    <span>System Administration</span>
                    <span>&bull;</span>
                    <span class="text-slate-300 font-semibold">Global Platform Configuration</span>
                </div>
                <div class="flex items-center gap-3 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">General Settings</h1>
                    <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                        Production Ready
                    </span>
                    <span id="saveStatusIndicator" class="text-xs text-slate-400 font-medium hidden sm:inline">
                        Last saved: <strong class="text-slate-200">{{ $lastSavedFormatted }}</strong>
                    </span>
                </div>
            </div>

            <!-- Right: Search & Action Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Quick Filter Input -->
                <div class="relative w-48 sm:w-60">
                    <input type="text" 
                           id="settingsSearchInput" 
                           placeholder="Search settings..." 
                           oninput="filterSettings(this.value)"
                           class="w-full pl-8 pr-3 py-1.5 bg-slate-800/90 border border-slate-700 rounded-xl text-xs text-slate-100 placeholder-slate-400 focus:outline-none focus:border-red-500 transition">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Export Settings Button -->
                <a href="{{ route('admin.settings.export') }}" 
                   title="Export safe configuration JSON"
                   class="px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                </a>

                <!-- Import Settings Trigger Button -->
                <button type="button" 
                        onclick="document.getElementById('importModal').classList.remove('hidden')"
                        title="Import settings JSON"
                        class="px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                    <span class="hidden sm:inline">Import</span>
                </button>

                <!-- Discard Changes -->
                <button type="button" 
                        id="discardBtnTop"
                        onclick="discardChanges()"
                        class="px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white bg-transparent hover:bg-slate-800 rounded-xl transition cursor-pointer hidden">
                    Discard
                </button>

                <!-- Top Primary Save Button -->
                <button type="submit" 
                        form="generalSettingsForm" 
                        id="saveBtnTop"
                        class="px-4 py-1.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="saveBtnTopText">Save Changes</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white text-base leading-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white text-base leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Please correct the following configuration errors:</span>
            </div>
            <ul class="list-disc list-inside pl-1 text-[11px] text-rose-300">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Production Danger Warning Alert -->
    @if($systemDiagnostics['is_prod_debug_danger'])
        <div class="p-4 rounded-2xl bg-amber-500/20 border-2 border-amber-500 text-amber-200 text-xs font-semibold flex items-center gap-3 animate-pulse">
            <svg class="w-6 h-6 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-bold text-amber-300 text-sm">CRITICAL PRODUCTION SECURITY WARNING</p>
                <p class="text-[11px] text-amber-100 mt-0.5">Application is running in production with <code>APP_DEBUG=true</code>. Sensitive stack traces and database queries will leak to public readers. Immediately set <code>APP_DEBUG=false</code> in server environment.</p>
            </div>
        </div>
    @endif

    <!-- Sticky Horizontal Section Navigation Bar -->
    <div class="sticky top-[69px] z-20 bg-slate-950/90 backdrop-blur-md border border-slate-800 rounded-2xl p-1.5 overflow-x-auto no-scrollbar shadow-xs">
        <nav class="flex items-center gap-1 min-w-max text-xs font-semibold text-slate-400" id="sectionNavbar">
            <a href="#sec-general" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition text-slate-200 bg-slate-800">1. General</a>
            <a href="#sec-branding" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">2. Branding</a>
            <a href="#sec-contact" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">3. Contact</a>
            <a href="#sec-localization" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">4. Localization</a>
            <a href="#sec-social" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">5. Social</a>
            <a href="#sec-reader" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">6. Reader Features</a>
            <a href="#sec-editorial" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">7. Editorial</a>
            <a href="#sec-system" class="nav-tab px-3 py-1.5 rounded-xl hover:text-white hover:bg-slate-800/80 transition">8. System Diagnostics</a>
        </nav>
    </div>

    <!-- Floating Unsaved Changes Warning Banner -->
    <div id="dirtyNoticeBanner" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900 border border-amber-500/50 text-amber-300 px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-4 text-xs font-semibold hidden backdrop-blur-md">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
            <span>You have unsaved changes in this session.</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="discardChanges()" class="px-3 py-1 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition cursor-pointer">Discard</button>
            <button type="submit" form="generalSettingsForm" class="px-3 py-1 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition shadow-xs cursor-pointer">Save Changes</button>
        </div>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('admin.settings.update') }}" method="POST" id="generalSettingsForm" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- SECTION 1: GENERAL IDENTITY -->
        <section id="sec-general" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>1. General Site Identity</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Core</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Authoritative publication title, global mission statement, canonical host, and legal copyright notice.</p>
                </div>
                <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                    Saved
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Publication Title / Site Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="site_name" 
                           id="input_site_name"
                           value="{{ old('site_name', $settings['general']['site_name']['value'] ?? 'AQ NEWSWIRE International Business & Leadership') }}"
                           required
                           oninput="updateBrandPreview()"
                           placeholder="e.g. AQ NEWSWIRE International Business & Leadership"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Used across page titles, open graph meta tags, and global email templates.</p>
                </div>

                <!-- Site Description -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Global Publication Description / Mission
                    </label>
                    <textarea name="site_description" 
                              rows="2"
                              id="input_site_description"
                              placeholder="e.g. Global business journalism, executive analysis, and market intelligence."
                              class="w-full px-4 py-2 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">{{ old('site_description', $settings['general']['site_description']['value'] ?? '') }}</textarea>
                    <p class="text-[11px] text-slate-400">Default fallback summary for search engine snippet previews and footer summaries.</p>
                </div>

                <!-- Site Base URL -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Canonical Base URL <span class="text-red-400">*</span>
                    </label>
                    <input type="url" 
                           name="site_url" 
                           id="input_site_url"
                           value="{{ old('site_url', $settings['general']['site_url']['value'] ?? config('app.url')) }}"
                           required
                           placeholder="https://aqnewswire.com"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Authoritative URL used for canonical link generation. Configured via <code>APP_URL={{ config('app.url') }}</code>.</p>
                </div>

                <!-- Copyright Notice -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Footer Copyright Notice
                    </label>
                    <input type="text" 
                           name="copyright_text" 
                           value="{{ old('copyright_text', $settings['general']['copyright_text']['value'] ?? '© 2026 AQ NEWSWIRE Group Inc. All rights reserved.') }}"
                           placeholder="© 2026 AQ NEWSWIRE Group Inc. All rights reserved."
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Appears at the base of every public page in the footer.</p>
                </div>
            </div>
        </section>

        <!-- SECTION 2: BRANDING & VISUAL IDENTITY -->
        <section id="sec-branding" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>2. Branding & Visual Identity</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Theme</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Short brand marks, official logo asset, and browser favicon.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                        Saved
                    </span>
                </div>
            </div>

            <!-- Live Branding Preview Widget -->
            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <span>Live Branding Preview</span>
                    <span class="text-[10px] text-slate-500 font-normal">(Updates in real-time)</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Header Preview -->
                    <div class="bg-[#0B0F19] border border-slate-800/80 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span id="previewHeaderBrand" class="text-2xl font-black text-white tracking-tight flex items-center">
                                {{ $settings['branding']['short_brand_name']['value'] ?? 'AQ NEWSWIRE' }}<span class="inline-block w-2 h-2 rounded-full bg-red-600 ml-0.5"></span>
                            </span>
                            <div class="border-l border-slate-700/80 pl-2 text-[9px] uppercase tracking-widest text-slate-400 font-extrabold leading-tight">
                                <div>INTERNATIONAL</div>
                                <div>BUSINESS</div>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-500">Public Header</span>
                    </div>

                    <!-- Browser Tab Preview -->
                    <div class="bg-[#0B0F19] border border-slate-800/80 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-5 h-5 rounded bg-slate-800 flex items-center justify-center text-xs shrink-0 text-red-500 font-bold">
                            ▲
                        </div>
                        <div class="truncate">
                            <div id="previewTabTitle" class="text-xs text-slate-200 font-semibold truncate">
                                {{ $settings['general']['site_name']['value'] ?? 'APEX Media' }}
                            </div>
                            <div class="text-[10px] text-slate-500 truncate">
                                {{ $settings['general']['site_url']['value'] ?? 'https://aqnewswire.com' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Short Brand Name -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Short Brand Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="short_brand_name" 
                           id="input_short_brand_name"
                           value="{{ old('short_brand_name', $settings['branding']['short_brand_name']['value'] ?? 'AQ NEWSWIRE') }}"
                           required
                           oninput="updateBrandPreview()"
                           placeholder="e.g. APEX"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Used for navigation badges and compact mobile displays.</p>
                </div>

                <!-- Logo URL -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Official Logo Path / URL
                    </label>
                    <input type="text" 
                           name="logo_url" 
                           value="{{ old('logo_url', $settings['branding']['logo_url']['value'] ?? '/images/apex-logo.svg') }}"
                           placeholder="/images/apex-logo.svg"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">SVG or high-resolution PNG recommended.</p>
                </div>

                <!-- Favicon URL -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Favicon URL
                    </label>
                    <input type="text" 
                           name="favicon_url" 
                           value="{{ old('favicon_url', $settings['branding']['favicon_url']['value'] ?? '/favicon.ico') }}"
                           placeholder="/favicon.ico"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Standard <code>.ico</code> or <code>32x32 .png</code> asset.</p>
                </div>
            </div>
        </section>

        <!-- SECTION 3: CONTACT INFORMATION -->
        <section id="sec-contact" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>3. Contact Information & Newsroom Inquiries</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Public Desk</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Inquiry channels rendered on the public <a href="{{ route('contact') }}" target="_blank" class="text-red-400 underline">/contact page</a> and footer bureaus.
                    </p>
                </div>
                <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                    Saved
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Primary Email -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Public Inquiries Email
                    </label>
                    <input type="email" 
                           name="primary_email" 
                           value="{{ old('primary_email', $settings['contact']['primary_email']['value'] ?? 'contact@apexmedia.org') }}"
                           placeholder="contact@apexmedia.org"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Main executive corporate contact inbox.</p>
                </div>

                <!-- Editorial Newsroom Email -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Editorial Desk Email
                    </label>
                    <input type="email" 
                           name="editorial_email" 
                           value="{{ old('editorial_email', $settings['contact']['editorial_email']['value'] ?? 'editorial@apexmedia.org') }}"
                           placeholder="editorial@apexmedia.org"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Whistleblower tips and editorial corrections.</p>
                </div>

                <!-- Support Email -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Technical / Reader Support
                    </label>
                    <input type="email" 
                           name="support_email" 
                           value="{{ old('support_email', $settings['contact']['support_email']['value'] ?? 'support@apexmedia.org') }}"
                           placeholder="support@apexmedia.org"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Subscription billing and digital member issues.</p>
                </div>

                <!-- Phone -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Enterprise Phone
                    </label>
                    <input type="text" 
                           name="contact_phone" 
                           value="{{ old('contact_phone', $settings['contact']['contact_phone']['value'] ?? '+1 (555) 019-2834') }}"
                           placeholder="+1 (555) 019-2834"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Direct switchboard line for newsroom reception.</p>
                </div>

                <!-- Contact Page URL -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Contact Page URL
                    </label>
                    <input type="text" 
                           name="contact_page_url" 
                           value="{{ old('contact_page_url', $settings['contact']['contact_page_url']['value'] ?? '/contact') }}"
                           placeholder="/contact"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Relative or absolute link to inquiries form.</p>
                </div>

                <!-- Physical Address -->
                <div class="space-y-1.5 md:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Global Headquarters Address
                    </label>
                    <textarea name="office_address" 
                              rows="2"
                              placeholder="100 Financial District, Suite 4200, New York, NY 10005"
                              class="w-full px-4 py-2 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">{{ old('office_address', $settings['contact']['office_address']['value'] ?? '') }}</textarea>
                    <p class="text-[11px] text-slate-400">Displayed in legal notices, contact bureaus, and email compliance footers.</p>
                </div>
            </div>
        </section>

        <!-- SECTION 4: LOCALIZATION -->
        <section id="sec-localization" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>4. Localization & Timezone</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Regional</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Control how article publish times and financial tickers are rendered.</p>
                </div>
                <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                    Saved
                </span>
            </div>

            <!-- Live Date & Time Preview Widget -->
            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                        🕒
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Live Timestamp Preview</div>
                        <div id="liveClockPreview" class="text-sm font-bold text-white font-mono">
                            {{ now()->timezone($settings['localization']['timezone']['value'] ?? 'UTC')->format($settings['localization']['date_format']['value'] ?? 'F j, Y') }} &bull; {{ now()->timezone($settings['localization']['timezone']['value'] ?? 'UTC')->format($settings['localization']['time_format']['value'] ?? 'g:i A') }}
                        </div>
                    </div>
                </div>
                <span class="text-[11px] text-slate-500">Zone: <code>{{ $settings['localization']['timezone']['value'] ?? 'UTC' }}</code></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Timezone -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Default Timezone <span class="text-red-400">*</span>
                    </label>
                    <select name="timezone" 
                            id="select_timezone"
                            required
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-red-500 transition">
                        @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', $settings['localization']['timezone']['value'] ?? 'UTC') === $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400">Strictly validated against standard IANA identifiers.</p>
                </div>

                <!-- Locale -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Default Locale <span class="text-red-400">*</span>
                    </label>
                    <select name="default_locale" 
                            required
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-red-500 transition">
                        <option value="en" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'en' ? 'selected' : '' }}>English (en)</option>
                        <option value="es" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'es' ? 'selected' : '' }}>Spanish (es)</option>
                        <option value="fr" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'fr' ? 'selected' : '' }}>French (fr)</option>
                        <option value="de" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'de' ? 'selected' : '' }}>German (de)</option>
                        <option value="ar" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'ar' ? 'selected' : '' }}>Arabic (ar)</option>
                        <option value="zh" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'zh' ? 'selected' : '' }}>Chinese (zh)</option>
                        <option value="ja" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'ja' ? 'selected' : '' }}>Japanese (ja)</option>
                        <option value="ur" {{ old('default_locale', $settings['localization']['default_locale']['value'] ?? 'en') === 'ur' ? 'selected' : '' }}>Urdu (ur)</option>
                    </select>
                    <p class="text-[11px] text-slate-400">Primary language definition for HTML tags.</p>
                </div>

                <!-- Date Format -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Date Format Pattern <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="date_format" 
                           value="{{ old('date_format', $settings['localization']['date_format']['value'] ?? 'F j, Y') }}"
                           required
                           placeholder="F j, Y"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">e.g. <code>F j, Y</code> &rarr; October 3, 2026</p>
                </div>

                <!-- Time Format -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Time Format Pattern <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="time_format" 
                           value="{{ old('time_format', $settings['localization']['time_format']['value'] ?? 'g:i A') }}"
                           required
                           placeholder="g:i A"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">e.g. <code>g:i A</code> &rarr; 12:45 AM</p>
                </div>
            </div>
        </section>

        <!-- SECTION 5: SOCIAL PROFILES -->
        <section id="sec-social" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>5. Social Network Profiles</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Channels</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Strictly enforced HTTPS URLs rendered in footer, article share dialogs, and Schema.org sameAs.</p>
                </div>
                <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                    Saved
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- LinkedIn -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center justify-between">
                        <span>LinkedIn Company URL</span>
                        @if(!empty($settings['social']['social_linkedin']['value']))
                            <a href="{{ $settings['social']['social_linkedin']['value'] }}" target="_blank" class="text-blue-400 hover:text-white text-[10px] font-semibold flex items-center gap-1">Open ↗</a>
                        @endif
                    </label>
                    <input type="url" 
                           name="social_linkedin" 
                           value="{{ old('social_linkedin', $settings['social']['social_linkedin']['value'] ?? '') }}"
                           placeholder="https://linkedin.com/company/apex-media"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                </div>

                <!-- Twitter / X -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center justify-between">
                        <span>X (Twitter) Profile URL</span>
                        @if(!empty($settings['social']['social_twitter']['value']))
                            <a href="{{ $settings['social']['social_twitter']['value'] }}" target="_blank" class="text-blue-400 hover:text-white text-[10px] font-semibold flex items-center gap-1">Open ↗</a>
                        @endif
                    </label>
                    <input type="url" 
                           name="social_twitter" 
                           value="{{ old('social_twitter', $settings['social']['social_twitter']['value'] ?? '') }}"
                           placeholder="https://twitter.com/apex_media"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                </div>

                <!-- YouTube -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center justify-between">
                        <span>YouTube Channel URL</span>
                        @if(!empty($settings['social']['social_youtube']['value']))
                            <a href="{{ $settings['social']['social_youtube']['value'] }}" target="_blank" class="text-red-400 hover:text-white text-[10px] font-semibold flex items-center gap-1">Open ↗</a>
                        @endif
                    </label>
                    <input type="url" 
                           name="social_youtube" 
                           value="{{ old('social_youtube', $settings['social']['social_youtube']['value'] ?? '') }}"
                           placeholder="https://youtube.com/@apexmedia"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                </div>

                <!-- Facebook -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center justify-between">
                        <span>Facebook Page URL</span>
                        @if(!empty($settings['social']['social_facebook']['value']))
                            <a href="{{ $settings['social']['social_facebook']['value'] }}" target="_blank" class="text-blue-400 hover:text-white text-[10px] font-semibold flex items-center gap-1">Open ↗</a>
                        @endif
                    </label>
                    <input type="url" 
                           name="social_facebook" 
                           value="{{ old('social_facebook', $settings['social']['social_facebook']['value'] ?? '') }}"
                           placeholder="https://facebook.com/apexmediagroup"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                </div>

                <!-- Instagram -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center justify-between">
                        <span>Instagram Profile URL</span>
                        @if(!empty($settings['social']['social_instagram']['value']))
                            <a href="{{ $settings['social']['social_instagram']['value'] }}" target="_blank" class="text-pink-400 hover:text-white text-[10px] font-semibold flex items-center gap-1">Open ↗</a>
                        @endif
                    </label>
                    <input type="url" 
                           name="social_instagram" 
                           value="{{ old('social_instagram', $settings['social']['social_instagram']['value'] ?? '') }}"
                           placeholder="https://instagram.com/apexmedia"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                </div>
            </div>
        </section>

        <!-- SECTION 6: READER EXPERIENCE FEATURE TOGGLES -->
        <section id="sec-reader" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>6. Reader Experience Feature Toggles</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Controls</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Live switches controlling public features. Disabling safely pauses features without deleting historical records.</p>
                </div>
                <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                    Saved
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Comments Toggle -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800/80 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-white block">Story Commentary & Discussion</span>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            When disabled, the comment submission form is closed while existing approved comments remain visible.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" 
                               name="enable_comments" 
                               value="1" 
                               {{ old('enable_comments', $settings['reader']['enable_comments']['value'] ?? true) ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>

                <!-- Newsletter Toggle -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800/80 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-white block">Newsletter Subscription Engine</span>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Controls whether subscription forms appear on public pages. Subscribers are never deleted when paused.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" 
                               name="enable_newsletter" 
                               value="1" 
                               {{ old('enable_newsletter', $settings['reader']['enable_newsletter']['value'] ?? true) ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>

                <!-- Registration Toggle -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800/80 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-white block">Public Reader Self-Registration</span>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            When disabled, the <code>/register</code> route closes new signups and directs users to sign in.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" 
                               name="enable_registration" 
                               value="1" 
                               {{ old('enable_registration', $settings['reader']['enable_registration']['value'] ?? true) ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>

                <!-- Bookmarks Toggle -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800/80 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-white block">Story Bookmarks & Reading Lists</span>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Controls whether readers can save stories. Existing reading lists are preserved in their dashboard.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" 
                               name="enable_bookmarks" 
                               value="1" 
                               {{ old('enable_bookmarks', $settings['reader']['enable_bookmarks']['value'] ?? true) ? 'checked' : '' }} 
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>

            </div>
        </section>

        <!-- SECTION 7: EDITORIAL DEFAULTS -->
        <section id="sec-editorial" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>7. Editorial Defaults & Analytics Metrics</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Newsroom</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Parameters governing article read-time computation and initial creation workflow status.</p>
                </div>
                <span class="status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                    Saved
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Reading Speed -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Reading Velocity (Words Per Minute) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" 
                           name="default_reading_words_per_minute" 
                           min="50" 
                           max="500"
                           value="{{ old('default_reading_words_per_minute', $settings['editorial']['default_reading_words_per_minute']['value'] ?? 220) }}"
                           required
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500 transition">
                    <p class="text-[11px] text-slate-400">Used by Article saving hook to calculate read time minutes.</p>
                </div>

                <!-- Default Story Status -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Default Story Workflow Status <span class="text-red-400">*</span>
                    </label>
                    <select name="default_article_status" 
                            required
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-red-500 transition">
                        <option value="draft" {{ old('default_article_status', $settings['editorial']['default_article_status']['value'] ?? 'draft') === 'draft' ? 'selected' : '' }}>
                            Draft (Requires Editorial Review)
                        </option>
                        <option value="published" {{ old('default_article_status', $settings['editorial']['default_article_status']['value'] ?? 'draft') === 'published' ? 'selected' : '' }}>
                            Published (Immediate Live Release)
                        </option>
                    </select>
                    <p class="text-[11px] text-slate-400">Initial preset in CMS story composer.</p>
                </div>

                <!-- Reading History Tracking -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Subscriber Reading History
                    </label>
                    <div class="pt-2">
                        <label class="flex items-center gap-3 text-xs text-slate-200 cursor-pointer">
                            <input type="checkbox" 
                                   name="enable_reading_history" 
                                   value="1" 
                                   {{ old('enable_reading_history', $settings['editorial']['enable_reading_history']['value'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-red-600 border-slate-700 bg-slate-800 focus:ring-red-500">
                            <span>Record reading history for signed-in readers</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mt-1">Powers reader personal dashboard recent reads.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 8: SYSTEM ARCHITECTURE & DIAGNOSTICS -->
        <section id="sec-system" class="settings-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>8. System Architecture & Live Telemetry</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400">Read-Only</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Read-only operational health telemetry. Sensitive environment secrets are never rendered.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $systemDiagnostics['app_env'] === 'production' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30' }}">
                        ENV: {{ strtoupper($systemDiagnostics['app_env']) }}
                    </span>
                </div>
            </div>

            <!-- Health Badges Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Database Probe</span>
                    <div class="flex items-center gap-1.5 mt-1.5 font-bold text-xs {{ $systemDiagnostics['db_connected'] ? 'text-emerald-400' : 'text-rose-400' }}">
                        <span>{{ $systemDiagnostics['db_connected'] ? '● Connected' : '✕ Disconnected' }}</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Driver: <code>{{ $systemDiagnostics['database_driver'] }}</code></span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Cache Probe</span>
                    <div class="flex items-center gap-1.5 mt-1.5 font-bold text-xs {{ $systemDiagnostics['cache_working'] ? 'text-emerald-400' : 'text-rose-400' }}">
                        <span>{{ $systemDiagnostics['cache_working'] ? '● Operational' : '✕ Failed' }}</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Driver: <code>{{ $systemDiagnostics['cache_driver'] }}</code></span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Storage Writable</span>
                    <div class="flex items-center gap-1.5 mt-1.5 font-bold text-xs {{ $systemDiagnostics['storage_writable'] ? 'text-emerald-400' : 'text-rose-400' }}">
                        <span>{{ $systemDiagnostics['storage_writable'] ? '● Writable' : '✕ Read-Only' }}</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Disk: <code>{{ $systemDiagnostics['filesystem_driver'] }}</code></span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">SEO Isolation</span>
                    <div class="flex items-center gap-1.5 mt-1.5 font-bold text-xs text-emerald-400">
                        <span>● Decoupled</span>
                    </div>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Path: <code>/admin/seo</code></span>
                </div>

            </div>

            <!-- Diagnostics Details Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800/80">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Framework</span>
                    <span class="font-semibold text-slate-200">Laravel {{ $systemDiagnostics['laravel_version'] }}</span>
                </div>
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800/80">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">PHP Runtime</span>
                    <span class="font-semibold text-slate-200">PHP {{ $systemDiagnostics['php_version'] }}</span>
                </div>
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800/80">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Debug Mode</span>
                    <span class="font-semibold text-slate-200">{{ $systemDiagnostics['app_debug'] }}</span>
                </div>
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800/80">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Queue / Session</span>
                    <span class="font-semibold text-slate-200">{{ $systemDiagnostics['queue_driver'] }} / {{ $systemDiagnostics['session_driver'] }}</span>
                </div>
            </div>
        </section>

        <!-- Bottom Actions Bar -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-xs text-slate-400">
                <span>Saving will invalidate <code>app_settings_map</code> cache and immediately update public site behavior.</span>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="discardChanges()" 
                        class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition cursor-pointer">
                    Discard Changes
                </button>
                <button type="submit" 
                        id="saveBtnBottom"
                        class="px-6 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-md inline-flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="saveBtnBottomText">Save Changes</span>
                </button>
            </div>
        </div>

    </form>

</div>

<!-- JSON Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full space-y-5 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-base font-bold text-white">Import Configuration JSON</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg">&times;</button>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed">
            Upload an authorized settings JSON file. Only allowlisted keys are imported; sensitive server secrets are never accepted.
        </p>
        <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <input type="file" 
                       name="settings_file" 
                       accept=".json" 
                       required 
                       class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">Upload & Apply</button>
            </div>
        </form>
    </div>
</div>

<!-- Vanilla JS Interactions: Live Previews, Dirty Detection, Filter & ScrollSpy -->
<script>
    let isDirty = false;
    const initialFormState = new FormData(document.getElementById('generalSettingsForm'));

    // Track Form Dirty State
    const form = document.getElementById('generalSettingsForm');
    form.addEventListener('input', markDirty);
    form.addEventListener('change', markDirty);

    function markDirty() {
        if (!isDirty) {
            isDirty = true;
            const banner = document.getElementById('dirtyNoticeBanner');
            if (banner) banner.classList.remove('hidden');
            const discardTop = document.getElementById('discardBtnTop');
            if (discardTop) discardTop.classList.remove('hidden');
            
            // Mark all section status pills as Modified
            document.querySelectorAll('.status-pill').forEach(pill => {
                pill.textContent = 'Modified';
                pill.className = 'status-pill text-[10px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 rounded-full';
            });
        }
    }

    function discardChanges() {
        if (confirm('Discard all unsaved changes and reset form?')) {
            form.reset();
            isDirty = false;
            document.getElementById('dirtyNoticeBanner')?.classList.add('hidden');
            document.getElementById('discardBtnTop')?.classList.add('hidden');
            document.querySelectorAll('.status-pill').forEach(pill => {
                pill.textContent = 'Saved';
                pill.className = 'status-pill text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full';
            });
            updateBrandPreview();
        }
    }

    // Warn on navigation with unsaved changes
    window.addEventListener('beforeunload', function (e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = 'You have unsaved configuration changes.';
        }
    });

    // Disable Save Buttons with loading indicator upon submit
    form.addEventListener('submit', function () {
        isDirty = false; // Disarm beforeunload
        const btnTop = document.getElementById('saveBtnTop');
        const btnBottom = document.getElementById('saveBtnBottom');
        const textTop = document.getElementById('saveBtnTopText');
        const textBottom = document.getElementById('saveBtnBottomText');

        if (btnTop) btnTop.disabled = true;
        if (btnBottom) btnBottom.disabled = true;
        if (textTop) textTop.textContent = 'Saving...';
        if (textBottom) textBottom.textContent = 'Saving...';
    });

    // Real-Time Branding & Tab Preview
    function updateBrandPreview() {
        const brandInput = document.getElementById('input_short_brand_name');
        const siteNameInput = document.getElementById('input_site_name');
        const siteUrlInput = document.getElementById('input_site_url');

        const brandPreview = document.getElementById('previewHeaderBrand');
        const tabTitlePreview = document.getElementById('previewTabTitle');

        if (brandPreview && brandInput) {
            const brandVal = brandInput.value.trim() || 'AQ NEWSWIRE';
            brandPreview.innerHTML = `${brandVal}<span class="inline-block w-2 h-2 rounded-full bg-red-600 ml-0.5"></span>`;
        }

        if (tabTitlePreview && siteNameInput) {
            tabTitlePreview.textContent = siteNameInput.value.trim() || 'APEX Media';
        }
    }

    // Client-Side Settings Search Filter
    function filterSettings(query) {
        const q = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.settings-card');
        
        cards.forEach(card => {
            if (!q) {
                card.style.display = '';
                // remove highlights
                card.classList.remove('ring-2', 'ring-red-500/50');
                return;
            }

            const text = card.textContent.toLowerCase();
            if (text.includes(q)) {
                card.style.display = '';
                card.classList.add('ring-2', 'ring-red-500/50');
            } else {
                card.style.display = 'none';
                card.classList.remove('ring-2', 'ring-red-500/50');
            }
        });
    }

    // Smooth Scroll & ScrollSpy for Section Nav
    const navLinks = document.querySelectorAll('#sectionNavbar a');
    const sections = document.querySelectorAll('section[id^="sec-"]');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 140;
            if (window.pageYOffset >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('text-slate-200', 'bg-slate-800');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('text-slate-200', 'bg-slate-800');
            }
        });
    });
</script>
@endsection
