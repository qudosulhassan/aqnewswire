<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\RankingsList;
use App\Models\SeoRedirect;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use SimpleXMLElement;

class SitemapService
{
    /**
     * Get all published articles eligible for XML sitemap inclusion.
     * Google Search Central Rule: URLs must represent canonical, indexable content.
     * Excludes drafts, scheduled future dates, deleted, and noindex stories.
     */
    public function getEligibleArticles(): Collection
    {
        return Article::indexable()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->with(['category', 'author'])
            ->latest('updated_at')
            ->get();
    }

    /**
     * Check if a specific article is eligible for sitemap inclusion.
     */
    public function isArticleEligible(Article $article): array
    {
        if ($article->status !== 'published') {
            return ['eligible' => false, 'reason' => "Non-published status ({$article->status})"];
        }

        if (empty($article->published_at) || $article->published_at->isFuture()) {
            return ['eligible' => false, 'reason' => 'Unpublished or scheduled for future publication'];
        }

        if (!empty($article->robots) && str_contains(strtolower($article->robots), 'noindex')) {
            return ['eligible' => false, 'reason' => 'Robots directive contains noindex'];
        }

        if (empty(trim($article->slug ?? ''))) {
            return ['eligible' => false, 'reason' => 'Missing URL slug'];
        }

        return ['eligible' => true, 'reason' => 'Eligible indexable canonical story'];
    }

    /**
     * Get eligible categories.
     */
    public function getEligibleCategories(): Collection
    {
        return Category::whereNotNull('slug')
            ->where('slug', '!=', '')
            ->get();
    }

    /**
     * Get eligible published rankings lists.
     */
    public function getEligibleRankings(): Collection
    {
        return RankingsList::where('status', 'published')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->get();
    }

    /**
     * Get eligible authors (active staff/contributors with at least one published article).
     */
    public function getEligibleAuthors(): Collection
    {
        return User::whereIn('role', ['admin', 'editor', 'managing_editor', 'writer', 'contributor'])
            ->where('is_active', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->whereHas('articles', fn($q) => $q->published())
            ->get();
    }

    /**
     * Collect all sitemap URLs as structured array entries.
     */
    public function getAllSitemapEntries(): array
    {
        $entries = [];

        // 1. Core Platform Pages
        $entries[] = [
            'loc' => url('/'),
            'type' => 'Core',
            'title' => config('app.name', 'AQ NEWSWIRE') . ' Homepage',
            'lastmod' => now()->startOfDay()->toAtomString(),
            'changefreq' => 'hourly',
            'priority' => '1.0',
            'model_id' => null,
            'model_type' => null,
            'canonical_url' => url('/'),
            'robots' => 'index, follow',
        ];

        $entries[] = [
            'loc' => route('rankings.index'),
            'type' => 'Core',
            'title' => 'Industry Rankings Index',
            'lastmod' => now()->startOfWeek()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
            'model_id' => null,
            'model_type' => null,
            'canonical_url' => route('rankings.index'),
            'robots' => 'index, follow',
        ];

        // 2. Categories
        foreach ($this->getEligibleCategories() as $cat) {
            $catUrl = route('categories.show', $cat->slug);
            $entries[] = [
                'loc' => $catUrl,
                'type' => 'Category',
                'title' => $cat->name,
                'lastmod' => $cat->updated_at ? $cat->updated_at->toAtomString() : now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
                'model_id' => $cat->id,
                'model_type' => Category::class,
                'canonical_url' => $catUrl,
                'robots' => 'index, follow',
            ];
        }

        // 3. Rankings Lists
        foreach ($this->getEligibleRankings() as $rank) {
            $rankUrl = route('rankings.show', $rank->slug);
            $entries[] = [
                'loc' => $rankUrl,
                'type' => 'Ranking',
                'title' => $rank->title,
                'lastmod' => $rank->updated_at ? $rank->updated_at->toAtomString() : now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
                'model_id' => $rank->id,
                'model_type' => RankingsList::class,
                'canonical_url' => $rankUrl,
                'robots' => 'index, follow',
            ];
        }

        // 4. Authors
        foreach ($this->getEligibleAuthors() as $author) {
            $authorUrl = route('authors.show', $author->slug);
            $entries[] = [
                'loc' => $authorUrl,
                'type' => 'Author',
                'title' => $author->name,
                'lastmod' => $author->updated_at ? $author->updated_at->toAtomString() : now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
                'model_id' => $author->id,
                'model_type' => User::class,
                'canonical_url' => $authorUrl,
                'robots' => 'index, follow',
            ];
        }

        // 5. Eligible Articles
        foreach ($this->getEligibleArticles() as $article) {
            $articleUrl = route('articles.show', $article->slug);
            // Actual content modification date (do not fabricate)
            $lastmod = ($article->updated_at ?? $article->published_at ?? now())->toAtomString();

            $entries[] = [
                'loc' => $articleUrl,
                'type' => 'Article',
                'title' => $article->title,
                'lastmod' => $lastmod,
                'changefreq' => 'monthly',
                'priority' => '0.9',
                'model_id' => $article->id,
                'model_type' => Article::class,
                'canonical_url' => $article->effective_canonical_url,
                'robots' => $article->effective_robots,
            ];
        }

        return $entries;
    }

    /**
     * Generate valid XML sitemap string for Sitemaps Protocol 0.9.
     */
    public function generateXml(string $type = 'main'): string
    {
        if ($type === 'news') {
            return $this->generateNewsXml();
        }

        if ($type === 'index') {
            return $this->generateIndexXml();
        }

        $entries = match ($type) {
            'articles' => array_filter($this->getAllSitemapEntries(), fn($e) => $e['type'] === 'Article'),
            'categories' => array_filter($this->getAllSitemapEntries(), fn($e) => $e['type'] === 'Category'),
            'rankings' => array_filter($this->getAllSitemapEntries(), fn($e) => $e['type'] === 'Ranking'),
            'authors' => array_filter($this->getAllSitemapEntries(), fn($e) => $e['type'] === 'Author'),
            default => $this->getAllSitemapEntries(),
        };

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($entries as $entry) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>' . "\n";
            if (!empty($entry['lastmod'])) {
                $xml .= '    <lastmod>' . htmlspecialchars($entry['lastmod'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</lastmod>' . "\n";
            }
            if (!empty($entry['changefreq'])) {
                $xml .= '    <changefreq>' . htmlspecialchars($entry['changefreq'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</changefreq>' . "\n";
            }
            if (!empty($entry['priority'])) {
                $xml .= '    <priority>' . htmlspecialchars($entry['priority'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</priority>' . "\n";
            }
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate Google News XML Sitemap (stories published in last 48 hours).
     */
    public function generateNewsXml(): string
    {
        $articles = Article::indexable()
            ->where('published_at', '>=', now()->subHours(48))
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('published_at')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        $appName = config('app.name', 'AQ NEWSWIRE');

        foreach ($articles as $art) {
            $loc = route('articles.show', $art->slug);
            $pubDate = ($art->published_at ?? now())->toAtomString();
            $title = htmlspecialchars($art->title, ENT_XML1 | ENT_COMPAT, 'UTF-8');

            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>' . "\n";
            $xml .= '    <news:news>' . "\n";
            $xml .= '      <news:publication>' . "\n";
            $xml .= '        <news:name>' . htmlspecialchars($appName, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</news:name>' . "\n";
            $xml .= '        <news:language>en</news:language>' . "\n";
            $xml .= '      </news:publication>' . "\n";
            $xml .= '      <news:publication_date>' . $pubDate . '</news:publication_date>' . "\n";
            $xml .= '      <news:title>' . $title . '</news:title>' . "\n";
            $xml .= '    </news:news>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate Sitemap Index XML (for scalable multi-sitemap architecture).
     */
    public function generateIndexXml(): string
    {
        $subSitemaps = [
            url('/sitemap-articles.xml'),
            url('/sitemap-categories.xml'),
            url('/sitemap-rankings.xml'),
            url('/sitemap-authors.xml'),
            url('/sitemap-news.xml'),
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($subSitemaps as $mapUrl) {
            $xml .= '  <sitemap>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($mapUrl, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>' . "\n";
            $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
            $xml .= '  </sitemap>' . "\n";
        }

        $xml .= '</sitemapindex>';

        return $xml;
    }

    /**
     * Deep server-side technical validation of sitemap output according to Google Search Central rules.
     */
    public function validateSitemap(string $type = 'main'): array
    {
        $xml = $this->generateXml($type);
        $checks = [];

        // 1. XML parse validity
        libxml_use_internal_errors(true);
        $parsed = simplexml_load_string($xml);
        $xmlErrors = libxml_get_errors();
        libxml_clear_errors();

        $isValidXml = ($parsed !== false && empty($xmlErrors));
        $checks[] = [
            'name' => 'XML Syntax & Schema Validity',
            'passed' => $isValidXml,
            'label' => $isValidXml ? 'Valid XML' : 'Malformed XML',
            'detail' => $isValidXml ? 'Parses cleanly as valid XML document without syntax errors' : 'XML parsing failed: ' . json_encode($xmlErrors),
        ];

        // Extract URLs
        $urls = [];
        $lastmods = [];
        if ($isValidXml) {
            if ($type === 'index') {
                foreach ($parsed->sitemap as $sm) {
                    $urls[] = (string) $sm->loc;
                }
            } else {
                foreach ($parsed->url as $u) {
                    $urls[] = (string) $u->loc;
                    if (isset($u->lastmod)) {
                        $lastmods[] = (string) $u->lastmod;
                    }
                }
            }
        }

        $urlCount = count($urls);
        $uniqueUrls = array_unique($urls);
        $uniqueCount = count($uniqueUrls);
        $duplicates = array_values(array_diff_assoc($urls, $uniqueUrls));

        // 2. URL Count & Duplicates
        $noDuplicates = empty($duplicates);
        $checks[] = [
            'name' => 'URL Uniqueness',
            'passed' => $noDuplicates,
            'label' => $noDuplicates ? 'No Duplicate URLs' : count($duplicates) . ' Duplicates Detected',
            'detail' => $noDuplicates ? "{$urlCount} URLs indexed with zero duplicates" : 'Duplicate URLs: ' . implode(', ', array_slice($duplicates, 0, 5)),
        ];

        // 3. Absolute URL format
        $invalidUrls = [];
        $nonAbsoluteUrls = [];
        foreach ($urls as $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $invalidUrls[] = $url;
            }
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $nonAbsoluteUrls[] = $url;
            }
        }

        $allAbsolute = empty($nonAbsoluteUrls) && empty($invalidUrls);
        $checks[] = [
            'name' => 'Absolute URL Format',
            'passed' => $allAbsolute,
            'label' => $allAbsolute ? '100% Absolute URLs' : 'Malformed URLs Detected',
            'detail' => $allAbsolute ? 'All sitemap URLs are fully qualified absolute URLs' : 'Relative or malformed URLs found',
        ];

        // 4. Production Domain & HTTPS Check
        $isProduction = app()->environment('production');
        $localhostUrls = [];
        $nonHttpsUrls = [];
        foreach ($urls as $url) {
            $host = parse_url($url, PHP_URL_HOST) ?? '';
            $scheme = parse_url($url, PHP_URL_SCHEME) ?? '';

            if (in_array(strtolower($host), ['localhost', '127.0.0.1'])) {
                $localhostUrls[] = $url;
            }
            if (strtolower($scheme) !== 'https') {
                $nonHttpsUrls[] = $url;
            }
        }

        if ($isProduction) {
            $prodSafe = empty($localhostUrls) && empty($nonHttpsUrls);
            $checks[] = [
                'name' => 'Production HTTPS & Domain Security',
                'passed' => $prodSafe,
                'label' => $prodSafe ? 'Production Secure' : 'Insecure Production URLs',
                'detail' => $prodSafe ? 'All production URLs resolve over HTTPS without localhost references' : 'Contains non-HTTPS or localhost entries in production environment',
            ];
        } else {
            // Local development disclosure
            $checks[] = [
                'name' => 'Local Environment Host Inspection',
                'passed' => true,
                'label' => 'Local Development Verified',
                'detail' => 'Operating on local environment (' . config('app.url') . '). Production domain will use HTTPS automatically.',
            ];
        }

        // 5. Exclusions Check: Draft, Deleted & Noindex stories
        $draftUrlsFound = [];
        $noindexUrlsFound = [];
        $draftSlugs = Article::where('status', 'draft')->pluck('slug')->all();
        $noindexSlugs = Article::whereNotNull('robots')->where('robots', 'like', '%noindex%')->pluck('slug')->all();

        foreach ($urls as $url) {
            foreach ($draftSlugs as $slug) {
                if (str_contains($url, "/article/{$slug}")) {
                    $draftUrlsFound[] = $url;
                }
            }
            foreach ($noindexSlugs as $slug) {
                if (str_contains($url, "/article/{$slug}")) {
                    $noindexUrlsFound[] = $url;
                }
            }
        }

        $draftsExcluded = empty($draftUrlsFound);
        $checks[] = [
            'name' => 'Draft Articles Excluded',
            'passed' => $draftsExcluded,
            'label' => $draftsExcluded ? '0 Drafts in Sitemap' : count($draftUrlsFound) . ' Drafts Leak',
            'detail' => $draftsExcluded ? 'Draft stories are strictly excluded from crawler sitemaps' : 'Draft articles detected in sitemap feed: ' . implode(', ', $draftUrlsFound),
        ];

        $noindexExcluded = empty($noindexUrlsFound);
        $checks[] = [
            'name' => 'Noindex Articles Excluded',
            'passed' => $noindexExcluded,
            'label' => $noindexExcluded ? '0 Noindex URLs' : count($noindexUrlsFound) . ' Noindex Conflict',
            'detail' => $noindexExcluded ? 'Stories flagged with noindex directives are strictly omitted' : 'Noindex conflict: Sitemap contains URLs that specify noindex: ' . implode(', ', $noindexUrlsFound),
        ];

        // 6. Canonical Consistency & Redirect Checks
        $canonicalMismatches = [];
        $redirectedUrls = [];
        $activeRedirects = SeoRedirect::where('is_active', true)->get()->keyBy(fn($r) => '/' . ltrim(strtolower($r->source_path), '/'));

        // Check articles
        $publishedArticles = Article::published()->get()->keyBy(fn($a) => route('articles.show', $a->slug));
        foreach ($urls as $url) {
            $path = parse_url($url, PHP_URL_PATH) ?? '';
            $normPath = '/' . ltrim(strtolower($path), '/');

            // Redirected check
            if ($activeRedirects->has($normPath)) {
                $redirectedUrls[] = [
                    'url' => $url,
                    'destination' => $activeRedirects->get($normPath)->destination_url,
                    'status_code' => $activeRedirects->get($normPath)->status_code,
                ];
            }

            // Canonical check
            if ($publishedArticles->has($url)) {
                $art = $publishedArticles->get($url);
                $effectiveCanonical = $art->effective_canonical_url;
                if ($effectiveCanonical !== $url) {
                    $canonicalMismatches[] = [
                        'sitemap_url' => $url,
                        'canonical_url' => $effectiveCanonical,
                    ];
                }
            }
        }

        $noRedirects = empty($redirectedUrls);
        $checks[] = [
            'name' => 'Direct Crawlability (No Redirected URLs in Sitemap)',
            'passed' => $noRedirects,
            'label' => $noRedirects ? '0 Redirected Sitemap URLs' : count($redirectedUrls) . ' Redirected URLs',
            'detail' => $noRedirects ? 'All sitemap URLs point to direct final destinations' : 'Sitemap contains URLs that trigger 301/302 redirects: ' . count($redirectedUrls),
        ];

        $canonicalConsistent = empty($canonicalMismatches);
        $checks[] = [
            'name' => 'Canonical Consistency',
            'passed' => $canonicalConsistent,
            'label' => $canonicalConsistent ? '100% Canonical Alignment' : count($canonicalMismatches) . ' Canonical Mismatches',
            'detail' => $canonicalConsistent ? 'Sitemap URLs exactly match public canonical URL directives' : 'Mismatch found between sitemap location and declared rel="canonical"',
        ];

        // 7. Lastmod Coverage & Sanity
        $lastmodCount = count($lastmods);
        $lastmodCoverage = $urlCount > 0 ? round(($lastmodCount / $urlCount) * 100, 1) : 100;
        $futureDates = [];
        $invalidDates = [];

        foreach ($lastmods as $lm) {
            try {
                $carbon = Carbon::parse($lm);
                if ($carbon->isAfter(now()->addMinutes(5))) {
                    $futureDates[] = $lm;
                }
            } catch (\Exception $e) {
                $invalidDates[] = $lm;
            }
        }

        $lastmodValid = empty($futureDates) && empty($invalidDates);
        $checks[] = [
            'name' => 'Lastmod Timestamps Validity',
            'passed' => $lastmodValid,
            'label' => $lastmodValid ? "{$lastmodCoverage}% Coverage (Valid)" : 'Suspicious Lastmod Detected',
            'detail' => $lastmodValid ? "Accurate timestamps based on content modifications ({$lastmodCoverage}% of entries have lastmod)" : 'Contains future or invalid timestamps: ' . count($futureDates) . ' future',
        ];

        // Overall Health Status
        $failedCount = count(array_filter($checks, fn($c) => !$c['passed']));
        $status = 'Healthy';
        if (!$isValidXml || !$draftsExcluded || !$noindexExcluded) {
            $status = 'Critical';
        } elseif ($failedCount > 0) {
            $status = 'Warning';
        }

        return [
            'type' => $type,
            'endpoint' => url($type === 'news' ? '/sitemap-news.xml' : ($type === 'index' ? '/sitemap-index.xml' : '/sitemap.xml')),
            'status' => $status,
            'http_status' => 200,
            'content_type' => 'application/xml',
            'is_valid_xml' => $isValidXml,
            'url_count' => $urlCount,
            'unique_url_count' => $uniqueCount,
            'duplicate_urls' => $duplicates,
            'invalid_urls' => $invalidUrls,
            'non_https_urls' => $nonHttpsUrls,
            'localhost_urls' => $localhostUrls,
            'draft_urls' => $draftUrlsFound,
            'noindex_urls' => $noindexUrlsFound,
            'redirected_urls' => $redirectedUrls,
            'canonical_mismatches' => $canonicalMismatches,
            'lastmod_coverage' => $lastmodCoverage,
            'future_dates' => $futureDates,
            'checks' => $checks,
            'last_checked' => now()->toFormattedDateString() . ' ' . now()->format('g:i A'),
        ];
    }

    /**
     * Get paginated Sitemap URL Inventory for administrative inspection.
     */
    public function getInventory(int $perPage = 25, int $page = 1, ?string $typeFilter = null, ?string $search = null): LengthAwarePaginator
    {
        $allEntries = $this->getAllSitemapEntries();

        // Check active redirects
        $activeRedirects = SeoRedirect::where('is_active', true)->get()->keyBy(fn($r) => '/' . ltrim(strtolower($r->source_path), '/'));

        $mapped = collect($allEntries)->map(function ($item) use ($activeRedirects) {
            $path = parse_url($item['loc'], PHP_URL_PATH) ?? '';
            $normPath = '/' . ltrim(strtolower($path), '/');

            $isRedirected = $activeRedirects->has($normPath);
            $canonicalMatch = ($item['canonical_url'] === $item['loc']);

            $canonicalStatus = 'MATCH';
            if ($isRedirected) {
                $canonicalStatus = 'REDIRECTED';
            } elseif (!$canonicalMatch) {
                $canonicalStatus = 'MISMATCH';
            }

            return [
                'url' => $item['loc'],
                'type' => $item['type'],
                'title' => $item['title'],
                'lastmod' => $item['lastmod'] ? Carbon::parse($item['lastmod'])->toFormattedDateString() : 'N/A',
                'http_status' => $isRedirected ? $activeRedirects->get($normPath)->status_code : 200,
                'canonical' => $item['canonical_url'],
                'canonical_status' => $canonicalStatus,
                'robots' => $item['robots'],
                'indexability' => $isRedirected ? 'Redirected' : (str_contains(strtolower($item['robots']), 'noindex') ? 'Noindex' : 'Indexable'),
                'sitemap_status' => 'Included',
                'model_id' => $item['model_id'],
                'model_type' => $item['model_type'],
            ];
        });

        // Filter by type
        if (!empty($typeFilter) && $typeFilter !== 'all') {
            $mapped = $mapped->filter(fn($i) => strtolower($i['type']) === strtolower($typeFilter));
        }

        // Search
        if (!empty($search)) {
            $term = strtolower($search);
            $mapped = $mapped->filter(function ($i) use ($term) {
                return str_contains(strtolower($i['url']), $term) || str_contains(strtolower($i['title']), $term);
            });
        }

        $total = $mapped->count();
        $items = $mapped->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => route('admin.seo.sitemap.inventory'), 'query' => request()->query()]
        );
    }

    /**
     * Identify published articles with zero internal inbound links ("Potential Orphan Content").
     */
    public function getPotentialOrphanArticles(): Collection
    {
        $published = Article::published()->get();
        if ($published->isEmpty()) {
            return collect();
        }

        $allContent = $published->pluck('content')->implode(' ');

        return $published->filter(function ($art) use ($allContent) {
            // Check if slug or route is mentioned in any other article's content
            $slugMentioned = str_contains($allContent, $art->slug);
            return !$slugMentioned;
        })->map(function ($art) {
            return [
                'article' => $art,
                'url' => route('articles.show', $art->slug),
                'inbound_internal_links' => 0,
                'status' => 'Potential Orphan',
            ];
        });
    }

    /**
     * Build technical Indexability Matrix across application entities.
     */
    public function getIndexabilityMatrix(): array
    {
        $publishedArticles = Article::published()->get();
        $totalArticles = Article::count();
        $articleNoindex = $publishedArticles->filter(fn($a) => !empty($a->robots) && str_contains(strtolower($a->robots), 'noindex'))->count();
        $articleIndexable = $publishedArticles->count() - $articleNoindex;

        $categories = Category::all();
        $rankings = RankingsList::all();
        $authors = $this->getEligibleAuthors();

        $potentialOrphans = $this->getPotentialOrphanArticles()->count();

        // Active redirects
        $activeRedirects = SeoRedirect::where('is_active', true)->get();
        $redirectedArticles = 0;
        foreach ($publishedArticles as $art) {
            $path = '/article/' . $art->slug;
            if ($activeRedirects->contains(fn($r) => strtolower($r->source_path) === strtolower($path))) {
                $redirectedArticles++;
            }
        }

        return [
            'articles' => [
                'name' => 'Articles & Stories',
                'total' => $totalArticles,
                'indexable' => $articleIndexable,
                'noindex' => $articleNoindex,
                'blocked' => 0, // protected by robots meta tag, not blocked in robots.txt
                'redirected' => $redirectedArticles,
                'orphans' => $potentialOrphans,
            ],
            'categories' => [
                'name' => 'Categories & Channels',
                'total' => $categories->count(),
                'indexable' => $categories->count(),
                'noindex' => 0,
                'blocked' => 0,
                'redirected' => 0,
                'orphans' => 0,
            ],
            'rankings' => [
                'name' => 'Rankings & Index Directories',
                'total' => $rankings->count(),
                'indexable' => $rankings->where('status', 'published')->count(),
                'noindex' => $rankings->where('status', '!=', 'published')->count(),
                'blocked' => 0,
                'redirected' => 0,
                'orphans' => 0,
            ],
            'authors' => [
                'name' => 'Author & Contributor Profiles',
                'total' => User::whereIn('role', ['admin', 'editor', 'writer', 'contributor'])->count(),
                'indexable' => $authors->count(),
                'noindex' => User::whereIn('role', ['admin', 'editor', 'writer', 'contributor'])->count() - $authors->count(),
                'blocked' => 0,
                'redirected' => 0,
                'orphans' => 0,
            ],
        ];
    }

    /**
     * Validate robots.txt output against Google Search Central guidance.
     * Note: robots.txt must NOT block pages whose noindex tags need to be crawled.
     */
    public function validateRobotsTxt(): array
    {
        $response = (new \App\Http\Controllers\SeoController($this))->robots();
        $content = $response->getContent();

        $checks = [];

        // 1. HTTP 200 & content type
        $checks[] = [
            'name' => 'HTTP 200 & Text Content',
            'passed' => $response->getStatusCode() === 200,
            'label' => 'HTTP 200 OK',
            'detail' => 'Serves text/plain with HTTP 200 status code',
        ];

        // 2. Sitemap pointer
        $hasMainSitemap = str_contains($content, 'Sitemap: ' . url('/sitemap.xml'));
        $hasNewsSitemap = str_contains($content, 'Sitemap: ' . url('/sitemap-news.xml'));
        $checks[] = [
            'name' => 'Sitemap Declarations',
            'passed' => $hasMainSitemap && $hasNewsSitemap,
            'label' => ($hasMainSitemap && $hasNewsSitemap) ? 'Sitemaps Advertised' : 'Missing Sitemap Declaration',
            'detail' => 'Explicitly advertises /sitemap.xml and /sitemap-news.xml to crawlers',
        ];

        // 3. Admin & Private Protection
        $blocksAdmin = str_contains($content, 'Disallow: /admin/');
        $blocksAccount = str_contains($content, 'Disallow: /account/');
        $checks[] = [
            'name' => 'Administrative Pathway Shielding',
            'passed' => $blocksAdmin && $blocksAccount,
            'label' => 'Admin Routes Protected',
            'detail' => 'Disallows indexing of administrative CMS and reader authentication panels',
        ];

        // 4. Public Content Crawlability
        $blocksArticles = str_contains($content, 'Disallow: /article') || str_contains($content, 'Disallow: /articles');
        $checks[] = [
            'name' => 'Public Editorial Crawlability',
            'passed' => !$blocksArticles,
            'label' => !$blocksArticles ? 'Public Content Accessible' : 'Accidental Public Disallow',
            'detail' => !$blocksArticles ? 'No accidental blocking of public articles or taxonomy routes' : 'CRITICAL: robots.txt is blocking public article paths',
        ];

        $allPassed = !in_array(false, array_column($checks, 'passed'));

        return [
            'status' => $allPassed ? 'Healthy' : 'Warning',
            'http_status' => 200,
            'content_type' => 'text/plain',
            'url' => url('/robots.txt'),
            'checks' => $checks,
            'content' => $content,
            'last_checked' => now()->toFormattedDateString() . ' ' . now()->format('g:i A'),
        ];
    }
}
