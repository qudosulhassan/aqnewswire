<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuditLog;
use App\Models\ReadingHistory;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminGeneralSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    protected function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    protected function getEditorUser(): User
    {
        return User::where('role', 'editor')->first() ?? User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);
    }

    protected function getWriterUser(): User
    {
        return User::where('role', 'writer')->first() ?? User::factory()->create([
            'role' => 'writer',
            'is_active' => true,
        ]);
    }

    protected function getReaderUser(): User
    {
        return User::where('role', 'reader')->first() ?? User::factory()->create([
            'role' => 'reader',
            'is_active' => true,
        ]);
    }

    protected function validSettingsData(array $overrides = []): array
    {
        return array_merge([
            'site_name' => 'APEX International Business & Leadership',
            'site_description' => 'Global business journalism and executive intelligence.',
            'site_url' => 'https://apexmedia.org',
            'copyright_text' => '© 2026 APEX Media International. All rights reserved.',
            'short_brand_name' => 'APEX',
            'logo_url' => '/images/apex-logo.svg',
            'favicon_url' => '/favicon.ico',
            'primary_email' => 'contact@apexmedia.org',
            'editorial_email' => 'editorial@apexmedia.org',
            'support_email' => 'support@apexmedia.org',
            'contact_phone' => '+1 (555) 019-2834',
            'office_address' => '100 Financial District, New York, NY 10005',
            'contact_page_url' => 'https://apexmedia.org/contact',
            'timezone' => 'America/New_York',
            'default_locale' => 'en',
            'date_format' => 'F j, Y',
            'time_format' => 'g:i A',
            'social_linkedin' => 'https://linkedin.com/company/apexnews',
            'social_twitter' => 'https://twitter.com/apexnews',
            'social_facebook' => 'https://facebook.com/apexnews',
            'social_instagram' => 'https://instagram.com/apexnews',
            'social_youtube' => 'https://youtube.com/@apexnews',
            'enable_comments' => '1',
            'enable_newsletter' => '1',
            'enable_registration' => '1',
            'enable_bookmarks' => '1',
            'default_reading_words_per_minute' => 220,
            'default_article_status' => 'draft',
            'enable_reading_history' => '1',
        ], $overrides);
    }

    public function test_guest_is_redirected_to_login_when_accessing_settings(): void
    {
        $response = $this->get(route('admin.settings'));

        $response->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_receives_forbidden_when_accessing_settings(): void
    {
        $writer = $this->getWriterUser();
        $response = $this->actingAs($writer)->get(route('admin.settings'));
        $response->assertStatus(403);

        $editor = $this->getEditorUser();
        $response = $this->actingAs($editor)->get(route('admin.settings'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_general_settings_page_with_status_200(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.index');
        $response->assertSee('General Settings');
        $response->assertSee('Global Platform Configuration');
    }

    public function test_general_settings_does_not_redirect_to_seo(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $this->assertFalse($response->isRedirection());
        $this->assertNotEquals(route('admin.seo.index'), url()->current());
    }

    public function test_seo_and_settings_sidebar_highlight_independently(): void
    {
        $admin = $this->getAdminUser();

        // 1. Visit General Settings
        $settingsResponse = $this->actingAs($admin)->get(route('admin.settings'));
        $settingsResponse->assertStatus(200);
        $settingsContent = $settingsResponse->getContent();
        $this->assertStringContainsString('href="' . route('admin.settings') . '"', $settingsContent);
        $this->assertStringContainsString('href="' . route('admin.seo.index') . '"', $settingsContent);

        // 2. Visit SEO
        $seoResponse = $this->actingAs($admin)->get(route('admin.seo.index'));
        $seoResponse->assertStatus(200);
        $seoResponse->assertSee('Technical SEO Control Center');
    }

    public function test_admin_can_update_settings_successfully(): void
    {
        $admin = $this->getAdminUser();

        $updateData = $this->validSettingsData([
            'site_name' => 'APEX International News',
            'primary_email' => 'contact@apexmedia.org',
            'timezone' => 'America/New_York',
            'default_reading_words_per_minute' => 250,
            'enable_comments' => '1',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), $updateData);

        $response->assertRedirect(route('admin.settings'));
        $response->assertSessionHas('success');

        // Verify in database
        $this->assertEquals('APEX International News', Setting::get('site_name'));
        $this->assertEquals('contact@apexmedia.org', Setting::get('primary_email'));
        $this->assertEquals('America/New_York', Setting::get('timezone'));
        $this->assertEquals(250, Setting::get('default_reading_words_per_minute'));
        $this->assertTrue(Setting::get('enable_comments'));
    }

    public function test_validation_rejects_invalid_email(): void
    {
        $admin = $this->getAdminUser();

        $invalidData = $this->validSettingsData([
            'primary_email' => 'not-an-email',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), $invalidData);

        $response->assertSessionHasErrors(['primary_email']);
    }

    public function test_validation_rejects_invalid_url(): void
    {
        $admin = $this->getAdminUser();

        $invalidData = $this->validSettingsData([
            'social_twitter' => 'ftp://invalid-url.com',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), $invalidData);

        $response->assertSessionHasErrors(['social_twitter']);
    }

    public function test_validation_rejects_invalid_timezone(): void
    {
        $admin = $this->getAdminUser();

        $invalidData = $this->validSettingsData([
            'timezone' => 'Invalid/Timezone_Name',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), $invalidData);

        $response->assertSessionHasErrors(['timezone']);
    }

    public function test_updating_settings_clears_cache(): void
    {
        $admin = $this->getAdminUser();

        // Warm up cache
        Setting::allCached();
        $this->assertTrue(Cache::has('app_settings_map'));

        // Update settings
        $updateData = $this->validSettingsData([
            'site_name' => 'Cache Test Name',
        ]);

        $this->actingAs($admin)->put(route('admin.settings.update'), $updateData);

        // After update and clearCache, fresh get fetches new value
        $this->assertEquals('Cache Test Name', Setting::get('site_name'));
    }

    public function test_audit_log_is_recorded_on_settings_update(): void
    {
        $admin = $this->getAdminUser();

        $updateData = $this->validSettingsData([
            'site_name' => 'Audited Title',
        ]);

        $this->actingAs($admin)->put(route('admin.settings.update'), $updateData);

        $log = AuditLog::where('action', 'general_settings_updated')->latest()->first();
        $this->assertNotNull($log);
        $this->assertEquals($admin->id, $log->user_id);
    }

    public function test_sensitive_environment_secrets_are_not_rendered(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.settings'));
        $content = $response->getContent();

        $this->assertStringNotContainsString(config('app.key'), $content);
        $this->assertStringNotContainsString('base64:', $content);
        if (config('database.connections.mysql.password')) {
            $this->assertStringNotContainsString(config('database.connections.mysql.password'), $content);
        }
    }

    public function test_admin_can_export_settings_json_without_secrets(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.settings.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');
        
        $json = json_decode($response->streamedContent(), true);
        $this->assertIsArray($json);
        $this->assertArrayHasKey('site_name', $json);
        $this->assertArrayNotHasKey('APP_KEY', $json);
        $this->assertArrayNotHasKey('DB_PASSWORD', $json);
    }

    public function test_admin_can_import_settings_json(): void
    {
        $admin = $this->getAdminUser();

        $importPayload = [
            'site_name' => 'Imported Publication Name',
            'short_brand_name' => 'IMPORTED',
        ];

        $file = UploadedFile::fake()->createWithContent('settings.json', json_encode($importPayload));

        $response = $this->actingAs($admin)->post(route('admin.settings.import'), [
            'settings_file' => $file,
        ]);

        $response->assertRedirect(route('admin.settings'));
        $this->assertEquals('Imported Publication Name', Setting::get('site_name'));
        $this->assertEquals('IMPORTED', Setting::get('short_brand_name'));
    }

    public function test_admin_can_reset_settings_group_to_defaults(): void
    {
        $admin = $this->getAdminUser();

        // Change branding first
        Setting::set('short_brand_name', 'CHANGED', 'string', 'branding');
        $this->assertEquals('CHANGED', Setting::get('short_brand_name'));

        // Reset branding group
        $response = $this->actingAs($admin)->post(route('admin.settings.reset'), [
            'group' => 'branding',
        ]);

        $response->assertRedirect(route('admin.settings'));
        $this->assertEquals('AQ NEWSWIRE', Setting::get('short_brand_name'));
    }

    public function test_registration_toggle_controls_reader_signup(): void
    {
        // 1. Enable registration
        Setting::set('enable_registration', '1', 'boolean', 'reader');
        $response = $this->get(route('register'));
        $response->assertOk();

        // 2. Disable registration
        Setting::set('enable_registration', '0', 'boolean', 'reader');
        $disabledResponse = $this->get(route('register'));
        $disabledResponse->assertRedirect(route('login'));

        // Attempting to POST registration when disabled fails
        $postResponse = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'newreader@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
        ]);
        $postResponse->assertRedirect(route('login'));
    }

    public function test_comments_toggle_controls_comment_submission(): void
    {
        $reader = $this->getReaderUser();
        $article = Article::first();

        // 1. Enable comments
        Setting::set('enable_comments', '1', 'boolean', 'reader');
        $response = $this->actingAs($reader)->post(route('comment.store', $article), [
            'content' => 'Insightful editorial perspective on global macro trends.',
        ]);
        $response->assertSessionHas('success');

        // 2. Disable comments
        Setting::set('enable_comments', '0', 'boolean', 'reader');
        $disabledResponse = $this->actingAs($reader)->post(route('comment.store', $article), [
            'content' => 'This commentary should be blocked when comments are closed.',
        ]);
        $disabledResponse->assertSessionHas('error');
    }

    public function test_newsletter_toggle_controls_newsletter_subscription(): void
    {
        // 1. Enable newsletter
        Setting::set('enable_newsletter', '1', 'boolean', 'reader');
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'subscriber@testcorp.com',
        ]);
        $response->assertSessionHas('success');

        // 2. Disable newsletter
        Setting::set('enable_newsletter', '0', 'boolean', 'reader');
        $disabledResponse = $this->post(route('newsletter.subscribe'), [
            'email' => 'blocked@testcorp.com',
        ]);
        $disabledResponse->assertSessionHas('error');
    }

    public function test_bookmark_toggle_controls_story_bookmarking(): void
    {
        $reader = $this->getReaderUser();
        $article = Article::first();

        // 1. Enable bookmarks
        Setting::set('enable_bookmarks', '1', 'boolean', 'reader');
        $response = $this->actingAs($reader)->post(route('bookmark.toggle', $article));
        $response->assertSessionHas('success');

        // 2. Disable bookmarks
        Setting::set('enable_bookmarks', '0', 'boolean', 'reader');
        $disabledResponse = $this->actingAs($reader)->post(route('bookmark.toggle', $article));
        $disabledResponse->assertSessionHas('error');
    }

    public function test_reading_history_toggle_controls_history_recording(): void
    {
        $reader = $this->getReaderUser();
        $article = Article::first();

        // Clean previous reading history for this reader & article
        ReadingHistory::where('user_id', $reader->id)->where('article_id', $article->id)->delete();

        // 1. Disable reading history
        Setting::set('enable_reading_history', '0', 'boolean', 'editorial');
        $this->actingAs($reader)->get(route('articles.show', $article->slug));
        $this->assertNull(ReadingHistory::where('user_id', $reader->id)->where('article_id', $article->id)->first());

        // 2. Enable reading history
        Setting::set('enable_reading_history', '1', 'boolean', 'editorial');
        $this->actingAs($reader)->get(route('articles.show', $article->slug));
        $this->assertNotNull(ReadingHistory::where('user_id', $reader->id)->where('article_id', $article->id)->first());
    }

    public function test_public_layout_reflects_custom_site_name_and_brand(): void
    {
        Setting::set('short_brand_name', 'TESTBRAND', 'string', 'branding');
        Setting::set('copyright_text', '© 2026 TestBrand Custom Copyright', 'string', 'general');

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('TESTBRAND');
        $response->assertSee('© 2026 TestBrand Custom Copyright');
    }

    public function test_public_contact_page_renders_configured_bureau_information(): void
    {
        Setting::set('primary_email', 'inquiries@testapex.org', 'string', 'contact');
        Setting::set('editorial_email', 'desk@testapex.org', 'string', 'contact');

        $response = $this->get(route('contact'));
        $response->assertOk();
        $response->assertSee('inquiries@testapex.org');
        $response->assertSee('desk@testapex.org');
    }
}
