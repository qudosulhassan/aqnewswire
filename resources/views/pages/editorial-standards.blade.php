@extends('layouts.app')

@section('title', 'Editorial Standards, Ethics & Corrections Policy | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'The official journalistic standards, fact-checking methodology, conflict of interest code, corrections policy, and AI ethics of AQ NEWSWIRE.')

@push('head')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "@type": "WebPage",
  "name": "Editorial Standards and Ethics Policy",
  "url": "{{ route('editorial-standards') }}",
  "description": "The publishing principles, verification standards, and corrections policy of AQ NEWSWIRE.",
  "publisher": {
    "@type": "NewsMediaOrganization",
    "name": "{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}",
    "url": "{{ url('/') }}",
    "publishingPrinciples": "{{ route('editorial-standards') }}"
  }
}
</script>
@endpush

@section('content')
<div class="bg-white text-slate-800">

    <!-- Header Section -->
    <section class="relative bg-[#F8FAFC] border-b border-slate-200/80 py-16 lg:py-20 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-[#635BFF]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-[#635BFF] transition">Home</a>
                <span>/</span>
                <span class="text-[#0A2540]">Legal & Trust</span>
                <span>/</span>
                <span class="text-[#635BFF]">Editorial Standards</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#635BFF]/10 text-[#635BFF] text-xs font-bold uppercase tracking-wider border border-[#635BFF]/20 shadow-xs">
                    <span>★ Google E-E-A-T Institutional Quality Charter</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0A2540] tracking-tight leading-[1.12] font-headline">
                    Accuracy, Independence & Uncompromising Rigor.
                </h1>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed pt-2">
                    Our newsroom adheres to the highest standards of international journalism. Explore our multi-source verification procedures, corrections policy, securities trading bans, and AI ethics code.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content Policy Text -->
    <section class="py-16 lg:py-20">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Table of Contents Sidebar (4 cols) -->
                <div class="lg:col-span-4 sticky top-24 space-y-4">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-xs space-y-3">
                        <span class="font-bold text-[#0A2540] uppercase tracking-wider block">Charter Chapters</span>
                        <ul class="space-y-2 text-slate-600 font-medium">
                            <li><a href="#standard-1" class="hover:text-[#635BFF] transition block">1. Verification & Multi-Sourcing</a></li>
                            <li><a href="#standard-2" class="hover:text-[#635BFF] transition block">2. Formal Corrections Protocol</a></li>
                            <li><a href="#standard-3" class="hover:text-[#635BFF] transition block">3. Conflict of Interest & Trading Bans</a></li>
                            <li><a href="#standard-4" class="hover:text-[#635BFF] transition block">4. AI & Synthetic Media Ethics</a></li>
                            <li><a href="#standard-5" class="hover:text-[#635BFF] transition block">5. Anonymous Sourcing Standards</a></li>
                            <li><a href="#standard-6" class="hover:text-[#635BFF] transition block">6. Opinion vs. Factual Reporting</a></li>
                            <li><a href="#standard-7" class="hover:text-[#635BFF] transition block">7. Submitting a Correction Request</a></li>
                        </ul>
                    </div>

                    <!-- Direct Corrections Box -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-3">
                        <strong class="text-sm font-bold text-[#0A2540] block">Corrections Intake Desk</strong>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            If you detect a factual inaccuracy, calculation discrepancy, or misattribution in any published dispatch, contact our Standards Editor immediately.
                        </p>
                        <a href="mailto:corrections@aqnewswire.com" class="block w-full py-2.5 text-center bg-[#635BFF] hover:bg-[#5349DF] text-white font-bold rounded-xl transition text-xs shadow-xs">
                            corrections@aqnewswire.com
                        </a>
                    </div>
                </div>

                <!-- Editorial Standards Body (8 cols) -->
                <div class="lg:col-span-8 space-y-12 text-slate-700 leading-relaxed font-serif text-base lg:text-lg">
                    
                    <!-- 1. Verification -->
                    <div id="standard-1" class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 01</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            Verification & Multi-Source Sourcing Standard
                        </h2>
                        <div class="space-y-4">
                            <p>
                                At {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}, speed never supersedes accuracy. Our journalists are bound by the <strong>Two-Source Verification Rule</strong>: any assertion of fact that is not a matter of public record, official filing, or eyewitness observation must be corroborated by a minimum of two independent, qualified primary sources before publication.
                            </p>
                            <p>
                                For financial reporting, assertions regarding earnings, mergers, capital allocations, or regulatory investigations must be anchored in primary documentation—such as SEC filings, audited balance sheets, court transcripts, or executive on-the-record statements—whenever possible.
                            </p>
                        </div>
                    </div>

                    <!-- 2. Corrections -->
                    <div id="standard-2" class="space-y-4 pt-8 border-t border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 02</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            Transparent & Rapid Corrections Protocol
                        </h2>
                        <div class="space-y-4">
                            <p>
                                When an error of fact or attribution occurs, our newsroom corrects it promptly, visibly, and completely. We do not stealthily edit stories or remove content without explanation.
                            </p>
                            <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                                <li><strong>Substantive Factual Corrections:</strong> When a factual error is corrected, a prominent dated notice is permanently appended to the top or bottom of the article detailing what was originally published, what the accurate fact is, and the timestamp of correction.</li>
                                <li><strong>Clarifications:</strong> If an article is factually accurate but potentially misleading or lacking essential context, a clarification note is appended.</li>
                                <li><strong>Typographical Adjustments:</strong> Purely typographical corrections that do not alter meaning or context are made directly without formal notices.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 3. Conflict of Interest -->
                    <div id="standard-3" class="space-y-4 pt-8 border-t border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 03</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            Conflict of Interest & Securities Trading Prohibition
                        </h2>
                        <div class="space-y-4">
                            <p>
                                Financial independence is paramount to maintaining public trust:
                            </p>
                            <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                                <li><strong>Trading Ban:</strong> Journalists and editors may not purchase, short, or sell securities, bonds, cryptocurrencies, or options in companies, sectors, or sovereign debt instruments they have covered within the preceding 30 days or plan to cover in the subsequent 30 days.</li>
                                <li><strong>No Outside Compensation:</strong> Newsroom personnel may not accept gifts, compensated travel, board seats, honoraria, or consulting fees from entities covered by our newsroom.</li>
                                <li><strong>Broad Market Index Exception:</strong> Passive holdings in broad-market, diversified mutual funds or exchange-traded index funds (e.g., S&P 500 ETFs) are permitted.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 4. AI & Synthetic Media -->
                    <div id="standard-4" class="space-y-4 pt-8 border-t border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 04</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            Artificial Intelligence & Synthetic Media Policy
                        </h2>
                        <div class="space-y-4">
                            <p>
                                While our journalists utilize computational tools, data models, and automated market telemetry to parse complex disclosures, we uphold an uncompromised <strong>Human-in-the-Loop</strong> mandate:
                            </p>
                            <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                                <li>Generative AI models are strictly prohibited from generating unverified reporting, synthetic quotes, or fabricated source attribution.</li>
                                <li>Every published word, table, and data visualization is authored, reviewed, and fact-verified by human editors before publication.</li>
                                <li>AI narration and synthetic audio transcripts are explicitly labeled with audio disclaimers for complete audience transparency.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 5. Anonymous Sourcing -->
                    <div id="standard-5" class="space-y-4 pt-8 border-t border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 05</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            Anonymous Sourcing Standards
                        </h2>
                        <div class="space-y-4">
                            <p>
                                On-the-record quotes are our default requirement. Anonymity is granted only as a measure of last resort, subject to three strict conditions:
                            </p>
                            <ol class="list-decimal pl-6 space-y-1 text-sm font-sans text-slate-600">
                                <li>The information is of critical public importance and cannot be obtained through on-the-record sources.</li>
                                <li>The source faces severe professional, legal, or personal repercussions for disclosure.</li>
                                <li>The source's identity and credibility have been disclosed to and approved by the Editor-in-Chief or Managing Editor.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- 6. Opinion vs News -->
                    <div id="standard-6" class="space-y-4 pt-8 border-t border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 06</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            Clear Separation of Analysis, Opinion & News
                        </h2>
                        <p>
                            To maintain clarity for readers and automated search indexers, all opinion columns, guest essays, and subjective viewpoints are unmistakably categorized under prominent labels such as <strong>Opinion</strong>, <strong>Commentary</strong>, or <strong>Executive Perspective</strong>, separate from straight news reporting.
                        </p>
                    </div>

                    <!-- 7. Corrections Intake Desk -->
                    <div id="standard-7" class="space-y-4 pt-8 border-t border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] font-sans">Chapter 07</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] font-sans tracking-tight">
                            How to Submit a Formal Correction
                        </h2>
                        <p>
                            If you believe any story published by AQ NEWSWIRE contains an error, please submit your request to our dedicated Corrections Desk:
                        </p>
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 font-sans text-xs space-y-2">
                            <strong class="text-[#0A2540] block">Office of the Standards Editor</strong>
                            <span>Email: <a href="mailto:corrections@aqnewswire.com" class="text-[#635BFF] font-bold">corrections@aqnewswire.com</a></span><br>
                            <span>Please include: Article URL, date of publication, the specific text in question, and documentation or primary source evidence verifying the requested correction.</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>
@endsection
