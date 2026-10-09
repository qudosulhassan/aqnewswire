@extends('layouts.admin')

@section('title', 'Preview: ' . $article->title)

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-12">

    <!-- Top Preview Bar -->
    <div class="sticky top-2 z-40 bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-2xl px-5 py-3 shadow-md flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles.edit', $article) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Editor</span>
            </a>

            <div class="h-4 w-px bg-slate-200"></div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                    @if($article->status === 'published') bg-emerald-50 text-emerald-700 ring-1 ring-emerald-300
                    @elseif($article->status === 'scheduled') bg-purple-50 text-purple-700 ring-1 ring-purple-300
                    @elseif($article->status === 'submitted') bg-amber-50 text-amber-700 ring-1 ring-amber-300
                    @else bg-blue-50 text-blue-700 ring-1 ring-blue-300
                    @endif">
                    <span class="w-1.5 h-1.5 rounded-full 
                        @if($article->status === 'published') bg-emerald-500
                        @elseif($article->status === 'scheduled') bg-purple-500
                        @elseif($article->status === 'submitted') bg-amber-500
                        @else bg-blue-500
                        @endif"></span>
                    {{ $article->status === 'published' ? 'Live Published' : strtoupper($article->status) . ' PREVIEW' }}
                </span>
                <span class="text-xs text-slate-500 font-medium hidden sm:inline">&mdash; Real Editorial Rendering</span>
            </div>
        </div>

        <!-- Viewport Switchers -->
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-semibold">
            <button type="button" 
                    id="btnViewportDesktop"
                    onclick="setViewport('desktop')" 
                    class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 bg-white text-slate-900 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <span class="hidden md:inline">Desktop</span>
            </button>
            <button type="button" 
                    id="btnViewportTablet"
                    onclick="setViewport('tablet')" 
                    class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-800">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                    <line x1="12" y1="18" x2="12" y2="18.01"></line>
                </svg>
                <span class="hidden md:inline">Tablet (768px)</span>
            </button>
            <button type="button" 
                    id="btnViewportMobile"
                    onclick="setViewport('mobile')" 
                    class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-800">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="7" y="2" width="10" height="20" rx="2"></rect>
                    <line x1="12" y1="18" x2="12" y2="18.01"></line>
                </svg>
                <span class="hidden md:inline">Mobile (390px)</span>
            </button>
        </div>

        <!-- Meta Inspector & Public Link -->
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="toggleMetaDrawer()"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>SEO & Diagnostics</span>
            </button>
            @if($article->status === 'published')
                <a href="{{ route('articles.show', $article->slug) }}" target="_blank"
                   class="px-3 py-1.5 text-xs font-semibold text-white bg-slate-900 hover:bg-black rounded-xl transition inline-flex items-center gap-1.5">
                    <span>View Public URL</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>

    <!-- Diagnostic Meta Panel (Collapsible) -->
    <div id="metaDrawer" class="hidden bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
            Editorial Diagnostics & Meta Data
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block mb-1">Effective SEO Title</span>
                <span class="font-semibold text-slate-900">{{ $article->effective_meta_title }}</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block mb-1">Effective Meta Description</span>
                <span class="text-slate-700">{{ Str::limit($article->effective_meta_description, 120) }}</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block mb-1">Canonical URL</span>
                <span class="font-mono text-slate-700 break-all">{{ $article->canonical_url ?: route('articles.show', $article->slug) }}</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block mb-1">Metrics</span>
                <div class="space-y-0.5 font-medium text-slate-800">
                    <div>Words: {{ str_word_count(strip_tags($article->content)) }}</div>
                    <div>Reading Time: {{ $article->reading_time_minutes }} min</div>
                    <div>Robots: {{ $article->robots ?: 'index, follow' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Viewport Container Frame -->
    <div class="flex justify-center transition-all duration-300">
        <div id="previewFrame"
             class="w-full max-w-5xl bg-white overflow-hidden transition-all duration-300">

            <!-- Simulated Mobile Header / Status bar -->
            <div id="mobileStatusBar" class="hidden h-6 bg-slate-900 flex items-center justify-between px-6 text-[10px] text-white">
                <span>9:41</span>
                <div class="flex items-center gap-1.5">
                    <span>5G</span>
                    <span class="inline-block w-4 h-2 bg-white rounded-xs"></span>
                </div>
            </div>

            <!-- AQ NEWSWIRE Simulated Article Viewport -->
            <div class="p-6 sm:p-10 md:p-12 space-y-8 bg-white min-h-[700px]">

                <!-- Category & Publish Details -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider"
                          style="background-color: {{ $article->category->color ?? '#DC2626' }}15; color: {{ $article->category->color ?? '#DC2626' }}">
                        {{ $article->category->name ?? 'News' }}
                    </span>
                    @if($article->is_breaking)
                        <span class="px-2 py-0.5 bg-red-600 text-white text-[10px] font-bold uppercase tracking-wider rounded">
                            Breaking News
                        </span>
                    @endif
                    @if($article->is_featured)
                        <span class="px-2 py-0.5 bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider rounded">
                            Featured
                        </span>
                    @endif
                    @if($article->sponsored_by)
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-bold uppercase tracking-wider rounded">
                            Sponsored by {{ $article->sponsored_by }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-400">
                        {{ $article->published_at ? $article->published_at->format('F j, Y — g:i A') : 'Unpublished Draft' }}
                    </span>
                    <span class="text-xs text-slate-400">&bull; {{ $article->reading_time_minutes }} min read</span>
                </div>

                <!-- Headline -->
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight font-serif">
                    {{ $article->title }}
                </h1>

                <!-- Subtitle / Dek -->
                @if($article->subtitle)
                    <p class="text-lg sm:text-xl text-slate-600 font-normal leading-relaxed">
                        {{ $article->subtitle }}
                    </p>
                @endif

                <!-- Author Bylines -->
                <div class="flex items-center gap-3.5 py-4 border-y border-slate-100">
                    <div class="w-11 h-11 rounded-full bg-slate-200 overflow-hidden flex items-center justify-center font-bold text-slate-600 text-sm">
                        @if($article->author && $article->author->avatar)
                            <img src="{{ str_starts_with($article->author->avatar, 'http') ? $article->author->avatar : asset('storage/' . $article->author->avatar) }}" alt="{{ $article->author->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($article->author->name ?? 'AQ NEWSWIRE', 0, 2) }}
                        @endif
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">{{ $article->author->name ?? 'AQ NEWSWIRE Editorial Board' }}</div>
                        <div class="text-xs text-slate-500">{{ $article->author->title ?? 'Staff Writer' }}</div>
                    </div>
                </div>

                <!-- Featured Cover Image -->
                @if($article->featured_image)
                    <div class="space-y-2">
                        <div class="w-full rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 shadow-2xs">
                            <img src="{{ $article->image_url }}" alt="{{ $article->featured_image_alt ?: $article->title }}" class="w-full h-auto object-cover max-h-[550px]">
                        </div>
                        @if($article->featured_image_caption)
                            <p class="text-xs text-slate-500 italic text-right px-2">
                                {{ $article->featured_image_caption }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Excerpt / Lead Paragraph -->
                @if($article->excerpt)
                    <div class="p-4 bg-slate-50 border-l-4 border-red-600 rounded-r-xl">
                        <p class="text-sm sm:text-base font-medium text-slate-800 leading-relaxed italic">
                            {{ $article->excerpt }}
                        </p>
                    </div>
                @endif

                <!-- Formatted Article Content -->
                <div class="prose prose-slate max-w-none text-base sm:text-lg leading-relaxed font-serif text-slate-800 space-y-4">
                    {!! $article->content !!}
                </div>

                <!-- Tags -->
                @if($article->tags && $article->tags->count() > 0)
                    <div class="pt-8 border-t border-slate-100 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Filed Under:</span>
                        @foreach($article->tags as $tag)
                            <span class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>

<script>
    function setViewport(mode) {
        const frame = document.getElementById('previewFrame');
        const statusBar = document.getElementById('mobileStatusBar');
        const btnDesktop = document.getElementById('btnViewportDesktop');
        const btnTablet = document.getElementById('btnViewportTablet');
        const btnMobile = document.getElementById('btnViewportMobile');

        // Reset button states
        [btnDesktop, btnTablet, btnMobile].forEach(btn => {
            btn.className = 'px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-800';
        });

        // Reset frame classes
        frame.className = 'bg-white overflow-hidden transition-all duration-300';
        statusBar.classList.add('hidden');

        if (mode === 'desktop') {
            btnDesktop.className = 'px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 bg-white text-slate-900 shadow-2xs';
            frame.classList.add('w-full', 'max-w-5xl');
        } else if (mode === 'tablet') {
            btnTablet.className = 'px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 bg-white text-slate-900 shadow-2xs';
            frame.classList.add('w-[768px]', 'border-8', 'border-slate-800', 'rounded-3xl', 'shadow-2xl');
        } else if (mode === 'mobile') {
            btnMobile.className = 'px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 bg-white text-slate-900 shadow-2xs';
            frame.classList.add('w-[390px]', 'border-8', 'border-slate-900', 'rounded-[38px]', 'shadow-2xl');
            statusBar.classList.remove('hidden');
        }
    }

    function toggleMetaDrawer() {
        const drawer = document.getElementById('metaDrawer');
        if (drawer) {
            drawer.classList.toggle('hidden');
        }
    }
</script>
@endsection
