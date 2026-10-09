<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::with(['episodes' => fn($q) => $q->take(3)])->get();
        $latestEpisodes = PodcastEpisode::with('podcast')->latest('published_at')->paginate(10);

        return view('podcasts.index', compact('podcasts', 'latestEpisodes'));
    }

    public function show(string $slug)
    {
        $podcast = Podcast::where('slug', $slug)->with('episodes')->firstOrFail();
        return view('podcasts.show', compact('podcast'));
    }

    public function episode(string $podcastSlug, string $episodeSlug)
    {
        $podcast = Podcast::where('slug', $podcastSlug)->firstOrFail();
        $episode = $podcast->episodes()->where('slug', $episodeSlug)->firstOrFail();

        return view('podcasts.episode', compact('podcast', 'episode'));
    }
}
