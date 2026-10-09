@extends('layouts.app')

@section('title', $episode->title . ' — ' . $podcast->title . ' | AQ NEWSWIRE Podcasts')

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "PodcastEpisode",
  "name": "{{ addslashes($episode->title) }}",
  "description": "{{ addslashes($episode->description ?? $episode->title) }}",
  "episodeNumber": {{ $episode->episode_number ?? 1 }},
  "datePublished": "{{ $episode->published_at ? $episode->published_at->toIso8601String() : now()->toIso8601String() }}",
  "partOfSeries": {
    "@@type": "PodcastSeries",
    "name": "{{ addslashes($podcast->title) }}"
  }
}
</script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('podcasts.show', $podcast->slug) }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">
            &larr; Back to {{ $podcast->title }}
        </a>
    </div>

    <!-- Episode Player Header -->
    <div class="bg-stone-950 text-white rounded p-8 sm:p-10 mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-red-500 block mb-1">
            {{ $podcast->title }} • Episode {{ $episode->episode_number }}
        </span>
        <h1 class="font-headline text-3xl sm:text-4xl font-bold mb-4">{{ $episode->title }}</h1>
        <p class="text-xs text-stone-400 mb-6">{{ $episode->duration }} • Published {{ $episode->published_at ? $episode->published_at->format('M j, Y') : '' }}</p>

        <audio controls class="w-full">
            <source src="{{ $episode->audio_url }}" type="audio/mpeg">
            Your browser does not support audio playback.
        </audio>
    </div>

    <!-- Notes & Transcript -->
    <div class="bg-white border border-stone-200 rounded p-8 shadow-xs space-y-6">
        <div>
            <h2 class="text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-200 pb-2 mb-3">
                Episode Notes & Discussion
            </h2>
            <p class="text-sm text-stone-700 leading-relaxed">{{ $episode->description }}</p>
        </div>

        @if($episode->transcript)
            <div class="pt-6 border-t border-stone-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 mb-3">
                    Verified Audio Transcript
                </h3>
                <div class="text-xs text-stone-600 font-mono leading-relaxed bg-stone-50 p-4 rounded max-h-96 overflow-y-auto">
                    {!! nl2br(e($episode->transcript)) !!}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
