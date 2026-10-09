@extends('layouts.app')

@section('title', $podcast->title . ' — AQ NEWSWIRE Podcasts')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Podcast Header -->
    <div class="bg-stone-950 text-white rounded p-8 sm:p-10 mb-10 flex flex-col sm:flex-row gap-8 items-center">
        <img src="{{ $podcast->cover_image ?? 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=400&q=80' }}" 
             alt="{{ $podcast->title }}" class="w-36 h-36 rounded-lg object-cover border-2 border-stone-800 shrink-0">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-red-500 block mb-1">AQ NEWSWIRE Original Audio Show</span>
            <h1 class="font-headline text-3xl sm:text-4xl font-bold mb-2">{{ $podcast->title }}</h1>
            <p class="text-stone-400 text-xs sm:text-sm leading-relaxed max-w-2xl mb-3">{{ $podcast->description }}</p>
            <p class="text-xs text-stone-300 font-semibold">Hosted by {{ $podcast->host_name }}</p>
        </div>
    </div>

    <!-- Episodes List -->
    <div class="border-b-2 border-stone-900 pb-2 mb-6">
        <h2 class="text-xs font-bold uppercase tracking-widest text-stone-900">All Episodes ({{ $podcast->episodes->count() }})</h2>
    </div>

    <div class="space-y-4">
        @forelse($podcast->episodes as $ep)
            <div class="bg-white border border-stone-200 rounded p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-red-600 block mb-1">Episode {{ $ep->episode_number }}</span>
                    <h3 class="font-headline text-lg font-bold text-stone-900 hover:text-red-700">
                        <a href="{{ route('podcasts.episode', [$podcast->slug, $ep->slug]) }}">{{ $ep->title }}</a>
                    </h3>
                    <p class="text-xs text-stone-500 line-clamp-2 mt-1">{{ $ep->description }}</p>
                    <span class="text-[11px] text-stone-400 mt-2 block">{{ $ep->duration }} • Published {{ $ep->published_at ? $ep->published_at->format('M j, Y') : '' }}</span>
                </div>
                <div class="shrink-0">
                    <audio controls class="h-8 max-w-xs">
                        <source src="{{ $ep->audio_url }}" type="audio/mpeg">
                    </audio>
                </div>
            </div>
        @empty
            <p class="text-xs text-stone-500 py-8 text-center">No episodes uploaded yet for this show.</p>
        @endforelse
    </div>

</div>
@endsection
