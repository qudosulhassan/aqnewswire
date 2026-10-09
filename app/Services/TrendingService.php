<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class TrendingService
{
    /**
     * Calculate and persist trending scores for recently published articles.
     * Uses engagement signals (views, bookmarks, comments) with a time-decay factor.
     */
    public function recalculateScores(): int
    {
        $articles = Article::published()
            ->where('published_at', '>=', now()->subDays(30))
            ->withCount(['bookmarks', 'comments'])
            ->get();

        foreach ($articles as $article) {
            $hoursOld = max(1, now()->diffInHours($article->published_at));
            
            // Engagement gravity formula:
            // (views * 1 + bookmarks * 5 + comments * 3) / (hours + 2)^1.3
            $engagement = ($article->view_count * 1) 
                        + ($article->bookmarks_count * 5) 
                        + ($article->comments_count * 3);
            
            $score = $engagement / pow($hoursOld + 2, 1.3);

            $article->updateQuietly([
                'trending_score' => round($score, 4),
            ]);
        }

        return $articles->count();
    }

    /**
     * Get top trending articles ordered by calculated trending score.
     */
    public function getTrending(int $limit = 5, ?int $excludeArticleId = null): Collection
    {
        $query = Article::published()
            ->with(['author', 'category']);

        if ($excludeArticleId) {
            $query->where('id', '!=', $excludeArticleId);
        }

        $results = $query->orderByDesc('trending_score')
            ->orderByDesc('view_count')
            ->take($limit)
            ->get();

        // Graceful fallback if scores haven't been calculated yet
        if ($results->isEmpty()) {
            $results = Article::published()
                ->where('id', '!=', $excludeArticleId)
                ->with(['author', 'category'])
                ->orderByDesc('view_count')
                ->take($limit)
                ->get();
        }

        return $results;
    }
}
