<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('AQ NEWSWIRE');
    }

    public function test_article_page_returns_200(): void
    {
        $article = Article::published()->first();
        $response = $this->get('/' . ($article->category?->slug ?? 'news') . '/' . $article->slug);
        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    public function test_category_page_returns_200(): void
    {
        $category = Category::first();
        $response = $this->get('/' . $category->slug);
        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    public function test_rankings_page_returns_200(): void
    {
        $response = $this->get('/rankings');
        $response->assertStatus(200);
        $response->assertSee('The AQ NEWSWIRE Lists');
    }

    public function test_search_page_returns_200(): void
    {
        $response = $this->get('/search?q=AI');
        $response->assertStatus(200);
    }

    public function test_admin_login_page_returns_200(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Editorial Sign In');
    }

    public function test_admin_dashboard_requires_auth(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Editorial Operations Dashboard');
    }
}
