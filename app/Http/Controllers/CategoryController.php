<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified category channel.
     */
    public function show(Request $request, string $slug)
    {
        $category = Category::with(['children', 'parent.children'])->where('slug', $slug)->first();

        if (!$category) {
            abort(404, 'Category channel not found.');
        }

        $filter = $request->query('filter', 'latest');

        // Determine scope of articles (if parent category, aggregate parent + all child category articles)
        if ($category->children->isNotEmpty()) {
            $categoryIds = $category->children->pluck('id')->prepend($category->id)->all();
            $parentCategory = $category;
            $subcategories = $category->children;
        } else {
            $categoryIds = [$category->id];
            $parentCategory = $category->parent ?? $category;
            $subcategories = $category->parent ? $category->parent->children : collect();
        }

        // Main articles query for this category / channel family
        $articlesQuery = Article::published()
            ->whereIn('category_id', $categoryIds)
            ->with(['author', 'category', 'tags']);

        switch ($filter) {
            case 'most-read':
                $articlesQuery->orderByDesc('view_count');
                break;
            case 'trending':
                $articlesQuery->orderByDesc('is_trending')
                              ->orderByDesc('view_count');
                break;
            case 'latest':
            default:
                $articlesQuery->latest('published_at');
                break;
        }

        $articles = $articlesQuery->paginate(9)->withQueryString();

        // Editorial Lead Story (first story on page 1)
        $leadArticle = null;
        $secondaryArticles = collect();

        if ($articles->currentPage() === 1 && $articles->isNotEmpty()) {
            $leadArticle = $articles->first();
            $secondaryArticles = $articles->slice(1);
        } else {
            $secondaryArticles = $articles->getCollection();
        }

        // Most Read in this category, augmented with top network stories if category volume is small
        $categoryTopArticles = Article::published()
            ->whereIn('category_id', $categoryIds)
            ->with(['author', 'category'])
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        if ($categoryTopArticles->count() < 5) {
            $existingIds = $categoryTopArticles->pluck('id')->all();
            $networkTopArticles = Article::published()
                ->whereNotIn('id', $existingIds)
                ->with(['author', 'category'])
                ->orderByDesc('view_count')
                ->take(5 - count($existingIds))
                ->get();
            $mostReadArticles = $categoryTopArticles->concat($networkTopArticles);
        } else {
            $mostReadArticles = $categoryTopArticles;
        }

        // Cross-channel editorial highlights (ensures high content density and discovery even if few stories exist in channel)
        $sisterStories = Article::published()
            ->whereNotIn('category_id', $categoryIds)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(6)
            ->get();

        // Dynamic categories navigation: load all 7 parent categories
        $allCategories = Category::whereNull('parent_id')
            ->orderBy('order')
            ->with('children')
            ->withCount('publishedArticles')
            ->get();

        // Channel real statistics
        $totalPublishedCount = Article::published()->whereIn('category_id', $categoryIds)->count();
        $latestUpdate = Article::published()->whereIn('category_id', $categoryIds)->latest('published_at')->value('published_at');

        // Sidebar Advertisement
        $sidebarAd = Advertisement::active()->where('zone', 'sidebar')->inRandomOrder()->first()
            ?? Advertisement::active()->inRandomOrder()->first();

        return view('categories.show', compact(
            'category',
            'parentCategory',
            'subcategories',
            'articles',
            'leadArticle',
            'secondaryArticles',
            'mostReadArticles',
            'sisterStories',
            'allCategories',
            'totalPublishedCount',
            'latestUpdate',
            'sidebarAd',
            'filter'
        ));
    }
}
