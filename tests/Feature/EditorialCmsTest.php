<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditorialCmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_editor_can_create_article(): void
    {
        $admin = User::where('role', 'admin')->first();
        $category = Category::first();

        $response = $this->actingAs($admin)->post('/admin/articles', [
            'title' => 'Quantum Advantage in Commercial Cloud Deployments',
            'subtitle' => 'How enterprise data centers integrate topological qubits',
            'category_id' => $category->id,
            'excerpt' => 'A breakthrough in commercial quantum computing.',
            'content' => '<p>The race for quantum computing advantage has crossed commercial thresholds.</p>',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $response->assertRedirect('/admin/articles');
        $this->assertDatabaseHas('articles', [
            'title' => 'Quantum Advantage in Commercial Cloud Deployments',
            'status' => 'published',
        ]);
    }

    public function test_newsletter_subscription(): void
    {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'executive.investor@example.com',
            'topics' => 'Markets & AI',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'executive.investor@example.com',
        ]);
    }
}
