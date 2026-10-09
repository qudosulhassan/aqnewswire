@extends('layouts.app')

@section('title', 'Reader Dashboard — ' . $user->name . ' | AQ NEWSWIRE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Profile Header Card -->
    <div class="bg-white border border-stone-200 rounded p-6 sm:p-8 mb-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center space-x-5">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-stone-200">
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="font-headline text-2xl sm:text-3xl font-bold text-stone-950">{{ $user->name }}</h1>
                    <span class="bg-stone-100 text-stone-700 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <p class="text-xs text-stone-500 mt-0.5">{{ $user->email }} • Member since {{ $user->created_at->format('M Y') }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            @if($user->isContributor())
                <a href="{{ route('contributor.dashboard') }}" class="px-4 py-2 bg-stone-900 text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-black transition">
                    Contributor Studio &rarr;
                </a>
            @else
                <a href="{{ route('contributor.apply') }}" class="px-4 py-2 border border-stone-300 text-stone-700 rounded text-xs font-bold uppercase tracking-wider hover:bg-stone-50 transition">
                    Apply as Contributor
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-2 text-stone-500 hover:text-red-600 text-xs font-semibold">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-stone-200 mb-8 overflow-x-auto">
        <nav class="flex space-x-8 text-xs font-bold uppercase tracking-wider whitespace-nowrap">
            <a href="{{ route('account.dashboard') }}" class="border-b-2 border-stone-950 text-stone-950 pb-3 font-extrabold">Overview</a>
            <a href="{{ route('account.bookmarks') }}" class="text-stone-500 hover:text-stone-900 pb-3 transition">Saved Stories ({{ $savedCount }})</a>
            <a href="{{ route('account.history') }}" class="text-stone-500 hover:text-stone-900 pb-3 transition">Reading History ({{ $historyCount }})</a>
            <a href="{{ route('account.following') }}" class="text-stone-500 hover:text-stone-900 pb-3 transition">Following ({{ $followsCount }})</a>
            <a href="{{ route('account.notifications') }}" class="text-stone-500 hover:text-stone-900 pb-3 transition flex items-center">
                Notifications
                @if($unreadNotifications > 0)
                    <span class="ml-1.5 px-1.5 py-0.2 bg-red-600 text-white text-[9px] rounded-full">{{ $unreadNotifications }}</span>
                @endif
            </a>
            <a href="{{ route('account.settings') }}" class="text-stone-500 hover:text-stone-900 pb-3 transition">Account Settings</a>
        </nav>
    </div>

    <!-- Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
            <span class="text-xs uppercase font-bold text-stone-400 block mb-1">Bookmarked Articles</span>
            <span class="text-3xl font-black text-stone-900">{{ $savedCount }}</span>
            <a href="{{ route('account.bookmarks') }}" class="text-xs font-bold text-red-600 hover:underline block mt-2">View Saved List &rarr;</a>
        </div>
        <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
            <span class="text-xs uppercase font-bold text-stone-400 block mb-1">Articles Read</span>
            <span class="text-3xl font-black text-stone-900">{{ $historyCount }}</span>
            <a href="{{ route('account.history') }}" class="text-xs font-bold text-red-600 hover:underline block mt-2">View Reading History &rarr;</a>
        </div>
        <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
            <span class="text-xs uppercase font-bold text-stone-400 block mb-1">Followed Authors & Channels</span>
            <span class="text-3xl font-black text-stone-900">{{ $followsCount }}</span>
            <a href="{{ route('account.following') }}" class="text-xs font-bold text-red-600 hover:underline block mt-2">Manage Follows &rarr;</a>
        </div>
    </div>

    <!-- Two-Column Grid: Saved Articles & Reading History -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        <!-- Saved Articles Preview (6 Cols) -->
        <div class="lg:col-span-6 bg-white border border-stone-200 rounded p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-stone-200 pb-3 mb-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-stone-900">Recently Saved Stories</h3>
                <a href="{{ route('account.bookmarks') }}" class="text-xs font-bold text-red-600 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($recentBookmarks as $bm)
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100 last:border-b-0">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-stone-400">{{ $bm->article->category->name }}</span>
                            <h4 class="font-headline text-sm font-bold text-stone-900 hover:text-red-700 line-clamp-1">
                                <a href="{{ route('articles.show', $bm->article->slug) }}">{{ $bm->article->title }}</a>
                            </h4>
                            <span class="text-[10px] text-stone-500">{{ $bm->article->author->name }} • {{ $bm->article->reading_time_minutes }}m read</span>
                        </div>
                        <form action="{{ route('bookmark.toggle', $bm->article) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-stone-400 hover:text-red-600 px-2 py-1">✕</button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-stone-500 py-4 text-center">No saved articles yet. Use the "Save Story" button on any article.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Reading History Preview (6 Cols) -->
        <div class="lg:col-span-6 bg-white border border-stone-200 rounded p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-stone-200 pb-3 mb-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-stone-900">Recent Reading History</h3>
                <a href="{{ route('account.history') }}" class="text-xs font-bold text-red-600 hover:underline">Full History &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($recentHistory as $rh)
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100 last:border-b-0">
                        <div>
                            <h4 class="font-headline text-sm font-bold text-stone-900 hover:text-red-700 line-clamp-1">
                                <a href="{{ route('articles.show', $rh->article->slug) }}">{{ $rh->article->title }}</a>
                            </h4>
                            <span class="text-[10px] text-stone-400">{{ $rh->article->category->name }} • Read {{ $rh->last_read_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-stone-500 py-4 text-center">Your reading history will appear here as you read articles.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Personalized Recommended Reading Strip -->
    <div class="bg-stone-50 border border-stone-200 rounded p-6 sm:p-8">
        <h3 class="text-xs font-bold uppercase tracking-widest text-stone-900 mb-4 pb-2 border-b border-stone-200">
            Recommended For You Based on Your Reading & Follows
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($recommendations as $rec)
                <div class="group">
                    <div class="aspect-16/10 rounded-xs overflow-hidden bg-stone-200 mb-2.5">
                        <img src="{{ $rec->image_url }}" alt="{{ $rec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <span class="text-[10px] uppercase font-bold text-red-600 block">{{ $rec->category->name }}</span>
                    <h4 class="font-headline text-sm font-bold text-stone-900 group-hover:text-red-700 line-clamp-2 mt-0.5">
                        <a href="{{ route('articles.show', $rec->slug) }}">{{ $rec->title }}</a>
                    </h4>
                    <span class="text-[10px] text-stone-400 mt-1 block">{{ $rec->author->name }} • {{ $rec->reading_time_minutes }}m</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
