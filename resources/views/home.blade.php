@extends('layouts.app')

@section('title', 'AQ NEWSWIRE — Global Business, Markets, Technology & Leadership')

@section('content')
<div class="relative overflow-hidden bg-white text-slate-800">

    <!-- =========================================================================
         SECTION 1: CINEMATIC EDITORIAL HERO ("THE BIG STORY & DEVELOPING DESK")
         ========================================================================= -->
    <section class="relative bg-white text-slate-900 pt-6 pb-12 lg:pt-10 lg:pb-16 border-b border-slate-200/80 overflow-hidden">
        <!-- Ambient Background Glow Accents -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-40 w-[32rem] h-[32rem] bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                
                <!-- Hero Left Column: Editorial Lead Story (7 Cols) -->
                <div class="lg:col-span-7 space-y-5">
                    <!-- Category Badge & Live Pulse -->
                    <div class="flex items-center gap-2.5">
                        <span class="bg-[#635BFF]/10 text-[#635BFF] font-bold text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full border border-[#635BFF]/20 shadow-xs flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#635BFF] animate-pulse"></span>
                            {{ $heroStory?->category?->name ?? 'THE BIG STORY' }}
                        </span>
                        <span class="text-slate-400 text-xs">•</span>
                        <span class="text-slate-500 text-xs font-medium">
                            {{ $heroStory?->published_at ? $heroStory->published_at->diffForHumans(null, true) . ' ago' : 'Developing' }}
                        </span>
                    </div>

                    <!-- Main Story Image -->
                    <div class="relative rounded-2xl overflow-hidden border border-slate-200/80 shadow-lg bg-slate-100 group">
                        <a href="{{ $heroStory ? route('articles.show', $heroStory->slug) : route('search.index') }}" class="aspect-[16/10] overflow-hidden relative block">
                            <img src="{{ $heroStory?->image_url ?? 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80' }}" 
                                 alt="{{ $heroStory?->title ?? 'Featured Story' }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                        </a>

                        @if($heroStory)
                            <button type="button" 
                                    onclick="event.preventDefault(); toggleBookmark({{ $heroStory->id }}, this)" 
                                    class="absolute top-3.5 right-3.5 p-2 rounded-xl bg-white/90 hover:bg-white text-slate-600 hover:text-[#635BFF] border border-slate-200/80 backdrop-blur-md transition shadow-sm group/btn cursor-pointer" 
                                    title="Save to Reading List">
                                <svg class="w-4 h-4 group-hover/btn:text-[#635BFF] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Massive Headline -->
                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[#0A2540] tracking-tight leading-[1.15] font-headline">
                        <a href="{{ $heroStory ? route('articles.show', $heroStory->slug) : route('search.index') }}" class="hover:text-[#635BFF] transition">
                            {{ $heroStory?->title ?? 'The New Rules of Global Business' }}
                        </a>
                    </h1>

                    <!-- Excerpt -->
                    <p class="text-slate-600 text-sm sm:text-base lg:text-lg font-normal leading-relaxed line-clamp-3">
                        {{ $heroStory?->excerpt ?? 'Geopolitics, artificial intelligence, and capital markets are reshaping global leadership. Read the definitive executive analysis.' }}
                    </p>

                    <!-- CTAs & Byline Row -->
                    <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-3">
                            <img src="{{ $heroStory?->author?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}" 
                                 alt="Author" 
                                 class="w-9 h-9 rounded-full object-cover ring-1 ring-slate-200">
                            <div class="text-xs">
                                <span class="text-[#0A2540] font-bold block">{{ $heroStory?->author?->name ?? 'Editorial Desk' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $heroStory?->reading_time_minutes ?? 6 }} min read • {{ number_format($heroStory?->view_count > 0 ? $heroStory->view_count : 24500) }} readers</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <a href="{{ $heroStory ? route('articles.show', $heroStory->slug) : route('search.index') }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                                <span>Read Full Story</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hero Right Column: Developing Intelligence & Trending Stack (5 Cols) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                            <h2 class="text-sm font-black uppercase tracking-wider text-[#0A2540]">Developing Intelligence</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-400">Editor's Desk</span>
                    </div>

                    <div class="space-y-3.5">
                        @foreach($topStories as $idx => $story)
                            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex gap-4 hover:border-slate-300 hover:shadow-md transition group shadow-xs">
                                <span class="text-2xl font-black text-slate-300 group-hover:text-[#635BFF] transition shrink-0 font-mono mt-1">
                                    0{{ $idx + 1 }}
                                </span>
                                <div class="w-24 sm:w-28 aspect-16/11 rounded-xl overflow-hidden bg-slate-100 shrink-0 relative">
                                    <img src="{{ $story->image_url }}" alt="{{ $story->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="flex-1 flex flex-col justify-between min-w-0">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#635BFF]">
                                                {{ $story->category?->name ?? 'MARKETS' }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">• {{ $story->reading_time_minutes ?? 5 }} min</span>
                                        </div>
                                        <h3 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                            <a href="{{ route('articles.show', $story->slug) }}">
                                                {{ $story->title }}
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="text-[11px] text-slate-400 flex items-center justify-between mt-2 pt-2 border-t border-slate-100">
                                        <span>{{ $story->author->name }}</span>
                                        <span>{{ $story->published_at ? $story->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mini Live Callout Banner -->
                    <div class="bg-gradient-to-r from-slate-900 via-[#0A2540] to-indigo-950 text-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#635BFF] block">Executive Brief</span>
                            <h4 class="text-xs font-bold text-slate-200 mt-0.5">Stay Ahead of Global Market Movements</h4>
                        </div>
                        <a href="{{ route('rankings.index') }}" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-[11px] font-bold rounded-lg border border-white/20 transition shrink-0">
                            Explore Lists &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 2: BREAKING NEWS TICKER
         ========================================================================= -->
    <div class="bg-[#F8FAFC] border-b border-slate-200/80 text-slate-800 py-2.5 px-4 overflow-hidden relative">
        <div class="max-w-[1600px] mx-auto flex items-center gap-4">
            <!-- Breaking Pill Badge -->
            <div class="inline-flex items-center gap-1.5 bg-[#635BFF] text-white font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 py-1 rounded-full shrink-0 shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                <span>GLOBAL PULSE</span>
            </div>

            <!-- Marquee Stream -->
            <div class="overflow-hidden whitespace-nowrap flex-1 text-xs text-slate-600 font-medium">
                <div class="animate-marquee inline-block">
                    <span class="mx-4 font-semibold text-slate-800">Global markets rally as enterprise AI spending accelerates</span>
                    <span class="text-slate-300">•</span>
                    <span class="mx-4 font-semibold text-slate-800">Central banks balance monetary policy with growth signals</span>
                    <span class="text-slate-300">•</span>
                    <span class="mx-4 font-semibold text-slate-800">Clean energy infrastructure investments hit milestone highs</span>
                    @if(isset($breakingNews) && $breakingNews->isNotEmpty())
                        @foreach($breakingNews as $b)
                            <span class="text-slate-300">•</span>
                            <a href="{{ route('articles.show', $b->slug) }}" class="mx-4 hover:text-[#635BFF] font-semibold text-[#0A2540]">{{ $b->title }}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 shrink-0 text-xs text-slate-500 font-semibold">
                <a href="{{ route('categories.show', 'markets') }}" class="hover:text-[#635BFF] flex items-center gap-1">
                    <span>Markets Beat</span> &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION 3: LATEST INSIGHTS (8-Story Comprehensive Magazine Rail)
         ========================================================================= -->
    <section class="py-12 lg:py-16 bg-white border-b border-slate-200/80" x-data>
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight font-headline">Latest Editorial Insights</h2>
                        <span class="text-[#635BFF] font-bold">&rarr;</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Fresh reporting, strategic analysis, and executive commentary across all beats.</p>
                </div>
                <div class="flex items-center gap-2 text-slate-500">
                    <button type="button" 
                            @click="$refs.insightsRail.scrollBy({ left: -340, behavior: 'smooth' })" 
                            class="p-2 rounded-full border border-slate-200 hover:border-slate-300 hover:text-slate-900 hover:bg-slate-50 transition cursor-pointer shadow-xs"
                            title="Scroll left">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" 
                            @click="$refs.insightsRail.scrollBy({ left: 340, behavior: 'smooth' })" 
                            class="p-2 rounded-full border border-slate-200 hover:border-slate-300 hover:text-slate-900 hover:bg-slate-50 transition cursor-pointer shadow-xs"
                            title="Scroll right">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Responsive Grid / Scrolling Rail -->
            <div x-ref="insightsRail" class="flex lg:grid lg:grid-cols-4 gap-6 overflow-x-auto lg:overflow-visible pb-4 lg:pb-0 scroll-smooth snap-x snap-mandatory">
                @foreach($latestInsights as $idx => $insight)
                    <div class="min-w-[280px] sm:min-w-[320px] lg:min-w-0 snap-start bg-white border border-slate-200/80 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-lg transition duration-300 flex flex-col group relative shadow-xs">
                        <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $insight->image_url }}" 
                                 alt="{{ $insight->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            <!-- Quick Bookmark Button -->
                            <button type="button" 
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleBookmark({{ $insight->id }}, this)" 
                                    class="absolute top-2.5 right-2.5 p-1.5 rounded-lg bg-white/90 hover:bg-white border border-slate-200 text-slate-500 hover:text-[#635BFF] backdrop-blur-sm transition shadow-xs group/bm cursor-pointer" 
                                    title="Save to Reading List">
                                <svg class="w-3.5 h-3.5 group-hover/bm:text-[#635BFF] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-2.5">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#635BFF] bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded">
                                        {{ $insight->category?->name ?? 'INSIGHT' }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium">
                                        {{ $insight->published_at ? $insight->published_at->diffForHumans(null, true) . ' ago' : 'Recently' }}
                                    </span>
                                </div>
                                <h3 class="text-sm sm:text-base font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $insight->slug) }}">
                                        {{ $insight->title }}
                                    </a>
                                </h3>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-4 border-t border-slate-100 mt-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ number_format($insight->view_count > 0 ? $insight->view_count : 14200) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $insight->reading_time_minutes ?? 5 }} min
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 4: "WHAT'S MOVING THE WORLD" + LIVE FINANCIAL BENCHMARKS
         ========================================================================= -->
    <section class="py-12 lg:py-16 bg-[#F8FAFC] border-b border-slate-200/80">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                
                <!-- Left 8 Columns: "What's moving the world" -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight font-headline">What's Moving the World</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Deep-dive investigative reporting and executive perspectives.</p>
                        </div>
                        <a href="{{ route('categories.show', 'business') }}" class="text-xs text-[#635BFF] hover:underline font-bold flex items-center gap-1">
                            <span>All Stories</span> &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Featured Large Card (7 cols) -->
                        <div class="md:col-span-7 bg-white border border-slate-200/80 rounded-2xl overflow-hidden relative group flex flex-col justify-between shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img src="{{ $whatsMovingFeature?->image_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="Feature" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/10 to-transparent"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-[#635BFF] text-white font-extrabold text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-md shadow-xs">
                                        {{ $whatsMovingFeature?->category?->name ?? 'FEATURE STORY' }} • {{ $whatsMovingFeature?->reading_time_minutes ?? 7 }} min
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 relative -mt-6 z-10 space-y-3 bg-white">
                                <h3 class="text-lg sm:text-xl font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                    <a href="{{ route('articles.show', $whatsMovingFeature->slug) }}">
                                        {{ $whatsMovingFeature->title }}
                                    </a>
                                </h3>
                                <p class="text-slate-600 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                                    {{ $whatsMovingFeature->excerpt }}
                                </p>
                                <div class="flex items-center gap-2.5 pt-3 border-t border-slate-100">
                                    <img src="{{ $whatsMovingFeature?->author?->avatar_url ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80' }}" 
                                         alt="Author" class="w-7 h-7 rounded-full object-cover ring-1 ring-slate-200">
                                    <div class="text-xs">
                                        <span class="text-[#0A2540] font-bold block">{{ $whatsMovingFeature?->author?->name ?? 'Senior Correspondent' }}</span>
                                        <span class="text-[10px] text-slate-400">Global Financial Analysis</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right 4 Compact Stacked Stories (5 cols) -->
                        <div class="md:col-span-5 flex flex-col justify-between gap-3">
                            @foreach($whatsMovingSide as $side)
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 flex gap-3.5 hover:border-slate-300 hover:shadow-md transition group shadow-xs">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                        <img src="{{ $side->image_url }}" alt="Thumb" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    <div class="flex-1 flex flex-col justify-between min-w-0">
                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#635BFF]">
                                                {{ $side->category?->name ?? 'MARKETS' }}
                                            </span>
                                            <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2 mt-0.5">
                                                <a href="{{ route('articles.show', $side->slug) }}">
                                                    {{ $side->title }}
                                                </a>
                                            </h4>
                                        </div>
                                        <span class="text-[10px] text-slate-400 mt-1">
                                            {{ $side->published_at ? $side->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }} • {{ $side->reading_time_minutes ?? 4 }} min
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right 4 Columns: Live Financial Benchmark Panel -->
                <div class="lg:col-span-4 bg-white border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between shadow-xs">
                    <div>
                        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                            <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-2">
                                <span>Live Markets</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </h3>
                            <a href="{{ route('categories.show', 'markets') }}" class="text-xs text-[#635BFF] hover:underline font-bold">
                                All Quotes &rarr;
                            </a>
                        </div>

                        <!-- Indices Table -->
                        <div class="divide-y divide-slate-100 mt-1">
                            @foreach($marketIndices as $idx)
                                <div class="py-2.5 flex items-center justify-between text-xs">
                                    <div class="w-28">
                                        <span class="font-bold text-[#0A2540] block">{{ $idx['symbol'] }}</span>
                                        <span class="text-[10px] text-slate-500 line-clamp-1">{{ $idx['name'] }}</span>
                                    </div>

                                    <div class="text-right">
                                        <span class="font-semibold text-slate-700 block">{{ $idx['value'] }}</span>
                                        <span class="text-[10px] font-bold {{ $idx['is_up'] ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $idx['is_up'] ? '▲' : '▼' }} {{ $idx['change'] }}
                                        </span>
                                    </div>

                                    <!-- Sparkline SVG -->
                                    <div class="w-14 h-5 shrink-0">
                                        <svg class="w-full h-full {{ $idx['is_up'] ? 'text-emerald-500' : 'text-rose-500' }}" viewBox="0 0 75 20" fill="none">
                                            <path d="{{ $idx['points'] }}" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 text-[10px] text-slate-400 text-center">
                        Market benchmarks indicative. Updated continuously.
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 5: AQ NEWSWIRE INTELLIGENCE (Macroeconomic Data & Interactive Growth Curve)
         ========================================================================= -->
    <section class="py-14 bg-white text-slate-900 border-b border-slate-200/80 relative overflow-hidden" 
             x-data="{ 
                 range: '1Y',
                 datasets: {
                     '1M': {
                         metric1: '$240B', metric1Label: 'Clean Energy Invested (MoM)', metric1Trend: '+4.2%',
                         metric2: '4.1%', metric2Label: 'Global Growth Forecast', metric2Trend: '+0.5%',
                         metric3: '86%', metric3Label: 'Enterprise AI Adoption', metric3Trend: '+6.1%',
                         gdpPath: 'M 0,160 C 120,150 240,110 360,75 S 480,45 500,40',
                         gdpArea: 'M 0,160 C 120,150 240,110 360,75 S 480,45 500,40 L 500,200 L 0,200 Z',
                         aiPath: 'M 0,180 C 130,170 250,130 380,85 S 480,60 500,55',
                         aiArea: 'M 0,180 C 130,170 250,130 380,85 S 480,60 500,55 L 500,200 L 0,200 Z',
                         cx: 360, cy: 75, tooltipValue: '4.1%', tooltipLabel: '1M Global AI Momentum', tooltipTop: '18%', tooltipRight: '22%'
                     },
                     '3M': {
                         metric1: '$215B', metric1Label: 'Clean Energy Invested (Q1)', metric1Trend: '+3.1%',
                         metric2: '3.9%', metric2Label: 'Global Growth Forecast', metric2Trend: '+0.3%',
                         metric3: '79%', metric3Label: 'Enterprise AI Adoption', metric3Trend: '+4.4%',
                         gdpPath: 'M 0,165 C 130,155 240,120 360,70 S 480,40 500,35',
                         gdpArea: 'M 0,165 C 130,155 240,120 360,70 S 480,40 500,35 L 500,200 L 0,200 Z',
                         aiPath: 'M 0,185 C 140,165 260,140 380,95 S 480,70 500,65',
                         aiArea: 'M 0,185 C 140,165 260,140 380,95 S 480,70 500,65 L 500,200 L 0,200 Z',
                         cx: 360, cy: 70, tooltipValue: '3.9%', tooltipLabel: '3M Aggregate Projection', tooltipTop: '16%', tooltipRight: '24%'
                     },
                     '1Y': {
                         metric1: '$200B', metric1Label: 'Clean Energy Invested (2026)', metric1Trend: '+2.8%',
                         metric2: '3.8%', metric2Label: 'Global Growth Forecast', metric2Trend: '+0.4%',
                         metric3: '72%', metric3Label: 'Enterprise AI Adoption', metric3Trend: '+5.2%',
                         gdpPath: 'M 0,170 C 120,180 220,120 360,65 S 470,35 500,30',
                         gdpArea: 'M 0,170 C 120,180 220,120 360,65 S 470,35 500,30 L 500,200 L 0,200 Z',
                         aiPath: 'M 0,190 C 130,160 240,140 380,100 S 470,80 500,75',
                         aiArea: 'M 0,190 C 130,160 240,140 380,100 S 470,80 500,75 L 500,200 L 0,200 Z',
                         cx: 360, cy: 65, tooltipValue: '3.8%', tooltipLabel: 'Global GDP Forecast 2026', tooltipTop: '12%', tooltipRight: '22%'
                     },
                     '5Y': {
                         metric1: '$1.2T', metric1Label: 'Cumulative Energy CapEx', metric1Trend: '+18.5%',
                         metric2: '5.4%', metric2Label: 'Productivity Lift Rate', metric2Trend: '+2.1%',
                         metric3: '94%', metric3Label: 'Enterprise AI Integration', metric3Trend: '+12.0%',
                         gdpPath: 'M 0,180 C 100,150 220,95 350,45 S 460,20 500,15',
                         gdpArea: 'M 0,180 C 100,150 220,95 350,45 S 460,20 500,15 L 500,200 L 0,200 Z',
                         aiPath: 'M 0,195 C 120,140 230,100 370,60 S 460,35 500,30',
                         aiArea: 'M 0,195 C 120,140 230,100 370,60 S 460,35 500,30 L 500,200 L 0,200 Z',
                         cx: 350, cy: 45, tooltipValue: '5.4%', tooltipLabel: '5Y Transformation Horizon', tooltipTop: '8%', tooltipRight: '25%'
                     }
                 }
             }">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Title & Metrics (6 cols) -->
                <div class="lg:col-span-6 space-y-6">
                    <div>
                        <span class="text-[#635BFF] font-extrabold text-xs uppercase tracking-widest block mb-1.5">AQ NEWSWIRE Intelligence</span>
                        <h2 class="text-2xl sm:text-4xl font-black text-[#0A2540] tracking-tight leading-tight font-headline">
                            Data-driven intelligence for high-stakes decision makers.
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                            Real-time tracking of global capital deployment, artificial intelligence integration rates, and macroeconomic indicators.
                        </p>
                    </div>

                    <!-- 3 Reactive Stats Cards -->
                    <div class="grid grid-cols-3 gap-3.5 pt-4 border-t border-slate-200">
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 space-y-1">
                            <h4 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight" x-text="datasets[range].metric1">$200B</h4>
                            <p class="text-[10px] text-slate-500 leading-tight font-medium" x-text="datasets[range].metric1Label">Clean Energy</p>
                            <span class="inline-flex items-center gap-0.5 text-[9px] font-bold text-emerald-700 bg-emerald-100/70 px-1 py-0.2 rounded">
                                ▲ <span x-text="datasets[range].metric1Trend">+2.8%</span>
                            </span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 space-y-1">
                            <h4 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight" x-text="datasets[range].metric2">3.8%</h4>
                            <p class="text-[10px] text-slate-500 leading-tight font-medium" x-text="datasets[range].metric2Label">Global GDP</p>
                            <span class="inline-flex items-center gap-0.5 text-[9px] font-bold text-emerald-700 bg-emerald-100/70 px-1 py-0.2 rounded">
                                ▲ <span x-text="datasets[range].metric2Trend">+0.4%</span>
                            </span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 space-y-1">
                            <h4 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight" x-text="datasets[range].metric3">72%</h4>
                            <p class="text-[10px] text-slate-500 leading-tight font-medium" x-text="datasets[range].metric3Label">Enterprise AI</p>
                            <span class="inline-flex items-center gap-0.5 text-[9px] font-bold text-emerald-700 bg-emerald-100/70 px-1 py-0.2 rounded">
                                ▲ <span x-text="datasets[range].metric3Trend">+5.2%</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Interactive Multi-Spline Chart (6 cols) -->
                <div class="lg:col-span-6 bg-[#F8FAFC] border border-slate-200/90 rounded-2xl p-6 shadow-sm relative">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#635BFF] animate-pulse"></span>
                            <span class="text-xs font-bold text-[#0A2540]">Global GDP & Enterprise AI Growth Trajectory</span>
                        </div>
                        <!-- Range Filters -->
                        <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-500 shadow-xs">
                            <button type="button" @click="range = '1M'" :class="range === '1M' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2 py-0.5 rounded transition cursor-pointer">1M</button>
                            <button type="button" @click="range = '3M'" :class="range === '3M' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2 py-0.5 rounded transition cursor-pointer">3M</button>
                            <button type="button" @click="range = '1Y'" :class="range === '1Y' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2 py-0.5 rounded transition cursor-pointer">1Y</button>
                            <button type="button" @click="range = '5Y'" :class="range === '5Y' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2 py-0.5 rounded transition cursor-pointer">5Y</button>
                        </div>
                    </div>

                    <!-- Glowing Dual Spline SVG Canvas -->
                    <div class="h-56 w-full relative mt-3">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 500 200" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartGrad1" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#635BFF" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#635BFF" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="chartGrad2" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#0EA5E9" stop-opacity="0.18"/>
                                    <stop offset="100%" stop-color="#0EA5E9" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>

                            <!-- Horizontal Grid Lines -->
                            <line x1="0" y1="40" x2="500" y2="40" stroke="#E2E8F0" stroke-dasharray="3 3"/>
                            <line x1="0" y1="90" x2="500" y2="90" stroke="#E2E8F0" stroke-dasharray="3 3"/>
                            <line x1="0" y1="140" x2="500" y2="140" stroke="#E2E8F0" stroke-dasharray="3 3"/>

                            <!-- Reactive Area Fills -->
                            <path :d="datasets[range].gdpArea" fill="url(#chartGrad1)" class="transition-all duration-500"/>
                            <path :d="datasets[range].aiArea" fill="url(#chartGrad2)" class="transition-all duration-500"/>

                            <!-- Reactive Stroke Lines -->
                            <path :d="datasets[range].gdpPath" fill="none" stroke="#635BFF" stroke-width="2.5" class="transition-all duration-500"/>
                            <path :d="datasets[range].aiPath" fill="none" stroke="#0EA5E9" stroke-width="2" class="transition-all duration-500"/>

                            <!-- Dynamic Peak Point -->
                            <circle :cx="datasets[range].cx" :cy="datasets[range].cy" r="5" fill="#635BFF" stroke="#FFFFFF" stroke-width="2.5" class="transition-all duration-500 shadow-sm"/>
                        </svg>

                        <!-- Dynamic Floating Tooltip -->
                        <div class="absolute bg-white border border-slate-200/90 rounded-xl px-2.5 py-1 shadow-md text-[11px] text-slate-800 transition-all duration-500 pointer-events-none"
                             :style="{ top: datasets[range].tooltipTop, right: datasets[range].tooltipRight }">
                            <span class="text-[#635BFF] font-bold" x-text="datasets[range].tooltipValue">3.8%</span> 
                            <span class="text-slate-600 ml-1" x-text="datasets[range].tooltipLabel">Global GDP Forecast 2026</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200/80 text-[10px] text-slate-400">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#635BFF]"></span> Global GDP</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#0EA5E9]"></span> Enterprise AI Index</span>
                        </div>
                        <span>Baseline: 2025</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 6: THE CATEGORY FRONTLINE (4 Core Beats: Tech, Markets, Politics, Lifestyle)
         ========================================================================= -->
    <section class="py-14 bg-white border-b border-slate-200/80">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between pb-6 mb-8 border-b border-slate-200">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight font-headline">Editorial Desks & Category Beats</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Explore our dedicated channels across global industries.</p>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-xs text-slate-400">Jump to:</span>
                    <a href="{{ route('categories.show', 'tech') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">Tech</a>
                    <a href="{{ route('categories.show', 'markets') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">Markets</a>
                    <a href="{{ route('categories.show', 'politics') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">Politics</a>
                    <a href="{{ route('categories.show', 'lifestyle') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">Lifestyle</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Beat 1: Tech & AI -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2.5 border-b-2 border-indigo-500">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                            <h3 class="font-bold text-[#0A2540] text-base">Technology & AI</h3>
                        </div>
                        <a href="{{ route('categories.show', 'tech') }}" class="text-xs text-indigo-600 hover:underline font-bold">More &rarr;</a>
                    </div>
                    @if($techStories->isNotEmpty())
                        @php $techLead = $techStories->first(); @endphp
                        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img src="{{ $techLead->image_url }}" alt="{{ $techLead->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute top-2.5 left-2.5 bg-indigo-600 text-white font-bold text-[9px] uppercase tracking-wider px-2 py-0.5 rounded shadow-xs">
                                    {{ $techLead->category?->name ?? 'TECH' }}
                                </span>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-indigo-600 transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $techLead->slug) }}">{{ $techLead->title }}</a>
                                </h4>
                                <div class="text-[11px] text-slate-400 flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span>{{ $techLead->author->name }}</span>
                                    <span>{{ $techLead->reading_time_minutes ?? 5 }} min</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stacked briefs -->
                        <div class="space-y-2 pt-1">
                            @foreach($techStories->skip(1)->take(3) as $story)
                                <div class="p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                    <h5 class="text-xs font-semibold text-[#0A2540] group-hover:text-indigo-600 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('articles.show', $story->slug) }}">{{ $story->title }}</a>
                                    </h5>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $story->published_at ? $story->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Beat 2: Markets & Finance -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2.5 border-b-2 border-emerald-500">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="font-bold text-[#0A2540] text-base">Markets & Capital</h3>
                        </div>
                        <a href="{{ route('categories.show', 'markets') }}" class="text-xs text-emerald-600 hover:underline font-bold">More &rarr;</a>
                    </div>
                    @if($marketsStories->isNotEmpty())
                        @php $mktLead = $marketsStories->first(); @endphp
                        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img src="{{ $mktLead->image_url }}" alt="{{ $mktLead->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute top-2.5 left-2.5 bg-emerald-600 text-white font-bold text-[9px] uppercase tracking-wider px-2 py-0.5 rounded shadow-xs">
                                    {{ $mktLead->category?->name ?? 'MARKETS' }}
                                </span>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-emerald-600 transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $mktLead->slug) }}">{{ $mktLead->title }}</a>
                                </h4>
                                <div class="text-[11px] text-slate-400 flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span>{{ $mktLead->author->name }}</span>
                                    <span>{{ $mktLead->reading_time_minutes ?? 5 }} min</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stacked briefs -->
                        <div class="space-y-2 pt-1">
                            @foreach($marketsStories->skip(1)->take(3) as $story)
                                <div class="p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                    <h5 class="text-xs font-semibold text-[#0A2540] group-hover:text-emerald-600 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('articles.show', $story->slug) }}">{{ $story->title }}</a>
                                    </h5>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $story->published_at ? $story->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Beat 3: Politics & Geopolitics -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2.5 border-b-2 border-rose-500">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <h3 class="font-bold text-[#0A2540] text-base">Politics & Policy</h3>
                        </div>
                        <a href="{{ route('categories.show', 'politics') }}" class="text-xs text-rose-600 hover:underline font-bold">More &rarr;</a>
                    </div>
                    @if($politicsStories->isNotEmpty())
                        @php $polLead = $politicsStories->first(); @endphp
                        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img src="{{ $polLead->image_url }}" alt="{{ $polLead->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute top-2.5 left-2.5 bg-rose-600 text-white font-bold text-[9px] uppercase tracking-wider px-2 py-0.5 rounded shadow-xs">
                                    {{ $polLead->category?->name ?? 'POLITICS' }}
                                </span>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-rose-600 transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $polLead->slug) }}">{{ $polLead->title }}</a>
                                </h4>
                                <div class="text-[11px] text-slate-400 flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span>{{ $polLead->author->name }}</span>
                                    <span>{{ $polLead->reading_time_minutes ?? 5 }} min</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stacked briefs -->
                        <div class="space-y-2 pt-1">
                            @foreach($politicsStories->skip(1)->take(3) as $story)
                                <div class="p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                    <h5 class="text-xs font-semibold text-[#0A2540] group-hover:text-rose-600 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('articles.show', $story->slug) }}">{{ $story->title }}</a>
                                    </h5>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $story->published_at ? $story->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Beat 4: Lifestyle, Careers & Culture -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2.5 border-b-2 border-amber-500">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <h3 class="font-bold text-[#0A2540] text-base">Executive Life</h3>
                        </div>
                        <a href="{{ route('categories.show', 'lifestyle') }}" class="text-xs text-amber-600 hover:underline font-bold">More &rarr;</a>
                    </div>
                    @if($lifestyleStories->isNotEmpty())
                        @php $lifeLead = $lifestyleStories->first(); @endphp
                        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img src="{{ $lifeLead->image_url }}" alt="{{ $lifeLead->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute top-2.5 left-2.5 bg-amber-600 text-white font-bold text-[9px] uppercase tracking-wider px-2 py-0.5 rounded shadow-xs">
                                    {{ $lifeLead->category?->name ?? 'LIFESTYLE' }}
                                </span>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-amber-600 transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $lifeLead->slug) }}">{{ $lifeLead->title }}</a>
                                </h4>
                                <div class="text-[11px] text-slate-400 flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span>{{ $lifeLead->author->name }}</span>
                                    <span>{{ $lifeLead->reading_time_minutes ?? 5 }} min</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stacked briefs -->
                        <div class="space-y-2 pt-1">
                            @foreach($lifestyleStories->skip(1)->take(3) as $story)
                                <div class="p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                    <h5 class="text-xs font-semibold text-[#0A2540] group-hover:text-amber-600 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('articles.show', $story->slug) }}">{{ $story->title }}</a>
                                    </h5>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $story->published_at ? $story->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 7: DEEP DIVE INVESTIGATION (Cinematic Full-Width Spotlight)
         ========================================================================= -->
    @if(isset($investigationStory) && $investigationStory)
        <section class="py-12 bg-white border-b border-slate-200/80">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative bg-[#0A2540] text-white rounded-3xl overflow-hidden shadow-2xl border border-slate-800">
                    <!-- Subtle Glow -->
                    <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#635BFF]/30 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-6 sm:p-10 lg:p-12 relative z-10">
                        <div class="lg:col-span-7 space-y-4">
                            <div class="inline-flex items-center gap-2">
                                <span class="bg-amber-400/20 text-amber-300 border border-amber-400/30 font-black text-[10px] uppercase tracking-widest px-3 py-1 rounded-full">
                                    ★ EXCLUSIVE INVESTIGATION
                                </span>
                                <span class="text-slate-400 text-xs">{{ $investigationStory->category?->name }}</span>
                            </div>
                            <h3 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight font-headline">
                                <a href="{{ route('articles.show', $investigationStory->slug) }}" class="hover:text-indigo-300 transition">
                                    {{ $investigationStory->title }}
                                </a>
                            </h3>
                            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-2xl line-clamp-3">
                                {{ $investigationStory->excerpt }}
                            </p>
                            <div class="flex items-center gap-4 pt-2">
                                <a href="{{ route('articles.show', $investigationStory->slug) }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs font-bold rounded-xl transition shadow-lg shadow-indigo-600/30">
                                    <span>Read Full Investigation</span> &rarr;
                                </a>
                                <span class="text-xs text-slate-400">{{ $investigationStory->reading_time_minutes ?? 8 }} min in-depth read</span>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="aspect-16/10 rounded-2xl overflow-hidden border border-slate-700 shadow-xl bg-slate-900 group">
                                <img src="{{ $investigationStory->image_url }}" alt="{{ $investigationStory->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- =========================================================================
         SECTION 8: HEALTH, BIOTECH & LONGEVITY CHANNEL (Dedicated 4-Card Grid)
         ========================================================================= -->
    <section class="py-14 bg-[#F8FAFC] border-b border-slate-200/80">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                    <h2 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight font-headline">Health, Longevity & Biotech</h2>
                </div>
                <a href="{{ route('categories.show', 'health') }}" class="text-xs text-teal-700 hover:underline font-bold flex items-center gap-1">
                    <span>Explore Health Channel</span> &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($healthStories->take(4) as $hStory)
                    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between group shadow-xs">
                        <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $hStory->image_url }}" alt="{{ $hStory->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 bg-teal-600 text-white font-bold text-[9px] uppercase tracking-wider px-2 py-0.5 rounded shadow-xs">
                                {{ $hStory->category?->name ?? 'HEALTH' }}
                            </span>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-teal-700 transition leading-snug line-clamp-2">
                                <a href="{{ route('articles.show', $hStory->slug) }}">{{ $hStory->title }}</a>
                            </h4>
                            <div class="text-[10px] text-slate-400 flex items-center justify-between pt-3 border-t border-slate-100 mt-3">
                                <span>{{ $hStory->author->name }}</span>
                                <span>{{ $hStory->reading_time_minutes ?? 4 }} min read</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 9: AQ NEWSWIRE LISTS (Rankings Strip)
         ========================================================================= -->
    @if(isset($featuredList) && $featuredList)
        <section class="py-14 bg-white border-b border-slate-200/80 text-slate-800" x-data>
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-black text-[#0A2540] tracking-tight font-headline">AQ NEWSWIRE Lists</span>
                            <span class="text-[#635BFF] font-bold">★</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">The founders, companies, and titans defining modern industry.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('rankings.index') }}" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-xs transition">
                            View All Rankings &rarr;
                        </a>
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <button type="button" 
                                    @click="$refs.rankingsRail.scrollBy({ left: -300, behavior: 'smooth' })" 
                                    class="p-2 rounded-full border border-slate-200 hover:border-slate-300 hover:text-slate-700 hover:bg-white transition cursor-pointer shadow-xs"
                                    title="Scroll left">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button type="button" 
                                    @click="$refs.rankingsRail.scrollBy({ left: 300, behavior: 'smooth' })" 
                                    class="p-2 rounded-full border border-slate-200 hover:border-slate-300 hover:text-slate-700 hover:bg-white transition cursor-pointer shadow-xs"
                                    title="Scroll right">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Horizontal Rail / Grid -->
                <div x-ref="rankingsRail" class="flex lg:grid lg:grid-cols-5 gap-4 overflow-x-auto lg:overflow-visible pb-4 lg:pb-0 scroll-smooth snap-x snap-mandatory">
                    @foreach($featuredList->items as $item)
                        <div class="min-w-[260px] lg:min-w-0 snap-start bg-slate-50/70 border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4 transition group flex items-center gap-3.5 shadow-xs hover:shadow-md">
                            <span class="text-2xl font-black text-slate-300 group-hover:text-[#635BFF] transition shrink-0 font-mono">
                                0{{ $item->rank }}
                            </span>
                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                            <div class="overflow-hidden flex-1">
                                <h4 class="text-xs font-bold text-[#0A2540] group-hover:text-[#635BFF] transition truncate">{{ $item->name }}</h4>
                                <span class="text-[10px] text-slate-500 truncate block">{{ $item->company }}</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="text-xs font-black text-slate-700">{{ $item->net_worth_or_metric }}</span>
                                    <span class="text-[9px] font-bold text-emerald-700 bg-emerald-100/70 px-1 py-0.2 rounded border border-emerald-200">▲ +12%</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- =========================================================================
         SECTION 10: CONTINUOUS INTELLIGENCE FEED (Latest 8 Stories Across All Desks)
         ========================================================================= -->
    <section class="py-14 bg-white border-b border-slate-200/80">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-200">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#0A2540] tracking-tight font-headline">Continuous Intelligence Feed</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Chronological updates across global commerce, policy, and innovation.</p>
                </div>
                <!-- Category Quick Jump Filters -->
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <a href="{{ route('categories.show', 'business') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-[#0A2540] hover:text-white transition font-semibold text-slate-700">Business</a>
                    <a href="{{ route('categories.show', 'markets') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-[#0A2540] hover:text-white transition font-semibold text-slate-700">Markets</a>
                    <a href="{{ route('categories.show', 'tech') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-[#0A2540] hover:text-white transition font-semibold text-slate-700">Tech</a>
                    <a href="{{ route('categories.show', 'politics') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-[#0A2540] hover:text-white transition font-semibold text-slate-700">Politics</a>
                    <a href="{{ route('categories.show', 'lifestyle') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-[#0A2540] hover:text-white transition font-semibold text-slate-700">Lifestyle</a>
                    <a href="{{ route('categories.show', 'health') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-[#0A2540] hover:text-white transition font-semibold text-slate-700">Health</a>
                </div>
            </div>

            <!-- 8-Card Feed Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestStream as $streamArticle)
                    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between group shadow-xs">
                        <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $streamArticle->image_url }}" alt="{{ $streamArticle->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 bg-slate-900/80 backdrop-blur-xs text-white font-bold text-[9px] uppercase tracking-wider px-2 py-0.5 rounded shadow-xs">
                                {{ $streamArticle->category?->name ?? 'GENERAL' }}
                            </span>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $streamArticle->slug) }}">{{ $streamArticle->title }}</a>
                                </h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-1.5 leading-relaxed">
                                    {{ $streamArticle->excerpt }}
                                </p>
                            </div>
                            <div class="text-[10px] text-slate-400 flex items-center justify-between pt-3 border-t border-slate-100 mt-3">
                                <span>{{ $streamArticle->author->name }}</span>
                                <span>{{ $streamArticle->published_at ? $streamArticle->published_at->diffForHumans(null, true) . ' ago' : 'Recent' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 11: HIGH-IMPACT NEWSLETTER CTA (Stripe Hero Banner)
         ========================================================================= -->
    <section id="newsletter-cta" class="py-16 bg-[#F8FAFC] text-slate-900">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gradient-to-br from-[#0A2540] via-[#0E355C] to-[#1E293B] text-white border border-slate-700 rounded-3xl p-8 sm:p-14 overflow-hidden shadow-2xl">
                <!-- Glowing Radial Background -->
                <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-[#635BFF]/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="max-w-2xl space-y-4 relative z-10" 
                     x-data="{ 
                         email: '', 
                         loading: false, 
                         subscribed: false, 
                         responseMsg: '',
                         async submitNewsletter() {
                             if (!this.email || this.loading) return;
                             this.loading = true;
                             try {
                                 const token = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');
                                 const res = await fetch('{{ route('newsletter.subscribe') }}', {
                                     method: 'POST',
                                     headers: {
                                         'Content-Type': 'application/json',
                                         'X-CSRF-TOKEN': token || '',
                                         'Accept': 'application/json'
                                     },
                                     body: JSON.stringify({ email: this.email })
                                 });
                                 const data = await res.json();
                                 if (res.ok && data.success) {
                                     this.subscribed = true;
                                     this.responseMsg = data.message || 'Thank you for subscribing to AQ NEWSWIRE!';
                                     this.email = '';
                                     if (window.showToast) window.showToast(this.responseMsg, 'success');
                                 } else {
                                     const errorMsg = data.errors?.email ? data.errors.email[0] : (data.message || 'Unable to subscribe.');
                                     if (window.showToast) window.showToast(errorMsg, 'error');
                                 }
                             } catch (err) {
                                 if (window.showToast) window.showToast('Network error submitting subscription.', 'error');
                             } finally {
                                 this.loading = false;
                             }
                         }
                     }">
                    <span class="text-[#635BFF] font-extrabold text-xs uppercase tracking-widest block bg-white/10 w-fit px-3 py-1 rounded-full border border-white/10">STAY AHEAD</span>
                    <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight font-headline">
                        Unrivaled Market Intelligence. Direct to Your Inbox.
                    </h2>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        Join 250,000+ global leaders, investors, and policymakers who rely on AQ NEWSWIRE daily.
                    </p>

                    <!-- Subscription Form -->
                    <form @submit.prevent="submitNewsletter()" class="pt-3 flex flex-col sm:flex-row gap-3">
                        <input type="email" 
                               x-model="email" 
                               required 
                               placeholder="Enter your corporate email address" 
                               class="flex-1 px-4 py-3 bg-slate-900/80 border border-slate-600 rounded-xl text-xs sm:text-sm text-white placeholder-slate-400 focus:outline-none focus:border-[#635BFF] transition disabled:opacity-50"
                               :disabled="loading">
                        <button type="submit" 
                                :disabled="loading"
                                class="px-6 py-3 bg-[#635BFF] hover:bg-[#5349DF] disabled:bg-slate-700 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-lg shadow-indigo-600/30 shrink-0 flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="!loading">Subscribe Now &rarr;</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>Processing...</span>
                            </span>
                        </button>
                    </form>

                    <!-- Success Feedback Banner -->
                    <div x-show="subscribed" x-cloak class="p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="responseMsg"></span>
                    </div>

                    <!-- Trust Tags -->
                    <div class="pt-4 flex flex-wrap items-center gap-4 text-[11px] text-slate-300">
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-[#635BFF]"></span> Daily Briefing</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span> Exclusive Analysis</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span> Executive Interviews</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Special Reports</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
