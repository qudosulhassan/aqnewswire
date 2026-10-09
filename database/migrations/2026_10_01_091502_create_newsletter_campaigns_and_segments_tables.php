<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Segments
        Schema::create('newsletter_segments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('criteria_type')->default('all'); // all, active_only, specific_topics
            $table->json('criteria_payload')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Templates
        Schema::create('newsletter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subject')->nullable();
            $table->longText('html_content');
            $table->timestamps();
        });

        // Campaigns
        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->foreignId('template_id')->nullable()->constrained('newsletter_templates')->nullOnDelete();
            $table->foreignId('segment_id')->nullable()->constrained('newsletter_segments')->nullOnDelete();
            $table->longText('content');
            // draft, test, scheduled, sending, sent
            $table->string('status')->default('draft')->index();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('total_opened')->default(0);
            $table->unsignedInteger('total_clicked')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletter_templates');
        Schema::dropIfExists('newsletter_segments');
    }
};
