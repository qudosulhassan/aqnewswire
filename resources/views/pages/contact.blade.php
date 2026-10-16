@extends('layouts.app')

@section('title', 'Contact & Global Newsroom Bureaus | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Contact the global newsroom, editorial desks, bureau chiefs, and whistleblower intake team at AQ NEWSWIRE. Global offices in New York, London, and Singapore.')

@push('head')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact AQ NEWSWIRE",
  "url": "{{ route('contact') }}",
  "description": "Global bureau directory and contact information for AQ NEWSWIRE.",
  "mainEntity": {
    "@type": "NewsMediaOrganization",
    "name": "{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}",
    "email": "{{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }}",
    "telephone": "{{ \App\Models\Setting::get('contact_phone', '+1-555-019-2834') }}",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "{{ \App\Models\Setting::get('office_address', '100 Financial District') }}",
      "addressLocality": "New York",
      "addressRegion": "NY",
      "postalCode": "10005",
      "addressCountry": "US"
    }
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
                <span class="text-[#0A2540]">Company</span>
                <span>/</span>
                <span class="text-[#635BFF]">Contact & Bureaus</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#635BFF]/10 text-[#635BFF] text-xs font-bold uppercase tracking-wider border border-[#635BFF]/20 shadow-xs">
                    <span>★ Direct Newsroom Communications</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0A2540] tracking-tight leading-[1.12] font-headline">
                    Global News Bureaus & Inquiries.
                </h1>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed pt-2">
                    Reach our editorial desks, bureau editors, investigative tip line, and corporate communications teams worldwide.
                </p>
            </div>
        </div>
    </section>

    <!-- Global Bureaus Direct Grid -->
    <section class="py-14 border-b border-slate-200/80">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Bureau 1: General & Executive -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-[#635BFF] flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#0A2540] mb-1">Corporate & General</h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            Executive communications, institutional media syndication, and corporate governance correspondence.
                        </p>
                    </div>
                    <div>
                        <a href="mailto:{{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }}" class="text-xs font-bold text-[#635BFF] hover:underline">
                            {{ \App\Models\Setting::get('primary_email', 'contact@aqnewswire.com') }} &rarr;
                        </a>
                    </div>
                </div>

                <!-- Bureau 2: Newsroom & Editorial Tips -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#0A2540] mb-1">Editorial Newsroom</h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            Confidential story tips, whistleblower materials, embargoed research briefs, and interview requests.
                        </p>
                    </div>
                    <div>
                        <a href="mailto:{{ \App\Models\Setting::get('editorial_email', 'editorial@aqnewswire.com') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            {{ \App\Models\Setting::get('editorial_email', 'editorial@aqnewswire.com') }} &rarr;
                        </a>
                    </div>
                </div>

                <!-- Bureau 3: Corrections Desk -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#0A2540] mb-1">Corrections & Standards</h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            Formal factual clarification requests. All notices receive priority review by the Standards Editor.
                        </p>
                    </div>
                    <div>
                        <a href="mailto:corrections@aqnewswire.com" class="text-xs font-bold text-emerald-600 hover:underline">
                            corrections@aqnewswire.com &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Grid: Transmission Form & Bureau Directory -->
    <section class="py-16">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Transmission Form (7 cols) -->
                <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-3xl p-8 sm:p-10 shadow-sm"
                     x-data="{
                         name: '',
                         email: '',
                         department: 'editorial',
                         subject: '',
                         message: '',
                         loading: false,
                         submitted: false,
                         statusMsg: '',
                         async sendTransmission() {
                             if (!this.name || !this.email || !this.message || this.loading) return;
                             this.loading = true;
                             try {
                                 const token = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');
                                 const res = await fetch('{{ route('contact.submit') }}', {
                                     method: 'POST',
                                     headers: {
                                         'Content-Type': 'application/json',
                                         'X-CSRF-TOKEN': token || '',
                                         'Accept': 'application/json'
                                     },
                                     body: JSON.stringify({
                                         name: this.name,
                                         email: this.email,
                                         department: this.department,
                                         subject: this.subject,
                                         message: this.message
                                     })
                                 });
                                 const data = await res.json();
                                 if (res.ok && data.success) {
                                     this.submitted = true;
                                     this.statusMsg = data.message;
                                     this.message = '';
                                     this.subject = '';
                                     if (window.showToast) window.showToast('Transmission received successfully', 'success');
                                 } else {
                                     const err = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Error sending message');
                                     if (window.showToast) window.showToast(err, 'error');
                                 }
                             } catch (e) {
                                 if (window.showToast) window.showToast('Network error submitting transmission', 'error');
                             } finally {
                                 this.loading = false;
                             }
                         }
                     }">
                    
                    <div class="mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] block mb-1">Direct Secure Dispatch</span>
                        <h2 class="text-2xl font-black text-[#0A2540] tracking-tight font-headline">
                            Send a Transmission to the Editorial Desk
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Directly routed to the responsible desk editor. Fields marked are required.</p>
                    </div>

                    <!-- Success Notice -->
                    <div x-show="submitted" x-cloak class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <div>
                            <strong class="block font-bold">Transmission Successfully Delivered</strong>
                            <p class="mt-0.5" x-text="statusMsg"></p>
                        </div>
                    </div>

                    <form @submit.prevent="sendTransmission()" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name *</label>
                                <input type="text" x-model="name" required placeholder="Alexander Hamilton" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Work / Primary Email *</label>
                                <input type="email" x-model="email" required placeholder="alexander@domain.com" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Department *</label>
                                <select x-model="department" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                                    <option value="editorial">Newsroom & Editorial Pitch</option>
                                    <option value="investigations">Investigative Leaks (Confidential)</option>
                                    <option value="corrections">Corrections & Standards Desk</option>
                                    <option value="advertising">Commercial & Partnerships</option>
                                    <option value="legal">Legal & Compliance</option>
                                    <option value="general">General Inquiries</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Subject *</label>
                                <input type="text" x-model="subject" required placeholder="Regarding: Market coverage tip..." 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Message / Documentation Details *</label>
                            <textarea x-model="message" rows="5" required placeholder="Provide clear factual details, dates, relevant document citations or inquiry specifics..." 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Strict journalistic confidentiality honored.</span>
                            <button type="submit" 
                                    :disabled="loading"
                                    class="px-6 py-3 bg-[#635BFF] hover:bg-[#5349DF] disabled:bg-slate-400 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                                <span x-show="!loading">Send Transmission &rarr;</span>
                                <span x-show="loading">Encrypting & Sending...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Global Bureau Directory (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6">
                        <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-2">
                            <span>Global Bureau Locations</span>
                        </h3>

                        <!-- Bureau 1 -->
                        <div class="border-b border-slate-200 pb-4 space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <strong class="text-sm font-bold text-[#0A2540]">New York (Headquarters)</strong>
                                <span class="text-[10px] uppercase font-bold text-slate-400">EST &middot; UTC-5</span>
                            </div>
                            <p class="text-slate-600 leading-relaxed">{{ \App\Models\Setting::get('office_address', '100 Financial District, New York, NY 10005') }}</p>
                            <span class="text-slate-500 block">Tel: {{ \App\Models\Setting::get('contact_phone', '+1 (555) 019-2834') }}</span>
                        </div>

                        <!-- Bureau 2 -->
                        <div class="border-b border-slate-200 pb-4 space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <strong class="text-sm font-bold text-[#0A2540]">London Bureau</strong>
                                <span class="text-[10px] uppercase font-bold text-slate-400">GMT &middot; UTC+0</span>
                            </div>
                            <p class="text-slate-600 leading-relaxed">25 Bank Street, Canary Wharf, London E14 5JP, United Kingdom</p>
                            <span class="text-slate-500 block">Europe Financial Desk</span>
                        </div>

                        <!-- Bureau 3 -->
                        <div class="border-b border-slate-200 pb-4 space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <strong class="text-sm font-bold text-[#0A2540]">Singapore Bureau</strong>
                                <span class="text-[10px] uppercase font-bold text-slate-400">SGT &middot; UTC+8</span>
                            </div>
                            <p class="text-slate-600 leading-relaxed">Marina Bay Financial Centre Tower 1, Singapore 018981</p>
                            <span class="text-slate-500 block">Asia-Pacific Capital Markets</span>
                        </div>

                        <!-- Bureau 4 -->
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <strong class="text-sm font-bold text-[#0A2540]">Zurich Bureau</strong>
                                <span class="text-[10px] uppercase font-bold text-slate-400">CET &middot; UTC+1</span>
                            </div>
                            <p class="text-slate-600 leading-relaxed">Bahnhofstrasse 45, 8001 Zurich, Switzerland</p>
                            <span class="text-slate-500 block">Private Capital & Wealth Investigations</span>
                        </div>
                    </div>

                    <!-- Whistleblower Security Callout -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="text-[#0A2540] font-bold block">Whistleblower Protection</strong>
                            <p class="text-slate-500 leading-relaxed mt-0.5">
                                For high-risk materials or sensitive regulatory documents, correspondents support Signal and PGP encryption upon request.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
