@extends('layouts.app')

@section('title', $category->name . ' — News, Analysis & Reports | AQ NEWSWIRE')
@section('meta_description', $category->display_description)
@section('canonical', route('categories.show', $category->slug))

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "{{ $category->name }} — AQ NEWSWIRE",
    "description": "{{ $category->display_description }}",
    "url": "{{ route('categories.show', $category->slug) }}",
    "isPartOf": {
        "@type": "WebSite",
        "name": "AQ NEWSWIRE",
        "url": "{{ url('/') }}"
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $category->name }}",
                "item": "{{ route('categories.show', $category->slug) }}"
            }
        ]
    }
}
</script>
@endpush

@section('content')
<div class="bg-white text-slate-800 min-h-screen">

    <!-- 1. Breadcrumbs & Channel Quick Navigation -->
    <div class="border-b border-slate-200/80 bg-[#F8FAFC]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-slate-500 text-[11px] font-medium" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-[#635BFF] transition">Home</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Channels</span>
                    @if($category->parent)
                        <span class="text-slate-300">/</span>
                        <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-[#635BFF] transition">{{ $category->parent->name }}</a>
                    @endif
                    <span class="text-slate-300">/</span>
                    <span class="text-[#0A2540] font-semibold">{{ $category->name }}</span>
                </nav>

                <!-- Channels Quick Strip (7 Parent Beats) -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                    @foreach($allCategories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" 
                           class="px-2.5 py-1 rounded-lg text-[11px] font-medium whitespace-nowrap transition {{ ($cat->id === $category->id || ($category->parent && $category->parent->id === $cat->id)) ? 'bg-[#635BFF] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-Channels Quick Bar (When Available for this Beat) -->
    @if(isset($subcategories) && $subcategories->isNotEmpty())
        <div class="border-b border-slate-200/80 bg-white py-2.5">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar text-xs">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 shrink-0 mr-1">
                        {{ $parentCategory->name }} Desk:
                    </span>
                    <a href="{{ route('categories.show', $parentCategory->slug) }}" 
                       class="px-2.5 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap transition {{ $category->id === $parentCategory->id ? 'bg-[#0A2540] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        All {{ $parentCategory->name }}
                    </a>
                    @foreach($subcategories as $sub)
                        <a href="{{ route('categories.show', $sub->slug) }}" 
                           class="px-2.5 py-1 rounded-md text-[11px] font-medium whitespace-nowrap transition {{ $category->id === $sub->id ? 'bg-[#635BFF] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            {{ $sub->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Category Hero Header Section — Stripe Style -->
    <section class="relative border-b border-slate-200/80 bg-[#F8FAFC] py-10 sm:py-14 overflow-hidden">
        <!-- Ambient Subtle Mesh Accent -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 left-10 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl space-y-4">
                
                <!-- Eyebrow Tag -->
                <div class="flex items-center gap-2.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-[0.25em] text-[#635BFF] block">
                        Channel Beat
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#635BFF]"></span>
                    <span class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">
                        AQ NEWSWIRE Intelligence
                    </span>
                </div>

                <!-- Primary Editorial H1: Guaranteed High Contrast Deep Navy (#0A2540) -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-[#0A2540] font-sans tracking-tight leading-[1.05]">
                    {{ $category->name }}
                </h1>

                <!-- Dynamic Editorial Description -->
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed max-w-3xl font-normal">
                    {{ $category->display_description }}
                </p>

                <!-- Metadata Metrics & Filter Tabs Row -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-200/80">
                    
                    <!-- Real Statistics -->
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-slate-200 text-slate-700 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ number_format($totalPublishedCount) }} {{ Str::plural('Story', $totalPublishedCount) }}</span>
                        </span>
                        @if($latestUpdate)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-slate-200 text-slate-600 shadow-xs">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Updated {{ \Carbon\Carbon::parse($latestUpdate)->diffForHumans() }}</span>
                            </span>
                        @endif
                    </div>

                    <!-- Category Filter Tabs (Real Backend Filtering) -->
                    <div class="inline-flex items-center p-1 rounded-xl bg-white border border-slate-200 text-xs font-semibold shadow-xs">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'latest']) }}" 
                           class="px-3.5 py-1.5 rounded-lg transition {{ $filter === 'latest' ? 'bg-[#635BFF] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Latest
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'most-read']) }}" 
                           class="px-3.5 py-1.5 rounded-lg transition {{ $filter === 'most-read' ? 'bg-[#635BFF] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Most Read
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'trending']) }}" 
                           class="px-3.5 py-1.5 rounded-lg transition {{ $filter === 'trending' ? 'bg-[#635BFF] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Trending
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. Main Editorial Body Section -->
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        
        <!-- LEAD FEATURE EDITORIAL STORY (If Available on Page 1) -->
        @if($leadArticle)
            <div class="mb-14">
                <div class="relative bg-white border border-slate-200/80 rounded-2xl overflow-hidden group hover:border-slate-300 transition-all duration-300 shadow-sm hover:shadow-md">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 items-center">
                        
                        <!-- Lead Story Media (7 Cols) -->
                        <div class="lg:col-span-7 aspect-16/10 sm:aspect-16/9 lg:aspect-auto lg:h-[420px] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $leadArticle->image_url }}" 
                                 alt="{{ $leadArticle->title }}" 
                                 fetchpriority="high"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent lg:hidden"></div>
                            
                            <!-- Badges Overlay -->
                            <div class="absolute top-4 left-4 flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-[#635BFF] text-white shadow-md">
                                    Lead Story
                                </span>
                                @if($leadArticle->is_breaking)
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-rose-600 text-white animate-pulse shadow-md">
                                        Breaking
                                    </span>
                                @endif
                                @if($leadArticle->is_trending)
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                                        Trending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Lead Story Content (5 Cols) -->
                        <div class="lg:col-span-5 p-6 sm:p-8 lg:p-10 flex flex-col justify-between space-y-5 bg-white">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#635BFF]">
                                    <span>{{ $category->name }}</span>
                                    <span>&middot;</span>
                                    <span>Special Report</span>
                                </div>

                                <h2 class="font-headline text-2xl sm:text-3xl xl:text-4xl font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                    <a href="{{ route('articles.show', $leadArticle->slug) }}">
                                        {{ $leadArticle->title }}
                                    </a>
                                </h2>

                                @if($leadArticle->subtitle)
                                    <p class="text-slate-700 text-sm font-medium leading-relaxed">
                                        {{ $leadArticle->subtitle }}
                                    </p>
                                @endif

                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed line-clamp-3">
                                    {{ $leadArticle->excerpt }}
                                </p>
                            </div>

                            <!-- Byline Metadata -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4 text-xs text-slate-500">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $leadArticle->author->avatar_url }}" 
                                         alt="{{ $leadArticle->author->name }}" 
                                         class="w-7 h-7 rounded-full object-cover ring-1 ring-slate-200">
                                    <div>
                                        <span class="font-bold text-[#0A2540] block leading-tight">{{ $leadArticle->author->name }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $leadArticle->published_at ? $leadArticle->published_at->format('M j, Y') : '' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-[11px] text-slate-500 shrink-0">
                                    <span>{{ $leadArticle->reading_time_minutes }}m read</span>
                                    <span>&middot;</span>
                                    <a href="{{ route('articles.show', $leadArticle->slug) }}" class="text-[#635BFF] hover:underline font-bold inline-flex items-center gap-1">
                                        <span>Read</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        <!-- 4. Main Grid: Stories Feed (8 Cols) + Right Rail (4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left Main Column (8 Cols) -->
            <div class="lg:col-span-8 space-y-12">
                
                <!-- Secondary Category Stories (If Multiple Exist) -->
                @if($secondaryArticles->isNotEmpty())
                    <section class="space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <h3 class="text-xs font-black uppercase tracking-widest text-[#0A2540] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#635BFF]"></span>
                                <span>Latest In {{ $category->name }}</span>
                            </h3>
                            <span class="text-[11px] text-slate-500">Showing page {{ $articles->currentPage() }} of {{ $articles->lastPage() }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($secondaryArticles as $art)
                                <article class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="aspect-16/10 bg-slate-100 overflow-hidden relative">
                                            <img src="{{ $art->image_url }}" alt="{{ $art->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            @if($art->is_breaking)
                                                <span class="absolute top-3 left-3 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-600 text-white">Breaking</span>
                                            @elseif($art->is_trending)
                                                <span class="absolute top-3 left-3 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-emerald-600 text-white">Trending</span>
                                            @endif
                                        </div>
                                        <div class="p-5 space-y-2.5">
                                            <h4 class="font-headline text-lg sm:text-xl font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                                <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                                            </h4>
                                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $art->excerpt }}</p>
                                        </div>
                                    </div>
                                    <div class="p-5 pt-0 border-t border-slate-100 mt-3 text-[11px] text-slate-500 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-700">{{ $art->author->name }}</span>
                                            <span>&middot;</span>
                                            <span>{{ $art->published_at ? $art->published_at->format('M j') : '' }}</span>
                                        </div>
                                        <span>{{ $art->reading_time_minutes }}m read</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination Links -->
                        @if($articles->hasPages())
                            <div class="pt-6 border-t border-slate-200">
                                {{ $articles->links('pagination.stripe') }}
                            </div>
                        @endif
                    </section>
                @endif

                <!-- Empty State When Zero Articles Exist In This Category -->
                @if($articles->isEmpty())
                    <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-10 text-center space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 mx-auto shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#0A2540]">No Stories Published In {{ $category->name }} Yet</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                            Our editorial bureau is actively reporting on this beat. In the meantime, explore essential executive reporting across our sister media channels.
                        </p>
                    </div>
                @endif

                <!-- Cross-Channel Editorial Intelligence (Guarantees Content Density & Discovery) -->
                @if($sisterStories->isNotEmpty())
                    <section class="space-y-6 pt-4 border-t border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200">
                            <div>
                                <div class="text-[10px] font-black uppercase tracking-widest text-[#635BFF]">AQ NEWSWIRE Media Bureau</div>
                                <h3 class="text-lg font-bold text-[#0A2540] tracking-tight">Cross-Channel Executive Briefings</h3>
                            </div>
                            <span class="text-xs text-slate-500">Essential global reading across active channels</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($sisterStories as $sArt)
                                <article class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="aspect-16/10 bg-slate-100 overflow-hidden relative">
                                            <img src="{{ $sArt->image_url }}" alt="{{ $sArt->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-white/95 text-[#0A2540] border border-slate-200 shadow-xs">
                                                {{ $sArt->category->name }}
                                            </span>
                                        </div>
                                        <div class="p-5 space-y-2">
                                            <h4 class="font-headline text-base sm:text-lg font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                                <a href="{{ route('articles.show', $sArt->slug) }}">{{ $sArt->title }}</a>
                                            </h4>
                                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $sArt->excerpt }}</p>
                                        </div>
                                    </div>
                                    <div class="p-5 pt-0 border-t border-slate-100 mt-3 text-[11px] text-slate-500 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-700">{{ $sArt->author->name }}</span>
                                            <span>&middot;</span>
                                            <span>{{ $sArt->published_at ? $sArt->published_at->format('M j, Y') : '' }}</span>
                                        </div>
                                        <span>{{ $sArt->reading_time_minutes }}m read</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

            </div>

            <!-- Right Rail Sidebar (4 Cols) -->
            <aside class="lg:col-span-4 space-y-8">
                
                <!-- 1. Integrated Light Theme Advertisement Card -->
                @if(isset($sidebarAd) && $sidebarAd)
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block text-center mb-2.5">
                            Advertisement
                        </span>
                        <a href="{{ $sidebarAd->link_url }}" target="_blank" rel="noopener noreferrer" class="block rounded-xl overflow-hidden group">
                            <img src="{{ $sidebarAd->image_url }}" 
                                 alt="{{ $sidebarAd->title }}" 
                                 class="w-full h-auto rounded-xl object-cover group-hover:opacity-95 transition">
                        </a>
                        <p class="text-[11px] text-slate-500 text-center mt-2.5 font-medium">{{ $sidebarAd->title }}</p>
                    </div>
                @endif

                <!-- 2. Most Read In Category / AQ NEWSWIRE (Ranked 01 to 05) -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
                        <h4 class="text-xs font-black uppercase tracking-widest text-[#0A2540] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#635BFF]"></span>
                            <span>Most Read &middot; {{ $category->name }}</span>
                        </h4>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Live Views</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($mostReadArticles as $idx => $mArt)
                            <div class="flex items-start gap-3.5 pb-4 border-b border-slate-100 last:border-b-0 last:pb-0 group">
                                <span class="font-sans text-xl font-black text-[#635BFF] shrink-0 leading-none mt-0.5">
                                    0{{ $idx + 1 }}
                                </span>
                                <div class="space-y-1 min-w-0">
                                    <h5 class="font-headline text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                        <a href="{{ route('articles.show', $mArt->slug) }}">{{ $mArt->title }}</a>
                                    </h5>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                        <span class="text-slate-600 font-medium">{{ $mArt->category->name }}</span>
                                        <span>&middot;</span>
                                        <span>{{ $mArt->reading_time_minutes }}m read</span>
                                        @if($mArt->view_count > 0)
                                            <span>&middot;</span>
                                            <span>{{ number_format($mArt->view_count) }} views</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Channel Focus & Beat Intelligence Widget -->
                <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#0A2540]">
                        <span class="w-2 h-2 rounded-full bg-[#635BFF]"></span>
                        <span>Channel Briefing</span>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        The {{ $category->name }} desk tracks market microstructure, institutional allocations, regulatory compliance, and cross-border commercial strategy.
                    </p>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Have a scoop or pitch?</span>
                        <a href="{{ route('contributor.apply') }}" class="text-[#635BFF] hover:underline font-bold inline-flex items-center gap-1">
                            <span>Submit Pitch</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- 4. Global Market Intelligence Widget (Stripe Contrast Banner) -->
                <div class="bg-gradient-to-br from-[#0A2540] via-[#0E355C] to-[#1E293B] text-white border border-slate-700 rounded-2xl p-6 shadow-md space-y-3">
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#635BFF] bg-white/10 w-fit px-2 py-0.5 rounded">Live Pulse</div>
                    <h5 class="text-sm font-bold text-white">Global Markets Coverage</h5>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Stay ahead of central bank rate decisions and macroeconomic volatility with real-time editorial updates.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('rankings.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#635BFF] hover:bg-[#5349DF] text-white rounded-xl text-xs font-semibold transition shadow-sm">
                            <span>Explore AQ NEWSWIRE Rankings</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

            </aside>

        </div>

    </div>

</div>
@endsection
