@extends('layouts.app')

@section('title', 'Reading History — AQ NEWSWIRE')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between pb-6 border-b border-stone-200 mb-8">
        <div>
            <a href="{{ route('account.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Dashboard</a>
            <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">Reading History</h1>
        </div>

        @if($history->isNotEmpty())
            <form action="{{ route('account.history.clear') }}" method="POST" onsubmit="return confirm('Clear your entire reading history?');">
                @csrf
                <button type="submit" class="px-3 py-1.5 border border-stone-300 text-stone-600 hover:text-red-600 hover:border-red-300 rounded text-xs font-semibold transition">
                    Clear History
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-stone-200 rounded divide-y divide-stone-200">
        @forelse($history as $item)
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-stone-50 transition">
                <div>
                    <span class="text-[10px] uppercase font-bold text-red-600 block mb-1">{{ $item->article->category->name }}</span>
                    <h2 class="font-headline text-lg font-bold text-stone-900 hover:text-red-700">
                        <a href="{{ route('articles.show', $item->article->slug) }}">{{ $item->article->title }}</a>
                    </h2>
                    <p class="text-xs text-stone-500 mt-1">
                        By {{ $item->article->author->name }} • Read {{ $item->last_read_at->diffForHumans() }}
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('articles.show', $item->article->slug) }}" class="text-xs font-bold uppercase text-stone-800 hover:text-red-600">
                        Read Again &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-stone-500 text-sm">
                No reading history recorded yet.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $history->links() }}
    </div>

</div>
@endsection
