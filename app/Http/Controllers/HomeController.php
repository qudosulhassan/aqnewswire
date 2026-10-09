<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\RankingsList;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Breaking News
        $breakingNews = Article::published()
            ->where('is_breaking', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        // 2. Hero Story (Featured)
        $heroStory = Article::published()
            ->where('is_featured', true)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->first();

        // If no hero story marked, pick the latest
        if (!$heroStory) {
            $heroStory = Article::published()->with(['author', 'category'])->latest('published_at')->first();
        }

        // Exclude hero story ID from next queries
        $excludeIds = $heroStory ? [$heroStory->id] : [];

        // 3. Secondary Featured / Top Stories (3 articles next to hero)
        $topStories = Article::published()
            ->whereNotIn('id', $excludeIds)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $excludeIds = array_merge($excludeIds, $topStories->pluck('id')->toArray());

        // 4. Trending Stories (Ordered by view_count or trending flag)
        $trendingStories = Article::published()
            ->where('is_trending', true)
            ->with(['author', 'category'])
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        // 5. Editors' Picks
        $editorsPicks = Article::published()
            ->where('is_editors_pick', true)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(4)
            ->get();

        // 6. Latest Insights Rail (4-5 stories)
        $latestInsights = Article::published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(4)
            ->get();

        // 7. What's Moving the World (Feature + 3 side stories)
        $whatsMovingFeature = Article::published()
            ->where('id', '!=', $heroStory?->id)
            ->with(['author', 'category'])
            ->orderByDesc('view_count')
            ->first() ?? $heroStory;

        $whatsMovingSide = Article::published()
            ->whereNotIn('id', array_filter([$heroStory?->id, $whatsMovingFeature?->id]))
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // If side stories are fewer than 3, backfill from published
        if ($whatsMovingSide->count() < 3) {
            $whatsMovingSide = Article::published()
                ->with(['author', 'category'])
                ->take(3)
                ->get();
        }

        // 8. Live Market Indices (Benchmark Data)
        $marketIndices = [
            ['symbol' => 'S&P 500', 'name' => 'S&P 500', 'value' => '5,487.21', 'change' => '+0.84%', 'is_up' => true, 'points' => 'M0,18 L15,16 L30,12 L45,14 L60,8 L75,5'],
            ['symbol' => 'NASDAQ', 'name' => 'NASDAQ Composite', 'value' => '17,371.08', 'change' => '+1.12%', 'is_up' => true, 'points' => 'M0,19 L15,17 L30,13 L45,10 L60,6 L75,3'],
            ['symbol' => 'DOW JONES', 'name' => 'Dow Jones Ind.', 'value' => '39,721.36', 'change' => '+0.62%', 'is_up' => true, 'points' => 'M0,16 L15,15 L30,13 L45,11 L60,9 L75,6'],
            ['symbol' => 'FTSE 100', 'name' => 'FTSE 100 London', 'value' => '7,682.11', 'change' => '+0.48%', 'is_up' => true, 'points' => 'M0,17 L15,15 L30,14 L45,10 L60,8 L75,5'],
            ['symbol' => 'Nikkei 225', 'name' => 'Nikkei 225 Tokyo', 'value' => '38,254.17', 'change' => '+1.21%', 'is_up' => true, 'points' => 'M0,18 L15,14 L30,11 L45,12 L60,6 L75,2'],
            ['symbol' => 'Gold', 'name' => 'Gold Spot / oz', 'value' => '2,401.50', 'change' => '+0.35%', 'is_up' => true, 'points' => 'M0,14 L15,16 L30,13 L45,15 L60,11 L75,8'],
            ['symbol' => 'Oil (Brent)', 'name' => 'Brent Crude Oil', 'value' => '82.19', 'change' => '-0.42%', 'is_up' => false, 'points' => 'M0,6 L15,8 L30,12 L45,11 L60,15 L75,18'],
            ['symbol' => 'Bitcoin', 'name' => 'BTC / USD', 'value' => '64,317.20', 'change' => '+2.14%', 'is_up' => true, 'points' => 'M0,19 L15,15 L30,16 L45,10 L60,7 L75,3'],
        ];

        // 9. Intelligence Module Metrics
        $intelligenceMetrics = [
            ['value' => '$200B', 'label' => 'Global AI Investment in 2026', 'trend' => '+32% YoY', 'is_positive' => true],
            ['value' => '3.8%', 'label' => 'Global GDP Growth Forecast', 'trend' => '+0.6%', 'is_positive' => true],
            ['value' => '72%', 'label' => 'CEOs Prioritize AI in Strategy', 'trend' => '+18%', 'is_positive' => true],
        ];

        // 10. Category Ecosystem Collections
        $allPublished = Article::published()->with(['author', 'category'])->get();
        
        $aiStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, ['ai', 'enterprise', 'tech', 'ai-innovation', 'technology']))->take(3);
        if ($aiStories->isEmpty()) { $aiStories = $allPublished->take(3); }

        $leadershipStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, ['workplace', 'business', 'leadership']))->take(3);
        if ($leadershipStories->isEmpty()) { $leadershipStories = $allPublished->skip(1)->take(3); }

        $financeStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, ['markets', 'bonds', 'finance', 'investing']))->take(3);
        if ($financeStories->isEmpty()) { $financeStories = $allPublished->skip(2)->take(3); }

        // 11. Latest Stream & Categories
        $latestStream = Article::published()
            ->whereNotIn('id', $excludeIds)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->paginate(6);

        $categories = Category::navVisible()->get();

        // 12. Featured Rankings List Spotlight
        $featuredList = RankingsList::with(['items' => fn($q) => $q->orderBy('rank')->take(5)])
            ->where('is_featured', true)
            ->latest()
            ->first() ?? RankingsList::with(['items' => fn($q) => $q->orderBy('rank')->take(5)])->first();

        // 13. Advertisements
        $billboardAd = Advertisement::active()->where('zone', 'billboard')->inRandomOrder()->first();
        $sidebarAd = Advertisement::active()->where('zone', 'sidebar')->inRandomOrder()->first();

        return view('home', compact(
            'breakingNews',
            'heroStory',
            'topStories',
            'trendingStories',
            'editorsPicks',
            'latestInsights',
            'whatsMovingFeature',
            'whatsMovingSide',
            'marketIndices',
            'intelligenceMetrics',
            'aiStories',
            'leadershipStories',
            'financeStories',
            'latestStream',
            'categories',
            'featuredList',
            'billboardAd',
            'sidebarAd'
        ));
    }
}
