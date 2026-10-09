<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\ContributorApplication;
use App\Models\User;
use App\Models\UserNotification;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContributorManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $editor;
    protected User $contributor;
    protected User $reader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('role', 'admin')->first();

        $this->editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $this->contributor = User::factory()->create([
            'role' => 'contributor',
            'is_active' => true,
        ]);

        $this->reader = User::factory()->create([
            'role' => 'reader',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_contributor_admin_management(): void
    {
        $response = $this->get('/admin/contributors');
        $response->assertRedirect('/admin/login');
    }

    public function test_regular_reader_cannot_access_contributor_admin_management(): void
    {
        $response = $this->actingAs($this->reader)->get('/admin/contributors');
        $response->assertStatus(403);
    }

    public function test_contributor_cannot_access_contributor_admin_management(): void
    {
        $response = $this->actingAs($this->contributor)->get('/admin/contributors');
        $response->assertStatus(403);
    }

    public function test_admin_and_editor_can_view_contributor_management(): void
    {
        $adminResponse = $this->actingAs($this->admin)->get('/admin/contributors');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Executive Contributor Management');
        $adminResponse->assertSee('Total Applications');
        $adminResponse->assertSee('Active Contributors');

        $editorResponse = $this->actingAs($this->editor)->get('/admin/contributors');
        $editorResponse->assertStatus(200);
        $editorResponse->assertSee('Executive Contributor Management');
    }

    public function test_empty_state_rendered_when_zero_applications(): void
    {
        // Database has 0 applications initially
        $this->assertEquals(0, ContributorApplication::count());

        $response = $this->actingAs($this->admin)->get('/admin/contributors');
        $response->assertStatus(200);
        $response->assertSee('No Contributor Applications Pending');
    }

    public function test_public_user_can_view_and_submit_contributor_application(): void
    {
        $viewResponse = $this->get(route('contributor.apply'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Become an AQ NEWSWIRE Contributor');

        $payload = [
            'name' => 'Sarah Jenkins',
            'email' => 'sarah.jenkins@example.org',
            'expertise' => 'Global Macroeconomics & Monetary Policy',
            'bio' => 'Former central bank senior advisor with 15 years analyzing sovereign debt markets and inflation models.',
            'website' => 'https://sarahjenkins.substack.com',
            'social_links' => 'linkedin.com/in/sarahjenkins-macro',
            'portfolio' => 'https://sarahjenkins.substack.com/p/central-bank-liquidity-crisis',
            'message' => 'I would like to author a bi-weekly column focusing on central bank balance sheets and global currency trends.',
        ];

        $submitResponse = $this->post(route('contributor.apply.store'), $payload);
        $submitResponse->assertRedirect(route('contributor.apply'));
        $submitResponse->assertSessionHas('success');

        $this->assertDatabaseHas('contributor_applications', [
            'name' => 'Sarah Jenkins',
            'email' => 'sarah.jenkins@example.org',
            'expertise' => 'Global Macroeconomics & Monetary Policy',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'contributor_application_submitted',
        ]);
    }

    public function test_duplicate_application_protection(): void
    {
        ContributorApplication::create([
            'name' => 'Marcus Vance',
            'email' => 'marcus@example.com',
            'expertise' => 'Venture Capital',
            'bio' => 'Partner at Apex Capital.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $duplicatePayload = [
            'name' => 'Marcus Vance',
            'email' => 'marcus@example.com',
            'expertise' => 'Venture Capital',
            'bio' => 'Attempting a duplicate submission.',
        ];

        $response = $this->post(route('contributor.apply.store'), $duplicatePayload);
        $response->assertSessionHas('error');
        $this->assertEquals(1, ContributorApplication::where('email', 'marcus@example.com')->count());
    }

    public function test_existing_contributor_sees_notice_on_application_form(): void
    {
        $response = $this->actingAs($this->contributor)->get(route('contributor.apply'));
        $response->assertStatus(200);
        $response->assertSee('You Already Have Contributor Access');
    }

    public function test_application_detail_endpoint_returns_json(): void
    {
        $application = ContributorApplication::create([
            'name' => 'Dr. Aris Thorne',
            'email' => 'aris@quantfund.com',
            'expertise' => 'Algorithmic Trading',
            'bio' => 'Lead researcher in high frequency quantitative strategies.',
            'website' => 'https://quantfund.com',
            'portfolio' => 'https://arxiv.org/abs/2301.12345',
            'message' => 'Seeking to write about market microstructure.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.contributors.show', $application));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'name' => 'Dr. Aris Thorne',
                'email' => 'aris@quantfund.com',
                'expertise' => 'Algorithmic Trading',
                'status' => 'pending',
            ],
        ]);
    }

    public function test_admin_can_move_application_to_under_review(): void
    {
        $application = ContributorApplication::create([
            'user_id' => $this->reader->id,
            'name' => $this->reader->name,
            'email' => $this->reader->email,
            'expertise' => 'Fintech',
            'bio' => 'Senior fintech researcher.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.contributors.review', $application), [
            'admin_notes' => 'Evaluating past publications with editorial board.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contributor_applications', [
            'id' => $application->id,
            'status' => ContributorApplication::STATUS_UNDER_REVIEW,
            'reviewed_by' => $this->admin->id,
        ]);

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $this->reader->id,
            'type' => 'contributor_under_review',
        ]);
    }

    public function test_admin_can_request_changes_from_applicant(): void
    {
        $application = ContributorApplication::create([
            'user_id' => $this->reader->id,
            'name' => $this->reader->name,
            'email' => $this->reader->email,
            'expertise' => 'Energy Transition',
            'bio' => 'Analyst in renewable energy investment.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.contributors.requestChanges', $application), [
            'admin_notes' => 'Please provide 2 additional published clips regarding grid storage.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contributor_applications', [
            'id' => $application->id,
            'status' => ContributorApplication::STATUS_CHANGES_REQUESTED,
            'admin_notes' => 'Please provide 2 additional published clips regarding grid storage.',
        ]);

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $this->reader->id,
            'type' => 'contributor_changes_requested',
        ]);
    }

    public function test_admin_can_approve_application_and_elevate_reader_to_contributor(): void
    {
        $application = ContributorApplication::create([
            'user_id' => $this->reader->id,
            'name' => $this->reader->name,
            'email' => $this->reader->email,
            'expertise' => 'Artificial Intelligence',
            'bio' => 'Senior researcher at AI laboratory.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.contributors.approve', $application), [
            'admin_notes' => 'Outstanding portfolio in machine learning.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contributor_applications', [
            'id' => $application->id,
            'status' => ContributorApplication::STATUS_APPROVED,
            'reviewed_by' => $this->admin->id,
        ]);

        // User role should now be elevated to contributor
        $this->reader->refresh();
        $this->assertEquals('contributor', $this->reader->role);
        $this->assertTrue((bool)$this->reader->is_verified);

        // Contributor approved notification created
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $this->reader->id,
            'type' => 'contributor_approved',
        ]);

        // Audit log created
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'contributor_approved',
        ]);
    }

    public function test_admin_can_approve_guest_application_and_create_user(): void
    {
        $application = ContributorApplication::create([
            'name' => 'Claire Sterling',
            'email' => 'claire.sterling@venture.com',
            'expertise' => 'Early Stage Venture',
            'bio' => 'Managing director at Silicon Valley seed fund.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.contributors.approve', $application));
        $response->assertRedirect();

        $newUser = User::where('email', 'claire.sterling@venture.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('contributor', $newUser->role);
        $this->assertTrue((bool)$newUser->is_verified);

        $application->refresh();
        $this->assertEquals($newUser->id, $application->user_id);
    }

    public function test_admin_can_reject_contributor_application(): void
    {
        $application = ContributorApplication::create([
            'user_id' => $this->reader->id,
            'name' => $this->reader->name,
            'email' => $this->reader->email,
            'expertise' => 'Crypto & NFTs',
            'bio' => 'Trading speculative assets.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.contributors.reject', $application), [
            'admin_notes' => 'Pitch does not align with editorial focus.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contributor_applications', [
            'id' => $application->id,
            'status' => ContributorApplication::STATUS_REJECTED,
            'reviewed_by' => $this->admin->id,
            'admin_notes' => 'Pitch does not align with editorial focus.',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'contributor_rejected',
        ]);
    }

    public function test_accredited_contributor_can_access_contributor_portal_and_draft_article(): void
    {
        $response = $this->actingAs($this->contributor)->get(route('contributor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Contributor');

        $category = Category::first();
        $draftPayload = [
            'title' => 'Emerging Trends in European Sovereign Debt Markets',
            'subtitle' => 'How yield curve shifts are impacting infrastructure bonds',
            'category_id' => $category->id,
            'excerpt' => 'Yield dynamics are altering private market financing structures.',
            'content' => '<p>Institutional allocators are re-evaluating duration risk across fixed income instruments.</p>',
            'status' => 'draft',
        ];

        $storeResponse = $this->actingAs($this->contributor)->post(route('contributor.articles.store'), $draftPayload);
        $storeResponse->assertRedirect(route('contributor.dashboard'));

        $this->assertDatabaseHas('articles', [
            'title' => 'Emerging Trends in European Sovereign Debt Markets',
            'user_id' => $this->contributor->id,
            'status' => 'draft',
        ]);
    }

    public function test_unaccredited_reader_is_redirected_from_contributor_portal(): void
    {
        $response = $this->actingAs($this->reader)->get(route('contributor.dashboard'));
        $response->assertRedirect(route('contributor.apply'));
        $response->assertSessionHas('info');
    }

    public function test_csv_export_endpoint_streams_correct_data(): void
    {
        ContributorApplication::create([
            'name' => 'Harrison Forde',
            'email' => 'harrison@globalmarket.com',
            'expertise' => 'Commodities',
            'bio' => 'Energy analyst.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.contributors.export'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Harrison Forde', $content);
        $this->assertStringContainsString('harrison@globalmarket.com', $content);
        $this->assertStringContainsString('Commodities', $content);
    }

    public function test_filtering_and_search_on_applications(): void
    {
        ContributorApplication::create([
            'name' => 'Alice Quantum',
            'email' => 'alice@quantum.io',
            'expertise' => 'Quantum Computing',
            'bio' => 'Physicist writing on quantum chips.',
            'status' => ContributorApplication::STATUS_PENDING,
        ]);

        ContributorApplication::create([
            'name' => 'Bob Finance',
            'email' => 'bob@finance.com',
            'expertise' => 'Private Equity',
            'bio' => 'LBO specialist.',
            'status' => ContributorApplication::STATUS_APPROVED,
        ]);

        // Filter status=pending
        $pendingResponse = $this->actingAs($this->admin)->get(route('admin.contributors.index', ['status' => 'pending']));
        $pendingResponse->assertSee('Alice Quantum');
        $pendingResponse->assertDontSee('Bob Finance');

        // Filter search=Quantum
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.contributors.index', ['search' => 'Quantum']));
        $searchResponse->assertSee('Alice Quantum');
        $searchResponse->assertDontSee('Bob Finance');
    }

    public function test_active_contributors_tab_displays_roster(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.contributors.index', ['tab' => 'contributors']));
        $response->assertStatus(200);
        $response->assertSee('Accredited AQ NEWSWIRE Contributors');
        $response->assertSee($this->contributor->name);
    }
}
