<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSegment;
use App\Models\NewsletterSubscriber;
use App\Models\PushSubscription;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\RecommendationService;
use App\Services\TrendingService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_can_follow_and_unfollow_topics(): void
    {
        $user = User::create([
            'name' => 'Topic Reader',
            'email' => 'topic.reader@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $tag = Tag::first();

        // 1. Follow topic
        $followResponse = $this->actingAs($user)->postJson(route('follow.toggle'), [
            'type' => 'topic',
            'id' => $tag->id,
        ]);

        $followResponse->assertOk();
        $followResponse->assertJson(['status' => 'following']);
        $this->assertDatabaseHas('follows', [
            'user_id' => $user->id,
            'followable_type' => Tag::class,
            'followable_id' => $tag->id,
        ]);

        // 2. Unfollow topic
        $unfollowResponse = $this->actingAs($user)->postJson(route('follow.toggle'), [
            'type' => 'topic',
            'id' => $tag->id,
        ]);

        $unfollowResponse->assertOk();
        $unfollowResponse->assertJson(['status' => 'unfollowed']);
        $this->assertDatabaseMissing('follows', [
            'user_id' => $user->id,
            'followable_type' => Tag::class,
            'followable_id' => $tag->id,
        ]);
    }

    public function test_recommendation_and_trending_services(): void
    {
        $article = Article::first();
        $recommendationService = app(RecommendationService::class);
        $trendingService = app(TrendingService::class);

        // Article recommendations
        $related = $recommendationService->forArticle($article, 3);
        $this->assertNotEmpty($related);
        $this->assertFalse($related->contains('id', $article->id));

        // Trending recalculation
        $count = $trendingService->recalculateScores();
        $this->assertGreaterThan(0, $count);

        $trending = $trendingService->getTrending(3);
        $this->assertNotEmpty($trending);

        // Artisan command
        $this->artisan('trending:calculate')->assertSuccessful();
    }

    public function test_advanced_search_and_autocomplete(): void
    {
        // Advanced search filters
        $searchResponse = $this->get(route('search.index', [
            'q' => 'AI',
            'type' => 'articles',
            'date' => '30d',
            'sort' => 'views',
        ]));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Search Results for');

        // Autocomplete JSON suggestions
        $autocompleteResponse = $this->getJson(route('search.autocomplete', ['q' => 'Enterprise']));
        $autocompleteResponse->assertOk();
        $autocompleteResponse->assertJsonStructure(['suggestions']);
    }

    public function test_newsletter_preferences_and_unsubscribe(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'executive.pref@example.com',
            'topics_interest' => 'Markets',
            'is_active' => true,
        ]);

        // Preference center page loads
        $this->get(route('newsletter.preferences', ['email' => $subscriber->email]))->assertOk();

        // Update preferences
        $updateResponse = $this->post(route('newsletter.preferences.update'), [
            'email' => $subscriber->email,
            'topics' => ['Artificial Intelligence & Deeptech', 'Global Macroeconomics & Central Banks'],
            'is_active' => 1,
        ]);
        $updateResponse->assertRedirect();
        $this->assertStringContainsString('Artificial Intelligence', $subscriber->fresh()->topics_interest);

        // One-click unsubscribe
        $unsubResponse = $this->post(route('newsletter.unsubscribe'), [
            'email' => $subscriber->email,
        ]);
        $unsubResponse->assertOk();
        $this->assertFalse($subscriber->fresh()->is_active);
    }

    public function test_admin_can_manage_newsletter_campaigns_and_segments(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Admin creates segment
        $segmentResponse = $this->actingAs($admin)->post(route('admin.newsletters.segments.store'), [
            'name' => 'Founders Cohort',
            'description' => 'Targeted tech founders and angel investors',
            'criteria_type' => 'active_only',
        ]);
        $segmentResponse->assertRedirect();
        $this->assertDatabaseHas('newsletter_segments', ['name' => 'Founders Cohort']);

        $segment = NewsletterSegment::where('name', 'Founders Cohort')->first();

        // Admin creates campaign
        $campaignResponse = $this->actingAs($admin)->post(route('admin.newsletters.campaigns.store'), [
            'name' => 'Q4 Deeptech Briefing',
            'subject' => 'The Next Era of Semiconductor Superclusters',
            'segment_id' => $segment->id,
            'content' => 'Full briefing content here...',
            'status' => 'draft',
        ]);
        $campaignResponse->assertRedirect();
        $this->assertDatabaseHas('newsletter_campaigns', ['name' => 'Q4 Deeptech Briefing']);
    }

    public function test_user_can_mark_notifications_read(): void
    {
        $user = User::create([
            'name' => 'Notif User',
            'email' => 'notif@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        $notification = UserNotification::create([
            'user_id' => $user->id,
            'type' => 'editorial',
            'title' => 'Story Approved',
            'message' => 'Your story is now in review queue.',
            'read_at' => null,
        ]);

        // Mark individual read
        $response = $this->actingAs($user)->post(route('account.notifications.read', $notification));
        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);

        // Mark all read
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'comment',
            'title' => 'New Discussion',
            'message' => 'Reader replied to your comment.',
            'read_at' => null,
        ]);

        $markAllResponse = $this->actingAs($user)->post(route('account.notifications.mark-all-read'));
        $markAllResponse->assertRedirect();
        $this->assertEquals(0, $user->notifications()->unread()->count());
    }

    public function test_browser_push_subscription(): void
    {
        $user = User::create([
            'name' => 'Push User',
            'email' => 'push@example.com',
            'password' => Hash::make('password123'),
            'role' => 'reader',
        ]);

        // Register push subscription
        $subResponse = $this->actingAs($user)->postJson(route('push.subscribe'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sample-token-123',
            'public_key' => 'BLf...sample-public-key',
            'auth_token' => 'sample-auth-secret',
        ]);

        $subResponse->assertOk();
        $this->assertDatabaseHas('push_subscriptions', [
            'user_id' => $user->id,
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sample-token-123',
            'is_active' => true,
        ]);

        // Deactivate push subscription
        $unsubResponse = $this->actingAs($user)->postJson(route('push.unsubscribe'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sample-token-123',
        ]);

        $unsubResponse->assertOk();
        $this->assertDatabaseHas('push_subscriptions', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sample-token-123',
            'is_active' => false,
        ]);
    }

    public function test_seo_redirect_rejects_self_redirect(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.seo.redirects.store'), [
            'source_path' => '/category/technology',
            'destination_url' => '/category/technology',
            'status_code' => 301,
        ]);

        $response->assertSessionHasErrors('destination_url');
    }

    public function test_contributor_cannot_edit_other_author_article(): void
    {
        $contributor1 = User::create([
            'name' => 'Contributor One',
            'email' => 'c1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'contributor',
        ]);

        $contributor2 = User::create([
            'name' => 'Contributor Two',
            'email' => 'c2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'contributor',
        ]);

        $category = Category::first();

        $article = Article::create([
            'user_id' => $contributor1->id,
            'category_id' => $category->id,
            'title' => 'Private Contributor Story Draft',
            'slug' => 'private-contributor-story-draft',
            'content' => '<p>Confidential analysis...</p>',
            'status' => 'draft',
        ]);

        // Contributor 2 attempts to edit Contributor 1's article
        $response = $this->actingAs($contributor2)->get(route('contributor.articles.edit', $article));
        $response->assertStatus(403);

        $updateResponse = $this->actingAs($contributor2)->put(route('contributor.articles.update', $article), [
            'title' => 'Maliciously Overwritten Title',
            'category_id' => $category->id,
            'content' => '<p>Overwritten</p>',
            'status' => 'draft',
        ]);
        $updateResponse->assertStatus(403);
    }
}
