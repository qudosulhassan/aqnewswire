<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\SeoRedirect;
use App\Services\SeoAuditService;
use App\Services\SeoRedirectService;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminSeoController extends Controller
{
    public function __construct(
        protected SeoAuditService $auditService,
        protected SeoRedirectService $redirectService,
        protected SitemapService $sitemapService
    ) {}

    /**
     * Technical SEO Control Center Dashboard.
     */
    public function index()
    {
        // 1. Live Database-backed KPIs
        $totalArticles = Article::published()->count();
        $indexableStories = Article::indexable()->count();

        $missingMetaDescription = Article::published()
            ->where(fn($q) => $q->whereNull('meta_description')->orWhere('meta_description', ''))
            ->count();

        $missingMetaTitle = Article::published()
            ->where(fn($q) => $q->whereNull('meta_title')->orWhere('meta_title', ''))
            ->count();

        $missingCanonical = Article::published()
            ->where(fn($q) => $q->whereNull('canonical_url')->orWhere('canonical_url', ''))
            ->count();

        $missingFeaturedImage = Article::published()
            ->where(fn($q) => $q->whereNull('featured_image')->orWhere('featured_image', ''))
            ->count();

        $active301 = SeoRedirect::where('status_code', 301)->where('is_active', true)->count();
        $active302 = SeoRedirect::where('status_code', 302)->where('is_active', true)->count();

        // 2. Comprehensive Technical Audit Report
        $auditReport = $this->auditService->runAudit();

        // 3. Articles requiring attention preview (top 6)
        $flaggedArticles = Article::published()
            ->where(function ($q) {
                $q->whereNull('meta_description')
                  ->orWhere('meta_description', '')
                  ->orWhereNull('meta_title')
                  ->orWhere('meta_title', '')
                  ->orWhereNull('canonical_url')
                  ->orWhere('canonical_url', '')
                  ->orWhere(fn($sq) => $sq->whereNotNull('robots')->where('robots', 'like', '%noindex%'));
            })
            ->latest('updated_at')
            ->take(6)
            ->get();

        return view('admin.seo.index', compact(
            'totalArticles',
            'indexableStories',
            'missingMetaDescription',
            'missingMetaTitle',
            'missingCanonical',
            'missingFeaturedImage',
            'active301',
            'active302',
            'auditReport',
            'flaggedArticles'
        ));
    }

    /**
     * Execute "Run Full SEO Audit" on demand.
     */
    public function runAuditAction()
    {
        $auditReport = $this->auditService->runAudit();

        AuditLog::record(
            'seo_audit_executed',
            'SeoAudit',
            null,
            "SEO Audit completed: Score {$auditReport['score']}%, Status {$auditReport['overall_status']}, {$auditReport['missing_meta_descriptions']} missing descriptions, {$auditReport['main_sitemap_validation']['url_count']} sitemap URLs verified"
        );

        return redirect()->route('admin.seo.index')->with('success', "Full SEO Audit executed successfully. Health Score: {$auditReport['score']}% ({$auditReport['overall_status']}).");
    }

    /**
     * Validate XML Sitemap on demand (returns JSON for modal or redirect).
     */
    public function validateSitemapAction(Request $request)
    {
        $type = $request->get('type', 'main');
        $report = $this->sitemapService->validateSitemap($type);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($report);
        }

        return redirect()->route('admin.seo.index')->with('sitemap_validation', $report);
    }

    /**
     * Download generated XML Sitemap.
     */
    public function downloadSitemapAction(Request $request)
    {
        $type = $request->get('type', 'main');
        $xml = $this->sitemapService->generateXml($type);
        $filename = $type === 'news' ? 'sitemap-news.xml' : 'sitemap.xml';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Sitemap URL Inventory table with search, filter, and pagination.
     */
    public function sitemapInventory(Request $request)
    {
        $perPage = in_array((int) $request->get('per_page'), [25, 50, 100]) ? (int) $request->get('per_page') : 25;
        $page = (int) $request->get('page', 1);
        $typeFilter = $request->get('type');
        $search = $request->get('search');

        $inventory = $this->sitemapService->getInventory($perPage, $page, $typeFilter, $search);
        $matrix = $this->sitemapService->getIndexabilityMatrix();

        return view('admin.seo.sitemap_inventory', compact('inventory', 'matrix', 'typeFilter', 'search'));
    }

    /**
     * Validate robots.txt on demand (JSON or redirect).
     */
    public function validateRobotsAction(Request $request)
    {
        $report = $this->sitemapService->validateRobotsTxt();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($report);
        }

        return redirect()->route('admin.seo.index')->with('robots_validation', $report);
    }

    /**
     * View rendered JSON-LD structured data graph for inspection.
     */
    public function viewJsonLdAction(Request $request, ?Article $article = null)
    {
        if (!$article) {
            $article = Article::published()->latest('published_at')->first();
        }

        if (!$article) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'NewsMediaOrganization',
                        'name' => config('app.name', 'AQ NEWSWIRE International Business & Leadership'),
                        'url' => url('/'),
                        'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
                    ],
                ],
            ]);
        }

        $graph = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'NewsArticle',
                    'headline' => $article->effective_meta_title,
                    'description' => $article->effective_meta_description,
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $article->effective_canonical_url,
                    ],
                    'datePublished' => ($article->published_at ?? now())->toAtomString(),
                    'dateModified' => ($article->updated_at ?? now())->toAtomString(),
                    'image' => [$article->image_url],
                    'author' => [
                        '@type' => 'Person',
                        'name' => $article->author?->name ?? 'AQ NEWSWIRE Editorial Board',
                        'url' => $article->author?->slug ? route('authors.show', $article->author->slug) : url('/'),
                    ],
                    'publisher' => [
                        '@type' => 'NewsMediaOrganization',
                        'name' => config('app.name', 'AQ NEWSWIRE International Business & Leadership'),
                        'url' => url('/'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('images/logo.png'),
                        ],
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Home',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $article->category?->name ?? 'Intelligence',
                            'item' => $article->category?->slug ? route('categories.show', $article->category->slug) : url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => $article->title,
                            'item' => route('articles.show', $article->slug),
                        ],
                    ],
                ],
            ],
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($graph, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        return response('<pre style="background:#0f172a;color:#f8fafc;padding:20px;border-radius:12px;font-family:monospace;font-size:12px;overflow:auto;">' . htmlspecialchars(json_encode($graph, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>');
    }

    /**
     * Content SEO Audit view with server-side search, filtering, and pagination.
     */
    public function articles(Request $request)
    {
        $query = Article::with(['author', 'category']);

        // Filter by publication state
        $publishedFilter = $request->get('status', 'published');
        if ($publishedFilter === 'published') {
            $query->published();
        } elseif ($publishedFilter === 'draft') {
            $query->where('status', 'draft');
        } elseif ($publishedFilter === 'all') {
            // all statuses
        }

        // Search headline or slug
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // SEO Problem Filter
        $filter = $request->get('filter');
        if ($filter === 'missing_desc') {
            $query->where(fn($q) => $q->whereNull('meta_description')->orWhere('meta_description', ''));
        } elseif ($filter === 'missing_title') {
            $query->where(fn($q) => $q->whereNull('meta_title')->orWhere('meta_title', ''));
        } elseif ($filter === 'missing_canonical') {
            $query->where(fn($q) => $q->whereNull('canonical_url')->orWhere('canonical_url', ''));
        } elseif ($filter === 'missing_image') {
            $query->where(fn($q) => $q->whereNull('featured_image')->orWhere('featured_image', ''));
        } elseif ($filter === 'noindex') {
            $query->whereNotNull('robots')->where('robots', 'like', '%noindex%');
        } elseif ($filter === 'optimal') {
            $query->whereNotNull('meta_description')->where('meta_description', '!=', '')
                  ->whereNotNull('meta_title')->where('meta_title', '!=', '')
                  ->whereNotNull('canonical_url')->where('canonical_url', '!=', '');
        }

        // Safe sorting
        $sort = $request->get('sort', 'updated_at');
        $direction = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['title', 'created_at', 'updated_at', 'published_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest('updated_at');
        }

        $perPage = in_array((int) $request->get('per_page'), [25, 50, 100]) ? (int) $request->get('per_page') : 25;
        $articles = $query->paginate($perPage)->withQueryString();

        return view('admin.seo.articles', compact('articles'));
    }

    /**
     * Dedicated Article SEO Editor.
     */
    public function editArticleSeo(Article $article)
    {
        $article->load(['author', 'category']);
        return view('admin.seo.article_edit', compact('article'));
    }

    /**
     * Update Article SEO metadata.
     */
    public function updateArticleSeo(Request $request, Article $article)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => ['nullable', 'string', Rule::in([
                'index, follow, max-image-preview:large',
                'index, follow',
                'noindex, follow',
                'index, nofollow',
                'noindex, nofollow',
            ])],
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:255',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|string|max:255',
        ]);

        $article->update($validated);

        AuditLog::record(
            'article_seo_updated',
            'Article',
            $article->id,
            "Updated SEO metadata for story '{$article->title}'"
        );

        return redirect()->route('admin.seo.articles')->with('success', "SEO metadata for '{$article->title}' updated successfully.");
    }

    /**
     * Manage 301/302 Redirects.
     */
    public function redirects(Request $request)
    {
        $query = SeoRedirect::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('source_path', 'like', "%{$search}%")
                  ->orWhere('destination_url', 'like', "%{$search}%");
            });
        }

        if ($statusCode = $request->get('status_code')) {
            $query->where('status_code', (int) $statusCode);
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $redirects = $query->latest()->paginate(25)->withQueryString();
        $auditNetwork = $this->redirectService->auditRedirectNetwork();

        return view('admin.seo.redirects', compact('redirects', 'auditNetwork'));
    }

    /**
     * Store new Redirect with loop & chain checks.
     */
    public function storeRedirect(Request $request)
    {
        $validated = $request->validate([
            'source_path' => 'required|string|max:255',
            'destination_url' => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
        ]);

        $check = $this->redirectService->validateRedirect(
            $validated['source_path'],
            $validated['destination_url']
        );

        if (!$check['valid']) {
            return back()->withInput()->withErrors(['destination_url' => $check['error']]);
        }

        $sourcePath = $check['normalized_source'];

        // Prevent duplicate source path
        if (SeoRedirect::where('source_path', $sourcePath)->exists()) {
            return back()->withInput()->withErrors(['source_path' => 'A redirect rule for this source path already exists.']);
        }

        $redirect = SeoRedirect::create([
            'source_path' => $sourcePath,
            'destination_url' => $check['normalized_destination'],
            'status_code' => (int) $validated['status_code'],
            'is_active' => true,
        ]);

        AuditLog::record(
            'seo_redirect_created',
            'SeoRedirect',
            $redirect->id,
            "Created {$redirect->status_code} redirect {$redirect->source_path} -> {$redirect->destination_url}"
        );

        $msg = "URL redirect ({$redirect->status_code}) created successfully.";
        if ($check['warning']) {
            $msg .= ' ' . $check['warning'];
        }

        return redirect()->route('admin.seo.redirects')->with('success', $msg);
    }

    /**
     * Update an existing redirect.
     */
    public function updateRedirect(Request $request, SeoRedirect $redirect)
    {
        $validated = $request->validate([
            'source_path' => 'required|string|max:255',
            'destination_url' => 'required|string|max:500',
            'status_code' => 'required|in:301,302',
            'is_active' => 'nullable|boolean',
        ]);

        $check = $this->redirectService->validateRedirect(
            $validated['source_path'],
            $validated['destination_url'],
            $redirect->id
        );

        if (!$check['valid']) {
            return back()->withInput()->withErrors(['destination_url' => $check['error']]);
        }

        $sourcePath = $check['normalized_source'];

        // Prevent duplicate with another record
        if (SeoRedirect::where('source_path', $sourcePath)->where('id', '!=', $redirect->id)->exists()) {
            return back()->withInput()->withErrors(['source_path' => 'Another redirect already uses this source path.']);
        }

        $redirect->update([
            'source_path' => $sourcePath,
            'destination_url' => $check['normalized_destination'],
            'status_code' => (int) $validated['status_code'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record(
            'seo_redirect_updated',
            'SeoRedirect',
            $redirect->id,
            "Updated redirect {$redirect->source_path} -> {$redirect->destination_url}"
        );

        return redirect()->route('admin.seo.redirects')->with('success', "Redirect #{$redirect->id} updated successfully.");
    }

    /**
     * Toggle active/inactive state of a redirect.
     */
    public function toggleRedirect(SeoRedirect $redirect)
    {
        $redirect->is_active = !$redirect->is_active;
        $redirect->save();

        AuditLog::record(
            'seo_redirect_toggled',
            'SeoRedirect',
            $redirect->id,
            "Toggled redirect {$redirect->source_path} to " . ($redirect->is_active ? 'Active' : 'Inactive')
        );

        $statusLabel = $redirect->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Redirect {$redirect->source_path} is now {$statusLabel}.");
    }

    /**
     * Test a path against current active redirects.
     */
    public function testRedirect(Request $request)
    {
        $testPath = '/' . ltrim(trim($request->input('test_path', '')), '/');
        $rule = SeoRedirect::where('is_active', true)
            ->where(function ($q) use ($testPath) {
                $q->where('source_path', $testPath)
                  ->orWhere('source_path', ltrim($testPath, '/'));
            })
            ->first();

        if ($rule) {
            return back()->with('test_result', [
                'matched' => true,
                'source' => $testPath,
                'destination' => $rule->destination_url,
                'status_code' => $rule->status_code,
                'hits' => $rule->hit_count,
            ]);
        }

        return back()->with('test_result', [
            'matched' => false,
            'source' => $testPath,
            'message' => 'No active redirect matched this source path.',
        ]);
    }

    /**
     * Delete redirect.
     */
    public function destroyRedirect(SeoRedirect $redirect)
    {
        AuditLog::record('seo_redirect_deleted', 'SeoRedirect', $redirect->id, "Deleted redirect {$redirect->source_path}");
        $redirect->delete();

        return back()->with('success', 'URL redirect removed successfully.');
    }
}
