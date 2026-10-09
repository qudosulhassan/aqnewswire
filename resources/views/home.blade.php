@extends('layouts.app')

@section('title', 'AQ NEWSWIRE — Global Business, Markets, Technology & Leadership')

@section('content')
<div class="relative overflow-hidden bg-white text-slate-800">

    <!-- =========================================================================
         SECTION 1: CINEMATIC EDITORIAL HERO ("THE BIG STORY") — STRIPE LIGHT STYLE
         ========================================================================= -->
    <section class="relative bg-white text-slate-900 pt-8 pb-16 lg:pt-12 lg:pb-24 border-b border-slate-200/80 overflow-hidden">
        <!-- Ambient Background Glow & Gradient Mesh -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-40 w-[32rem] h-[32rem] bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Hero Left Column: Editorial Lead (7 Cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Stripe-Style Pill Badge -->
                    <div class="inline-flex items-center gap-2">
                        <span class="bg-[#635BFF]/10 text-[#635BFF] font-bold text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full border border-[#635BFF]/20 shadow-xs">
                            THE BIG STORY
                        </span>
                    </div>

                    <!-- Massive Confident Headline with Electric Gradient -->
                    <h1 class="text-4xl sm:text-6xl xl:text-7xl font-black text-[#0A2540] tracking-tight leading-[1.08] font-sans">
                        The New Rules of 
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#635BFF] via-indigo-600 to-cyan-600">
                            Global Business
                        </span>
                    </h1>

                    <!-- 2-3 Line Summary -->
                    <p class="text-slate-600 text-base sm:text-lg lg:text-xl font-normal leading-relaxed max-w-2xl">
                        {{ $heroStory?->excerpt ?? 'Geopolitics, AI, and capital are reshaping the global economy. Leaders who adapt now will define the next decade.' }}
                    </p>

                    <!-- CTAs Row -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ $heroStory ? route('articles.show', $heroStory->slug) : route('search.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs sm:text-sm font-bold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                            <span>Read the full story</span>
                            <span>&rarr;</span>
                        </a>

                        <a href="{{ route('videos.index') }}" 
                           class="inline-flex items-center gap-2.5 px-5 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 transition shadow-xs">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <span>Watch analysis</span>
                        </a>

                        @if($heroStory)
                            <button type="button" 
                                    onclick="toggleBookmark({{ $heroStory->id }}, this)" 
                                    class="inline-flex items-center gap-2 px-4 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 transition group shadow-xs" 
                                    title="Save to Reading List">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#635BFF] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                                <span>Save Story</span>
                            </button>
                        @endif
                    </div>

                    <!-- Editorial Byline & Metadata -->
                    <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-slate-200/80 text-xs text-slate-500">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ $heroStory?->author?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}" 
                                 alt="Author" 
                                 class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200">
                            <div>
                                <span class="text-[#0A2540] font-bold block leading-tight">{{ $heroStory?->author?->name ?? 'Alexander Vance' }}</span>
                                <span class="text-[11px] text-slate-400">Global Business Correspondent</span>
                            </div>
                        </div>

                        <span class="text-slate-300">•</span>

                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $heroStory?->reading_time_minutes ?? 8 }} min read</span>
                        </div>

                        <span class="text-slate-300">•</span>

                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ number_format($heroStory?->view_count > 0 ? $heroStory->view_count : 24500) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Right Column: Cinematic Visual & Floating Annotation (5 Cols) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-2xl overflow-hidden border border-slate-200/80 shadow-xl bg-slate-100 group">
                        <!-- Featured Cinematic Image -->
                        <div class="aspect-[16/11] overflow-hidden relative">
                            <img src="{{ $heroStory?->image_url ?? 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80' }}" 
                                 alt="Global Business Graphic" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-out">
                            <!-- Overlay Sheen -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                        </div>

                        <!-- Top-Right Floating Bookmark Icon -->
                        @if($heroStory)
                            <button type="button" 
                                    onclick="toggleBookmark({{ $heroStory->id }}, this)" 
                                    class="absolute top-4 right-4 p-2.5 rounded-xl bg-white/90 hover:bg-white text-slate-600 hover:text-[#635BFF] border border-slate-200/80 backdrop-blur-md transition shadow-md group/btn" 
                                    title="Save to Reading List">
                                <svg class="w-4 h-4 group-hover/btn:text-[#635BFF] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        @endif

                        <!-- Floating Editorial Annotation -->
                        <div class="absolute bottom-5 right-5 sm:bottom-6 sm:right-6 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-xl p-3.5 shadow-xl max-w-xs">
                            <div class="flex items-center gap-2.5 text-xs text-slate-600">
                                <span class="w-2 h-2 rounded-full bg-[#635BFF] animate-ping shrink-0"></span>
                                <p class="text-[11px] leading-snug">
                                    <strong class="text-[#0A2540] block font-semibold">Editorial Observation</strong>
                                    A more connected global economy is emerging.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 2: BREAKING NEWS TICKER
         ========================================================================= -->
    <div class="bg-[#F8FAFC] border-b border-slate-200/80 text-slate-800 py-3 px-4 overflow-hidden relative">
        <div class="max-w-[1600px] mx-auto flex items-center gap-4">
            <!-- Breaking Pill Badge -->
            <div class="inline-flex items-center gap-2 bg-[#635BFF] text-white font-extrabold text-[11px] uppercase tracking-wider px-3 py-1 rounded-full shrink-0 shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                <span>BREAKING NEWS</span>
            </div>

            <!-- Marquee Stream -->
            <div class="overflow-hidden whitespace-nowrap flex-1 text-xs text-slate-600 font-medium">
                <div class="animate-marquee inline-block">
                    <span class="mx-4">Global markets rise as tech stocks lead rally</span>
                    <span class="text-slate-300">•</span>
                    <span class="mx-4">AI investment hits record $200B in 2026</span>
                    <span class="text-slate-300">•</span>
                    <span class="mx-4">Central banks signal cautious rate cuts ahead</span>
                    <span class="text-slate-300">•</span>
                    <span class="mx-4">Clean energy funding surges worldwide</span>
                    @if(isset($breakingNews) && $breakingNews->isNotEmpty())
                        @foreach($breakingNews as $b)
                            <span class="text-slate-300">•</span>
                            <a href="{{ route('articles.show', $b->slug) }}" class="mx-4 hover:text-[#635BFF] font-semibold text-[#0A2540]">{{ $b->title }}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Ticker Arrow Controls -->
            <div class="hidden sm:flex items-center gap-1 shrink-0 text-slate-400">
                <button type="button" class="p-1 rounded hover:bg-slate-200 hover:text-slate-700" title="Previous story">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="p-1 rounded hover:bg-slate-200 hover:text-slate-700" title="Next story">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION 3: LATEST INSIGHTS (Horizontal Card Rail)
         ========================================================================= -->
    <section class="py-12 lg:py-16 bg-white border-b border-slate-200/80" x-data>
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-2">
                    <h2 class="text-xl sm:text-2xl font-bold text-[#0A2540] tracking-tight">Latest Insights</h2>
                    <span class="text-[#635BFF] font-bold">&rarr;</span>
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

            <!-- Responsive Scrolling Rail & Grid -->
            <div x-ref="insightsRail" class="flex lg:grid lg:grid-cols-4 gap-6 overflow-x-auto lg:overflow-visible pb-4 lg:pb-0 scroll-smooth snap-x snap-mandatory">
                @php
                    $defaultInsights = [
                        [
                            'category' => 'TECHNOLOGY', 'time' => '2h ago',
                            'title' => 'OpenAI Unveils Next-Gen AI Models for Enterprise Teams',
                            'views' => '12.4K', 'read' => '6 min read',
                            'img' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'category' => 'MARKETS & FINANCE', 'time' => '3h ago',
                            'title' => 'Clean Energy Investment Hits $200 Billion in 2026',
                            'views' => '8.2K', 'read' => '4 min read',
                            'img' => 'https://images.unsplash.com/photo-1466611653911-95081537e5b7?auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'category' => 'BUSINESS', 'time' => '5h ago',
                            'title' => 'Global Chip Supply Chain Enters a New Phase of Stability',
                            'views' => '6.1K', 'read' => '6 min read',
                            'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'category' => 'LEADERSHIP', 'time' => '6h ago',
                            'title' => 'How Visionary Leaders Navigate Uncertain Times',
                            'views' => '15.8K', 'read' => '7 min read',
                            'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80',
                        ],
                    ];
                @endphp

                @foreach($latestInsights as $idx => $insight)
                    @php
                        $fallback = $defaultInsights[$idx % count($defaultInsights)];
                    @endphp
                    <div class="min-w-[280px] sm:min-w-[320px] lg:min-w-0 snap-start bg-white border border-slate-200/80 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-lg transition duration-300 flex flex-col group relative shadow-xs">
                        <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $insight->image_url ?? $fallback['img'] }}" 
                                 alt="{{ $insight->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            <!-- Quick Bookmark Button -->
                            <button type="button" 
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleBookmark({{ $insight->id }}, this)" 
                                    class="absolute top-2.5 right-2.5 p-1.5 rounded-lg bg-white/90 hover:bg-white border border-slate-200 text-slate-500 hover:text-[#635BFF] backdrop-blur-sm transition shadow-xs group/bm" 
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
                                        {{ $insight->category?->name ?? $fallback['category'] }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium">
                                        {{ $insight->published_at ? $insight->published_at->diffForHumans(null, true) . ' ago' : $fallback['time'] }}
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
                                    {{ number_format($insight->view_count > 0 ? $insight->view_count : 12400) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $insight->reading_time_minutes ?? 6 }} min read
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 4: "WHAT'S MOVING THE WORLD" + LIVE MARKETS
         ========================================================================= -->
    <section class="py-12 lg:py-16 bg-[#F8FAFC] border-b border-slate-200/80">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                <!-- Left 8 Columns: "What's moving the world" -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div>
                            <h2 class="text-2xl font-bold text-[#0A2540] tracking-tight">What's moving the world</h2>
                            <p class="text-xs text-slate-500 mt-0.5">In-depth analysis, expert perspectives and exclusive reporting.</p>
                        </div>
                        <a href="{{ route('categories.show', 'business') }}" class="text-xs text-[#635BFF] hover:underline font-semibold flex items-center gap-1">
                            <span>View all stories</span> &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Featured Large Editorial Card (7 cols) -->
                        <div class="md:col-span-7 bg-white border border-slate-200/80 rounded-2xl overflow-hidden relative group flex flex-col justify-between shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img src="{{ $whatsMovingFeature?->image_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="Feature" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/20 to-transparent"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-[#635BFF] text-white font-extrabold text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-md shadow-xs">
                                        {{ $whatsMovingFeature?->category?->name ?? 'AI & INNOVATION' }} • {{ $whatsMovingFeature?->reading_time_minutes ?? 8 }} min read
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 relative -mt-8 z-10 space-y-3 bg-white">
                                <h3 class="text-xl sm:text-2xl font-black text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
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
                                        <span class="text-[#0A2540] font-bold block">{{ $whatsMovingFeature?->author?->name ?? 'Sophia Thorne' }}</span>
                                        <span class="text-[10px] text-slate-400">Senior Technology Correspondent</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right 3 Compact Stacked Stories (5 cols) -->
                        <div class="md:col-span-5 flex flex-col justify-between gap-4">
                            @foreach($whatsMovingSide as $side)
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 flex gap-4 hover:border-slate-300 hover:shadow-md transition group shadow-xs">
                                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                        <img src="{{ $side->image_url }}" alt="Thumb" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#635BFF]">
                                                {{ $side->category?->name ?? 'MARKETS' }} • {{ $side->reading_time_minutes ?? 4 }} min
                                            </span>
                                            <h4 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2 mt-1">
                                                <a href="{{ route('articles.show', $side->slug) }}">
                                                    {{ $side->title }}
                                                </a>
                                            </h4>
                                        </div>
                                        <span class="text-[11px] text-slate-400">
                                            {{ $side->published_at ? $side->published_at->diffForHumans(null, true) . ' ago' : 'Recently' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right 4 Columns: Live Markets Stripe Light Panel -->
                <div class="lg:col-span-4 bg-white border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between shadow-xs">
                    <div>
                        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                            <h3 class="text-base font-bold text-[#0A2540] flex items-center gap-2">
                                <span>Live Markets</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </h3>
                            <a href="{{ route('categories.show', 'markets') }}" class="text-xs text-[#635BFF] hover:underline font-semibold">
                                View more &rarr;
                            </a>
                        </div>

                        <!-- Indices Table -->
                        <div class="divide-y divide-slate-100 mt-2">
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
                                    <div class="w-16 h-6 shrink-0">
                                        <svg class="w-full h-full {{ $idx['is_up'] ? 'text-emerald-500' : 'text-rose-500' }}" viewBox="0 0 75 20" fill="none">
                                            <path d="{{ $idx['points'] }}" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 text-[10px] text-slate-400 text-center">
                        Last updated: {{ now()->format('M j, Y g:i A') }} (GMT)
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 5: AQ NEWSWIRE INTELLIGENCE (Data-Driven Stripe Module)
         ========================================================================= -->
    <section class="py-16 bg-white text-slate-900 border-b border-slate-200/80 relative overflow-hidden" 
             x-data="{ 
                 range: '1Y',
                 datasets: {
                     '1M': {
                         metric1: '$240B', metric1Label: 'Clean Energy Invested (MoM)', metric1Trend: '+4.2%',
                         metric2: '4.1%', metric2Label: 'Global Growth Forecast', metric2Trend: '+0.5%',
                         metric3: '86%', metric3Label: 'Enterprise AI Adoption', metric3Trend: '+6.1%',
                         gdpPath: 'M 0 160 Q 100 140, 200 110 T 360 85 T 500 50',
                         gdpArea: 'M 0 160 Q 100 140, 200 110 T 360 85 T 500 50 L 500 200 L 0 200 Z',
                         aiPath: 'M 0 180 Q 110 170, 220 130 T 380 90 T 500 65',
                         aiArea: 'M 0 180 Q 110 170, 220 130 T 380 90 T 500 65 L 500 200 L 0 200 Z',
                         cx: 360, cy: 85, tooltipValue: '4.1%', tooltipLabel: '1M Global AI Momentum', tooltipTop: '18%', tooltipRight: '22%'
                     },
                     '3M': {
                         metric1: '$215B', metric1Label: 'Clean Energy Invested (Q1)', metric1Trend: '+3.1%',
                         metric2: '3.9%', metric2Label: 'Global Growth Forecast', metric2Trend: '+0.3%',
                         metric3: '79%', metric3Label: 'Enterprise AI Adoption', metric3Trend: '+4.4%',
                         gdpPath: 'M 0 165 Q 115 155, 210 120 T 360 80 T 500 45',
                         gdpArea: 'M 0 165 Q 115 155, 210 120 T 360 80 T 500 45 L 500 200 L 0 200 Z',
                         aiPath: 'M 0 182 Q 115 165, 225 140 T 380 100 T 500 75',
                         aiArea: 'M 0 182 Q 115 165, 225 140 T 380 100 T 500 75 L 500 200 L 0 200 Z',
                         cx: 360, cy: 80, tooltipValue: '3.9%', tooltipLabel: '3M Aggregate Projection', tooltipTop: '16%', tooltipRight: '24%'
                     },
                     '1Y': {
                         metric1: '$200B', metric1Label: 'Clean Energy Invested (2026)', metric1Trend: '+2.8%',
                         metric2: '3.8%', metric2Label: 'Global Growth Forecast', metric2Trend: '+0.4%',
                         metric3: '72%', metric3Label: 'Enterprise AI Adoption', metric3Trend: '+5.2%',
                         gdpPath: 'M 0 170 Q 120 180, 200 130 T 360 70 T 500 40',
                         gdpArea: 'M 0 170 Q 120 180, 200 130 T 360 70 T 500 40 L 500 200 L 0 200 Z',
                         aiPath: 'M 0 185 Q 120 160, 220 150 T 380 110 T 500 90',
                         aiArea: 'M 0 185 Q 120 160, 220 150 T 380 110 T 500 90 L 500 200 L 0 200 Z',
                         cx: 360, cy: 70, tooltipValue: '3.8%', tooltipLabel: 'Global GDP Forecast 2026', tooltipTop: '12%', tooltipRight: '22%'
                     },
                     '5Y': {
                         metric1: '$1.2T', metric1Label: 'Cumulative Energy CapEx', metric1Trend: '+18.5%',
                         metric2: '5.4%', metric2Label: 'Productivity Lift Rate', metric2Trend: '+2.1%',
                         metric3: '94%', metric3Label: 'Enterprise AI Integration', metric3Trend: '+12.0%',
                         gdpPath: 'M 0 180 Q 100 150, 200 100 T 350 50 T 500 20',
                         gdpArea: 'M 0 180 Q 100 150, 200 100 T 350 50 T 500 20 L 500 200 L 0 200 Z',
                         aiPath: 'M 0 190 Q 110 140, 210 110 T 370 70 T 500 40',
                         aiArea: 'M 0 190 Q 110 140, 210 110 T 370 70 T 500 40 L 500 200 L 0 200 Z',
                         cx: 350, cy: 50, tooltipValue: '5.4%', tooltipLabel: '5Y Long-Term Transformation', tooltipTop: '8%', tooltipRight: '25%'
                     }
                 }
             }">
        <!-- Subtle Glow Accent -->
        <div class="absolute inset-0 bg-radial from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Title & Metrics (6 cols) -->
                <div class="lg:col-span-6 space-y-8">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-2">
                            <span class="text-[#635BFF] font-extrabold text-xs uppercase tracking-widest">AQ NEWSWIRE Intelligence</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-black text-[#0A2540] tracking-tight leading-tight">
                            Data-driven insights for a more complex world.
                        </h2>
                        <div class="mt-4">
                            <a href="{{ route('categories.show', 'ai') }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-[#0A2540] text-xs font-bold rounded-xl border border-slate-200 transition">
                                <span>Explore Insights</span> &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- 3 Reactive Stats Columns -->
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-200">
                        <div class="space-y-1">
                            <h4 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight" x-text="datasets[range].metric1">$200B</h4>
                            <p class="text-[11px] text-slate-500 leading-tight font-medium" x-text="datasets[range].metric1Label">Clean Energy Invested (2026)</p>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded">
                                ▲ <span x-text="datasets[range].metric1Trend">+2.8%</span>
                            </span>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight" x-text="datasets[range].metric2">3.8%</h4>
                            <p class="text-[11px] text-slate-500 leading-tight font-medium" x-text="datasets[range].metric2Label">Global Growth Forecast</p>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded">
                                ▲ <span x-text="datasets[range].metric2Trend">+0.4%</span>
                            </span>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-2xl sm:text-3xl font-black text-[#0A2540] tracking-tight" x-text="datasets[range].metric3">72%</h4>
                            <p class="text-[11px] text-slate-500 leading-tight font-medium" x-text="datasets[range].metric3Label">Enterprise AI Adoption</p>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded">
                                ▲ <span x-text="datasets[range].metric3Trend">+5.2%</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Interactive Multi-Spline Chart (6 cols) -->
                <div class="lg:col-span-6 bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-6 shadow-sm relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#635BFF] animate-pulse"></span>
                            <span class="text-xs font-bold text-[#0A2540]">Global GDP & AI Growth Forecast</span>
                        </div>
                        <!-- Range Filters -->
                        <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-500 shadow-xs">
                            <button type="button" @click="range = '1M'" :class="range === '1M' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2.5 py-1 rounded transition cursor-pointer">1M</button>
                            <button type="button" @click="range = '3M'" :class="range === '3M' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2.5 py-1 rounded transition cursor-pointer">3M</button>
                            <button type="button" @click="range = '1Y'" :class="range === '1Y' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2.5 py-1 rounded transition cursor-pointer">1Y</button>
                            <button type="button" @click="range = '5Y'" :class="range === '5Y' ? 'bg-[#635BFF] text-white shadow-xs' : 'hover:text-[#0A2540]'" class="px-2.5 py-1 rounded transition cursor-pointer">5Y</button>
                        </div>
                    </div>

                    <!-- Glowing Dual Spline SVG Canvas -->
                    <div class="h-60 w-full relative mt-4">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 500 200" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartGrad1" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#635BFF" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#635BFF" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="chartGrad2" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#0EA5E9" stop-opacity="0.20"/>
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

                        <!-- Dynamic Floating Tooltip Card -->
                        <div class="absolute bg-white border border-slate-200/90 rounded-xl px-3 py-1.5 shadow-xl text-[11px] text-slate-800 transition-all duration-500 pointer-events-none"
                             :style="{ top: datasets[range].tooltipTop, right: datasets[range].tooltipRight }">
                            <span class="text-[#635BFF] font-bold" x-text="datasets[range].tooltipValue">3.8%</span> 
                            <span class="text-slate-600 ml-1" x-text="datasets[range].tooltipLabel">Global GDP Forecast 2026</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 6: CATEGORY ECOSYSTEM (Top Stories, AI, Leadership, Finance)
         ========================================================================= -->
    <section class="py-16 bg-white border-b border-slate-200/80">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Col 1: Top Stories -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <h3 class="font-bold text-[#0A2540] text-base">Top Stories</h3>
                        <a href="{{ route('categories.show', 'business') }}" class="text-xs text-[#635BFF] hover:underline font-semibold">View all &rarr;</a>
                    </div>
                    @if($topStories->isNotEmpty())
                        @php $top = $topStories->first(); @endphp
                        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100">
                                <img src="{{ $top->image_url }}" alt="{{ $top->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="p-4 space-y-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#635BFF]">
                                    {{ $top->category?->name ?? 'BUSINESS' }} • {{ $top->reading_time_minutes ?? 6 }} min
                                </span>
                                <h4 class="text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $top->slug) }}">{{ $top->title }}</a>
                                </h4>
                                <div class="text-[11px] text-slate-500 flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span>{{ $top->author->name }}</span>
                                    <span>{{ number_format($top->view_count > 0 ? $top->view_count : 18400) }} views</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Col 2: AI & Innovation -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <h3 class="font-bold text-[#0A2540] text-base">AI & Innovation</h3>
                        <a href="{{ route('categories.show', 'ai') }}" class="text-xs text-[#635BFF] hover:underline font-semibold">View all &rarr;</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($aiStories as $ai)
                            <div class="bg-white border border-slate-200/80 rounded-2xl p-3 flex gap-3 hover:border-slate-300 hover:shadow-sm transition group shadow-xs">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                    <img src="{{ $ai->image_url }}" alt="Thumb" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h4 class="text-xs font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                        <a href="{{ route('articles.show', $ai->slug) }}">{{ $ai->title }}</a>
                                    </h4>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $ai->reading_time_minutes ?? 4 }} min read
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Col 3: Leadership -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <h3 class="font-bold text-[#0A2540] text-base">Leadership</h3>
                        <a href="{{ route('categories.show', 'workplace') }}" class="text-xs text-[#635BFF] hover:underline font-semibold">View all &rarr;</a>
                    </div>
                    @if($leadershipStories->isNotEmpty())
                        @php $leader = $leadershipStories->first(); @endphp
                        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group shadow-xs hover:shadow-md transition">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100">
                                <img src="{{ $leader->image_url }}" alt="{{ $leader->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="p-4 space-y-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#635BFF]">
                                    LEADERSHIP • {{ $leader->reading_time_minutes ?? 7 }} min
                                </span>
                                <h4 class="text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $leader->slug) }}">{{ $leader->title }}</a>
                                </h4>
                                <div class="text-[11px] text-slate-500 flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span>{{ $leader->author->name }}</span>
                                    <span>{{ number_format($leader->view_count > 0 ? $leader->view_count : 12600) }} views</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Col 4: Markets & Finance -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <h3 class="font-bold text-[#0A2540] text-base">Markets & Finance</h3>
                        <a href="{{ route('categories.show', 'markets') }}" class="text-xs text-[#635BFF] hover:underline font-semibold">View all &rarr;</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($financeStories as $fin)
                            <div class="bg-white border border-slate-200/80 rounded-2xl p-3 flex gap-3 hover:border-slate-300 hover:shadow-sm transition group shadow-xs">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                    <img src="{{ $fin->image_url }}" alt="Thumb" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h4 class="text-xs font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug line-clamp-2">
                                        <a href="{{ route('articles.show', $fin->slug) }}">{{ $fin->title }}</a>
                                    </h4>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $fin->reading_time_minutes ?? 5 }} min read
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 7: AQ NEWSWIRE LISTS (Rankings Strip)
         ========================================================================= -->
    @if(isset($featuredList) && $featuredList)
        <section class="py-14 bg-[#F8FAFC] border-b border-slate-200/80 text-slate-800" x-data>
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-black text-[#0A2540] tracking-tight">AQ NEWSWIRE Lists</span>
                            <span class="text-[#635BFF] font-bold">★</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">The people, companies and ideas shaping tomorrow.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('rankings.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-xs transition">
                            View all rankings &rarr;
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
                        <div class="min-w-[260px] lg:min-w-0 snap-start bg-white border border-slate-200/80 hover:border-slate-300 rounded-2xl p-4 transition group flex items-center gap-3.5 shadow-xs hover:shadow-md">
                            <span class="text-2xl font-black text-slate-300 group-hover:text-[#635BFF] transition shrink-0 font-mono">
                                0{{ $item->rank }}
                            </span>
                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                            <div class="overflow-hidden flex-1">
                                <h4 class="text-xs font-bold text-[#0A2540] group-hover:text-[#635BFF] transition truncate">{{ $item->name }}</h4>
                                <span class="text-[10px] text-slate-500 truncate block">{{ $item->company }}</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="text-xs font-black text-slate-700">{{ $item->net_worth_or_metric }}</span>
                                    <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-1 py-0.2 rounded border border-emerald-200">▲ +12%</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- =========================================================================
         SECTION 8: HIGH-IMPACT NEWSLETTER CTA (AJAX Powered - Stripe Hero Banner)
         ========================================================================= -->
    <section id="newsletter-cta" class="py-16 bg-white text-slate-900">
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
                    <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                        Unrivaled Market Intelligence. Direct to Your Inbox.
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Join 250,000+ global leaders, investors and innovators who read AQ NEWSWIRE daily.
                    </p>

                    <!-- Subscription Form -->
                    <form @submit.prevent="submitNewsletter()" class="pt-3 flex flex-col sm:flex-row gap-3">
                        <input type="email" 
                               x-model="email" 
                               required 
                               placeholder="Enter your corporate email address" 
                               class="flex-1 px-4 py-3.5 bg-slate-900/80 border border-slate-600 rounded-xl text-xs sm:text-sm text-white placeholder-slate-400 focus:outline-none focus:border-[#635BFF] transition disabled:opacity-50"
                                :disabled="loading">
                        <button type="submit" 
                                :disabled="loading"
                                class="px-6 py-3.5 bg-[#635BFF] hover:bg-[#5349DF] disabled:bg-slate-700 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-lg shadow-indigo-600/30 shrink-0 flex items-center justify-center gap-2 cursor-pointer">
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
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span> Expert Interviews</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Special Reports</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
