<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicArticlePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_published_article_renders_successfully(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $response = $this->get('/' . $article->category->slug . '/' . $article->slug);

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee('article-content font-serif', false);
        $response->assertSee('font-headline', false);
        $response->assertSee($article->category->name);
        $response->assertSee($article->author->name);
    }

    public function test_legacy_article_url_returns_404_without_redirect(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $response = $this->get('/article/' . $article->slug);

        $response->assertStatus(404);
    }

    public function test_non_existent_article_returns_404(): void
    {
        $response = $this->get('/news/non-existent-article-xyz-404');
        $response->assertStatus(404);
    }

    public function test_draft_article_is_hidden_from_public_guests(): void
    {
        $author = User::where('role', 'admin')->first();
        $category = Category::first();

        $draft = Article::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Confidential Internal Draft Article',
            'slug' => 'confidential-internal-draft-article',
            'content' => '<p>This should not be publicly accessible.</p>',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/' . $category->slug . '/' . $draft->slug);
        $response->assertStatus(404);
    }

    public function test_staff_author_can_preview_draft_article(): void
    {
        $author = User::where('role', 'admin')->first();
        $category = Category::first();

        $draft = Article::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Confidential Internal Draft Article',
            'slug' => 'confidential-internal-draft-article',
            'content' => '<p>Staff preview content test.</p>',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->actingAs($author)->get('/' . $category->slug . '/' . $draft->slug);
        $response->assertStatus(200);
        $response->assertSee('Staff Preview');
        $response->assertSee('Confidential Internal Draft Article');
    }

    public function test_scheduled_article_is_hidden_until_published_at(): void
    {
        $author = User::where('role', 'admin')->first();
        $category = Category::first();

        $future = Article::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Future Scheduled Article',
            'slug' => 'future-scheduled-article',
            'content' => '<p>Scheduled for next month.</p>',
            'status' => 'published',
            'published_at' => now()->addDays(7),
        ]);

        // Guest gets 404
        $response = $this->get('/' . $category->slug . '/' . $future->slug);
        $response->assertStatus(404);

        // Staff author can preview
        $responseAuth = $this->actingAs($author)->get('/' . $category->slug . '/' . $future->slug);
        $responseAuth->assertStatus(200);
    }

    public function test_article_page_contains_seo_tags_and_json_ld(): void
    {
        $article = Article::published()->first();

        $response = $this->get('/' . $article->category->slug . '/' . $article->slug);

        $response->assertStatus(200);
        // Canonical tag
        $response->assertSee('<link rel="canonical" href="' . $article->effective_canonical_url . '">', false);
        // OpenGraph tags
        $response->assertSee('<meta property="og:type" content="article">', false);
        // JSON-LD NewsArticle & BreadcrumbList
        $response->assertSee('"@context": "https://schema.org"', false);
        $response->assertSee('"@type": "NewsArticle"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
    }

    public function test_article_page_renders_interactive_widgets_and_right_rail(): void
    {
        $article = Article::published()->first();

        $response = $this->get('/' . $article->category->slug . '/' . $article->slug);

        $response->assertStatus(200);
        // Audio Player
        $response->assertSee('Listen to this Story');
        // Author card
        $response->assertSee('View Portfolio &rarr;', false);
        // Discussion
        $response->assertSee('Executive Discussion');
        // Trending Right Now
        $response->assertSee('Trending Right Now');
        // Channel Briefing
        $response->assertSee('Channel Briefing');
    }
}
