<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContributorApplication;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\SeoRedirect;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Phase2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Jonathan Sterling',
            'email' => 'jonathan@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'jonathan@example.com',
            'role' => 'reader',
        ]);
    }

    public function test_public_user_can_login_and_logout(): void
    {
        $user = User::create([
            'name' => 'Jane Reader',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $loginResponse = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect('/account');
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_user_can_bookmark_and_unbookmark_article(): void
    {
        $user = User::where('role', 'reader')->first() ?? User::create([
            'name' => 'Bookmark Reader',
            'email' => 'bm@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $article = Article::first();

        // 1. Bookmark
        $response = $this->actingAs($user)->postJson(route('bookmark.toggle', $article));
        $response->assertOk();
        $response->assertJson(['status' => 'saved']);
        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);

        // 2. Unbookmark
        $response2 = $this->actingAs($user)->postJson(route('bookmark.toggle', $article));
        $response2->assertOk();
        $response2->assertJson(['status' => 'removed']);
        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);
    }

    public function test_reading_article_records_reading_history(): void
    {
        $user = User::create([
            'name' => 'History Reader',
            'email' => 'hist@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $article = Article::first();

        $response = $this->actingAs($user)->get(route('articles.show', $article->slug));
        $response->assertOk();

        $this->assertDatabaseHas('reading_histories', [
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);
    }

    public function test_user_can_follow_author_and_category(): void
    {
        $user = User::create([
            'name' => 'Follower User',
            'email' => 'follower@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $author = User::where('role', 'writer')->first();
        $category = Category::first();

        // Follow Author
        $resAuthor = $this->actingAs($user)->postJson(route('follow.toggle'), [
            'type' => 'author',
            'id' => $author->id,
        ]);
        $resAuthor->assertOk();
        $resAuthor->assertJson(['status' => 'following']);
        $this->assertDatabaseHas('follows', [
            'user_id' => $user->id,
            'followable_type' => User::class,
            'followable_id' => $author->id,
        ]);

        // Follow Category
        $resCat = $this->actingAs($user)->postJson(route('follow.toggle'), [
            'type' => 'category',
            'id' => $category->id,
        ]);
        $resCat->assertOk();
        $resCat->assertJson(['status' => 'following']);
        $this->assertDatabaseHas('follows', [
            'user_id' => $user->id,
            'followable_type' => Category::class,
            'followable_id' => $category->id,
        ]);
    }

    public function test_reader_can_apply_as_contributor_and_admin_can_approve(): void
    {
        $user = User::create([
            'name' => 'Aspiring Contributor',
            'email' => 'aspiring@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        // Submit Application
        $applyResponse = $this->actingAs($user)->post(route('contributor.apply.store'), [
            'name' => 'Aspiring Contributor',
            'email' => 'aspiring@example.com',
            'expertise' => 'Global Hedge Funds & Macroeconomics',
            'bio' => 'Senior strategist with 12 years analyzing interest rates and sovereign currency markets.',
            'linkedin_url' => 'https://linkedin.com/in/aspiring-contributor',
            'pitch' => 'I would like to contribute bi-weekly analyses of central bank policy.',
        ]);

        $applyResponse->assertRedirect();
        $this->assertDatabaseHas('contributor_applications', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $application = ContributorApplication::where('user_id', $user->id)->first();
        $admin = User::where('role', 'admin')->first();

        // Admin Approves Application
        $approveResponse = $this->actingAs($admin)->post(route('admin.contributors.approve', $application));
        $approveResponse->assertRedirect();

        $this->assertEquals('approved', $application->fresh()->status);
        $this->assertEquals('contributor', $user->fresh()->role);
    }

    public function test_contributor_can_submit_draft_article(): void
    {
        $contributor = User::where('role', 'contributor')->first();
        $category = Category::first();

        $response = $this->actingAs($contributor)->post(route('contributor.articles.store'), [
            'title' => 'Emerging Trends in Sovereign Fund Direct Investments',
            'subtitle' => 'How state investment arms bypass traditional private equity syndicates',
            'category_id' => $category->id,
            'excerpt' => 'An analysis of direct venture investments by sovereign entities.',
            'content' => '<p>Sovereign wealth managers are increasingly building in-house deal execution teams...</p>',
            'status' => 'submitted',
        ]);

        $response->assertRedirect(route('contributor.dashboard'));
        $this->assertDatabaseHas('articles', [
            'title' => 'Emerging Trends in Sovereign Fund Direct Investments',
            'user_id' => $contributor->id,
            'status' => 'submitted',
        ]);
    }

    public function test_user_can_post_comment_and_like(): void
    {
        $user = User::create([
            'name' => 'Commenter One',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $article = Article::first();

        // Post top-level comment
        $commentResponse = $this->actingAs($user)->post(route('comment.store', $article), [
            'content' => 'Outstanding analysis of commercial quantum deployments! Very insightful.',
        ]);

        $commentResponse->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $comment = Comment::where('article_id', $article->id)->where('user_id', $user->id)->first();

        // Like the comment
        $likeResponse = $this->actingAs($user)->postJson(route('comment.like', $comment));
        $likeResponse->assertOk();
        $this->assertEquals(1, $comment->fresh()->likes_count);
    }

    public function test_multimedia_routes_render(): void
    {
        $video = Video::first();
        $podcast = Podcast::first();
        $episode = PodcastEpisode::first();

        // Videos Index & Show
        $this->get(route('videos.index'))->assertOk();
        $this->get(route('videos.show', $video->slug))->assertOk();

        // Podcasts Index & Show & Episode
        $this->get(route('podcasts.index'))->assertOk();
        $this->get(route('podcasts.show', $podcast->slug))->assertOk();
        $this->get(route('podcasts.episode', [$podcast->slug, $episode->slug]))->assertOk();
    }

    public function test_seo_sitemaps_and_robots(): void
    {
        // XML Sitemap
        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapResponse->assertOk();
        $sitemapResponse->assertHeader('Content-Type', 'application/xml');
        $sitemapResponse->assertSee('<urlset', false);

        // News Sitemap
        $newsSitemapResponse = $this->get('/sitemap-news.xml');
        $newsSitemapResponse->assertOk();
        $newsSitemapResponse->assertHeader('Content-Type', 'application/xml');
        $newsSitemapResponse->assertSee('<urlset', false);

        // Robots.txt
        $robotsResponse = $this->get('/robots.txt');
        $robotsResponse->assertOk();
        $robotsResponse->assertSee('User-agent: *');
        $robotsResponse->assertSee('/sitemap.xml');
    }

    public function test_seo_redirect_middleware_handles_redirects(): void
    {
        $response = $this->get('/legacy/tech-report-2025');
        $response->assertStatus(301);
        $response->assertRedirect('/category/tech');
    }

    public function test_admin_cms_editorial_notes_and_media(): void
    {
        $admin = User::where('role', 'admin')->first();
        $article = Article::first();

        // Admin can access media library
        $mediaResponse = $this->actingAs($admin)->get(route('admin.media.index'));
        $mediaResponse->assertOk();

        // Admin can add editorial note
        $noteResponse = $this->actingAs($admin)->post(route('admin.articles.notes.store', $article), [
            'note_type' => 'revision_request',
            'content' => 'Please provide citation for the 184% growth figure.',
        ]);

        $noteResponse->assertRedirect();
        $this->assertDatabaseHas('editorial_notes', [
            'article_id' => $article->id,
            'note_type' => 'revision_request',
            'content' => 'Please provide citation for the 184% growth figure.',
        ]);
    }
}
