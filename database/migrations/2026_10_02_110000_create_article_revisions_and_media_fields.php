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
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'featured_image_alt')) {
                $table->string('featured_image_alt')->nullable()->after('featured_image_caption');
            }
            if (!Schema::hasColumn('articles', 'sponsor_url')) {
                $table->string('sponsor_url')->nullable()->after('sponsored_by');
            }
        });

        if (!Schema::hasTable('article_revisions')) {
            Schema::create('article_revisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->unsignedInteger('version')->default(1);
                $table->string('change_summary')->nullable();
                $table->timestamps();

                $table->index(['article_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_revisions');

        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'featured_image_alt')) {
                $table->dropColumn('featured_image_alt');
            }
            if (Schema::hasColumn('articles', 'sponsor_url')) {
                $table->dropColumn('sponsor_url');
            }
        });
    }
};
