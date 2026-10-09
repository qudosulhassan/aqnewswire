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
        Schema::create('rankings_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. "The World's Billionaires", "30 Under 30"
            $table->string('slug')->unique();
            $table->integer('year')->default(2026);
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('status')->default('published');
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        Schema::create('ranking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rankings_list_id')->constrained('rankings_lists')->cascadeOnDelete();
            $table->unsignedInteger('rank');
            $table->string('name');
            $table->string('title_or_role')->nullable();
            $table->string('company')->nullable();
            $table->string('net_worth_or_metric')->nullable(); // e.g. "$214 B" or "Valuation: $10B"
            $table->string('industry')->nullable();
            $table->string('country')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_items');
        Schema::dropIfExists('rankings_lists');
    }
};
