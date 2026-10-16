@extends('layouts.app')

@section('title', 'Cookie Policy & Tracking Technologies | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Learn about how AQ NEWSWIRE uses cookies, local storage, and tracking technologies to optimize your reader experience, protect your sessions, and measure editorial analytics.')

@push('head')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "@type": "WebPage",
  "name": "Cookie Policy",
  "url": "{{ route('cookies') }}",
  "description": "Cookie policy and tracking disclosures for AQ NEWSWIRE.",
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
                <span class="text-[#635BFF]">Cookie Policy</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200/70 text-slate-700 text-xs font-bold uppercase tracking-wider">
                    <span>EU ePrivacy Directive & GDPR Compliant</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0A2540] tracking-tight leading-tight font-headline">
                    Cookie Policy & Storage Disclosures.
                </h1>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    How {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} utilizes cookies and technical storage to provide secure, fast, and personalized digital journalism.
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
                        <span class="font-bold text-[#0A2540] uppercase tracking-wider block">Policy Sections</span>
                        <ul class="space-y-2 text-slate-600 font-medium">
                            <li><a href="#cookie-1" class="hover:text-[#635BFF] transition block">1. What Are Cookies?</a></li>
                            <li><a href="#cookie-2" class="hover:text-[#635BFF] transition block">2. Categories of Cookies We Use</a></li>
                            <li><a href="#cookie-3" class="hover:text-[#635BFF] transition block">3. Detailed Cookie Inventory</a></li>
                            <li><a href="#cookie-4" class="hover:text-[#635BFF] transition block">4. Managing & Disabling Cookies</a></li>
                            <li><a href="#cookie-5" class="hover:text-[#635BFF] transition block">5. Third-Party Web Beacons</a></li>
                            <li><a href="#cookie-6" class="hover:text-[#635BFF] transition block">6. Inquiries & Policy Updates</a></li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                        <strong class="text-[#0A2540] block">Respect for Do Not Track (DNT)</strong>
                        <p class="text-[11px] leading-relaxed">
                            Our systems respect automated Global Privacy Control (GPC) signals transmitted by modern privacy browsers.
                        </p>
                    </div>
                </div>

                <!-- Cookie Body (8 cols) -->
                <div class="lg:col-span-8 space-y-10 text-slate-700 leading-relaxed font-serif text-base lg:text-lg">
                    
                    <div id="cookie-1" class="space-y-3">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">1. What Are Cookies & Web Storage?</h2>
                        <p>
                            Cookies are compact alphanumeric text files placed onto your computer, smartphone, or tablet when you visit digital publications. Cookies allow our web servers to recognize your session, preserve your reading preferences (e.g. customized typography font size, saved bookmarks), and ensure authentication security.
                        </p>
                        <p>
                            We also utilize modern browser storage mechanisms such as <code>localStorage</code> to cache recent search terms and reading list state without transmitting personal credentials over network requests.
                        </p>
                    </div>

                    <div id="cookie-2" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">2. Categories of Cookies We Use</h2>
                        <p>
                            We classify all cookies into four distinct operational tiers:
                        </p>
                        <div class="space-y-4 pt-2 font-sans text-xs">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                                <strong class="text-sm font-bold text-[#0A2540] block mb-1">A. Strictly Necessary Cookies (Essential)</strong>
                                <p class="text-slate-600 leading-relaxed">
                                    Required for the technical operation of AQ NEWSWIRE. Includes CSRF token protection against malicious cross-site forgery, session authentication, and load balancer distribution. These cannot be disabled without breaking core site functionality.
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                                <strong class="text-sm font-bold text-[#0A2540] block mb-1">B. Performance & Analytics Cookies</strong>
                                <p class="text-slate-600 leading-relaxed">
                                    Collect aggregated, privacy-preserving statistics regarding which articles, categories, and market desks are most frequently read. These allow us to measure traffic load and evaluate newsroom impact.
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                                <strong class="text-sm font-bold text-[#0A2540] block mb-1">C. Functionality & Preference Cookies</strong>
                                <p class="text-slate-600 leading-relaxed">
                                    Preserves reader settings, such as the in-article font scaler (A- / A / A+), audio narration playback speed (1x / 1.5x / 2x), and saved bookmarks.
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                                <strong class="text-sm font-bold text-[#0A2540] block mb-1">D. Advertising & Sponsorship Attribution</strong>
                                <p class="text-slate-600 leading-relaxed">
                                    Utilized to verify delivery of sponsored intelligence reports and native campaign impressions. We do not use invasive third-party cross-site behavioral tracking cookies.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="cookie-3" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">3. Detailed Cookie Inventory</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left font-sans text-xs border border-slate-200 rounded-xl overflow-hidden">
                                <thead class="bg-slate-100 text-slate-700 font-bold">
                                    <tr>
                                        <th class="p-3 border-b border-slate-200">Name</th>
                                        <th class="p-3 border-b border-slate-200">Provider</th>
                                        <th class="p-3 border-b border-slate-200">Purpose</th>
                                        <th class="p-3 border-b border-slate-200">Lifespan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-600">
                                    <tr>
                                        <td class="p-3 font-mono font-bold text-slate-800">XSRF-TOKEN</td>
                                        <td class="p-3">AQ NEWSWIRE</td>
                                        <td class="p-3">Security against Cross-Site Request Forgery</td>
                                        <td class="p-3">Session (2 hours)</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-mono font-bold text-slate-800">aq_session</td>
                                        <td class="p-3">AQ NEWSWIRE</td>
                                        <td class="p-3">Encrypted reader authentication session</td>
                                        <td class="p-3">Session / 30 days</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-mono font-bold text-slate-800">aq_font_size</td>
                                        <td class="p-3">AQ NEWSWIRE</td>
                                        <td class="p-3">Remembers reader font sizing preference</td>
                                        <td class="p-3">1 Year</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-mono font-bold text-slate-800">_ga / _gid</td>
                                        <td class="p-3">Google Analytics</td>
                                        <td class="p-3">Aggregated anonymous readership metrics</td>
                                        <td class="p-3">14 Months</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="cookie-4" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">4. How to Manage & Disable Cookies</h2>
                        <p>
                            You have the right to accept, reject, or delete cookies at any time via your browser settings:
                        </p>
                        <ul class="list-disc pl-6 space-y-1 text-sm font-sans text-slate-600">
                            <li><strong>Google Chrome:</strong> Settings &gt; Privacy and Security &gt; Third-party cookies.</li>
                            <li><strong>Apple Safari:</strong> Preferences &gt; Privacy &gt; Manage Website Data.</li>
                            <li><strong>Mozilla Firefox:</strong> Settings &gt; Privacy & Security &gt; Cookies and Site Data.</li>
                            <li><strong>Microsoft Edge:</strong> Settings &gt; Cookies and site permissions.</li>
                        </ul>
                        <p>
                            Please note that disabling strictly necessary cookies may prevent logging into your subscriber account or saving articles to your reading list.
                        </p>
                    </div>

                    <div id="cookie-5" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">5. Third-Party Web Beacons & Attribution</h2>
                        <p>
                            Our daily executive email newsletters may contain microscopic single-pixel images ("web beacons") that report aggregated open rates to our delivery gateway, verifying that critical financial dispatches are reaching subscribers without spam-filter suppression.
                        </p>
                    </div>

                    <div id="cookie-6" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">6. Questions Regarding Cookie Governance</h2>
                        <p>
                            For inquiries concerning our implementation of technical storage and tracking technologies, please contact <a href="mailto:privacy@aqnewswire.com" class="text-[#635BFF] font-sans font-bold hover:underline">privacy@aqnewswire.com</a>.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>
@endsection
