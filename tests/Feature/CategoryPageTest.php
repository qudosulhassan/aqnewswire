<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }
    public function test_category_page_renders_successfully_for_valid_category(): void
    {
        $category = Category::where('slug', 'markets')->first();
        if (! $category) {
            $category = Category::create([
                'name' => 'Markets',
                'slug' => 'markets',
                'description' => null,
            ]);
        }

        $response = $this->get('/markets');

        $response->assertStatus(200);
        $response->assertSee('Markets');
        $response->assertSee('Channel Beat');
        $response->assertSee('AQ NEWSWIRE');
    }

    public function test_legacy_category_url_returns_404_without_redirect(): void
    {
        $response = $this->get('/category/markets');

        $response->assertStatus(404);
    }

    public function test_non_existent_category_returns_404(): void
    {
        $response = $this->get('/non-existent-channel-xyz');

        $response->assertStatus(404);
    }

    public function test_category_filters_latest_most_read_and_trending(): void
    {
        $responseLatest = $this->get('/markets?filter=latest');
        $responseLatest->assertStatus(200);

        $responseMostRead = $this->get('/markets?filter=most-read');
        $responseMostRead->assertStatus(200);

        $responseTrending = $this->get('/markets?filter=trending');
        $responseTrending->assertStatus(200);
    }

    public function test_category_page_contains_seo_metadata_and_json_ld(): void
    {
        $response = $this->get('/markets');

        $response->assertStatus(200);
        // Canonical tag
        $response->assertSee('<link rel="canonical" href="' . url('/markets') . '">', false);
        // JSON-LD structured data
        $response->assertSee('"@context": "https://schema.org"', false);
        $response->assertSee('"@type": "CollectionPage"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
    }

    public function test_category_page_renders_channel_briefing_and_editorial_rhythm(): void
    {
        $response = $this->get('/markets');

        $response->assertStatus(200);
        $response->assertSee('Channel Briefing');
        $response->assertSee('Most Read &middot; Markets', false);
        $response->assertSee('Submit Pitch');
    }

    public function test_category_display_description_fallback_and_custom(): void
    {
        $category = Category::where('slug', 'markets')->first();
        $this->assertNotEmpty($category->display_description);

        // Test custom description override
        $originalDesc = $category->description;
        $category->description = 'Custom bespoke editorial overview for markets.';
        $this->assertEquals('Custom bespoke editorial overview for markets.', $category->display_description);
        $category->description = $originalDesc;
    }

    public function test_newly_created_category_is_directly_accessible_without_redirect(): void
    {
        $category = Category::create([
            'name' => 'Space Economy',
            'slug' => 'space-economy',
            'description' => 'Commercial space exploration and satellite infrastructure.',
            'is_nav_visible' => true,
        ]);

        $response = $this->get('/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee('Space Economy');
        $this->assertEquals(0, \App\Models\SeoRedirect::where('source_path', '/' . $category->slug)->count());
    }
}
