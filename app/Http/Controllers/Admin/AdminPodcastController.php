<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminPodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::withCount('episodes')->latest()->paginate(15);
        return view('admin.podcasts.index', compact('podcasts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|url',
            'host_name' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = Auth::id();

        $podcast = Podcast::create($validated);
        AuditLog::record('podcast_created', 'Podcast', $podcast->id, "Created podcast show {$podcast->title}");

        return back()->with('success', 'Podcast show registered.');
    }

    public function storeEpisode(Request $request, Podcast $podcast)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'audio_url' => 'required|url',
            'duration' => 'nullable|string|max:50',
            'episode_number' => 'required|integer',
            'description' => 'nullable|string',
            'transcript' => 'nullable|string',
        ]);

        $validated['podcast_id'] = $podcast->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['published_at'] = now();

        $episode = PodcastEpisode::create($validated);
        AuditLog::record('podcast_episode_created', 'PodcastEpisode', $episode->id, "Published episode {$episode->title}");

        return back()->with('success', 'Podcast episode published.');
    }
}
