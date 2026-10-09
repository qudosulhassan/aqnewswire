<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function show(Request $request, string $category, ?string $slug = null)
    {
        if ($slug === null) {
            $slug = $category;
            $category = null;
        }

        $article = Article::where('slug', $slug)
            ->with(['author', 'category', 'tags', 'approvedComments.user', 'approvedComments.replies.user'])
            ->firstOrFail();

        // If the URL category segment does not match the article's actual category, return 404
        if ($article->category && $category && $article->category->slug !== $category) {
            abort(404);
        }

        $user = Auth::user();
        $isAuthor = $user && $user->id === $article->user_id;
        $canPreview = $user && ($user->canAccessAdmin() || $isAuthor);

        // Security check: only published articles visible to public; drafts / scheduled require author or staff preview
        $isPubliclyLive = $article->status === 'published' && (!$article->published_at || $article->published_at->isPast());
        if (!$isPubliclyLive && !$canPreview) {
            abort(404);
        }

        // Increment view count quietly only for public views
        if ($isPubliclyLive && !$canPreview) {
            $article->incrementQuietly('view_count');
        }

        // Record reading history if authenticated and feature enabled
        if (Auth::check() && \App\Models\Setting::get('enable_reading_history', true)) {
            ReadingHistory::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'article_id' => $article->id,
                ],
                [
                    'last_read_at' => now(),
                    'progress_percent' => 100,
                ]
            );
        }

        $isBookmarked = Auth::check() && Auth::user()->bookmarks()->where('article_id', $article->id)->exists();
        $isFollowingAuthor = Auth::check() && Auth::user()->follows()->where('followable_type', \App\Models\User::class)->where('followable_id', $article->user_id)->exists();

        $recommendationService = app(\App\Services\RecommendationService::class);
        $relatedArticles = $recommendationService->forArticle($article, 4);

        // Trending stories for sidebar (published only)
        $trendingArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        $sidebarAd = Advertisement::active()->where('zone', 'sidebar')->inRandomOrder()->first();

        return view('articles.show', compact('article', 'relatedArticles', 'trendingArticles', 'sidebarAd', 'isBookmarked', 'isFollowingAuthor', 'canPreview'));
    }
}
