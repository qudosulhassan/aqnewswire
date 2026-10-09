@extends('layouts.app')

@section('title', 'AQ NEWSWIRE Video — Executive Interviews, Market Briefings & Documentaries')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="border-b-2 border-stone-900 pb-4 mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-red-600 block mb-1">Broadcasting</span>
        <h1 class="font-headline text-3xl sm:text-5xl font-extrabold text-stone-950">AQ NEWSWIRE Video Network</h1>
        <p class="text-stone-600 text-sm mt-1">Daily market opening bells, CEO boardroom conversations, and deep-dive technology investigations.</p>
    </div>

    <!-- Featured Lead Video -->
    @if(isset($featuredVideo) && $featuredVideo)
        <div class="mb-12 bg-stone-950 text-white rounded overflow-hidden shadow-md">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-7 aspect-16/9 bg-stone-900">
                    <iframe class="w-full h-full" src="{{ str_contains($featuredVideo->video_url, 'youtube') ? str_replace('watch?v=', 'embed/', $featuredVideo->video_url) : $featuredVideo->video_url }}" 
                            title="{{ $featuredVideo->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="lg:col-span-5 p-6 sm:p-8">
                    <span class="bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded inline-block mb-3">
                        Featured Broadcast
                    </span>
                    <h2 class="font-headline text-2xl sm:text-3xl font-bold mb-3 leading-snug">
                        <a href="{{ route('videos.show', $featuredVideo->slug) }}" class="hover:text-red-400 transition">
                            {{ $featuredVideo->title }}
                        </a>
                    </h2>
                    <p class="text-stone-400 text-xs sm:text-sm line-clamp-3 mb-4 leading-relaxed">
                        {{ $featuredVideo->description }}
                    </p>
                    <div class="text-[11px] text-stone-500">
                        {{ number_format($featuredVideo->view_count) }} views • Published {{ $featuredVideo->published_at ? $featuredVideo->published_at->format('M j, Y') : '' }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Videos Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @forelse($videos as $vid)
            <div class="bg-white border border-stone-200 rounded overflow-hidden group shadow-xs">
                <a href="{{ route('videos.show', $vid->slug) }}" class="block aspect-16/9 bg-stone-900 relative overflow-hidden">
                    <img src="{{ $vid->thumbnail_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80' }}" 
                         alt="{{ $vid->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                        <span class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 transition">▶</span>
                    </div>
                </a>
                <div class="p-5">
                    <span class="text-[10px] uppercase font-bold text-red-600 block mb-1">{{ $vid->category->name ?? 'Special Report' }}</span>
                    <h3 class="font-headline text-base font-bold text-stone-900 group-hover:text-red-700 line-clamp-2 leading-snug">
                        <a href="{{ route('videos.show', $vid->slug) }}">{{ $vid->title }}</a>
                    </h3>
                    <p class="text-xs text-stone-500 line-clamp-2 mt-2">{{ $vid->description }}</p>
                    <div class="text-[10px] text-stone-400 mt-3 pt-3 border-t border-stone-100 flex items-center justify-between">
                        <span>{{ number_format($vid->view_count) }} views</span>
                        <span>{{ $vid->published_at ? $vid->published_at->diffForHumans() : '' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-stone-500">No additional video broadcasts available.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $videos->links() }}
    </div>

</div>
@endsection
