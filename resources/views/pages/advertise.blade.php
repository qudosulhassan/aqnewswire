@extends('layouts.app')

@section('title', 'Advertise & Corporate Partnerships | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Partner with AQ NEWSWIRE to reach high-net-worth investors, institutional decision makers, and C-suite innovators across 140+ countries. Media kit and partnership opportunities.')

@push('head')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "@type": "WebPage",
  "name": "Advertise with AQ NEWSWIRE",
  "url": "{{ route('advertise') }}",
  "description": "Commercial advertising and corporate partnership solutions across AQ NEWSWIRE digital channels.",
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

    <!-- Header Hero Section -->
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
                <span class="text-[#635BFF]">Advertise</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#635BFF]/10 text-[#635BFF] text-xs font-bold uppercase tracking-wider border border-[#635BFF]/20 shadow-xs">
                    <span>★ Institutional Advertising Solutions</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0A2540] tracking-tight leading-[1.12] font-headline">
                    Reach Global Decision Makers Where It Matters.
                </h1>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed pt-2">
                    Connect your enterprise brand, financial institution, or technology platform with 250,000+ senior executives, investors, and policymakers daily.
                </p>
            </div>
        </div>
    </section>

    <!-- Audience Demographics Strip -->
    <section class="border-b border-slate-200/80 bg-white py-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-[#0A2540] block tracking-tight font-headline">78%</span>
                    <span class="text-xs font-semibold text-slate-600 mt-1 block">C-Suite & VP Level Leaders</span>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Founders, managing partners, and senior corporate officers.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-[#635BFF] block tracking-tight font-headline">$2.4M</span>
                    <span class="text-xs font-semibold text-slate-600 mt-1 block">Average Net Investable Assets</span>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Institutional capital allocators and private wealth investors.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-[#0A2540] block tracking-tight font-headline">4.8 min</span>
                    <span class="text-xs font-semibold text-slate-600 mt-1 block">Average Attention Duration</span>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Deep engagement across long-form analysis and executive briefs.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-3xl sm:text-4xl font-black text-emerald-600 block tracking-tight font-headline">140+</span>
                    <span class="text-xs font-semibold text-slate-600 mt-1 block">Global Sovereign Markets</span>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Distributed readership across North America, Europe, and Asia-Pacific.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Advertising Products Grid -->
    <section class="py-16 border-b border-slate-200/80">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] block mb-1">Partnership Formats</span>
                <h2 class="text-2xl sm:text-4xl font-black text-[#0A2540] tracking-tight font-headline">
                    Bespoke Commercial Solutions
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Engineered to integrate seamlessly with our luxury editorial environment.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Format 1 -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-indigo-300 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-[#635BFF] flex items-center justify-center font-bold text-xs mb-4">
                            SI
                        </div>
                        <h3 class="text-lg font-bold text-[#0A2540] mb-2">Sponsored Intelligence & Native Reports</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-serif">
                            Co-created research papers and market briefs produced alongside our commercial studio. Compliant with FTC disclosure mandates and permanently archived.
                        </p>
                    </div>
                    <ul class="text-[11px] text-slate-500 space-y-1.5 pt-4 border-t border-slate-100 mt-4">
                        <li>&bull; Prominent homepage & category desk placement</li>
                        <li>&bull; Dedicated executive PDF download package</li>
                        <li>&bull; Guaranteed institutional impressions</li>
                    </ul>
                </div>

                <!-- Format 2 -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-indigo-300 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mb-4">
                            NL
                        </div>
                        <h3 class="text-lg font-bold text-[#0A2540] mb-2">Executive Newsletter Sponsorship</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-serif">
                            Direct, 100% share-of-voice sponsorship inside our daily morning executive dispatches, reaching 250,000+ verified corporate inboxes before markets open.
                        </p>
                    </div>
                    <ul class="text-[11px] text-slate-500 space-y-1.5 pt-4 border-t border-slate-100 mt-4">
                        <li>&bull; 44% average institutional open rate</li>
                        <li>&bull; Exclusive single-sponsor header & mid-roll unit</li>
                        <li>&bull; High-intent C-suite click-through engagement</li>
                    </ul>
                </div>

                <!-- Format 3 -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-indigo-300 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs mb-4">
                            AV
                        </div>
                        <h3 class="text-lg font-bold text-[#0A2540] mb-2">Audio, Video & Rankings Spotlight</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-serif">
                            Presenting sponsorships across our proprietary Lists & Rankings (e.g. World's Billionaires, Top AI Pioneers) and executive podcast interview series.
                        </p>
                    </div>
                    <ul class="text-[11px] text-slate-500 space-y-1.5 pt-4 border-t border-slate-100 mt-4">
                        <li>&bull; Pre-roll audio branding on AI & market dispatches</li>
                        <li>&bull; Exclusive co-branded interactive rankings rail</li>
                        <li>&bull; Custom social & institutional distribution</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Inquiry & RFP Form -->
    <section class="py-16">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Inquiry Form (7 cols) -->
                <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-3xl p-8 sm:p-10 shadow-sm"
                     x-data="{
                         name: '',
                         company: '',
                         work_email: '',
                         budget: '$10,000 - $25,000',
                         campaign_type: 'Sponsored Content & Native',
                         message: '',
                         loading: false,
                         submitted: false,
                         statusMsg: '',
                         async sendInquiry() {
                             if (!this.name || !this.company || !this.work_email || !this.message || this.loading) return;
                             this.loading = true;
                             try {
                                 const token = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');
                                 const res = await fetch('{{ route('advertise.submit') }}', {
                                     method: 'POST',
                                     headers: {
                                         'Content-Type': 'application/json',
                                         'X-CSRF-TOKEN': token || '',
                                         'Accept': 'application/json'
                                     },
                                     body: JSON.stringify({
                                         name: this.name,
                                         company: this.company,
                                         work_email: this.work_email,
                                         budget: this.budget,
                                         campaign_type: this.campaign_type,
                                         message: this.message
                                     })
                                 });
                                 const data = await res.json();
                                 if (res.ok && data.success) {
                                     this.submitted = true;
                                     this.statusMsg = data.message;
                                     this.message = '';
                                     if (window.showToast) window.showToast('Inquiry received successfully', 'success');
                                 } else {
                                     const err = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Error submitting inquiry');
                                     if (window.showToast) window.showToast(err, 'error');
                                 }
                             } catch (e) {
                                 if (window.showToast) window.showToast('Network error submitting inquiry', 'error');
                             } finally {
                                 this.loading = false;
                             }
                         }
                     }">
                    
                    <div class="mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] block mb-1">Partnership Request</span>
                        <h2 class="text-2xl font-black text-[#0A2540] tracking-tight font-headline">
                            Request Media Kit & Campaign Consultation
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Our commercial solutions team will respond with inventory availability and pricing.</p>
                    </div>

                    <!-- Success Notice -->
                    <div x-show="submitted" x-cloak class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <div>
                            <strong class="block font-bold">RFP Received</strong>
                            <p class="mt-0.5" x-text="statusMsg"></p>
                        </div>
                    </div>

                    <form @submit.prevent="sendInquiry()" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Name *</label>
                                <input type="text" x-model="name" required placeholder="Elena Rostova" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Enterprise / Organization *</label>
                                <input type="text" x-model="company" required placeholder="Apex Global Capital" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Corporate Work Email *</label>
                                <input type="email" x-model="work_email" required placeholder="elena@apexcapital.com" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Planned Budget Range *</label>
                                <select x-model="budget" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                                    <option value="$10,000 - $25,000">$10,000 - $25,000</option>
                                    <option value="$25,000 - $50,000">$25,000 - $50,000</option>
                                    <option value="$50,000 - $100,000">$50,000 - $100,000</option>
                                    <option value="$100,000+">$100,000+ (Annual Partnership)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Primary Campaign Objective *</label>
                            <select x-model="campaign_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                                <option value="Sponsored Content & Native">Sponsored Intelligence & Native Research</option>
                                <option value="Executive Newsletter">Morning Executive Briefing Newsletter Sponsorship</option>
                                <option value="High-Impact Display">High-Impact Billboard & Category Takeovers</option>
                                <option value="Multimedia & Video">Video & Executive Podcast Series Sponsorship</option>
                                <option value="Integrated Multi-Channel">Multi-Channel Enterprise Campaign</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Campaign Details & Timeline *</label>
                            <textarea x-model="message" rows="4" required placeholder="Outline target audience, flight dates, deliverables, or desired outcomes..." 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Strict corporate confidentiality observed.</span>
                            <button type="submit" 
                                    :disabled="loading"
                                    class="px-6 py-3 bg-[#635BFF] hover:bg-[#5349DF] disabled:bg-slate-400 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                                <span x-show="!loading">Submit Partnership RFP &rarr;</span>
                                <span x-show="loading">Submitting...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Advertising Disclosure & Guidelines Sidebar (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-5">
                        <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-2">
                            <span>Advertising Standards & Policy</span>
                        </h3>
                        <div class="text-xs text-slate-600 space-y-3 leading-relaxed">
                            <p>
                                <strong>FTC & Google Guidelines Compliance:</strong> All paid integrations, commercial reports, and sponsored features are marked prominently with standardized disclosures (`Sponsored Intelligence`, `Presented By`, or `Partner Content`).
                            </p>
                            <p>
                                <strong>No Editorial Influence:</strong> Commercial partnerships never influence our news judgements, investigative reporting, or editorial conclusions. Our editorial newsroom operates under complete institutional independence.
                            </p>
                            <p>
                                <strong>Technical Transparency:</strong> All outbound commercial links strictly carry `rel="sponsored"` or `rel="nofollow"` attributes in full alignment with Google Webmaster Standards.
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Commercial Desk:</span>
                            <a href="mailto:advertising@aqnewswire.com" class="font-bold text-[#635BFF]">
                                advertising@aqnewswire.com
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
