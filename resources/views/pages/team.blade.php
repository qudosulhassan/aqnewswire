@extends('layouts.app')

@section('title', 'Our Newsroom & Editorial Leadership | ' . \App\Models\Setting::get('site_name', 'AQ NEWSWIRE'))
@section('meta_description', 'Meet the journalists, editors, and senior correspondents who power AQ NEWSWIRE. Discover our newsroom leadership, beat expertise, and editorial credentials.')

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "AQ NEWSWIRE Editorial Team & Correspondents",
  "url": "{{ route('team') }}",
  "description": "The editorial masthead, correspondents, and beat reporters of AQ NEWSWIRE.",
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
    <section class="relative bg-[#F8FAFC] border-b border-slate-200/80 py-16 lg:py-20 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-[#635BFF]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-[#635BFF] transition">Home</a>
                <span>/</span>
                <span class="text-[#0A2540]">Company</span>
                <span>/</span>
                <span class="text-[#635BFF]">Our Team</span>
            </nav>

            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#635BFF]/10 text-[#635BFF] text-xs font-bold uppercase tracking-wider border border-[#635BFF]/20 shadow-xs">
                    <span>★ Masthead & Editorial Leadership</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0A2540] tracking-tight leading-[1.12] font-headline">
                    The Journalists Behind the Intelligence.
                </h1>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed pt-2">
                    Our newsroom brings together veteran financial investigative reporters, former Wall Street analysts, technology researchers, and foreign policy correspondents.
                </p>
            </div>
        </div>
    </section>

    <!-- Editorial Masthead Section -->
    <section class="py-16 border-b border-slate-200/80">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] block mb-1">Executive Leadership</span>
                <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight font-headline">
                    Editorial Board & Bureau Chiefs
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Responsible for journalistic integrity, multi-source verification, and investigative oversight.</p>
            </div>

            <!-- Leadership Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Leader 1 -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80" 
                                 alt="Alexander Vance" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-[#635BFF]/20">
                            <div>
                                <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-1.5">
                                    <span>Alexander Vance</span>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500" title="Verified Editor"></span>
                                </h3>
                                <span class="text-xs font-semibold text-[#635BFF] block">Editor-in-Chief</span>
                                <span class="text-[11px] text-slate-400">Former Senior Markets Correspondent, WSJ</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-serif">
                            Directs global editorial coverage across our six bureaus. Specializes in macroeconomic volatility, central banking policy, and sovereign debt restructuring.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mt-5">
                        <span class="font-medium">New York Bureau</span>
                        <a href="mailto:editorial@aqnewswire.com" class="text-[#635BFF] hover:underline font-bold">Inquiries &rarr;</a>
                    </div>
                </div>

                <!-- Leader 2 -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80" 
                                 alt="Sophia Thorne" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-indigo-500/20">
                            <div>
                                <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-1.5">
                                    <span>Sophia Thorne</span>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500" title="Verified Editor"></span>
                                </h3>
                                <span class="text-xs font-semibold text-[#635BFF] block">Head of Frontier Tech & AI</span>
                                <span class="text-[11px] text-slate-400">Ex-MIT Technology Review Editor</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-serif">
                            Oversees artificial intelligence, enterprise compute, and semiconductor supply chains. Author of numerous award-winning investigations into algorithmic governance.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mt-5">
                        <span class="font-medium">San Francisco / London</span>
                        <a href="mailto:editorial@aqnewswire.com" class="text-[#635BFF] hover:underline font-bold">Inquiries &rarr;</a>
                    </div>
                </div>

                <!-- Leader 3 -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" 
                                 alt="Marcus Sterling" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-emerald-500/20">
                            <div>
                                <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-1.5">
                                    <span>Marcus Sterling</span>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500" title="Verified Editor"></span>
                                </h3>
                                <span class="text-xs font-semibold text-[#635BFF] block">Chief Investigations Editor</span>
                                <span class="text-[11px] text-slate-400">Pulitzer Prize Finalist in Financial Reporting</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-serif">
                            Leads the investigative forensics bureau, examining corporate fraud, sovereign capital evasion, and confidential regulatory interventions.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mt-5">
                        <span class="font-medium">Washington / Zurich</span>
                        <a href="mailto:editorial@aqnewswire.com" class="text-[#635BFF] hover:underline font-bold">Inquiries &rarr;</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Active Newsroom Correspondents (Database & Roster) -->
    <section class="py-16 bg-[#F8FAFC] border-b border-slate-200/80">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#635BFF] block mb-1">Beat Correspondents</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight font-headline">
                        Accredited Newsroom Authors
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Discover published reports and ongoing investigations by individual journalists.</p>
                </div>
            </div>

            @if(isset($teamMembers) && $teamMembers->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($teamMembers as $member)
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3.5 mb-3">
                                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-12 h-12 rounded-full object-cover ring-1 ring-slate-200">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('authors.show', $member->slug) }}" class="text-sm font-bold text-[#0A2540] hover:text-[#635BFF] transition block truncate">
                                            {{ $member->name }}
                                        </a>
                                        <span class="text-xs text-slate-400 block truncate">{{ $member->title ?? $member->role_name }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-serif">
                                    {{ $member->bio ?? 'Accredited correspondent covering global commerce, regulatory shifts, and economic intelligence for AQ NEWSWIRE.' }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs mt-4">
                                <span class="text-slate-400">{{ $member->articles_count }} Published Reports</span>
                                <a href="{{ route('authors.show', $member->slug) }}" class="text-[#635BFF] font-bold hover:underline">
                                    View Archive &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Newsroom Standards Callout -->
    <section class="py-14 bg-white">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-[#0A2540] to-slate-900 text-white rounded-3xl p-8 sm:p-12 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-xl space-y-2">
                    <span class="text-[#635BFF] font-extrabold text-xs uppercase tracking-widest">Standards & Ethics</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight font-headline">
                        Committed to SPJ & Dow Jones Principles.
                    </h3>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        Every report published across our channels undergoes stringent editorial vetting. Read our formal corrections policy and verification procedures.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('editorial-standards') }}" class="px-6 py-3.5 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs font-bold rounded-xl shadow-lg transition">
                        View Editorial Standards &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
