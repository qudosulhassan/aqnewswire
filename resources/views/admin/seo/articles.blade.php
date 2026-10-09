@extends('layouts.admin')

@section('title', 'Content SEO Audit')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.seo.index') }}" class="hover:text-slate-600">SEO Center</a>
                <span>&rarr;</span>
                <span class="text-slate-700 font-semibold">Content SEO Audit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Content SEO Audit & Metadata Health</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    {{ number_format($articles->total()) }} Stories
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Audit title tags, meta descriptions, canonical URLs, robots directives, and social share cards across all stories.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.seo.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                &larr; Back to SEO Center
            </a>
            <a href="{{ route('admin.seo.redirects') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                Redirects &rarr;
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form action="{{ route('admin.seo.articles') }}" method="GET" class="flex flex-wrap items-center justify-between gap-3">
            <!-- Search -->
            <div class="relative flex-1 min-w-[240px]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search headlines or slugs..." 
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
            </div>

            <!-- SEO Health Filter -->
            <div class="w-48">
                <select name="filter" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="">All SEO States</option>
                    <option value="missing_desc" {{ request('filter') === 'missing_desc' ? 'selected' : '' }}>Missing Meta Description</option>
                    <option value="missing_title" {{ request('filter') === 'missing_title' ? 'selected' : '' }}>Missing Custom SEO Title</option>
                    <option value="missing_canonical" {{ request('filter') === 'missing_canonical' ? 'selected' : '' }}>Missing Canonical URL</option>
                    <option value="missing_image" {{ request('filter') === 'missing_image' ? 'selected' : '' }}>Missing Social Image</option>
                    <option value="noindex" {{ request('filter') === 'noindex' ? 'selected' : '' }}>Marked NOINDEX</option>
                    <option value="optimal" {{ request('filter') === 'optimal' ? 'selected' : '' }}>Fully Customized (Optimal)</option>
                </select>
            </div>

            <!-- Publication Status Filter -->
            <div class="w-36">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="published" {{ request('status', 'published') === 'published' ? 'selected' : '' }}>Published Only</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Stories</option>
                </select>
            </div>

            <!-- Per Page -->
            <div class="w-24">
                <select name="per_page" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 / page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / page</option>
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'filter', 'status', 'per_page']))
                    <a href="{{ route('admin.seo.articles') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Responsive Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4">Story Headline</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">SEO Title</th>
                        <th class="py-3 px-4">Meta Description</th>
                        <th class="py-3 px-4">Canonical URL</th>
                        <th class="py-3 px-4">Robots</th>
                        <th class="py-3 px-4">Social Image</th>
                        <th class="py-3 px-4">SEO Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        @php $health = $art->seo_health; @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 max-w-xs">
                                <a href="{{ route('admin.seo.articles.edit', $art) }}" class="font-bold text-slate-900 hover:text-red-600 transition block truncate">
                                    {{ $art->title }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-mono block truncate">/article/{{ $art->slug }}</span>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $art->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $art->status }}
                                </span>
                            </td>

                            <td class="py-3 px-4 max-w-xs truncate">
                                @if(!empty($art->meta_title))
                                    <span class="text-slate-800 font-medium" title="{{ $art->meta_title }}">{{ Str::limit($art->meta_title, 32) }}</span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Headline Fallback</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-xs truncate">
                                @if(!empty($art->meta_description))
                                    <span class="text-slate-800" title="{{ $art->meta_description }}">{{ Str::limit($art->meta_description, 35) }}</span>
                                @else
                                    <span class="text-amber-700 bg-amber-50 px-2 py-0.5 rounded text-[10px] font-bold">Missing</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-xs truncate">
                                @if(!empty($art->canonical_url))
                                    <span class="font-mono text-[10px] text-slate-600">{{ $art->canonical_url }}</span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Auto /article/{{ $art->slug }}</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $art->robots && str_contains($art->robots, 'noindex') ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $art->robots ?? 'index, follow' }}
                                </span>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                @if(!empty($art->og_image) || !empty($art->featured_image))
                                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[10px] font-bold">✓ Defined</span>
                                @else
                                    <span class="text-slate-400 text-[10px]">Brand Fallback</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $health['status'] === 'healthy' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($health['status'] === 'critical' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ str_replace('_', ' ', $health['status']) }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.seo.articles.edit', $art) }}" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                        Edit SEO
                                    </a>
                                    <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg transition" title="Preview Public Story">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-12 text-center text-slate-500">
                                No editorial articles matched your search or filtering parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} stories
                </div>
                <div>
                    {{ $articles->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
