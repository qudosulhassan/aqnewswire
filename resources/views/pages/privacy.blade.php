@extends('layouts.app')

@section('title', 'Privacy Policy & Data Protection | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Official Privacy Policy of AQ NEWSWIRE Media Group. Learn how we collect, process, safeguard, and respect your personal data under GDPR, CCPA/CPRA, and international privacy laws.')

@push('head')
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "@type": "WebPage",
  "name": "Privacy Policy",
  "url": "{{ route('privacy') }}",
  "description": "Privacy policy and personal data protection procedures of AQ NEWSWIRE.",
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
                <span class="text-[#635BFF]">Privacy Policy</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200/70 text-slate-700 text-xs font-bold uppercase tracking-wider">
                    <span>GDPR & CCPA/CPRA Compliant &middot; Last Revised: {{ now()->format('F j, Y') }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0A2540] tracking-tight leading-tight font-headline">
                    Privacy Policy & Data Governance.
                </h1>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} is committed to safeguarding the privacy, confidentiality, and data sovereignty of our global readership.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content Policy Text -->
    <section class="py-14 lg:py-16">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Table of Contents Sidebar (4 cols) -->
                <div class="lg:col-span-4 sticky top-24 space-y-4">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 text-xs space-y-3">
                        <span class="font-bold text-[#0A2540] uppercase tracking-wider block">Policy Sections</span>
                        <ul class="space-y-2 text-slate-600 font-medium">
                            <li><a href="#section-1" class="hover:text-[#635BFF] transition block">1. Identity of Data Controller</a></li>
                            <li><a href="#section-2" class="hover:text-[#635BFF] transition block">2. Information We Collect</a></li>
                            <li><a href="#section-3" class="hover:text-[#635BFF] transition block">3. Lawful Basis for Processing</a></li>
                            <li><a href="#section-4" class="hover:text-[#635BFF] transition block">4. Analytics, Telemetry & Cookies</a></li>
                            <li><a href="#section-5" class="hover:text-[#635BFF] transition block">5. Third-Party Data Disclosures</a></li>
                            <li><a href="#section-6" class="hover:text-[#635BFF] transition block">6. Your Rights (GDPR & CCPA)</a></li>
                            <li><a href="#section-7" class="hover:text-[#635BFF] transition block">7. Retention & International Transfers</a></li>
                            <li><a href="#section-8" class="hover:text-[#635BFF] transition block">8. Data Protection Officer Contact</a></li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 text-[11px] text-indigo-900 leading-relaxed">
                        <strong>Zero Sale of Personal Data:</strong> We do not sell, rent, or trade reader personal data, browsing histories, or contact information to data brokers under any circumstances.
                    </div>
                </div>

                <!-- Legal Policy Body (8 cols) -->
                <div class="lg:col-span-8 space-y-10 text-slate-700 leading-relaxed font-serif text-base lg:text-lg">
                    
                    <div id="section-1" class="space-y-3">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">1. Identity of the Data Controller</h2>
                        <p>
                            This Privacy Policy governs the processing of personal data by <strong>{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} Media Group Inc.</strong> ("AQ NEWSWIRE", "we", "us", or "our"), with registered corporate address at {{ \App\Models\Setting::get('office_address', '100 Financial District, New York, NY 10005') }}.
                        </p>
                        <p>
                            If you access our dispatches, digital applications, newsletters, or multimedia services from within the European Economic Area (EEA), the United Kingdom, or Switzerland, AQ NEWSWIRE acts as the data controller under the General Data Protection Regulation (Regulation (EU) 2016/679).
                        </p>
                    </div>

                    <div id="section-2" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">2. Information We Collect</h2>
                        <p>
                            We collect only the minimum personal data strictly necessary to deliver high-quality editorial experiences:
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                            <li><strong>Account & Subscription Data:</strong> Name, professional email address, encrypted authentication credentials, subscription tier, and communication preferences.</li>
                            <li><strong>Reader Interactions:</strong> Reading list bookmarks, newsletter subscriptions, search terms entered in our command palette, and interactive feedback.</li>
                            <li><strong>Device & Telemetry Data:</strong> IP addresses (anonymized for analytics), browser user agent, operating system, approximate geographic location (country/city level), and referring URL.</li>
                            <li><strong>Communications:</strong> Records of editorial tips, contact inquiries, or corrections requests voluntarily transmitted to our newsroom bureaus.</li>
                        </ul>
                    </div>

                    <div id="section-3" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">3. Lawful Basis for Processing (GDPR Article 6)</h2>
                        <p>
                            Under European data protection standards, we process your personal information under the following defined legal bases:
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                            <li><strong>Performance of Contract:</strong> To manage your reader account, process paid editorial subscriptions, and deliver daily requested newsletters.</li>
                            <li><strong>Legitimate Interests:</strong> To maintain technical infrastructure security, defend against distributed denial-of-service (DDoS) attacks, detect fraud, and optimize our editorial layout.</li>
                            <li><strong>Consent:</strong> Where explicitly provided, such as opting in to push notifications, non-essential cookies, or marketing alerts. You retain the right to withdraw consent at any time.</li>
                            <li><strong>Compliance with Legal Obligations:</strong> To adhere to applicable statutory corporate record-keeping and regulatory directives.</li>
                        </ul>
                    </div>

                    <div id="section-4" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">4. Analytics, Telemetry & Cookies</h2>
                        <p>
                            We utilize first-party session management cookies and privacy-preserving analytics to evaluate reader traffic patterns. For complete details on specific cookie categories, lifespan, and opt-out mechanisms, please review our comprehensive <a href="{{ route('cookies') }}" class="text-[#635BFF] hover:underline font-sans font-semibold">Cookie Policy</a>.
                        </p>
                    </div>

                    <div id="section-5" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">5. Third-Party Service Providers & Data Disclosures</h2>
                        <p>
                            We do not sell personal data. We disclose information to vetted enterprise processors strictly under rigorous Data Processing Agreements (DPAs):
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                            <li><strong>Hosting & Edge Delivery:</strong> Cloud infrastructure, content delivery networks (CDNs), and SSL encryption services.</li>
                            <li><strong>Transactional Communications:</strong> Email transmission gateways utilized to deliver account verification and editorial digests.</li>
                            <li><strong>Billing & Payment Gateways:</strong> PCI-DSS Level 1 compliant payment networks. We do not store raw credit card numbers on our servers.</li>
                        </ul>
                    </div>

                    <div id="section-6" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">6. Your Rights Under GDPR & CCPA/CPRA</h2>
                        <p>
                            Irrespective of your geographical jurisdiction, {{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }} affords readers comprehensive data sovereignty rights:
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-sm font-sans text-slate-600">
                            <li><strong>Right to Access:</strong> You may request a complete export of all personal data held concerning your identity.</li>
                            <li><strong>Right to Rectification:</strong> You may update or correct inaccurate profile credentials at any time via your <a href="{{ route('account.dashboard') }}" class="text-[#635BFF] hover:underline">Account Settings</a>.</li>
                            <li><strong>Right to Erasure ("Right to be Forgotten"):</strong> You may request complete deletion of your account, saved reading lists, and associated telemetry.</li>
                            <li><strong>Right to Object & Restrict Processing:</strong> You may restrict processing or opt out of editorial telemetry.</li>
                            <li><strong>CCPA "Do Not Sell or Share My Information":</strong> We affirm that we do not sell or share consumer personal information for cross-context behavioral advertising.</li>
                        </ul>
                    </div>

                    <div id="section-7" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">7. Data Retention & Cross-Border Transfers</h2>
                        <p>
                            Personal data is retained only for as long as your reader account remains active or as required to fulfill statutory reporting obligations. When cross-border transfers occur between our European bureaus and US infrastructure, we implement Standard Contractual Clauses (SCCs) approved by the European Commission.
                        </p>
                    </div>

                    <div id="section-8" class="space-y-3 pt-6 border-t border-slate-200">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] font-sans tracking-tight">8. Data Protection Officer (DPO) Contact</h2>
                        <p>
                            To exercise any statutory data right or submit inquiries regarding our privacy architecture, contact our designated privacy governance desk:
                        </p>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs font-sans space-y-1">
                            <strong class="text-[#0A2540] block">Office of the Data Protection Officer</strong>
                            <span>Email: <a href="mailto:privacy@aqnewswire.com" class="text-[#635BFF] font-bold">privacy@aqnewswire.com</a></span><br>
                            <span>Mailing: AQ NEWSWIRE Media Group, Attn: Privacy Office, {{ \App\Models\Setting::get('office_address', '100 Financial District, New York, NY 10005') }}</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>
@endsection
