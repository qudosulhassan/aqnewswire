<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Follow;
use App\Models\ReadingHistory;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RecommendationService
{
    /**
     * Get recommendations for a specific article.
     * Uses category, shared tags, and author signals with graceful fallback.
     */
    public function forArticle(Article $article, int $limit = 4): Collection
    {
        $tagIds = $article->tags()->pluck('tags.id');

        $query = Article::published()
            ->where('id', '!=', $article->id)
            ->with(['author', 'category']);

        // Score based on shared category, tags, and author
        $related = $query->where(function ($q) use ($article, $tagIds) {
            $q->where('category_id', $article->category_id);
            if ($tagIds->isNotEmpty()) {
                $q->orWhereHas('tags', function ($tq) use ($tagIds) {
                    $tq->whereIn('tags.id', $tagIds);
                });
            }
            $q->orWhere('user_id', $article->user_id);
        })
        ->latest('published_at')
        ->take($limit)
        ->get();

        if ($related->count() < $limit) {
            $needed = $limit - $related->count();
            $excludeIds = $related->pluck('id')->push($article->id)->all();

            $fallbacks = Article::published()
                ->whereNotIn('id', $excludeIds)
                ->with(['author', 'category'])
                ->latest('published_at')
                ->take($needed)
                ->get();

            $related = $related->merge($fallbacks);
        }

        return $related;
    }

    /**
     * Get personalized recommendations for a user.
     * Uses follows (authors, categories, topics) and reading history.
     */
    public function forUser(?User $user, int $limit = 6): Collection
    {
        if (!$user) {
            return Article::published()
                ->where(function ($q) {
                    $q->where('is_editors_pick', true)->orWhere('is_featured', true);
                })
                ->with(['author', 'category'])
                ->latest('published_at')
                ->take($limit)
                ->get();
        }

        // Gather user follows
        $followedAuthorIds = Follow::where('user_id', $user->id)
            ->where('followable_type', User::class)
            ->pluck('followable_id');

        $followedCategoryIds = Follow::where('user_id', $user->id)
            ->where('followable_type', Category::class)
            ->pluck('followable_id');

        $followedTagIds = Follow::where('user_id', $user->id)
            ->where('followable_type', Tag::class)
            ->pluck('followable_id');

        // Recent read article IDs to optionally deprioritize duplicates
        $recentlyReadIds = ReadingHistory::where('user_id', $user->id)
            ->where('progress_percent', '>=', 80)
            ->latest('last_read_at')
            ->take(20)
            ->pluck('article_id');

        $hasSignals = $followedAuthorIds->isNotEmpty() || $followedCategoryIds->isNotEmpty() || $followedTagIds->isNotEmpty();

        if ($hasSignals) {
            $query = Article::published()->with(['author', 'category']);

            if ($recentlyReadIds->isNotEmpty()) {
                $query->whereNotIn('id', $recentlyReadIds);
            }

            $recommendations = $query->where(function ($q) use ($followedAuthorIds, $followedCategoryIds, $followedTagIds) {
                if ($followedAuthorIds->isNotEmpty()) {
                    $q->orWhereIn('user_id', $followedAuthorIds);
                }
                if ($followedCategoryIds->isNotEmpty()) {
                    $q->orWhereIn('category_id', $followedCategoryIds);
                }
                if ($followedTagIds->isNotEmpty()) {
                    $q->orWhereHas('tags', function ($tq) use ($followedTagIds) {
                        $tq->whereIn('tags.id', $followedTagIds);
                    });
                }
            })
            ->latest('published_at')
            ->take($limit)
            ->get();
        } else {
            $recommendations = new Collection();
        }

        // Fallback to top editorial stories and trending if needed
        if ($recommendations->count() < $limit) {
            $needed = $limit - $recommendations->count();
            $excludeIds = $recommendations->pluck('id')->merge($recentlyReadIds)->all();

            $fallbacks = Article::published()
                ->whereNotIn('id', $excludeIds)
                ->with(['author', 'category'])
                ->orderByDesc('view_count')
                ->latest('published_at')
                ->take($needed)
                ->get();

            $recommendations = $recommendations->merge($fallbacks);
        }

        return $recommendations;
    }

    /**
     * Get recommendations within a category.
     */
    public function forCategory(Category $category, ?Article $excludeArticle = null, int $limit = 4): Collection
    {
        $query = Article::published()
            ->where('category_id', $category->id)
            ->with(['author', 'category']);

        if ($excludeArticle) {
            $query->where('id', '!=', $excludeArticle->id);
        }

        return $query->latest('published_at')
            ->take($limit)
            ->get();
    }
}
