<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminVideoController extends Controller
{
    public function index()
    {
        $videos = Video::with(['category', 'author'])->latest()->paginate(15);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'thumbnail_url' => 'nullable|url',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'duration_seconds' => 'nullable|integer',
            'is_featured' => 'boolean',
        ]);

        $url = $validated['video_url'];
        $provider = 'html5';

        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            $provider = 'youtube';
        } elseif (str_contains($url, 'vimeo.com')) {
            $provider = 'vimeo';
        }

        $validated['provider'] = $provider;
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['user_id'] = Auth::id();
        $validated['published_at'] = now();
        $validated['is_featured'] = $request->boolean('is_featured');

        $video = Video::create($validated);
        AuditLog::record('video_published', 'Video', $video->id, "Published video {$video->title}");

        return redirect()->route('admin.videos.index')->with('success', 'Video broadcast published successfully.');
    }

    public function destroy(Video $video)
    {
        AuditLog::record('video_deleted', 'Video', $video->id, "Deleted video {$video->title}");
        $video->delete();
        return back()->with('success', 'Video deleted.');
    }
}
