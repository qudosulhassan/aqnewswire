@extends('layouts.app')

@section('title', 'About Us — Global Journalistic Mandate & Corporate Charter | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Learn about AQ NEWSWIRE, our journalistic charter, global newsroom standards, leadership, and mission to deliver authoritative financial, technology, and geopolitical intelligence.')

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "About AQ NEWSWIRE",
  "url": "{{ route('about') }}",
  "description": "AQ NEWSWIRE is an independent digital news organization delivering real-time financial market intelligence, technology analysis, and geopolitical reporting.",
  "publisher": {
    "@type": "NewsMediaOrganization",
    "name": "{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}",
    "url": "{{ url('/') }}",
    "foundingDate": "2024",
    "publishingPrinciples": "{{ route('editorial-standards') }}"
  }
}
</script>
@endpush

@section('content')
<div class="bg-white text-slate-800">

    <!-- Hero Header -->
    <section class="relative bg-[#F8FAFC] border-b border-slate-200/80 py-16 lg:py-20 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-[#635BFF]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-32 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-[#635BFF] transition">Home</a>
                <span>/</span>
                <span class="text-[#0A2540]">Company</span>
                <span>/</span>
                <span class="text-[#635BFF]">About Us</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#635BFF]/10 text-[#635BFF] text-xs font-bold uppercase tracking-wider border border-[#635BFF]/20 shadow-xs">
                    <span>★ Institutional Journalistic Charter</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0A2540] tracking-tight leading-[1.12] font-headline">
                    Empowering Global Decision Makers with Uncompromised Intelligence.
                </h1>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed pt-2">
                    {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} is a global digital news organization committed to objective, data-driven journalism across international markets, technological innovation, and geopolitical policy.
                </p>
            </div>
        </div>
    </section>

    <!-- Key Facts & Metrics Banner -->
    <section class="border-b border-slate-200/80 bg-white py-10">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-[#0A2540] block tracking-tight font-headline">140+</span>
                    <span class="text-xs font-semibold text-slate-500 mt-1 block">Countries Reached Daily</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-[#635BFF] block tracking-tight font-headline">250K+</span>
                    <span class="text-xs font-semibold text-slate-500 mt-1 block">Active Executive Readers</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-[#0A2540] block tracking-tight font-headline">6</span>
                    <span class="text-xs font-semibold text-slate-500 mt-1 block">Global News Bureaus</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-emerald-600 block tracking-tight font-headline">100%</span>
                    <span class="text-xs font-semibold text-slate-500 mt-1 block">Editorial Independence</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Editorial Layout -->
    <section class="py-16 lg:py-20">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Main Body Copy (8 cols) -->
                <div class="lg:col-span-8 space-y-12">
                    
                    <!-- 1. The Mission -->
                    <div class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF]">01 &middot; Mission & Values</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight font-headline">
                            Clarity in a Fractured and Accelerating World
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4 font-serif text-lg">
                            <p>
                                At a time when headlines are commoditized and algorithmic noise often displaces verification, {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} was built to restore signal to global news. We operate on the foundational principle that high-stakes leaders require verified, contextual, and forward-looking analysis.
                            </p>
                            <p>
                                Our newsroom covers the convergence of capital, technology, and governance. Whether analyzing generative artificial intelligence architectures, tracking central bank yield curve dynamics, or chronicling regulatory shifts across Washington, Brussels, and Beijing, our dispatches focus on substance over sensationalism.
                            </p>
                        </div>
                    </div>

                    <!-- 2. Pillars of Coverage -->
                    <div class="space-y-4 pt-6 border-t border-slate-200/80">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF]">02 &middot; Editorial Pillars</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight font-headline">
                            Four Fundamental Desks
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs hover:border-indigo-300 transition">
                                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-[#635BFF] flex items-center justify-center font-bold text-xs mb-3">AI</span>
                                <h3 class="text-base font-bold text-[#0A2540] mb-1">Frontier Computing & AI</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Deep technical and commercial investigations into foundational AI models, enterprise cloud, chips, and cyber defence.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs hover:border-emerald-300 transition">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs mb-3">FX</span>
                                <h3 class="text-base font-bold text-[#0A2540] mb-1">Capital Markets & Economy</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Sovereign bond curves, private credit, currency movements, and central bank monetary policy shifts.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs hover:border-rose-300 transition">
                                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs mb-3">GP</span>
                                <h3 class="text-base font-bold text-[#0A2540] mb-1">Geopolitics & Defense</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    International supply chain reorganization, energy security, summit diplomacy, and national security directives.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs hover:border-amber-300 transition">
                                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-xs mb-3">EX</span>
                                <h3 class="text-base font-bold text-[#0A2540] mb-1">Executive Leadership & Life</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    C-suite decision frameworks, corporate governance, longevity science, and high-performance workplaces.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Independence & Ethics -->
                    <div class="space-y-4 pt-6 border-t border-slate-200/80">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF]">03 &middot; Trust & Independence</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight font-headline">
                            Complete Separation of News and Commercial Operations
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4 font-serif text-lg">
                            <p>
                                {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} maintains an impenetrable firewall between our editorial newsroom and any commercial or advertising relationships. No advertiser, sponsor, or external shareholder exerts influence over our news decisions, headlines, or investigations.
                            </p>
                            <p>
                                All journalists and editorial staff adhere to a strict securities trading prohibition: reporters may not trade or hold individual equities, debt securities, or derivatives in industries they directly cover. All sponsored or native content is unmistakably designated with standardized disclosures in compliance with FTC and international publishing standards.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Sidebar Navigation & Fast Facts (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Newsroom Standards Card -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#0A2540] flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#635BFF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Trust & Standards Links</span>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            We encourage our readers to examine our operational disclosures, ethics policies, and verification protocols.
                        </p>
                        <ul class="space-y-2 text-xs font-semibold text-slate-700">
                            <li>
                                <a href="{{ route('editorial-standards') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white hover:text-[#635BFF] transition border border-transparent hover:border-slate-200">
                                    <span>Editorial Standards & Corrections</span>
                                    <span>&rarr;</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('team') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white hover:text-[#635BFF] transition border border-transparent hover:border-slate-200">
                                    <span>Newsroom Leadership & Authors</span>
                                    <span>&rarr;</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white hover:text-[#635BFF] transition border border-transparent hover:border-slate-200">
                                    <span>Global Bureaus & Whistleblower Leaks</span>
                                    <span>&rarr;</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('privacy') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white hover:text-[#635BFF] transition border border-transparent hover:border-slate-200">
                                    <span>Data Protection & Privacy Policy</span>
                                    <span>&rarr;</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Corporate Headquarters Brief -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-3 shadow-xs">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Headquarters</h4>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            <strong>{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} Media Group</strong><br>
                            {{ \App\Models\Setting::get('office_address', '100 Financial District, New York, NY 10005') }}<br>
                            Registered in the United States of America.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Corporate Email:</span>
                            <a href="mailto:{{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }}" class="font-bold text-[#635BFF]">
                                {{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
