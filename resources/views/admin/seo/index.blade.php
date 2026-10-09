@extends('layouts.admin')

@section('title', 'Technical SEO Control Center — SEO Architecture & Discovery Center')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- 1. Header & Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-0.5">
                <span>SEO Architecture & Discovery Center</span>
                <span>&bull;</span>
                <span class="text-slate-700 font-semibold">Google Search Central Technical Alignment</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Technical SEO Control Center</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $auditReport['overall_status'] === 'Healthy' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($auditReport['overall_status'] === 'Critical' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                    {{ $auditReport['score'] }}% &bull; {{ $auditReport['overall_status'] }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Audit search engine visibility, XML discovery feeds, robots rules, canonical integrity, and search discovery signals.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <form action="{{ route('admin.seo.audit') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Run Full SEO Audit</span>
                </button>
            </form>
            <a href="{{ route('admin.seo.sitemap.inventory') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                Sitemap Inventory &rarr;
            </a>
            <a href="{{ route('admin.seo.articles') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                Content SEO Audit &rarr;
            </a>
            <a href="{{ route('admin.seo.redirects') }}" class="px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition shadow-2xs">
                Manage 301/302 Redirects &rarr;
            </a>
        </div>
    </div>

    <!-- Flash Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- 2. Priority SEO Alerts Banner -->
    @if(count($auditReport['action_items']) > 0)
        <div class="bg-amber-50/70 border border-amber-200/90 rounded-2xl p-4 shadow-2xs space-y-2.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Critical Search Engine Conflicts & Priority Recommendations</span>
                </span>
                <span class="text-[11px] text-amber-700 font-medium">Last Audited: {{ $auditReport['audited_at'] }}</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pt-1">
                @foreach($auditReport['action_items'] as $item)
                    <div class="bg-white/95 p-2.5 rounded-xl border border-amber-200/60 flex items-center justify-between gap-3 text-xs shadow-2xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $item['severity'] === 'critical' ? 'bg-rose-500' : ($item['severity'] === 'warning' ? 'bg-amber-500' : 'bg-blue-500') }} shrink-0"></span>
                            <span class="text-slate-800 font-medium">{{ $item['message'] }}</span>
                        </div>
                        <a href="{{ $item['action_url'] }}" class="text-[11px] font-bold text-red-600 hover:text-red-700 shrink-0">
                            {{ $item['action_label'] }} &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 3. 6 Live Database-Driven KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <!-- Indexable Stories -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Indexable Stories</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $indexableStories }}</span>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">In Sitemaps</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Eligible published stories</span>
        </div>

        <!-- Missing Descriptions -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Missing Descriptions</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black {{ $missingMetaDescription > 0 ? 'text-amber-600' : 'text-emerald-600' }} tracking-tight">
                    {{ $missingMetaDescription }}
                </span>
                <span class="text-[10px] font-bold {{ $missingMetaDescription > 0 ? 'text-amber-700 bg-amber-50' : 'text-emerald-700 bg-emerald-50' }} px-1.5 py-0.5 rounded">
                    {{ $missingMetaDescription > 0 ? 'Action Needed' : 'Complete' }}
                </span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Fallback to excerpt</span>
        </div>

        <!-- Missing Custom Titles -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Missing SEO Titles</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-800 tracking-tight">{{ $missingMetaTitle }}</span>
                <span class="text-[10px] font-medium text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">H1 Fallback</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Uses headline title</span>
        </div>

        <!-- Missing Canonicals -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Missing Canonicals</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-800 tracking-tight">{{ $missingCanonical }}</span>
                <span class="text-[10px] font-medium text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">Auto URL</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Auto-routes to slug</span>
        </div>

        <!-- Missing OG Images -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Missing Social Images</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-800 tracking-tight">{{ $missingFeaturedImage }}</span>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">OG Share</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Uses brand fallback</span>
        </div>

        <!-- Active 301 Redirects -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Active 301 Rules</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-blue-600 tracking-tight">{{ $active301 }}</span>
                <a href="{{ route('admin.seo.redirects') }}" class="text-[10px] font-bold text-blue-600 hover:underline">Manage</a>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">{{ $active302 }} temporary 302</span>
        </div>
    </div>

    <!-- 4. Technical Health Checklist (8 Verified Pillars) -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Technical SEO Health Status</h3>
                <p class="text-xs text-slate-500">Google Search Central automated infrastructure diagnostics.</p>
            </div>
            <span class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full font-bold">
                8/8 Core Checks Active
            </span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($auditReport['technical_health'] as $key => $th)
                <div class="p-3 rounded-xl border {{ $th['passed'] ? 'bg-slate-50/70 border-slate-200/70' : 'bg-amber-50/70 border-amber-200' }} flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full {{ $th['passed'] ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center shrink-0 mt-0.5 text-xs font-black">
                        {{ $th['passed'] ? '✓' : '!' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-xs font-bold text-slate-900 truncate">{{ $th['label'] }}</span>
                            <span class="text-[10px] font-semibold {{ $th['passed'] ? 'text-emerald-700' : 'text-amber-700' }}">{{ $th['status'] }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">{{ $th['detail'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 5. Indexation Overview Matrix -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Indexation Overview Matrix</h3>
                <p class="text-xs text-slate-500">Real database and crawler state across all publication models.</p>
            </div>
            <a href="{{ route('admin.seo.sitemap.inventory') }}" class="text-xs font-bold text-red-600 hover:text-red-700">
                Explore Full Inventory &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-3">Entity Type</th>
                        <th class="py-2.5 px-3 text-center">Indexable</th>
                        <th class="py-2.5 px-3 text-center">Noindex</th>
                        <th class="py-2.5 px-3 text-center">Blocked (Robots.txt)</th>
                        <th class="py-2.5 px-3 text-center">Redirected</th>
                        <th class="py-2.5 px-3 text-center">Potential Orphans</th>
                        <th class="py-2.5 px-3 text-right">Coverage Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($auditReport['indexability_matrix'] as $key => $row)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $row['name'] }}</td>
                            <td class="py-3 px-3 text-center font-bold text-emerald-600">{{ $row['indexable'] }}</td>
                            <td class="py-3 px-3 text-center font-medium {{ $row['noindex'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }}">{{ $row['noindex'] }}</td>
                            <td class="py-3 px-3 text-center font-medium text-slate-400">{{ $row['blocked'] }}</td>
                            <td class="py-3 px-3 text-center font-medium {{ $row['redirected'] > 0 ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $row['redirected'] }}</td>
                            <td class="py-3 px-3 text-center font-medium {{ $row['orphans'] > 0 ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $row['orphans'] }}</td>
                            <td class="py-3 px-3 text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $row['total'] }} Total Records
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 6. Search Engine Crawler Discovery Feeds -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Search Engine Crawler Discovery Feeds</h3>
                <p class="text-xs text-slate-500">Live technical endpoints consumed by Googlebot, Bingbot, and Google News aggregators.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">RFC 9309 & Sitemaps Protocol 0.9</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Card 1: Main XML Sitemap (P0 Prominence) -->
            <div class="bg-white rounded-2xl border-2 border-slate-300/80 p-5 shadow-xs flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-emerald-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-extrabold text-sm text-slate-900 tracking-tight">MAIN XML SITEMAP</span>
                        <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ strtoupper($auditReport['main_sitemap_validation']['status']) }}</span>
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Dynamic XML sitemap containing canonical indexable URLs across all public categories, rankings, authors, and stories.</p>

                    <!-- Technical Stats Box -->
                    <div class="mt-3.5 grid grid-cols-3 gap-2 p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/70 text-center text-xs">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">HTTP</span>
                            <span class="font-mono font-black text-emerald-700">{{ $auditReport['main_sitemap_validation']['http_status'] }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">URLS</span>
                            <span class="font-mono font-black text-slate-900">{{ $auditReport['main_sitemap_validation']['url_count'] }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">LASTMOD</span>
                            <span class="font-mono font-black text-emerald-700">{{ $auditReport['main_sitemap_validation']['lastmod_coverage'] }}%</span>
                        </div>
                    </div>

                    <!-- URL Display -->
                    <div class="mt-2.5 flex items-center justify-between bg-white p-2 rounded-lg border border-slate-200 font-mono text-[11px] text-slate-700 truncate">
                        <span class="truncate">{{ url('/sitemap.xml') }}</span>
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('seo.sitemap') }}" target="_blank" rel="noopener" class="px-2.5 py-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition inline-flex items-center gap-1 shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>OPEN XML</span>
                        </a>
                        <button type="button" onclick="openSitemapValidatorModal()" class="px-2.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            VALIDATE
                        </button>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="copyFeedUrl('{{ url('/sitemap.xml') }}', this)" class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition" title="Copy URL">
                            COPY URL
                        </button>
                        <a href="{{ route('admin.seo.sitemap.download') }}" class="p-1.5 text-slate-500 hover:text-slate-900 border border-slate-200 rounded-lg transition" title="Download sitemap.xml">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Google News XML Sitemap -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-blue-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-extrabold text-sm text-slate-900 tracking-tight">GOOGLE NEWS SITEMAP</span>
                        <span class="text-[10px] font-black text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <span>{{ strtoupper($auditReport['news_sitemap_validation']['status']) }}</span>
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Complies with Google News protocol; dynamically exposes indexable stories published within the trailing 48 hours.</p>

                    <!-- Technical Stats Box -->
                    <div class="mt-3.5 grid grid-cols-3 gap-2 p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/70 text-center text-xs">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">HTTP</span>
                            <span class="font-mono font-black text-emerald-700">{{ $auditReport['news_sitemap_validation']['http_status'] }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">RECENT</span>
                            <span class="font-mono font-black text-slate-900">{{ $auditReport['news_sitemap_validation']['url_count'] }} Stories</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">WINDOW</span>
                            <span class="font-mono font-black text-blue-700">48 Hours</span>
                        </div>
                    </div>

                    <!-- URL Display -->
                    <div class="mt-2.5 flex items-center justify-between bg-white p-2 rounded-lg border border-slate-200 font-mono text-[11px] text-slate-700 truncate">
                        <span class="truncate">{{ url('/sitemap-news.xml') }}</span>
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('seo.news_sitemap') }}" target="_blank" rel="noopener" class="px-2.5 py-1.5 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition inline-flex items-center gap-1">
                            <span>OPEN XML</span>
                        </a>
                        <button type="button" onclick="openNewsValidatorModal()" class="px-2.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            VALIDATE
                        </button>
                    </div>
                    <button type="button" onclick="copyFeedUrl('{{ url('/sitemap-news.xml') }}', this)" class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition">
                        COPY URL
                    </button>
                </div>
            </div>

            <!-- Card 3: Robots Directives -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-slate-700"></div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-extrabold text-sm text-slate-900 tracking-tight">ROBOTS DIRECTIVES</span>
                        <span class="text-[10px] font-black text-slate-700 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            <span>RFC 9309</span>
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Dynamic crawler security rules protecting `/admin/` and `/account/` pathways while advertising discovery sitemaps.</p>

                    <!-- Technical Stats Box -->
                    <div class="mt-3.5 grid grid-cols-3 gap-2 p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/70 text-center text-xs">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">HTTP</span>
                            <span class="font-mono font-black text-emerald-700">200</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">ADMIN</span>
                            <span class="font-mono font-black text-emerald-700">Blocked</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">PUBLIC</span>
                            <span class="font-mono font-black text-emerald-700">Allowed</span>
                        </div>
                    </div>

                    <!-- URL Display -->
                    <div class="mt-2.5 flex items-center justify-between bg-white p-2 rounded-lg border border-slate-200 font-mono text-[11px] text-slate-700 truncate">
                        <span class="truncate">{{ url('/robots.txt') }}</span>
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('seo.robots') }}" target="_blank" rel="noopener" class="px-2.5 py-1.5 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-lg transition inline-flex items-center gap-1">
                            <span>OPEN ROBOTS.TXT</span>
                        </a>
                        <button type="button" onclick="openRobotsValidatorModal()" class="px-2.5 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            VALIDATE
                        </button>
                    </div>
                    <button type="button" onclick="copyFeedUrl('{{ url('/robots.txt') }}', this)" class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition">
                        COPY URL
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- 7. Structured Data Center & Page Experience Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- Structured Data Center -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Schema.org Structured Data Center</h3>
                    <p class="text-xs text-slate-500">Rich snippet JSON-LD graphs rendered in public page HTML.</p>
                </div>
                <button type="button" onclick="openJsonLdViewerModal()" class="px-3 py-1 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                    VIEW JSON-LD &rarr;
                </button>
            </div>
            <div class="space-y-2">
                @foreach($auditReport['structured_data_checks'] as $key => $sd)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <div>
                                <span class="font-bold text-slate-900">{{ $sd['type'] }}</span>
                                <span class="text-slate-500 block text-[11px]">{{ $sd['description'] }}</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full shrink-0">
                            {{ $sd['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Page Experience & Google Search Console Connection -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Page Experience & Search Console</h3>
                    <p class="text-xs text-slate-500">Mobile usability, Core Web Vitals, and search portal integrations.</p>
                </div>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Field Data Verification
                </span>
            </div>

            <!-- Page Experience Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($auditReport['page_experience'] as $key => $pe)
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 text-xs">
                        <span class="text-[10px] font-bold uppercase text-slate-400 block truncate">{{ $pe['metric'] }}</span>
                        <span class="font-mono font-bold text-slate-900 mt-1 block truncate {{ $pe['value'] === 'NOT MEASURED' ? 'text-slate-400' : 'text-emerald-700' }}">
                            {{ $pe['value'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Google Search Console Connection Card -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold text-xs">
                        GSC
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-900">Google Search Console</span>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-200 px-1.5 py-0.5 rounded">NOT CONNECTED</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">CMS verifies internal technical readiness. Live indexing reports require Search Console ownership.</p>
                    </div>
                </div>
                <a href="https://search.google.com/search-console" target="_blank" rel="noopener" class="px-3 py-1.5 text-xs font-semibold text-slate-800 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition shrink-0 shadow-2xs">
                    Open GSC &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- 8. Content SEO Audit Preview Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Stories Requiring SEO Attention</h3>
                <p class="text-xs text-slate-500">Editorial stories with missing custom meta tags or relying on fallback defaults.</p>
            </div>
            <a href="{{ route('admin.seo.articles') }}" class="text-xs font-semibold text-red-600 hover:text-red-700">
                View All Stories ({{ $totalArticles }}) &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4">Story Headline</th>
                        <th class="py-3 px-4">SEO Title</th>
                        <th class="py-3 px-4">Meta Description</th>
                        <th class="py-3 px-4">Canonical URL</th>
                        <th class="py-3 px-4">Robots</th>
                        <th class="py-3 px-4">SEO Health</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($flaggedArticles as $art)
                        @php $health = $art->seo_health; @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 max-w-xs">
                                <a href="{{ route('admin.seo.articles.edit', $art) }}" class="font-bold text-slate-900 hover:text-red-600 transition block truncate">
                                    {{ $art->title }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-mono block truncate">/article/{{ $art->slug }}</span>
                            </td>

                            <td class="py-3 px-4 max-w-xs truncate">
                                @if(!empty($art->meta_title))
                                    <span class="text-slate-800 font-medium" title="{{ $art->meta_title }}">{{ Str::limit($art->meta_title, 35) }}</span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Headline Fallback</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-xs truncate">
                                @if(!empty($art->meta_description))
                                    <span class="text-slate-800" title="{{ $art->meta_description }}">{{ Str::limit($art->meta_description, 40) }}</span>
                                @else
                                    <span class="text-amber-700 bg-amber-50 px-2 py-0.5 rounded text-[10px] font-bold">Missing</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-xs truncate">
                                @if(!empty($art->canonical_url))
                                    <span class="font-mono text-[10px] text-slate-600">{{ $art->canonical_url }}</span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Auto Generated</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $art->robots && str_contains($art->robots, 'noindex') ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $art->robots ?? 'index, follow' }}
                                </span>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $health['status'] === 'healthy' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($health['status'] === 'critical' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ str_replace('_', ' ', $health['status']) }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.seo.articles.edit', $art) }}" class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                                        Edit SEO
                                    </a>
                                    <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="p-1 text-slate-400 hover:text-slate-700 rounded transition" title="Preview Public Story">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                All published stories have customized SEO tags. Outstanding work!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 9. Potential Orphan Content Section -->
    @if(count($auditReport['potential_orphans']) > 0)
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Potential Orphan Content</h3>
                    <p class="text-xs text-slate-500">Published stories with zero inbound internal links detected in other articles.</p>
                </div>
                <span class="text-xs text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full font-bold">
                    {{ count($auditReport['potential_orphans']) }} Stories Flagged
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                @foreach($auditReport['potential_orphans']->take(6) as $orphan)
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/60 flex items-center justify-between gap-2 text-xs">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-900 block truncate" title="{{ $orphan['article']->title }}">
                                {{ $orphan['article']->title }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono block truncate">
                                {{ $orphan['url'] }}
                            </span>
                        </div>
                        <a href="{{ route('admin.seo.articles.edit', $orphan['article']) }}" class="px-2 py-1 text-[11px] font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition shrink-0">
                            Inspect
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 10. Audit History Section -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Technical SEO Audit Trail History</h3>
                <p class="text-xs text-slate-500">Log of automated and manual diagnostic scans performed on this instance.</p>
            </div>
            <span class="text-xs text-slate-400">AuditLog System</span>
        </div>
        <div class="space-y-2">
            @forelse($auditReport['audit_history'] as $audit)
                <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/60 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <div>
                            <span class="font-bold text-slate-900">{{ $audit->details }}</span>
                            <span class="text-[10px] text-slate-400 block font-mono">Triggered by {{ $audit->user?->name ?? 'System Administrator' }} &bull; IP: {{ $audit->ip_address }}</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono text-slate-500 shrink-0">{{ $audit->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="p-4 text-center text-xs text-slate-400">
                    No recent audit trail entries. Click "Run Full SEO Audit" above to record a new entry.
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Interactive Modal 1: Sitemap Validator -->
<div id="sitemapValidatorModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900" id="validatorModalTitle">Sitemap Technical Validation</h3>
                <p class="text-xs text-slate-500" id="validatorModalSubtitle">Real-time local verification against Google Search Central requirements.</p>
            </div>
            <button type="button" onclick="closeModal('sitemapValidatorModal')" class="text-slate-400 hover:text-slate-800 text-lg font-bold">&times;</button>
        </div>

        <div id="validatorModalContent" class="space-y-3">
            <div class="p-4 text-center text-xs text-slate-500">Loading validator output...</div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeModal('sitemapValidatorModal')" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Close Inspector
            </button>
        </div>
    </div>
</div>

<!-- Interactive Modal 2: JSON-LD Structured Data Viewer -->
<div id="jsonLdViewerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Schema.org Structured Data Graph</h3>
                <p class="text-xs text-slate-500">Live JSON-LD output rendered in public article markup.</p>
            </div>
            <button type="button" onclick="closeModal('jsonLdViewerModal')" class="text-slate-400 hover:text-slate-800 text-lg font-bold">&times;</button>
        </div>

        <div>
            <pre id="jsonLdCodeBlock" class="p-4 bg-slate-900 text-slate-100 rounded-xl text-xs font-mono overflow-x-auto max-h-[500px]">Loading JSON-LD...</pre>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
            <button type="button" onclick="copyJsonLd()" class="px-3 py-1.5 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Copy JSON-LD
            </button>
            <button type="button" onclick="closeModal('jsonLdViewerModal')" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function copyFeedUrl(url, btn) {
    navigator.clipboard.writeText(url).then(function() {
        const original = btn.innerText;
        btn.innerText = 'COPIED!';
        btn.classList.add('text-emerald-700', 'border-emerald-300');
        setTimeout(() => {
            btn.innerText = original;
            btn.classList.remove('text-emerald-700', 'border-emerald-300');
        }, 1500);
    });
}

function openSitemapValidatorModal() {
    document.getElementById('validatorModalTitle').innerText = 'Main XML Sitemap Validator';
    document.getElementById('validatorModalSubtitle').innerText = 'Checking {{ url("/sitemap.xml") }} against Google Search Central specifications';
    document.getElementById('sitemapValidatorModal').classList.remove('hidden');

    fetch('{{ route("admin.seo.sitemap.validate", ["type" => "main"]) }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => renderValidatorResults(data))
    .catch(err => {
        document.getElementById('validatorModalContent').innerHTML = '<div class="p-4 text-center text-rose-600 font-bold">Failed to execute validator.</div>';
    });
}

function openNewsValidatorModal() {
    document.getElementById('validatorModalTitle').innerText = 'Google News XML Sitemap Validator';
    document.getElementById('validatorModalSubtitle').innerText = 'Checking {{ url("/sitemap-news.xml") }} trailing 48-hour indexable stories';
    document.getElementById('sitemapValidatorModal').classList.remove('hidden');

    fetch('{{ route("admin.seo.sitemap.validate", ["type" => "news"]) }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => renderValidatorResults(data))
    .catch(err => {
        document.getElementById('validatorModalContent').innerHTML = '<div class="p-4 text-center text-rose-600 font-bold">Failed to execute validator.</div>';
    });
}

function openRobotsValidatorModal() {
    document.getElementById('validatorModalTitle').innerText = 'Robots.txt Directive Validator';
    document.getElementById('validatorModalSubtitle').innerText = 'Checking {{ url("/robots.txt") }} crawler shielding and sitemap declarations';
    document.getElementById('sitemapValidatorModal').classList.remove('hidden');

    fetch('{{ route("admin.seo.robots.validate") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => renderValidatorResults(data))
    .catch(err => {
        document.getElementById('validatorModalContent').innerHTML = '<div class="p-4 text-center text-rose-600 font-bold">Failed to execute validator.</div>';
    });
}

function renderValidatorResults(data) {
    let html = `
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-900">Target Endpoint</span>
                <span class="font-mono text-[11px] text-slate-500 block">${data.endpoint || data.url}</span>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-black ${data.status === 'Healthy' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                ${data.status}
            </span>
        </div>
        <div class="space-y-2 mt-3">
    `;

    (data.checks || []).forEach(check => {
        html += `
            <div class="p-2.5 rounded-xl border ${check.passed ? 'bg-emerald-50/40 border-emerald-200/60' : 'bg-rose-50/40 border-rose-200/60'} flex items-start gap-2.5 text-xs">
                <span class="w-5 h-5 rounded-full ${check.passed ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'} flex items-center justify-center font-bold text-[11px] shrink-0">
                    ${check.passed ? '✓' : '✕'}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900">${check.name}</span>
                        <span class="text-[10px] font-bold ${check.passed ? 'text-emerald-700' : 'text-rose-700'}">${check.label}</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-0.5">${check.detail}</p>
                </div>
            </div>
        `;
    });

    html += `</div>`;
    document.getElementById('validatorModalContent').innerHTML = html;
}

function openJsonLdViewerModal() {
    document.getElementById('jsonLdViewerModal').classList.remove('hidden');
    document.getElementById('jsonLdCodeBlock').innerText = 'Fetching rendered structured data...';

    fetch('{{ route("admin.seo.jsonld") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('jsonLdCodeBlock').innerText = JSON.stringify(data, null, 2);
    })
    .catch(err => {
        document.getElementById('jsonLdCodeBlock').innerText = 'Failed to load JSON-LD graph.';
    });
}

function copyJsonLd() {
    const text = document.getElementById('jsonLdCodeBlock').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Structured data JSON-LD copied to clipboard!');
    });
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>
@endsection
