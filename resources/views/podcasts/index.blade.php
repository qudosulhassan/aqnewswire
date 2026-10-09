@extends('layouts.app')

@section('title', 'AQ NEWSWIRE Podcasts — Executive Audio Intelligence & Deep Dives')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="border-b-2 border-stone-900 pb-4 mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-red-600 block mb-1">Audio Programming</span>
        <h1 class="font-headline text-3xl sm:text-5xl font-extrabold text-stone-950">AQ NEWSWIRE Podcasts</h1>
        <p class="text-stone-600 text-sm mt-1">Insightful conversations with chief executives, portfolio managers, and leading researchers.</p>
    </div>

    <!-- Shows List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-14">
        @forelse($podcasts as $pod)
            <div class="bg-white border border-stone-200 rounded p-6 shadow-xs flex space-x-5 group">
                <img src="{{ $pod->cover_image ?? 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=300&q=80' }}" 
                     alt="{{ $pod->title }}" class="w-24 h-24 rounded object-cover border border-stone-200 shrink-0">
                <div class="flex-1">
                    <h2 class="font-headline text-lg font-bold text-stone-900 group-hover:text-red-700 leading-snug">
                        <a href="{{ route('podcasts.show', $pod->slug) }}">{{ $pod->title }}</a>
                    </h2>
                    <p class="text-xs text-stone-500 mt-1 line-clamp-2">{{ $pod->description }}</p>
                    <span class="text-[11px] font-semibold text-stone-400 mt-2 block">Host: {{ $pod->host_name }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-stone-500">No podcast channels registered.</div>
        @endforelse
    </div>

    <!-- Recent Episodes Feed -->
    <div class="border-b-2 border-stone-900 pb-2 mb-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-stone-900">Latest Audio Episodes</h3>
    </div>

    <div class="space-y-4">
        @forelse($latestEpisodes as $ep)
            <div class="bg-white border border-stone-200 rounded p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-red-600 transition">
                <div>
                    <span class="text-[10px] uppercase font-bold text-red-600 block mb-0.5">{{ $ep->podcast->title }}</span>
                    <h4 class="font-headline text-base font-bold text-stone-900 hover:text-red-700">
                        <a href="{{ route('podcasts.episode', [$ep->podcast->slug, $ep->slug]) }}">{{ $ep->title }}</a>
                    </h4>
                    <span class="text-xs text-stone-500 mt-1 block">Episode {{ $ep->episode_number }} • {{ $ep->duration ?? '35 mins' }}</span>
                </div>
                <div class="shrink-0">
                    <audio controls class="h-8 max-w-xs">
                        <source src="{{ $ep->audio_url }}" type="audio/mpeg">
                    </audio>
                </div>
            </div>
        @empty
            <p class="text-xs text-stone-500 py-6 text-center">No episodes published yet.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $latestEpisodes->links() }}
    </div>

</div>
@endsection
