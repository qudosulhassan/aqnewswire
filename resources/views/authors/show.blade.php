@extends('layouts.app')

@section('title', $author->name . ' — Journalist Profile & Archive | AQ NEWSWIRE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Author Profile Header Card -->
    <div class="bg-white border border-stone-200 rounded p-8 sm:p-10 mb-12 shadow-xs flex flex-col md:flex-row gap-8 items-start">
        <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="w-28 h-28 sm:w-36 sm:h-36 rounded-full object-cover border-4 border-stone-100 shrink-0">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="font-headline text-3xl sm:text-4xl font-bold text-stone-950">{{ $author->name }}</h1>
                @if($author->is_verified)
                    <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-200">
                        Verified Contributor
                    </span>
                @endif
            </div>

            <p class="text-sm font-semibold text-stone-600 mb-4">{{ $author->title ?? 'Editorial Staff' }}</p>

            <p class="text-sm text-stone-700 max-w-3xl leading-relaxed mb-6">
                {{ $author->bio ?? 'Journalist and analyst at AQ NEWSWIRE.' }}
            </p>

            <div class="flex items-center space-x-4 text-xs font-semibold text-stone-600">
                @if($author->twitter)
                    <a href="https://twitter.com/{{ ltrim($author->twitter, '@') }}" target="_blank" class="hover:text-stone-900 transition flex items-center">
                        <span class="mr-1">𝕏</span> {{ $author->twitter }}
                    </a>
                @endif
                @if($author->linkedin)
                    <a href="https://linkedin.com/in/{{ $author->linkedin }}" target="_blank" class="hover:text-stone-900 transition flex items-center">
                        <span class="mr-1">in</span> {{ $author->name }}
                    </a>
                @endif
                @if($author->website)
                    <a href="{{ $author->website }}" target="_blank" class="hover:text-stone-900 transition flex items-center">
                        <span class="mr-1">🌐</span> Website
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Articles Archive by this Author -->
    <div class="border-b-2 border-stone-900 pb-3 mb-8">
        <h2 class="text-sm font-bold uppercase tracking-widest text-stone-900">
            Articles by {{ $author->name }} ({{ $articles->total() }})
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $art)
            <article class="bg-white border border-stone-200 rounded overflow-hidden group flex flex-col justify-between shadow-xs">
                <div>
                    <div class="aspect-16/10 bg-stone-100 overflow-hidden">
                        <img src="{{ $art->image_url }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-red-600 block mb-1">
                            {{ $art->category->name }}
                        </span>
                        <h3 class="font-headline text-lg font-bold text-stone-900 group-hover:text-red-700 transition leading-snug mb-2">
                            <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                        </h3>
                        <p class="text-xs text-stone-600 line-clamp-2">{{ $art->excerpt }}</p>
                    </div>
                </div>
                <div class="p-5 pt-0 text-[11px] text-stone-400">
                    {{ $art->published_at ? $art->published_at->format('M j, Y') : '' }} • {{ $art->reading_time_minutes }}m read
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-16 bg-white border border-stone-200 rounded">
                <p class="text-stone-500 text-sm">No articles published yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>

</div>
@endsection
