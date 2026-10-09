<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Podcast;
use App\Models\RankingsList;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));
        $categoryId = $request->input('category');
        $authorId = $request->input('author');
        $contentType = $request->input('type', 'all'); // all, articles, videos, podcasts, rankings, authors
        $dateRange = $request->input('date', 'all'); // all, 24h, 7d, 30d, 1y
        $sortBy = $request->input('sort', 'latest'); // latest, views, trending

        $articles = collect();
        $videos = collect();
        $podcasts = collect();
        $rankings = collect();
        $authors = collect();
        $topics = collect();

        if ($query !== '') {
            // 1. Articles Search
            if (in_array($contentType, ['all', 'articles'])) {
                $articlesQuery = Article::published()
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('subtitle', 'like', "%{$query}%")
                          ->orWhere('excerpt', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->with(['author', 'category', 'tags']);

                if ($categoryId) {
                    $articlesQuery->where('category_id', $categoryId);
                }

                if ($authorId) {
                    $articlesQuery->where('user_id', $authorId);
                }

                if ($dateRange === '24h') {
                    $articlesQuery->where('published_at', '>=', now()->subDay());
                } elseif ($dateRange === '7d') {
                    $articlesQuery->where('published_at', '>=', now()->subDays(7));
                } elseif ($dateRange === '30d') {
                    $articlesQuery->where('published_at', '>=', now()->subDays(30));
                } elseif ($dateRange === '1y') {
                    $articlesQuery->where('published_at', '>=', now()->subYear());
                }

                if ($sortBy === 'views') {
                    $articlesQuery->orderByDesc('view_count');
                } elseif ($sortBy === 'trending') {
                    $articlesQuery->orderByDesc('trending_score');
                } else {
                    $articlesQuery->latest('published_at');
                }

                $articles = $articlesQuery->paginate(10)->withQueryString();
            }

            // 2. Videos Search
            if (in_array($contentType, ['all', 'videos'])) {
                $videos = Video::published()
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['category'])
                    ->take(6)
                    ->get();
            }

            // 3. Podcasts Search
            if (in_array($contentType, ['all', 'podcasts'])) {
                $podcasts = Podcast::where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->orWhere('host_name', 'like', "%{$query}%");
                    })
                    ->withCount('episodes')
                    ->take(6)
                    ->get();
            }

            // 4. Rankings Search
            if (in_array($contentType, ['all', 'rankings'])) {
                $rankings = RankingsList::published()
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('subtitle', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->take(4)
                    ->get();
            }

            // 5. Authors Search
            if (in_array($contentType, ['all', 'authors'])) {
                $authors = User::whereIn('role', ['admin', 'editor', 'writer', 'contributor'])
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('title', 'like', "%{$query}%")
                          ->orWhere('bio', 'like', "%{$query}%");
                    })
                    ->take(4)
                    ->get();
            }

            // 6. Topics Search
            $topics = Tag::where('name', 'like', "%{$query}%")->take(5)->get();
        }

        $categories = Category::navVisible()->get();
        $writers = User::whereIn('role', ['admin', 'editor', 'writer', 'contributor'])->get();

        return view('search.index', compact(
            'articles',
            'videos',
            'podcasts',
            'rankings',
            'authors',
            'topics',
            'query',
            'categories',
            'writers',
            'categoryId',
            'authorId',
            'contentType',
            'dateRange',
            'sortBy'
        ));
    }

    /**
     * Fast JSON autocomplete for real-time suggestions dropdown.
     */
    public function autocomplete(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $articles = Article::published()
            ->where('title', 'like', "%{$query}%")
            ->take(4)
            ->get(['title', 'slug'])
            ->map(fn ($a) => [
                'type' => 'article',
                'title' => $a->title,
                'url' => route('articles.show', $a->slug),
            ]);

        $authors = User::whereIn('role', ['admin', 'editor', 'writer', 'contributor'])
            ->where('name', 'like', "%{$query}%")
            ->take(3)
            ->get(['name', 'slug', 'title'])
            ->map(fn ($u) => [
                'type' => 'author',
                'title' => $u->name,
                'subtitle' => $u->title,
                'url' => route('authors.show', $u->slug),
            ]);

        $topics = Tag::where('name', 'like', "%{$query}%")
            ->take(3)
            ->get(['name', 'slug'])
            ->map(fn ($t) => [
                'type' => 'topic',
                'title' => $t->name,
                'url' => route('search.index', ['q' => $t->name]),
            ]);

        return response()->json([
            'suggestions' => $articles->concat($authors)->concat($topics),
        ]);
    }
}
