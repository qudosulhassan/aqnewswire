@extends('layouts.admin')

@section('title', 'Sitemap URL Inventory — Technical SEO Control Center')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.seo.index') }}" class="hover:text-slate-900 transition">&larr; Technical SEO Control Center</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Sitemap URL Inventory</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Sitemap URL Inventory</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $inventory->total() }} Canonical Indexable URLs
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Authoritative registry of all public endpoints exposed to Googlebot and crawler discovery feeds.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('seo.sitemap') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Open Raw XML</span>
            </a>
            <a href="{{ route('admin.seo.sitemap.download') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Download XML</span>
            </a>
            <a href="{{ route('admin.seo.index') }}" class="px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition shadow-2xs">
                &larr; Back to SEO Center
            </a>
        </div>
    </div>

    <!-- Indexability Matrix Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        @foreach($matrix as $key => $row)
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">{{ $row['name'] }}</span>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $row['indexable'] }}</span>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Indexable</span>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Total: {{ $row['total'] }}</span>
                    @if($row['noindex'] > 0)
                        <span class="text-rose-600 font-semibold">{{ $row['noindex'] }} Noindex</span>
                    @elseif($row['orphans'] > 0)
                        <span class="text-amber-600 font-semibold">{{ $row['orphans'] }} Orphans</span>
                    @else
                        <span class="text-slate-400">100% Eligible</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.seo.sitemap.inventory') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 flex-1">
                <!-- Search input -->
                <div class="relative min-w-[260px] flex-1 max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by URL path or page title..." class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Type Filter -->
                <select name="type" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-slate-200 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                    <option value="">All Types (All Endpoints)</option>
                    <option value="article" {{ request('type') === 'article' ? 'selected' : '' }}>Articles & Stories</option>
                    <option value="category" {{ request('type') === 'category' ? 'selected' : '' }}>Categories & Channels</option>
                    <option value="ranking" {{ request('type') === 'ranking' ? 'selected' : '' }}>Rankings Lists</option>
                    <option value="author" {{ request('type') === 'author' ? 'selected' : '' }}>Author Profiles</option>
                    <option value="core" {{ request('type') === 'core' ? 'selected' : '' }}>Core Pages</option>
                </select>

                <!-- Per page -->
                <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-slate-200 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                </select>

                <button type="submit" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'type', 'per_page']))
                    <a href="{{ route('admin.seo.sitemap.inventory') }}" class="text-xs text-red-600 hover:underline font-medium">
                        Reset Filters
                    </a>
                @endif
            </div>

            <div class="text-xs text-slate-400 font-medium">
                Showing {{ $inventory->firstItem() ?? 0 }}–{{ $inventory->lastItem() ?? 0 }} of {{ $inventory->total() }} entries
            </div>
        </form>
    </div>

    <!-- Inventory Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4">Indexed URL & Title</th>
                        <th class="py-3 px-4">Entity Type</th>
                        <th class="py-3 px-4">Last Modified</th>
                        <th class="py-3 px-4">HTTP Status</th>
                        <th class="py-3 px-4">Canonical Status</th>
                        <th class="py-3 px-4">Robots</th>
                        <th class="py-3 px-4">Indexability</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inventory as $row)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- URL & Title -->
                            <td class="py-3 px-4 max-w-sm">
                                <span class="font-bold text-slate-900 block truncate" title="{{ $row['title'] }}">
                                    {{ $row['title'] }}
                                </span>
                                <span class="text-[11px] text-slate-500 font-mono block truncate" title="{{ $row['url'] }}">
                                    {{ $row['url'] }}
                                </span>
                            </td>

                            <!-- Entity Type -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $row['type'] === 'Article' ? 'bg-blue-50 text-blue-700' : ($row['type'] === 'Category' ? 'bg-purple-50 text-purple-700' : ($row['type'] === 'Author' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ $row['type'] }}
                                </span>
                            </td>

                            <!-- Last Modified -->
                            <td class="py-3 px-4 whitespace-nowrap text-slate-600 font-mono text-[11px]">
                                {{ $row['lastmod'] }}
                            </td>

                            <!-- HTTP Status -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold {{ $row['http_status'] === 200 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $row['http_status'] }}
                                </span>
                            </td>

                            <!-- Canonical Status -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($row['canonical_status'] === 'MATCH')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Match</span>
                                    </span>
                                @elseif($row['canonical_status'] === 'REDIRECTED')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        <span>Redirected</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span>Mismatch</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Robots -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono {{ str_contains(strtolower($row['robots']), 'noindex') ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $row['robots'] }}
                                </span>
                            </td>

                            <!-- Indexability -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $row['indexability'] === 'Indexable' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $row['indexability'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick="copyUrl('{{ $row['url'] }}', this)" class="p-1 text-slate-400 hover:text-slate-700 rounded transition" title="Copy URL">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                    <a href="{{ $row['url'] }}" target="_blank" rel="noopener" class="p-1 text-slate-400 hover:text-red-600 rounded transition" title="Open Public URL in new tab">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                    @if($row['type'] === 'Article' && !empty($row['model_id']))
                                        <a href="{{ route('admin.seo.articles.edit', $row['model_id']) }}" class="px-2 py-1 text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                                            Edit SEO
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-500">
                                No sitemap URLs matched the specified criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inventory->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $inventory->links() }}
            </div>
        @endif
    </div>

</div>

<script>
function copyUrl(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="text-emerald-600 text-[10px] font-bold">Copied!</span>';
        setTimeout(() => { btn.innerHTML = original; }, 1500);
    });
}
</script>
@endsection
