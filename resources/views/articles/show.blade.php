@extends('layouts.app')

@section('title', $article->effective_meta_title)
@section('meta_description', $article->effective_meta_description)
@section('canonical', $article->effective_canonical_url)
@section('robots', $article->effective_robots)
@section('og_type', 'article')
@section('og_title', $article->effective_og_title)
@section('og_description', $article->effective_og_description)
@section('og_url', $article->effective_canonical_url)
@section('og_image', $article->effective_og_image)
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $article->effective_twitter_title)
@section('twitter_description', $article->effective_twitter_description)
@section('twitter_image', $article->effective_twitter_image)

@push('head')
<!-- Schema.org JSON-LD Structured Data: NewsArticle & BreadcrumbList -->
@php
$articleSchema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'NewsArticle',
            '@id' => $article->effective_canonical_url . '#article',
            'isPartOf' => [
                '@type' => 'WebPage',
                '@id' => $article->effective_canonical_url,
            ],
            'headline' => $article->title,
            'description' => $article->effective_meta_description,
            'image' => array_values(array_filter([$article->effective_og_image])),
            'datePublished' => $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $article->effective_canonical_url,
            ],
            'author' => [
                [
                    '@type' => 'Person',
                    'name' => $article->author->name,
                    'jobTitle' => $article->author->title ?? 'Staff Journalist',
                    'url' => route('authors.show', $article->author->slug),
                ],
            ],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => \App\Models\Setting::get('short_brand_name', 'AQ NEWSWIRE'),
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset(\App\Models\Setting::get('logo_url', '/images/logo.png')),
                ],
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $article->category->name,
                    'item' => route('categories.show', $article->category->slug),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $article->title,
                    'item' => $article->effective_canonical_url,
                ],
            ],
        ],
    ],
];
@endphp
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div x-data="{
    scrollProgress: 0,
    toastMsg: '',
    showToast: false,
    fontSize: 'md',
    triggerToast(msg) {
        this.toastMsg = msg;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3000);
    }
}" 
x-init="
    const updateScroll = () => {
        const winScroll = window.scrollY || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        scrollProgress = height > 0 ? Math.min(100, Math.max(0, (winScroll / height) * 100)) : 0;
    };
    window.addEventListener('scroll', updateScroll, { passive: true });
    updateScroll();
"
class="bg-white text-slate-800 min-h-screen relative selection:bg-[#635BFF] selection:text-white">

    <!-- 0. Dynamic Top Reading Progress Indicator -->
    <div class="fixed top-0 left-0 h-1 bg-gradient-to-r from-[#635BFF] via-[#00D4FF] to-[#635BFF] z-50 transition-all duration-75 pointer-events-none" 
         :style="'width: ' + scrollProgress + '%'"></div>

    <!-- Live Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 z-50 bg-[#0A2540] border border-slate-700 text-white px-4 py-2.5 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2"
         style="display: none;">
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        <span x-text="toastMsg"></span>
    </div>

    <!-- Editorial Preview Banner (Visible to Staff only on Unpublished Articles) -->
    @if(isset($canPreview) && $canPreview && ($article->status !== 'published' || ($article->published_at && $article->published_at->isFuture())))
        <div class="bg-amber-50 border-b border-amber-300 text-amber-900 px-4 py-2 text-xs font-bold text-center tracking-wide flex items-center justify-center gap-2">
            <span class="px-2 py-0.5 rounded bg-amber-500 text-white text-[10px] uppercase font-black">Staff Preview</span>
            <span>This article is currently <strong>{{ strtoupper($article->status) }}</strong> and is hidden from public indexing.</span>
        </div>
    @endif

    <!-- 1. Breadcrumb Bar -->
    <div class="border-b border-slate-200/80 bg-[#F8FAFC]">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-slate-500 text-xs font-medium overflow-x-auto no-scrollbar font-sans" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-[#635BFF] transition whitespace-nowrap">Home</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('categories.show', $article->category->slug) }}" class="hover:text-[#635BFF] transition whitespace-nowrap">
                    {{ $article->category->name }}
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-[#0A2540] font-semibold truncate max-w-xs sm:max-w-md lg:max-w-lg" title="{{ $article->title }}">
                    {!! html_entity_decode($article->title) !!}
                </span>
            </nav>
        </div>
    </div>

    <!-- 2. Main Article Container (Unified Grid for Perfect Typography Alignment) -->
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">
            
            <!-- Primary Left Column: Article Header + Cover + Narration + Content (8 Columns) -->
            <main class="lg:col-span-8 min-w-0 space-y-8">
                
                <!-- Category Eyebrow & Badges -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('categories.show', $article->category->slug) }}" 
                       class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#635BFF] bg-[#635BFF]/10 hover:bg-[#635BFF]/20 px-3 py-1 rounded-full transition inline-flex items-center gap-1.5 font-sans">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#635BFF]"></span>
                        <span>{{ $article->category->name }}</span>
                    </a>
                    
                    <span class="text-slate-300">&middot;</span>
                    <span class="text-[11px] font-semibold text-slate-500 tracking-wider uppercase font-sans">Executive Intelligence</span>

                    @if($article->is_breaking)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-rose-600 text-white animate-pulse font-sans">
                            Breaking
                        </span>
                    @endif
                    @if($article->is_trending)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-600 text-white font-sans">
                            Trending
                        </span>
                    @endif
                </div>

                <!-- Dominant Article Headline -->
                <h1 class="font-headline text-2xl sm:text-4xl md:text-5xl lg:text-[2.85rem] font-black text-[#0A2540] leading-[1.12] tracking-tight">
                    {!! html_entity_decode($article->title) !!}
                </h1>

                <!-- High-Legibility Editorial Dek / Subtitle -->
                @if($article->subtitle)
                    <p class="font-serif text-slate-600 text-lg sm:text-xl lg:text-[1.35rem] font-normal leading-relaxed border-l-3 border-[#635BFF] pl-4 py-0.5">
                        {{ $article->subtitle }}
                    </p>
                @endif

                <!-- Author Byline, Publication Meta & Reader Actions Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between py-4 border-y border-slate-200/90 gap-4 font-sans">
                    
                    <!-- Author Profile Brief -->
                    <div class="flex items-center gap-3.5">
                        <a href="{{ route('authors.show', $article->author->slug) }}" class="shrink-0">
                            <img src="{{ $article->author->avatar_url }}" 
                                 alt="{{ $article->author->name }}" 
                                 class="w-12 h-12 rounded-full object-cover ring-2 ring-slate-200 hover:ring-[#635BFF] transition">
                        </a>
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('authors.show', $article->author->slug) }}" 
                                   class="text-sm font-bold text-[#0A2540] hover:text-[#635BFF] transition">
                                    {{ $article->author->name }}
                                </a>
                                @if($article->author->is_verified)
                                    <svg class="w-4 h-4 text-[#635BFF] shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Verified Journalist">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif

                                <!-- Follow Author Button -->
                                <form action="{{ route('follow.toggle') }}" method="POST" class="inline ml-1">
                                    @csrf
                                    <input type="hidden" name="type" value="user">
                                    <input type="hidden" name="id" value="{{ $article->user_id }}">
                                    <button type="submit" 
                                            class="text-[11px] font-bold px-2.5 py-0.5 rounded-lg border transition {{ $isFollowingAuthor ? 'bg-slate-100 text-slate-700 border-slate-300' : 'bg-indigo-50 text-[#635BFF] border-indigo-200 hover:bg-[#635BFF] hover:text-white' }}">
                                        {{ $isFollowingAuthor ? '✓ Following' : '+ Follow' }}
                                    </button>
                                </form>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                <span>{{ $article->author->title ?? 'Staff Correspondent' }}</span>
                                <span>&middot;</span>
                                <span>{{ $article->published_at ? $article->published_at->format('M j, Y') : $article->created_at->format('M j, Y') }}</span>
                                <span>&middot;</span>
                                <span class="text-slate-700 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $article->reading_time_minutes }} min read
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Reader Actions: Text Size, Bookmark & Share Controls -->
                    <div class="flex items-center gap-2 text-xs shrink-0">
                        
                        <!-- Text Size Toggler -->
                        <div class="flex items-center bg-slate-100 rounded-xl p-0.5 border border-slate-200 text-[11px] font-bold" title="Adjust text size">
                            <button type="button" @click="fontSize = 'sm'" :class="fontSize === 'sm' ? 'bg-white text-[#635BFF] shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-1 rounded-lg transition">A-</button>
                            <button type="button" @click="fontSize = 'md'" :class="fontSize === 'md' ? 'bg-white text-[#635BFF] shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-1 rounded-lg transition">A</button>
                            <button type="button" @click="fontSize = 'lg'" :class="fontSize === 'lg' ? 'bg-white text-[#635BFF] shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-1 rounded-lg transition">A+</button>
                        </div>

                        <!-- Bookmark Story Button -->
                        @if(\App\Models\Setting::get('enable_bookmarks', true))
                            <form action="{{ route('bookmark.toggle', $article) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-semibold transition {{ $isBookmarked ? 'bg-[#635BFF] text-white border-[#635BFF] shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:text-[#0A2540] hover:border-slate-300 shadow-xs' }}">
                                    <span>{{ $isBookmarked ? '★' : '☆' }}</span>
                                    <span class="hidden sm:inline">{{ $isBookmarked ? 'Saved' : 'Save' }}</span>
                                </button>
                            </form>
                        @endif

                        <span class="w-px h-5 bg-slate-200 mx-0.5 hidden sm:block"></span>

                        <!-- Share Controls -->
                        <div class="flex items-center gap-1 text-slate-500">
                            <!-- X / Twitter -->
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($article->effective_canonical_url) }}&text={{ urlencode($article->title) }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               title="Share on X"
                               class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-[#0A2540] hover:border-slate-300 transition shadow-xs">
                                𝕏
                            </a>

                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($article->effective_canonical_url) }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               title="Share on LinkedIn"
                               class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-[#0A2540] hover:border-slate-300 transition font-bold text-[11px] shadow-xs">
                                in
                            </a>

                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . $article->effective_canonical_url) }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               title="Share on WhatsApp"
                               class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-emerald-600 hover:border-slate-300 transition shadow-xs">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </a>

                            <!-- Copy Canonical Link -->
                            <button type="button" 
                                    @click="
                                        navigator.clipboard.writeText('{{ $article->effective_canonical_url }}')
                                             .then(() => triggerToast('Canonical story link copied to clipboard!'))
                                             .catch(() => triggerToast('Unable to copy automatically.'));
                                    "
                                    title="Copy Link" 
                                    class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-[#0A2540] hover:border-slate-300 transition shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Featured Hero Cover Image -->
                @if($article->image_url)
                    <figure class="rounded-2xl overflow-hidden border border-slate-200/80 bg-slate-100 shadow-sm">
                        <div class="aspect-16/10 sm:aspect-16/9 overflow-hidden bg-slate-100">
                            <img src="{{ $article->image_url }}" 
                                 alt="{{ $article->title }}" 
                                 fetchpriority="high"
                                 class="w-full h-full object-cover">
                        </div>
                        @if($article->featured_image_caption)
                            <figcaption class="p-3.5 bg-[#F8FAFC] border-t border-slate-200/80 text-xs text-slate-500 italic flex items-center gap-2 font-sans">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#635BFF] shrink-0"></span>
                                <span>{{ $article->featured_image_caption }}</span>
                            </figcaption>
                        @endif
                    </figure>
                @endif

                <!-- Audio Narration Player ("Listen to this Story") -->
                <div class="bg-gradient-to-r from-[#F8FAFC] to-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs font-sans" x-data="{
                    hasAudioFile: {{ $article->audio_url ? 'true' : 'false' }},
                    audioUrl: '{{ $article->audio_url }}',
                    isPlaying: false,
                    playbackRate: 1.0,
                    textToRead: '{{ addslashes(strip_tags($article->excerpt . ' ' . Str::limit(strip_tags($article->content), 800))) }}',
                    utterance: null,
                    togglePlay() {
                        if (this.hasAudioFile) {
                            const audio = this.$refs.audioPlayer;
                            if (this.isPlaying) {
                                audio.pause();
                                this.isPlaying = false;
                            } else {
                                audio.playbackRate = this.playbackRate;
                                audio.play();
                                this.isPlaying = true;
                            }
                        } else if ('speechSynthesis' in window) {
                            if (this.isPlaying) {
                                window.speechSynthesis.cancel();
                                this.isPlaying = false;
                            } else {
                                window.speechSynthesis.cancel();
                                this.utterance = new SpeechSynthesisUtterance(this.textToRead);
                                this.utterance.rate = this.playbackRate;
                                this.utterance.onend = () => { this.isPlaying = false; };
                                this.utterance.onerror = () => { this.isPlaying = false; };
                                window.speechSynthesis.speak(this.utterance);
                                this.isPlaying = true;
                            }
                        } else {
                            alert('Voice narration is not supported on this device/browser.');
                        }
                    },
                    setRate(rate) {
                        this.playbackRate = rate;
                        if (this.hasAudioFile && this.$refs.audioPlayer) {
                            this.$refs.audioPlayer.playbackRate = rate;
                        } else if (this.isPlaying && 'speechSynthesis' in window) {
                            this.togglePlay();
                            this.togglePlay();
                        }
                    }
                }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-[#635BFF]/10 border border-[#635BFF]/20 text-[#635BFF] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" :class="isPlaying ? 'animate-pulse text-[#635BFF]' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.22-1.63.622-2.326.234-.847 1.058-1.354 1.938-1.354h2.24z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-[#0A2540]">Listen to this Story</span>
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-600 font-semibold shadow-xs" 
                                          x-text="hasAudioFile ? 'HQ Master Track' : 'Voice Synthesis'"></span>
                                </div>
                                <span class="text-[11px] text-slate-500 block mt-0.5">
                                    {{ $article->audio_duration ?? ($article->reading_time_minutes . ' min audio duration') }} &middot; Narrated by AQ NEWSWIRE Audio
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <!-- Speed Control -->
                            <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 text-[11px] font-bold shadow-xs">
                                <button type="button" @click="setRate(1.0)" :class="playbackRate === 1.0 ? 'bg-[#635BFF] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition">1x</button>
                                <button type="button" @click="setRate(1.25)" :class="playbackRate === 1.25 ? 'bg-[#635BFF] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition">1.25x</button>
                                <button type="button" @click="setRate(1.5)" :class="playbackRate === 1.5 ? 'bg-[#635BFF] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition">1.5x</button>
                            </div>

                            <!-- Play/Pause Button -->
                            <button type="button" 
                                    @click="togglePlay()" 
                                    class="px-4 py-2 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition flex items-center gap-2 shrink-0 shadow-md">
                                <span x-text="isPlaying ? '⏸ Pause' : '▶ Play Story'"></span>
                            </button>
                        </div>
                    </div>

                    @if($article->audio_url)
                        <audio x-ref="audioPlayer" src="{{ $article->audio_url }}" @ended="isPlaying = false" class="hidden"></audio>
                    @endif
                </div>

                <!-- Sponsored Notice (If Applicable) -->
                @if($article->sponsored_by)
                    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-r-2xl p-4 text-xs text-amber-900 font-sans">
                        <span class="font-bold uppercase tracking-wider block mb-0.5">Paid Program</span>
                        This story is presented in partnership with <strong>{{ $article->sponsored_by }}</strong>.
                    </div>
                @endif

                <!-- 3. Key Takeaways / Executive Brief Box -->
                <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs font-sans">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2 h-2 rounded-full bg-[#635BFF]"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#0A2540]">Executive Brief &amp; Key Takeaways</span>
                    </div>
                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed">
                        <li class="flex items-start gap-2.5">
                            <span class="text-[#635BFF] font-bold mt-0.5 text-sm">✓</span>
                            <span>In-depth investigative reporting verified by the <strong>{{ $article->category->name }}</strong> desk.</span>
                        </li>
                        @if($article->subtitle)
                        <li class="flex items-start gap-2.5">
                            <span class="text-[#635BFF] font-bold mt-0.5 text-sm">✓</span>
                            <span>{{ $article->subtitle }}</span>
                        </li>
                        @endif
                        <li class="flex items-start gap-2.5">
                            <span class="text-[#635BFF] font-bold mt-0.5 text-sm">✓</span>
                            <span>Comprehensive background, historical context, and geopolitical significance analyzed for international leaders.</span>
                        </li>
                    </ul>
                </div>

                <!-- 4. High-Contrast Legible Article Body -->
                <div class="w-full">
                    <div class="article-content font-serif text-slate-800 leading-relaxed transition-all duration-150"
                         :class="{
                             'text-base leading-[1.75]': fontSize === 'sm',
                             'text-lg sm:text-xl leading-[1.85]': fontSize === 'md',
                             'text-xl sm:text-2xl leading-[1.9]': fontSize === 'lg'
                         }">
                        {!! $article->content !!}
                    </div>
                </div>

                <!-- 5. Filed Under Tags -->
                @if($article->tags->isNotEmpty())
                    <div class="pt-8 border-t border-slate-200 flex flex-wrap items-center gap-2 font-sans">
                        <span class="text-xs uppercase font-extrabold tracking-widest text-slate-500 mr-2">Filed Under:</span>
                        @foreach($article->tags as $tag)
                            <a href="{{ route('search.index', ['q' => $tag->name]) }}" 
                               class="text-xs bg-[#F8FAFC] border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-700 hover:text-[#0A2540] px-3.5 py-1.5 rounded-xl font-medium transition shadow-xs">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- 6. Author Bio Card -->
                <div class="bg-[#F8FAFC] border border-slate-200/80 p-6 sm:p-8 rounded-2xl flex flex-col sm:flex-row gap-5 items-start shadow-xs font-sans">
                    <img src="{{ $article->author->avatar_url }}" 
                         alt="{{ $article->author->name }}" 
                         class="w-16 h-16 rounded-full object-cover ring-2 ring-slate-200 shrink-0">
                    <div class="flex-1 space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h4 class="font-bold text-[#0A2540] text-base sm:text-lg">
                                    {{ $article->author->name }}
                                </h4>
                                <p class="text-xs text-slate-500 font-medium">{{ $article->author->title ?? 'Staff Correspondent' }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('authors.show', $article->author->slug) }}" 
                                   class="text-xs uppercase font-bold tracking-wider text-[#635BFF] hover:underline transition">
                                    View Portfolio &rarr;
                                </a>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-serif">
                            {{ $article->author->bio ?? 'Senior journalist covering financial markets, macroeconomic trends, and institutional capital movements for AQ NEWSWIRE.' }}
                        </p>
                    </div>
                </div>

                <!-- 7. Previous and Next Story In Same Category Navigation -->
                @php
                    $prevArticle = \App\Models\Article::published()->where('category_id', $article->category_id)->where('id', '<', $article->id)->orderByDesc('id')->first();
                    $nextArticle = \App\Models\Article::published()->where('category_id', $article->category_id)->where('id', '>', $article->id)->orderBy('id')->first();
                @endphp
                @if($prevArticle || $nextArticle)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-slate-200 font-sans">
                        @if($prevArticle)
                            <a href="{{ route('articles.show', $prevArticle->slug) }}" class="p-4 rounded-2xl border border-slate-200/90 bg-[#F8FAFC] hover:bg-slate-100 hover:border-slate-300 transition group flex flex-col justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-[#635BFF] transition flex items-center gap-1">
                                    <span>&larr;</span> Previous in {{ $article->category->name }}
                                </span>
                                <h5 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition mt-2 line-clamp-2 leading-snug">
                                    {{ $prevArticle->title }}
                                </h5>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextArticle)
                            <a href="{{ route('articles.show', $nextArticle->slug) }}" class="p-4 rounded-2xl border border-slate-200/90 bg-[#F8FAFC] hover:bg-slate-100 hover:border-slate-300 transition group flex flex-col justify-between text-right sm:items-end">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-[#635BFF] transition flex items-center gap-1 self-end">
                                    Next in {{ $article->category->name }} <span>&rarr;</span>
                                </span>
                                <h5 class="text-xs sm:text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition mt-2 line-clamp-2 leading-snug">
                                    {{ $nextArticle->title }}
                                </h5>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- 8. Executive Discussion / Comments Section -->
                <section class="pt-8 border-t border-slate-200 space-y-6 font-sans" id="comments">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <h3 class="font-headline text-2xl font-bold text-[#0A2540]">
                            Executive Discussion ({{ $article->approvedComments->count() }})
                        </h3>
                        <span class="text-xs text-slate-500">Moderated Professional Community</span>
                    </div>

                    <!-- Comment Submission Form -->
                    @if(\App\Models\Setting::get('enable_comments', true))
                        @auth
                            <form action="{{ route('comment.store', $article) }}" method="POST" class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-3">
                                @csrf
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#0A2540]">Add Professional Perspective</label>
                                <textarea name="content" rows="3" required placeholder="Share thoughtful commentary, critique, or inquiry..."
                                          class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:ring-1 focus:ring-[#635BFF] transition"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-5 py-2.5 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm">
                                        Post Commentary
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-6 text-center space-y-3">
                                <p class="text-xs sm:text-sm text-slate-600">Please sign in to your executive account to participate in this discussion.</p>
                                <a href="{{ route('login') }}" class="inline-block px-5 py-2 bg-[#635BFF] hover:bg-[#5349DF] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm">
                                    Sign In to Comment
                                </a>
                            </div>
                        @endauth
                    @else
                        <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-4 text-center">
                            <p class="text-xs text-slate-500 font-medium">Comments on this story are currently closed by site administration.</p>
                        </div>
                    @endif

                    <!-- Approved Comments Feed -->
                    <div class="space-y-4">
                        @forelse($article->approvedComments as $comment)
                            <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3" id="comment-{{ $comment->id }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200">
                                        <div>
                                            <h4 class="font-bold text-xs text-[#0A2540]">{{ $comment->user->name }}</h4>
                                            <span class="text-[10px] text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 text-xs">
                                        <form action="{{ route('comment.like', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-slate-500 hover:text-[#635BFF] font-semibold flex items-center gap-1 transition">
                                                <span>▲</span> <span>{{ $comment->likes_count }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('comment.report', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-slate-400 hover:text-slate-600 text-[11px] transition" title="Report abuse">
                                                Report
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed pl-11">{{ $comment->content }}</p>

                                <!-- Replies Thread -->
                                @if($comment->replies->isNotEmpty())
                                    <div class="mt-4 pl-11 space-y-3 pt-3 border-t border-slate-200">
                                        @foreach($comment->replies as $reply)
                                             <div class="bg-white border border-slate-200 p-3.5 rounded-xl text-xs space-y-1.5 shadow-xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-[#0A2540]">{{ $reply->user->name }}</span>
                                                    <span class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-slate-700 leading-relaxed">{{ $reply->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 text-center py-6">Be the first to share an executive insight on this story.</p>
                        @endforelse
                    </div>
                </section>

                <!-- 9. Recommended Reading in Same Category -->
                @if($relatedArticles->isNotEmpty())
                    <section class="pt-8 border-t border-slate-200 space-y-6 font-sans">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <h3 class="text-xs font-black uppercase tracking-widest text-[#0A2540] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#635BFF]"></span>
                                <span>Recommended Reading in {{ $article->category->name }}</span>
                            </h3>
                            <a href="{{ route('categories.show', $article->category->slug) }}" class="text-xs text-[#635BFF] hover:underline font-semibold">
                                View Channel &rarr;
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($relatedArticles as $rel)
                                <article class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden group hover:border-slate-300 hover:shadow-md transition flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="aspect-16/10 bg-slate-100 overflow-hidden relative">
                                            <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-white/95 text-[#0A2540] border border-slate-200 shadow-xs">
                                                {{ $rel->category->name }}
                                            </span>
                                        </div>
                                        <div class="p-5 space-y-2">
                                            <h4 class="font-headline text-base sm:text-lg font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                                <a href="{{ route('articles.show', $rel->slug) }}">{{ $rel->title }}</a>
                                            </h4>
                                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-serif">{{ $rel->excerpt }}</p>
                                        </div>
                                    </div>
                                    <div class="p-5 pt-0 border-t border-slate-100 mt-3 text-[11px] text-slate-500 flex items-center justify-between">
                                        <span>{{ $rel->author->name }}</span>
                                        <span>{{ $rel->reading_time_minutes }}m read</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

            </main>

            <!-- Secondary Right Rail Sidebar (4 Columns, Sticky on Desktop) -->
            <aside class="lg:col-span-4 space-y-8 lg:sticky lg:top-24 font-sans">
                
                <!-- 1. Integrated Light Advertisement Card -->
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

                <!-- 2. Trending Right Now (Ranked 01 to 05) -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
                        <h4 class="text-xs font-black uppercase tracking-widest text-[#0A2540] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#635BFF]"></span>
                            <span>Trending Right Now</span>
                        </h4>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Live Pulse</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($trendingArticles as $idx => $tArt)
                            <div class="flex items-start gap-3.5 pb-4 border-b border-slate-100 last:border-b-0 last:pb-0 group">
                                <span class="font-sans text-xl font-black text-[#635BFF] shrink-0 leading-none mt-0.5">
                                    0{{ $idx + 1 }}
                                </span>
                                <div class="space-y-1 min-w-0">
                                    <h5 class="font-headline text-sm font-bold text-[#0A2540] group-hover:text-[#635BFF] transition leading-snug">
                                        <a href="{{ route('articles.show', $tArt->slug) }}">{{ $tArt->title }}</a>
                                    </h5>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                        <span class="text-slate-600 font-medium">{{ $tArt->category->name }}</span>
                                        <span>&middot;</span>
                                        <span>{{ $tArt->reading_time_minutes }}m read</span>
                                        @if($tArt->view_count > 0)
                                            <span>&middot;</span>
                                            <span>{{ number_format($tArt->view_count) }} views</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Channel Intelligence Briefing Widget -->
                <div class="bg-[#F8FAFC] border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#0A2540]">
                        <span class="w-2 h-2 rounded-full bg-[#635BFF]"></span>
                        <span>Channel Briefing</span>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed font-serif">
                        The {{ $article->category->name }} desk provides comprehensive intelligence on global capital flows, institutional strategy, and regulatory shifts.
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
                        Stay ahead of central bank decisions, currency movements, and volatility with real-time editorial updates.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('rankings.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#635BFF] hover:bg-[#5349DF] text-white rounded-xl text-xs font-semibold transition shadow-sm">
                            <span>Explore AQ NEWSWIRE Rankings</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- 5. Newsletter Quick Signup Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#0A2540]">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>The Daily Briefing</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Get our essential morning dispatch delivered directly to your inbox before the opening bell.
                    </p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="email" name="email" required placeholder="executive@enterprise.com" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] focus:bg-white transition">
                        <button type="submit" class="w-full py-2 bg-[#0A2540] hover:bg-slate-900 text-white rounded-xl text-xs font-bold tracking-wider uppercase transition shadow-sm">
                            Subscribe Free
                        </button>
                    </form>
                </div>

            </aside>

        </div>
    </div>

</div>
@endsection
