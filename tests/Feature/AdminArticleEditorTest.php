<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\Category;
use App\Models\EditorialNote;
use App\Models\MediaAsset;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminArticleEditorTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $editor;
    protected User $contributor;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('role', 'admin')->first();
        $this->category = Category::first();

        $this->editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $this->contributor = User::factory()->create([
            'role' => 'contributor',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_article_editor(): void
    {
        $article = Article::first();

        $this->get('/admin/articles')->assertRedirect('/admin/login');
        $this->get('/admin/articles/create')->assertRedirect('/admin/login');
        $this->get("/admin/articles/{$article->id}/edit")->assertRedirect('/admin/login');
        $this->get("/admin/articles/{$article->id}/preview")->assertRedirect('/admin/login');
    }

    public function test_editor_can_view_index_and_editor_pages(): void
    {
        $article = Article::first();

        $this->actingAs($this->editor)
            ->get('/admin/articles')
            ->assertStatus(200)
            ->assertSee('Articles & Editorial Stories');

        $this->actingAs($this->editor)
            ->get('/admin/articles/create')
            ->assertStatus(200)
            ->assertSee('Article Body');

        $this->actingAs($this->editor)
            ->get("/admin/articles/{$article->id}/edit")
            ->assertStatus(200)
            ->assertSee($article->title);
    }

    public function test_contributor_cannot_edit_other_authors_article(): void
    {
        $article = Article::where('user_id', $this->admin->id)->first();

        $response = $this->actingAs($this->contributor)
            ->get("/admin/articles/{$article->id}/edit");

        $response->assertStatus(403);
    }

    public function test_contributor_cannot_directly_publish(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post('/admin/articles', [
                'title' => 'Contributor Story Attempting Direct Publishing',
                'category_id' => $this->category->id,
                'content' => '<p>Article body content by contributor.</p>',
                'status' => 'published',
            ]);

        $response->assertRedirect('/admin/articles');

        $article = Article::where('title', 'Contributor Story Attempting Direct Publishing')->first();
        $this->assertNotNull($article);
        // Should be forced to 'submitted' for non-editors
        $this->assertEquals('submitted', $article->status);
        $this->assertEquals($this->contributor->id, $article->user_id);
    }

    public function test_article_creation_creates_initial_revision_and_audit_log(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/articles', [
                'title' => 'Revolutionary Silicon Photonics for High-Throughput AI Clusters',
                'subtitle' => 'Next generation optical interconnects accelerate model training',
                'category_id' => $this->category->id,
                'excerpt' => 'Silicon photonics break latency barriers in cluster topologies.',
                'content' => '<p>Optical interconnects provide petabit-scale backplanes for superclusters.</p>',
                'status' => 'published',
                'featured_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475',
                'featured_image_caption' => 'Photo courtesy of Intel Labs',
                'featured_image_alt' => 'Silicon photonics microchip wafer',
                'is_featured' => true,
                'is_breaking' => false,
                'meta_title' => 'Silicon Photonics in AI Clusters | APEX',
                'meta_description' => 'In-depth analysis of optical interconnect architectures in high-performance computing.',
            ]);

        $response->assertRedirect('/admin/articles');

        $article = Article::where('title', 'Revolutionary Silicon Photonics for High-Throughput AI Clusters')->first();
        $this->assertNotNull($article);
        $this->assertEquals('published', $article->status);
        $this->assertEquals('Silicon photonics microchip wafer', $article->featured_image_alt);
        $this->assertEquals(1, $article->revisions()->count());
        $this->assertEquals(1, $article->revisions()->first()->version);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'article_created',
            'entity_id' => $article->id,
        ]);
    }

    public function test_article_update_creates_new_revision(): void
    {
        $article = Article::first();
        $initialRevisionCount = $article->revisions()->count();

        $response = $this->actingAs($this->admin)
            ->put("/admin/articles/{$article->id}", [
                'title' => 'Updated Title for Existing Article',
                'slug' => $article->slug,
                'category_id' => $article->category_id,
                'content' => '<p>Completely updated article content with new facts and data points.</p>',
                'status' => 'published',
                'change_summary' => 'Updated data points with Q4 figures',
            ]);

        $response->assertRedirect('/admin/articles');

        $article->refresh();
        $this->assertEquals('Updated Title for Existing Article', $article->title);
        $this->assertGreaterThan($initialRevisionCount, $article->revisions()->count());

        $latestRev = $article->revisions()->first();
        $this->assertEquals('Updated data points with Q4 figures', $latestRev->change_summary);
    }

    public function test_autosave_updates_draft_without_unpublishing(): void
    {
        $article = Article::where('status', 'published')->first();

        $response = $this->actingAs($this->admin)
            ->postJson("/admin/articles/{$article->id}/autosave", [
                'title' => $article->title,
                'content' => '<p>Autosaved content modification while live.</p>',
                'excerpt' => 'Updated draft excerpt during live editing.',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Draft autosaved',
        ]);

        $article->refresh();
        $this->assertEquals('published', $article->status);
        $this->assertStringContainsString('Autosaved content modification', $article->content);
        $this->assertEquals('Updated draft excerpt during live editing.', $article->excerpt);
    }

    public function test_article_preview_renders_successfully(): void
    {
        $article = Article::first();

        $response = $this->actingAs($this->admin)
            ->get("/admin/articles/{$article->id}/preview");

        $response->assertStatus(200);
        $response->assertSee('Real Editorial Rendering');
        $response->assertSee($article->title);
        $response->assertSee('Desktop');
        $response->assertSee('Mobile (390px)');
    }

    public function test_editorial_notes_lifecycle(): void
    {
        $article = Article::first();

        // 1. Create Note
        $createResponse = $this->actingAs($this->editor)
            ->post("/admin/articles/{$article->id}/notes", [
                'note_type' => 'fact_check',
                'content' => 'Verify semiconductor export volume in paragraph 3 with Commerce Dept data.',
            ]);

        $createResponse->assertSessionHas('success');

        $note = EditorialNote::where('article_id', $article->id)
            ->where('content', 'like', '%semiconductor%')
            ->first();

        $this->assertNotNull($note);
        $this->assertFalse((bool) $note->is_resolved);

        // 2. Resolve Note
        $toggleResponse = $this->actingAs($this->editor)
            ->postJson("/admin/articles/{$article->id}/notes/{$note->id}/resolve");

        $toggleResponse->assertStatus(200);
        $toggleResponse->assertJson(['success' => true, 'is_resolved' => true]);
        $this->assertTrue((bool) $note->fresh()->is_resolved);

        // 3. Delete Note
        $deleteResponse = $this->actingAs($this->editor)
            ->deleteJson("/admin/articles/{$article->id}/notes/{$note->id}");

        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson(['success' => true]);
        $this->assertDatabaseMissing('editorial_notes', ['id' => $note->id]);
    }

    public function test_revision_restore(): void
    {
        $article = Article::first();
        $originalTitle = $article->title;
        $originalContent = $article->content;

        // Create an explicit revision
        $revision = ArticleRevision::create([
            'article_id' => $article->id,
            'user_id' => $this->admin->id,
            'title' => 'Historical Version Title',
            'content' => '<p>Historical revision content that will be restored.</p>',
            'excerpt' => 'Historical excerpt',
            'version' => 99,
            'change_summary' => 'Historical landmark snapshot',
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/articles/{$article->id}/revisions/{$revision->id}/restore");

        $response->assertRedirect("/admin/articles/{$article->id}/edit");
        $response->assertSessionHas('success');

        $article->refresh();
        $this->assertEquals('Historical Version Title', $article->title);
        $this->assertEquals('<p>Historical revision content that will be restored.</p>', $article->content);
    }

    public function test_media_picker_api(): void
    {
        MediaAsset::create([
            'filename' => 'quantum-computing-superconducting.webp',
            'title' => 'Superconducting Qubits In Cryostat',
            'caption' => 'Photo by Rigetti Computing',
            'alt_text' => 'Quantum dilution refrigerator assembly',
            'file_path' => 'media/quantum.webp',
            'file_type' => 'image',
            'mime_type' => 'image/webp',
            'file_size' => 102400,
            'uploaded_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson('/admin/media/picker?search=Superconducting');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'total',
        ]);
        $this->assertStringContainsString('Superconducting Qubits In Cryostat', $response->getContent());
    }

    public function test_reading_time_and_word_count_calculation(): void
    {
        Setting::set('default_reading_words_per_minute', 220, 'editorial');

        // 440 words with 220 WPM should be exactly 2 minutes
        $words = implode(' ', array_fill(0, 440, 'quantum'));
        $content = "<p>{$words}</p>";

        $article = Article::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Reading Time Verification Story',
            'slug' => 'reading-time-test',
            'content' => $content,
            'status' => 'draft',
        ]);

        $this->assertEquals(2, $article->reading_time_minutes);
    }
}
