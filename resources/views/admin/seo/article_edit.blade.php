@extends('layouts.admin')

@section('title', 'Edit SEO Metadata — ' . $article->title)

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header & Breadcrumbs -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.seo.index') }}" class="hover:text-slate-600">SEO Center</a>
                <span>&rarr;</span>
                <a href="{{ route('admin.seo.articles') }}" class="hover:text-slate-600">Content Audit</a>
                <span>&rarr;</span>
                <span class="text-slate-700 font-semibold truncate max-w-sm">{{ $article->title }}</span>
                <span>&rarr;</span>
                <span class="text-slate-400">Edit SEO</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Article SEO & Discovery Configuration</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Optimize search snippet appearance, canonical routes, and Open Graph social sharing cards.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs inline-flex items-center gap-1.5">
                <span>Preview Public Story</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            <a href="{{ route('admin.seo.articles') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                &larr; Back to Audit
            </a>
        </div>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Please correct the errors below before saving:</span>
            </div>
            @foreach($errors->all() as $err)
                <p class="text-rose-700 ml-6">&bull; {{ $err }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.seo.articles.update', $article) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left 7 Cols: Metadata Inputs -->
            <div class="lg:col-span-7 space-y-5">

                <!-- 1. Primary Search Engine Metadata -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Organic Search Engine Tags (Google / Bing)
                    </h3>

                    <!-- Custom SEO Title -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Custom SEO Title (&lt;title&gt;)</label>
                            <span class="text-[11px] text-slate-400">
                                <span id="titleCount" class="font-bold text-slate-700">0</span> / 60 characters
                                <span id="titleStatus" class="ml-1 text-[10px] font-semibold text-slate-500"></span>
                            </span>
                        </div>
                        <input type="text" 
                               name="meta_title" 
                               id="metaTitleInput" 
                               value="{{ old('meta_title', $article->meta_title) }}" 
                               placeholder="{{ $article->title }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        <span class="text-[10px] text-slate-400 mt-1 block">Recommended: 50–60 characters. Leave blank to automatically use story headline.</span>
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Meta Description</label>
                            <span class="text-[11px] text-slate-400">
                                <span id="descCount" class="font-bold text-slate-700">0</span> / 160 characters
                                <span id="descStatus" class="ml-1 text-[10px] font-semibold text-slate-500"></span>
                            </span>
                        </div>
                        <textarea name="meta_description" 
                                  id="metaDescInput" 
                                  rows="3" 
                                  placeholder="{{ $article->excerpt ?? 'Brief compelling summary of the article for search results...' }}"
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">{{ old('meta_description', $article->meta_description) }}</textarea>
                        <span class="text-[10px] text-slate-400 mt-1 block">Recommended: 140–160 characters. Leave blank to automatically use story excerpt.</span>
                    </div>

                    <!-- Canonical URL -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Canonical URL Override</label>
                        <input type="url" 
                               name="canonical_url" 
                               id="canonicalInput" 
                               value="{{ old('canonical_url', $article->canonical_url) }}" 
                               placeholder="{{ route('articles.show', $article->slug) }}"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        <span class="text-[10px] text-slate-400 mt-1 block">Default: {{ route('articles.show', $article->slug) }}. Only specify if syndicating from another publication.</span>
                    </div>

                    <!-- Robots Directive -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Robots Crawl Directive</label>
                        <select name="robots" id="robotsSelect" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-red-500">
                            <option value="index, follow, max-image-preview:large" {{ old('robots', $article->robots) === 'index, follow, max-image-preview:large' || empty($article->robots) ? 'selected' : '' }}>
                                index, follow (Standard Indexable - Recommended)
                            </option>
                            <option value="noindex, follow" {{ old('robots', $article->robots) === 'noindex, follow' ? 'selected' : '' }}>
                                noindex, follow (Hide from search engines, but crawl internal links)
                            </option>
                            <option value="index, nofollow" {{ old('robots', $article->robots) === 'index, nofollow' ? 'selected' : '' }}>
                                index, nofollow (Index story, but do not follow outgoing links)
                            </option>
                            <option value="noindex, nofollow" {{ old('robots', $article->robots) === 'noindex, nofollow' ? 'selected' : '' }}>
                                noindex, nofollow (Completely block search crawlers)
                            </option>
                        </select>
                    </div>
                </div>

                <!-- 2. Social Media Open Graph (OG) -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Open Graph & Social Share Metadata (LinkedIn, Facebook)
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Open Graph Title</label>
                        <input type="text" 
                               name="og_title" 
                               id="ogTitleInput" 
                               value="{{ old('og_title', $article->og_title) }}" 
                               placeholder="{{ $article->effective_meta_title }}"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Open Graph Description</label>
                        <textarea name="og_description" 
                                  id="ogDescInput" 
                                  rows="2" 
                                  placeholder="{{ $article->effective_meta_description }}"
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">{{ old('og_description', $article->og_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Custom Social Share Image URL</label>
                        <input type="text" 
                               name="og_image" 
                               id="ogImageInput" 
                               value="{{ old('og_image', $article->og_image) }}" 
                               placeholder="{{ $article->image_url }}"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        <span class="text-[10px] text-slate-400 mt-1 block">Defaults to article featured image ({{ $article->featured_image ? 'configured' : 'fallback' }}). Optimal aspect ratio: 1200x630px.</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('admin.seo.articles') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition transform hover:-translate-y-0.5">
                        Save SEO Changes &rarr;
                    </button>
                </div>

            </div>

            <!-- Right 5 Cols: Live Previews -->
            <div class="lg:col-span-5 space-y-5">

                <!-- 1. Google Search Result Snippet Preview -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Google Search Preview</span>
                        <span class="text-[10px] font-bold text-slate-400">SERP Desktop</span>
                    </div>

                    <!-- Mock SERP Box -->
                    <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 space-y-1.5 shadow-2xs font-sans">
                        <div class="flex items-center gap-2 text-[11px] text-slate-700">
                            <span class="w-4 h-4 rounded-full bg-red-600 text-white font-bold text-[9px] flex items-center justify-center shrink-0">A</span>
                            <div class="truncate">
                                <span class="font-semibold text-slate-900">AQ NEWSWIRE</span>
                                <span class="text-slate-400 font-mono text-[10px] ml-1 truncate" id="serpUrlPreview">
                                    {{ route('articles.show', $article->slug) }}
                                </span>
                            </div>
                        </div>

                        <h4 class="text-base text-[#1a0dab] hover:underline font-medium cursor-pointer leading-snug line-clamp-2" id="serpTitlePreview">
                            {{ $article->effective_meta_title }}
                        </h4>

                        <p class="text-xs text-[#4d5156] leading-relaxed line-clamp-3" id="serpDescPreview">
                            {{ $article->effective_meta_description }}
                        </p>
                    </div>
                </div>

                <!-- 2. Social Media Share Card Preview -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Social Share Card Preview</span>
                        <span class="text-[10px] font-bold text-slate-400">Open Graph / LinkedIn / X</span>
                    </div>

                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 shadow-2xs">
                        <div class="h-44 bg-slate-900 overflow-hidden relative">
                            <img src="{{ $article->effective_og_image }}" 
                                 id="socialImagePreview" 
                                 alt="Social preview" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-3.5 bg-white space-y-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">
                                {{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'AQNEWSWIRE.COM' }}
                            </span>
                            <h5 class="text-xs font-bold text-slate-900 line-clamp-2" id="socialTitlePreview">
                                {{ $article->effective_og_title }}
                            </h5>
                            <p class="text-[11px] text-slate-500 line-clamp-2" id="socialDescPreview">
                                {{ $article->effective_og_description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3. Technical Schema Summary -->
                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-4.5 space-y-2 text-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Structured Data Schema</span>
                    <ul class="space-y-1 text-slate-600 text-[11px]">
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Schema.org: <strong>NewsArticle</strong> (Validated)</span>
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Schema.org: <strong>BreadcrumbList</strong> (Validated)</span>
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Author Byline: <strong>{{ $article->author->name }}</strong></span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </form>

</div>

<!-- Vanilla JavaScript Interactive Character Counters & Live Previews -->
<script>
    const defaultTitle = @json($article->title . ' — ' . config('app.name', 'AQ NEWSWIRE'));
    const defaultDesc = @json($article->excerpt ?? Str::limit(strip_tags($article->content ?? ''), 160));
    const defaultImage = @json($article->image_url);

    const titleInput = document.getElementById('metaTitleInput');
    const descInput = document.getElementById('metaDescInput');
    const ogTitleInput = document.getElementById('ogTitleInput');
    const ogDescInput = document.getElementById('ogDescInput');
    const ogImageInput = document.getElementById('ogImageInput');

    const serpTitle = document.getElementById('serpTitlePreview');
    const serpDesc = document.getElementById('serpDescPreview');
    const socialTitle = document.getElementById('socialTitlePreview');
    const socialDesc = document.getElementById('socialDescPreview');
    const socialImage = document.getElementById('socialImagePreview');

    const titleCount = document.getElementById('titleCount');
    const titleStatus = document.getElementById('titleStatus');
    const descCount = document.getElementById('descCount');
    const descStatus = document.getElementById('descStatus');

    function updateTitle() {
        const val = titleInput.value.trim();
        const len = val.length;
        titleCount.textContent = len;

        if (len === 0) {
            titleStatus.textContent = '(Using H1 fallback)';
            titleStatus.className = 'ml-1 text-[10px] font-semibold text-slate-400';
            serpTitle.textContent = defaultTitle;
            if (!ogTitleInput.value.trim()) socialTitle.textContent = defaultTitle;
        } else if (len >= 50 && len <= 60) {
            titleStatus.textContent = '(Optimal)';
            titleStatus.className = 'ml-1 text-[10px] font-semibold text-emerald-600';
            serpTitle.textContent = val;
            if (!ogTitleInput.value.trim()) socialTitle.textContent = val;
        } else if (len > 60) {
            titleStatus.textContent = '(May be truncated)';
            titleStatus.className = 'ml-1 text-[10px] font-semibold text-amber-600';
            serpTitle.textContent = val;
            if (!ogTitleInput.value.trim()) socialTitle.textContent = val;
        } else {
            titleStatus.textContent = '(Short)';
            titleStatus.className = 'ml-1 text-[10px] font-semibold text-slate-500';
            serpTitle.textContent = val;
            if (!ogTitleInput.value.trim()) socialTitle.textContent = val;
        }
    }

    function updateDesc() {
        const val = descInput.value.trim();
        const len = val.length;
        descCount.textContent = len;

        if (len === 0) {
            descStatus.textContent = '(Using excerpt fallback)';
            descStatus.className = 'ml-1 text-[10px] font-semibold text-slate-400';
            serpDesc.textContent = defaultDesc;
            if (!ogDescInput.value.trim()) socialDesc.textContent = defaultDesc;
        } else if (len >= 140 && len <= 160) {
            descStatus.textContent = '(Optimal)';
            descStatus.className = 'ml-1 text-[10px] font-semibold text-emerald-600';
            serpDesc.textContent = val;
            if (!ogDescInput.value.trim()) socialDesc.textContent = val;
        } else if (len > 160) {
            descStatus.textContent = '(May be truncated)';
            descStatus.className = 'ml-1 text-[10px] font-semibold text-amber-600';
            serpDesc.textContent = val;
            if (!ogDescInput.value.trim()) socialDesc.textContent = val;
        } else {
            descStatus.textContent = '(Short)';
            descStatus.className = 'ml-1 text-[10px] font-semibold text-slate-500';
            serpDesc.textContent = val;
            if (!ogDescInput.value.trim()) socialDesc.textContent = val;
        }
    }

    titleInput.addEventListener('input', updateTitle);
    descInput.addEventListener('input', updateDesc);

    ogTitleInput.addEventListener('input', function() {
        socialTitle.textContent = this.value.trim() || titleInput.value.trim() || defaultTitle;
    });

    ogDescInput.addEventListener('input', function() {
        socialDesc.textContent = this.value.trim() || descInput.value.trim() || defaultDesc;
    });

    ogImageInput.addEventListener('input', function() {
        socialImage.src = this.value.trim() || defaultImage;
    });

    // Run initial calculation
    updateTitle();
    updateDesc();
</script>
@endsection
