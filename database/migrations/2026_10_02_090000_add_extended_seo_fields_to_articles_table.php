<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'robots')) {
                $table->string('robots', 50)->nullable()->after('canonical_url');
            }
            if (!Schema::hasColumn('articles', 'og_title')) {
                $table->string('og_title')->nullable()->after('robots');
            }
            if (!Schema::hasColumn('articles', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title');
            }
            if (!Schema::hasColumn('articles', 'og_image')) {
                $table->string('og_image')->nullable()->after('og_description');
            }
            if (!Schema::hasColumn('articles', 'twitter_title')) {
                $table->string('twitter_title')->nullable()->after('og_image');
            }
            if (!Schema::hasColumn('articles', 'twitter_description')) {
                $table->text('twitter_description')->nullable()->after('twitter_title');
            }
            if (!Schema::hasColumn('articles', 'twitter_image')) {
                $table->string('twitter_image')->nullable()->after('twitter_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $columns = [
                'robots',
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
                'twitter_image',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('articles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
