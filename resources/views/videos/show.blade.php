@extends('layouts.app')

@section('title', $video->title . ' — AQ NEWSWIRE Video')

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "VideoObject",
  "name": "{{ addslashes($video->title) }}",
  "description": "{{ addslashes($video->description ?? $video->title) }}",
  "thumbnailUrl": ["{{ $video->thumbnail_url }}"],
  "uploadDate": "{{ $video->published_at ? $video->published_at->toIso8601String() : now()->toIso8601String() }}",
  "embedUrl": "{{ $video->video_url }}"
}
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Player & Details (8 Cols) -->
        <div class="lg:col-span-8">
            <div class="aspect-16/9 bg-stone-950 rounded overflow-hidden shadow-lg mb-6">
                <iframe class="w-full h-full" src="{{ str_contains($video->video_url, 'youtube') ? str_replace('watch?v=', 'embed/', $video->video_url) : $video->video_url }}" 
                        title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div class="border-b border-stone-200 pb-4 mb-6">
                <span class="text-xs font-bold uppercase tracking-widest text-red-600 block mb-1">
                    {{ $video->category->name ?? 'Executive Broadcast' }}
                </span>
                <h1 class="font-headline text-2xl sm:text-4xl font-bold text-stone-950 leading-tight mb-3">
                    {{ $video->title }}
                </h1>
                <div class="text-xs text-stone-500 flex items-center space-x-3">
                    <span>{{ number_format($video->view_count) }} views</span>
                    <span>•</span>
                    <span>Published {{ $video->published_at ? $video->published_at->format('M j, Y') : 'Recently' }}</span>
                </div>
            </div>

            <div class="text-stone-700 text-sm leading-relaxed mb-8">
                <p>{{ $video->description }}</p>
            </div>
        </div>

        <!-- Related Videos Sidebar (4 Cols) -->
        <aside class="lg:col-span-4 space-y-6">
            <h3 class="text-xs font-bold uppercase tracking-widest text-stone-900 pb-2 border-b border-stone-200">
                Recommended Broadcasts
            </h3>

            <div class="space-y-4">
                @foreach($relatedVideos as $rel)
                    <div class="flex space-x-3 group">
                        <a href="{{ route('videos.show', $rel->slug) }}" class="w-28 aspect-16/9 bg-stone-900 rounded overflow-hidden shrink-0 relative">
                            <img src="{{ $rel->thumbnail_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=300&q=80' }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition">
                        </a>
                        <div>
                            <h4 class="font-headline text-xs font-bold text-stone-900 group-hover:text-red-700 line-clamp-2">
                                <a href="{{ route('videos.show', $rel->slug) }}">{{ $rel->title }}</a>
                            </h4>
                            <span class="text-[10px] text-stone-400 mt-1 block">{{ $rel->published_at ? $rel->published_at->diffForHumans() : '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>
    </div>

</div>
@endsection
