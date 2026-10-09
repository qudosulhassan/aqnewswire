<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $featuredVideo = Video::published()->where('is_featured', true)->latest('published_at')->first();
        if (!$featuredVideo) {
            $featuredVideo = Video::published()->latest('published_at')->first();
        }

        $excludeIds = $featuredVideo ? [$featuredVideo->id] : [];

        $videos = Video::published()
            ->whereNotIn('id', $excludeIds)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12);

        return view('videos.index', compact('featuredVideo', 'videos'));
    }

    public function show(string $slug)
    {
        $video = Video::where('slug', $slug)->with(['category', 'author'])->firstOrFail();
        $video->incrementQuietly('view_count');

        $relatedVideos = Video::published()
            ->where('id', '!=', $video->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('videos.show', compact('video', 'relatedVideos'));
    }
}
