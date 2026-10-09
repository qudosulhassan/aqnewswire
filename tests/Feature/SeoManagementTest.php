<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\SeoRedirect;
use App\Models\User;
use App\Services\SeoAuditService;
use App\Services\SeoRedirectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $writer;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->writer = User::factory()->create([
            'role' => 'writer',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
            'description' => 'Technology coverage',
        ]);
    }

    public function test_seo_dashboard_loads_for_admin(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.seo.index'));

        $response->assertStatus(200);
        $response->assertSee('SEO Architecture & Discovery Center');
        $response->assertSee('Indexable Stories');
        $response->assertSee('Missing Descriptions');
        $response->assertSee('Search Engine Crawler Discovery Feeds');
    }

    public function test_kpi_counts_and_missing_metadata_detection(): void
    {
        // 1. Fully customized article
        Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Optimized Story Headline',
            'slug' => 'optimized-story-headline',
            'content' => 'Full story content body...',
            'excerpt' => 'Story excerpt...',
            'status' => 'published',
            'meta_title' => 'Custom Optimized Title | Apex Media',
            'meta_description' => 'A custom description crafted for search engine result pages.',
            'canonical_url' => 'https://apexmedia.com/article/optimized-story-headline',
            'featured_image' => 'https://images.unsplash.com/test.jpg',
            'robots' => 'index, follow, max-image-preview:large',
            'published_at' => now(),
        ]);

        // 2. Story with missing title and description
        Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Unoptimized Story Headline',
            'slug' => 'unoptimized-story-headline',
            'content' => 'Full story content body...',
            'excerpt' => 'Story excerpt...',
            'status' => 'published',
            'meta_title' => null,
            'meta_description' => null,
            'canonical_url' => null,
            'featured_image' => null,
            'robots' => null,
            'published_at' => now(),
        ]);

        // 3. Draft story (must not count towards indexable KPIs)
        Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Draft Unfinished Story',
            'slug' => 'draft-unfinished-story',
            'content' => 'Draft body...',
            'status' => 'draft',
            'published_at' => null,
        ]);

        // 4. Create one 301 redirect
        SeoRedirect::create([
            'source_path' => '/old-story-url',
            'destination_url' => '/category/technology',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.seo.index'));

        $response->assertStatus(200);
        // 2 published stories, 1 missing description, 1 missing title, 1 active redirect
        $response->assertViewHas('totalArticles', 2);
        $response->assertViewHas('indexableStories', 2);
        $response->assertViewHas('missingMetaDescription', 1);
        $response->assertViewHas('missingMetaTitle', 1);
        $response->assertViewHas('missingCanonical', 1);
        $response->assertViewHas('active301', \App\Models\SeoRedirect::where('is_active', true)->where('status_code', 301)->count());
    }

    public function test_sitemap_returns_valid_xml_and_excludes_drafts_and_noindex(): void
    {
        // Published indexable story
        $indexable = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Public Story In Sitemap',
            'slug' => 'public-story-in-sitemap',
            'content' => 'Story content...',
            'status' => 'published',
            'published_at' => now(),
            'robots' => 'index, follow',
        ]);

        // Draft story
        $draft = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Draft Excluded From Sitemap',
            'slug' => 'draft-excluded-from-sitemap',
            'content' => 'Draft content...',
            'status' => 'draft',
            'published_at' => null,
        ]);

        // Noindex published story
        $noindex = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Private Noindex Story',
            'slug' => 'private-noindex-story',
            'content' => 'Noindex content...',
            'status' => 'published',
            'published_at' => now(),
            'robots' => 'noindex, follow',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('/public-story-in-sitemap');
        $response->assertDontSee('/draft-excluded-from-sitemap');
        $response->assertDontSee('/private-noindex-story');
    }

    public function test_google_news_sitemap_returns_valid_xml(): void
    {
        Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Breaking News Within 48 Hours',
            'slug' => 'breaking-news-within-48-hours',
            'content' => 'News content...',
            'status' => 'published',
            'published_at' => now()->subHours(2),
        ]);

        $response = $this->get('/sitemap-news.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"', false);
        $response->assertSee('Breaking News Within 48 Hours');
    }

    public function test_robots_txt_returns_proper_directives_and_blocks_admin(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Disallow: /account/');
        $response->assertSee('Disallow: /contributor/');
        $response->assertSee('Allow: /');
        $response->assertSee('Sitemap: ' . url('/sitemap.xml'));
        $response->assertSee('Sitemap: ' . url('/sitemap-news.xml'));
    }

    public function test_301_and_302_redirects_execute_correctly(): void
    {
        SeoRedirect::create([
            'source_path' => '/legacy-page-301',
            'destination_url' => '/category/technology',
            'status_code' => 301,
            'is_active' => true,
        ]);

        SeoRedirect::create([
            'source_path' => '/temporary-promo-302',
            'destination_url' => '/category/technology',
            'status_code' => 302,
            'is_active' => true,
        ]);

        $res301 = $this->get('/legacy-page-301');
        $res301->assertRedirect('/category/technology');
        $res301->assertStatus(301);

        $res302 = $this->get('/temporary-promo-302');
        $res302->assertRedirect('/category/technology');
        $res302->assertStatus(302);
    }

    public function test_self_redirect_and_circular_loops_are_rejected(): void
    {
        $service = app(SeoRedirectService::class);

        // Self redirect
        $check1 = $service->validateRedirect('/some-path', '/some-path');
        $this->assertFalse($check1['valid']);
        $this->assertStringContainsString('Self-redirect detected', $check1['error']);

        // Direct circular loop A -> B, B -> A
        SeoRedirect::create([
            'source_path' => '/route-a',
            'destination_url' => '/route-b',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $check2 = $service->validateRedirect('/route-b', '/route-a');
        $this->assertFalse($check2['valid']);
        $this->assertStringContainsString('Redirect loop detected', $check2['error']);
    }

    public function test_redirect_chains_are_detected(): void
    {
        $service = app(SeoRedirectService::class);

        SeoRedirect::create([
            'source_path' => '/route-intermediate',
            'destination_url' => '/route-final',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $check = $service->validateRedirect('/route-start', '/route-intermediate');
        $this->assertTrue($check['valid']);
        $this->assertNotNull($check['warning']);
        $this->assertStringContainsString('Redirect chain detected', $check['warning']);
    }

    public function test_admin_can_create_update_toggle_and_delete_redirect(): void
    {
        // 1. Create redirect
        $createRes = $this->actingAs($this->admin)->post(route('admin.seo.redirects.store'), [
            'source_path' => '/old-editorial',
            'destination_url' => '/category/technology',
            'status_code' => 301,
        ]);
        $createRes->assertRedirect(route('admin.seo.redirects'));
        $this->assertDatabaseHas('seo_redirects', [
            'source_path' => '/old-editorial',
            'destination_url' => '/category/technology',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $redirect = SeoRedirect::where('source_path', '/old-editorial')->first();

        // 2. Toggle status
        $this->actingAs($this->admin)->post(route('admin.seo.redirects.toggle', $redirect));
        $this->assertDatabaseHas('seo_redirects', [
            'id' => $redirect->id,
            'is_active' => false,
        ]);

        // 3. Update redirect
        $this->actingAs($this->admin)->put(route('admin.seo.redirects.update', $redirect), [
            'source_path' => '/old-editorial-updated',
            'destination_url' => '/category/business',
            'status_code' => 302,
            'is_active' => 1,
        ]);
        $this->assertDatabaseHas('seo_redirects', [
            'id' => $redirect->id,
            'source_path' => '/old-editorial-updated',
            'destination_url' => '/category/business',
            'status_code' => 302,
        ]);

        // 4. Delete redirect
        $this->actingAs($this->admin)->delete(route('admin.seo.redirects.destroy', $redirect));
        $this->assertDatabaseMissing('seo_redirects', ['id' => $redirect->id]);
    }

    public function test_unauthorized_user_cannot_manage_seo(): void
    {
        $response = $this->actingAs($this->writer)->get(route('admin.seo.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_update_article_seo_metadata_and_it_renders_in_public_html(): void
    {
        $article = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Original Story Headline Title',
            'slug' => 'original-story-headline-title',
            'content' => 'Comprehensive editorial investigation content...',
            'excerpt' => 'Default excerpt...',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 1. Update SEO metadata via Admin
        $updateRes = $this->actingAs($this->admin)->put(route('admin.seo.articles.update', $article), [
            'meta_title' => 'Bespoke Search Engine Title Tag | Apex Media',
            'meta_description' => 'A precision meta description that increases click-through rates in organic search results.',
            'canonical_url' => 'https://apexmedia.com/article/original-story-headline-title',
            'robots' => 'index, follow, max-image-preview:large',
            'og_title' => 'Custom Open Graph Social Title',
            'og_description' => 'Custom Open Graph social description for LinkedIn and X.',
            'og_image' => 'https://images.unsplash.com/custom-og.jpg',
            'twitter_title' => 'Custom Twitter Card Title',
            'twitter_description' => 'Custom Twitter Card description.',
            'twitter_image' => 'https://images.unsplash.com/custom-og.jpg',
        ]);

        $updateRes->assertRedirect(route('admin.seo.articles'));

        // 2. Cross-check against public HTML rendering
        $htmlRes = $this->get(route('articles.show', $article->slug));
        $htmlRes->assertStatus(200);

        // Verify Title tag
        $htmlRes->assertSee('<title>Bespoke Search Engine Title Tag | Apex Media</title>', false);

        // Verify Meta Description
        $htmlRes->assertSee('content="A precision meta description that increases click-through rates in organic search results."', false);

        // Verify Canonical
        $htmlRes->assertSee('<link rel="canonical" href="https://apexmedia.com/article/original-story-headline-title">', false);

        // Verify Robots
        $htmlRes->assertSee('content="index, follow, max-image-preview:large"', false);

        // Verify Open Graph
        $htmlRes->assertSee('property="og:title" content="Custom Open Graph Social Title"', false);
        $htmlRes->assertSee('property="og:description" content="Custom Open Graph social description for LinkedIn and X."', false);
        $htmlRes->assertSee('property="og:image" content="https://images.unsplash.com/custom-og.jpg"', false);

        // Verify Twitter Cards
        $htmlRes->assertSee('name="twitter:card" content="summary_large_image"', false);
        $htmlRes->assertSee('name="twitter:title" content="Custom Twitter Card Title"', false);

        // Verify JSON-LD Schema.org NewsArticle and BreadcrumbList
        $htmlRes->assertSee('"@type": "NewsArticle"', false);
        $htmlRes->assertSee('"@type": "BreadcrumbList"', false);
    }

    public function test_run_seo_audit_action_completes_successfully(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.seo.audit'));
        $response->assertRedirect(route('admin.seo.index'));
        $response->assertSessionHas('success');
    }

    public function test_sitemap_validator_action_returns_valid_checks(): void
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.seo.sitemap.validate', ['type' => 'main']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'endpoint',
            'status',
            'http_status',
            'content_type',
            'is_valid_xml',
            'url_count',
            'checks',
        ]);
        $this->assertTrue($response->json('is_valid_xml'));
        $this->assertEquals(200, $response->json('http_status'));
    }

    public function test_sitemap_inventory_action_returns_paginated_records(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.seo.sitemap.inventory'));

        $response->assertStatus(200);
        $response->assertSee('Sitemap URL Inventory');
        $response->assertViewHas('inventory');
        $response->assertViewHas('matrix');
    }

    public function test_sitemap_download_action_returns_xml_attachment(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.seo.sitemap.download', ['type' => 'main']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="sitemap.xml"');
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
    }

    public function test_scalable_sub_sitemaps_and_index_return_valid_xml(): void
    {
        // Articles sub-sitemap
        $artRes = $this->get(route('seo.articles_sitemap'));
        $artRes->assertStatus(200);
        $artRes->assertHeader('Content-Type', 'application/xml');
        $artRes->assertSee('<urlset', false);

        // Categories sub-sitemap
        $catRes = $this->get(route('seo.categories_sitemap'));
        $catRes->assertStatus(200);
        $catRes->assertHeader('Content-Type', 'application/xml');

        // Sitemap Index
        $indexRes = $this->get(route('seo.sitemap_index'));
        $indexRes->assertStatus(200);
        $indexRes->assertHeader('Content-Type', 'application/xml');
        $indexRes->assertSee('<sitemapindex', false);
        $indexRes->assertSee(url('/sitemap-articles.xml'));
    }

    public function test_robots_validation_action_returns_valid_checks(): void
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.seo.robots.validate'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'http_status',
            'content_type',
            'url',
            'checks',
        ]);
        $this->assertEquals('Healthy', $response->json('status'));
    }

    public function test_jsonld_viewer_endpoint_returns_valid_structured_data_graph(): void
    {
        $article = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Structured Data Story',
            'slug' => 'structured-data-story',
            'content' => 'Structured data content body...',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.seo.jsonld', $article));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '@context',
            '@graph',
        ]);
        $this->assertEquals('https://schema.org', $response->json('@context'));
    }

    public function test_potential_orphan_content_detection(): void
    {
        // Story 1: Lonely story never linked
        $orphan = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Orphan Island Story',
            'slug' => 'orphan-island-story',
            'content' => 'Stand alone content...',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Story 2: Links to story 3
        $linked = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Referenced Story Destination',
            'slug' => 'referenced-story-destination',
            'content' => 'Target content...',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $referrer = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Referring Article Story',
            'slug' => 'referring-article-story',
            'content' => 'Read our previous report at /article/referenced-story-destination for insights.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $auditService = app(\App\Services\SitemapService::class);
        $orphans = $auditService->getPotentialOrphanArticles();

        $this->assertTrue($orphans->contains(fn($o) => $o['article']->id === $orphan->id));
        $this->assertFalse($orphans->contains(fn($o) => $o['article']->id === $linked->id));
    }
}
