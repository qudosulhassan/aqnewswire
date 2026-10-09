@extends('layouts.app')

@section('title', 'Search Results: ' . ($query ? $query : 'Global Media Search') . ' — AQ NEWSWIRE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Search Form Bar with Live Suggestions -->
    <div class="bg-white border border-stone-200 rounded p-6 mb-8 shadow-xs relative" x-data="{
        q: '{{ addslashes($query) }}',
        suggestions: [],
        isOpen: false,
        fetchSuggestions() {
            if (this.q.trim().length >= 2) {
                fetch('{{ route('search.autocomplete') }}?q=' + encodeURIComponent(this.q))
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data.suggestions || [];
                        this.isOpen = this.suggestions.length > 0;
                    });
            } else {
                this.suggestions = [];
                this.isOpen = false;
            }
        }
    }">
        <form method="GET" action="{{ route('search.index') }}" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-3 relative">
                <div class="flex-1 relative">
                    <input type="text" name="q" x-model="q" @input.debounce.300ms="fetchSuggestions()" @click.away="isOpen = false" autocomplete="off"
                           placeholder="Search articles, videos, podcasts, authors, topics..." 
                           class="w-full bg-stone-50 border border-stone-200 rounded px-4 py-3 text-sm focus:outline-none focus:border-stone-900 transition">
                    
                    <!-- Autocomplete Dropdown -->
                    <div x-show="isOpen" x-cloak class="absolute left-0 right-0 top-full mt-1 bg-white border border-stone-200 rounded shadow-lg z-50 divide-y divide-stone-100 max-h-72 overflow-y-auto">
                        <template x-for="item in suggestions" :key="item.url">
                            <a :href="item.url" class="block px-4 py-2.5 hover:bg-stone-50 transition flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-stone-900 block" x-text="item.title"></span>
                                    <span class="text-[10px] text-stone-400 uppercase font-mono" x-text="item.type"></span>
                                </div>
                                <span class="text-stone-400 text-xs">&rarr;</span>
                            </a>
                        </template>
                    </div>
                </div>

                <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white font-bold text-xs uppercase tracking-wider px-8 py-3 rounded transition shrink-0">
                    Search AQ NEWSWIRE
                </button>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-stone-100 text-xs">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1">Channel</label>
                    <select name="category" class="w-full bg-stone-50 border border-stone-200 rounded px-2.5 py-1.5 focus:outline-none focus:border-stone-900">
                        <option value="">All Channels</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1">Content Type</label>
                    <select name="type" class="w-full bg-stone-50 border border-stone-200 rounded px-2.5 py-1.5 focus:outline-none focus:border-stone-900">
                        <option value="all" {{ $contentType === 'all' ? 'selected' : '' }}>All Formats</option>
                        <option value="articles" {{ $contentType === 'articles' ? 'selected' : '' }}>Articles Only</option>
                        <option value="videos" {{ $contentType === 'videos' ? 'selected' : '' }}>Videos Only</option>
                        <option value="podcasts" {{ $contentType === 'podcasts' ? 'selected' : '' }}>Podcasts Only</option>
                        <option value="rankings" {{ $contentType === 'rankings' ? 'selected' : '' }}>Lists & Rankings</option>
                        <option value="authors" {{ $contentType === 'authors' ? 'selected' : '' }}>Journalists & Authors</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1">Published Within</label>
                    <select name="date" class="w-full bg-stone-50 border border-stone-200 rounded px-2.5 py-1.5 focus:outline-none focus:border-stone-900">
                        <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="24h" {{ $dateRange === '24h' ? 'selected' : '' }}>Past 24 Hours</option>
                        <option value="7d" {{ $dateRange === '7d' ? 'selected' : '' }}>Past 7 Days</option>
                        <option value="30d" {{ $dateRange === '30d' ? 'selected' : '' }}>Past 30 Days</option>
                        <option value="1y" {{ $dateRange === '1y' ? 'selected' : '' }}>Past Year</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1">Sort Order</label>
                    <select name="sort" class="w-full bg-stone-50 border border-stone-200 rounded px-2.5 py-1.5 focus:outline-none focus:border-stone-900">
                        <option value="latest" {{ $sortBy === 'latest' ? 'selected' : '' }}>Most Recent</option>
                        <option value="views" {{ $sortBy === 'views' ? 'selected' : '' }}>Most Viewed</option>
                        <option value="trending" {{ $sortBy === 'trending' ? 'selected' : '' }}>Trending Score</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Related Topics Bar (if found) -->
    @if($topics->isNotEmpty())
        <div class="mb-6 flex items-center space-x-2 overflow-x-auto pb-2">
            <span class="text-xs uppercase font-bold text-stone-400 shrink-0">Matching Topics:</span>
            @foreach($topics as $topic)
                <a href="{{ route('search.index', ['q' => $topic->name]) }}" 
                   class="px-3 py-1 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-full shrink-0 transition">
                    #{{ $topic->name }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Results Heading -->
    <div class="border-b-2 border-stone-900 pb-3 mb-8 flex items-center justify-between">
        <h1 class="text-sm font-bold uppercase tracking-widest text-stone-900">
            @if($query)
                Search Results for "{{ $query }}"
            @else
                Please enter a keyword to search articles and media
            @endif
        </h1>
        @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <span class="text-xs text-stone-500">{{ $articles->total() }} Articles</span>
        @endif
    </div>

    <!-- Multi-Entity Results: Authors & Multimedia Cards -->
    @if($authors->isNotEmpty())
        <div class="mb-8">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-500 mb-3">Matching Journalists & Contributors</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($authors as $writer)
                    <a href="{{ route('authors.show', $writer->slug) }}" class="bg-white border border-stone-200 p-4 rounded hover:border-stone-900 transition flex items-center space-x-3">
                        <img src="{{ $writer->avatar_url }}" alt="{{ $writer->name }}" class="w-12 h-12 rounded-full object-cover">
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-xs text-stone-900 truncate">{{ $writer->name }}</h4>
                            <p class="text-[10px] text-stone-500 truncate">{{ $writer->title ?? 'Staff Correspondent' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($videos->isNotEmpty())
        <div class="mb-8">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-500 mb-3">Matching Videos</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($videos as $video)
                    <a href="{{ route('videos.show', $video->slug) }}" class="bg-white border border-stone-200 rounded overflow-hidden group">
                        <div class="aspect-16/9 bg-stone-100 relative">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition"></div>
                        </div>
                        <div class="p-3">
                            <h4 class="font-bold text-xs text-stone-900 line-clamp-1 group-hover:text-red-600 transition">{{ $video->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Articles List -->
    <div class="space-y-6">
        @forelse($articles as $art)
            <article class="flex flex-col sm:flex-row gap-5 pb-6 border-b border-stone-200 group">
                <div class="sm:w-1/4 aspect-16/10 rounded-xs overflow-hidden bg-stone-100 shrink-0">
                    <img src="{{ $art->image_url }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <div class="sm:w-3/4 flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] uppercase font-bold text-red-600 block mb-1">
                            {{ $art->category->name }}
                        </span>
                        <h2 class="font-headline text-xl font-bold text-stone-900 group-hover:text-red-700 transition mb-2">
                            <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                        </h2>
                        <p class="text-xs text-stone-600 line-clamp-2 mb-2">{{ $art->excerpt }}</p>
                    </div>
                    <div class="text-[11px] text-stone-500">
                        {{ $art->author->name }} • {{ $art->published_at ? $art->published_at->format('M j, Y') : '' }} • {{ $art->reading_time_minutes }}m read
                    </div>
                </div>
            </article>
        @empty
            @if($query && $videos->isEmpty() && $authors->isEmpty())
                <div class="text-center py-16 bg-white border border-stone-200 rounded">
                    <p class="text-stone-500 text-sm">No stories or media found matching "{{ $query }}". Try adjusting your search filters.</p>
                </div>
            @endif
        @endforelse
    </div>

    @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection
