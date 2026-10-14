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
        if ($editorsPicks->count() < 4) {
            $editorsPicks = Article::published()
                ->with(['author', 'category'])
                ->orderByDesc('view_count')
                ->take(4)
                ->get();
        }

        // 6. Latest Insights Rail (8 stories)
        $latestInsights = Article::published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(8)
            ->get();

        // 7. What's Moving the World (Feature + 4 side stories)
        $whatsMovingFeature = Article::published()
            ->where('id', '!=', $heroStory?->id)
            ->with(['author', 'category'])
            ->orderByDesc('view_count')
            ->first() ?? $heroStory;

        $whatsMovingSide = Article::published()
            ->whereNotIn('id', array_filter([$heroStory?->id, $whatsMovingFeature?->id]))
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(4)
            ->get();

        // If side stories are fewer than 4, backfill from published
        if ($whatsMovingSide->count() < 4) {
            $whatsMovingSide = Article::published()
                ->where('id', '!=', $whatsMovingFeature?->id)
                ->with(['author', 'category'])
                ->take(4)
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

        // 10. Comprehensive Category Ecosystem Collections
        $allPublished = Article::published()->with(['author', 'category'])->latest('published_at')->get();

        // A. Tech & AI
        $techStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, [
            'ai', 'enterprise', 'tech', 'mobile', 'cybersecurity', 'internet', 'social-media', 'tech-guide'
        ]))->take(5);
        if ($techStories->count() < 4) {
            $techStories = $allPublished->take(5);
        }

        // B. Markets, Finance & Investing
        $marketsStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, [
            'markets', 'finance', 'economy', 'wealth', 'investing', 'bonds', 'fintech', 'earnings', 'etf-street', 'trader-talk', 'us-markets', 'europe-markets', 'asia-markets'
        ]))->take(5);
        if ($marketsStories->count() < 4) {
            $marketsStories = $allPublished->skip(2)->take(5);
        }

        // C. Politics & Geopolitics
        $politicsStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, [
            'politics', 'white-house', 'defense', 'congress', 'world-politics', 'europe-politics', 'china-politics', 'asia-politics', 'expanding-opportunity'
        ]))->take(5);
        if ($politicsStories->count() < 4) {
            $politicsStories = $allPublished->skip(4)->take(5);
        }

        // D. Leadership, Workplace & Lifestyle
        $lifestyleStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, [
            'lifestyle', 'workplace', 'careers', 'cars', 'travel', 'food-cooking', 'fitness', 'relationships', 'life', 'small-business', 'retail', 'media'
        ]))->take(5);
        if ($lifestyleStories->count() < 4) {
            $lifestyleStories = $allPublished->skip(6)->take(5);
        }

        // E. Health, Longevity & Science
        $healthStories = $allPublished->filter(fn($a) => in_array($a->category?->slug, [
            'health', 'mental-health', 'supplements', 'hearing-health', 'medical-devices', 'diet'
        ]))->take(5);
        if ($healthStories->count() < 4) {
            $healthStories = $allPublished->skip(8)->take(5);
        }

        // F. Deep Dive / Special Investigation Story
        $investigationStory = $allPublished->firstWhere('category.slug', 'investigations')
            ?? $allPublished->firstWhere('is_editors_pick', true)
            ?? $allPublished->first();

        // 11. Latest Stream & Categories
        $latestStream = Article::published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(8)
            ->get();

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
            'techStories',
            'marketsStories',
            'politicsStories',
            'lifestyleStories',
            'healthStories',
            'investigationStory',
            'latestStream',
            'categories',
            'featuredList',
            'billboardAd',
            'sidebarAd'
        ));
    }
}
