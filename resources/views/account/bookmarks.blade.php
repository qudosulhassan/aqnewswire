@extends('layouts.app')

@section('title', 'My Saved Stories — AQ NEWSWIRE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-stone-200 mb-8 gap-4">
        <div>
            <a href="{{ route('account.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Dashboard</a>
            <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">My Saved Stories ({{ $bookmarks->total() }})</h1>
        </div>

        <form method="GET" action="{{ route('account.bookmarks') }}" class="flex items-center space-x-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search saved stories..." 
                   class="px-3 py-1.5 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
            <button type="submit" class="bg-stone-900 text-white font-bold text-xs uppercase px-3 py-1.5 rounded">Search</button>
        </form>
    </div>

    <!-- Bookmarks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($bookmarks as $bm)
            <div class="bg-white border border-stone-200 rounded overflow-hidden shadow-xs flex flex-col justify-between group">
                <div>
                    <div class="aspect-16/10 bg-stone-100 overflow-hidden relative">
                        <img src="{{ $bm->article->image_url }}" alt="{{ $bm->article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] uppercase font-bold text-red-600 block mb-1">{{ $bm->article->category->name }}</span>
                        <h3 class="font-headline text-lg font-bold text-stone-900 group-hover:text-red-700 leading-snug mb-2">
                            <a href="{{ route('articles.show', $bm->article->slug) }}">{{ $bm->article->title }}</a>
                        </h3>
                        <p class="text-xs text-stone-600 line-clamp-2">{{ $bm->article->excerpt }}</p>
                    </div>
                </div>

                <div class="p-5 pt-0 border-t border-stone-100 flex items-center justify-between mt-4">
                    <span class="text-[11px] text-stone-400">{{ $bm->article->author->name }} • {{ $bm->article->reading_time_minutes }}m</span>
                    <form action="{{ route('bookmark.toggle', $bm->article) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-stone-500 hover:text-red-600">Remove</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white border border-stone-200 rounded">
                <p class="text-stone-500 text-sm">No saved stories found in your bookmarks.</p>
                <a href="{{ route('home') }}" class="text-xs font-bold text-red-600 hover:underline mt-2 inline-block">Browse Top Stories &rarr;</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $bookmarks->links() }}
    </div>

</div>
@endsection
