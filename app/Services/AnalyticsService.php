<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Carbon;

class AnalyticsService
{
    /**
     * Record a genuine analytical event.
     */
    public function track(string $eventType, ?int $userId = null, ?int $articleId = null, ?string $pageUrl = null, array $metadata = []): AnalyticsEvent
    {
        return AnalyticsEvent::create([
            'event_type' => $eventType,
            'user_id' => $userId,
            'article_id' => $articleId,
            'page_url' => $pageUrl ?? request()->path(),
            'referrer' => request()->header('referer'),
            'ip_address' => request()->ip(),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Get real summary metrics across the requested timeframe.
     */
    public function getSummary(string $period = '7d'): array
    {
        $since = match ($period) {
            'today' => Carbon::today(),
            '30d' => Carbon::now()->subDays(30),
            'all' => Carbon::createFromTimestamp(0),
            default => Carbon::now()->subDays(7),
        };

        $eventCounts = AnalyticsEvent::where('created_at', '>=', $since)
            ->selectRaw('event_type, count(*) as count')
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->all();

        $totalArticleViews = Article::sum('view_count');
        $totalBookmarks = Bookmark::where('created_at', '>=', $since)->count();
        $totalComments = Comment::where('created_at', '>=', $since)->count();
        $newSubscribers = NewsletterSubscriber::where('subscribed_at', '>=', $since)->count();
        $registeredUsers = User::where('created_at', '>=', $since)->count();

        $topArticles = Article::published()
            ->orderByDesc('view_count')
            ->take(5)
            ->get(['id', 'title', 'slug', 'view_count', 'published_at']);

        return [
            'period' => $period,
            'events' => $eventCounts,
            'total_article_views' => $totalArticleViews,
            'period_bookmarks' => $totalBookmarks,
            'period_comments' => $totalComments,
            'period_subscribers' => $newSubscribers,
            'period_users' => $registeredUsers,
            'top_articles' => $topArticles,
        ];
    }
}
