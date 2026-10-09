<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->decimal('trending_score', 12, 4)->default(0.0)->index()->after('view_count');
            $table->string('audio_url', 500)->nullable()->after('canonical_url');
            $table->string('audio_duration', 50)->nullable()->after('audio_url');
            $table->timestamp('audio_generated_at')->nullable()->after('audio_duration');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['trending_score', 'audio_url', 'audio_duration', 'audio_generated_at']);
        });
    }
};
