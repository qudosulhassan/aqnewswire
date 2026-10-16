@extends('layouts.app')

@section('title', 'Terms of Service & Reader Agreement | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Terms of Service governing the use of AQ NEWSWIRE digital publications, financial reporting, newsletters, and multimedia services.')

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Terms of Service",
  "url": "{{ route('terms') }}",
  "description": "Terms of Service and subscriber agreement of AQ NEWSWIRE.",
  "publisher": {
    "@type": "NewsMediaOrganization",
    "name": "{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}",
    "url": "{{ url('/') }}"
  }
}
</script>
@endpush

@section('content')
<div class="bg-white text-slate-800">

    <!-- Header Section -->
    <section class="relative bg-[#F8FAFC] border-b border-slate-200/80 py-14 lg:py-16 overflow-hidden">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-4 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-[#635BFF] transition">Home</a>
                <span>/</span>
                <span class="text-[#0A2540]">Legal</span>
                <span>/</span>
                <span class="text-[#635BFF]">Terms of Service</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200/70 text-slate-700 text-xs font-bold uppercase tracking-wider">
                    <span>Effective Date: {{ now()->format('F j, Y') }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0A2540] tracking-tight leading-tight font-headline">
                    Terms of Service & Subscriber Agreement.
                </h1>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Please read these terms carefully before accessing or subscribing to {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-14 lg:py-16">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Table of Contents Sidebar (4 cols) -->
                <div class="lg:col-span-4 sticky top-24 space-y-4">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-xs space-y-3">
                        <span class="font-bold text-[#0A2540] uppercase tracking-wider block">Contract Sections</span>
                        <ul class="space-y-2 text-slate-600 font-medium">
                            <li><a href="#term-1" class="hover:text-[#635BFF] transition block">1. Acceptance of Terms</a></li>
                            <li><a href="#term-2" class="hover:text-[#635BFF] transition block">2. Financial Non-Advice Disclaimer</a></li>
                            <li><a href="#term-3" class="hover:text-[#635BFF] transition block">3. Intellectual Property & Copyright</a></li>
                            <li><a href="#term-4" class="hover:text-[#635BFF] transition block">4. DMCA Takedown Procedure</a></li>
                            <li><a href="#term-5" class="hover:text-[#635BFF] transition block">5. User Conduct & Comments</a></li>
                            <li><a href="#term-6" class="hover:text-[#635BFF] transition block">6. Disclaimer of Warranties</a></li>
                            <li><a href="#term-7" class="hover:text-[#635BFF] transition block">7. Limitation of Liability</a></li>
                            <li><a href="#term-8" class="hover:text-[#635BFF] transition block">8. Governing Law & Jurisdiction</a></li>
                        </ul>
                    </div>

                    <!-- Highlight Warning Box -->
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-900 leading-relaxed">
                        <strong class="block font-bold">Important Notice:</strong>
                        All reports, financial indices, and market analyses are published solely for general informational and educational journalism. AQ NEWSWIRE does not provide investment, legal, tax, or accounting advice.
                    </div>
                </div>

                <!-- Terms Body (8 cols) -->
                <div class="lg:col-span-8 space-y-10 text-slate-700 leading-relaxed font-serif text-base lg:text-lg">
                    
                    <div id="term-1" class="space-y-3">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">1. Acceptance of Terms</h2>
                        <p>
                            By visiting, browsing, registering for an account, or subscribing to <strong>{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}</strong> (the "Service"), operated by AQ NEWSWIRE Media Group Inc. ("we", "us", "our"), you agree to be legally bound by these Terms of Service and our incorporated Privacy Policy. If you do not agree to these terms, you must refrain from accessing our publications.
                        </p>
                    </div>

                    <div id="term-2" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">2. Editorial Financial Non-Advice Disclaimer (YMYL Mandate)</h2>
                        <p>
                            <strong>NOT FINANCIAL, INVESTMENT OR TAX ADVICE:</strong> The content provided through AQ NEWSWIRE—including articles, market tickers, indices, lists, audio dispatches, and opinion commentary—is published strictly for general journalistic, informational, and educational purposes.
                        </p>
                        <p>
                            Nothing published on this website constitutes an offer to buy or sell securities, a solicitation of an offer, or a recommendation of any security, portfolio of securities, transaction, or investment strategy. You should consult a qualified financial advisor, CPA, or attorney before executing any investment decision.
                        </p>
                    </div>

                    <div id="term-3" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">3. Intellectual Property, Copyright & Limited License</h2>
                        <p>
                            All original journalism, investigative articles, headlines, graphics, charts, audio narration, video recordings, databases, and code residing on AQ NEWSWIRE are the exclusive property of AQ NEWSWIRE Media Group Inc. or our content licensors, protected by United States and international copyright, trademark, and trade secret laws.
                        </p>
                        <p>
                            We grant you a personal, non-exclusive, non-transferable, revocable license to access and read our publications for personal, non-commercial use. Automated scraping, data extraction, or ingestion of our reporting into artificial intelligence models without an express commercial licensing agreement is strictly prohibited.
                        </p>
                    </div>

                    <div id="term-4" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">4. Digital Millennium Copyright Act (DMCA) Notice</h2>
                        <p>
                            We respect intellectual property rights. If you believe any content on our services infringes upon your copyright, please dispatch a formal DMCA Notice containing:
                        </p>
                        <ul class="list-disc pl-6 space-y-1 text-sm font-sans text-slate-600">
                            <li>Physical or electronic signature of the copyright owner or authorized representative.</li>
                            <li>Identification of the copyrighted work claimed to have been infringed.</li>
                            <li>Specific URL location of the allegedly infringing material on our domain.</li>
                            <li>Your contact details (name, mailing address, telephone number, email).</li>
                            <li>A statement made under penalty of perjury affirming good-faith belief of infringement.</li>
                        </ul>
                        <p class="text-xs font-sans text-slate-500">
                            Direct DMCA notices to: <a href="mailto:dmca@aqnewswire.com" class="text-[#635BFF] font-bold">dmca@aqnewswire.com</a>.
                        </p>
                    </div>

                    <div id="term-5" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">5. User Conduct & Reader Discussions</h2>
                        <p>
                            When utilizing interactive commentary, article saving, or community features, you agree not to submit defamatory, fraudulent, unlawful, abusive, or infringing commentary. We reserve the unrestricted right to moderate, hide, or terminate reader accounts that violate our civic discourse standards.
                        </p>
                    </div>

                    <div id="term-6" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">6. Disclaimer of Warranties</h2>
                        <p>
                            OUR SERVICES AND ALL CONTENT ARE PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE, OR NON-INFRINGEMENT.
                        </p>
                    </div>

                    <div id="term-7" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">7. Limitation of Liability</h2>
                        <p>
                            TO THE MAXIMUM EXTENT PERMITTED BY LAW, AQ NEWSWIRE MEDIA GROUP INC., ITS DIRECTORS, EMPLOYEES, JOURNALISTS, AND AFFILIATES SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING TRADING LOSSES, LOST PROFITS, OR BUSINESS INTERRUPTION ARISING FROM RELIANCE UPON OUR CONTENT.
                        </p>
                    </div>

                    <div id="term-8" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">8. Governing Law & Dispute Resolution</h2>
                        <p>
                            These Terms shall be governed by and construed in accordance with the laws of the State of New York and applicable federal laws of the United States of America, without regard to conflict of law principles. Any legal action arising hereunder shall be filed exclusively in state or federal courts situated in New York County, New York.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>
@endsection
