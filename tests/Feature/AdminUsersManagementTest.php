<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUsersManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * 1. Admin can view users dashboard and KPIs.
     */
    public function test_admin_can_view_users_dashboard_and_kpis(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Users & Roles');
        $response->assertSee('Total Users');
        $response->assertSee('Active Users');
        $response->assertSee('Administrators');
        $response->assertSee('Editors');
        $response->assertSee('Contributors');
        $response->assertSee('+ Add User');
        $response->assertSee($admin->email);
    }

    /**
     * 2. Admin can create a new user.
     */
    public function test_admin_can_create_user(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Jonathan Vance',
            'email' => 'jonathan.vance@apexmedia.com',
            'role' => 'editor',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'is_active' => '1',
            'title' => 'Associate Editor',
            'bio' => 'Focusing on European tech startups and VC.',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Jonathan Vance',
            'email' => 'jonathan.vance@apexmedia.com',
            'role' => 'editor',
            'is_active' => true,
        ]);

        $createdUser = User::where('email', 'jonathan.vance@apexmedia.com')->first();
        $this->assertTrue(Hash::check('secret12345', $createdUser->password));

        // Audit log created
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user_created',
            'entity_type' => 'User',
            'entity_id' => $createdUser->id,
        ]);
    }

    /**
     * 3. Admin can edit user.
     */
    public function test_admin_can_edit_user(): void
    {
        $admin = User::where('role', 'admin')->first();
        $targetUser = User::where('role', 'contributor')->first();

        $response = $this->actingAs($admin)->put(route('admin.users.update', $targetUser), [
            'name' => 'Dr. Elena Rostova Senior',
            'email' => $targetUser->email,
            'role' => 'writer',
            'is_active' => '1',
            'title' => 'Senior DeepTech Contributor',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Dr. Elena Rostova Senior',
            'role' => 'writer',
        ]);

        // Audit log for role change
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'role_changed',
            'entity_type' => 'User',
            'entity_id' => $targetUser->id,
        ]);
    }

    /**
     * 4. Invalid role is rejected.
     */
    public function test_invalid_role_rejected(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Hacker Account',
            'email' => 'hacker@example.com',
            'role' => 'unauthorized_super_master',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
        ]);

        $response->assertSessionHasErrors(['role']);
        $this->assertDatabaseMissing('users', ['email' => 'hacker@example.com']);
    }

    /**
     * 5. Duplicate email is rejected.
     */
    public function test_duplicate_email_rejected(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Duplicate Person',
            'email' => $admin->email, // Existing email
            'role' => 'editor',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * 6. Admin can deactivate and activate user.
     */
    public function test_admin_can_toggle_user_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $targetUser = User::where('role', 'writer')->first();

        // 1. Deactivate
        $deactivateResponse = $this->actingAs($admin)->post(route('admin.users.toggleStatus', $targetUser));
        $deactivateResponse->assertSessionHas('success');
        $this->assertFalse($targetUser->fresh()->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user_deactivated',
            'entity_type' => 'User',
            'entity_id' => $targetUser->id,
        ]);

        // 2. Reactivate
        $activateResponse = $this->actingAs($admin)->post(route('admin.users.toggleStatus', $targetUser));
        $activateResponse->assertSessionHas('success');
        $this->assertTrue($targetUser->fresh()->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user_activated',
            'entity_type' => 'User',
            'entity_id' => $targetUser->id,
        ]);
    }

    /**
     * 7. Unauthorized user receives 403.
     */
    public function test_unauthorized_user_receives_403(): void
    {
        $contributor = User::where('role', 'contributor')->first();
        $subscriber = User::factory()->create(['role' => 'subscriber']);

        // Contributor forbidden
        $res1 = $this->actingAs($contributor)->get(route('admin.users.index'));
        $res1->assertStatus(403);

        // Subscriber forbidden
        $res2 = $this->actingAs($subscriber)->get(route('admin.users.index'));
        $res2->assertStatus(403);

        // Direct create attempt forbidden
        $res3 = $this->actingAs($contributor)->post(route('admin.users.store'), [
            'name' => 'Bad User',
            'email' => 'bad@example.com',
            'role' => 'admin',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
        ]);
        $res3->assertStatus(403);
    }

    /**
     * 8. Contributor cannot elevate own role.
     */
    public function test_contributor_cannot_elevate_own_role(): void
    {
        $contributor = User::where('role', 'contributor')->first();

        $response = $this->actingAs($contributor)->put(route('admin.users.update', $contributor), [
            'name' => $contributor->name,
            'email' => $contributor->email,
            'role' => 'admin',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('contributor', $contributor->fresh()->role);
    }

    /**
     * 9. Cannot delete currently authenticated admin.
     */
    public function test_cannot_delete_currently_authenticated_admin(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * 10. Cannot deactivate currently authenticated admin.
     */
    public function test_cannot_deactivate_currently_authenticated_admin(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.users.toggleStatus', $admin));

        $response->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->is_active);
    }

    /**
     * 11. Cannot remove final remaining administrator.
     */
    public function test_cannot_remove_final_remaining_administrator(): void
    {
        // Delete or change other admins so only 1 admin remains
        User::where('role', 'admin')->skip(1)->take(10)->update(['role' => 'editor']);
        $finalAdmin = User::where('role', 'admin')->first();

        $targetAdmin = User::factory()->create(['role' => 'admin']);
        $finalAdmin->update(['role' => 'editor']); // Now $targetAdmin is the sole admin

        $response = $this->actingAs($targetAdmin)->delete(route('admin.users.destroy', $targetAdmin));
        $this->assertDatabaseHas('users', ['id' => $targetAdmin->id]);
    }

    /**
     * 12. Search works on name and email.
     */
    public function test_search_works(): void
    {
        $admin = User::where('role', 'admin')->first();

        $res1 = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Alexander']));
        $res1->assertOk();
        $res1->assertSee('Alexander Vance');

        $res2 = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'elena@apexmedia.com']));
        $res2->assertOk();
        $res2->assertSee('elena@apexmedia.com');

        $res3 = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'nonexistent999']));
        $res3->assertOk();
        $res3->assertSee('No users found');
    }

    /**
     * 13. Role filter works.
     */
    public function test_role_filter_works(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'editor']));

        $response->assertOk();
        $response->assertSee('Sophia Thorne');
    }

    /**
     * 14. Status filter works.
     */
    public function test_status_filter_works(): void
    {
        $admin = User::where('role', 'admin')->first();
        $inactiveUser = User::factory()->create(['name' => 'Dormant User', 'is_active' => false]);

        $resInactive = $this->actingAs($admin)->get(route('admin.users.index', ['status' => 'inactive']));
        $resInactive->assertOk();
        $resInactive->assertSee('Dormant User');

        $resActive = $this->actingAs($admin)->get(route('admin.users.index', ['status' => 'active']));
        $resActive->assertOk();
        $resActive->assertDontSee('Dormant User');
    }

    /**
     * 15. Article counts and user detail page work.
     */
    public function test_user_detail_page_and_article_counts_work(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.users.show', $admin));

        $response->assertOk();
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);
        $response->assertSee('Total Articles');
        $response->assertSee('Security');
        $response->assertSee('Audit History');
    }

    /**
     * 16. Deactivated user cannot log into staff admin or reader account.
     */
    public function test_deactivated_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'disabled@apexmedia.com',
            'password' => Hash::make('password123'),
            'role' => 'editor',
            'is_active' => false,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'disabled@apexmedia.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * 17. Password reset workflow works.
     */
    public function test_password_reset_workflow_works(): void
    {
        $admin = User::where('role', 'admin')->first();
        $targetUser = User::where('role', 'contributor')->first();

        $response = $this->actingAs($admin)->post(route('admin.users.resetPassword', $targetUser), [
            'password' => 'brandNewPassword123!',
            'password_confirmation' => 'brandNewPassword123!',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('brandNewPassword123!', $targetUser->fresh()->password));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'password_reset',
            'entity_type' => 'User',
            'entity_id' => $targetUser->id,
        ]);
    }

    /**
     * 18. Safe user deletion requires article reassignment.
     */
    public function test_user_deletion_safeguards_editorial_content(): void
    {
        $admin = User::where('role', 'admin')->first();
        $authorWithArticles = User::where('role', 'writer')->has('articles')->first();
        $articleId = $authorWithArticles->articles()->first()->id;

        // 1. Attempt delete without reassigning articles -> blocked
        $blockedResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $authorWithArticles));
        $blockedResponse->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $authorWithArticles->id]);

        // 2. Attempt delete with article reassignment to $admin -> succeeds and preserves articles
        $successResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $authorWithArticles), [
            'reassign_to' => $admin->id,
        ]);

        $successResponse->assertRedirect(route('admin.users.index'));
        $successResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $authorWithArticles->id]);

        // The article is preserved and now belongs to $admin
        $this->assertEquals($admin->id, Article::find($articleId)->user_id);
    }

    /**
     * 19. Bulk operations work for user activation and deactivation.
     */
    public function test_bulk_operations_work(): void
    {
        $admin = User::where('role', 'admin')->first();
        $u1 = User::factory()->create(['is_active' => true]);
        $u2 = User::factory()->create(['is_active' => true]);

        // Bulk deactivate
        $response = $this->actingAs($admin)->post(route('admin.users.bulk'), [
            'action' => 'deactivate',
            'user_ids' => [$u1->id, $u2->id],
        ]);

        $response->assertSessionHas('success');
        $this->assertFalse($u1->fresh()->is_active);
        $this->assertFalse($u2->fresh()->is_active);

        // Bulk activate
        $response2 = $this->actingAs($admin)->post(route('admin.users.bulk'), [
            'action' => 'activate',
            'user_ids' => [$u1->id, $u2->id],
        ]);

        $response2->assertSessionHas('success');
        $this->assertTrue($u1->fresh()->is_active);
        $this->assertTrue($u2->fresh()->is_active);
    }
}
