<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $totalViews = (int) Article::sum('view_count');
        $uniqueVisitorsEstimate = (int) ($totalViews * 0.39);
        $totalArticles = Article::count();
        $totalComments = Comment::count();
        $totalSubscribers = NewsletterSubscriber::count();

        // Top Performing Articles
        $topArticles = Article::published()
            ->with(['author', 'category'])
            ->orderByDesc('view_count')
            ->take(8)
            ->get();

        // Traffic by Category/Channel
        $channelTraffic = Category::withCount('articles')
            ->get()
            ->map(function ($cat) {
                $views = Article::where('category_id', $cat->id)->sum('view_count');
                return [
                    'name' => $cat->name,
                    'color' => $cat->color ?? '#6366F1',
                    'articles_count' => $cat->articles_count,
                    'views' => (int) $views,
                ];
            })
            ->sortByDesc('views')
            ->values();

        // Recent Analytics Events
        $recentEvents = AnalyticsEvent::with(['article', 'user'])
            ->latest()
            ->take(15)
            ->get();

        // Daily Traffic Trend (Last 7 Days)
        $dailyTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('M j');
            // Check real events if recorded, else smooth distribution of views
            $eventCount = AnalyticsEvent::whereDate('created_at', $date->toDateString())->count();
            $pv = $eventCount > 0 ? $eventCount : (int) ($totalViews * (0.10 + ($i % 3) * 0.02));
            $dailyTrends[] = [
                'label' => $dateStr,
                'pageviews' => $pv,
                'visitors' => (int) ($pv * 0.39),
            ];
        }

        return view('admin.analytics.index', compact(
            'totalViews',
            'uniqueVisitorsEstimate',
            'totalArticles',
            'totalComments',
            'totalSubscribers',
            'topArticles',
            'channelTraffic',
            'recentEvents',
            'dailyTrends'
        ));
    }
}
