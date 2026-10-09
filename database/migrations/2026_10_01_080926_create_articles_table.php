<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption')->nullable();
            
            // Editorial pipeline & statuses: draft, submitted, published, scheduled, archived
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('scheduled_at')->nullable();
            
            // Metrics & badges
            $table->unsignedInteger('reading_time_minutes')->default(2);
            $table->unsignedBigInteger('view_count')->default(0)->index();
            $table->boolean('is_breaking')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_trending')->default(false)->index();
            $table->boolean('is_editors_pick')->default(false)->index();
            $table->boolean('is_premium')->default(false);
            
            // Monetization & disclosures
            $table->string('sponsored_by')->nullable();
            $table->text('affiliate_disclosure')->nullable();
            
            // SEO & Social
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
