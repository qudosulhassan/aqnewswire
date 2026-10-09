<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_dashboard_renders_redesigned_components_and_kpis(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'name' => 'Alexander Vance']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('AQ NEWSWIRE');
        $response->assertSee('EDITORIAL CMS');
        $response->assertSee('Total Articles');
        $response->assertSee('Published');
        $response->assertSee('Pageviews');
        $response->assertSee('Newsletter Subscribers');
        $response->assertSee('Active Contributors');
        $response->assertSee('Website Traffic');
        $response->assertSee('Content Pipeline');
        $response->assertSee('Quick Actions');
        $response->assertSee('Recent Articles');
        $response->assertSee('Top Performing Articles');
        $response->assertSee('Editorial Activity');
        $response->assertSee('Newsroom Platform:');
        $response->assertSee('Ctrl + K');
    }

    public function test_admin_articles_management_renders_modern_filters(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.articles.index'));

        $response->assertOk();
        $response->assertSee('All Stories');
        $response->assertSee('Published');
        $response->assertSee('Drafts');
        $response->assertSee('In Review');
        $response->assertSee('Write New Story');
    }

    public function test_admin_article_create_and_edit_render_two_column_workspace(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
        $article = Article::first();

        $createResponse = $this->actingAs($admin)->get(route('admin.articles.create'));
        $createResponse->assertOk();
        $createResponse->assertSee('Publishing Controls');
        $createResponse->assertSee('Primary News Channel');

        if ($article) {
            $editResponse = $this->actingAs($admin)->get(route('admin.articles.edit', $article));
            $editResponse->assertOk();
            $editResponse->assertSee('Internal Editorial Feedback');
            $editResponse->assertSee('Publishing Controls');
        }
    }
}
