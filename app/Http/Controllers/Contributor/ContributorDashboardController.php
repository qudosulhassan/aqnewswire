<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContributorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total' => $user->articles()->count(),
            'published' => $user->articles()->where('status', 'published')->count(),
            'submitted' => $user->articles()->where('status', 'submitted')->count(),
            'drafts' => $user->articles()->where('status', 'draft')->count(),
            'views' => $user->articles()->sum('view_count'),
        ];

        $articles = $user->articles()
            ->with(['category', 'editorialNotes'])
            ->latest()
            ->paginate(10);

        return view('contributor.dashboard', compact('user', 'stats', 'articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('contributor.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string|max:600',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'status' => 'required|in:draft,submitted',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);

        // Contributors cannot directly publish without editorial review
        if ($validated['status'] !== 'draft') {
            $validated['status'] = 'submitted';
        }

        Article::create($validated);

        return redirect()->route('contributor.dashboard')->with('success', 'Story draft saved/submitted for editorial review.');
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id() && !Auth::user()->isEditor()) {
            abort(403, 'Unauthorized access to article draft.');
        }

        $categories = Category::all();
        $article->load('editorialNotes.user');

        return view('contributor.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== Auth::id() && !Auth::user()->isEditor()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string|max:600',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'status' => 'required|in:draft,submitted',
        ]);

        if ($validated['status'] !== 'draft') {
            $validated['status'] = 'submitted';
        }

        $article->update($validated);

        return redirect()->route('contributor.dashboard')->with('success', 'Story updated and submitted for editorial review.');
    }
}
